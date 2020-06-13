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

class GeofactoryModelMarkerset extends JModelAdmin{

	protected function canDelete($record){
		if (!empty($record->id)) {
				if ($record->state != -2) {
					return;
				}
			$user = JFactory::getUser();

			return $user->authorise('core.delete', 'com_geofactory');
		}
	}

	protected function canEditState($record){
		$user = JFactory::getUser();

		return $user->authorise('core.edit.state', 'com_geofactory');
	}

	// Returns a reference to the a Table object, always creating it.
	public function getTable($type = 'Markerset', $prefix = 'GeofactoryTable', $config = array()){
		return JTable::getInstance($type, $prefix, $config);
	}

	// Method to get the record form.
	public function getForm($data = array(), $loadData = true){
		// Get the form.
		$form = $this->loadForm('com_geofactory.markerset', 'markerset', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// recherche les liste de fields.
		$typeliste = $form->getValue("typeList") ;
		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();
		$vFields = $dispatcher->trigger( 'getListFieldsMs', array($typeliste) );

		// prépare le formulaire pour le type de liste
		$usefields = null ;
		foreach($vFields as $fs){
			if (count($fs)!=2)
				continue ;

			if (strtolower($fs[0])==strtolower($typeliste)){
				// trouvé les champs
				$usefields = $fs[1] ;
				break ;
			}
		}

		$allfields = array() ;
		if (is_array($usefields)){
			// ajoute encore des fields communs à tous mais qui sont dans les onglets de type
			$usefields[] = "customimage" ;
			$usefields[] = "avatarSizeW" ;
			$usefields[] = "avatarSizeH" ;
			$usefields[] = "mapicon" ;
			$usefields[] = "maxmarkers" ;

			// recherche tous les champs dispos ...
			foreach ($form->getFieldset("markerset-icon") as $field) {
				$allfields[] = $field->fieldname; 
			}
			foreach ($form->getFieldset("markerset-type-settings") as $field) {
				$allfields[] = $field->fieldname; 
			}

			// ... afin de ne garder que les utilisés
			foreach($allfields as $af){
				if (!in_array($af, $usefields)){
					// cache les champs non-demandés
					if (!$form->removeField($af, "params_markerset_type_setting") )
						echo "Unable to hide : {$af}<br />";
				} 
			}
		}

		return $form;
	}

	// Method to get the data that should be injected in the form.
	protected function loadFormData(){
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_geofactory.edit.markerset.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	// Prepare and sanitise the table data prior to saving.
	protected function prepareTable($table){
		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
	}

	// Method to get a single record.
	public function getItem($pk = null){
		// Convert the params field to an array.
		if ($item = parent::getItem($pk)){
			$registry = new JRegistry;
			$registry->loadString($item->params_markerset_settings);
			$item->params_markerset_settings = $registry->toArray();

			$registry = new JRegistry;
			$registry->loadString($item->params_markerset_radius);
			$item->params_markerset_radius = $registry->toArray();

			$registry = new JRegistry;
			$registry->loadString($item->params_markerset_icon);
			$item->params_markerset_icon = $registry->toArray();

			$registry = new JRegistry;
			$registry->loadString($item->params_markerset_type_setting);
			$item->params_markerset_type_setting = $registry->toArray();
		}

		// charge les cartes car elles viennent d'une autre table
		$item->idmaps= GeofactoryHelperAdm::getArrayMapsFromMs($item->id) ; 

		return $item;
	}

	// Method to save the form data.
	public function save($data){
		// nettoie le cache
		GeofactoryHelperAdm::delCacheFiles($data['id']);

		return parent::save($data);
	}
}
