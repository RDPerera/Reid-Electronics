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

class redirectonloginViewUsergroups extends JViewLegacy{
	
	protected $state;
	protected $items;
	protected $pagination;

	function display($tpl = null){
	
		$ds = DIRECTORY_SEPARATOR;
	
		//get configuration
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);
		
		$this->state = $this->get('State');				
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');	
		
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();
		$this->assignRef('helper', $helper);	

		if($helper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}
		
		parent::display($tpl);
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=usergroups');	
				
		$controller->add_submenu();		
		
		$this->sidebar = JHtmlSidebar::render();
	}	
	
	
	protected function getSortFields(){
		
		return array(
			'a.title' => JText::_('COM_REDIRECTONLOGIN_USERGROUP_TITLE'),
			'a.lft' => JText::_('COM_REDIRECTONLOGIN_USERGROUP').' '.JText::_('COM_REDIRECTONLOGIN_HIERARCHY'),
			't.frontend_type' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGIN'),
			't.opening_site' => JText::_('COM_REDIRECTONLOGIN_WHEN_OPENING_SITE'),
			't.frontend_type_logout' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGOUT'),
			't.backend_type' => JText::_('COM_REDIRECTONLOGIN_REDIRECT_TYPE').' '.JText::_('COM_REDIRECTONLOGIN_BACKEND'),
			'o.redirect_order_front' => JText::_('COM_REDIRECTONLOGIN_ORDERING').' '.JText::_('COM_REDIRECTONLOGIN_FRONTEND'),
			'o.redirect_order_back' => JText::_('COM_REDIRECTONLOGIN_ORDERING').' '.JText::_('COM_REDIRECTONLOGIN_BACKEND'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
	
	
}
?>