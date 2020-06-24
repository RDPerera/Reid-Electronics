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
class GeofactoryViewOldmaps extends JViewLegacy{
	protected $items;
	protected $pagination;
	protected $state;

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
		parent::display($tpl);
	}

	protected function addToolbar(){
		require_once JPATH_COMPONENT.'/helpers/geofactory.php';
		$canDo	= GeofactoryHelperAdm::getActions();

		JToolbarHelper::title(JText::_('COM_GEOFACTORY_MAPS_IMPORT'));
		JToolbarHelper::custom('oldmaps.import', ' ', JText::_('COM_GEOFACTORY_MAPS_IMPORT_NOW'), JText::_('COM_GEOFACTORY_MAPS_IMPORT_NOW_DESC'));
	
		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_geofactory');
		}

//		JToolbarHelper::help('COM_GEOFACTORY_HELP_XXX');
	}
}
