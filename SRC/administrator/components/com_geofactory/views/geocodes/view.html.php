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

class GeofactoryViewGeocodes extends JViewLegacy{
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
		$this->sidebar = JHtmlSidebar::render();
//		GeofactoryHelperAdm::addSubmenu();
		parent::display($tpl);
	}

	protected function addToolbar(){
		require_once JPATH_COMPONENT.'/helpers/geofactory.php';

		$canDo	= GeofactoryHelperAdm::getActions();
		JToolbarHelper::title(JText::_('COM_GEOFACTORY_BATCH_GEOCODE'));

		// prépare les listes déroulantes
		$valTypes	= GeofactoryHelperAdm::getArrayObjTypeListe() ;
		$curType	= $this->escape($this->state->get('filter.typeliste'));
		$valAssign	= GeofactoryHelperAdm::getArrayObjAssign($curType) ;
		$assign		= $this->escape($this->state->get('filter.assign'));

		// prépare l'url
		$params = array() ;
		$params[] = "tmpl=component";
		$params[] = "total=".count($this->items) ;
		$params[] = "type=".$curType ;
		$params[] = "assign=".$assign ;

	//	$bar = JToolBar::getInstance('toolbar');
		$url = 'index.php?option=com_geofactory&amp;'. implode('&amp;',$params) ;
	//	$bar->appendButton('slider', 'geocodefiltered', JText::_('COM_GEOFACTORY_GEOCODE_FILTERED'), $url."&amp;task=geocodes.geocodefiltered", 800, 400, 0, 0, 'jQuery("form#adminForm").submit();', JText::_('COM_GEOFACTORY_BATCH_GEOCODE'));
	//	$bar->appendButton('slider', 'geocodeselected', JText::_('COM_GEOFACTORY_GEOCODE_SELECTED'), $url."&amp;task=geocodes.geocodeselected", 800, 400, 0, 0, 'jQuery("form#adminForm").submit();', JText::_('COM_GEOFACTORY_BATCH_GEOCODE'));
		// remetre popup, dès que c'est bon et si selected ne fonctionne
		// pas, on peut essayer de recharger la page en mode normal, avec :  
		JToolbarHelper::custom('geocodes.geocodefiltered', 'flag', 'flag', 'COM_GEOFACTORY_GEOCODE_FILTERED', false);
		JToolbarHelper::custom('geocodes.geocodeselected', 'flag', 'flag', 'COM_GEOFACTORY_GEOCODE_SELECTED', true);

		// ajout d'un bouton delete 
		if ($curType=='MS_K2')
			JToolbarHelper::deleteList('Delete this position?','geocodes.deletek2', 'Delete position');

		if ($curType=='MS_JC')
			JToolbarHelper::deleteList('Delete this position?','geocodes.deletejc', 'Delete position');

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_geofactory');
			JToolbarHelper::divider();
		}
//		JToolbarHelper::help('COM_GEOFACTORY_HELP_XXX');

		JHtmlSidebar::setAction('index.php?option=com_geofactory&view=geocodes');
		JHtmlSidebar::addFilter(
			JText::_('COM_GEOFACTORY_SELECT_TYPE'),
			'typeliste',
			JHtml::_('select.options', $valTypes, 'value', 'text', $curType)
		);
		JHtmlSidebar::addFilter(
			JText::_('COM_GEOFACTORY_SELECT_PATTERN'),
			'assign',
			JHtml::_('select.options', $valAssign, 'value', 'text', $assign)
		);
	}
}
