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

class GeofactoryTableGgmap extends JTable{
	public function __construct(&$_db){
		$this->checked_out_time = $_db->getNullDate();
		parent::__construct('#__geofactory_ggmaps', 'id', $_db);
	}
	
	public function bind($array, $ignore = ''){
		if (isset($array['params_map_cluster']) && is_array($array['params_map_cluster'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_map_cluster']);
			$array['params_map_cluster'] = (string) $registry;
		}

		if (isset($array['params_map_radius']) && is_array($array['params_map_radius'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_map_radius']);
			$array['params_map_radius'] = (string) $registry;
		}

		if (isset($array['params_additional_data']) && is_array($array['params_additional_data'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_additional_data']);
			$array['params_additional_data'] = (string) $registry;
		}

		if (isset($array['params_map_types']) && is_array($array['params_map_types'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_map_types']);
			$array['params_map_types'] = (string) $registry;
		}

		if (isset($array['params_map_controls']) && is_array($array['params_map_controls'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_map_controls']);
			$array['params_map_controls'] = (string) $registry;
		}

		if (isset($array['params_map_settings']) && is_array($array['params_map_settings'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_map_settings']);
			$array['params_map_settings'] = (string) $registry;
		}

		if (isset($array['params_map_mouse']) && is_array($array['params_map_mouse'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params_map_mouse']);
			$array['params_map_mouse'] = (string) $registry;
		}

		// parametres reserve
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

		$this->alias = JApplication::stringURLSafe($this->alias);
		if (empty($this->alias)) {
			$this->alias = JApplication::stringURLSafe($this->name);
		}

		return true;
	}
	
	public function importFromOldGF($old){
		// base data
		$this->name 		= $old->title ;
		$this->template		= $old->introduction ;
		$this->template = str_replace('[', 		'{', $this->template) ;
		$this->template = str_replace(']', 		'}', $this->template) ;
		$this->template = str_replace('&lt;', 	'<', $this->template) ;
		$this->template = str_replace('&gt;', 	'>', $this->template) ;
		$this->template = str_replace('&quot;', '"', $this->template) ;

		$this->extrainfo	= "Backend map description..." ;
		$this->alias		= '' ;
		$this->mapwidth		= "width: ".$old->width.' '.$old->unitW ; // les espaces sont importants pour l'explode
		$this->mapheight	= "height: ".$old->height.' '.$old->unitH ; // les espaces sont importants pour l'explode
		$this->totalmarkers	= $old->totalmarkers ;
		$this->centerlat	= $old->centerlat ;
		$this->centerlng	= $old->centerlong ;
		$this->state		= $old->public ;
		
		$params_map_cluster 	= array('useCluster'=>$old->useCluster,
										'clusterZoom'=>$old->clusterZoom,
										'gridSize'=>$old->gridSize,
										'imagePath'=>$old->imagePath,
										'imageSizes'=>$old->imageSizes,
										'minimumClusterSize'=>$old->minimumClusterSize) ;

		$params_map_radius 		= array('drawCircle'=>$old->drawCircle,
										'frontDistSelect'=>$old->frontDistSelect,
										'fe_rad_unit'=>$old->fe_rad_unit,
										'acCountry'=>$old->acCountry,
										'useBrowserRadLoad'=>$old->useBrowserRadLoad,
										'acTypes'=>$old->acTypes) ;

		$params_additional_data = array('kml_file'=>$old->kml_file,
										'layers'=>$old->layers) ;

		$params_map_types 		= array('mapControl'=>$old->mapControl,
										'mapTypeBar'=>$old->mapTypeBar,
										'mapTypeAvailable'=>$old->mapTypeAvailable,
										'mapTypeOnStart'=>$old->mapTypeOnStart,
										'tiles'=>$old->tiles) ;

		$params_map_controls 	= array('mapsZoom'=>$old->mapsZoom,
										'centerUser'=>$old->centerUser,
										'minZoom'=>$old->minZoom, 
										'maxZoom'=>$old->maxZoom, 
										'mapTypeControl'=>$old->mapTypeControl,
										'pegman'=>$old->pegman,
										'scaleControl'=>$old->scaleControl,
										'rotateControl'=>$old->rotateControl,
										'overviewMapControl'=>$old->overviewMapControl) ;

		$params_map_settings 	= array('allowDbl'=>$old->allowDbl,
										'randomMarkers'=>$old->randomMarkers,
										'useRoutePlaner'=>$old->useRoutePlaner,
										'cacheTime'=>$old->cacheTime,
										'mapStyle'=>$old->mapStyle) ;

		$params_map_mouse 		= array('doubleClickZoom'=>$old->doubleClickZoom, 
										'wheelZoom'=>$old->wheelZoom, 
										'bubbleOnOver'=>$old->bubbleOnOver,
										'clickRadius'=>$old->clickRadius,
										'salesRadMode'=>$old->salesRadMode,
										'trackOnOver'=>$old->trackOnOver,
										'trackZoom'=>$old->trackZoom) ;

		$registry = new JRegistry;
		$registry->loadArray($params_map_cluster);
		$this->params_map_cluster = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_map_radius);
		$this->params_map_radius = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_additional_data);
		$this->params_additional_data = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_map_types);
		$this->params_map_types = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_map_controls);
		$this->params_map_controls = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_map_settings);
		$this->params_map_settings = (string) $registry;

		$registry = new JRegistry;
		$registry->loadArray($params_map_mouse);
		$this->params_map_mouse = (string) $registry;
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

