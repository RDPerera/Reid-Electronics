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

$id_15 = JRequest::getVar('slider',  0, '', 'int');
$cis_class = new CreativeimagesliderHelper;
$cis_class->slider_id = $id_15;
$cis_class->type = 'component';
$cis_class->class_suffix = '';
$cis_class->module_id = 0;
echo $cis_class->render_html();
?>