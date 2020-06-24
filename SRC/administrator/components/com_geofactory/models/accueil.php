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

class GeofactoryModelAccueil extends JModelAdmin{

	public function getTable($type = 'Assign', $prefix = 'GeofactoryTable', $config = array()){
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true){
		// Get the form.
		$form = $this->loadForm('com_geofactory.assign', 'assign', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData(){
	}

	protected function prepareTable($table){
	}
}
