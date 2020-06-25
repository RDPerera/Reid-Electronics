<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_32F
	* Creation date: Août 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();
define('COM_GMAPFP_PRO',    0);

require_once (JPATH_COMPONENT.'/controller.php');
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}
$classname	= 'GMapFPsController'.ucfirst($controller);
$controller = new $classname( );

$controller->execute(JFactory::getApplication()->input->get('task'));

$controller->redirect();

?>