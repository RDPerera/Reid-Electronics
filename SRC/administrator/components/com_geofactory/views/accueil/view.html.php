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

class GeofactoryViewAccueil extends JViewLegacy{
	public function display($tpl = null){
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar(){
		JToolbarHelper::title(JText::_('COM_GEOFACTORY'));

//		parent::addToolbar();
//		JToolbarHelper::help('COM_GEOFACTORY_HELP_XXX');
	}
}
