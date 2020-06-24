<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal', 'a.modal');
JHtml::_('jquery.framework');

// délai entre chaque geocode. A
$config = JComponentHelper::getParams('com_geofactory');
$client = (int) $config->get('geocodeClient', 0);
$delay 	= (int) $config->get('iPauseGeo', 2000000);
$delay 	= $delay / 1000 ; // incrémenté dans le js ... d'une milliseconde par item... ... et le premier est envoyé sans pause.
$jobEnd	= "<a class='btn btn-primary btn-large' href='index.php?option=com_geofactory&view=geocodes&assign={$this->assign}&typeliste={$this->type}'>".JText::_('COM_GEOFACTORY_GEOCODE_DONE')."</a>"  ;
$total  = 0 ;
$idsToGc= "" ;
if (is_array($this->idsToGc) && count($this->idsToGc)>0){
	$idsToGc	= implode(',', $this->idsToGc);
	$total  	= count($this->idsToGc) ;
}

$doc = JFactory::getDocument();
$doc->addStyleDeclaration('.map-canvas img {  max-width: none !important;}');

// si c'est client side, on augmente un peut le delay, car sinon il y a des appels ajax dans le success du ajax
$delayClient = 0 ;
if ($client)
	$delayClient = 500 ;

$ggApikey	= trim($config->get('ggApikey'));
$ggApikey 	= (strlen($ggApikey)>4)?"&key=".$ggApikey:'';
?>
<script src="https://maps.googleapis.com/maps/api/js?<?php echo $ggApikey; ?>"></script>
<script type="text/javascript">
jQuery(document).ready(function(){

	var needStop = false;
	jQuery('#button_stop').click(function() {
    	needStop = true;
	});

	initialize();
	geocodeJob(-1) ;

	function geocodeJob(item, toGc){
		toGc = [<?php echo $idsToGc; ?>];
		item = parseInt(item) + 1 ;

		setTimeout(function(){
			<?php 
				if($client) echo 'geocodeItemClient(item, toGc[item]);';
				else 		echo 'geocodeItemServer(item, toGc[item]);';
			?>


			
		}, <?php echo $delay ; ?> + parseInt(item) + <?php echo $delayClient ; ?>
		);
	}

	<?php 
		if ($client){
			echo "

				function geocodeItemClient(item, curid){
					if(needStop) {
						jQuery('#geocodeEnd').html(\"{$jobEnd}\");
						return;
					}
					// récupère l'adresse
					var urlAx			= 'index.php?option=com_geofactory&task=geocodes.getcurrentitemaddressraw' ;
					var arData 			= {} ;
					arData['cur'] 		= item;
					arData['curId'] 	= curid;
					arData['total'] 	= jQuery('#total').val() ;
					arData['type'] 		= jQuery('#type').val() ;
					arData['assign'] 	= jQuery('#assign').val() ;

					if (parseInt(item) >= parseInt(arData['total'])){
						jQuery('#geocodeEnd').html(\"{$jobEnd}\");
						return ;
					}

					// update le current dans le form
					jQuery('#currentIdx').val(item) ;
					jQuery.ajax({
						url: urlAx,
						data: arData,		
						success:function(data){
							var def = pos = new google.maps.LatLng(34,-41);
							var pos = def;

							if(data.length > 2){

								// geocode l'adresse
								geocoder = new google.maps.Geocoder();
								geocoder.geocode({ 'address': data }, function(results, status) {
									if (status == google.maps.GeocoderStatus.OK) {
										jQuery('#geocodeLog').html('Geocode ok');
										pos = results[0].geometry.location;

										drawGeocodeResult(def, pos);

										// sauve les coordonnées
										var urlAx			= 'index.php?option=com_geofactory&task=geocodes.axsavecoord' ;
										arData['savlat'] 	= pos.lat();
										arData['savlng'] 	= pos.lng();
										arData['savMsg'] 	= 'Save' ;
										arData['adresse'] 	= data ;

										jQuery.ajax({
											url: urlAx,
											data: arData,		
											success:function(data){
												jQuery('#geocodeLog').html(data);
											}
										});
									}
								});
							}
				
							geocodeJob(item);
						}
					});
				}";
		}else{
			echo "
				function geocodeItemServer(item, curid){
					if(needStop) {
						jQuery('#geocodeEnd').html(\"{$jobEnd}\");
						return;
					}

					// prépare les données à envoyer
					var urlAx			= 'index.php?option=com_geofactory&task=geocodes.geocodecurrentitem' ;
					var arData 			= {} ;
					arData['cur'] 		= item;
					arData['curId'] 	= curid;
					arData['total'] 	= jQuery('#total').val() ;
					arData['type'] 		= jQuery('#type').val() ;
					arData['assign'] 	= jQuery('#assign').val() ;

					if (parseInt(item) >= parseInt(arData['total'])){
						jQuery('#geocodeEnd').html(\"{$jobEnd}\");
						return ;
					}

					// update le current dans le form
					jQuery('#currentIdx').val(item) ;
					jQuery.ajax({
						url: urlAx,
						data: arData,		
						success:function(data){
							var def = pos = new google.maps.LatLng(34,-41);
							htmlRes = 'Unknown Ajax Error';
							data = data.split('#-@');
							if(data.length > 0){
								htmlRes = data[0];
							}
							if(data.length > 2){
								var pos = new google.maps.LatLng(data[1],data[2]);
								if (data[1]==255){
									pos = def;
								}else{
									drawGeocodeResult(def, pos);
								}
							}
				
							jQuery('#geocodeLog').html(htmlRes);
							geocodeJob(item);
						}
					});
				}";
		}
	?>


	function drawGeocodeResult(def, pos){
		if (! pos.equals(def)){
			var points = [def,pos];
			var pline = new google.maps.Polyline({
				path: points,
				geodesic: true,
				strokeColor: '#FF0000',
				strokeOpacity: 1.0,
				strokeWeight: 1
			});

			pline.setMap(map);
		}

		map.panTo(pos);
		var marker = new google.maps.Marker({
			map:map,
			draggable:false,
			animation: google.maps.Animation.DROP,
			position: pos
		});
	}
});


var map ;
function initialize() {
  var myLatlng = new google.maps.LatLng(34,-41);
  var mapOptions = {
    zoom: 4,
    center: myLatlng
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var origin = new google.maps.Marker({
    position: myLatlng,
    icon: {
      path: google.maps.SymbolPath.CIRCLE,
      scale: 10
    },
    draggable: false,
    map: map
  });
}
</script>


<h2><?php echo JText::_('COM_GEOFACTORY_GEOCODE_PROCESS') ;?></h2>
<form action="" method="post" name="adminForm"id="markerset-form" class="">
	<input type="hidden" name="currentIdx" 	id="currentIdx"	value="1">
	<input type="hidden" name="total" 		id="total" 		value="<?php echo $total ;?>">
	<input type="hidden" name="type" 		id="type" 		value="<?php echo $this->type ;?>">
	<input type="hidden" name="assign" 		id="assign"		value="<?php echo $this->assign ;?>">
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<div style="max-width:100%;height:500px;" id="map-canvas"></div>

<div id="geocodeLog"></div>
<div id="geocodeEnd"></div>

<input type="button" 		id="button_stop" name="button_stop"		value="Stop !">
