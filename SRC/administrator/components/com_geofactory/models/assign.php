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

class GeofactoryModelAssign extends JModelAdmin{

	public function getTable($type = 'Assign', $prefix = 'GeofactoryTable', $config = array()){
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true){
		// Get the form.
		$form = $this->loadForm('com_geofactory.assign', 'assign', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		$typeliste = $form->getValue("typeList") ;
		// si pas en train de charger, mais en train de sauver
		if (!$loadData) 
			$typeliste = $data["typeList"] ;

		// recherche les liste de fields.
		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();
		$vFields = $dispatcher->trigger( 'getListFieldsAssign', array($typeliste) );

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
			// recherche tous les champs dispos ...
			foreach ($form->getFieldset("assign-address") as $field) {
				$allfields[] = $field->fieldname; 
			}

			// ... afin de ne garder que les utilisés
			foreach($allfields as $af){
				if (!in_array($af, $usefields)){
					// cache les champs non-demandés
					$form->removeField($af) ;
				} 
			}
		}

		return $form;
	}

	protected function loadFormData(){
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_geofactory.edit.assign.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	protected function prepareTable($table){
		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
	}
}
