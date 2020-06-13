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

class JFormFieldTypeListe extends JFormFieldList {
	protected $type = 'typeListe';

	protected function getOptions() {
		$options = GeofactoryHelperAdm::getArrayObjTypeListe() ;
		array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT'))) ;
		return $options ;
	}
}
