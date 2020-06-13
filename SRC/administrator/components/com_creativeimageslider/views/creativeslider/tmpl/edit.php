<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: edit.php 2012-04-05 14:30:25 svn $
 * @author 2GLux.com
 * @package Sexy Polling
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'creativeslider.cancel') {
		submitform( task );
	}
	else {
		if (form.jform_name.value != ""){
			form.jform_name.style.border = "1px solid green";
		} 
		
		if (form.jform_name.value == ""){
			form.jform_name.style.border = "1px solid red";
			form.jform_name.focus();
		} 
		else {
			submitform( task );
		}
	}
	
}
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

$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/mousewheel.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/creativeimageslider.js';
$document->addScript($jsFile);

if(JV == 'j2') {
	echo '<style>
.colorpicker {
z-index: 10000 !important;
}
	</style>';
}
else {
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
}

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//JHtml::_('formbehavior.chosen', 'select');

$db = JFactory::getDBO();

$query = "SELECT id,name FROM #__cis_categories";
$db->setQuery($query);
$row = $db->loadAssocList();
$cat_options = array();
if(is_array($row))
	foreach($row as $arr)
		$cat_options[$arr["id"]] = $arr["name"];

$query = "SELECT id,name FROM #__cis_templates";
$db->setQuery($query);
$row = $db->loadAssocList();
$tmp_options = array();
if(is_array($row))
	foreach($row as $arr)
		$tmp_options[$arr["id"]] = $arr["name"];

//set global options
$slider_global_options = Array();
$slider_global_options["showreadmore"] = 1;
$slider_global_options["readmoretext"] = 'View Image';
$slider_global_options["readmorestyle"] = 'blue';
$slider_global_options["readmoreicon"] = 'picture';
$slider_global_options["readmoresize"] = 'mini';
$slider_global_options["overlaycolor"] = '#000000';
$slider_global_options["overlayopacity"] = 50;
$slider_global_options["textcolor"] = '#ffffff';
$slider_global_options["overlayfontsize"] = 17;
$slider_global_options["textshadowcolor"] = '#000000';
$slider_global_options["textshadowsize"] = 2;
$slider_global_options["readmorealign"] = 1;
$slider_global_options["captionalign"] = 0;
$slider_global_options["readmoremargin"] = '0px 15px 10px 10px';
$slider_global_options["captionmargin"] = '10px 15px 10px 15px';

//slider options
$slider_global_options["height"] = 250;
$slider_global_options["itemsoffset"] = 2;
$slider_global_options["margintop"] = 0;
$slider_global_options["marginbottom"] = 0;
$slider_global_options["paddingtop"] = 2;
$slider_global_options["paddingbottom"] = 2;

$slider_global_options["showarrows"] = 1;//on hover
$slider_global_options["arrow_template"] = 26;
$slider_global_options["arrow_width"] = 28;
$slider_global_options["arrow_left_offset"] = 15;
$slider_global_options["arrow_center_offset"] = 0;
$slider_global_options["arrow_passive_opacity"] = 50;

$slider_global_options["move_step"] = 25;
$slider_global_options["move_time"] = 600;
$slider_global_options["move_ease"] = 60;
$slider_global_options["autoplay"] = 1;
$slider_global_options["autoplay_start_timeout"] = 5000;
$slider_global_options["autoplay_hover_timeout"] = 2000;
$slider_global_options["autoplay_step_timeout"] = 1000;
$slider_global_options["autoplay_evenly_speed"] = 25;

$slider_global_options["overlayanimationtype"] = 0;
$slider_global_options["popup_max_size"] = 90;
$slider_global_options["popup_item_min_width"] = 300;
$slider_global_options["popup_use_back_img"] = 1;
$slider_global_options["popup_arrow_passive_opacity"] = 50;
$slider_global_options["popup_arrow_left_offset"] = 12;
$slider_global_options["popup_arrow_min_height"] = 25;
$slider_global_options["popup_arrow_max_height"] = 50;
$slider_global_options["popup_showarrows"] = 1;
$slider_global_options["popup_image_order_opacity"] = 70;
$slider_global_options["popup_image_order_top_offset"] = 12;
$slider_global_options["popup_show_orderdata"] = 1;
$slider_global_options["popup_icons_opacity"] = 50;
$slider_global_options["popup_show_icons"] = 1;
$slider_global_options["popup_autoplay_default"] = 1;
$slider_global_options["popup_closeonend"] = 1;
$slider_global_options["popup_autoplay_time"] = 5000;
$slider_global_options["popup_open_event"] = 0;
$slider_global_options["link_open_event"] = 3;
$slider_global_options['cis_touch_enabled'] = 0;
$slider_global_options['cis_inf_scroll_enabled'] = 1;
$slider_global_options['cis_mouse_scroll_enabled'] = 0;
$slider_global_options['cis_item_correction_enabled'] = 1;

$slider_global_options['cis_animation_type'] = 0;
$slider_global_options['cis_item_hover_effect'] = 1;
$slider_global_options['cis_overlay_type'] = 0;
$slider_global_options['cis_touch_type'] = 1;
$slider_global_options['cis_font_family'] = 'inherit';
$slider_global_options['cis_font_effect'] = 'None';

$slider_global_options['cis_items_appearance_effect'] = 0;

$slider_global_options['icons_size'] = 30;
$slider_global_options['icons_margin'] = 10;
$slider_global_options['icons_offset'] = 5;
$slider_global_options['icons_animation'] = 0;
$slider_global_options['icons_color'] = 0;
$slider_global_options['icons_valign'] = 0;

$slider_global_options['ov_items_offset'] = 10;
$slider_global_options['ov_items_m_offset'] = 0;
$slider_global_options['cis_button_font_family'] = 'inherit';

