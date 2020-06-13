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

class redirectonloginViewDynamicredirects extends JViewLegacy{
	
	protected $state;
	protected $items;
	protected $pagination;

	function display($tpl = null){
	
		$ds = DIRECTORY_SEPARATOR;
	
		//get configuration
		$controller = new redirectonloginController();	
		$this->assignRef('controller', $controller);
		
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();
		$this->assignRef('helper', $helper);
		
		$this->state = $this->get('State');				
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');		

		//toolbar	
		JToolBarHelper::custom('dynamicredirect','new.png','new_f2.png',JText::_('JTOOLBAR_NEW'),false,false);	
		JToolBarHelper::custom('dynamicredirect_delete','delete.png','delete_f2.png',JText::_('JTOOLBAR_DELETE'),false,false);	
		
		if($helper->joomla_version >= '3.0'){
			//sidebar
			$this->add_sidebar($controller);	
		}		
		
		parent::display($tpl);
	}
	
	function add_sidebar($controller){
	
		JHtmlSidebar::setAction('index.php?option=com_redirectonlogin&view=dynamicredirects');	
				
		$controller->add_submenu();		
		
		$this->sidebar = JHtmlSidebar::render();
	}
	
	protected function getSortFields(){
		
		return array(
			'a.name' => JText::_('COM_REDIRECTONLOGIN_NAME'),
			'a.ordering' => JText::_('COM_REDIRECTONLOGIN_ORDERING'),			
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
?>