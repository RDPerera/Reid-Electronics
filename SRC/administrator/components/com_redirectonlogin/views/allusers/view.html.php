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

class redirectonloginViewAllusers extends JViewLegacy{
	
	function display($tpl = null){
					
		$database = JFactory::getDBO();
		$ds = DIRECTORY_SEPARATOR;
		
		//get configuration
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);
		
		//get components
		$components_array = $this->controller->get_components_array();		
		$this->assignRef('components', $components_array);			
		
		//get helper for menuitem selects and dynamic selects	
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$redirectonloginHelper = new redirectonloginHelper();
		$this->assignRef('redirectonloginHelper', $redirectonloginHelper);
		
		$menuitem_login = $this->controller->rol_config['menuitem_login'];			
		$this->assignRef('menuitem_login', $menuitem_login);
		$menuitem_login_select = $redirectonloginHelper->menuitems('menuitem_login', '', array($menuitem_login));	
		$this->assignRef('menuitem_login_select', $menuitem_login_select);
		
		$dynamic_login = $this->controller->rol_config['dynamic_login'];			
		$this->assignRef('dynamic_login', $dynamic_login);
		$dynamic_login_select = $redirectonloginHelper->get_dynamics_select('dynamic_login', $dynamic_login);	
		$this->assignRef('dynamic_login_select', $dynamic_login_select);
		
		$menuitem_open = $this->controller->rol_config['menuitem_open'];				
		$this->assignRef('menuitem_open', $menuitem_open);
		$menuitem_open_select = $redirectonloginHelper->menuitems('menuitem_open', '', array($menuitem_open));	
		$this->assignRef('menuitem_open_select', $menuitem_open_select);
		
		$dynamic_open = $this->controller->rol_config['dynamic_open'];			
		$this->assignRef('dynamic_open', $dynamic_open);
		$dynamic_open_select = $redirectonloginHelper->get_dynamics_select('dynamic_open', $dynamic_open);	
		$this->assignRef('dynamic_open_select', $dynamic_open_select);		
		
		$menuitem_logout = $this->controller->rol_config['menuitem_logout'];				
		$this->assignRef('menuitem_logout', $menuitem_logout);
		$menuitem_logout_select = $redirectonloginHelper->menuitems('menuitem_logout', '', array($menuitem_logout));	
		$this->assignRef('menuitem_logout_select', $menuitem_logout_select);
		
		$dynamic_logout = $this->controller->rol_config['dynamic_logout'];			
		$this->assignRef('dynamic_logout', $dynamic_logout);
		$dynamic_logout_select = $redirectonloginHelper->get_dynamics_select('dynamic_logout', $dynamic_logout);	
		$this->assignRef('dynamic_logout_select', $dynamic_logout_select);
		
		$menuitem_registration = $this->controller->rol_config['menuitem_registration'];				
		$this->assignRef('menuitem_registration', $menuitem_registration);
		$menuitem_registration_select = $redirectonloginHelper->menuitems('menuitem_registration', '', array($menuitem_registration));	
		$this->assignRef('menuitem_registration_select', $menuitem_registration_select);
		
		$dynamic_registration = $this->controller->rol_config['dynamic_registration'];			
		$this->assignRef('dynamic_registration', $dynamic_registration);
		$dynamic_registration_select = $redirectonloginHelper->get_dynamics_select('dynamic_registration', $dynamic_registration);	
		$this->assignRef('dynamic_registration_select', $dynamic_registration_select);
		
		$menuitem_first = $this->controller->rol_config['menuitem_first'];				
		$this->assignRef('menuitem_first', $menuitem_first);
		$menuitem_first_select = $redirectonloginHelper->menuitems('menuitem_first', '', array($menuitem_first));	
		$this->assignRef('menuitem_first_select', $menuitem_first_select);
		
		$dynamic_first = $this->controller->rol_config['dynamic_first'];			
		$this->assignRef('dynamic_first', $dynamic_first);
		$dynamic_first_select = $redirectonloginHelper->get_dynamics_select('dynamic_first', $dynamic_first);	
		$this->assignRef('dynamic_first_select', $dynamic_first_select);		
			
		$dynamic_run_script_select = $redirectonloginHelper->get_dynamics_select('run_script', $this->controller->rol_config['run_script']);	
		$this->assignRef('dynamic_run_script_select', $dynamic_run_script_select);
		
		$logoutbackend_menuitem = $this->controller->rol_config['logoutbackend_menuitem'];		
		$menuitem_logoutbackend_select = $redirectonloginHelper->menuitems('logoutbackend_menuitem', '', array($logoutbackend_menuitem));	
		$this->assignRef('menuitem_logoutbackend_select', $menuitem_logoutbackend_select);
		
		$logoutbackend_dynamic = $this->controller->rol_config['logoutbackend_dynamic'];		
		$logoutbackend_dynamic_select = $redirectonloginHelper->get_dynamics_select('logoutbackend_dynamic', $logoutbackend_dynamic);	
		$this->assignRef('logoutbackend_dynamic_select', $logoutbackend_dynamic_select);
		
		$loginbackend_dynamic = $this->controller->rol_config['loginbackend_dynamic'];		
		$loginbackend_dynamic_select = $redirectonloginHelper->get_dynamics_select('loginbackend_dynamic', $loginbackend_dynamic);	
		$this->assignRef('loginbackend_dynamic_select', $loginbackend_dynamic_select);
		
		$lang = JFactory::getLanguage();
		$lang->load('com_users', JPATH_ROOT, null, false);
		
		//toolbar			
		JToolBarHelper::apply('allusers_save', 'JToolbar_Apply');	
		
		if($redirectonloginHelper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}			
		
		parent::display($tpl);
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=allusers');	
				
		$controller->add_submenu();			
		
		$this->sidebar = JHtmlSidebar::render();
	}	
	

	
}
?>