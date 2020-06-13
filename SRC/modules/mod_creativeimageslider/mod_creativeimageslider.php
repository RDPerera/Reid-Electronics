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

// get a parameter from the module's configuration
$module_id = $module->id;
$id_slider = $params->get('slider_id',1);
$class_suffix = $params->get('class_suffix','');

//include helper class
require_once JPATH_SITE.'/components/com_creativeimageslider/helpers/helper.php';

//
$cis_class = new CreativeimagesliderHelper;
$cis_class->slider_id = $id_slider;
$cis_class->type = 'module';
$cis_class->class_suffix = $class_suffix;
$cis_class->module_id = $module_id;
echo $cis_class->render_html();
?>