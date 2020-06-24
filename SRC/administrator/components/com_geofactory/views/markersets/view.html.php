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

class GeofactoryViewMarkersets extends JViewLegacy{
	protected $items;
	protected $pagination;
	protected $state;
	protected $listTypeList ;

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

	protected function _getTypeListeName($test){
		if (! is_array($this->listTypeList))
			$this->listTypeList = GeofactoryHelperAdm::getArrayObjTypeListe() ;

		foreach($this->listTypeList as $type){
			if (strtolower($test) != strtolower($type->value))
				continue ;

			return $type->text ;
		}

		return "?";
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar(){
		require_once JPATH_COMPONENT.'/helpers/geofactory.php';
		$canDo	= GeofactoryHelperAdm::getActions();

		JToolbarHelper::title(JText::_('COM_GEOFACTORY_MARKERSETS_MARKERSETS'));
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('markerset.add');
		}
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList('markerset.edit');
		}
		if ($canDo->get('core.edit.state')){
			JToolbarHelper::publish('markersets.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('markersets.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('markersets.archive');
			JToolbarHelper::checkin('markersets.checkin');
		}
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')){
			JToolbarHelper::deleteList('', 'markersets.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')){
			JToolbarHelper::trash('markersets.trash');
		}

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_geofactory');
		}

//		JToolbarHelper::help('COM_GEOFACTORY_HELP_XXX');

		JHtmlSidebar::setAction('index.php?option=com_geofactory&view=markersets');
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);
		JHtmlSidebar::addFilter(
			JText::_('COM_GEOFACTORY_FILTER_MAPS'),
			'filter_map_id',
			JHtml::_('select.options', GeofactoryHelperAdm::getMapsOptions(1), 'value', 'text', $this->state->get('filter.map_id'))
		);
		JHtmlSidebar::addFilter(
			JText::_('COM_GEOFACTORY_FILTER_EXT'),
			'filter_extension',
			JHtml::_('select.options', GeofactoryHelperAdm::getArrayObjTypeListe(), 'value', 'text', $this->state->get('filter.extension'))
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
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.status' => JText::_('JSTATUS'),
			'a.name' => JText::_('COM_GEOFACTORY_MARKERSETS_HEADING'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
