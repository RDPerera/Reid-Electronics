<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_10F
	* Creation date: Septembre 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class GMapFPsViewElement_perso extends JViewLegacy
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$option = $option.'_perso';

		JHTML::_('behavior.tooltip');

		// Get data from the model
		$items	= $this->get( 'Data');
		$this->assignRef('items',	$items);

		$pageNav = $this->get( 'Pagination' );				
		$this->assignRef('pageNav', $pageNav);

		$filter_order = $mainframe->getUserStateFromRequest($option.'filter_order', 'filter_order', 'nom', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search_perso	= $mainframe->getUserStateFromRequest($option.'search_perso', 'search_perso', '',	'string' );
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		$lists['search_perso'] = $search_perso;
		$this->assignRef('lists', $lists);

		parent::display($tpl);

	}
}
