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
$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/colorpicker.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

?>


<script type="text/javascript">
<?php if(version_compare( JVERSION, '1.6.0', 'lt' )) { ?>
function submitbutton(task) {
<?php } else { ?>
Joomla.submitbutton = function(task) {
<?php } ?>
	var form = document.adminForm;
	if (task == 'cancel') {
		submitform( task );
	} else if (form.name.value == ""){
		form.name.style.border = "1px solid red";
		form.name.focus();
	} else {
		submitform( task );
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
<script>
(function($) {
	$(document).ready(function() {


	})
})(sexyJ);
</script>

<div class="col100" style="position: relative;" id="c_div">
	 
</div>
<form action="<?php echo JRoute::_('index.php?option=com_creativeimageslider&layout=form&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset class="adminform" style="position: relative;">
        <legend><?php echo JText::_( 'Custom Styles' ); ?></legend>
    </fieldset>
</div>
 
<div class="clr"></div>
 
<input type="hidden" name="option" value="com_creativeimageslider" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="creativetemplate.edit" />
<?php echo JHtml::_('form.token'); ?>
</form>
<?php include (JPATH_BASE.'/components/com_creativeimageslider/helpers/footer.php'); ?>