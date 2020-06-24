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

class redirectonloginViewUser extends JViewLegacy{
	
	function display($tpl = null){
					
		$database = JFactory::getDBO();
		$ds = DIRECTORY_SEPARATOR;
		
		//get controller
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);	
		
		//get helper
		$helper = $this->get_helper();
		$this->assignRef('helper', $helper);	
		
		//get user id
		$user_id = intval(JRequest::getVar('user_id', ''));
		$this->assignRef('user_id', $user_id);
		
		//get redirect
		$database->setQuery("SELECT * "
		."FROM #__redirectonlogin_users "
		."WHERE user_id='$user_id' "
		."LIMIT 1 "
		);
		$redirects = $database->loadObjectList();
		$redirect = '';	
		$menuitem_login = 0;
		$dynamic_login = 0;	
		$menuitem_open = 0;
		$dynamic_open = 0;	
		$menuitem_logout = 0;
		$dynamic_logout = 0;	
		$logoutbackend_menu = 0;
		$logoutbackend_dyna = 0;		
		$first_menu = 0;
		$first_dyna = 0;
		$loginbackend_dynamic = 0;		
		foreach($redirects as $temp){
			$redirect = $temp;	
			$menuitem_login = $temp->menuitem_login;
			$dynamic_login = $temp->dynamic_login;	
			$menuitem_open = $temp->menuitem_open;
			$dynamic_open = $temp->dynamic_open;	
			$menuitem_logout = $temp->menuitem_logout;
			$dynamic_logout = $temp->dynamic_logout;	
			$logoutbackend_menu = $temp->logoutbackend_menu;
			$logoutbackend_dyna = $temp->logoutbackend_dyna;			
			$first_menu = $temp->first_menu;
			$first_dyna = $temp->first_dyna;	
			$loginbackend_dynamic = $temp->loginbackend_dynamic;			
		}	
		$this->assignRef('redirect', $redirect);
		
		//get helper for menuitem selects and dynamic selects	
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$redirectonloginHelper = new redirectonloginHelper();		
		
		$menuitem_login_select = $redirectonloginHelper->menuitems('menuitem_login', '', array($menuitem_login));	
		$this->assignRef('menuitem_login_select', $menuitem_login_select);
		
		$dynamic_login_select = $redirectonloginHelper->get_dynamics_select('dynamic_login', $dynamic_login);	
		$this->assignRef('dynamic_login_select', $dynamic_login_select);
		
		$menuitem_open_select = $redirectonloginHelper->menuitems('menuitem_open', '', array($menuitem_open));	
		$this->assignRef('menuitem_open_select', $menuitem_open_select);
		
		$dynamic_open_select = $redirectonloginHelper->get_dynamics_select('dynamic_open', $dynamic_open);	
		$this->assignRef('dynamic_open_select', $dynamic_open_select);
		
		$menuitem_logout_select = $redirectonloginHelper->menuitems('menuitem_logout', '', array($menuitem_logout));	
		$this->assignRef('menuitem_logout_select', $menuitem_logout_select);
		
		$dynamic_logout_select = $redirectonloginHelper->get_dynamics_select('dynamic_logout', $dynamic_logout);	
		$this->assignRef('dynamic_logout_select', $dynamic_logout_select);	
		
		$menuitem_logoutbackend_select = $redirectonloginHelper->menuitems('logoutbackend_menu', '', array($logoutbackend_menu));	
		$this->assignRef('menuitem_logoutbackend_select', $menuitem_logoutbackend_select);
		
		$dyna_logoutbackend_select = $redirectonloginHelper->get_dynamics_select('logoutbackend_dyna', $logoutbackend_dyna);	
		$this->assignRef('dyna_logoutbackend_select', $dyna_logoutbackend_select);		
		
		$menuitem_first_select = $redirectonloginHelper->menuitems('first_menu', '', array($first_menu));	
		$this->assignRef('menuitem_first_select', $menuitem_first_select);
		
		$dyna_first_select = $redirectonloginHelper->get_dynamics_select('first_dyna', $first_dyna);	
		$this->assignRef('dyna_first_select', $dyna_first_select);		
		
		$loginbackend_dynamic_select = $redirectonloginHelper->get_dynamics_select('loginbackend_dynamic', $loginbackend_dynamic);	
		$this->assignRef('loginbackend_dynamic_select', $loginbackend_dynamic_select);
		
		//get components
		$components_array = $this->controller->get_components_array();		
		$this->assignRef('components', $components_array);
		
		//get user name
		$database->setQuery("SELECT name, username "
		."FROM #__users "
		."WHERE id='$user_id' "
		."LIMIT 1 "
		);
		$users = $database->loadObjectList();
		$name = '';
		$username = '';
		foreach($users as $user){
			$name = $user->name;
			$username = $user->username;
		}	
		$this->assignRef('name', $name);
		$this->assignRef('username', $username);
			
			
		// Check for errors.
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		//toolbar		
		JToolBarHelper::apply('user_apply', 'JToolbar_Apply');
		JToolBarHelper::save('user_save', 'JToolbar_Save');
		JToolBarHelper::cancel('cancel', 'JToolbar_Close');		
		
		if($redirectonloginHelper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}
		
		parent::display($tpl);
	}	
	
	function get_helper(){
		$ds = DIRECTORY_SEPARATOR;
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();
		return $helper;
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=user');	
				
		$controller->add_submenu();			
		
		$this->sidebar = JHtmlSidebar::render();
	}
	
}
?>