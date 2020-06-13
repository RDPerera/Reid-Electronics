<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.99pro
	* Creation date: Juillet 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class JFormFieldGMapFPMap extends JFormField
{
	public $type = 'GMapFPMap';

	protected function getInput()
	{
        $lang = JFactory::getLanguage(); 
        $tag_lang=(substr($lang->getTag(),0,2)); 
		$http = substr(JUri::base(), 0, strpos(JUri::base(), '://'));
					
		$params 	= JComponentHelper::getParams('com_gmapfp');
		$key 	= $params->get('gmapfp_google_key');

return '
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<script src="'.$http.'://maps.googleapis.com/maps/api/js?language='.$tag_lang.'&key='.$key.'" type="text/javascript"></script>
		<script src="'.$http.'://www.google.com/jsapi" type="text/javascript"></script><noscript>JavaScript must be enabled in order for you to use Google Maps. However, it seems JavaScript is either disabled or not supported by your browser. To view Google Maps, enable JavaScript by changing your browser options, and then try again.</noscript>
		<style>#map_div img {max-width : none !important;}</style>
		<fieldset style="height: 300px; width: 100%; overflow:hidden; " class="radio"><div id="map_div" style="height: 300px; width: 100%; overflow:hidden;"></div></fieldset>

		<script language="javascript" type="text/javascript">//<![CDATA[
			var map;
			var marker1;
		
			function init() {
				var lat, lng, zoom_carte, stylemap;
				if ( (e = document.getElementById("jform_gmapfp_centre_lat")))
					lat = e.value;
				if ( (e = document.getElementById("jform_gmapfp_centre_lng")))
					lng = e.value;
				if ( (e = document.getElementById("jform_gmapfp_zoom_admin")))
					zoom_carte = parseInt(e.value);
				if ( (e = document.getElementById("jform_gmapfp_type_admin")))
					stylemap = e.value;
				if ( (e = document.getElementById("jform_params_gmapfp_centre_lat")))
					lat = e.value;
				if ( (e = document.getElementById("jform_params_gmapfp_centre_lng")))
					lng = e.value;
				if ( (e = document.getElementById("jform_params_gmapfp_zoom_admin")))
					zoom_carte = parseInt(e.value);
				if ( isNaN(zoom_carte)) zoom_carte = 7;
				if ( (e = document.getElementById("jform_params_gmapfp_type_admin")))
					stylemap = e.value;
					
				if (lat == "") lat = 48;
				if (lng == "") lng = 2;

				var latlng = new google.maps.LatLng(lat, lng);
				var myOptions = {
				  zoom: zoom_carte,
				  center: latlng,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				};
		
				map = new google.maps.Map(document.getElementById("map_div"), myOptions);
				if (stylemap) map.setMapTypeId(stylemap);
		
			  google.maps.event.addListener(map, "bounds_changed", function() {
				if ( (e = document.getElementById("jform_gmapfp_zoom_admin")))
				   e.value = map.getZoom();
				if ( (e = document.getElementById("jform_params_gmapfp_zoom_admin")))
				   e.value = map.getZoom();
			  });
			  google.maps.event.addListener(map, "maptypeid_changed", function() {
				if ( (e = document.getElementById("jform_gmapfp_type_admin")))
				   e.value = map.getMapTypeId();
				if ( (e = document.getElementById("jform_params_gmapfp_type_admin")))
				   e.value = map.getMapTypeId();
			  });
			  // Create a draggable marker which will later on be binded to a
			  marker1 = new google.maps.Marker({
				  map: map,
				  position: new google.maps.LatLng(lat, lng),
				  draggable: true,
				  title: "Drag me!"
			  });
			  google.maps.event.addListener(marker1, "drag", function() {
				if ( (e = document.getElementById("jform_gmapfp_centre_lat")))
					e.value = marker1.getPosition().lat();
				if ( (e = document.getElementById("jform_gmapfp_centre_lng")))
					e.value = marker1.getPosition().lng();
				if ( (e = document.getElementById("jform_params_gmapfp_centre_lat")))
					e.value = marker1.getPosition().lat();
				if ( (e = document.getElementById("jform_params_gmapfp_centre_lng")))
					e.value = marker1.getPosition().lng();
			  });
			}
		
			// Register an event listener to fire when the page finishes loading.
			//google.maps.event.addDomListener(window, "load", init);
			google.setOnLoadCallback(initialize);

			var tstGMapFP = document.getElementById("map_div");
			var tstIntGMapFP;
			
			function CheckGMapFP() {
				if (tstGMapFP) {
					if (tstGMapFP.offsetWidth != tstGMapFP.getAttribute("oldValue")) {
						tstGMapFP.setAttribute("oldValue",tstGMapFP.offsetWidth);
						init();
					}
				}
			}
			
			function initialize() {
			   tstGMapFP.setAttribute("oldValue",0);
			   tstIntGMapFP = setInterval("CheckGMapFP()",500);
			}
		 
		//]]></script>
';

	}
}

?>