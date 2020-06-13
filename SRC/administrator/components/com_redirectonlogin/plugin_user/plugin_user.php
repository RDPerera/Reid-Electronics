<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// No direct access
defined('_JEXEC') or die;

//silly workaround for developers who install the trail version while totally ignoring 
//all warnings about that you need Ioncube installed or else it will criple the site
$rol_trial_version = 0;

$ds = DIRECTORY_SEPARATOR;

if(!$rol_trial_version || ($rol_trial_version && extension_loaded('ionCube Loader'))){
	include(dirname(__FILE__).$ds.'plugin_user2.php');
}

?>