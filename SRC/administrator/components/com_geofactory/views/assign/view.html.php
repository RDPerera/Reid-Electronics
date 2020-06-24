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
JLoader::register('GeofactoryHelper', JPATH_COMPONENT.'/helpers/geofactory.php');

class GeofactoryViewAssign extends JViewLegacy{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null){
		// Initialise variables.
		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');
		$this->state= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar(){
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= GeofactoryHelperAdm::getActions();

		JToolbarHelper::title($isNew ? JText::_('COM_GEOFACTORY_ASSIGN_PATTERN_NEW') : JText::_('COM_GEOFACTORY_ASSIGN_PATTERN_EDIT'));

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||$canDo->get('core.create'))) {
			JToolbarHelper::apply('assign.apply');
			JToolbarHelper::save('assign.save');
		}
		if (!$checkedOut && $canDo->get('core.create')) {
			JToolbarHelper::save2new('assign.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolbarHelper::save2copy('assign.save2copy');
		}

		if (empty($this->item->id))  {
			JToolbarHelper::cancel('assign.cancel');
		} else {
			JToolbarHelper::cancel('assign.cancel', 'JTOOLBAR_CLOSE');
		}

//		JToolbarHelper::divider();
//		JToolbarHelper::help('COM_GEOFACTORY_HELP_XXX');
	}
}
