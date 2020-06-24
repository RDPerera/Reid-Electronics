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
		$ds = DIRECTORY_SEPARATOR;
		
		//get controller
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);	
		
		//get helper for menuitem selects and dynamic selects	
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();	
		
		//get menu-items
		
		$menuitem_id_finder = $helper->menuitems('menuitem_id_finder', '', array(0));	
		$this->assignRef('menuitem_id_finder', $menuitem_id_finder);
		
		//get user id
		$id = intval(JRequest::getVar('id', ''));		
		
		//get redirect
		$database->setQuery("SELECT * "
		."FROM #__redirectonlogin_dynamics "
		."WHERE id='$id' "
		."LIMIT 1 "
		);
		$redirects = $database->loadObjectList();	
		//set defaults for new
		$redirect = (object)'';			
		$redirect->id = 0;
		$redirect->name = '';
		$redirect->value = '';	
		$redirect->type = 'php';
		$new_line = '
';			
		foreach($redirects as $temp){
			$redirect = $temp;	
			$temp_value = $temp->value;			
			$temp_value = str_replace('[newline]', $new_line, $temp_value);
			$temp_value = str_replace('[equal]', '=', $temp_value);			
			$redirect->value = $temp_value;
		}	
		$this->assignRef('redirect', $redirect);
		
		//reuse language
		$lang = JFactory::getLanguage();
		$lang->load('com_installer', JPATH_ADMINISTRATOR, null, false);
			
		// Check for errors.
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		//toolbar		
		JToolBarHelper::apply('dynamicredirect_apply', 'JToolbar_Apply');
		JToolBarHelper::save('dynamicredirect_save', 'JToolbar_Save');
		JToolBarHelper::cancel('cancel', 'JToolbar_Close');		
		
		if($helper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}
		
		parent::display($tpl);
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=dynamicredirect');	
				
		$controller->add_submenu();			
		
		$this->sidebar = JHtmlSidebar::render();
	}	
	
}
?>