$slider_global_options['slider_full_size'] = 0;
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

		//add sliders
	    var select11 = $( "#cis_overlayopacity" );
	    var place11 = select11.parent('div').find('.cis_slider_insert_here');
	    var slider11 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place11 ).slider({
	      min: 1,
	      max: 11,
	      range: "min",
	      value: select11[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select11[ 0 ].selectedIndex = ui.value - 1;
	        select11.trigger("change");
	      }
	    });
	    $( "#cis_overlayopacity" ).change(function() {
	    	slider11.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select12 = $( "#cis_textshadowsize" );
	    var place12 = select12.parent('div').find('.cis_slider_insert_here');
	    var slider12 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place12 ).slider({
	      min: 1,
	      max: 4,
	      range: "min",
	      value: select12[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select12[ 0 ].selectedIndex = ui.value - 1;
	        select12.trigger("change");
	      }
	    });
	    $( "#cis_textshadowsize" ).change(function() {
	    	slider12.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select13 = $( "#cis_captionalign" );
	    var place13 = select13.parent('div').find('.cis_slider_insert_here');
	    var slider13 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place13 ).slider({
	      min: 1,
	      max: 3,
	      range: "min",
	      value: select13[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select13[ 0 ].selectedIndex = ui.value - 1;
	        select13.trigger("change");
	      }
	    });
	    $( "#cis_captionalign" ).change(function() {
	    	slider13.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select14 = $( "#cis_overlayfontsize" );
	    var place14 = select14.parent('div').find('.cis_slider_insert_here');
	    var slider14 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place14 ).slider({
	      min: 1,
	      max: 46,
	      range: "min",
	      value: select14[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select14[ 0 ].selectedIndex = ui.value - 1;
	        select14.trigger("change");
	      }
	    });
	    $( "#cis_overlayfontsize" ).change(function() {
	    	slider14.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select15 = $( "#cis_readmorestyle" );
	    var place15 = select15.parent('div').find('.cis_slider_insert_here');
	    var slider15 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place15 ).slider({
	      min: 1,
	      max: 7,
	      range: "min",
	      value: select15[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select15[ 0 ].selectedIndex = ui.value - 1;
	        select15.trigger("change");
	      }
	    });
	    $( "#cis_readmorestyle" ).change(function() {
	    	slider15.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select16 = $( "#cis_readmorealign" );
	    var place16 = select16.parent('div').find('.cis_slider_insert_here');
	    var slider16 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place16 ).slider({
	      min: 1,
	      max: 3,
	      range: "min",
	      value: select16[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select16[ 0 ].selectedIndex = ui.value - 1;
	        select16.trigger("change");
	      }
	    });
	    $( "#cis_readmorealign" ).change(function() {
	    	slider16.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select17 = $( "#cis_readmoresize" );
	    var place17 = select17.parent('div').find('.cis_slider_insert_here');
	    var slider17 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place17 ).slider({
	      min: 1,
	      max: 4,
	      range: "min",
	      value: select17[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select17[ 0 ].selectedIndex = ui.value - 1;
	        select17.trigger("change");
	      }
	    });
	    $( "#cis_readmoresize" ).change(function() {
	    	slider17.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select18 = $( "#cis_readmoreicon" );
	    var place18 = select18.parent('div').find('.cis_slider_insert_here');
	    var slider18 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place18 ).slider({
	      min: 1,
	      max: 27,
	      range: "min",
	      value: select18[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select18[ 0 ].selectedIndex = ui.value - 1;
	        select18.trigger("change");
	      }
	    });
	    $( "#cis_readmoreicon" ).change(function() {
	    	slider18.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select19 = $( "#cis_itemsoffset" );
	    var place19 = select19.parent('div').find('.cis_slider_insert_here');
	    var slider19 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place19 ).slider({
	      min: 1,
	      max: 41,
	      range: "min",
	      value: select19[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select19[ 0 ].selectedIndex = ui.value - 1;
	        select19.trigger("change");
	      }
	    });
	    $( "#cis_itemsoffset" ).change(function() {
	    	slider19.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select20 = $( "#cis_margintop" );
	    var place20 = select20.parent('div').find('.cis_slider_insert_here');
	    var slider20 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place20 ).slider({
	      min: 1,
	      max: 41,
	      range: "min",
	      value: select20[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select20[ 0 ].selectedIndex = ui.value - 1;
	        select20.trigger("change");
	      }
	    });
	    $( "#cis_margintop" ).change(function() {
	    	slider20.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select21 = $( "#cis_marginbottom" );
	    var place21 = select21.parent('div').find('.cis_slider_insert_here');
	    var slider21 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place21 ).slider({
	      min: 1,
	      max: 41,
	      range: "min",
	      value: select21[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select21[ 0 ].selectedIndex = ui.value - 1;
	        select21.trigger("change");
	      }
	    });
	    $( "#cis_marginbottom" ).change(function() {
	    	slider21.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select22 = $( "#cis_paddingtop" );
	    var place22 = select22.parent('div').find('.cis_slider_insert_here');
	    var slider22 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place22 ).slider({
	      min: 1,
	      max: 41,
	      range: "min",
	      value: select22[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select22[ 0 ].selectedIndex = ui.value - 1;
	        select22.trigger("change");
	      }
	    });
	    $( "#cis_paddingtop" ).change(function() {
	    	slider22.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select23 = $( "#cis_paddingbottom" );
	    var place23 = select23.parent('div').find('.cis_slider_insert_here');
	    var slider23 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place23 ).slider({
	      min: 1,
	      max: 41,
	      range: "min",
	      value: select23[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select23[ 0 ].selectedIndex = ui.value - 1;
	        select23.trigger("change");
	      }
	    });
	    $( "#cis_paddingbottom" ).change(function() {
	    	slider23.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select24 = $( "#cis_showarrows" );
	    var place24 = select24.parent('div').find('.cis_slider_insert_here');
	    var slider24 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place24 ).slider({
	      min: 1,
	      max: 3,
	      range: "min",
	      value: select24[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select24[ 0 ].selectedIndex = ui.value - 1;
	        select24.trigger("change");
	      }
	    });
	    $( "#cis_showarrows" ).change(function() {
	    	slider24.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select25 = $( "#cis_height" );
	    var place25 = select25.parent('div').find('.cis_slider_insert_here');
	    var slider25 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place25 ).slider({
	      min: 1,
	      max: 651,
	      range: "min",
	      value: select25[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select25[ 0 ].selectedIndex = ui.value - 1;
	        select25.trigger("change");
	      }
	    });
	    $( "#cis_height" ).change(function() {
	    	slider25.slider( "value", this.selectedIndex + 1 );
	    });


	    var select26 = $( "#cis_arrow_template" );
	    var place26 = select26.parent('div').find('.cis_slider_insert_here');
	    var slider26 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place26 ).slider({
	      min: 1,
	      max: 45,
	      range: "min",
	      value: select26[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select26[ 0 ].selectedIndex = ui.value - 1;
	        select26.trigger("change");
	      }
	    });
	    $( "#cis_arrow_template" ).change(function() {
	    	slider26.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select27 = $( "#cis_arrow_width" );
	    var place27 = select27.parent('div').find('.cis_slider_insert_here');
	    var slider27 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place27 ).slider({
	      min: 1,
	      max: 53,
	      range: "min",
	      value: select27[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select27[ 0 ].selectedIndex = ui.value - 1;
	        select27.trigger("change");
	      }
	    });
	    $( "#cis_arrow_width" ).change(function() {
	    	slider27.slider( "value", this.selectedIndex + 1 );
	    });
		
		
	    var select28 = $( "#cis_arrow_left_offset" );
	    var place28 = select28.parent('div').find('.cis_slider_insert_here');
	    var slider28 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place28 ).slider({
	      min: 1,
	      max: 101,
	      range: "min",
	      value: select28[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select28[ 0 ].selectedIndex = ui.value - 1;
	        select28.trigger("change");
	      }
	    });
	    $( "#cis_arrow_left_offset" ).change(function() {
	    	slider28.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select29 = $( "#cis_arrow_center_offset" );
	    var place29 = select29.parent('div').find('.cis_slider_insert_here');
	    var slider29 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place29 ).slider({
	      min: 1,
	      max: 501,
	      range: "min",
	      value: select29[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select29[ 0 ].selectedIndex = ui.value - 1;
	        select29.trigger("change");
	      }
	    });
	    $( "#cis_arrow_center_offset" ).change(function() {
	    	slider29.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select30 = $( "#cis_arrow_passive_opacity" );
	    var place30 = select30.parent('div').find('.cis_slider_insert_here');
	    var slider30 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place30 ).slider({
	      min: 1,
	      max: 21,
	      range: "min",
	      value: select30[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select30[ 0 ].selectedIndex = ui.value - 1;
	        select30.trigger("change");
	      }
	    });
	    $( "#cis_arrow_passive_opacity" ).change(function() {
	    	slider30.slider( "value", this.selectedIndex + 1 );
	    });

	    var select31 = $( "#cis_popup_max_size" );
	    var place31 = select31.parent('div').find('.cis_slider_insert_here');
	    var slider31 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place31 ).slider({
	      min: 1,
	      max: 15,
	      range: "min",
	      value: select31[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select31[ 0 ].selectedIndex = ui.value - 1;
	        select31.trigger("change");
	      }
	    });
	    $( "#cis_popup_max_size" ).change(function() {
	    	slider31.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select32 = $( "#cis_popup_item_min_width" );
	    var place32 = select32.parent('div').find('.cis_slider_insert_here');
	    var slider32 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place32 ).slider({
	      min: 1,
	      max: 41,
	      range: "min",
	      value: select32[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select32[ 0 ].selectedIndex = ui.value - 1;
	        select32.trigger("change");
	      }
	    });
	    $( "#cis_popup_item_min_width" ).change(function() {
	    	slider32.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select33 = $( "#cis_popup_arrow_passive_opacity" );
	    var place33 = select33.parent('div').find('.cis_slider_insert_here');
	    var slider33 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place33 ).slider({
	      min: 1,
	      max: 21,
	      range: "min",
	      value: select33[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select33[ 0 ].selectedIndex = ui.value - 1;
	        select33.trigger("change");
	      }
	    });
	    $( "#cis_popup_arrow_passive_opacity" ).change(function() {
	    	slider33.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select34 = $( "#cis_popup_arrow_left_offset" );
	    var place34 = select34.parent('div').find('.cis_slider_insert_here');
	    var slider34 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place34 ).slider({
	      min: 1,
	      max: 101,
	      range: "min",
	      value: select34[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select34[ 0 ].selectedIndex = ui.value - 1;
	        select34.trigger("change");
	      }
	    });
	    $( "#cis_popup_arrow_left_offset" ).change(function() {
	    	slider34.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select35 = $( "#cis_popup_arrow_min_height" );
	    var place35 = select35.parent('div').find('.cis_slider_insert_here');
	    var slider35 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place35 ).slider({
	      min: 1,
	      max: 21,
	      range: "min",
	      value: select35[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select35[ 0 ].selectedIndex = ui.value - 1;
	        select35.trigger("change");
	      }
	    });
	    $( "#cis_popup_arrow_min_height" ).change(function() {
	    	slider35.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select36 = $( "#cis_popup_arrow_max_height" );
	    var place36 = select36.parent('div').find('.cis_slider_insert_here');
	    var slider36 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place36 ).slider({
	      min: 1,
	      max: 35,
	      range: "min",
	      value: select36[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select36[ 0 ].selectedIndex = ui.value - 1;
	        select36.trigger("change");
	      }
	    });
	    $( "#cis_popup_arrow_max_height" ).change(function() {
	    	slider36.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select37 = $( "#cis_popup_showarrows" );
	    var place37 = select37.parent('div').find('.cis_slider_insert_here');
	    var slider37 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place37 ).slider({
	      min: 1,
	      max: 3,
	      range: "min",
	      value: select37[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select37[ 0 ].selectedIndex = ui.value - 1;
	        select37.trigger("change");
	      }
	    });
	    $( "#cis_popup_showarrows" ).change(function() {
	    	slider37.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select38 = $( "#cis_popup_image_order_opacity" );
	    var place38 = select38.parent('div').find('.cis_slider_insert_here');
	    var slider38 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place38 ).slider({
	      min: 1,
	      max: 21,
	      range: "min",
	      value: select38[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select38[ 0 ].selectedIndex = ui.value - 1;
	        select38.trigger("change");
	      }
	    });
	    $( "#cis_popup_image_order_opacity" ).change(function() {
	    	slider38.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select39 = $( "#cis_popup_image_order_top_offset" );
	    var place39 = select39.parent('div').find('.cis_slider_insert_here');
	    var slider39 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place39 ).slider({
	      min: 1,
	      max: 101,
	      range: "min",
	      value: select39[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select39[ 0 ].selectedIndex = ui.value - 1;
	        select39.trigger("change");
	      }
	    });
	    $( "#cis_popup_image_order_top_offset" ).change(function() {
	    	slider39.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select40 = $( "#cis_popup_show_orderdata" );
	    var place40 = select40.parent('div').find('.cis_slider_insert_here');
	    var slider40 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place40 ).slider({
	      min: 1,
	      max: 3,
	      range: "min",
	      value: select40[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select40[ 0 ].selectedIndex = ui.value - 1;
	        select40.trigger("change");
	      }
	    });
	    $( "#cis_popup_show_orderdata" ).change(function() {
	    	slider40.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select41 = $( "#cis_popup_icons_opacity" );
	    var place41 = select41.parent('div').find('.cis_slider_insert_here');
	    var slider41 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place41 ).slider({
	      min: 1,
	      max: 21,
	      range: "min",
	      value: select41[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select41[ 0 ].selectedIndex = ui.value - 1;
	        select41.trigger("change");
	      }
	    });
	    $( "#cis_popup_icons_opacity" ).change(function() {
	    	slider41.slider( "value", this.selectedIndex + 1 );
	    });
		
	    var select42 = $( "#cis_popup_show_icons" );
	    var place42 = select42.parent('div').find('.cis_slider_insert_here');
	    var slider42 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place42 ).slider({
	      min: 1,
	      max: 3,
	      range: "min",
	      value: select42[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select42[ 0 ].selectedIndex = ui.value - 1;
	        select42.trigger("change");
	      }
	    });
	    $( "#cis_popup_show_icons" ).change(function() {
	    	slider42.slider( "value", this.selectedIndex + 1 );
	    });

	    var select43 = $( "#cis_icons_size" );
	    var place43 = select43.parent('div').find('.cis_slider_insert_here');
	    var slider43 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place43 ).slider({
	      min: 1,
	      max: 49,
	      range: "min",
	      value: select43[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select43[ 0 ].selectedIndex = ui.value - 1;
	        select43.trigger("change");
	      }
	    });
	    $( "#cis_icons_size" ).change(function() {
	    	slider43.slider( "value", this.selectedIndex + 1 );
	    });
	    
	    var select44 = $( "#cis_icons_margin" );
	    var place44 = select44.parent('div').find('.cis_slider_insert_here');
	    var slider44 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place44 ).slider({
	      min: 1,
	      max: 31,
	      range: "min",
	      value: select44[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select44[ 0 ].selectedIndex = ui.value - 1;
	        select44.trigger("change");
	      }
	    });
	    $( "#cis_icons_margin" ).change(function() {
	    	slider44.slider( "value", this.selectedIndex + 1 );
	    });

	    var select45 = $( "#cis_icons_offset" );
	    var place45 = select45.parent('div').find('.cis_slider_insert_here');
	    var slider45 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place45 ).slider({
	      min: 1,
	      max: 31,
	      range: "min",
	      value: select45[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select45[ 0 ].selectedIndex = ui.value - 1;
	        select45.trigger("change");
	      }
	    });
	    $( "#cis_icons_offset" ).change(function() {
	    	slider45.slider( "value", this.selectedIndex + 1 );
	    });

	    var select46 = $( "#cis_ov_items_offset" );
	    var place46 = select46.parent('div').find('.cis_slider_insert_here');
	    var slider46 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place46 ).slider({
	      min: 1,
	      max: 51,
	      range: "min",
	      value: select46[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select46[ 0 ].selectedIndex = ui.value - 1;
	        select46.trigger("change");
	      }
	    });
	    $( "#cis_ov_items_offset" ).change(function() {
	    	slider46.slider( "value", this.selectedIndex + 1 );
	    });

	    var select47 = $( "#cis_ov_items_m_offset" );
	    var place47 = select47.parent('div').find('.cis_slider_insert_here');
	    var slider47 = $( "<div id='cis_overlayopacity_slider' class='cis_options_slider'></div>" ).insertAfter( place47 ).slider({
	      min: 1,
	      max: 501,
	      range: "min",
	      value: select47[ 0 ].selectedIndex + 1,
	      slide: function( event, ui ) {
	        select47[ 0 ].selectedIndex = ui.value - 1;
	        select47.trigger("change");
	      }
	    });
	    $( "#cis_ov_items_m_offset" ).change(function() {
	    	slider47.slider( "value", this.selectedIndex + 1 );
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
				cis_make_slider_css();

				//$("#ssw_template_wrapper").css('background-color','#' + hex);
			}
		});

		//preview//////////////////////////////////////////////////////////////////////////
		function cis_update_overlay_txt() {
			var $cis_element = $(".cis_row_item_overlay_txt").not('.cis_preset');

			//generate styles
			var textShadowSize = parseInt($("#cis_textshadowsize").val());
			var textShadowColor = $("#cis_textshadowcolor").val();
			var textShadowRule = textShadowSize == 0 ? 'none' : (textShadowSize == 1 ? '1px 2px 0px ' + textShadowColor : (textShadowSize == 2 ? '1px 2px 2px ' + textShadowColor : '1px 2px 4px ' + textShadowColor));

			var textAlignVal = parseInt($("#cis_captionalign").val());
			var textAlign = textAlignVal == 0 ? 'left' : (textAlignVal == 1 ? 'right' : 'center');

			var textColor = $("#cis_textcolor").val();
			var textFontSize = parseInt($("#cis_overlayfontsize").val());
			var textMargin = $("#cis_captionmargin").val();

			//apply css

			$cis_element.css({
				'text-shadow' : textShadowRule,
				'color' : textColor,
				'font-size' : textFontSize,
				'margin' : textMargin,
				'text-align': textAlign
			});
		};

		function cis_hexToRgb(hex) {
		    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		    return result ? parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) : null;
		};
		
		function cis_update_overlay_bg() {
			var $cis_element = $(".cis_row_item_overlay").not('.cis_preset');

			//generate css
			var overlay_bg = $("#cis_overlaycolor").val();
			var overlay_opacity = $("#cis_overlayopacity").val() / 100;
			var overlay_bg_rgba = 'rgba(' + cis_hexToRgb(overlay_bg) + ',' + overlay_opacity + ')';

			$cis_element.css({
				'background-color' : overlay_bg,
				'background-color' : overlay_bg_rgba
			});
		};

		//overlay txt
		$("#cis_textshadowsize").change(function() {
			cis_update_overlay_txt();
		});
		$("#cis_captionalign").change(function() {
			cis_update_overlay_txt();
		});
		$("#cis_captionmargin").keyup(function() {
			cis_update_overlay_txt();
		});
		$("#cis_captionmargin").keyup(function() {
			cis_update_overlay_txt();
		});
		$("#cis_overlayfontsize").change(function() {
			cis_update_overlay_txt();
		});

		//overlay bg
		$("#cis_overlayopacity").change(function() {
			cis_update_overlay_bg();
		});

		//buttons preview///////////////////////////////////////
		// $("#cis_showreadmore").change(function() {
		// 	var v = parseInt($(this).val());
		// 	var $targetElement = $(".creative_btn").not('.cis_preset');
		// 	if(v == 0) {
		// 		$targetElement.hide();
		// 	}
		// 	else {
		// 		$targetElement.show().css('display','inline-block');
		// 	}
		// });

		$("#cis_readmoretext").keyup(function() {
			var v = $(this).val();
			$(".creative_btn").not('.cis_preset').find(".cis_creative_btn_txt").html(v);
		});

		function cis_make_creative_button() {
			var $cis_element = $(".creative_btn").not('.cis_preset');

			//generate css
			var margin = $("#cis_readmoremargin").val();
			var float = parseInt($("#cis_readmorealign").val()) == 0 ? 'left' : (parseInt($("#cis_readmorealign").val()) == 1 ? 'right' : 'none');
			var button_style_class = 'creative_btn-' + $("#cis_readmorestyle").val();
			var button_size_class = 'creative_btn-' + $("#cis_readmoresize").val();
			
			$cis_element.attr("class","creative_btn " + button_style_class + " " + button_size_class);

			//icon
			var cis_icon = $("#cis_readmoreicon").val() == 'none' ? '' : '<i class="creative_icon-white creative_icon-' + $("#cis_readmoreicon").val() + '"></i> ';
			$(".creative_btn").not('.cis_preset').find(".cis_creative_btn_icon").html(cis_icon);

			if(float == 'none')
				$cis_element.parent('div').css('text-align','center');
			else
				$cis_element.parent('div').css('text-align','left');
			
			$cis_element.css({
				'margin' : margin,
				'float' : float
			});
		};

		$("#cis_readmoremargin").keyup(function() {
			cis_make_creative_button();
		});
		$("#cis_readmorestyle").change(function() {
			cis_make_creative_button();
		});
		$("#cis_readmoresize").change(function() {
			cis_make_creative_button();
		});
		$("#cis_readmorealign").change(function() {
			cis_make_creative_button();
		});
		$("#cis_readmoreicon").change(function() {
			cis_make_creative_button();
		});

		//////////////////////////////////////////////////////////////////slider main preview
		$("#cis_margintop").change(function() {
			cis_make_slider_css();
		});
		$("#cis_marginbottom").change(function() {
			cis_make_slider_css();
		});
		$("#cis_paddingtop").change(function() {
			cis_make_slider_css();
		});
		$("#cis_paddingbottom").change(function() {
			cis_make_slider_css();
		});
		$("#cis_itemsoffset").change(function() {
			cis_make_slider_css();
		});
		$("#cis_height").change(function() {
			cis_make_slider_css();
			cis_make_arrows_css();
		});
		$("#cis_width").blur(function() {
			cis_make_slider_css();
		});

		function cis_make_slider_css() {
			var $cis_element = $(".cis_main_wrapper");

			//get css
			var margintop = parseInt($("#cis_margintop").val());
			var marginbottom = parseInt($("#cis_marginbottom").val());
			var paddingtop = parseInt($("#cis_paddingtop").val());
			var paddingbottom = parseInt($("#cis_paddingbottom").val());
			var itemsoffset = parseInt($("#cis_itemsoffset").val());
			var itemsheight = parseInt($("#cis_height").val());
			var backgroundcolor = $("#cis_bgcolor").val();
			var width = $("#cis_width").val();

			//set big width
			$('.cis_images_holder').css('width','9999999px');

			//apply css
			$cis_element.css({
				'width' : width,
				'margin-top' : margintop,
				'margin-bottom' : marginbottom,
				'padding-top' : paddingtop,
				'padding-bottom' : paddingbottom,
				'background-color' : backgroundcolor
			}).find('.cis_row_item').css({
				'margin-right' : itemsoffset
			}).find('img').css({
				'height' : itemsheight
			});

			cis_calculate_backend_width();
		};

		function cis_calculate_backend_width() {
			$('.cis_images_holder').each(function() {
				var $wrapper = $(this);
				var total_w = 0;
				$wrapper.find('.cis_row_item').each(function() {
					$(this).find('img').css('width','auto');
					var w = parseInt($(this).find('img').width());
					$(this).find('img').width(w);
					var m_r = isNaN(parseFloat($(this).css('margin-right'))) ? 0 : parseFloat($(this).css('margin-right'));
					var m_l = isNaN(parseFloat($(this).css('margin-left'))) ? 0 : parseFloat($(this).css('margin-left'));
					total_w += w + m_r*1 + m_l*1;
				});
				$wrapper.width(total_w);
			});
		};

		//arrows
		function cis_make_arrows_css() {
			var $cis_element = $(".cis_main_wrapper");

			var $left_arrow = $cis_element.find('.cis_button_left');
			var $right_arrow = $cis_element.find('.cis_button_right');


			//get arrows data
			var arrow_width = $("#cis_arrow_width").val();
			var arrow_corner_offset = $("#cis_arrow_left_offset").val();
			var arrow_middle_offset = $("#cis_arrow_center_offset").val();
			var arrow_opacity = $("#cis_arrow_passive_opacity").val() / 100;
			var show_arrows = $("#cis_showarrows").val();

			if(show_arrows == 0) {
				$left_arrow.hide();
				$right_arrow.hide();
				return;
			}
			else {
				$left_arrow.show();
				$right_arrow.show();
			}

			//set images
			var img_src1 = '../components/com_creativeimageslider/assets/images/arrows/cis_button_left' + $("#cis_arrow_template").val() + '.png';
			var img_src2 = '../components/com_creativeimageslider/assets/images/arrows/cis_button_right' + $("#cis_arrow_template").val() + '.png';

			$left_arrow.attr("src",img_src1);
			$right_arrow.attr("src",img_src2)

			//set data
			$left_arrow.attr("op",arrow_opacity);
			$left_arrow.attr("corner_offset",arrow_corner_offset);
			$right_arrow.attr("op",arrow_opacity);
			$right_arrow.attr("corner_offset",arrow_corner_offset);
			
			//set styles
			$left_arrow.css('width',arrow_width);
			$right_arrow.css('width',arrow_width);

			setTimeout(function() {
				var arrow_height = parseInt ($left_arrow.height());
				var wrapper_height = parseFloat ($cis_element.height());
				var p_t = isNaN(parseFloat($cis_element.css('padding-top'))) ? 0 : parseFloat($cis_element.css('padding-top'));
				var p_b = isNaN(parseFloat($cis_element.css('padding-bottom'))) ? 0 : parseFloat($cis_element.css('padding-bottom'));
				var arrow_top_position = ((wrapper_height + 1 * p_t + 1 * p_b - arrow_height) / 2 ) + 1 * arrow_middle_offset;

				var c_off = arrow_corner_offset + 'px';
				$left_arrow.css({
					'top': arrow_top_position,
					'left': c_off,
					'opacity': arrow_opacity
				});
				$right_arrow.css({
					'top': arrow_top_position,
					'right': c_off,
					'opacity': arrow_opacity
				});
			},200);
			
		};

		$("#cis_arrow_template").change(function() {
			cis_make_arrows_css();
		});
		$("#cis_arrow_width").change(function() {
			cis_make_arrows_css();
		});
		$("#cis_arrow_left_offset").change(function() {
			cis_make_arrows_css();
		});
		$("#cis_arrow_center_offset").change(function() {
			cis_make_arrows_css();
		});
		$("#cis_arrow_passive_opacity").change(function() {
			cis_make_arrows_css();
		});
		$("#cis_showarrows").change(function() {
			cis_make_arrows_css();
		});
		
	})
})(creativeJ);
</script>
<?php 
$fonts_array = array("Standard Fonts" => array("inherit" => "Use Parent Font", "Arial, Helvetica, sans-serif" => "Arial", "'Comic Sans MS', cursive, sans-serif" => "Comic Sans MS", "Impact, Charcoal, sans-serif" => "Impact", "'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "Lucida Sans Unicode", "Tahoma, Geneva, sans-serif" => "Tahoma", "'Trebuchet MS', Helvetica, sans-serif" => "Trebuchet MS", "Verdana, Geneva, sans-serif" => "Verdana", "Georgia, serif" => "Georgia", "'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "Palatino Linotype", "'Times New Roman', Times, serif" => "Times New Roman", "'Courier New', Courier, monospace" => "Courier New", "Monaco, monospace" => "Monaco", "'Lucida Console', monospace" => "Lucida Console", ),"Google Web Fonts" => array("cis-googlewebfont-ABeeZee" => "ABeeZee", "cis-googlewebfont-Abel" => "Abel","cis-googlewebfont-Aclonica" => "Aclonica", "cis-googlewebfont-Acme" => "Acme", "cis-googlewebfont-Actor" => "Actor", "cis-googlewebfont-Adamina" => "Adamina", "cis-googlewebfont-Advent Pro" => "Advent Pro", "cis-googlewebfont-Aguafina Script" => "Aguafina Script", "cis-googlewebfont-Akronim" => "Akronim", "cis-googlewebfont-Aladin" => "Aladin", "cis-googlewebfont-Aldrich" => "Aldrich", "cis-googlewebfont-Alef" => "Alef", "cis-googlewebfont-Alegreya" => "Alegreya", "cis-googlewebfont-Alegreya SC" => "Alegreya SC", "cis-googlewebfont-Alegreya Sans" => "Alegreya Sans", "cis-googlewebfont-Alegreya Sans SC" => "Alegreya Sans SC", "cis-googlewebfont-Alex Brush" => "Alex Brush", "cis-googlewebfont-Alfa Slab One" => "Alfa Slab One", "cis-googlewebfont-Alice" => "Alice", "cis-googlewebfont-Alike" => "Alike", "cis-googlewebfont-Alike Angular" => "Alike Angular", "cis-googlewebfont-Allan" => "Allan", "cis-googlewebfont-Allerta" => "Allerta", "cis-googlewebfont-Allerta Stencil" => "Allerta Stencil", "cis-googlewebfont-Allura" => "Allura", "cis-googlewebfont-Almendra" => "Almendra", "cis-googlewebfont-Almendra Display" => "Almendra Display", "cis-googlewebfont-Almendra SC" => "Almendra SC", "cis-googlewebfont-Amarante" => "Amarante", "cis-googlewebfont-Amaranth" => "Amaranth", "cis-googlewebfont-Amatic SC" => "Amatic SC", "cis-googlewebfont-Amethysta" => "Amethysta", "cis-googlewebfont-Anaheim" => "Anaheim", "cis-googlewebfont-Andada" => "Andada", "cis-googlewebfont-Andika" => "Andika", "cis-googlewebfont-Angkor" => "Angkor", "cis-googlewebfont-Annie Use Your Telescope" => "Annie Use Your Telescope", "cis-googlewebfont-Anonymous Pro" => "Anonymous Pro", "cis-googlewebfont-Antic" => "Antic", "cis-googlewebfont-Antic Didone" => "Antic Didone", "cis-googlewebfont-Antic Slab" => "Antic Slab", "cis-googlewebfont-Anton" => "Anton", "cis-googlewebfont-Arapey" => "Arapey", "cis-googlewebfont-Arbutus" => "Arbutus", "cis-googlewebfont-Arbutus Slab" => "Arbutus Slab", "cis-googlewebfont-Architects Daughter" => "Architects Daughter", "cis-googlewebfont-Archivo Black" => "Archivo Black", "cis-googlewebfont-Archivo Narrow" => "Archivo Narrow", "cis-googlewebfont-Arimo" => "Arimo", "cis-googlewebfont-Arizonia" => "Arizonia", "cis-googlewebfont-Armata" => "Armata", "cis-googlewebfont-Artifika" => "Artifika", "cis-googlewebfont-Arvo" => "Arvo", "cis-googlewebfont-Asap" => "Asap", "cis-googlewebfont-Asset" => "Asset", "cis-googlewebfont-Astloch" => "Astloch", "cis-googlewebfont-Asul" => "Asul", "cis-googlewebfont-Atomic Age" => "Atomic Age", "cis-googlewebfont-Aubrey" => "Aubrey", "cis-googlewebfont-Audiowide" => "Audiowide", "cis-googlewebfont-Autour One" => "Autour One", "cis-googlewebfont-Average" => "Average", "cis-googlewebfont-Average Sans" => "Average Sans", "cis-googlewebfont-Averia Gruesa Libre" => "Averia Gruesa Libre", "cis-googlewebfont-Averia Libre" => "Averia Libre", "cis-googlewebfont-Averia Sans Libre" => "Averia Sans Libre", "cis-googlewebfont-Averia Serif Libre" => "Averia Serif Libre", "cis-googlewebfont-Bad Script" => "Bad Script", "cis-googlewebfont-Balthazar" => "Balthazar", "cis-googlewebfont-Bangers" => "Bangers", "cis-googlewebfont-Basic" => "Basic", "cis-googlewebfont-Battambang" => "Battambang", "cis-googlewebfont-Baumans" => "Baumans", "cis-googlewebfont-Bayon" => "Bayon", "cis-googlewebfont-Belgrano" => "Belgrano", "cis-googlewebfont-Belleza" => "Belleza", "cis-googlewebfont-BenchNine" => "BenchNine", "cis-googlewebfont-Bentham" => "Bentham", "cis-googlewebfont-Berkshire Swash" => "Berkshire Swash", "cis-googlewebfont-Bevan" => "Bevan", "cis-googlewebfont-Bigelow Rules" => "Bigelow Rules", "cis-googlewebfont-Bigshot One" => "Bigshot One", "cis-googlewebfont-Bilbo" => "Bilbo", "cis-googlewebfont-Bilbo Swash Caps" => "Bilbo Swash Caps", "cis-googlewebfont-Bitter" => "Bitter", "cis-googlewebfont-Black Ops One" => "Black Ops One", "cis-googlewebfont-Bokor" => "Bokor", "cis-googlewebfont-Bonbon" => "Bonbon", "cis-googlewebfont-Boogaloo" => "Boogaloo", "cis-googlewebfont-Bowlby One" => "Bowlby One", "cis-googlewebfont-Bowlby One SC" => "Bowlby One SC", "cis-googlewebfont-Brawler" => "Brawler", "cis-googlewebfont-Bree Serif" => "Bree Serif", "cis-googlewebfont-Bubblegum Sans" => "Bubblegum Sans", "cis-googlewebfont-Bubbler One" => "Bubbler One", "cis-googlewebfont-Buda" => "Buda", "cis-googlewebfont-Buenard" => "Buenard", "cis-googlewebfont-Butcherman" => "Butcherman", "cis-googlewebfont-Butterfly Kids" => "Butterfly Kids", "cis-googlewebfont-Cabin" => "Cabin", "cis-googlewebfont-Cabin Condensed" => "Cabin Condensed", "cis-googlewebfont-Cabin Sketch" => "Cabin Sketch", "cis-googlewebfont-Caesar Dressing" => "Caesar Dressing", "cis-googlewebfont-Cagliostro" => "Cagliostro", "cis-googlewebfont-Calligraffitti" => "Calligraffitti", "cis-googlewebfont-Cambo" => "Cambo", "cis-googlewebfont-Candal" => "Candal", "cis-googlewebfont-Cantarell" => "Cantarell", "cis-googlewebfont-Cantata One" => "Cantata One", "cis-googlewebfont-Cantora One" => "Cantora One", "cis-googlewebfont-Capriola" => "Capriola", "cis-googlewebfont-Cardo" => "Cardo", "cis-googlewebfont-Carme" => "Carme", "cis-googlewebfont-Carrois Gothic" => "Carrois Gothic", "cis-googlewebfont-Carrois Gothic SC" => "Carrois Gothic SC", "cis-googlewebfont-Carter One" => "Carter One", "cis-googlewebfont-Caudex" => "Caudex", "cis-googlewebfont-Cedarville Cursive" => "Cedarville Cursive", "cis-googlewebfont-Ceviche One" => "Ceviche One", "cis-googlewebfont-Changa One" => "Changa One", "cis-googlewebfont-Chango" => "Chango", "cis-googlewebfont-Chau Philomene One" => "Chau Philomene One", "cis-googlewebfont-Chela One" => "Chela One", "cis-googlewebfont-Chelsea Market" => "Chelsea Market", "cis-googlewebfont-Chenla" => "Chenla", "cis-googlewebfont-Cherry Cream Soda" => "Cherry Cream Soda", "cis-googlewebfont-Cherry Swash" => "Cherry Swash", "cis-googlewebfont-Chewy" => "Chewy", "cis-googlewebfont-Chicle" => "Chicle", "cis-googlewebfont-Chivo" => "Chivo", "cis-googlewebfont-Cinzel" => "Cinzel", "cis-googlewebfont-Cinzel Decorative" => "Cinzel Decorative", "cis-googlewebfont-Clicker Script" => "Clicker Script", "cis-googlewebfont-Coda" => "Coda", "cis-googlewebfont-Coda Caption" => "Coda Caption", "cis-googlewebfont-Codystar" => "Codystar", "cis-googlewebfont-Combo" => "Combo", "cis-googlewebfont-Comfortaa" => "Comfortaa", "cis-googlewebfont-Coming Soon" => "Coming Soon", "cis-googlewebfont-Concert One" => "Concert One", "cis-googlewebfont-Condiment" => "Condiment", "cis-googlewebfont-Content" => "Content", "cis-googlewebfont-Contrail One" => "Contrail One", "cis-googlewebfont-Convergence" => "Convergence", "cis-googlewebfont-Cookie" => "Cookie", "cis-googlewebfont-Copse" => "Copse", "cis-googlewebfont-Corben" => "Corben", "cis-googlewebfont-Courgette" => "Courgette", "cis-googlewebfont-Cousine" => "Cousine", "cis-googlewebfont-Coustard" => "Coustard", "cis-googlewebfont-Covered By Your Grace" => "Covered By Your Grace", "cis-googlewebfont-Crafty Girls" => "Crafty Girls", "cis-googlewebfont-Creepster" => "Creepster", "cis-googlewebfont-Crete Round" => "Crete Round", "cis-googlewebfont-Crimson Text" => "Crimson Text", "cis-googlewebfont-Croissant One" => "Croissant One", "cis-googlewebfont-Crushed" => "Crushed", "cis-googlewebfont-Cuprum" => "Cuprum", "cis-googlewebfont-Cutive" => "Cutive", "cis-googlewebfont-Cutive Mono" => "Cutive Mono", "cis-googlewebfont-Damion" => "Damion", "cis-googlewebfont-Dancing Script" => "Dancing Script", "cis-googlewebfont-Dangrek" => "Dangrek", "cis-googlewebfont-Dawning of a New Day" => "Dawning of a New Day", "cis-googlewebfont-Days One" => "Days One", "cis-googlewebfont-Delius" => "Delius", "cis-googlewebfont-Delius Swash Caps" => "Delius Swash Caps", "cis-googlewebfont-Delius Unicase" => "Delius Unicase", "cis-googlewebfont-Della Respira" => "Della Respira", "cis-googlewebfont-Denk One" => "Denk One", "cis-googlewebfont-Devonshire" => "Devonshire", "cis-googlewebfont-Didact Gothic" => "Didact Gothic", "cis-googlewebfont-Diplomata" => "Diplomata", "cis-googlewebfont-Diplomata SC" => "Diplomata SC", "cis-googlewebfont-Domine" => "Domine", "cis-googlewebfont-Donegal One" => "Donegal One", "cis-googlewebfont-Doppio One" => "Doppio One", "cis-googlewebfont-Dorsa" => "Dorsa", "cis-googlewebfont-Dosis" => "Dosis", "cis-googlewebfont-Dr Sugiyama" => "Dr Sugiyama", "cis-googlewebfont-Droid Sans" => "Droid Sans", "cis-googlewebfont-Droid Sans Mono" => "Droid Sans Mono", "cis-googlewebfont-Droid Serif" => "Droid Serif", "cis-googlewebfont-Duru Sans" => "Duru Sans", "cis-googlewebfont-Dynalight" => "Dynalight", "cis-googlewebfont-EB Garamond" => "EB Garamond", "cis-googlewebfont-Eagle Lake" => "Eagle Lake", "cis-googlewebfont-Eater" => "Eater", "cis-googlewebfont-Economica" => "Economica", "cis-googlewebfont-Ek Mukta" => "Ek Mukta", "cis-googlewebfont-Electrolize" => "Electrolize", "cis-googlewebfont-Elsie" => "Elsie", "cis-googlewebfont-Elsie Swash Caps" => "Elsie Swash Caps", "cis-googlewebfont-Emblema One" => "Emblema One", "cis-googlewebfont-Emilys Candy" => "Emilys Candy", "cis-googlewebfont-Engagement" => "Engagement", "cis-googlewebfont-Englebert" => "Englebert", "cis-googlewebfont-Enriqueta" => "Enriqueta", "cis-googlewebfont-Erica One" => "Erica One", "cis-googlewebfont-Esteban" => "Esteban", "cis-googlewebfont-Euphoria Script" => "Euphoria Script", "cis-googlewebfont-Ewert" => "Ewert", "cis-googlewebfont-Exo" => "Exo", "cis-googlewebfont-Exo 2" => "Exo 2", "cis-googlewebfont-Expletus Sans" => "Expletus Sans", "cis-googlewebfont-Fanwood Text" => "Fanwood Text", "cis-googlewebfont-Fascinate" => "Fascinate", "cis-googlewebfont-Fascinate Inline" => "Fascinate Inline", "cis-googlewebfont-Faster One" => "Faster One", "cis-googlewebfont-Fasthand" => "Fasthand", "cis-googlewebfont-Fauna One" => "Fauna One", "cis-googlewebfont-Federant" => "Federant", "cis-googlewebfont-Federo" => "Federo", "cis-googlewebfont-Felipa" => "Felipa", "cis-googlewebfont-Fenix" => "Fenix", "cis-googlewebfont-Finger Paint" => "Finger Paint", "cis-googlewebfont-Fira Mono" => "Fira Mono", "cis-googlewebfont-Fira Sans" => "Fira Sans", "cis-googlewebfont-Fjalla One" => "Fjalla One", "cis-googlewebfont-Fjord One" => "Fjord One", "cis-googlewebfont-Flamenco" => "Flamenco", "cis-googlewebfont-Flavors" => "Flavors", "cis-googlewebfont-Fondamento" => "Fondamento", "cis-googlewebfont-Fontdiner Swanky" => "Fontdiner Swanky", "cis-googlewebfont-Forum" => "Forum", "cis-googlewebfont-Francois One" => "Francois One", "cis-googlewebfont-Freckle Face" => "Freckle Face", "cis-googlewebfont-Fredericka the Great" => "Fredericka the Great", "cis-googlewebfont-Fredoka One" => "Fredoka One", "cis-googlewebfont-Freehand" => "Freehand", "cis-googlewebfont-Fresca" => "Fresca", "cis-googlewebfont-Frijole" => "Frijole", "cis-googlewebfont-Fruktur" => "Fruktur", "cis-googlewebfont-Fugaz One" => "Fugaz One", "cis-googlewebfont-GFS Didot" => "GFS Didot", "cis-googlewebfont-GFS Neohellenic" => "GFS Neohellenic", "cis-googlewebfont-Gabriela" => "Gabriela", "cis-googlewebfont-Gafata" => "Gafata", "cis-googlewebfont-Galdeano" => "Galdeano", "cis-googlewebfont-Galindo" => "Galindo", "cis-googlewebfont-Gentium Basic" => "Gentium Basic", "cis-googlewebfont-Gentium Book Basic" => "Gentium Book Basic", "cis-googlewebfont-Geo" => "Geo", "cis-googlewebfont-Geostar" => "Geostar", "cis-googlewebfont-Geostar Fill" => "Geostar Fill", "cis-googlewebfont-Germania One" => "Germania One", "cis-googlewebfont-Gilda Display" => "Gilda Display", "cis-googlewebfont-Give You Glory" => "Give You Glory", "cis-googlewebfont-Glass Antiqua" => "Glass Antiqua", "cis-googlewebfont-Glegoo" => "Glegoo", "cis-googlewebfont-Gloria Hallelujah" => "Gloria Hallelujah", "cis-googlewebfont-Goblin One" => "Goblin One", "cis-googlewebfont-Gochi Hand" => "Gochi Hand", "cis-googlewebfont-Gorditas" => "Gorditas", "cis-googlewebfont-Goudy Bookletter 1911" => "Goudy Bookletter 1911", "cis-googlewebfont-Graduate" => "Graduate", "cis-googlewebfont-Grand Hotel" => "Grand Hotel", "cis-googlewebfont-Gravitas One" => "Gravitas One", "cis-googlewebfont-Great Vibes" => "Great Vibes", "cis-googlewebfont-Griffy" => "Griffy", "cis-googlewebfont-Gruppo" => "Gruppo", "cis-googlewebfont-Gudea" => "Gudea", "cis-googlewebfont-Habibi" => "Habibi", "cis-googlewebfont-Halant" => "Halant", "cis-googlewebfont-Hammersmith One" => "Hammersmith One", "cis-googlewebfont-Hanalei" => "Hanalei", "cis-googlewebfont-Hanalei Fill" => "Hanalei Fill", "cis-googlewebfont-Handlee" => "Handlee", "cis-googlewebfont-Hanuman" => "Hanuman", "cis-googlewebfont-Happy Monkey" => "Happy Monkey", "cis-googlewebfont-Headland One" => "Headland One", "cis-googlewebfont-Henny Penny" => "Henny Penny", "cis-googlewebfont-Herr Von Muellerhoff" => "Herr Von Muellerhoff", "cis-googlewebfont-Hind" => "Hind", "cis-googlewebfont-Holtwood One SC" => "Holtwood One SC", "cis-googlewebfont-Homemade Apple" => "Homemade Apple", "cis-googlewebfont-Homenaje" => "Homenaje", "cis-googlewebfont-IM Fell DW Pica" => "IM Fell DW Pica", "cis-googlewebfont-IM Fell DW Pica SC" => "IM Fell DW Pica SC", "cis-googlewebfont-IM Fell Double Pica" => "IM Fell Double Pica", "cis-googlewebfont-IM Fell Double Pica SC" => "IM Fell Double Pica SC", "cis-googlewebfont-IM Fell English" => "IM Fell English", "cis-googlewebfont-IM Fell English SC" => "IM Fell English SC", "cis-googlewebfont-IM Fell French Canon" => "IM Fell French Canon", "cis-googlewebfont-IM Fell French Canon SC" => "IM Fell French Canon SC", "cis-googlewebfont-IM Fell Great Primer" => "IM Fell Great Primer", "cis-googlewebfont-IM Fell Great Primer SC" => "IM Fell Great Primer SC", "cis-googlewebfont-Iceberg" => "Iceberg", "cis-googlewebfont-Iceland" => "Iceland", "cis-googlewebfont-Imprima" => "Imprima", "cis-googlewebfont-Inconsolata" => "Inconsolata", "cis-googlewebfont-Inder" => "Inder", "cis-googlewebfont-Indie Flower" => "Indie Flower", "cis-googlewebfont-Inika" => "Inika", "cis-googlewebfont-Irish Grover" => "Irish Grover", "cis-googlewebfont-Istok Web" => "Istok Web", "cis-googlewebfont-Italiana" => "Italiana", "cis-googlewebfont-Italianno" => "Italianno", "cis-googlewebfont-Jacques Francois" => "Jacques Francois", "cis-googlewebfont-Jacques Francois Shadow" => "Jacques Francois Shadow", "cis-googlewebfont-Jim Nightshade" => "Jim Nightshade", "cis-googlewebfont-Jockey One" => "Jockey One", "cis-googlewebfont-Jolly Lodger" => "Jolly Lodger", "cis-googlewebfont-Josefin Sans" => "Josefin Sans", "cis-googlewebfont-Josefin Slab" => "Josefin Slab", "cis-googlewebfont-Joti One" => "Joti One", "cis-googlewebfont-Judson" => "Judson", "cis-googlewebfont-Julee" => "Julee", "cis-googlewebfont-Julius Sans One" => "Julius Sans One", "cis-googlewebfont-Junge" => "Junge", "cis-googlewebfont-Jura" => "Jura", "cis-googlewebfont-Just Another Hand" => "Just Another Hand", "cis-googlewebfont-Just Me Again Down Here" => "Just Me Again Down Here", "cis-googlewebfont-Kalam" => "Kalam", "cis-googlewebfont-Kameron" => "Kameron", "cis-googlewebfont-Kantumruy" => "Kantumruy", "cis-googlewebfont-Karla" => "Karla", "cis-googlewebfont-Karma" => "Karma", "cis-googlewebfont-Kaushan Script" => "Kaushan Script", "cis-googlewebfont-Kavoon" => "Kavoon", "cis-googlewebfont-Kdam Thmor" => "Kdam Thmor", "cis-googlewebfont-Keania One" => "Keania One", "cis-googlewebfont-Kelly Slab" => "Kelly Slab", "cis-googlewebfont-Kenia" => "Kenia", "cis-googlewebfont-Khand" => "Khand", "cis-googlewebfont-Khmer" => "Khmer", "cis-googlewebfont-Kite One" => "Kite One", "cis-googlewebfont-Knewave" => "Knewave", "cis-googlewebfont-Kotta One" => "Kotta One", "cis-googlewebfont-Koulen" => "Koulen", "cis-googlewebfont-Kranky" => "Kranky", "cis-googlewebfont-Kreon" => "Kreon", "cis-googlewebfont-Kristi" => "Kristi", "cis-googlewebfont-Krona One" => "Krona One", "cis-googlewebfont-La Belle Aurore" => "La Belle Aurore", "cis-googlewebfont-Laila" => "Laila", "cis-googlewebfont-Lancelot" => "Lancelot", "cis-googlewebfont-Lato" => "Lato", "cis-googlewebfont-League Script" => "League Script", "cis-googlewebfont-Leckerli One" => "Leckerli One", "cis-googlewebfont-Ledger" => "Ledger", "cis-googlewebfont-Lekton" => "Lekton", "cis-googlewebfont-Lemon" => "Lemon", "cis-googlewebfont-Libre Baskerville" => "Libre Baskerville", "cis-googlewebfont-Life Savers" => "Life Savers", "cis-googlewebfont-Lilita One" => "Lilita One", "cis-googlewebfont-Lily Script One" => "Lily Script One", "cis-googlewebfont-Limelight" => "Limelight", "cis-googlewebfont-Linden Hill" => "Linden Hill", "cis-googlewebfont-Lobster" => "Lobster", "cis-googlewebfont-Lobster Two" => "Lobster Two", "cis-googlewebfont-Londrina Outline" => "Londrina Outline", "cis-googlewebfont-Londrina Shadow" => "Londrina Shadow", "cis-googlewebfont-Londrina Sketch" => "Londrina Sketch", "cis-googlewebfont-Londrina Solid" => "Londrina Solid", "cis-googlewebfont-Lora" => "Lora", "cis-googlewebfont-Love Ya Like A Sister" => "Love Ya Like A Sister", "cis-googlewebfont-Loved by the King" => "Loved by the King", "cis-googlewebfont-Lovers Quarrel" => "Lovers Quarrel", "cis-googlewebfont-Luckiest Guy" => "Luckiest Guy", "cis-googlewebfont-Lusitana" => "Lusitana", "cis-googlewebfont-Lustria" => "Lustria", "cis-googlewebfont-Macondo" => "Macondo", "cis-googlewebfont-Macondo Swash Caps" => "Macondo Swash Caps", "cis-googlewebfont-Magra" => "Magra", "cis-googlewebfont-Maiden Orange" => "Maiden Orange", "cis-googlewebfont-Mako" => "Mako", "cis-googlewebfont-Marcellus" => "Marcellus", "cis-googlewebfont-Marcellus SC" => "Marcellus SC", "cis-googlewebfont-Marck Script" => "Marck Script", "cis-googlewebfont-Margarine" => "Margarine", "cis-googlewebfont-Marko One" => "Marko One", "cis-googlewebfont-Marmelad" => "Marmelad", "cis-googlewebfont-Marvel" => "Marvel", "cis-googlewebfont-Mate" => "Mate", "cis-googlewebfont-Mate SC" => "Mate SC", "cis-googlewebfont-Maven Pro" => "Maven Pro", "cis-googlewebfont-McLaren" => "McLaren", "cis-googlewebfont-Meddon" => "Meddon", "cis-googlewebfont-MedievalSharp" => "MedievalSharp", "cis-googlewebfont-Medula One" => "Medula One", "cis-googlewebfont-Megrim" => "Megrim", "cis-googlewebfont-Meie Script" => "Meie Script", "cis-googlewebfont-Merienda" => "Merienda", "cis-googlewebfont-Merienda One" => "Merienda One", "cis-googlewebfont-Merriweather" => "Merriweather", "cis-googlewebfont-Merriweather Sans" => "Merriweather Sans", "cis-googlewebfont-Metal" => "Metal", "cis-googlewebfont-Metal Mania" => "Metal Mania", "cis-googlewebfont-Metamorphous" => "Metamorphous", "cis-googlewebfont-Metrophobic" => "Metrophobic", "cis-googlewebfont-Michroma" => "Michroma", "cis-googlewebfont-Milonga" => "Milonga", "cis-googlewebfont-Miltonian" => "Miltonian", "cis-googlewebfont-Miltonian Tattoo" => "Miltonian Tattoo", "cis-googlewebfont-Miniver" => "Miniver", "cis-googlewebfont-Miss Fajardose" => "Miss Fajardose", "cis-googlewebfont-Modern Antiqua" => "Modern Antiqua", "cis-googlewebfont-Molengo" => "Molengo", "cis-googlewebfont-Molle" => "Molle", "cis-googlewebfont-Monda" => "Monda", "cis-googlewebfont-Monofett" => "Monofett", "cis-googlewebfont-Monoton" => "Monoton", "cis-googlewebfont-Monsieur La Doulaise" => "Monsieur La Doulaise", "cis-googlewebfont-Montaga" => "Montaga", "cis-googlewebfont-Montez" => "Montez", "cis-googlewebfont-Montserrat" => "Montserrat", "cis-googlewebfont-Montserrat Alternates" => "Montserrat Alternates", "cis-googlewebfont-Montserrat Subrayada" => "Montserrat Subrayada", "cis-googlewebfont-Moul" => "Moul", "cis-googlewebfont-Moulpali" => "Moulpali", "cis-googlewebfont-Mountains of Christmas" => "Mountains of Christmas", "cis-googlewebfont-Mouse Memoirs" => "Mouse Memoirs", "cis-googlewebfont-Mr Bedfort" => "Mr Bedfort", "cis-googlewebfont-Mr Dafoe" => "Mr Dafoe", "cis-googlewebfont-Mr De Haviland" => "Mr De Haviland", "cis-googlewebfont-Mrs Saint Delafield" => "Mrs Saint Delafield", "cis-googlewebfont-Mrs Sheppards" => "Mrs Sheppards", "cis-googlewebfont-Muli" => "Muli", "cis-googlewebfont-Mystery Quest" => "Mystery Quest", "cis-googlewebfont-Neucha" => "Neucha", "cis-googlewebfont-Neuton" => "Neuton", "cis-googlewebfont-New Rocker" => "New Rocker", "cis-googlewebfont-News Cycle" => "News Cycle", "cis-googlewebfont-Niconne" => "Niconne", "cis-googlewebfont-Nixie One" => "Nixie One", "cis-googlewebfont-Nobile" => "Nobile", "cis-googlewebfont-Nokora" => "Nokora", "cis-googlewebfont-Norican" => "Norican", "cis-googlewebfont-Nosifer" => "Nosifer", "cis-googlewebfont-Nothing You Could Do" => "Nothing You Could Do", "cis-googlewebfont-Noticia Text" => "Noticia Text", "cis-googlewebfont-Noto Sans" => "Noto Sans", "cis-googlewebfont-Noto Serif" => "Noto Serif", "cis-googlewebfont-Nova Cut" => "Nova Cut", "cis-googlewebfont-Nova Flat" => "Nova Flat", "cis-googlewebfont-Nova Mono" => "Nova Mono", "cis-googlewebfont-Nova Oval" => "Nova Oval", "cis-googlewebfont-Nova Round" => "Nova Round", "cis-googlewebfont-Nova Script" => "Nova Script", "cis-googlewebfont-Nova Slim" => "Nova Slim", "cis-googlewebfont-Nova Square" => "Nova Square", "cis-googlewebfont-Numans" => "Numans", "cis-googlewebfont-Nunito" => "Nunito", "cis-googlewebfont-Odor Mean Chey" => "Odor Mean Chey", "cis-googlewebfont-Offside" => "Offside", "cis-googlewebfont-Old Standard TT" => "Old Standard TT", "cis-googlewebfont-Oldenburg" => "Oldenburg", "cis-googlewebfont-Oleo Script" => "Oleo Script", "cis-googlewebfont-Oleo Script Swash Caps" => "Oleo Script Swash Caps", "cis-googlewebfont-Open Sans" => "Open Sans", "cis-googlewebfont-Open Sans Condensed" => "Open Sans Condensed", "cis-googlewebfont-Oranienbaum" => "Oranienbaum", "cis-googlewebfont-Orbitron" => "Orbitron", "cis-googlewebfont-Oregano" => "Oregano", "cis-googlewebfont-Orienta" => "Orienta", "cis-googlewebfont-Original Surfer" => "Original Surfer", "cis-googlewebfont-Oswald" => "Oswald", "cis-googlewebfont-Over the Rainbow" => "Over the Rainbow", "cis-googlewebfont-Overlock" => "Overlock", "cis-googlewebfont-Overlock SC" => "Overlock SC", "cis-googlewebfont-Ovo" => "Ovo", "cis-googlewebfont-Oxygen" => "Oxygen", "cis-googlewebfont-Oxygen Mono" => "Oxygen Mono", "cis-googlewebfont-PT Mono" => "PT Mono", "cis-googlewebfont-PT Sans" => "PT Sans", "cis-googlewebfont-PT Sans Caption" => "PT Sans Caption", "cis-googlewebfont-PT Sans Narrow" => "PT Sans Narrow", "cis-googlewebfont-PT Serif" => "PT Serif", "cis-googlewebfont-PT Serif Caption" => "PT Serif Caption", "cis-googlewebfont-Pacifico" => "Pacifico", "cis-googlewebfont-Paprika" => "Paprika", "cis-googlewebfont-Parisienne" => "Parisienne", "cis-googlewebfont-Passero One" => "Passero One", "cis-googlewebfont-Passion One" => "Passion One", "cis-googlewebfont-Pathway Gothic One" => "Pathway Gothic One", "cis-googlewebfont-Patrick Hand" => "Patrick Hand", "cis-googlewebfont-Patrick Hand SC" => "Patrick Hand SC", "cis-googlewebfont-Patua One" => "Patua One", "cis-googlewebfont-Paytone One" => "Paytone One", "cis-googlewebfont-Peralta" => "Peralta", "cis-googlewebfont-Permanent Marker" => "Permanent Marker", "cis-googlewebfont-Petit Formal Script" => "Petit Formal Script", "cis-googlewebfont-Petrona" => "Petrona", "cis-googlewebfont-Philosopher" => "Philosopher", "cis-googlewebfont-Piedra" => "Piedra", "cis-googlewebfont-Pinyon Script" => "Pinyon Script", "cis-googlewebfont-Pirata One" => "Pirata One", "cis-googlewebfont-Plaster" => "Plaster", "cis-googlewebfont-Play" => "Play", "cis-googlewebfont-Playball" => "Playball", "cis-googlewebfont-Playfair Display" => "Playfair Display", "cis-googlewebfont-Playfair Display SC" => "Playfair Display SC", "cis-googlewebfont-Podkova" => "Podkova", "cis-googlewebfont-Poiret One" => "Poiret One", "cis-googlewebfont-Poller One" => "Poller One", "cis-googlewebfont-Poly" => "Poly", "cis-googlewebfont-Pompiere" => "Pompiere", "cis-googlewebfont-Pontano Sans" => "Pontano Sans", "cis-googlewebfont-Port Lligat Sans" => "Port Lligat Sans", "cis-googlewebfont-Port Lligat Slab" => "Port Lligat Slab", "cis-googlewebfont-Prata" => "Prata", "cis-googlewebfont-Preahvihear" => "Preahvihear", "cis-googlewebfont-Press Start 2P" => "Press Start 2P", "cis-googlewebfont-Princess Sofia" => "Princess Sofia", "cis-googlewebfont-Prociono" => "Prociono", "cis-googlewebfont-Prosto One" => "Prosto One", "cis-googlewebfont-Puritan" => "Puritan", "cis-googlewebfont-Purple Purse" => "Purple Purse", "cis-googlewebfont-Quando" => "Quando", "cis-googlewebfont-Quantico" => "Quantico", "cis-googlewebfont-Quattrocento" => "Quattrocento", "cis-googlewebfont-Quattrocento Sans" => "Quattrocento Sans", "cis-googlewebfont-Questrial" => "Questrial", "cis-googlewebfont-Quicksand" => "Quicksand", "cis-googlewebfont-Quintessential" => "Quintessential", "cis-googlewebfont-Qwigley" => "Qwigley", "cis-googlewebfont-Racing Sans One" => "Racing Sans One", "cis-googlewebfont-Radley" => "Radley", "cis-googlewebfont-Rajdhani" => "Rajdhani", "cis-googlewebfont-Raleway" => "Raleway", "cis-googlewebfont-Raleway Dots" => "Raleway Dots", "cis-googlewebfont-Rambla" => "Rambla", "cis-googlewebfont-Rammetto One" => "Rammetto One", "cis-googlewebfont-Ranchers" => "Ranchers", "cis-googlewebfont-Rancho" => "Rancho", "cis-googlewebfont-Rationale" => "Rationale", "cis-googlewebfont-Redressed" => "Redressed", "cis-googlewebfont-Reenie Beanie" => "Reenie Beanie", "cis-googlewebfont-Revalia" => "Revalia", "cis-googlewebfont-Ribeye" => "Ribeye", "cis-googlewebfont-Ribeye Marrow" => "Ribeye Marrow", "cis-googlewebfont-Righteous" => "Righteous", "cis-googlewebfont-Risque" => "Risque", "cis-googlewebfont-Roboto" => "Roboto", "cis-googlewebfont-Roboto Condensed" => "Roboto Condensed", "cis-googlewebfont-Roboto Slab" => "Roboto Slab", "cis-googlewebfont-Rochester" => "Rochester", "cis-googlewebfont-Rock Salt" => "Rock Salt", "cis-googlewebfont-Rokkitt" => "Rokkitt", "cis-googlewebfont-Romanesco" => "Romanesco", "cis-googlewebfont-Ropa Sans" => "Ropa Sans", "cis-googlewebfont-Rosario" => "Rosario", "cis-googlewebfont-Rosarivo" => "Rosarivo", "cis-googlewebfont-Rouge Script" => "Rouge Script", "cis-googlewebfont-Rozha One" => "Rozha One", "cis-googlewebfont-Rubik Mono One" => "Rubik Mono One", "cis-googlewebfont-Rubik One" => "Rubik One", "cis-googlewebfont-Ruda" => "Ruda", "cis-googlewebfont-Rufina" => "Rufina", "cis-googlewebfont-Ruge Boogie" => "Ruge Boogie", "cis-googlewebfont-Ruluko" => "Ruluko", "cis-googlewebfont-Rum Raisin" => "Rum Raisin", "cis-googlewebfont-Ruslan Display" => "Ruslan Display", "cis-googlewebfont-Russo One" => "Russo One", "cis-googlewebfont-Ruthie" => "Ruthie", "cis-googlewebfont-Rye" => "Rye", "cis-googlewebfont-Sacramento" => "Sacramento", "cis-googlewebfont-Sail" => "Sail", "cis-googlewebfont-Salsa" => "Salsa", "cis-googlewebfont-Sanchez" => "Sanchez", "cis-googlewebfont-Sancreek" => "Sancreek", "cis-googlewebfont-Sansita One" => "Sansita One", "cis-googlewebfont-Sarina" => "Sarina", "cis-googlewebfont-Sarpanch" => "Sarpanch", "cis-googlewebfont-Satisfy" => "Satisfy", "cis-googlewebfont-Scada" => "Scada", "cis-googlewebfont-Schoolbell" => "Schoolbell", "cis-googlewebfont-Seaweed Script" => "Seaweed Script", "cis-googlewebfont-Sevillana" => "Sevillana", "cis-googlewebfont-Seymour One" => "Seymour One", "cis-googlewebfont-Shadows Into Light" => "Shadows Into Light", "cis-googlewebfont-Shadows Into Light Two" => "Shadows Into Light Two", "cis-googlewebfont-Shanti" => "Shanti", "cis-googlewebfont-Share" => "Share", "cis-googlewebfont-Share Tech" => "Share Tech", "cis-googlewebfont-Share Tech Mono" => "Share Tech Mono", "cis-googlewebfont-Shojumaru" => "Shojumaru", "cis-googlewebfont-Short Stack" => "Short Stack", "cis-googlewebfont-Siemreap" => "Siemreap", "cis-googlewebfont-Sigmar One" => "Sigmar One", "cis-googlewebfont-Signika" => "Signika", "cis-googlewebfont-Signika Negative" => "Signika Negative", "cis-googlewebfont-Simonetta" => "Simonetta", "cis-googlewebfont-Sintony" => "Sintony", "cis-googlewebfont-Sirin Stencil" => "Sirin Stencil", "cis-googlewebfont-Six Caps" => "Six Caps", "cis-googlewebfont-Skranji" => "Skranji", "cis-googlewebfont-Slabo 13px" => "Slabo 13px", "cis-googlewebfont-Slabo 27px" => "Slabo 27px", "cis-googlewebfont-Slackey" => "Slackey", "cis-googlewebfont-Smokum" => "Smokum", "cis-googlewebfont-Smythe" => "Smythe", "cis-googlewebfont-Sniglet" => "Sniglet", "cis-googlewebfont-Snippet" => "Snippet", "cis-googlewebfont-Snowburst One" => "Snowburst One", "cis-googlewebfont-Sofadi One" => "Sofadi One", "cis-googlewebfont-Sofia" => "Sofia", "cis-googlewebfont-Sonsie One" => "Sonsie One", "cis-googlewebfont-Sorts Mill Goudy" => "Sorts Mill Goudy", "cis-googlewebfont-Source Code Pro" => "Source Code Pro", "cis-googlewebfont-Source Sans Pro" => "Source Sans Pro", "cis-googlewebfont-Source Serif Pro" => "Source Serif Pro", "cis-googlewebfont-Special Elite" => "Special Elite", "cis-googlewebfont-Spicy Rice" => "Spicy Rice", "cis-googlewebfont-Spinnaker" => "Spinnaker", "cis-googlewebfont-Spirax" => "Spirax", "cis-googlewebfont-Squada One" => "Squada One", "cis-googlewebfont-Stalemate" => "Stalemate", "cis-googlewebfont-Stalinist One" => "Stalinist One", "cis-googlewebfont-Stardos Stencil" => "Stardos Stencil", "cis-googlewebfont-Stint Ultra Condensed" => "Stint Ultra Condensed", "cis-googlewebfont-Stint Ultra Expanded" => "Stint Ultra Expanded", "cis-googlewebfont-Stoke" => "Stoke", "cis-googlewebfont-Strait" => "Strait", "cis-googlewebfont-Sue Ellen Francisco" => "Sue Ellen Francisco", "cis-googlewebfont-Sunshiney" => "Sunshiney", "cis-googlewebfont-Supermercado One" => "Supermercado One", "cis-googlewebfont-Suwannaphum" => "Suwannaphum", "cis-googlewebfont-Swanky and Moo Moo" => "Swanky and Moo Moo", "cis-googlewebfont-Syncopate" => "Syncopate", "cis-googlewebfont-Tangerine" => "Tangerine", "cis-googlewebfont-Taprom" => "Taprom", "cis-googlewebfont-Tauri" => "Tauri", "cis-googlewebfont-Teko" => "Teko", "cis-googlewebfont-Telex" => "Telex", "cis-googlewebfont-Tenor Sans" => "Tenor Sans", "cis-googlewebfont-Text Me One" => "Text Me One", "cis-googlewebfont-The Girl Next Door" => "The Girl Next Door", "cis-googlewebfont-Tienne" => "Tienne", "cis-googlewebfont-Tinos" => "Tinos", "cis-googlewebfont-Titan One" => "Titan One", "cis-googlewebfont-Titillium Web" => "Titillium Web", "cis-googlewebfont-Trade Winds" => "Trade Winds", "cis-googlewebfont-Trocchi" => "Trocchi", "cis-googlewebfont-Trochut" => "Trochut", "cis-googlewebfont-Trykker" => "Trykker", "cis-googlewebfont-Tulpen One" => "Tulpen One", "cis-googlewebfont-Ubuntu" => "Ubuntu", "cis-googlewebfont-Ubuntu Condensed" => "Ubuntu Condensed", "cis-googlewebfont-Ubuntu Mono" => "Ubuntu Mono", "cis-googlewebfont-Ultra" => "Ultra", "cis-googlewebfont-Uncial Antiqua" => "Uncial Antiqua", "cis-googlewebfont-Underdog" => "Underdog", "cis-googlewebfont-Unica One" => "Unica One", "cis-googlewebfont-UnifrakturCook" => "UnifrakturCook", "cis-googlewebfont-UnifrakturMaguntia" => "UnifrakturMaguntia", "cis-googlewebfont-Unkempt" => "Unkempt", "cis-googlewebfont-Unlock" => "Unlock", "cis-googlewebfont-Unna" => "Unna", "cis-googlewebfont-VT323" => "VT323", "cis-googlewebfont-Vampiro One" => "Vampiro One", "cis-googlewebfont-Varela" => "Varela", "cis-googlewebfont-Varela Round" => "Varela Round", "cis-googlewebfont-Vast Shadow" => "Vast Shadow", "cis-googlewebfont-Vesper Libre" => "Vesper Libre", "cis-googlewebfont-Vibur" => "Vibur", "cis-googlewebfont-Vidaloka" => "Vidaloka", "cis-googlewebfont-Viga" => "Viga", "cis-googlewebfont-Voces" => "Voces", "cis-googlewebfont-Volkhov" => "Volkhov", "cis-googlewebfont-Vollkorn" => "Vollkorn", "cis-googlewebfont-Voltaire" => "Voltaire", "cis-googlewebfont-Waiting for the Sunrise" => "Waiting for the Sunrise", "cis-googlewebfont-Wallpoet" => "Wallpoet", "cis-googlewebfont-Walter Turncoat" => "Walter Turncoat", "cis-googlewebfont-Warnes" => "Warnes", "cis-googlewebfont-Wellfleet" => "Wellfleet", "cis-googlewebfont-Wendy One" => "Wendy One", "cis-googlewebfont-Wire One" => "Wire One", "cis-googlewebfont-Yanone Kaffeesatz" => "Yanone Kaffeesatz", "cis-googlewebfont-Yellowtail" => "Yellowtail", "cis-googlewebfont-Yeseva One" => "Yeseva One", "cis-googlewebfont-Yesteryear" => "Yesteryear", "cis-googlewebfont-Zeyada" => "Zeyada") );
$font_effects = array ("cis_font_effect_none" => "None", "cis_font_effect_emboss" => "Emboss", "cis_font_effect_fire" => "Fire", "cis_font_effect_fire_animation" => "Fire Animation", "cis_font_effect_neon" => "Neon", "cis_font_effect_outline" => "Outline", "cis_font_effect_shadow_multiple" => "Shadow Multiple", "cis_font_effect_3d" => "3D", "cis_font_effect_3d_float" => "3D Float"); 
?>
<form action="<?php echo JRoute::_('index.php?option=com_creativeimageslider&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<div id="c_div">
		<div>
			<table cellpadding="0" cellspacing="0" style="width: 100%;"><tr><td style="width: 440px;vertical-align: top;" align="top">
			<?php if(($this->max_id < 1) || ($this->item->id != 0)) {?>
			<fieldset>
				<?php if((int)$this->item->id != 0 ) {?><h3 style="font-size: 16px;font-weight: normal;font-style: italic;">To manage <b>slider items</b>, go to <a href="index.php?option=com_creativeimageslider&view=creativeimages&filter_slider_id=<?php echo $this->item->id;?>" target="_blank">items page.</a></h3><?php } ?>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<div style="clear: both;margin: 0px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MAIN_OPTIONS_LABEL' );?></div>
							<?php foreach($this->form->getFieldset() as $field): ?>
								<div class="cis_control_label"><?php echo $field->label;?></div>
								<div class="cis_controls"><?php echo $field->input;?></div>
							<?php endforeach; ?>
						
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_id_category" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CATEGORY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CATEGORY_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? 1 : $this->item->id_category;
									//$opts = array(1 => 'Published', 0 => 'Unpublished');
									$opts = $cat_options;
									$options = array();
									echo '<select id="cis_id_category" class="" name="id_category">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>
						
							<div style="display: none;">
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_id_template" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEMPLATE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEMPLATE_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? 1 : $this->item->id_template;
									//$opts = array(1 => 'Published', 0 => 'Unpublished');
									$opts = $tmp_options;
									$options = array();
									echo '<select id="cis_id_template" class="" name="id_template">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>
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
						


							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_OPTIONS_LABEL' );?></div>
							
							<div class="cis_control_label"><label id="" for="cis_width" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_WIDTH_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_WIDTH_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="width" id="cis_width" value="<?php echo $v = $this->item->id == 0 ? '100%' : $this->item->width;?>" class="inputbox" size="40" required=""  ></div>
						
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_height" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_HEIGHT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_HEIGHT_LABEL' );?><span class="star">&nbsp;*</span></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['height'] : $this->item->height;
									echo '<select id="cis_height" class="cis_has_slider" name="height">';
									for($k = 50; $k <= 700; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_open_event" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_OPENEVENT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_OPENEVENT_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_open_event'] : $this->item->popup_open_event;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_POPUP_ZOOM'), // Open Popup onclick of Zoom icon 
													1 => JText::_('COM_CREATIVEIMAGESLIDER_POPUP_OVERLAY'), // Open Popup onclick of overlay
													2 => JText::_('COM_CREATIVEIMAGESLIDER_POPUP_BUTTON'), // Open Popup onclick of overlay button
													3 => JText::_('COM_CREATIVEIMAGESLIDER_POPUP_NONE') // none
												);
									$options = array();
									echo '<select id="cis_popup_open_event" class="" name="popup_open_event">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_link_open_event" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_LINK_OPENEVENT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_LINK_OPENEVENT_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['link_open_event'] : $this->item->link_open_event;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_LINK_ZOOM'), // Open Link onclick of Link icon 
													1 => JText::_('COM_CREATIVEIMAGESLIDER_LINK_OVERLAY'), // Open Link onclick of overlay
													2 => JText::_('COM_CREATIVEIMAGESLIDER_LINK_BUTTON'), // Open Link onclick of overlay button
													3 => JText::_('COM_CREATIVEIMAGESLIDER_LINK_NONE') // none
												);
									$options = array();
									echo '<select id="cis_link_open_event" class="" name="link_open_event">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>


							<!-- Version 3.0.0 BETA options	 -->

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_touch_enabled" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TOUCH_ENABLED_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TOUCH_ENABLED_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_touch_enabled'] : $this->item->cis_touch_enabled;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_TOUCH_DISABLED'), // disabled
													1 => JText::_('COM_CREATIVEIMAGESLIDER_TOUCH_ENABLED'), // enabled
													2 => JText::_('COM_CREATIVEIMAGESLIDER_TOUCH_MOBILE') // only on mobile
												);
									$options = array();
									echo '<select id="cis_touch_enabled" class="" name="cis_touch_enabled">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_touch_type" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TOUCH_TYPE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TOUCH_TYPE_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_touch_type'] : $this->item->cis_touch_type;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_TOUCH_TYPE_1'),
													1 => JText::_('COM_CREATIVEIMAGESLIDER_TOUCH_TYPE_2'),
													2 => JText::_('COM_CREATIVEIMAGESLIDER_TOUCH_TYPE_3')
												);
									$options = array();
									echo '<select id="cis_touch_type" class="" name="cis_touch_type">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_inf_scroll_enabled" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_INF_SCROLL_ENABLED_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_INF_SCROLL_ENABLED_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_inf_scroll_enabled'] : $this->item->cis_inf_scroll_enabled;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_INF_SCROLL_DISABLED'), // disabled
													1 => JText::_('COM_CREATIVEIMAGESLIDER_INF_SCROLL_ENABLED') // enabled
												);
									$options = array();
									echo '<select id="cis_inf_scroll_enabled" class="" name="cis_inf_scroll_enabled">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_mouse_scroll_enabled" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOUSE_SCROLL_ENABLED_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOUSE_SCROLL_ENABLED_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_mouse_scroll_enabled'] : $this->item->cis_mouse_scroll_enabled;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_MOUSE_SCROLL_DISABLED'), // disabled
													1 => JText::_('COM_CREATIVEIMAGESLIDER_MOUSE_SCROLL_ENABLED') // enabled
												);
									$options = array();
									echo '<select id="cis_mouse_scroll_enabled" class="" name="cis_mouse_scroll_enabled">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_item_correction_enabled" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEM_CORRECTION_ENABLED_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEM_CORRECTION_ENABLED_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_item_correction_enabled'] : $this->item->cis_item_correction_enabled;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_ITEM_CORRECTION_DISABLED'), // disabled
													1 => JText::_('COM_CREATIVEIMAGESLIDER_ITEM_CORRECTION_ENABLED') // enabled
												);
									$options = array();
									echo '<select id="cis_item_correction_enabled" class="" name="cis_item_correction_enabled">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_animation_type" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ANIMATION_TYPE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ANIMATION_TYPE_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_animation_type'] : $this->item->cis_animation_type;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_ANIMATION_TYPE_CSS'), // disabled
													1 => JText::_('COM_CREATIVEIMAGESLIDER_ANIMATION_TYPE_JAVASCRIPT') // enabled
												);
									$options = array();
									echo '<select id="cis_item_correction_enabled" class="" name="cis_animation_type">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

<!-- Slider Appearance ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_APPEARANCE_LABEL' );?></div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="slider_full_width" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_FULL_SIZE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_FULL_SIZE_LABEL' );?></label></div>
							<div class="cis_controls">
								<?php
								$default = $this->item->id == 0 ? $slider_global_options['slider_full_size'] : $this->item->slider_full_size;
								$opts = array(
									0 => JText::_('COM_CREATIVEIMAGESLIDER_NO'), // disabled
									1 => JText::_('COM_CREATIVEIMAGESLIDER_YES') // enabled
								);
								$options = array();
								echo '<select id="slider_full_size" class="" name="slider_full_size">';
								foreach($opts as $key=>$value) :
									$selected = $key == $default ? 'selected="selected"' : '';
									echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
								endforeach;
								echo '</select>';
								?>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_item_hover_effect" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEMS_APPEARANCE_EFFECT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEMS_APPEARANCE_EFFECT_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_items_appearance_effect'] : $this->item->cis_items_appearance_effect;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_ITEMS_APPEARANCE_EFFECT_0'), // disabled
													1 => JText::_('COM_CREATIVEIMAGESLIDER_ITEMS_APPEARANCE_EFFECT_1') // enabled
												);
									$options = array();
									echo '<select id="cis_items_appearance_effect" class="" name="cis_items_appearance_effect">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_item_hover_effect" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEMS_HOVER_EFFECT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEMS_HOVER_EFFECT_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_item_hover_effect'] : $this->item->cis_item_hover_effect;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_ITEMS_HOVER_EFFECT_0'), // disabled
													1 => JText::_('COM_CREATIVEIMAGESLIDER_ITEMS_HOVER_EFFECT_1'), // enabled
													2 => JText::_('COM_CREATIVEIMAGESLIDER_ITEMS_HOVER_EFFECT_2') // enabled
												);
									$options = array();
									echo '<select id="cis_item_hover_effect" class="" name="cis_item_hover_effect">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>


						
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label style="margin-top: 4px;" id="" for="cis_bgcolor" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_BGCOLOR_LABEL' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_BGCOLOR_DESCRIPTION' );?></label></div>
							<div class="cis_controls">
								<div id="colorSelector" class="colorSelector" style="float: left;"><div style="background-color: <?php echo $v = $this->item->id == 0 ? '#000000' : $this->item->bgcolor;?>"></div></div>
               					<input class="colorSelector" type="text" value="<?php echo $v = $this->item->id == 0 ? '#000000' : $this->item->bgcolor;?>" name="bgcolor" roll=""  id="cis_bgcolor" style="width: 162px;margin: 4px 0 0 6px;" />
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_itemsoffset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEMSOFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ITEMSOFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['itemsoffset'] : $this->item->itemsoffset;
									$opts = array(0 => '0px', 1 => '1px', 2 => '2px', 3 => '3px', 4 => '4px', 5 => '5px', 6 => '6px', 7 => '7px', 8 => '8px', 9 => '9px', 10 => '10px', 11 => '11px', 12 => '12px', 13 => '13px', 14 => '14px', 15 => '15px', 16 => '16px', 17 => '17px', 18 => '18px', 19 => '19px', 20 => '20px');
									$options = array();
									echo '<select id="cis_itemsoffset" class="cis_has_slider" name="itemsoffset">';
									for($k = 0; $k <= 40; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_margintop" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MARGINTOP_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MARGINTOP_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['margintop'] : $this->item->margintop;
									$opts = array(0 => '0px', 1 => '1px', 2 => '2px', 3 => '3px', 4 => '4px', 5 => '5px', 6 => '6px', 7 => '7px', 8 => '8px', 9 => '9px', 10 => '10px', 11 => '11px', 12 => '12px', 13 => '13px', 14 => '14px', 15 => '15px', 16 => '16px', 17 => '17px', 18 => '18px', 19 => '19px', 20 => '20px');
									$options = array();
									echo '<select id="cis_margintop" class="cis_has_slider" name="margintop">';
									for($k = 0; $k <= 40; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_marginbottom" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MARGINBOTTOM_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MARGINBOTTOM_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['marginbottom'] : $this->item->marginbottom;
									$opts = array(0 => '0px', 1 => '1px', 2 => '2px', 3 => '3px', 4 => '4px', 5 => '5px', 6 => '6px', 7 => '7px', 8 => '8px', 9 => '9px', 10 => '10px', 11 => '11px', 12 => '12px', 13 => '13px', 14 => '14px', 15 => '15px', 16 => '16px', 17 => '17px', 18 => '18px', 19 => '19px', 20 => '20px');
									$options = array();
									echo '<select id="cis_marginbottom" class="cis_has_slider" name="marginbottom">';
									for($k = 0; $k <= 40; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_paddingtop" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_PADDINGTOP_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_PADDINGTOP_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['paddingtop'] : $this->item->paddingtop;
									$opts = array(0 => '0px', 1 => '1px', 2 => '2px', 3 => '3px', 4 => '4px', 5 => '5px', 6 => '6px', 7 => '7px', 8 => '8px', 9 => '9px', 10 => '10px', 11 => '11px', 12 => '12px', 13 => '13px', 14 => '14px', 15 => '15px', 16 => '16px', 17 => '17px', 18 => '18px', 19 => '19px', 20 => '20px');
									$options = array();
									echo '<select id="cis_paddingtop" class="cis_has_slider" name="paddingtop">';
									for($k = 0; $k <= 40; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_paddingbottom" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_PADDINGBOTTOM_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_PADDINGBOTTOM_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['paddingbottom'] : $this->item->paddingbottom;
									$opts = array(0 => '0px', 1 => '1px', 2 => '2px', 3 => '3px', 4 => '4px', 5 => '5px', 6 => '6px', 7 => '7px', 8 => '8px', 9 => '9px', 10 => '10px', 11 => '11px', 12 => '12px', 13 => '13px', 14 => '14px', 15 => '15px', 16 => '16px', 17 => '17px', 18 => '18px', 19 => '19px', 20 => '20px');
									$options = array();
									echo '<select id="cis_paddingbottom" class="cis_has_slider" name="paddingbottom">';
									for($k = 0; $k <= 40; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							

<!-- OVERLAY ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAY_OPTIONS_LABEL' );?></div>
							
							<!-- TODO -->
							<div class="cis_control_label"><label id="" for="cis_overlay_type" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAY_TYPE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAY_TYPE_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_overlay_type'] : $this->item->cis_overlay_type;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_OVERLAY_TYPE_1'), 
													1 => JText::_('COM_CREATIVEIMAGESLIDER_OVERLAY_TYPE_2')
												);
									$options = array();
									echo '<select id="cis_overlay_type" class="" name="cis_overlay_type">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_overlayanimationtype" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYANIMTYPE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYANIMTYPE_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['overlayanimationtype'] : $this->item->overlayanimationtype;
									$opts = array(
													0 => JText::_('COM_CREATIVEIMAGESLIDER_SLIDE_UP'), 
													1 => JText::_('COM_CREATIVEIMAGESLIDER_KEEP_VISIBLE'), 
													2 => JText::_('COM_CREATIVEIMAGESLIDER_FADE'),
													3 => JText::_('COM_CREATIVEIMAGESLIDER_FOLLOW_MOUSE'),
													4 => JText::_('COM_CREATIVEIMAGESLIDER_HIDDEN')
												);
									$options = array();
									echo '<select id="cis_overlayanimationtype" class="" name="overlayanimationtype">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label  style="margin-top: 4px;" id="" for="cis_overlaycolor" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYCOLOR_LABEL' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYCOLOR_DESCRIPTION' );?></label></div>
							<div class="cis_controls">
								<div id="colorSelector" class="colorSelector" style="float: left;"><div style="background-color: <?php echo $v = $this->item->id == 0 ? $slider_global_options['overlaycolor'] : $this->item->overlaycolor;?>"></div></div>
               					<input class="colorSelector" type="text" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['overlaycolor'] : $this->item->overlaycolor;?>" name="overlaycolor" roll=""  id="cis_overlaycolor" style="width: 162px;margin: 4px 0 0 6px;" />
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_overlayopacity" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYOPACITY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYOPACITY_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['overlayopacity'] : $this->item->overlayopacity;
									$opts = array(0 => '0', 10 => '10%', 20 => '20%', 30 => '30%', 40 => '40%', 50 => '50%', 60 => '60%', 70 => '70%', 80 => '80%', 90 => '90%', 100 => '100%');
									$options = array();
									echo '<select id="cis_overlayopacity" class="cis_has_slider" name="overlayopacity">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_ov_items_offset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OV_ITEMS_OFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OV_ITEMS_OFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['ov_items_offset'] : $this->item->ov_items_offset;
									echo '<select id="cis_ov_items_offset" class="cis_has_slider" name="ov_items_offset">';
									for($k = 0; $k <= 50; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_ov_items_m_offset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OV_ITEMS_M_OFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OV_ITEMS_M_OFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['ov_items_m_offset'] : $this->item->ov_items_m_offset;
									echo '<select id="cis_ov_items_m_offset" class="cis_has_slider" name="ov_items_m_offset">';
									for($k = -250; $k <= 250; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
<!-- Caption Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CAPTION_OPTIONS_LABEL' );?></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_showreadmore" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SHOWREADMORE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SHOWREADMORE_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['showreadmore'] : $this->item->showreadmore;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_NO'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_YES'));
									$options = array();
									echo '<select id="cis_showreadmore" class="" name="showreadmore">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>


							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_captionalign" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CAPTIONALIGN_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CAPTIONALIGN_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['captionalign'] : $this->item->captionalign;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_LEFT'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_CENTER'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_RIGHT'));
									$options = array();
									echo '<select id="cis_captionalign" class="cis_has_slider" name="captionalign">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_captionmargin" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CAPTIONMARGIN_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CAPTIONMARGIN_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="captionmargin" id="cis_captionmargin" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['captionmargin'] : $this->item->captionmargin;?>" class="inputbox" size="40" required="" ></div>
						
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label  style="margin-top: 4px;" id="" for="cis_textcolor" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEXTCOLOR_LABEL' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEXTCOLOR_DESCRIPTION' );?></label></div>
							<div class="cis_controls">
								<div id="colorSelector" class="colorSelector" style="float: left;"><div style="background-color: <?php echo $v = $this->item->id == 0 ? $slider_global_options['textcolor'] : $this->item->textcolor;?>"></div></div>
               					<input class="colorSelector" type="text" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['textcolor'] : $this->item->textcolor;?>" name="textcolor" roll=""  id="cis_textcolor" style="width: 162px;margin: 4px 0 0 6px;" />
							</div>
									
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label  style="margin-top: 4px;" id="" for="cis_textshadowcolor" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEXTSHAOWCOLOR_LABEL' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEXTSHAOWCOLOR_DESCRIPTION' );?></label></div>
							<div class="cis_controls">
								<div id="colorSelector" class="colorSelector" style="float: left;"><div style="background-color: <?php echo $v = $this->item->id == 0 ? $slider_global_options['textshadowcolor'] : $this->item->textshadowcolor;?>"></div></div>
               					<input class="colorSelector" type="text" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['textshadowcolor'] : $this->item->textshadowcolor;?>" name="textshadowcolor" roll=""  id="cis_textshadowcolor" style="width: 162px;margin: 4px 0 0 6px;" />
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_overlayfontsize" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYFONTSIZE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_OVERLAYFONTSIZE_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['overlayfontsize'] : $this->item->overlayfontsize;
									$opts = array(5 => '5px', 6 => '6px', 7 => '7px', 8 => '8px', 9 => '9px', 10 => '10px', 11 => '11px', 12 => '12px', 13 => '13px', 14 => '14px', 15 => '15px', 16 => '16px', 17 => '17px', 18 => '18px', 19 => '19px', 20 => '20px', 21 => '21px', 22 => '22px', 23 => '23px', 24 => '24px', 25 => '25px', 26 => '26px', 27 => '27px', 28 => '28px', 29 => '29px', 30 => '30px', 31 => '31px', 32 => '32px', 33 => '33px', 34 => '34px', 35 => '35px', 36 => '36px');
									$options = array();
									echo '<select id="cis_overlayfontsize" class="cis_has_slider" name="overlayfontsize">';
									for($k = 5; $k <= 50; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_textshadowsize" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEXTSHADOWSIZE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_TEXTSHADOWSIZE_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['textshadowsize'] : $this->item->textshadowsize;
									$opts = array(0 => JText::_( 'COM_CREATIVEIMAGESLIDER_NONE_LABEL' ), 1 => JText::_( 'COM_CREATIVEIMAGESLIDER_LOW_LABEL' ), 2 => JText::_( 'COM_CREATIVEIMAGESLIDER_NORMAL_LABEL' ), 3 => JText::_( 'COM_CREATIVEIMAGESLIDER_HIGH_LABEL' ));
									$options = array();
									echo '<select id="cis_textshadowsize" class="cis_has_slider" name="textshadowsize">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_font_family" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_FONT_FAMILY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_FONT_FAMILY_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_font_family'] : $this->item->cis_font_family;
									$options = array();
									echo '<select id="cis_font_family" class="" name="cis_font_family">';
									$q = 0;
				                	foreach($fonts_array as $label => $val_array) {
				                		echo '<optgroup label="'.$label.'">';
				                		foreach($val_array as $k => $val) {
				                			$def_class=$q == 0 ? '' : 'googlefont';
					                		$selected = $default == $k ? 'selected="selected"' : '';
					                		echo '<option class="'.$def_class.'" value="'.$k.'" '.$selected.'>'.$val.'</option>';
				                		}
				                		echo '</optgroup>';
				                		$q ++;
				                	}
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_font_effect" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_FONT_EFFECT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_FONT_EFFECT_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_font_effect'] : $this->item->cis_font_effect;
									$options = array();
									echo '<select id="cis_font_effect"  name="cis_font_effect">';
									foreach($font_effects as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>
<!-- Icons Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICON_OPTIONS_LABEL' );?></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_icons_size" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_SIZE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_SIZE_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['icons_size'] : $this->item->icons_size;
									echo '<select id="cis_icons_size" class="cis_has_slider" name="icons_size">';
									for($k = 16; $k <= 64; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_icons_margin" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_MARGIN_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_MARGIN_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['icons_margin'] : $this->item->icons_margin;
									echo '<select id="cis_icons_margin" class="cis_has_slider" name="icons_margin">';
									for($k = 0; $k <= 30; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_icons_offset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_OFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_OFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['icons_offset'] : $this->item->icons_offset;
									echo '<select id="cis_icons_offset" class="cis_has_slider" name="icons_offset">';
									for($k = 0; $k <= 30; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_icons_valign" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_VALIGN_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_VALIGN_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['icons_valign'] : $this->item->icons_valign;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_POSITION_TOP'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_POSITION_CENTER'));
									$options = array();
									echo '<select id="cis_icons_valign" class="" name="icons_valign">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_icons_color" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_COLOR_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_COLOR_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['icons_color'] : $this->item->icons_color;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_COLOR_BLACK'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_COLOR_WHITE'));
									$options = array();
									echo '<select id="cis_icons_color" class="" name="icons_color">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_icons_animation" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_ANIMATION_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ICONS_ANIMATION_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['icons_animation'] : $this->item->icons_animation;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_ANIM_0'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_ANIM_1'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_ANIM_2'), 3 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_ANIM_3'), 4 => JText::_('COM_CREATIVEIMAGESLIDER_ICON_ANIM_NONE'));
									$options = array();
									echo '<select id="cis_icons_animation" class="" name="icons_animation">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

<!-- Button Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_BUTTON_OPTIONS_LABEL' );?></div>
		
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_readmoretext" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMORETEXT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMORETEXT_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="readmoretext" id="cis_readmoretext" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['readmoretext'] : $this->item->readmoretext;?>" class="inputbox" size="40" required="" ></div>
						
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_readmoremargin" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMOREMARGIN_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMOREMARGIN_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="readmoremargin" id="cis_readmoremargin" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['readmoremargin'] : $this->item->readmoremargin;?>" class="inputbox" size="40" required="" ></div>
					
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_readmorestyle" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMORESTYLE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMORESTYLE_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['readmorestyle'] : $this->item->readmorestyle;
									$opts = array('gray' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESTYLE_GRAY'), 'blue' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESTYLE_BLUE'), 'raver' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESTYLE_RAVER'), 'green' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESTYLE_GREEN'), 'orange' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESTYLE_ORANGE'), 'red' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESTYLE_RED'), 'black' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESTYLE_BLACK'));
									$options = array();
									echo '<select id="cis_readmorestyle" class="cis_has_slider" name="readmorestyle">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_readmorealign" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMOREALIGN_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMOREALIGN_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['readmorealign'] : $this->item->readmorealign;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_LEFT'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_CENTER'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_RIGHT'));
									$options = array();
									echo '<select id="cis_readmorealign" class="cis_has_slider" name="readmorealign">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_readmoresize" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMORESIZE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMORESIZE_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['readmoresize'] : $this->item->readmoresize;
									$opts = array('mini' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESIZE_MINI'), 'small' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESIZE_SMALL'), 'normal' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESIZE_NORMAL'), 'large' => JText::_('COM_CREATIVEIMAGESLIDER_READMORESIZE_LARGE'));
									$options = array();
									echo '<select id="cis_readmoresize" class="cis_has_slider" name="readmoresize">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
						
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_readmoreicon" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMOREICON_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_READMOREICON_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['readmoreicon'] : $this->item->readmoreicon;
									$opts = array('none' =>  JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_NONE'), 'play' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_PLAY'), 'ok' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_OK'), 'check' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_CHECK'), 'pencil' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_PENCIL'), 'star' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_STAR'), 'star-empty' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_STAR_EMPTY'), 'user' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_USER'), 'download' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_DOWNLOAD'), 'home' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_HOME'), 'music' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_MUSIC'), 'list' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_LIST'), 'glass' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_GLASS'), 'time' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_TIME'), 'tag' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_TAG'), 'tags' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_TAGS'), 'book' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_BOOK'), 'picture' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_PICTURE'), 'tint' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_TINT'), 'fire' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_FIRE'), 'comment' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_COMMENT'), 'magnet' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_MAGNET'), 'chevron-down' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_CHEVRON_DOWN'), 'chevron-up' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_CHEVRON_UP'), 'bell' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_BELL'), 'like' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_LIKE'), 'globe' => JText::_('COM_CREATIVEIMAGESLIDER_READMOREICON_GLOBE'));
									$options = array();
									echo '<select id="cis_readmoreicon" class="cis_has_slider" name="readmoreicon">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>


							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_button_font_family" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_FONT_FAMILY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_FONT_FAMILY_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['cis_button_font_family'] : $this->item->cis_button_font_family;
									$options = array();
									echo '<select id="cis_button_font_family" class="" name="cis_button_font_family">';
									$q = 0;
				                	foreach($fonts_array as $label => $val_array) {
				                		echo '<optgroup label="'.$label.'">';
				                		foreach($val_array as $k => $val) {
				                			$def_class=$q == 0 ? '' : 'googlefont';
					                		$selected = $default == $k ? 'selected="selected"' : '';
					                		echo '<option class="'.$def_class.'" value="'.$k.'" '.$selected.'>'.$val.'</option>';
				                		}
				                		echo '</optgroup>';
				                		$q ++;
				                	}
									echo '</select>';
									?>
							</div>



