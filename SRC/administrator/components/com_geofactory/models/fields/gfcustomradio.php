<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */
defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');

class JFormFieldgfCustomRadio extends JFormFieldList {
	protected $type = 'gfCustomRadio';

	protected function getOptions() {
		$config 	= JComponentHelper::getParams('com_geofactory');
		$options 	= array() ;
		$typeList 	= $this->form->getValue("typeList") ;
		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();

		if ($this->fieldname=="custom_radio_1"){
			$lab = null ;
			$dispatcher->trigger('getCustomRadio_1', array($typeList, &$options, &$lab) );
			if ($lab) $this->element['label'] = $lab ;
		}

		if ($this->fieldname=="custom_radio_2"){
			$lab = null ;
			$dispatcher->trigger('getCustomRadio_2', array($typeList, &$options, &$lab) );
			if ($lab) $this->element['label'] = $lab ;
		}
 
		return $options ;
	}
}