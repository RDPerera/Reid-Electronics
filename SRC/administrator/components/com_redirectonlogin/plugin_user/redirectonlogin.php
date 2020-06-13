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

$ds = DIRECTORY_SEPARATOR;

if(file_exists(JPATH_ADMINISTRATOR.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'plugin_user'.$ds.'plugin_user.php')){			
	require_once(JPATH_ADMINISTRATOR.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'plugin_user'.$ds.'plugin_user.php');
}

?>