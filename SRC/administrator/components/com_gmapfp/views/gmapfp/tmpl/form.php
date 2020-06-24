<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_49F
	* Creation date: Mai 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access');

$editor = JFactory::getEditor();
$config = JComponentHelper::getParams('com_gmapfp'); 
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.modal_jform_article');

$_lat = $this->config->get('gmapfp_centre_lat');
$_lng = $this->config->get('gmapfp_centre_lng');
$_zoom = $this->config->get('gmapfp_zoom_admin');
if (empty($_lat)) {$_lat = 47.927385663;};
if (empty($_lng)) {$_lng = 2.1437072753;};
if (empty($_zoom)) {$_zoom = 10;};

?>
<link rel="stylesheet" href="components/com_gmapfp/views/general.css" type="text/css" /> 

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'gmapfp.cancel') {
			Joomla.submitform(task, document.getElementById("item-form"));		
			return;
		}

		<?php
			echo $editor->save( 'text_message' )."\n";
			echo $editor->save( 'text_horaires_prix' )."\n";
		?>
		if (document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById("item-form"));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
			return false;
		}
	}
</script>

<script language="javascript" type="text/javascript">
    var geocoder;
    var map;
    var marker1;

    function init() {
		UpdateAddress();
		geocoder = new google.maps.Geocoder();
        
		var lat, lng, zoom_carte;
        if(document.adminForm.glat.value!=0) lat = document.adminForm.glat.value;
        else lat = <?php echo $_lat?>;
        if(document.adminForm.glng.value!=0) lng = document.adminForm.glng.value;
        else lng = <?php echo $_lng?>;
        if(document.adminForm.gzoom.value!=0) zoom_carte = parseInt(document.adminForm.gzoom.value);
        else zoom_carte = <?php echo $_zoom?>;

		var latlng = new google.maps.LatLng(lat, lng);
		var myOptions = {
		  zoom: zoom_carte,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		map = new google.maps.Map(document.getElementById("map"), myOptions);

	  google.maps.event.addListener(map, "bounds_changed", function() {
		   document.adminForm.gzoom.value = map.getZoom();
	  });

      // Create a draggable marker which will later on be binded to a
      marker1 = new google.maps.Marker({
          map: map,
          position: new google.maps.LatLng(lat, lng),
          draggable: true,
          title: 'Drag me!'
      });
	  google.maps.event.addListener(marker1, "drag", function() {
		document.adminForm.glat.value = marker1.getPosition().lat();
		document.adminForm.glng.value = marker1.getPosition().lng();
	  });
    }

    // Register an event listener to fire when the page finishes loading.
    google.maps.event.addDomListener(window, 'load', init);
 
  
    function showAddress() {
		var address = document.adminForm.localisation.value;
		if (geocoder) {
			geocoder.geocode( { 'address' : address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
				  map.setCenter(results[0].geometry.location);
				  marker1.setPosition(results[0].geometry.location); 
					document.adminForm.glat.value = results[0].geometry.location.lat();
					document.adminForm.glng.value = results[0].geometry.location.lng();
				} else {
				  alert(address + " not found for the following reason: " + status);
				}
			})
		}
    }

    function getCoordinate() {
		var lat, lng;
        if(document.adminForm.glat.value!=0) lat = document.adminForm.glat.value;
        else lat = <?php echo $_lat?>;
        if(document.adminForm.glng.value!=0) lng = document.adminForm.glng.value;
        else lng = <?php echo $_lng?>;
        if(document.adminForm.gzoom.value!=0) zoom_carte = parseInt(document.adminForm.gzoom.value);
        else zoom = <?php echo $_zoom?>;

		var latlng = new google.maps.LatLng(lat, lng);
		map.setZoom(zoom_carte);
		map.setCenter(latlng);
		marker1.setPosition(latlng); 
    }
	
	function changeDisplayImage(chemin) {
		if (document.adminForm.img.value !='') {
			document.adminForm.imagelib.src=chemin + document.adminForm.img.value;
		} else {
			document.adminForm.imagelib.src=chemin+'blank/blank.png';
		}
	}

	function changeDisplayIcon(chemin) {
		if (document.adminForm.icon.value !='') {
			document.adminForm.imageicon.src='<?php echo JURI::root()?>'+'images/gmapfp/icons/' + document.adminForm.icon.value;
		} else {
			document.adminForm.imageicon.src='<?php echo JURI::root()?>'+'images/gmapfp/blank/blank.png';
		}
	}

    function addphoto(file, indice){
        var optX = new Option(file, file);
        var selX = document.forms[0].elements['img'];
        var lenghX = selX.length;
        selX.options[lenghX] = optX;
                selX.options[lenghX].selected = true;
    }

	function UpdateAddress(){
 		document.adminForm.localisation.value = document.adminForm.adresse.value + " " + document.adminForm.adresse2.value + " " + document.adminForm.codepostal.value + " " + document.adminForm.ville.value + " " + document.adminForm.departement.value + ", " + document.adminForm.pay.value;	
	}

	function IsReal(id){
		MonNombre=document.getElementById(id).value;
		if(isNaN(MonNombre))
		{
			alert("\"" + MonNombre + "\" <?php echo JText::_( 'GMAPFP_PAS_NOMBRE' ); ?>");
			return false;
		}	
		return true;
	}

	function jSelectArticle_id_name(id, title, catid, object, url, language) {
		document.getElementById('id_id').value = id;
		document.getElementById('id_name').value = title;
		SqueezeBox.close();
	}

	function jResetArticle_id_name() {
		document.getElementById("id_id").value = "";
		document.getElementById("id_name").value = "";
	}