<!-- Arrow Options ****************************************************************************************-->

							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_OPTIONS_LABEL' );?></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_arrow_template" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_TEMPLATE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_TEMPLATE_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['arrow_template'] : $this->item->arrow_template;
									echo '<select id="cis_arrow_template" class="cis_has_slider" name="arrow_template">';
									for($k = 1; $k <= 45; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">Tmp-'.$k.'</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_arrow_width" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_WIDTH_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_WIDTH_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['arrow_width'] : $this->item->arrow_width;
									echo '<select id="cis_arrow_width" class="cis_has_slider" name="arrow_width">';
									for($k = 12; $k <= 64; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_arrow_left_offset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_LEFT_OFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_LEFT_OFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['arrow_left_offset'] : $this->item->arrow_left_offset;
									echo '<select id="cis_arrow_left_offset" class="cis_has_slider" name="arrow_left_offset">';
									for($k = 0; $k <= 100; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_arrow_center_offset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_CENTER_OFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_CENTER_OFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['arrow_center_offset'] : $this->item->arrow_center_offset;
									echo '<select id="cis_arrow_center_offset" class="cis_has_slider" name="arrow_center_offset">';
									for($k = -250; $k <= 250; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_arrow_passive_opacity" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_PASSIVE_OPACITY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_ARROW_PASSIVE_OPACITY_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['arrow_passive_opacity'] : $this->item->arrow_passive_opacity;
									echo '<select id="cis_arrow_passive_opacity" class="cis_has_slider" name="arrow_passive_opacity">';
									for($k = 0; $k <= 100; $k += 5) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'%</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_showarrows" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SHOWARROWS_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SHOWARROWS_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['showarrows'] : $this->item->showarrows;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_NEVER'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ONHOVER'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ALWAYS'));
									$options = array();
									echo '<select id="cis_showarrows" class="cis_has_slider" name="showarrows">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							
							

							
<!-- AutoPlay Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_OPTIONS_LABEL' );?></div>
							
							<div class="cis_control_label"><label id="" for="cis_move_step" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOVESTEP_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOVESTEP_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['move_step'] : $this->item->move_step;
									$opts = array(1 => 1, 2 => 2, 3 => 3, 4=>4, 5=>5,25=>JText::_('COM_CREATIVEIMAGESLIDER_MAX_VISIBLE'));
									$options = array();
									echo '<select id="cis_move_step" class="" name="move_step">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>







							<div style="display: none">
							<div style="clear: both;height: 5px;"></div>EMPTY OPTION, CAN BE USED LATER
							<div class="cis_control_label"><label id="" for="cis_move_time" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOVETIME_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOVETIME_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="move_time" id="cis_move_time" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['move_time'] : $this->item->move_time;?>" class="inputbox" size="40" required="" ></div>
							</div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_move_ease" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOVEEASE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_MOVEEASE_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="move_ease" id="cis_move_ease" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['move_ease'] : $this->item->move_ease;?>" class="inputbox" size="40" required="" ></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_autoplay" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['autoplay'] : $this->item->autoplay;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_AUTOPLAY_NEVER'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_AUTOPLAY_EVENLY'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_AUTOPLAY_STEPS'));
									$options = array();
									echo '<select id="cis_autoplay" class="" name="autoplay">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_autoplay_start_timeout" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_START_TIMEOUT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_START_TIMEOUT_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="autoplay_start_timeout" id="cis_autoplay_start_timeout" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['autoplay_start_timeout'] : $this->item->autoplay_start_timeout;?>" class="inputbox" size="40" required="" ></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_autoplay_hover_timeout" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_HOVER_TIMEOUT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_HOVER_TIMEOUT_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="autoplay_hover_timeout" id="cis_autoplay_hover_timeout" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['autoplay_hover_timeout'] : $this->item->autoplay_hover_timeout;?>" class="inputbox" size="40" required="" ></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_autoplay_step_timeout" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_STEP_TIMEOUT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_STEP_TIMEOUT_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="autoplay_step_timeout" id="cis_autoplay_step_timeout" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['autoplay_step_timeout'] : $this->item->autoplay_step_timeout;?>" class="inputbox" size="40" required="" ></div>
							
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_autoplay_evenly_speed" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_EVENLY_SPEED_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_AUTOPLAY_EVENLY_SPEED_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="autoplay_evenly_speed" id="cis_autoplay_evenly_speed" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['autoplay_evenly_speed'] : $this->item->autoplay_evenly_speed;?>" class="inputbox" size="40" required="" ></div>
							

<!-- Popup Options ****************************************************************************************-->

							<div style="clear: both;margin: 25px 0 20px 17px;color: #575757;font-size: 20px;list-style-type: disc;display: list-item;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_OPTIONS_LABEL' );?></div>

<!-- Popup Main Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_MAIN_OPTIONS_LABEL' );?></div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_max_size" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_MAX_SIZE_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_MAX_SIZE_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_max_size'] : $this->item->popup_max_size;
									echo '<select id="cis_popup_max_size" class="cis_has_slider" name="popup_max_size">';
									for($k = 30; $k <= 100; $k += 5) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'%</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_item_min_width" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ITEM_MIN_WIDTH_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ITEM_MIN_WIDTH_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_item_min_width'] : $this->item->popup_item_min_width;
									echo '<select id="cis_popup_item_min_width" class="cis_has_slider" name="popup_item_min_width">';
									for($k = 100; $k <= 500; $k += 10) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_closeonend" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_CLOSEONEND_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_CLOSEONEND_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_closeonend'] : $this->item->popup_closeonend;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_NO'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_YES'));
									$options = array();
									echo '<select id="cis_popup_closeonend" class="" name="popup_closeonend">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_use_back_img" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_USE_BACK_IMG_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_USE_BACK_IMG_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_use_back_img'] : $this->item->popup_use_back_img;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_NO'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_YES'));
									$options = array();
									echo '<select id="cis_popup_use_back_img" class="" name="popup_use_back_img">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>
