<?php
/**
 *
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 *
 */
defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');

class JFormFieldfieldGeoSelector extends JFormFieldList {
	protected $type = 'fieldGeoSelector';

	//	Ici pas d'option par defaut ... par exemple, pour SP, il doit choisir :
	//		- soit un des SP-Geo fields créés, 
	//		- soit l'option SP default qui est créeé par le plugin (simule un field)
	protected function getOptions() {
		$options = array() ;
		$typeList = $this->form->getValue("typeList") ;
		$singelGps = false ;

		$config 	= JComponentHelper::getParams('com_geofactory');
		$all 		= (bool) $config->get('useAllFields');

		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger( 'getIsSingleGpsField', array($typeList, &$singleGps));

		if ($singleGps AND $this->fieldname=="field_longitude"){
			array_unshift($options, JHtml::_('select.option', '-1', JText::_('COM_GEOFACTORY_NOT_NEEDED') )) ;
			return $options;
		}

		$dispatcher->trigger( 'getCustomFieldsCoord', array($typeList, &$options, $all));
 
		return $options ;
	}
}
