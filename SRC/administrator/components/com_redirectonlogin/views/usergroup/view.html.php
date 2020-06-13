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

class redirectonloginViewUsergroup extends JViewLegacy{
	
	function display($tpl = null){
					
		$database = JFactory::getDBO();
		$ds = DIRECTORY_SEPARATOR;
		
		//get controller
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);	
		
		//get group id
		$group_id = intval(JRequest::getVar('group_id', ''));
		$this->assignRef('group_id', $group_id);
		
		//get redirect
		$database->setQuery("SELECT * "
		."FROM #__redirectonlogin_groups "
		."WHERE group_id='$group_id' "
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
		$inherit_login = 0;
		$inherit_open = 0;
		$inherit_logout = 0;
		$inherit_backend = 0;
		$logoutbackend_menu = 0;
		$logoutbackend_dyna = 0;
		$logoutbackend_inherit = 0;
		$first_menu = 0;
		$first_dyna = 0;
		$first_inherit = 0;
		$loginbackend_dynamic = 0;
		foreach($redirects as $temp){
			$redirect = $temp;	
			$menuitem_login = $temp->menuitem_login;
			$dynamic_login = $temp->dynamic_login;	
			$menuitem_open = $temp->menuitem_open;
			$dynamic_open = $temp->dynamic_open;	
			$menuitem_logout = $temp->menuitem_logout;
			$dynamic_logout = $temp->dynamic_logout;
			$inherit_login = $temp->inherit_login;
			$inherit_open = $temp->inherit_open;
			$inherit_logout = $temp->inherit_logout;
			$inherit_backend = $temp->inherit_backend;
			$logoutbackend_menu = $temp->logoutbackend_menu;
			$logoutbackend_dyna = $temp->logoutbackend_dyna;
			$logoutbackend_inherit = $temp->logoutbackend_inherit;
			$first_menu = $temp->first_menu;
			$first_dyna = $temp->first_dyna;
			$first_inherit = $temp->first_inherit;
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
		
		$inherit_login_select = $this->get_usergroup_select('inherit_login', $inherit_login);	
		$this->assignRef('inherit_login_select', $inherit_login_select);
		
		$inherit_open_select = $this->get_usergroup_select('inherit_open', $inherit_open);	
		$this->assignRef('inherit_open_select', $inherit_open_select);
		
		$inherit_logout_select = $this->get_usergroup_select('inherit_logout', $inherit_logout);	
		$this->assignRef('inherit_logout_select', $inherit_logout_select);
		
		$inherit_backend_select = $this->get_usergroup_select('inherit_backend', $inherit_backend);	
		$this->assignRef('inherit_backend_select', $inherit_backend_select);
		
		$menuitem_logoutbackend_select = $redirectonloginHelper->menuitems('logoutbackend_menu', '', array($logoutbackend_menu));	
		$this->assignRef('menuitem_logoutbackend_select', $menuitem_logoutbackend_select);
		
		$dyna_logoutbackend_select = $redirectonloginHelper->get_dynamics_select('logoutbackend_dyna', $logoutbackend_dyna);	
		$this->assignRef('dyna_logoutbackend_select', $dyna_logoutbackend_select);
		
		$inherit_logoutbackend_select = $this->get_usergroup_select('logoutbackend_inherit', $logoutbackend_inherit);	
		$this->assignRef('inherit_logoutbackend_select', $inherit_logoutbackend_select);
		
		$menuitem_first_select = $redirectonloginHelper->menuitems('first_menu', '', array($first_menu));	
		$this->assignRef('menuitem_first_select', $menuitem_first_select);
		
		$dyna_first_select = $redirectonloginHelper->get_dynamics_select('first_dyna', $first_dyna);	
		$this->assignRef('dyna_first_select', $dyna_first_select);
		
		$inherit_first_select = $this->get_usergroup_select('first_inherit', $first_inherit);	
		$this->assignRef('inherit_first_select', $inherit_first_select);
			
		$loginbackend_dynamic_select = $redirectonloginHelper->get_dynamics_select('loginbackend_dynamic', $loginbackend_dynamic);	
		$this->assignRef('loginbackend_dynamic_select', $loginbackend_dynamic_select);
					
		//get components
		$components_array = $this->controller->get_components_array();		
		$this->assignRef('components', $components_array);
		
		//get usergroup name
		$database->setQuery("SELECT title "
		."FROM #__usergroups "
		."WHERE id='$group_id' "
		."LIMIT 1 "
		);
		$groups = $database->loadObjectList();
		$group_title = '';
		foreach($groups as $group){
			$group_title = $group->title;
		}	
		$this->assignRef('group_title', $group_title);
			
			
		// Check for errors.
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		//toolbar		
		JToolBarHelper::apply('usergroup_apply', 'JToolbar_Apply');
		JToolBarHelper::save('usergroup_save', 'JToolbar_Save');
		JToolBarHelper::cancel('cancel', 'JToolbar_Close');		
		
		if($redirectonloginHelper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}
		
		parent::display($tpl);
	}	
	
	function get_groups(){
		$database = JFactory::getDBO();
		$database->setQuery(
			'SELECT a.id AS id, a.title AS name, COUNT(DISTINCT b.id) AS level' .
			' FROM #__usergroups AS a' .
			' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' GROUP BY a.id' .
			' ORDER BY a.lft ASC'
		);
		$groups = $database->loadObjectList();
		foreach ($groups as &$group) {
			$group->name = str_repeat('- ',$group->level).$group->name;
		}
		return $groups;
	}
	
	function get_usergroup_select($element_name, $selection){	
		$groups = $this->get_groups();						
		$groups_select = '<select name="'.$element_name.'">';			
		$groups_select .= '<option value="0"> - '.JText::_('COM_REDIRECTONLOGIN_SELECT_USERGROUP').' - </option>';			
		foreach ($groups as $group){				
			$groups_select .= '<option';
			if($group->id==$selection){
				$groups_select .= ' selected="selected"';
			}
			$groups_select .= ' value="'.$group->id.'">'.$group->name.'</option>';
		}
		$groups_select .= '</select>';
		
		return $groups_select;
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=usergroup');	
				
		$controller->add_submenu();			
		
		$this->sidebar = JHtmlSidebar::render();
	}
	
}
?>