<!-- Popup AutoPlay Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_AUTOPLAY_OPTIONS_LABEL' );?></div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_autoplay_time" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_AUTOPLAY_TIME_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_AUTOPLAY_TIME_LABEL' );?></label></div>
							<div class="cis_controls"><input type="text" name="popup_autoplay_time" id="cis_popup_autoplay_time" value="<?php echo $v = $this->item->id == 0 ? $slider_global_options['popup_autoplay_time'] : $this->item->popup_autoplay_time;?>" class="inputbox" size="40" required="" ></div>
							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_autoplay_default" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_AUTOPLAY_DEFAULT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_AUTOPLAY_DEFAULT_LABEL' );?></label></div>
							<div class="cis_controls">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_autoplay_default'] : $this->item->popup_autoplay_default;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_NO'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_YES'));
									$options = array();
									echo '<select id="cis_popup_autoplay_default" class="" name="popup_autoplay_default">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
							</div>	

<!-- Popup Arrow Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_OPTIONS_LABEL' );?></div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_arrow_passive_opacity" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_PASSIVE_OPACITY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_PASSIVE_OPACITY_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_arrow_passive_opacity'] : $this->item->popup_arrow_passive_opacity;
									echo '<select id="cis_popup_arrow_passive_opacity" class="cis_has_slider" name="popup_arrow_passive_opacity">';
									for($k = 0; $k <= 100; $k += 5) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'%</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_arrow_left_offset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_LEFT_OFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_LEFT_OFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_arrow_left_offset'] : $this->item->popup_arrow_left_offset;
									echo '<select id="cis_popup_arrow_left_offset" class="cis_has_slider" name="popup_arrow_left_offset">';
									for($k = 0; $k <= 100; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_arrow_min_height" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_MIN_HEIGHT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_MIN_HEIGHT_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_arrow_min_height'] : $this->item->popup_arrow_min_height;
									echo '<select id="cis_popup_arrow_min_height" class="cis_has_slider" name="popup_arrow_min_height">';
									for($k = 10; $k <= 30; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_arrow_max_height" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_MAX_HEIGHT_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ARROW_MAX_HEIGHT_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_arrow_max_height'] : $this->item->popup_arrow_max_height;
									echo '<select id="cis_popup_arrow_max_height" class="cis_has_slider" name="popup_arrow_max_height">';
									for($k = 30; $k <= 64; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>
							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_showarrows" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_SHOWARROWS_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_SHOWARROWS_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_showarrows'] : $this->item->popup_showarrows;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_NEVER'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ONHOVER'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ALWAYS'));
									$options = array();
									echo '<select id="cis_popup_showarrows" class="cis_has_slider" name="popup_showarrows">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

<!-- Popup Image Order Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_IMAGE_ORDER_LABEL' );?></div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_image_order_opacity" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_IMAGE_ORDER_OPACITY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_IMAGE_ORDER_OPACITY_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_image_order_opacity'] : $this->item->popup_image_order_opacity;
									echo '<select id="cis_popup_image_order_opacity" class="cis_has_slider" name="popup_image_order_opacity">';
									for($k = 0; $k <= 100; $k += 5) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'%</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_image_order_top_offset" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_IMAGE_ORDER_TOP_OFFSET_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_IMAGE_ORDER_TOP_OFFSET_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_image_order_top_offset'] : $this->item->popup_image_order_top_offset;
									echo '<select id="cis_popup_image_order_top_offset" class="cis_has_slider" name="popup_image_order_top_offset">';
									for($k = 0; $k <= 100; $k ++) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'px</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_show_orderdata" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_SHOWORDERDATA_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_SHOWORDERDATA_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_show_orderdata'] : $this->item->popup_show_orderdata;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_NEVER'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ONHOVER'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ALWAYS'));
									$options = array();
									echo '<select id="cis_popup_show_orderdata" class="cis_has_slider" name="popup_show_orderdata">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

