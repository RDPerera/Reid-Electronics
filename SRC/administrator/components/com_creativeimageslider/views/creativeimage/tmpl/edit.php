<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: default.php 2012-04-05 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Image Slider
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

$document = JFactory::getDocument();

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'creativeimage.cancel') {
		submitform( task );
	}
	else {
		if (form.name.value != ""){
			form.name.style.border = "1px solid green";
		} 
		
		if (form.name.value == ""){
			form.name.style.border = "1px solid red";
			form.name.focus();
		} 
		else {
			submitform( task );
		}
	}
}

//admin forever
var req = false;
function refreshSession() {
    req = false;
    if(window.XMLHttpRequest && !(window.ActiveXObject)) {
        try {
            req = new XMLHttpRequest();
        } catch(e) {
            req = false;
        }
    // branch for IE/Windows ActiveX version
    } else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                req = false;
            }
        }
    }

    if(req) {
        req.onreadystatechange = processReqChange;
        req.open("HEAD", "<?php echo JURI::base();?>", true);
        req.send();
    }
}

function processReqChange() {
    // only if req shows "loaded"
    if(req.readyState == 4) {
        // only if "OK"
        if(req.status == 200) {
            // TODO: think what can be done here
        } else {
            // TODO: think what can be done here
            //alert("There was a problem retrieving the XML data: " + req.statusText);
        }
    }
}
setInterval("refreshSession()", <?php echo $timeout = intval(JFactory::getApplication()->getCfg('lifetime') * 60 / 3 * 1000);?>);
</script>
<?php if(true) {//////////////////////////////////////////////////////////////////////////////////////Joomla3.x/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<?php 
function cis_hex2rgb($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	return implode(",", $rgb); // returns the rgb values separated by commas
	//return $rgb; // returns an array with the rgb values
}

//add scripts, styles
$document = JFactory::getDocument();

$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/colorpicker.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/layout.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/creative_buttons.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/creativecss-ui.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/../components/com_creativeimageslider/assets/css/main.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/creativelib.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/creativelib-ui.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/colorpicker.js';
$document->addScript($jsFile);

echo '<style>
	.colorpicker input {
	background-color: transparent !important;
	border: 1px solid transparent !important;
	position: absolute !important;
	font-size: 10px !important;
	font-family: Arial, Helvetica, sans-serif !important;
	color: #898989 !important;
	top: 4px !important;
	right: 11px !important;
	text-align: right !important;
	margin: 0 !important;
	padding: 0 !important;
	height: 11px !important;
	outline: none !important;
	box-shadow: none !important;
	width: 32px !important;
	height: 12px !important;
	top: 2px !important;
}
.colorpicker_hex input {
width: 38px !important;
right: 6px !important;
}
.colorpicker {
z-index: 10000 !important;
}
</style>';

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//JHtml::_('formbehavior.chosen', 'select');

$db = JFactory::getDBO();
$query = "SELECT `height` FROM `#__cis_sliders` WHERE id = '".$this->item->id_slider."'";
$db->setQuery($query);
$item_height = $db->loadResult();

$query = "SELECT id,name FROM #__cis_sliders";
$db->setQuery($query);
$row = $db->loadAssocList();
$slider_options = array();
if(is_array($row))
	foreach($row as $arr)
	$slider_options[$arr["id"]] = $arr["name"];

//set global options
$slider_global_options = Array();
$slider_global_options["showreadmore"] = 1;
$slider_global_options["readmoretext"] = 'Read More!';
$slider_global_options["readmorestyle"] = 'red';
$slider_global_options["readmoreicon"] = 'pencil';
$slider_global_options["readmoresize"] = 'normal';
$slider_global_options["overlaycolor"] = '#000000';
$slider_global_options["overlayopacity"] = 50;
$slider_global_options["textcolor"] = '#ffffff';
$slider_global_options["overlayfontsize"] = 18;
$slider_global_options["textshadowcolor"] = '#000000';
$slider_global_options["textshadowsize"] = 2;
$slider_global_options["readmorealign"] = 1;
$slider_global_options["captionalign"] = 0;
$slider_global_options["readmoremargin"] = '0px 10px 10px 10px';
$slider_global_options["captionmargin"] = '10px 15px 10px 15px';
$slider_global_options['popup_open_event'] = 4;

//get slider globsl options
if($this->item->id != 0) {
	$query = "SELECT * FROM #__cis_sliders WHERE id = '".$this->item->id_slider."'";
	$db->setQuery($query);
	$slider_parent_options = $db->loadAssoc();
}

?>
<script type="text/javascript">
(function($) {
	$(document).ready(function() {
		//close preview
		$("#cis_preview_close").click(function() {
			$(this).parents('.preview_box').hide();
		});
		
		var top_offset = parseInt($(".preview_box").css('top'));
		//top_offset_moove = top_offset == 75 ? 75 : 100;
		top_offset_moove = 120;
		//animate preview
		$(window).scroll(function() {
			var off = $("#preview_dummy").offset().top;

			var off_0 = $("#c_div").offset().top;
			if(off > off_0 ) {
				delta = off - off_0 + top_offset_moove*1;
				$(".preview_box").stop(true).animate( {
					top: delta
				},500);
			}
			else {
				$(".preview_box").stop(true).animate( {
					top: top_offset
				},500);
			}
			
		});

		
		//colorpicker
		var active_element;
		$('.colorSelector').click(function() {
			active_element = $(this).parent('div');
		})
		
		$('.colorSelector').ColorPicker({
			onBeforeShow: function () {
				$color = $(active_element).find('input').val();
				$(this).ColorPickerSetColor($color);
			},
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				active_element.find('input').val('#' + hex);
				$(active_element).children('#colorSelector').children('div').css('backgroundColor', '#' + hex);

				cis_update_overlay_txt();
				cis_update_overlay_bg();

				//$("#ssw_template_wrapper").css('background-color','#' + hex);
			}
		});


		function cis_hexToRgb(hex) {
		    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		    return result ? parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) : null;
		};
		


		// textarea animation
		$("#cis_caption").focus(function() {
			$(this).stop(true,false).animate({
				'height': '250px'
			},200);
		});
		$("#cis_caption").blur(function() {
			$(this).stop(true,false).animate({
				'height': '35px'
			},200);
		});
	});
})(creativeJ);
</script>
<form action="<?php echo JRoute::_('index.php?option=com_creativeimageslider&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<div id="c_div">
		<div>
			<?php if(($this->max_id < 5) || ($this->item->id != 0)) {?>
			<fieldset>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<div style="clear: both;margin: 0px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MAIN_OPTIONS_LABEL' );?></div>
							<div class="cis_control_label"><label id="" for="cis_name" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_NAME_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_NAME_LABEL' );?><span class="star">&nbsp;*</span></label></div>
							<div class="cis_controls"><input type="text" name="name" id="cis_name" value="<?php echo $v = $this->item->id == 0 ? '' : $this->item->name;?>" class="inputbox" size="40" required="" aria-required="true" ></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_caption" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CAPTION_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CAPTION_LABEL' );?></label></div>
							<div class="cis_controls">
								<textarea name="caption" id="cis_caption" style="width: 215px;height: 35px;"><?php echo $v = $this->item->id == 0 ? '' : $this->item->caption;?></textarea>
							</div>
							
							<div style="clear: both;height: 7px;"></div>
							<?php $k = 0;?>
							<?php foreach($this->form->getFieldset() as $field): ?>
								<?php if($k == 0) {?>
								<div class="cis_control_label"><?php echo $field->label;?></div>
								<div class="cis_controls"><?php echo $field->input;?></div>
								<?php }?>
							<?php $k++; endforeach; ?>
							
							<div style="clear: both;height: 7px;"></div>
							<div class="cis_control_label"><label id="" for="cis_img_url" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_IMGURL_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_IMGURL_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="img_url" id="cis_img_url" value="<?php echo $v = $this->item->id == 0 ? '' : $this->item->img_url;?>" class="inputbox" size="40" required="" ></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_id_slider" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$slider_id_get = isset($_GET["filter_slider_id"]) ? (int)$_GET["filter_slider_id"] : 1;
									$default = $this->item->id == 0 ? $slider_id_get : $this->item->id_slider;
									//$opts = array(1 => 'Published', 0 => 'Unpublished');
									$opts = $slider_options;
									$options = array();
									echo '<select id="cis_id_slider" class="" name="id_slider">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_status" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_STATUS_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_STATUS_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? 1 : $this->item->published;
									$opts = array(1 => 'Published', 0 => 'Unpublished');
									$options = array();
									echo '<select id="cis_status" class="" name="published">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_OPTIONS_LABEL' );?></div>
							
							<div style="clear: both;height: 7px;"></div>
							<?php $k = 0;?>
							<?php foreach($this->form->getFieldset() as $field): ?>
								<?php if($k == 2) {?>
								<div class="cis_control_label"><?php echo $field->label;?></div>
								<div class="cis_controls"><?php echo $field->input;?></div>
								<?php }?>
							<?php $k++; endforeach; ?>

							<div style="clear: both;height: 7px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_img_url" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_IMGURL_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_IMGURL_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="popup_img_url" id="cis_popup_img_url" value="<?php echo $v = $this->item->id == 0 ? '' : $this->item->popup_img_url;?>" class="inputbox" size="40" required="" ></div>

							
							
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_BUTTON_LINK_OPTIONS_LABEL' );?></div>
							<div class="cis_control_label"><label id="" for="cis_redirect_url" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_REDIRECT_URL_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_REDIRECT_URL_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="redirect_url" id="cis_redirect_url" value="<?php echo $v = $this->item->id == 0 ? '#' : $this->item->redirect_url;?>" class="inputbox" size="40" required="" ></div>
							
							<div style="clear: both;height: 7px;"></div>
							<?php $k = 0;?>
							<?php foreach($this->form->getFieldset() as $field): ?>
								<?php if($k == 1) {?>
								<div class="cis_control_label"><?php echo $field->label;?></div>
								<div class="cis_controls"><?php echo $field->input;?></div>
								<?php }?>
							<?php $k++; endforeach; ?>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_redirect_target" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_REDIRECT_TARGET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_REDIRECT_TARGET_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? 0 : $this->item->redirect_target;
									$opts = array(0 => 'Same Window', 1 => 'New Window');
									$options = array();
									echo '<select id="cis_redirect_target" class="" name="redirect_target">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;"><?php echo JText::_('COM_CREATIVEIMAGESLIDER_PLEASE_UPGRADE_TO_HAVE_MORE_THAN_X_IMAGES');?></div>
					<div id="cpanel" style="float: left;">
					<div class="icon" style="float: right;">
					<a href="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" title="<?php echo JText::_('COM_CREATIVEIMAGESLIDER_PLEASE_UPGRADE_TO_HAVE_MORE_THAN_X_IMAGES');?>">
					<table style="width: 100%;height: 100%;text-decoration: none;">
					<tr>
					<td align="center" valign="middle">
					<img src="components/com_creativeimageslider/assets/images/shopping_cart.png" /><br />
											<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_BUY_PRO_VERSION' ); ?>
										</td>
									</tr>
								</table>
							</a>
						</div>
					</div>
					<div style="font-style: italic;font-size: 12px;color: #949494;clear: both;">Updrading to PRO is easy, and will take only <u style="color: rgb(44, 66, 231);font-weight: bold;">5 minutes!</u></div>
			<?php }?>
		</div>
	</div>
<input type="hidden" name="task" value="creativeimage.edit" />
<?php echo JHtml::_('form.token'); ?>
</form>
<?php include (JPATH_BASE.'/components/com_creativeimageslider/helpers/footer.php'); ?>
<?php }?>
<style>
				
</style>
