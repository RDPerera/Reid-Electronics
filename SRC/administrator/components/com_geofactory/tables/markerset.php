<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/helpers/geofactory.php';

class GeofactoryTableMarkerset extends JTable{
	// variable de travail, non sauvée dans cette table, mais dans la table de liens
	var $idmaps = null ;

	public function __construct(&$_db){
		$this->checked_out_time = $_db->getNullDate();
		parent::__construct('#__geofactory_markersets', 'id', $_db);
	}
	
	public function bind($array, $ignore = ''){
		// variable supplémentaire de travail, si pas un array,
		if (isset($array['idmaps']) )
			$this->idmaps = $array['idmaps'] ;

		if (isset($array['params_markerset_settings']) && is_array($array['params_markerset_settings'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_markerset_settings']);
			$array['params_markerset_settings'] = (string) $registry;
		}
 
		if (isset($array['params_markerset_radius']) && is_array($array['params_markerset_radius'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_markerset_radius']);
			$array['params_markerset_radius'] = (string) $registry;
		}
 
		if (isset($array['params_markerset_icon']) && is_array($array['params_markerset_icon'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_markerset_icon']);
			$array['params_markerset_icon'] = (string) $registry;
		}
 
		if (isset($array['params_markerset_type_setting']) && is_array($array['params_markerset_type_setting'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_markerset_type_setting']);
			$array['params_markerset_type_setting'] = (string) $registry;
		}

		if (isset($array['params_extra']) && is_array($array['params_extra'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_extra']);
			$array['params_extra'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check function
	 *
	 * @return	boolean
	 * @see		JTable::check
	 * @since	1.5
	 */
	public function check(){
		$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);

		// ordering
		if ($this->state < 0) {
			// order a 0 si archivéou corbeille
			$this->ordering = 0;
		} elseif (empty($this->ordering)) {
			// Set ordering to last if ordering was 0
			$this->ordering = self::getNextOrder($this->_db->quoteName('id').'=' . $this->_db->Quote($this->id).' AND state>=0');
		}

		return true;
	}

	// sauve et met les cartes dans le link talbe 
	public function store($updateNulls = false){
		parent::store($updateNulls);

		// INFO : if (empty($this->id)) -> pour savoir si je fait un update ..
		$this->_addMarkersetToLink() ;

		return count($this->getErrors()) == 0;
	}

	protected function _addMarkersetToLink(){
		// si on vient du form, on sauve, sinon (par exemple lors du ordering) on ne s'occupe pas de ca (ca reste)
		if (! isset($this->idmaps))
			return ;

		// pour chaque map, on créer les records
		$vals = array() ;
		if (!is_array($this->idmaps) OR !count($this->idmaps))
			return ;

		$this->_deleteLink() ;
		
		foreach($this->idmaps as $id){
			if ($id<1)
				continue ;
			$vals[] = "({$this->id},{$id})";
		}

		if (!count($vals))
			return ;

		$this->_db->setQuery("INSERT INTO #__geofactory_link_map_ms (id_ms,id_map) VALUES ".implode(',',$vals));
		$this->_db->execute();
	}

	protected function _deleteLink(){
		$this->_db->setQuery("DELETE FROM #__geofactory_link_map_ms WHERE id_ms={$this->id}");
		$this->_db->execute();
	}

	public function delete($pk = null){
		$this->_deleteLink() ;
		return parent::delete($pk);
	}

	public function importFromOldMS($old, $idM){
		// base data
		$this->name 			= $old->setname ;
		$this->template_bubble	= $old->bubble_template ;
		$this->template_bubble = str_replace('[', 		'{', $this->template_bubble) ;
		$this->template_bubble = str_replace(']', 		'}', $this->template_bubble) ;
		$this->template_bubble = str_replace('&lt;', 	'<', $this->template_bubble) ;
		$this->template_bubble = str_replace('&gt;', 	'>', $this->template_bubble) ;
		$this->template_bubble = str_replace('&quot;', 	'"', $this->template_bubble) ;

		$this->template_sidebar	= $old->sidebar_template ;
		$this->template_sidebar = str_replace('[', 		'{', $this->template_sidebar) ;
		$this->template_sidebar = str_replace(']', 		'}', $this->template_sidebar) ;
		$this->template_sidebar = str_replace('&lt;', 	'<', $this->template_sidebar) ;
		$this->template_sidebar = str_replace('&gt;', 	'>', $this->template_sidebar) ;
		$this->template_sidebar = str_replace('&quot;', '"', $this->template_sidebar) ;

		$this->state			= 1 ;	// le seul moyen de les desactiver était de mettre le map a 0 et dans ce cas il ne serai pas ici
		$this->extrainfo		= "Backend markerset description ..." ; // nouveau concept
		$this->ordering			= $old->ordering ;

		// essaie de trouver le markerset corrspendans
		$this->typeList			= (isset($old->section) AND $old->section>0)?$old->typeList."-".$old->section:$old->typeList ; 

		// essaie de trover le assign par defaut
		$vType = GeofactoryHelperAdm::getArrayObjAssign($this->typeList) ;
		$assign = 0 ;// ! je ne sais pas ce que cela donnera si reste default
		if (is_array($vType) AND count($vType)>0)
			$assign = $vType[0]->value;

		// changement de valeurs (afin de pouvoir avoir la vraie valeur de calcul)
		$acc = 0 ; // def
		switch($old->accuracy){
			case 3: $acc = 5 ; break ;
			case 1: $acc = 25 ; break ;
			case 2: $acc = 75 ; break ;
			case 3: $acc = 150 ; break ;
		}

		$params_markerset_settings 		= array('allow_groups'=>$old->allow_groups,
												'accuracy'=>$acc,
												'j_menu_id'=>$old->j_menu_id,
												'field_assignation'=>$assign,
												'bubblewidth'=>$old->bubblewidth
		) ;

		$params_markerset_radius 		= array('rad_distance'=>$old->rad_distance,
												'rad_unit'=>$old->rad_unit, 
												'rad_mode'=>$old->rad_mode
		) ;

		$params_markerset_icon	 		= array('markerIconType'=>$old->markerIconType,
												'customimage'=>$old->marker,
												'avatarSizeW'=>$old->avatarSizeW,
												'avatarSizeH'=>$old->avatarSizeH,
												'mapicon'=>$old->mapicon
		) ;

		$params_markerset_type_setting	= array('filter'=>$old->filter
		) ;

		// Spéciphique aux diverses extensions
		$params_specific = array();
		if ($this->typeList=="MS_CB"){
			$params_markerset_type_setting['field_title']=$old->field_title ;
			$params_markerset_type_setting['include_groups']=$old->include_groups ;
			$params_markerset_type_setting['salesRadField']=$old->salesRadField ;
			$params_markerset_type_setting['onlyOnline']=$old->onlyOnline ;
			$params_markerset_type_setting['onlineTmp']=$old->onlineTmp ;
			$params_markerset_type_setting['offlineTmp']=$old->offlineTmp ;
		}
		if ($this->typeList=="MS_JS"){
			$params_markerset_type_setting['field_title']=$old->field_title ;
			$params_markerset_type_setting['include_groups']=$old->include_groups ;
			$params_markerset_type_setting['salesRadField']=$old->salesRadField ;
			$params_markerset_type_setting['onlyOnline']=$old->onlyOnline ;
			$params_markerset_type_setting['onlineTmp']=$old->onlineTmp ;
			$params_markerset_type_setting['offlineTmp']=$old->offlineTmp ;
		}
		if ($this->typeList=="MS_S2"){
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
			$params_markerset_type_setting['salesRadField']=$old->salesRadField ;
			$params_markerset_type_setting['catAuto']=$old->catAuto ;
		}
		if ($this->typeList=="MS_MT"){
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
			$params_markerset_type_setting['salesRadField']=$old->salesRadField ;
			$params_markerset_type_setting['catAuto']=$old->catAuto ;
		}
		if ($this->typeList=="MS_JSEV"){
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
		}
		if ($this->typeList=="MS_SP"){
			$params_markerset_icon['avatarImage']=$old->avatarImage ;
			$params_markerset_icon['section']=$old->section ;
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
			$params_markerset_type_setting['salesRadField']=$old->salesRadField ;
			$params_markerset_type_setting['catAuto']=$old->catAuto ;
			$params_markerset_type_setting['filter_opt']=$old->filter_opt ;
		}
		if ($this->typeList=="MS_AM"){
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
			$params_markerset_type_setting['salesRadField']=$old->salesRadField ;
			$params_markerset_type_setting['catAuto']=$old->catAuto ;
		}
		if ($this->typeList=="MS_JE"){
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
			$params_markerset_type_setting['dateFormat']=$old->dateFormat ;
			$params_markerset_type_setting['allEvents']=$old->allEvents ;
		}
		if ($this->typeList=="MS_JC"){
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
			$params_markerset_type_setting['catAuto']=$old->catAuto ;
		}
		if ($this->typeList=="MS_GT"){
			$params_markerset_type_setting['include_categories']=$old->include_categories ;
			$params_markerset_type_setting['linesOwners']=$old->pline ;
			$params_markerset_type_setting['dateFormat']=$old->dateFormat ;
		}

		$registry = new JRegistry;
		$registry->loadArray($params_markerset_settings);
		$this->params_markerset_settings = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_markerset_radius);
		$this->params_markerset_radius = (string) $registry;
		
		$registry = new JRegistry;
		$registry->loadArray($params_markerset_icon);
		$this->params_markerset_icon = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_markerset_type_setting);
		$this->params_markerset_type_setting = (string) $registry;
	}
	
	// assigne la carte au markerset
	public function bindMapMarkerset($newMapId){
		$db = $this->getDbo();
		$db->setQuery("INSERT INTO #__geofactory_link_map_ms (id_ms,id_map) VALUES ({$this->id},{$newMapId})");
		$db->execute();
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed	An optional array of primary key values to update.  If not
	 *					set the instance property value is used.
	 * @param   integer The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer The user id of the user performing the operation.
	 * @return  boolean  True on success.
	 * @since   1.0.4
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE '.$this->_db->quoteName($this->_tbl).
			' SET '.$this->_db->quoteName('state').' = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);

		try
		{
			$this->_db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
}