<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_31F
	* Creation date: Juillet 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_gmapfp')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once (JPATH_COMPONENT.'/controller.php');
require_once( JPATH_COMPONENT.'/helper.php' );

// Require the base controller

$controllerName = JRequest::getWord('controller');

// Require specific controller if requested
if (!($controllerName = JRequest::getWord('controller')))
	{$controllerName = 'accueil';};

	// Perform the Request task
if (!(JFactory::getApplication()->input->get('task'))) {
	$task = 'view';
}else{
	$task = JFactory::getApplication()->input->get('task');
	$result = explode('.', $task);
	if (count($result) > 1){
		$controllerName = $result[0];
		$task =  $result[1];
		unset($result);
	}
};

if($controllerName)
	{
   		$path = JPATH_COMPONENT.'/controllers/'.$controllerName.'.php';
    	if( file_exists($path))
    		{
        		require_once $path;
    		} else
    		{
        		$controllerName = '';
    		}
	}
$classname = 'GMapFPsController'.$controllerName;

// Create the controller
$controllerName = new $classname();

$controllerName->execute( $task );

// Redirect if set by the controller
$controllerName->redirect();


?>
