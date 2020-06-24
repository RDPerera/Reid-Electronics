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

class JFormFieldFieldSelector extends JFormFieldList {
	protected $type = 'fieldSelector';

	protected function getOptions() {
		$config 	= JComponentHelper::getParams('com_geofactory');
		$all 		= (bool) $config->get('useAllFields');
		$options 	= array() ;
		$typeList 	= $this->form->getValue("typeList") ;

		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();

		// on est dans un profile ?
		if ($this->fieldname=="avatarImage")
			$dispatcher->trigger( 'getCustomFieldsImages', array($typeList, &$options) );

		// le vecteur est toujours vide, donc on cherche les champs de base
		if (count($options)<1)
			$dispatcher->trigger( 'getCustomFields', array($typeList, &$options, $all) );
	
		// on est dans un profile ?
		if ($this->default=="username" AND ($typeList=='MS_CB' OR $typeList=='MS_JS') ){
			array_unshift($options, JHtml::_('select.option', 'Username', 'Username')) ;
			array_unshift($options, JHtml::_('select.option', 'Name', 'Name')) ;
		}
 
		array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT'))) ;
		return $options ;
	}
}