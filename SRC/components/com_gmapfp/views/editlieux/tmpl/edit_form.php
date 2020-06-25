<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_40F
	* Creation date: Avril 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

$editor = JFactory::getEditor();
$config = JFactory::getConfig();

$_lat = $this->params->get('gmapfp_centre_lat');
$_lng = $this->params->get('gmapfp_centre_lng');
$_zoom = $this->params->get('gmapfp_zoom');
if (empty($_lat)) {$_lat = 47.927385663;};
if (empty($_lng)) {$_lng = 2.1437072753;};
if (empty($_zoom)) {$_zoom = 10;};

if (!$this->items->id) $this->items->published = 1;

if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif;

?>
<script language="javascript" type="text/javascript">

	function validateForm( frm, my_task ) {
		var my_form = document.adminForm;
		if (my_task == 'cancel') {
			my_form.task.value = my_task;
			return;
		}

		<?php
			echo $editor->save( 'text_message' )."\n";
			echo $editor->save( 'text_horaires_prix' )."\n";
		?>
		if ((my_form.nom.value == "")||(my_form.catid.value == "")) {
			alert( "<?php echo JText::_( 'GMAPFP_CHAMPS_VIDE', true ); ?>" );
			return false;
		} else {
			my_form.task.value = my_task;
			my_form.submit();
			return true;
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
			document.adminForm.imagelib.src=chemin+'blank.png';
		}
	}

	function changeDisplayIcon(chemin) {
		if (document.adminForm.icon.value !='') {
			document.adminForm.imageicon.src='templates/bluestork/images/header/' + document.adminForm.icon.value;
		} else {
			document.adminForm.imageicon.src='<?php echo JURI::root().$config->get('gmapfp_chemin_img')?>'+'blank.png';
		}
	}

    function addphoto(file, indice){
        var optX = new Option(file, file);
        var selX = document.adminForm.elements['img'];
        var lenghX = selX.length;
        selX.options[lenghX] = optX;
                selX.options[lenghX].selected = true;
    }

	function jSelectArticle(id, title, object) {
		document.getElementById(object + '_id').value = id;
		document.getElementById(object + '_name').value = title;
		document.getElementById('sbox-window').close();
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

</script>

<?php 
	//fonction dropfile
?>
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function ($){
		$('#dropzonefp').unbind();
		$('#dropzonefp').filedrop({
			fallback_id: 'upload_filefp_input',   // an identifier of a standard file input element, becomes the target of \"click\" events on the dropzone
			url: 'index.php?option=com_gmapfp&controller=dropfiles&task=upload_image&<?php echo JSession::getFormToken();?>=1',              // upload handler, handles each file separately, can also be a function taking the file and returning a url
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

<form action="<?php echo JRoute::_('index.php?',false);//echo JRoute::_('index.php?option=com_gmapfp&view=editlieux&controller=editlieux',false); ?>" method="post" name="adminForm">
	<div style="height:70px">
    <div class="toolbar" id="toolbar" style="height:70px"> 
		<table class="toolbar"><tr> 
 
            <td> 
            <button name="save" class="button" onclick="return validateForm(this, 'save');" >
                <span class="icon-32-save" title="<?php echo JText::_( 'GMAPFP_SAVE' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'JSAVE' ).'&nbsp;'; ?>
            </button>
            </td> 
             
            <td> 
            <button name="apply" class="button"  onclick="return validateForm(this, 'apply');">
                <span class="icon-32-apply" title="<?php echo JText::_( 'GMAPFP_APPLIQUER' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'GMAPFP_APPLY' ).'&nbsp;'; ?>
            </button>
            </td> 
             
            <td>
            <button name="cancel" class="button" onclick="return validateForm(this, 'cancel');" >
                <span class="icon-32-cancel" title="<?php echo JText::_( 'GMAPFP_CANCEL' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'GMAPFP_CANCEL' ).'&nbsp;'; ?>
            </button>
            </td>
 
        </tr></table> 
    </div>
    </div>

<div id="gmapfp_submit">
	<fieldset class="form-horizontal">
		<div class="span12">
			<div class="control-group">
				<div class="control-label">
					<label for="title">
						<?php 
						echo JText::_( 'GMAPFP_NOM' );
						?>
					</label>
				</div>
				<div class="controls">
					<input class="inputbox" type="text" name="nom" id="nom" size="60" maxlength="200" value="<?php echo str_replace('"', '&quot;',$this->items->nom); ?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<label for="alias">
						<?php echo JText::_( 'JFIELD_ALIAS_LABEL' ); ?>
					</label>
				</div>
				<div class="controls">
					<input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="255" maxlength="250" value="<?php echo $this->items->alias;?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<label>
						<?php echo JText::_( 'JFIELD_CATEGORY_DESC' ); ?>
					</label>
				</div>
				<div class="controls">
					<?php
						echo $this->lists['catid'];
					?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo JText::_( 'JPUBLISHED' ); ?>
				</div>
				<div class="controls">
					<fieldset id="published_id" class="radio btn-group btn-group-yesno">
						<input type="radio" id="published_id0" name="published" value="1" <?php if ($this->items->published) echo 'checked="checked"';?>>
						<label for="published_id0" class="btn <?php if ($this->items->published) echo 'active btn-success"';?>"><?php echo JText::_('JYES');?></label>
						<input type="radio" id="published_id1" name="published" value="0" <?php if (!$this->items->published) echo 'checked="checked"';?>>
						<label for="published_id1" class="btn <?php if (!$this->items->published) echo 'active btn-danger"';?>"><?php echo JText::_('JNO');?></label>
					</fieldset>
				</div>
			</div>
		</div>
    </fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'GMAPFP_DETAILS' ); ?></legend>
		<div class="row-fluid">
			<div class="span6">
				<table class="admintable">
					<tr <?php if (@$this->params->get('gmapfpadresse1_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="alias">
								<?php echo JText::_( 'GMAPFP_ADRESSE' ); ?> 1
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="adresse" id="adresse" size="60" maxlength="200" value="<?php echo str_replace('"', '&quot;',$this->items->adresse); ?>" onchange="UpdateAddress()"/>
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfpadresse2_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="alias">
								<?php echo JText::_( 'GMAPFP_ADRESSE' ); ?> 2
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="adresse2" id="adresse2" size="60" maxlength="200" value="<?php echo str_replace('"', '&quot;',$this->items->adresse2); ?>" onchange="UpdateAddress()"/>
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_cp_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_CODEPOSTAL' ); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="codepostal" id="codepostal" size="60" maxlength="80" value="<?php echo str_replace('"', '&quot;',$this->items->codepostal); ?>" onchange="UpdateAddress();" />
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_ville_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_VILLE' ); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="ville" id="ville" size="60" maxlength="200" value="<?php echo str_replace('"', '&quot;',$this->items->ville); ?>" onchange="UpdateAddress();" />
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_departement_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_DEPARTEMENT' ); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="departement" id="departement" size="60" maxlength="200" value="<?php echo str_replace('"', '&quot;',$this->items->departement); ?>" onchange="UpdateAddress()"/>
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_pays_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_PAYS' ); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="pay" id="pay" size="60" maxlength="200" value="<?php echo str_replace('"', '&quot;',$this->items->pay); ?>" onchange="UpdateAddress()"/>
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_tel_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php 
								echo JText::_( 'GMAPFP_TEL' );
								?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="tel" id="tel" size="60" maxlength="30" value="<?php echo str_replace('"', '&quot;',$this->items->tel); ?>" />
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_tel2_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php 
								echo JText::_( 'GMAPFP_TEL' );
								?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="tel2" id="tel2" size="60" maxlength="30" value="<?php echo str_replace('"', '&quot;',$this->items->tel2); ?>" />
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_fax_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php 
								echo JText::_( 'GMAPFP_FAX' );
								?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="fax" id="fax" size="60" maxlength="20" value="<?php echo str_replace('"', '&quot;',$this->items->fax); ?>" />
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_email_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_EMAIL' ); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="email" id="email" size="60" maxlength="100" value="<?php echo str_replace('"', '&quot;',$this->items->email); ?>" />
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_web_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="lag">
								<?php echo JText::_( 'GMAPFP_SITE_WEB' ); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="web" id="web" size="60" maxlength="200" value="<?php echo str_replace('"', '&quot;',$this->items->web); ?>" />
						</td>
					</tr>
				</table>
			</div>
			<div class="span6">
				<div id="dropzonefp">
					<label for="title">
						<?php echo JText::_('GMAPFP_IMAGE'); ?>
					</label>
					<label class="drop_info" style="cursor:default;">
						<?php echo JText::_('GMAPFP_DROP_ZONE_IMAGE'); ?>
					</label>
					<div id="gmapfp_image" style="overflow:auto;">
					<?php 
						$directory		= JURI::base().$this->params->get('gmapfp_chemin_img');
						$javascript		= 'onchange="changeDisplayImage('."'".$directory."'".');"';

						if ((stristr($this->items->img,'bmp'))||(stristr($this->items->img,'gif'))||(stristr($this->items->img,'jpg'))||(stristr($this->items->img,'jpeg'))||(stristr($this->items->img,'png'))) {
							?>
							<img src="<?php echo $directory.$this->items->img; ?>" name="imagelib" />
							<?php
						} else {
							?>
							<img src="<?php echo $directory; ?>blank/blank.png" name="imagelib" />
							<?php
						}
						echo '</div>';
						echo '<div>';
						$chemin	= $this->params->get('gmapfp_chemin_img');
						echo $lists		= JHTML::_('list.images', 'img', $this->items->img, $javascript, $chemin, "bmp|gif|jpg|jpeg|png"  );
					?>
						<br />
						<input type="file" id="upload_filefp_input" multiple="">
						<a href="" id="upload_filefp_button" class="btn btn-primary"><i class="icon-picture"></i>&nbsp;<?php echo '&nbsp;&nbsp;&nbsp;'.JText::_('GMAPFP_UPLOAD') ?></span></a>
					</div>
				</div>
			</div>
			<div class="span12">
				<table>
				   <?php $adresselocalisation = "".$this->items->adresse." ".$this->items->adresse2." ".$this->items->codepostal." ".$this->items->ville." ".$this->items->pay.""; ?>
					<tr>
						<td style="width:100px;" class="key">
							<label for="title">       
								<?php echo JText::_('GMAPFP_MAJ_ADRESSE'); ?>
							</label>
						</td>
						<td valign="top">
							<input type="text" style="width:90%" name="localisation" value=<?php echo ('"'.str_replace('"', '&quot;',$adresselocalisation).'"'); ?> /><input type="button" onclick="showAddress();" class="button" value="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" />
						</td>
					</tr>
					<tr>
						<td style="width:100px;" class="key">
							<label for="title">
								<?php echo JText::_('GMAPFP_LAT'); ?> - <?php echo JText::_('GMAPFP_LON'); ?> - Zoom:
							</label>
						</td>
						<td valign="top">
							<input class="inputbox" type="text" name="glat" id="glat" size="12" maxlength="20" value="<?php echo $this->items->glat ?>" />
							<input class="inputbox" type="text" name="glng" id="glng" size="12" maxlength="20" value="<?php echo $this->items->glng ?>" />
							<input class="inputbox" type="text" name="gzoom" id="gzoom" size="2" maxlength="2" value="<?php echo $this->items->gzoom ?>" />
							<input type="button" class="button" onclick="getCoordinate();" value="<?php echo JText::_('GMAPFP_CHERCHER_COORDONNEES'); ?>" />
						</td>
					</tr>
					<tr>
						<td style="width:100px;" class="key">
							<label for="title">
								<?php echo JText::_('GMAPFP_CARTE'); ?>
							</label>
						</td>
						<td>
							<div id="map" style="width:100%; height:<?php echo $this->params->get('gmapfp_height');?>px; overflow:hidden;"></div>
						</td>
					</tr>
					<tr>
						<td style="width:100px;" class="key">
							<label for="marker"><?php echo JText::_( 'GMAPFP_MARKER' ); ?></label>
						</td>
						<td>
							<table>
								<tr>
								<?php 
									$cnt = 0;
									$cnt2 = 0;
									foreach($this->marqueurs as $marqueur) {
										$checked = '';
										if (($this->items->marqueur == $marqueur->url) || (empty($this->items->marqueur) && $marqueur->id == '1')) { $checked = 'checked="checked"'; }
										echo '<td width="40" align="center" valign="top" style="border:1px solid #eeeeee"><img src="'.$marqueur->url.'" title="'.$marqueur->nom.'" /><br /><input type="radio" name="marqueur" id="marqueur" value="'.$marqueur->url.'" '.$checked.' /></td>';
										if ($cnt < 7) {
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
					<tr <?php if (@$this->params->get('gmapfp_message_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="title">
								<?php 
								echo JText::_( 'GMAPFP_MESSAGE' );
								?>
							</label>
						</td>
						<td valign="top" class="inputbox">
							<div id="Edit1" style="overflow:auto;">
								<?php
								if (GMAPFP_ANDROID) {
									echo '<textarea class="inputbox" rows="6" cols="" style="width:97%;" id="text_message" name="text_message">'.$this->items->text.'</textarea>';
								}else{
									echo $editor->display( 'text_message', $this->items->text, '100%', '300', '75', '20', false);
								}
								?>
							</div>
						</td>
					</tr>
					<tr <?php if (@$this->params->get('gmapfp_prix_view')){echo 'style="display: none; visibility: hidden;"';}?>>
						<td style="width:100px;" class="key">
							<label for="title">
								<?php 
									if (@$this->custom[11]->nom) {
										echo $this->custom[11]->nom;
									}else{
										echo JText::_( 'GMAPFP_HORAIRES_PRIX' );
									}
								?>
							</label>
						</td>
						<td valign="top" class="inputbox">
							<div id="Edit2" style="overflow:auto;">
								<?php
								if (GMAPFP_ANDROID) {
									echo '<textarea class="inputbox" rows="6" cols="" style="width:97%;" id="text_horaires_prix" name="text_horaires_prix">'.$this->items->horaires_prix.'</textarea>';
								}else{
									echo $editor->display( 'text_horaires_prix', $this->items->horaires_prix, '100%', '200', '75', '20', false);
								}
								?>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
    </fieldset>
    <fieldset class="adminform">
	<legend><?php echo JText::_( 'GMAPFP_MOTEUR' ); ?></legend>
	<table class="admintable" style="width:100%">
		<tr>
			<td style="width:100px;" class="key">
				<label for="alias">
					<?php echo JText::_( 'JFIELD_META_DESCRIPTION_LABEL' ); ?>
				</label>
			</td>
			<td>
				<textarea class="inputbox" name="metadesc" id="metadesc" cols="" rows="3" style="width:95%;"><?php echo $this->items->metadesc; ?></textarea>
			</td>
		</tr>
		<tr>
			<td style="width:100px;" class="key">
				<label for="alias">
					<?php echo JText::_( 'JFIELD_META_KEYWORDS_LABEL' ); ?>
				</label>
			</td>
			<td>
				<textarea class="inputbox" name="metakey" id="metakey" cols="" rows="3" style="width:95%;"><?php echo $this->items->metakey; ?></textarea>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<input type="hidden" name="option" value="com_gmapfp" />
<input type="hidden" name="id" value="<?php echo $this->items->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="editlieux" />
<?php echo JHTML::_( 'form.token' ); ?>
<form\>