<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_50F
	* Creation date: Octobre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class GMapFPsViewPersonnalisations extends JViewLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$controller = JRequest::getWord('controller');

		if (JRequest::getWord('task')=='view') 
		{
			JToolBarHelper::title(   JText::_( 'GMAPFP_PERSONNALISATIONS_MANAGER' ), 'article.png' );
			JToolBarHelper::addNew();
			JToolBarHelper::editList();
			JToolBarHelper::divider();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::divider();
			JToolBarHelper::deleteList();
		
			// Get data from the model
			$personnalisations	= $this->get('List');
		}
		else
		{
			// Get data from the model
			$personnalisations		= $this->get('Data');
			$isNew		= ($personnalisations->id < 1);
	
			$text = $isNew ? JText::_( 'JTOOLBAR_NEW' ) : JText::_( 'JTOOLBAR_EDIT' );
			JToolBarHelper::title(   JText::_( 'GMAPFP_PERSONNALISATIONS_MANAGER' ).': <small>[ ' . $text.' ]</small>', 'article.png' );
			JToolBarHelper::apply();
			JToolBarHelper::save();
			if ($isNew)  {
				JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}
		}
		JHTML::_('behavior.tooltip');

		$this->personnalisations = $personnalisations;
		$this->sidebar = JHtmlSidebar::render();
		
		$this->pageNav = $this->get( 'Pagination' );				

		$filter_order 		= $mainframe->getUserStateFromRequest( $option.$controller.'filter_order', 'filter_order', 'nom', 'cmd');
		$filter_order_Dir 	= $mainframe->getUserStateFromRequest( $option.$controller.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		$this->lists = $lists;

		parent::display();

	}
}