</script>

<?php 
	//fonction dropfile
?>
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function ($){
		$('#dropzonefp').unbind();
		$('#dropzonefp').filedrop({
			fallback_id: 'upload_filefp_input',   // an identifier of a standard file input element, becomes the target of \"click\" events on the dropzone
			url: 'index.php?option=com_gmapfp&controller=dropfiles&task=upload_image&<?php echo JSession::getFormToken();?>=1', // upload handler, handles each file separately, can also be a function taking the file and returning a url
			paramname: 'image1',          // POST parameter name used on serverside to reference file, can also be a function taking the filename and returning the paramname
			maxfiles: 1,
			maxfilesize: 20,    // max file size in MBs
			queuefiles: 1,
			data: {
				module_id : function(){
					return $('input[name=id]').val(); 
				}
			},
			error: function(err, file) {
				switch(err) {
					case 'BrowserNotSupported':
						bootbox.alert(Joomla.JText._('GMAPFP_BROWSER_NOT_SUPPORT_HTML5'));
						break;
					case 'TooManyFiles':
						// user uploaded more than 'maxfiles'
						bootbox.alert(Joomla.JText._('GMAPFP_TOO_MANY_FILES') + ' !');
						break;
					case 'FileTooLarge':
						// program encountered a file whose size is greater than 'maxfilesize'
						// FileTooLarge also has access to the file which was too large
						// use file.name to reference the filename of the culprit file
						bootbox.alert(file.name + Joomla.JText._('GMAPFP_FILE_TOO_LARGE') + ' !');
						break;
					case 'FileTypeNotAllowed':
						// The file type is not in the specified list 'allowedfiletypes'
						bootbox.alert(file.name + Joomla.JText._('GMAPFP_FILE_TYPE_NOT_ALLOWED') + ' !');
						break;
					case 'FileExtensionNotAllowed':
						// The file extension is not in the specified list 'allowedfileextensions'
						bootbox.alert(file.name + Joomla.JText._('GMAPFP_EXTENSION_TYPE_NOT_ALLOWED') + ' !');
						break;
					default:
						break;
				}
			},
			dragOver: function() {
				$(this).css('border', '3px dashed red');
				// user dragging files over #dropzone
			},
			dragLeave: function() {
				$(this).css('border', '3px dashed #BBBBBB');
				// user dragging files out of #dropzone
			},
			drop: function() {
				// user drops file
				$(this).css('border', '3px dashed #BBBBBB');
			},
			uploadStarted: function(i, file, len){
				// a file began uploading
				// i = index => 0, 1, 2, 3, 4 etc
				// file is the actual file of the index
				// len = total files user dropped
				var bar = $('<div class="progress progress-striped active">'+
								'<div class="bar"></div>'+
							'</div>');
				$('#gmapfp_image').append(bar);
				
				var preview = $('#gmapfp_image');
				var image = $('img', preview);

				var reader = new FileReader();

				reader.onload = function(e){
						// e.target.result holds the DataURL which
						image.attr('src',e.target.result);
				};

				// Reading the file as a DataURL. When finished,
				// this will trigger the onload function above:
				reader.readAsDataURL(file);

				preview.appendTo('#gmapfp_image');
				// Associating a preview container
				// with the file, using jQuery's $.data():

				$.data(file,preview);
			},
			uploadFinished: function(i, file, response, time) {
				// response is the data you got back from server in JSON format.
				var preview = $('#gmapfp_image');
				var image = $('img', preview);
				if (response.response != true) {
					bootbox.alert(response.datas);
					if ($('#img').val()) {
						image.attr('src','<?php echo JURI::root(); ?>images/gmapfp/' + $('#img').val());
					} else {
						image.attr('src','<?php echo JURI::root(); ?>images/gmapfp/blank/blank.png');
					}
					preview.appendTo('#gmapfp_image');
				} else {
					$('<option />', {val: response.datas, text: file.name}).appendTo($('#img'))
					$('#img option[value="' + response.datas + '"]').attr('selected', 'selected');
					// $('#img').trigger('chosen:updated'); //nouvelle methode
					$('#img').val(response.datas).trigger('liszt:updated'); //ancienne methode
					image.attr('src','<?php echo JURI::root(); ?>images/gmapfp/' + response.datas);
					preview.appendTo('#gmapfp_image');
				}
			},
			progressUpdated: function(i, file, progress) {
				// this function is used for large files and updates intermittently
				// progress is the integer value of file being uploaded percentage to completion
				$.data(file).find('.progress .bar').width(progress+'%');
			},
			beforeEach: function(file) {
				// file is a file object
				// return false to cancel upload
				if(!file.type.match(/^image\//)){
					bootbox.alert(Joomla.JText._('GMAPFP_ONLY_IMAGE_ALLOWED') + ' !');
					return false;
				}
			},
			afterAll: function() {
				// runs after all files have been uploaded or otherwise dealt with
				$('#dropzonefp .progress').delay(300).fadeIn(300).hide(300, function(){
				  $(this).remove();
				});
			}
		});
		jQuery('#upload_filefp_button').on('click',function(){
			jQuery('#upload_filefp_input').trigger('click');
			return false;
		});
	});

