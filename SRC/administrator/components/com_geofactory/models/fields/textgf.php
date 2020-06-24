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
JFormHelper::loadFieldClass('text');

class JFormFieldTextGF extends JFormFieldText {
	protected $type = 'textgf';


	protected function getInput() {
		$prefix =  $this->element['prefix'] ;
		$suffix =  $this->element['suffix'] ;

		$prefix =  $prefix?'<span class="add-on">'.$prefix.'</span>':'' ;
		$suffix =  $suffix?'<span class="add-on">'.$suffix.'</span>':'' ;
	
		$html = array() ;
		$html[] = '<div class="input-prepend input-append">';
		$html[] = 	$prefix;
		$html[] = 	parent::getInput() ;
		$html[] = 	$suffix;
		$html[] = '</div>';

		return implode($html) ;
	}
}

