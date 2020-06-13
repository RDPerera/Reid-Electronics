<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */

defined('_JEXEC') or die;

class GeofactoryViewAssigns extends JViewLegacy{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null) {
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

	protected function addToolbar(){
		require_once JPATH_COMPONENT.'/helpers/geofactory.php';
		$canDo	= GeofactoryHelperAdm::getActions();

		JToolbarHelper::title(JText::_('COM_GEOFACTORY_ASSIGN_PATTERN'));
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('assign.add');
		}
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList('assign.edit');
		}
		if ($canDo->get('core.edit.state')){
			JToolbarHelper::publish('assigns.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('assigns.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('assigns.archive');
			JToolbarHelper::checkin('assigns.checkin');
		}
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')){
			JToolbarHelper::deleteList('', 'assigns.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')){
			JToolbarHelper::trash('assigns.trash');
		}

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_geofactory');
		}

//		JToolbarHelper::help('COM_GEOFACTORY_HELP_XXX');

		JHtmlSidebar::setAction('index.php?option=com_geofactory&view=assigns');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);
	}

	protected function getSortFields(){
		return array(
			'a.name' => JText::_('COM_GEOFACTORY_PATTERN_HEADING'),
			'a.typeList' => JText::_('COM_GEOFACTORY_EXTENSION'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
