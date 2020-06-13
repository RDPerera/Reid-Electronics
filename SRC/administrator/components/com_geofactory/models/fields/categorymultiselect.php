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

class JFormFieldcategoryMultiSelect extends JFormFieldList {
	protected $type = 'categorymultiselect';

	//array_unshift($options, JHtml::_('select.option', '0', "-select-")) ;
	protected function getOptions() {
		JPluginHelper::importPlugin('geocodefactory');
		$dsp = JDispatcher::getInstance();
		$typeList = $this->form->getValue("typeList") ;
		$language = $this->form->getValue("language") ;
		$idTopParent = -1 ;

		$vTmp = array();
		$dsp->trigger('getAllSubCats', array($typeList, &$vTmp, &$idTopParent, $language));

		$vRes = array();
		if (count($vTmp)>5000){ // pour les gros site, on trie pas paretn/enfant sinon plante
			foreach ($vTmp as $category) {
	    		$vRes[] = JHTML::_('select.option', $category->catid, $category->title);
			}


		} else if(count($vTmp) > 0){
			GeofactoryPluginHelper::_getChildCatOf($vTmp, $idTopParent, $vRes, "");
		}
		return $vRes ;
	}
}
