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

class JFormFieldMarkerTemplate extends JFormField {
	protected $type = 'markerTemplate';

	protected function getInput() {
		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');
		JHtml::_('bootstrap.tooltip');
		$this->_addJs() ;

		$link = 'index.php?option=com_geofactory&amp;view=markerset&amp;layout=placeHolders&amp;tmpl=component&amp;type=' . $this->form->getValue("typeList");
		$html = "\n".'<div class="input-append"><a class="modal btn" title="'.JText::_('COM_GEOFACTORY_TEMPLATE_TOOLS').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-list hasTooltip" title="'.JText::_('COM_GEOFACTORY_TEMPLATE_TOOLS').'"></i> '.JText::_('JSELECT').'</a></div>'."\n";

		return $html;
	}

	protected function _addJs(){
		$js=array() ;
		$js[] = "function addCtrlInTpl(item, code){";
		$js[] =	"	if (code=='_b'){jInsertEditorText(item, 'jform_template_bubble') ;} "; 
		$js[] =	"	if (code=='_s'){jInsertEditorText(item, 'jform_template_sidebar') ;} "; 
		$js[] = "	SqueezeBox.close();";
		$js[] =	"}" ;

		$js[] =	"jQuery(document).ready(function(){" ;
		$js[] =	"	jQuery('#pan_buttons').hide();" ;
		$js[] =	"	jQuery('#tog_buttons').click(function(){" ;
		$js[] =	"		jQuery('#pan_buttons').slideToggle();" ;
		$js[] =	"	});" ;

		$js[] =	"	jQuery('#pan_sample').hide();" ;
		$js[] =	"	jQuery('#tog_sample').click(function(){" ;
		$js[] =	"		jQuery('#pan_sample').slideToggle();" ;
		$js[] =	"	});" ;
		$js[] =	"});";

		GeofactoryHelperAdm::loadJsCode($js); 
	}
}
