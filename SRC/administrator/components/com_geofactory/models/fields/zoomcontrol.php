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

class JFormFieldZoomControl extends JFormField {
	protected $type = 'zoomControl';

	protected function getInput() {
		// si il a un centre de carte défini, autant prendre une carte qu'il connait
		$lat = strlen($this->form->getValue("centerlat"))>0?$this->form->getValue("centerlat"):'48.858915';
		$lng = strlen($this->form->getValue("centerlng"))>0?$this->form->getValue("centerlng"):'2.293833';

		$config = JComponentHelper::getParams('com_geofactory');
		$ggApikey =  strlen( $config->get('ggApikeySt') ) > 3 ? "&key=".$config->get('ggApikeySt') : "" ;
		
		$http 	= $config->get('sslSite');
		if(empty($http)) $http = (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS']))?"https://":"http://" ; 

		$src = $http."://maps.googleapis.com/maps/api/staticmap?" ;
		$src.= "center={$lat},{$lng}&size=300x200&maptype=hybrid{$ggApikey}&";
		$src.= "zoom=";
		$this->addJs($src) ;
		
		$imgattr	= array('id' => $this->id . '_preview', 'class' => 'media-preview', 'style' => 'cursor:pointer;width:30px;height:20px;', 'onClick'=>'updateSize(this);');
		$img		= JHtml::image($src.$this->value, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $imgattr);
		$img_id 	= $this->id. '_preview' ;

		$html = array() ;
		$html[] = '<div class="input-prepend input-append">';
		$html[] = '<input type="text" value="'.$this->value.'" class="btn-success input-mini validate-numeric"  name="'.$this->name.'" id="'.$this->id.'"> ' ;
		$html[] = '<input type="button" value="+" class="btn input-mini " name="" onClick="updateZoom(+1, '.$this->id.', '.$img_id.');"> ' ;
		$html[] = '<input type="button" value="-" class="btn input-mini " name="" onClick="updateZoom(-1, '.$this->id.', '.$img_id.');"> ' ;
		$html[] = '</div><br />';
		$html[] = $img;
		$html[] = ' <span class="small">click to enlarge/reduce</span>';
		
		return implode($html) ;
	}

	protected function addJs($src){
		$js=array() ;
		$js[] = "function updateSize(me){ ";		
		$js[] = "	if (me.style.width=='300px'){";
		$js[] = "		me.style.width='30px';";
		$js[] = "		me.style.height='20px';";
		$js[] = "	}else{";
		$js[] = "		me.style.width='300px';";
		$js[] = "		me.style.height='200px';";
		$js[] = "	}";
		$js[] = "}";

		$js[] = "function updateZoom(fact, id, img_id){";
		$js[] = "	var curZ = parseInt(document.id(id).value);";
		$js[] = "	var newZ = curZ + fact ;";
		$js[] = "	if (newZ < 1){	newZ = 1 ; }";
		$js[] = "	if (newZ > 25){	newZ = 25 ; }";

		$js[] = "	document.id(id).value = newZ ; ";
		$js[] = "	var img = document.id(img_id); ";
		$js[] = "	img.src = '{$src}'  + newZ;";

		$js[] = "}";

		GeofactoryHelperAdm::loadJsCode($js); 
	}
}