</script>

<form action="index.php" method="post" name="adminForm" id="item-form" class="gmapfp form-validate">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'GMAPFP_DETAILS' ); ?></legend>
		<div class="row-fluid">
			<div class="span6">
				<table class="admintable">
					<tr>
						<td class="control-label">
							<label for="title">
								<?php echo JText::_( 'GMAPFP_NOM' ); ?>:<span class="star">&nbsp;*</span>
							</label>
						</td>
						<td class="controls">
							<input class="inputbox required" type="text" name="nom" id="nom" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->nom); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="alias">
								<?php echo JText::_( 'JFIELD_ALIAS_LABEL' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="250" value="<?php echo $this->gmapfp->alias;?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label>
								<?php echo JText::_( 'JCATEGORY' ); ?>:<span class="star">&nbsp;*</span>
							</label>
						</td>
						<td class="controls">
							<?php
								echo $this->lists['catid'];
							?>
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<?php echo JText::_( 'JPUBLISHED' ); ?>:
						</td>
						<td class="controls">
							<fieldset class="radio btn-group">
								<label for="published0" id="published0-lbl" class="radio"><?php echo JText::_( 'JNO' ); ?></label>
								<input type="radio" name="published" id="published0" value="0"  <?php if (!$this->gmapfp->published) echo 'checked="checked"' ?>>
								<label for="published1" id="published1-lbl" class="radio"><?php echo JText::_( 'JYES' ); ?></label>
								<input type="radio" name="published" id="published1" value="1"  <?php if ($this->gmapfp->published) echo 'checked="checked"' ?>>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="alias">
								<?php echo JText::_( 'GMAPFP_ADRESSE' ); ?> 1:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="adresse" id="adresse" onchange="UpdateAddress();" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->adresse); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="alias">
								<?php echo JText::_( 'GMAPFP_ADRESSE' ); ?> 2:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="adresse2" id="adresse2" onchange="UpdateAddress();" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->adresse2); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_CODEPOSTAL' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="codepostal" id="codepostal" onchange="UpdateAddress();" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->codepostal); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_VILLE' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="ville" id="ville" onchange="UpdateAddress();" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->ville); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_DEPARTEMENT' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="departement" id="departement" onchange="UpdateAddress();" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->departement); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_PAYS' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="pay" id="pay" onchange="UpdateAddress();" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->pay); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_TEL' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="tel" id="tel" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->tel); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_TEL2' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="tel2" id="tel2" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->tel2); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_FAX' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="fax" id="fax" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->fax); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_EMAIL' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="email" id="email" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->email); ?>" />
						</td>
					</tr>
					<tr>
						<td class="control-label">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_SITE_WEB' ); ?>:
							</label>
						</td>
						<td class="controls">
							<input class="inputbox" type="text" name="web" id="web" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->web); ?>" />
						</td>
					</tr>
				</table>
			</div>
			<div class="span6">
				<div id="dropzonefp">
						<label for="title">
							<?php echo JText::_('GMAPFP_IMAGE'); ?>:
						</label>
						<label class="drop_info" style="cursor:default;">
							<?php echo JText::_('GMAPFP_DROP_ZONE_IMAGE'); ?>
						</label>
						<div id="gmapfp_image" style="overflow:auto;">
						<?php 
							$directory		= JURI::root().'images/gmapfp/';
							$javascript		= 'onchange="changeDisplayImage('."'".$directory."'".');"';

							if ((stristr($this->gmapfp->img,'bmp'))||(stristr($this->gmapfp->img,'gif'))||(stristr($this->gmapfp->img,'jpg'))||(stristr($this->gmapfp->img,'jpeg'))||(stristr($this->gmapfp->img,'png'))) {
								?>
								<img src="<?php echo $directory.$this->gmapfp->img; ?>" name="imagelib"/>
								<?php
							} else {
								?>
								<img src="<?php echo $directory; ?>/blank/blank.png" name="imagelib"/>
								<?php
							}
							echo '</div>';
							echo '<div>';
							echo $chemin	= $this->config->get('gmapfp_chemin_img');
							echo JHTML::_('list.images', 'img', $this->gmapfp->img, $javascript, $chemin, "bmp|gif|jpg|jpeg|png"  );
						?>
							<br />
							<input type="file" id="upload_filefp_input" multiple="">
							<a href="" id="upload_filefp_button" class="btn btn-primary"><i class="icon-picture"></i>&nbsp;<?php echo '&nbsp;&nbsp;&nbsp;'.JText::_('GMAPFP_UPLOAD') ?></span></a>
						</div>
				</div>
			</div>
		</div>
		<div class="span12">
			<table class="admintable">
				<tr>
					<td width="110" class="key">
						<label for="title">       
							<?php echo JText::_('GMAPFP_MAJ_ADRESSE'); ?>:
						</label>
					</td>
					<td valign="top">
						<input type="text" style="width:70%" name="localisation" value="" /><input class="btn" type="button" onclick="showAddress();" value="<?php echo JText::_('GMAPFP_CHERCHER'); ?>" />
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="title">
							<?php echo JText::_('GMAPFP_LAT'); ?> - <?php echo JText::_('GMAPFP_LON'); ?> - Zoom:
						</label>
					</td>
					<td valign="top">
						<input class="inputbox validate-numeric" onblur="IsReal('glat');" type="text" name="glat" id="glat" size="20" value="<?php echo $this->gmapfp->glat ?>" />
						<input class="inputbox validate-numeric" onblur="IsReal('glng');" type="text" name="glng" id="glng" size="20" value="<?php echo $this->gmapfp->glng ?>" />
						<input class="inputbox validate-numeric" onblur="IsReal('gzoom');" type="text" name="gzoom" id="gzoom" size="2" value="<?php echo $this->gmapfp->gzoom ?>" />
						<input class="btn" type="button" onclick="getCoordinate();" value="<?php echo JText::_('GMAPFP_CHERCHER_COORDONNEES'); ?>" />
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="title">
							<?php echo JText::_('GMAPFP_CARTE'); ?>:
						</label>
					</td>
					<td>
						<div id="map" style="width: 100%; height: 500px; overflow:hidden;"></div>
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<label for="marker"><?php echo JText::_( 'GMAPFP_MARKER' ); ?>:</label>
					</td>
					<td>
						<table>
							<tr>
							<?php 
								$cnt = 0;
								foreach($this->marqueurs as $marqueur) {
									$checked = '';
									if (($this->gmapfp->marqueur == $marqueur->url) || (empty($this->gmapfp->marqueur) && $marqueur->id == '1')) { $checked = 'checked="checked"'; }
									echo '<td width="40" align="center" valign="top" style="border:1px solid #eeeeee"><img src="'.$marqueur->url.'" title="'.$marqueur->nom.'" /><br /><input type="radio" name="marqueur" id="marqueur" value="'.$marqueur->url.'" '.$checked.' /></td>';
									if ($cnt < 15) {
										$cnt++;
									} else {
										echo '</tr><tr>';
										$cnt = 0;
									}
								}
							?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="title">
						<?php echo JText::_( 'GMAPFP_MESSAGE' ); ?>:
						</label>
					</td>
					<td valign="top" class="inputbox">
						<?php
						echo $editor->display( 'text_message', htmlspecialchars($this->gmapfp->text, ENT_COMPAT, 'UTF-8'), '100%', '300', '75', '20', true, 'text_message');
						?>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="title">
						<?php echo JText::_( 'GMAPFP_HORAIRES_PRIX' ); ?>:
						</label>
					</td>
					<td valign="top" class="inputbox">
						<?php
						echo $editor->display( 'text_horaires_prix', htmlspecialchars($this->gmapfp->horaires_prix, ENT_COMPAT, 'UTF-8'), '100%', '200', '75', '20', false,'text_horaires_prix');
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="ordering">
							<?php echo JText::_( 'JFIELD_ORDERING_LABEL' ); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="alias">
							<?php echo JText::_( 'JFIELD_META_DESCRIPTION_LABEL' ); ?>:
						</label>
					</td>
					<td>
						<textarea class="inputbox" name="metadesc" id="metadesc" cols="70" rows="4"><?php echo $this->gmapfp->metadesc; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="alias">
							<?php echo JText::_( 'JFIELD_META_KEYWORDS_LABEL' ); ?>:
						</label>
					</td>
					<td>
						<textarea class="inputbox" name="metakey" id="metakey" cols="70" rows="4"><?php echo $this->gmapfp->metakey; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">&nbsp;
						
					</td>
					<td style="text-align:left" class="key">
						<?php echo JText::_( 'GMAPFP_EXTERNE' ); ?>:
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="lag">
							<?php echo JText::_( 'GMAPFP_LINK' ); ?>:
						</label>
					</td>
					<td>
						<span class="input-append">
							<input class="inputbox" type="text" name="text_link" id="id_name" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->link); ?>" />
							<a class="btn modal_jform_article" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: 800, y: 500}, url:'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;<?php echo JSession::getFormToken(); ?>=1&amp;function=jSelectArticle_id_name'})">
								<i class="icon-list icon-white"></i>
								<?php echo JText::_( 'GMAPFP_SELECT_ARTICLE' ); ?>
							</a>
							<a class="btn hasTooltip"  href="javascript:;" onClick="jResetArticle_id_name()" data-placement="bottom" title="<?php echo JText::_( 'JSEARCH_RESET' ); ?>"><i class="icon-remove">&nbsp;</i></a>
						<input type="hidden" id="id_id" name="article_id" value="<?php echo $this->gmapfp->article_id; ?>" />                
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label>
							<?php echo JText::_('GMAPFP_ICON'); ?>:
						</label>
					</td>
					<td valign="center"">
						<?php 
							$path_icon = "/images/gmapfp/icons/";
							$javascript		= 'onchange="changeDisplayIcon('."'".$path_icon."'".');"';
							echo "<div>".$path_icon."</div>";
							echo $lists		= JHTML::_('list.images', 'icon', $this->gmapfp->icon, $javascript, $path_icon, "bmp|gif|jpg|jpeg|png"  );
							if ((stristr($this->gmapfp->icon,'bmp'))||(stristr($this->gmapfp->icon,'gif'))||(stristr($this->gmapfp->icon,'jpg'))||(stristr($this->gmapfp->icon,'jpeg'))||(stristr($this->gmapfp->icon,'png'))) {
								?>
								<img src="<?php echo JURI::root().$path_icon.$this->gmapfp->icon; ?>" name="imageicon" style="height:32px"/>
								<?php
							} else {
								?>
								<img src="<?php echo JURI::root(); ?>images/gmapfp/blank/blank.png" name="imageicon" style="height:32px"/>
								<?php
							}
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label>
							<?php echo JText::_( 'GMAPFP_ICON_LABEL' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="icon_label" id="icon_label" size="60" value="<?php echo str_replace('"', '&quot;',$this->gmapfp->icon_label); ?>" />
					</td>
				</tr>
			</table>
		</div>
	</fieldset>
</div>
<div class="clr"></div>
<div class="clr"></div>

<input type="hidden" name="userid" value="<?php echo $this->gmapfp->userid; ?>" />
<input type="hidden" name="option" value="com_gmapfp" />
<input type="hidden" name="id" value="<?php echo $this->gmapfp->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="gmapfp" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<div class="copyright" align="center">
	<br />
	<?php echo JText::_( 'GMAPFP_COPYRIGHT' );?>
</div>