<!-- Popup Icons Options ****************************************************************************************-->
							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ICONS_OPTIONS_LABEL' );?></div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_icons_opacity" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ICONS_OPACITY_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_ICONS_OPACITY_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_icons_opacity'] : $this->item->popup_icons_opacity;
									echo '<select id="cis_popup_icons_opacity" class="cis_has_slider" name="popup_icons_opacity">';
									for($k = 0; $k <= 100; $k += 5) :
										$selected = $k == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$k.'">'.$k.'%</option>';
									endfor;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<!-- TODO -->
							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_popup_show_icons" class="hasTooltip" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_SHOWICONS_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_POPUP_SHOWICONS_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
									<?php 
									$default = $this->item->id == 0 ? $slider_global_options['popup_show_icons'] : $this->item->popup_show_icons;
									$opts = array(0 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_NEVER'), 1 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ONHOVER'), 2 => JText::_('COM_CREATIVEIMAGESLIDER_SHOWARROWS_ALWAYS'));
									$options = array();
									echo '<select id="cis_popup_show_icons" class="cis_has_slider" name="popup_show_icons">';
									foreach($opts as $key=>$value) :
										$selected = $key == $default ? 'selected="selected"' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									endforeach;
									echo '</select>';
									?>
									<div class="cis_slider_wrapper_inner"><div class="cis_slider_insert_here" style="display: none;"></div></div>
							</div>

							<div style="clear: both;margin: 15px 0 10px 0px;color: #08c; font-style: italic;font-size: 12px;text-decoration: underline;"><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CUSTOM_CODE_LABEL' );?></div>

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_custom_css" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CUSTOM_STYLES_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CUSTOM_STYLES_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
								<textarea name="custom_css" id="cis_custom_styles" style="height: 250px"><?php echo $this->item->custom_css;?></textarea>
							</div>	

							<div style="clear: both;height: 5px;"></div>
							<div class="cis_control_label"><label id="" for="cis_custom_js" class="hasTooltip required" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CUSTOM_JS_DESCRIPTION' );?>" ><?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_CUSTOM_JS_LABEL' );?></label></div>
							<div class="cis_controls cis_slider_wrapper">
								<textarea name="custom_js" id="cis_custom_js" style="height: 250px"><?php echo $this->item->custom_js;?></textarea>
							</div>						

