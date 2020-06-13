<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

//ACL
if (!JFactory::getUser()->authorise('core.manage', 'com_redirectonlogin')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//silly workaround for developers who install the trail version while totally ignoring 
//all warnings about that you need Ioncube installed or else it will criple the site
$rol_trial_version = 0;

if($rol_trial_version && !extension_loaded('ionCube Loader')){
	echo 'This trial version is encrypted. You need Ioncube installed and enabled on your server to use it. <a href="http://www.pages-and-items.com/faqs/ioncube" target="_blank">read more</a>';
	exit;
}

// Require the base controller
$ds = DIRECTORY_SEPARATOR;
require_once (JPATH_COMPONENT.$ds.'controller.php');

// Create the controller
$controller = new redirectonloginController();

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();

?>