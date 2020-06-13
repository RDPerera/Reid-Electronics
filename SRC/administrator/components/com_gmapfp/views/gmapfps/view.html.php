<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.50F
	* Creation date: Octobre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class GMapFPsViewGMapFPs extends JViewLegacy
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 

		JHTML::_('behavior.tooltip');

		// Get data from the model
		$this->items	= $this->get( 'Data');
		$this->pageNav 	= $this->get( 'Pagination' );

		$filtrevilles = array();
		$filtrevilles[] = JHTML::_('select.option', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --' );
				foreach($this->get('listville') as $temp) {
					$filtrevilles[] = JHTML::_('select.option', $temp->ville );
				}

		$filtredepartements = array();
		$filtredepartements[] = JHTML::_('select.option', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --' );
				foreach($this->get('listdepartement') as $temp) {
					$filtredepartements[] = JHTML::_('select.option', $temp->departement );
				}

		$filtrecategories = array();
		$filtrecategories[] = JHTML::_('select.option', 0, '-- '.JText::_( 'JCATEGORY' ).' --' );
				foreach(JHtml::_('category.options', 'com_gmapfp') as $temp) {
					$filtrecategories[] = JHTML::_('select.option', $temp->value, $temp->text );
				}

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir 	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 	'filter_order_Dir', 	'', 											'word');
		$search				= $mainframe->getUserStateFromRequest( $option.'search', 			'search', 				'',												'string' );
		$filtreville 		= $mainframe->getUserStateFromRequest( $option.'filtreville', 		'filtreville', 			'-- '.JText::_( 'GMAPFP_VILLE' ).' --', 		'string' );
		$filtredepartement 	= $mainframe->getUserStateFromRequest( $option.'filtredepartement', 'filtredepartement', 	'-- '.JText::_( 'GMAPFP_DEPARTEMENT' ).' --', 	'string' );
		$filtrecategorie 	= $mainframe->getUserStateFromRequest( $option.'filtrecategorie', 	'filtrecategorie', 		'0', 	'int' );

		$lists['ville'] 		= JHTML::_('select.genericlist', $filtrevilles, 'filtreville', 'size="1" class="inputbox small" onchange="form.submit()"', 'value', 'text', $filtreville );
		$lists['departement'] 	= JHTML::_('select.genericlist', $filtredepartements, 'filtredepartement', 'size="1" class="inputbox small" onchange="form.submit()"', 'value', 'text', $filtredepartement );
		$lists['categorie'] 	= JHTML::_('select.genericlist', $filtrecategories, 'filtrecategorie', 'size="1" class="inputbox small" onchange="form.submit()"', 'value', 'text', $filtredepartement );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;
		
		$this->lists = $lists;

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);

	}

	protected function addToolbar()
	{
		JToolBarHelper::title(   JText::_( 'GMAPFP_LIEUX_MANAGER' ), 'frontpage.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );
		JToolBarHelper::custom( 'user', 'restore.png', 'restore_f2.png', 'User' );
		JToolBarHelper::divider();
		JToolBarHelper::deleteList();
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_gmapfp');
	}
	
}
