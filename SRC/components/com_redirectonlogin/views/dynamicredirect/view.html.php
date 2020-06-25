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

class redirectonloginViewDynamicredirect extends JViewLegacy{

	function display($tpl = null){
	
		$database = JFactory::getDBO();
		$app = JFactory::getApplication();			
		$helper = $this->get_helper();
		$config = $helper->get_config();
		
		if($config['enable_redirection']=='yes'){
		
			$id = JRequest::getVar('id', '');
			$id = intval($id);
			
			$current_url = 'index.php';
			if(isset($_SERVER['HTTP_REFERER'])){
				$current_url = $_SERVER['HTTP_REFERER'];							
			}	
			
			$dynamic_array = $helper->get_dynamic_link($id);
			$redirect_url = $dynamic_array[0];
			$message = $dynamic_array[1];
			$logout = $dynamic_array[2];
			$message_type = $dynamic_array[3];			
			
			if($redirect_url!='' || $message!='' || $logout){			
				$session_id = session_id();			
				$database->setQuery( "UPDATE #__redirectonlogin_sessions SET url='$redirect_url', message='$message', message_type='$message_type', logout='$logout' WHERE session_id='$session_id' ");
				$database->query();		
				//$app->redirect($current_url);	
			}	
		
		}else{
			parent::display($tpl);
		}	
	}
	
	function get_helper(){
		
		$ds = DIRECTORY_SEPARATOR;
		
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();
		return $helper;
	}
}
?>