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

jimport( 'joomla.application.component.view');

class redirectonloginViewLogout extends JViewLegacy{

	function display($tpl = null){
	
		$app = JFactory::getApplication();		
		
		$error = $app->logout();			
		
		//parent::display($tpl);
		
		$return = JRequest::getVar('return', '');
		$return = base64_decode($return);
		if($return){
			$url = $return;
		}else{
			$url = 'index.php';
		}
		
		$app->redirect($url);		
	}
}
?>