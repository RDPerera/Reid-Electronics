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
	protected $total ;

	public function display($tpl = null){
		$app 				= JFactory::getApplication();
		$this->total 		= $app->input->get('total', -1) ;
		$this->type 		= $app->input->get('typeliste', "NO_TYPE") ;
		$this->assign 		= $app->input->get('assign', -1) ;
		$this->items		= $this->get('Items');
		$this->idsToGc		= $this->get('idsToGc', '');	

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}


	protected function addToolbar(){
		$canDo	= GeofactoryHelperAdm::getActions();
		JToolbarHelper::title(JText::_('COM_GEOFACTORY_BATCH_GEOCODE'));

	}
}
