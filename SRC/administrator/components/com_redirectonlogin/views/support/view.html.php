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

class redirectonloginViewSupport extends JViewLegacy{

	function display($tpl = null){
	
		$ds = DIRECTORY_SEPARATOR;
	
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);
		
		//get helper for menuitem selects and dynamic selects	
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();
		$this->assignRef('helper', $helper);	
		
		//include language files. Reuse or die ;-)#
		$lang = JFactory::getLanguage();
		$lang->load('com_installer', JPATH_ADMINISTRATOR, null, false);	
		$lang->load('mod_menu', JPATH_ADMINISTRATOR, null, false);	
		$lang->load('com_menus', JPATH_ADMINISTRATOR, null, false);	
		$lang->load('com_modules', JPATH_ADMINISTRATOR, null, false);	
		$lang->load('mod_stats_admin', JPATH_ADMINISTRATOR, null, false);	
		$lang->load('com_contact', JPATH_ADMINISTRATOR, null, false);
		
		if($helper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}					

		parent::display($tpl);
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=support');	
				
		$controller->add_submenu();			
		
		$this->sidebar = JHtmlSidebar::render();
	}
	
}
?>