<!-- End Options ****************************************************************************************-->


						</div>
					</div>
				</div>
			</fieldset>
			</td>
			<td style="vertical-align: top;position: relative;"align="top">
			<?php 
				if($this->item->id != 0) {
					$query = 'SELECT '.
							'sp.id slider_id, ' .
							'sp.id_template, ' .
							'sp.width, ' .
							'sp.height, ' .
							'sp.itemsoffset, ' .
							'sp.margintop, ' .
							'sp.marginbottom, ' .
							'sp.paddingtop, ' .
							'sp.paddingbottom, ' .
							'sp.showarrows, ' .
							'sp.bgcolor, ' .
							'sp.showreadmore, ' .
							'sp.readmoretext, ' .
							'sp.readmorestyle, ' .
							'sp.readmoresize, ' .
							'sp.readmoreicon, ' .
							'sp.readmorealign, ' .
							'sp.readmoremargin, ' .
							'sp.captionalign, ' .
							'sp.captionmargin, ' .
							'sp.overlaycolor, ' .
							'sp.overlayopacity, ' .
							'sp.textcolor, ' .
							'sp.overlayfontsize, ' .
							'sp.textshadowcolor, ' .
							'sp.textshadowsize, ' .
							'sp.arrow_template, ' .
							'sp.arrow_width, ' .
							'sp.arrow_left_offset, ' .
							'sp.arrow_center_offset, ' .
							'sp.arrow_passive_opacity, ' .
							'sp.move_step, ' .
							'sp.move_time, ' .
							'sp.move_ease, ' .
							'sp.autoplay, ' .
							'sp.autoplay_start_timeout, ' .
							'sp.autoplay_hover_timeout, ' .
							'sp.autoplay_step_timeout, ' .
							'sp.autoplay_evenly_speed, ' .

							'sp.overlayanimationtype, ' .
							'sp.popup_max_size, ' .
							'sp.popup_item_min_width, ' .
							'sp.popup_use_back_img, ' .
							'sp.popup_arrow_passive_opacity, ' .
							'sp.popup_arrow_left_offset, ' .
							'sp.popup_arrow_min_height, ' .
							'sp.popup_arrow_max_height, ' .
							'sp.popup_showarrows, ' .
							'sp.popup_image_order_opacity, ' .
							'sp.popup_image_order_top_offset, ' .
							'sp.popup_show_orderdata, ' .
							'sp.popup_icons_opacity, ' .
							'sp.popup_show_icons, ' .
							'sp.popup_autoplay_default, ' .
							'sp.popup_closeonend, ' .
							'sp.popup_autoplay_time, ' .
							'sp.popup_open_event, ' .
							'sp.link_open_event, ' .
							
							// 3.0 options
							'sp.cis_touch_enabled, ' .
							'sp.cis_inf_scroll_enabled, ' .
							'sp.cis_mouse_scroll_enabled, ' .
							'sp.cis_item_correction_enabled, ' .
							'sp.cis_animation_type, ' .
							'sp.cis_item_hover_effect, ' .
							'sp.cis_items_appearance_effect, ' .
							'sp.cis_overlay_type, ' .
							'sp.cis_touch_type, ' .
							'sp.cis_font_family, ' .
							'sp.cis_font_effect, ' .

							'sp.icons_size, ' .
							'sp.icons_margin, ' .
							'sp.icons_offset, ' .
							'sp.icons_animation, ' .
							'sp.icons_color, ' .
							'sp.icons_valign, ' .
							'sp.cis_button_font_family, ' .

							'sa.id img_id, ' .
							'sa.name img_name, ' .
							'sa.img_name img_path, ' .
							'sa.img_url img_url_path ,' .
							'sa.caption ,' .
							'sa.showarrows item_showarrows, ' .
							'sa.showreadmore item_showreadmore, ' .
							'sa.readmoretext item_readmoretext, ' .
							'sa.readmorestyle item_readmorestyle, ' .
							'sa.readmoresize item_readmoresize, ' .
							'sa.readmoreicon item_readmoreicon, ' .
							'sa.readmorealign item_readmorealign, ' .
							'sa.readmoremargin item_readmoremargin, ' .
							'sa.captionalign item_captionalign, ' .
							'sa.captionmargin item_captionmargin, ' .
							'sa.overlaycolor item_overlaycolor, ' .
							'sa.overlayopacity item_overlayopacity, ' .
							'sa.textcolor item_textcolor, ' .
							'sa.overlayfontsize item_overlayfontsize, ' .
							'sa.textshadowcolor item_textshadowcolor, ' .
							'sa.textshadowsize item_textshadowsize, ' .
							'sa.overlayusedefault, ' .
							'sa.buttonusedefault ' .
							'FROM '.
							'`#__cis_sliders` sp '.
							'JOIN '.
							'`#__cis_images` sa ON sa.id_slider = sp.id '.
							'AND sa.published = \'1\' '.
							'LEFT JOIN '.
							'`#__cis_templates` st ON st.id = sp.id_template '.
							'WHERE sp.published = \'1\' '.
							'AND sp.id = '.$this->item->id.' '.
							'ORDER BY sp.ordering,sp.id,sa.ordering,sa.id';
					$db->setQuery($query);
					$rows = $db->loadObjectList();
					$slider_options = array();
					if($rows === false)
						$slider_options = array();
					else
						for ($i=0, $n=count( $rows ); $i < $n; $i++) {
						$slider_options[$rows[$i]->slider_id][] = $rows[$i];
					}
					if($rows !== false && sizeof($slider_options) > 0) {?>
					<div id="preview_dummy" style="position: fixed;left:0;top: 0;"></div>
					<div class="preview_box" style="position: absolute;width: 100%;top: 75px;">
						<img alt="Close Preview Box" title="Close Preview Box" src="components/com_creativeimageslider/assets/images/close.png" id="cis_preview_close" />
						<div  id="cis_preview">Preview</div>
						<div id="cis_preview_wrapper1" style="position: relative;overflow: hidden;">
							<div id="cis_preview_inner1" style="padding-bottom: 32px;">
								<div style="margin: 0px auto 0px;color: #444;font-style: italic;">Slider preview (active state)</div>
								<div style="height: 5px;width: 100%;">&nbsp;</div>
								<?php 
								reset($slider_options);
								$first_key = key($slider_options);
								
								$slider_options_value = $slider_options[$first_key][0];
								
								$slider_width = $slider_options_value->width;
								$slider_item_height = (int) $slider_options_value->height;
								$slider_id_template = (int) $slider_options_value->id_template;
								$slider_margintop = (int) $slider_options_value->margintop;
								$slider_marginbottom = (int) $slider_options_value->marginbottom;
								$slider_paddingtop = (int) $slider_options_value->paddingtop;
								$slider_paddingbottom = (int) $slider_options_value->paddingbottom;
								$slider_itemsoffset = (int) $slider_options_value->itemsoffset;
								$slider_showarrows = (int) $slider_options_value->showarrows;
								$slider_bgcolor =  $slider_options_value->bgcolor;
								$slider_showreadmore = (int) $slider_options_value->showreadmore;
								$slider_readmoretext =  $slider_options_value->readmoretext;
								$slider_readmorestyle =  $slider_options_value->readmorestyle;
								$slider_readmoresize =  $slider_options_value->readmoresize;
								$slider_readmoreicon =  $slider_options_value->readmoreicon;
								$slider_readmorealign =  (int) $slider_options_value->readmorealign;
								$slider_readmoremargin =  $slider_options_value->readmoremargin;
								$slider_overlaycolor =  $slider_options_value->overlaycolor;
								$slider_overlayopacity = (int) $slider_options_value->overlayopacity;
								$slider_textcolor = $slider_options_value->textcolor;
								$slider_overlayfontsize = (int) $slider_options_value->overlayfontsize;
								$slider_textshadowcolor =  $slider_options_value->textshadowcolor;
								$slider_textshadowsize = (int) $slider_options_value->textshadowsize;
								$slider_captionalign = (int) $slider_options_value->captionalign;
								$slider_captionmargin = $slider_options_value->captionmargin;
								
								$slider_arrow_template = $slider_options_value->arrow_template;
								$slider_arrow_width = $slider_options_value->arrow_width;
								$slider_arrow_left_offset = $slider_options_value->arrow_left_offset;
								$slider_arrow_center_offset = $slider_options_value->arrow_center_offset;
								$slider_arrow_passive_opacity = $slider_options_value->arrow_passive_opacity;
								
								$slider_move_step = $slider_options_value->move_step;
								$slider_move_time = $slider_options_value->move_time;
								$slider_move_ease = $slider_options_value->move_ease;
								$slider_autoplay = $slider_options_value->autoplay;
								$slider_autoplay_start_timeout = $slider_options_value->autoplay_start_timeout;
								$slider_autoplay_hover_timeout = $slider_options_value->autoplay_hover_timeout;
								$slider_autoplay_step_timeout = $slider_options_value->autoplay_step_timeout;
								$slider_autoplay_evenly_speed = $slider_options_value->autoplay_evenly_speed;

								$cis_overlayanimationtype = (int) $slider_options_value->overlayanimationtype;

								// 3.0 options
								$cis_link_open_event = (int) $slider_options_value->link_open_event;
								$cis_popup_open_event = (int) $slider_options_value->popup_open_event;

								$cis_touch_enabled = (int) $slider_options_value->cis_touch_enabled; // 0 - disabled, 1 - enabled, 2 - only on touch devices
								$cis_inf_scroll_enabled = (int) $slider_options_value->cis_inf_scroll_enabled;
								$cis_mouse_scroll_enabled = (int) $slider_options_value->cis_mouse_scroll_enabled;
								$cis_item_correction_enabled = (int) $slider_options_value->cis_item_correction_enabled;

								// options to add in html
								$cis_animation_type = (int) $slider_options_value->cis_animation_type;
								$cis_item_hover_effect = (int) $slider_options_value->cis_item_hover_effect;
								$cis_items_appearance_effect = (int) $slider_options_value->cis_items_appearance_effect;
								$cis_overlay_type = (int) $slider_options_value->cis_overlay_type;
								$cis_touch_type = (int) $slider_options_value->cis_touch_type;

								$cis_icons_size = (int) $slider_options_value->icons_size;
								$cis_icons_margin = (int) $slider_options_value->icons_margin;
								$cis_icons_offset = (int) $slider_options_value->icons_offset;
								$cis_icons_animation = (int) $slider_options_value->icons_animation;
								$cis_icons_color = (int) $slider_options_value->icons_color;
								$cis_icons_valign = (int) $slider_options_value->icons_valign;


								$cis_font_family = $slider_options_value->cis_font_family;
								$cis_button_font_family = $slider_options_value->cis_button_font_family;
								$cis_font_effect = $slider_options_value->cis_font_effect;
								
								$slider_autoplay = 0;//turn off autoplay
								
								
								$cache_dir = __DIR__ . '/../../../../../../cache/com_creativeimageslider/';
								$cached_img_dir = JURI::base(true) . '/../cache/com_creativeimageslider/';
								$uploaded_img_dir = JURI::base(true) . '/../';
								
								$id_slider = $this->item->id;


								// add goiogle font
								$cis_googlefont = 'cis-googlewebfont-';
								$cis_google_fonts = '';
								if (strpos($cis_font_family,$cis_googlefont) !== false) {
									$cis_google_fonts = str_replace($cis_googlefont, '', $cis_font_family);
									$cis_font_family = str_replace($cis_googlefont, '', $cis_font_family);
								}
								if (strpos($cis_button_font_family,$cis_googlefont) !== false) {
									$cis_google_fonts = $cis_google_fonts . '|' . str_replace($cis_googlefont, '', $cis_button_font_family);
									$cis_button_font_family = str_replace($cis_googlefont, '', $cis_button_font_family);
								}
								$cis_google_fonts = trim($cis_google_fonts,'|');

								if($cis_google_fonts != '') {
									$cis_google_font_link = 'http://fonts.googleapis.com/css?family='.$cis_google_fonts;
									$document->addStyleSheet($cis_google_font_link, 'text/css', null, array());
								}

								//start render html
								echo '<div id="cis_slider_'.$id_slider.'" roll="'.$id_slider.'" class="cis_main_wrapper" style="position: relative !important;" cis_overlay_animation_type="'.$cis_overlayanimationtype.'" inf_scroll_enabled="'.$cis_inf_scroll_enabled.'" mouse_scroll_enabled="'.$cis_mouse_scroll_enabled.'" touch_enabled="'.$cis_touch_enabled.'" item_correction_enabled="'.$cis_item_correction_enabled.'" >';
								echo '<div class="cis_images_row">';
								
								//buttons
								$img_src1 = JURI::base(true) .'/../components/com_creativeimageslider/assets/images/arrows/cis_button_left'.$slider_arrow_template.'.png';
								$img_src2 = JURI::base(true) .'/../components/com_creativeimageslider/assets/images/arrows/cis_button_right'.$slider_arrow_template.'.png';
								echo '<img class="cis_button_left" src="'.$img_src1.'" />';
								echo '<img class="cis_button_right" src="'.$img_src2.'" />';
								echo '<div class="cis_arrow_data" style="display: none;">'.$slider_arrow_width.','.$slider_arrow_left_offset.','.$slider_arrow_center_offset.','.$slider_arrow_passive_opacity.','.$slider_showarrows.'</div>';
								echo '<div class="cis_moving_data" style="display: none;">'.$slider_move_step.','.$slider_move_time.','.$slider_move_ease.','.$slider_autoplay.','.$slider_autoplay_start_timeout.','.$slider_autoplay_step_timeout.','.$slider_autoplay_evenly_speed.','.$slider_autoplay_hover_timeout.'</div>';
								
								echo '<div class="cis_options_data" style="display: none !important;">'
								.$cis_animation_type.','.$cis_item_hover_effect.','.$cis_items_appearance_effect.','.$cis_overlay_type.','.$cis_touch_type.','
								.$cis_icons_size.','.$cis_icons_margin.','.$cis_icons_offset.','.$cis_icons_animation.','.$cis_icons_color.','.$cis_icons_valign.'</div>';

								echo '<div class="cis_images_holder">';
								
								$items_css = '';
								$loader_color_class = 'cis_row_item_loader_color1';
								foreach( $slider_options[$first_key] as $cis_index => $image_info) {
									//get image
									$img_path = $image_info->img_path != '' ? $image_info->img_path : $image_info->img_url_path;
									if($image_info->img_path != '') {
										//check to see if cached file exists
										$img_parts = explode('/',$image_info->img_path);
										$filename = $img_parts[sizeof($img_parts) - 1];
										preg_match('/^(.*)\.([a-z]{3,4}$)/i',$filename,$matches);
										$img_path_cache = $matches[1] . '-tmb-h' . $slider_item_height . '.' . $matches[2];
										$img_fullpath_cache = $cache_dir . $img_path_cache;
										if(file_exists($img_fullpath_cache)) {
											$img_path = $cached_img_dir . $img_path_cache;
										}
										else {
											$img_path = $uploaded_img_dir . $image_info->img_path;
										}
									}
										
									echo '<div class="cis_row_item" id="cis_item_'.$image_info->img_id.'">';
									$loader_color_class = $loader_color_class == 'cis_row_item_loader_color1' ? 'cis_row_item_loader_color2' : 'cis_row_item_loader_color1';
									echo '<div class="cis_row_item_loader '.$loader_color_class.'" style="height: '.$slider_item_height.'px;"></div>';
									echo '<div class="cis_row_item_inner cis_row_hidden_element">';
									//image
									echo '<img src="'.$img_path.'" style="height: '.$slider_item_height.'px;"  />';
								
									//overlay
									$custom_rule_class = '';
									$custom_rule_class_button = '';

									$overlay_type_class = $cis_overlay_type == 0 ? 'cis_height_auto' : 'cis_height_100_perc';
									echo '<div class="cis_row_item_overlay '.$overlay_type_class.'" overlay_type="'.$cis_overlay_type.'">';
									//caption
									// if($image_info->img_name != '')
									$cis_caption_visible = $slider_showreadmore == 0 ? 'cis_display_none' : '';
									echo '<div class="cis_row_item_txt_wrapper '.$cis_caption_visible.'"><div class="cis_row_item_overlay_txt'.$custom_rule_class.'"><div class="cis_txt_inner '.$cis_font_effect.'">'.$image_info->img_name.'</div></div></div>';
										
									//button
									// if(($image_info->buttonusedefault == 0 && $slider_showreadmore == 1) || ($image_info->buttonusedefault == 1 && $image_info->item_showreadmore == 1)) {
									
									$button_visible_check = ($cis_popup_open_event == 2 || $cis_link_open_event == 2) ? '' : 'style="display: none"';
									// if($cis_popup_open_event == 2 || $cis_link_open_event == 2) {
										//get click url
										$click_url = '#';
								
										//read more text
										$item_readmoretext = $slider_readmoretext;
								
										//button styles
										$button_style = 'creative_btn-' . $slider_readmorestyle;
										$button_size = 'creative_btn-' . $slider_readmoresize;
										$button_icon_color = $slider_readmorestyle == 'gray' ? 'white' : 'white';
										$button_icon_html = $slider_readmoreicon == 'none' ? '' : '<i class="creative_icon-'.$button_icon_color.' creative_icon-'.$slider_readmoreicon.'"></i> ';
										echo '<div class="cis_btn_wrapper"><a href="'.$click_url.'" class="creative_btn '.$button_style.' '.$button_size.$custom_rule_class_button.'" '.$button_visible_check.'><span class="cis_creative_btn_icon">'.$button_icon_html .'</span><span class="cis_creative_btn_txt">'. $item_readmoretext.'</span></a></div>';
									// }
									echo '</div>';
									echo '</div>';
									echo '</div>';
								}
									
								echo '</div>';
								echo '</div>';
								echo '</div>';
								
								//print css
								$slider_overlaycolor_rgb = cis_hex2rgb($slider_overlaycolor);
								$slider_overlayopacity = $slider_overlayopacity / 100;
								$slider_overlaycolor_rgba = 'rgba('.$slider_overlaycolor_rgb.','.$slider_overlayopacity.')';
								
								//get txt text shadow;
								if($slider_textshadowsize == 0)
									$slider_textshadow_rule = 'text-shadow: none;';
								elseif($slider_textshadowsize == 1)
								$slider_textshadow_rule = 'text-shadow: -1px 2px 0px '.$slider_textshadowcolor.';';
								elseif($slider_textshadowsize == 2)
								$slider_textshadow_rule = 'text-shadow: -1px 2px 2px '.$slider_textshadowcolor.';';
								elseif($slider_textshadowsize == 3)
								$slider_textshadow_rule = 'text-shadow: -1px 2px 4px '.$slider_textshadowcolor.';';
								
								$cis_css = '';
								$cis_css .= '#cis_slider_'.$id_slider.'.cis_main_wrapper {';
								$cis_css .= 'width: '.$slider_width.';';
								$cis_css .= 'margin: '.$slider_margintop.'px auto '.$slider_marginbottom.'px;';
								$cis_css .= 'padding: '.$slider_paddingtop.'px 0px '.$slider_paddingbottom.'px 0px;';
								$cis_css .= 'background-color: '.$slider_bgcolor.';';
								$cis_css .= '}';
								$cis_css .= '#cis_slider_'.$id_slider.' .cis_row_item_overlay {';
								$cis_css .= 'background-color: '.$slider_overlaycolor.';';
								$cis_css .= 'background-color: '.$slider_overlaycolor_rgba.';';
								$cis_ta = $slider_readmorealign == 2 ? 'center' : 'left';
								$cis_css .= 'text-align: '.$cis_ta.';';
								$cis_css .= '}';
								$cis_css .= '#cis_slider_'.$id_slider.' .cis_row_item {';
								$cis_css .= 'margin-right: '.$slider_itemsoffset.'px;';
								$cis_css .= '}';
								$cis_css .= '#cis_slider_'.$id_slider.' .cis_row_item_overlay_txt {';
								$cis_css .= $slider_textshadow_rule;
								$cis_css .= 'font-size: '.$slider_overlayfontsize.'px;';
								$cis_css .= 'color: '.$slider_textcolor.';';
								$cis_css .= 'margin: '.$slider_captionmargin.';';
								$cis_text_align = $slider_captionalign == 0 ? 'left' : ($slider_captionalign == 1 ? 'right' : 'center');
								$cis_css .= 'text-align: '.$cis_text_align.';';
								$cis_css .= '}';
								$cis_css .= '#cis_slider_'.$id_slider.' .creative_btn {';
								$cis_css .= 'margin: '.$slider_readmoremargin.';';
								$cis_float = $slider_readmorealign == 0 ? 'left' : ($slider_readmorealign == 1 ? 'right' : 'none');
								$cis_css .= 'float: '.$cis_float.';';
								$cis_css .= '}';

								// 3.0 updates
								$cis_css .= '.cis_row_item_txt_wrapper {';
								$cis_css .= 'font-family: '.$cis_font_family.';';
								$cis_css .= '}';
								$cis_css .= '.cis_btn_wrapper {';
								$cis_css .= 'font-family: '.$cis_button_font_family.';';
								$cis_css .= '}';
								
								echo '<style>'.$cis_css.$items_css.'</style>';
								?>
								
							</div>
						</div>
					</div>
					<?php }
				}


			?>
			</td></tr></table>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;"><?php echo JText::_('COM_CREATIVEIMAGESLIDER_PLEASE_UPGRADE_TO_HAVE_MORE_THAN_ONE_SLIDER');?></div>
					<div id="cpanel" style="float: left;">
					<div class="icon" style="float: right;">
					<a href="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_BUY_PRO_VERSION_DESCRIPTION' ); ?>">
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
<input type="hidden" name="task" value="creativeslider.edit" />
<?php echo JHtml::_('form.token'); ?>
</form>
<?php include (JPATH_BASE.'/components/com_creativeimageslider/helpers/footer.php'); ?>
<?php }?>
<style>
.form-horizontal .cis_controls {
margin-left: 200px !important;
}

.cis_button_left, .cis_button_right {
	-webkit-transition:  top linear 0.2s;
	-moz-transition: top linear 0.2s;
	-o-transition: top linear 0.2s;
	transition: top linear 0.2s;
}
</style>
