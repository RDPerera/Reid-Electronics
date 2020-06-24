<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();

\JHtml::_('behavior.tooltip');
\JHtml::_('formbehavior.chosen', 'select');
\JHtml::_('behavior.modal', 'a.modal');


$this->form = $this->get('ContentPluginButtonForm');

$script  = '

function updateContentTag() {
    var $tag = jQuery("#contenttagfield"),
        event = document.getElementById("jform_folder_id").value,
        attrElement = document.getElementById("jform_attr"),
        attr = attrElement.options[attrElement.selectedIndex].value,
        modeElement = document.getElementById("jform_image_mode"),
        mode = modeElement.options[modeElement.selectedIndex].value,
        
        linkModeElement = document.getElementById("jform_link_mode"),
        linkMode = linkModeElement.options[linkModeElement.selectedIndex].value,
        
        sliderFieldsetElement = document.getElementById("jform_use_slider"),  
        use_slider = parseInt(document.querySelector(\'input[name = "jform[use_slider]"]:checked\').value),
        slider_shownav = parseInt(document.querySelector(\'input[name = "jform[slider_shownav]"]:checked\').value),
        slider_rows = parseInt(document.getElementById("jform_slider_rows").value),
        slider_autoplay = parseInt(document.getElementById("jform_slider_autoplay").value),
        
        max_images = document.getElementById("jform_max_images").value,
        offset = document.getElementById("jform_offset").value,
        thumb_width = document.getElementById("jform_image_width").value,
        tag = "",
        type = "",
        use_cart = parseInt(document.querySelector(\'input[name = "jform[use_cart]"]:checked\').value);
        
        
	if (attr == "text_intro") {
		attr = "text";
		type = "intro";
	}

	if (attr == "text_full") {
		attr = "text";
		type = "full";
	}
	
	tag   = "{eventgallery ";
	tag = tag + "event=\'" + event +"\' ";
	tag = tag + "attr="+ attr +" ";
	
	if (attr == "text") {
		tag = tag + "type="+ type +" ";
	} 
	
	if (attr == "images") {
		tag = tag + "mode="+ mode +" ";
		tag = tag + "linkmode="+ linkMode +" "; 
		tag = tag + "max_images="+ max_images +" ";
		tag = tag + "thumb_width="+ thumb_width + " ";
		tag = tag + "offset="+ offset + " ";
		
		// only if the slider config is visible.
		if (use_slider == 1 && sliderFieldsetElement.offsetWidth > 0 && sliderFieldsetElement.offsetHeight > 0) {
            tag = tag + "use_slider="+ use_slider + " ";
            if (slider_rows !== 1) tag = tag + "slider_rows="+ slider_rows + " ";
            if (slider_autoplay !== 0) tag = tag + "slider_autoplay="+ slider_autoplay + " ";
            if (slider_shownav !== 0) tag = tag + "slider_shownav="+ slider_shownav + " ";
		}
		
		if (use_cart == 1) {
		    tag = tag + "use_cart=1 ";
		}
		
	}

	tag = tag + "}";
	
	$tag.html(tag);
}

function insertContentTag() {
    updateContentTag();
	var tag = document.getElementById("contenttagfield").innerHTML;
	window.parent.jInsertEditorText(tag, \''.$this->escape($app->input->getString('e_name')).'\');
	window.parent.jModalClose();
	return false;
}

jQuery(function() {
    jQuery("input, select").on("change", function() {
        setTimeout(updateContentTag, 500);
    
    }); 
    updateContentTag();
});
';

JFactory::getDocument()->addScriptDeclaration($script);
?>

<?php echo $this->loadSnippet('formfields'); ?>
<p>
    <button class="btn btn-primary" onclick="insertContentTag();"><?php echo JText::_('COM_EVENTGALLERY_CONTENTPLUGINBUTTON_BUTTON_INSERT'); ?></button>
</p>
<pre><span id="contenttagfield"></span></pre>
