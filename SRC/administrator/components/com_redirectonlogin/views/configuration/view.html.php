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

class redirectonloginViewConfiguration extends JViewLegacy{
	
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
		
		//language
		$lang = JFactory::getLanguage();		
		$lang->load('com_installer', JPATH_ADMINISTRATOR, null, false);		

		//toolbar
		// Options button.
		if (JFactory::getUser()->authorise('core.admin', 'com_redirectonlogin')) {
			JToolBarHelper::preferences('com_redirectonlogin');
		}		
		JToolBarHelper::apply('config_save', 'JToolbar_Apply');	
		
		if($redirectonloginHelper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}			
		
		parent::display($tpl);
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=configuration');	
				
		$controller->add_submenu();			
		
		$this->sidebar = JHtmlSidebar::render();
	}	
	
	function check_cb_problem(){
		
		$db = JFactory::getDBO();
		
		$return = 0;
		if(file_exists(JPATH_SITE.'/plugins/system/communitybuilder/communitybuilder.php')){
			//CB system plugin installed
			
			//check if plugin is enabled and doing its evil thing
			$query = $db->getQuery(true);
			$query->select('params');
			$query->from('#__extensions');			
			$query->where('element='.$db->q('communitybuilder'));
			$query->where('folder='.$db->q('system'));
			$query->where('enabled='.$db->q('1'));			
			$rows = $db->setQuery($query);				
			$rows = $db->loadObjectList();
				
			foreach($rows as $row){		
				$params = $row->params;	
				$registry = new JRegistry;
				$registry->loadString($params);
				$params_array = $registry->toArray();
				if(isset($params_array['return_urls'])){
					if($params_array['return_urls']){
						$return = 1;
					}
				}
			}
		
		}		
		return $return;
	}
	
	function check_jomsocial_facebook_plugin(){
	
		$db = JFactory::getDBO();
		
		$return = 0;
		
		$query = $db->getQuery(true);
		$query->select('enabled');
		$query->from('#__extensions');		
		$query->where('element='.$db->q('jomsocialconnect'));
		$query->where('folder='.$db->q('system'));		
		$rows = $db->setQuery($query);				
		$rows = $db->loadObjectList();
			
		foreach($rows as $row){		
			$return = $row->enabled;
		}
		
		return $return;	
	}
	

	
}
?>