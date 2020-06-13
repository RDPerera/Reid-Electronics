<?php

/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */defined('_JEXEC') or die;

class GeofactoryViewGgmaps extends JViewLegacy{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
		// Initialise variables.
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
//		GeofactoryHelperAdm::addSubmenu();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/geofactory.php';

		$canDo	= GeofactoryHelperAdm::getActions();

		JToolbarHelper::title(JText::_('COM_GEOFACTORY_MAPS_MAPS'));
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('ggmap.add');
		}
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList('ggmap.edit');
		}
		if ($canDo->get('core.edit.state')){
			JToolbarHelper::publish('ggmaps.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('ggmaps.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('ggmaps.archive');
			JToolbarHelper::checkin('ggmaps.checkin');
		}
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')){
			JToolbarHelper::deleteList('', 'ggmaps.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')){
			JToolbarHelper::trash('ggmaps.trash');
		}

		if ($canDo->get('core.admin')){
			JToolbarHelper::preferences('com_geofactory');
		}

//		JToolbarHelper::help('COM_GEOFACTORY_HELP_XXX');

		JHtmlSidebar::setAction('index.php?option=com_geofactory&view=ggmaps');
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);
		JHtmlSidebar::addFilter(
			JText::_('COM_GEOFACTORY_FILTER_MAKERSETS'),
			'filter_markerset_id',
			JHtml::_('select.options', GeofactoryHelperAdm::getMarkersetsOptions(1), 'value', 'text', $this->state->get('filter.markerset_id'))
		);
	}
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.status' => JText::_('JSTATUS'),
			'a.name' => JText::_('COM_GEOFACTORY_MAPS_HEADING'),
			'nbrMs' => JText::_('COM_GEOFACTORY_MAPS_NBR_MS'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
