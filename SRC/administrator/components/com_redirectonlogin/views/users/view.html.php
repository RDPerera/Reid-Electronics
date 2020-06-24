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

class redirectonloginViewUsers extends JViewLegacy{
	
	protected $items;	
	protected $state;
	protected $pagination;	
	protected $user_index;
	protected $group_level_index;
	
	public function display($tpl = null){
	
		$ds = DIRECTORY_SEPARATOR;
	
		//get configuration
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);
				
		$this->state		= $this->get('State');	
		$this->items		= $this->get('Items');			
		$this->pagination = $this->get('Pagination');	
		$this->user_index = $this->get_userindex($this->items);
		$this->group_level_index = $this->get_group_level_index();
		
		//get groups ordered by frontend order
		$groups_title_order_front = $this->get_groups_title_order();
		$this->assignRef('groups_title_order_front', $groups_title_order_front);
		
		//resort groups on backend order
		$groups_title_order_back = $groups_title_order_front;
		foreach ($groups_title_order_back as $key => $row) {
			$order[$key]  = $row['2'];    
		}
		$sort_order = SORT_ASC;
		array_multisort($order, $sort_order, $groups_title_order_back);
		$this->assignRef('groups_title_order_back', $groups_title_order_back);
		
		//get levels in order
		$levels_title_order = $this->get_levels_title_order();
		$this->assignRef('levels_title_order', $levels_title_order);
		
		//get levels in order backend
		$levels_title_order_backend = $this->get_levels_title_order_backend();
		$this->assignRef('levels_title_order_backend', $levels_title_order_backend);
		
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();
		$this->assignRef('helper', $helper);
		
		//include mod_menu language. Reuse or die ;-)#
		$lang = JFactory::getLanguage();
		$lang->load('mod_menu', JPATH_ADMINISTRATOR, null, false);
		
		if($helper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}

		parent::display($tpl);
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=users');	
				
		$controller->add_submenu();		
		
		JHtmlSidebar::addFilter(
			'- '.JText::_('JSELECT').' '.JText::_('JLIB_RULES_GROUPS').' -',
			'filter_group_id',
			JHtml::_('select.options', $this->get_groups(), 'value', 'text', $this->state->get('filter.group_id'))
		);
		
		JHtmlSidebar::addFilter(
			'- '.JText::_('JSELECT').' '.JText::_('MOD_MENU_COM_USERS_LEVELS').' -',
			'filter_level_id',
			JHtml::_('select.options', $this->get_levels(), 'value', 'text', $this->state->get('filter.level_id'))
		);	
		
		$this->sidebar = JHtmlSidebar::render();
	}
	
	protected function getSortFields(){
		
		return array(
			'a.name' => JText::_('COM_REDIRECTONLOGIN_NAME'),
			'a.username' => JText::_('COM_REDIRECTONLOGIN_USERNAME'),
			't.frontend_type' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGIN'),
			't.opening_site' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_WHEN_OPENING_SITE'),
			't.frontend_type_logout' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGOUT'),
			't.backend_type' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_BACKEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGIN'),	
			't.logoutbackend_type' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_BACKEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGOUT'),		
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}	
	
	function get_groups(){
		$database = JFactory::getDBO();
		$database->setQuery(
			'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
			' FROM #__usergroups AS a' .
			' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' GROUP BY a.id' .
			' ORDER BY a.lft ASC'
		);
		$groups = $database->loadObjectList();
		foreach ($groups as &$group) {
			$group->text = str_repeat('- ',$group->level).$group->text;
		}
		return $groups;
	}
	
	function get_levels(){
		$database = JFactory::getDBO();
		$database->setQuery("SELECT id AS value, title AS text "
		."FROM #__viewlevels "
		."ORDER BY ordering ASC "		
		);
		$accesslevels = $database->loadObjectList();
		return $accesslevels;		
	}
	
	function get_groups_title_order(){
		$database = JFactory::getDBO();
		$database->setQuery(
			"SELECT a.id AS group_id, a.title AS group_title, b.redirect_order_back ".
			"FROM #__usergroups AS a ".
			"LEFT JOIN `#__redirectonlogin_order_groups` AS b ON a.id=b.group_id ".		
			"ORDER BY b.redirect_order_front ASC "
		);
		$groups = $database->loadObjectList();	
		$groups_array = array();
		foreach($groups as $group){
			$groups_title_order[] = array($group->group_id, $group->group_title, $group->redirect_order_back);
		}	
		return $groups_title_order;
	}
	
	function get_levels_title_order(){
		$database = JFactory::getDBO();
		$database->setQuery(
			"SELECT a.id AS level_id, a.title AS level_title ".
			"FROM #__viewlevels AS a ".
			"LEFT JOIN `#__redirectonlogin_order_levels` AS b ON a.id=b.level_id ".		
			"ORDER BY b.redirect_order ASC "
		);
		$levels_title_order = $database->loadObjectList();				
		return $levels_title_order;
	}
	
	function get_levels_title_order_backend(){
		$database = JFactory::getDBO();
		$database->setQuery(
			"SELECT a.id AS level_id, a.title AS level_title ".
			"FROM #__viewlevels AS a ".
			"LEFT JOIN `#__redirectonlogin_order_levels` AS b ON a.id=b.level_id ".		
			"ORDER BY b.order_backend ASC "
		);
		$levels_title_order = $database->loadObjectList();				
		return $levels_title_order;
	}
	
	function get_users_groups($user_id){
		$groups = array();
		foreach($this->user_index as $user_group_row){
			if($user_id==$user_group_row->user_id){
				$groups[] = $user_group_row->group_id;
			}
		}
		return $groups;
	}
	
	static function get_userindex($current_users){
	
		$database = JFactory::getDBO();
		
		//only get those users we need for performance
		$user_id_string = '0';		
		foreach($current_users as $users){						
			$user_id_string .= ','.$users->id;			
		}			
		
		$database->setQuery(
		"SELECT user_id, group_id ".
		"FROM #__user_usergroup_map ".
		"WHERE user_id IN ($user_id_string) "		
		);
		$users_usergroups = $database->loadObjectList();		
		return $users_usergroups;		
	}
	
	static function get_group_level_index(){
		$database = JFactory::getDBO();
		$database->setQuery(
		"SELECT group_id, level_id, level_title ".
		"FROM #__redirectonlogin_map "				
		);
		$group_level_index = $database->loadObjectList();		
		return $group_level_index;	
	}
	
	function get_groups_levels($groups){
		$levels = array();		
		foreach($this->group_level_index as $group_level_row){
			if(in_array($group_level_row->group_id, $groups)){
				$levels[] = $group_level_row->level_id;
			}
		}
		$levels = array_unique($levels);
		return $levels;
	}
	
	
}
?>