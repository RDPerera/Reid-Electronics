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

class JFormFieldBackendMap extends JFormField {
	protected $type = 'backendMap';

	// dessine une carte qui sait se mettre à jour en fonction de point centre et du zoom courant
	protected function getInput() {
		$this->_getBackendMap() ;
		$html = array() ;
		$html[] = '<input type="button" value="'.JText::_('COM_GEOFACTORY_LOAD_MAP').'" onclick="initialize();">' ;
		$html[] = '<div id="gf_admin_map_container" style="display:none;">' ;
		$html[] = ' <p>'.JText::_('COM_GEOFACTORY_BACKEND_HELP').'</p>' ;		
		$html[] = ' <input type="text" id="searchPos" />' ;
		$html[] = ' <div id="gf_admin_map" style="border:1px solid silver; max-width:50%!important; height:300px;" ></div>' ;
		$html[] = '</div>' ;
		return implode($html) ;
	}

	protected function _getBackendMap(){
		$config = JComponentHelper::getParams('com_geofactory');
		$document = JFactory::getDocument();
		$http 	= $config->get('sslSite');
		if(empty($http)) $http = (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS']))?"https://":"http://" ; 
		$ggApikey =  strlen( $config->get('ggApikey') ) > 3 ? "&key=".$config->get('ggApikey') : "" ;
		$mapLang = (strlen($config->get('mapLang'))>1)?'&language='.$config->get('mapLang'):'' ;
		$document->addCustomTag('<script type="text/javascript" src="'.$http.'maps.googleapis.com/maps/api/js?libraries=places'.$ggApikey.$mapLang.'"></script>');
		$document->addStyleDeclaration("#gf_admin_map img{max-width:none!important;}");

		// tente de retrouver la position du user (+/-)
		$lat = 0;
		$lng = 0;
		$czo = 12;

		$noFetchUserIp 	= $config->get('noFetchUserIp', 1); 
		if (function_exists('file_get_contents') AND ($noFetchUserIp==1)){
			$ip = $_SERVER['REMOTE_ADDR'];
			if (strlen($ip)>6){
				$json = '' ;
			    $url = "http://ipinfo.io/{$ip}/json";
				 
				$opts = array('http'=>array('timeout'=>1));
				$context = stream_context_create($opts);
				$json = file_get_contents($url,false,$context);

				if (strlen($json)>2){
					$details = json_decode($json);
					if ((strlen($details->city)>3) AND (strlen($details->loc)>3)){
						$coor = explode(',', $details->loc);
						if (is_array($coor) AND (count($coor)==2)){
							$lat = $coor[0];
							$lng = $coor[1];
						}
					}
				}
			}
		}

		if (($lat+$lng)==0)
			$czo = 10;

		$js = "
var map;
function initialize(){
	jQuery('#gf_admin_map_container').show();
	var cla='{$lat}';
	var clo='{$lng}';
	var czo={$czo};
	
	var ula=jQuery('#{$this->form->getField('centerlat')->id}').val();
	var ulo=jQuery('#{$this->form->getField('centerlng')->id}').val();
	var uzo=jQuery('#jform_params_map_controls_mapsZoom').val();

	if (ula.length>0){cla=ula;}
	if (ulo.length>0){clo=ulo;}

	var cm=new google.maps.LatLng(cla,clo) ;
	var mapOptions = {zoom:parseInt(uzo),mapTypeId:google.maps.MapTypeId.ROADMAP,center:cm};
	map = new google.maps.Map(document.getElementById('gf_admin_map'),mapOptions);
	var marker = new google.maps.Marker({map:map,draggable:true,animation: google.maps.Animation.DROP,position:cm});
	map.setZoom(czo);

	var inp = document.getElementById('searchPos');
	var autocomplete = new google.maps.places.Autocomplete(inp);
	autocomplete.bindTo('bounds', map);

	google.maps.event.addListener(marker, 'dragend', function(event) {
		jQuery('#{$this->form->getField('centerlat')->id}').val(event.latLng.lat());
		jQuery('#{$this->form->getField('centerlng')->id}').val(event.latLng.lng());
		map.panTo(event.latLng);
	});	


	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		marker.setVisible(false);
		var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		// If the place has a geometry, then present it on a map.
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);  // Why 17? Because it looks good.
		}
		jQuery('#{$this->form->getField('centerlat')->id}').val(place.geometry.location.lat());
		jQuery('#{$this->form->getField('centerlng')->id}').val(place.geometry.location.lng());

		marker.setPosition(place.geometry.location);
		marker.setVisible(true);
	});
}
		";

		GeofactoryHelperAdm::loadJsCode($js); 
	}
}
