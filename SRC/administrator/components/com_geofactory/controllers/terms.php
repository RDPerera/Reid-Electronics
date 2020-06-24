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

class GeofactoryControllerTerms extends JControllerLegacy{
	public function apply(){
		$model = $this->getModel('terms');
		$model->setTerms(0);

		$this->setRedirect(JRoute::_('index.php?option=com_geofactory', false), JText::_('COM_GEOFACTORY_TERMS_WELCOME'));
	}

	public function cancel(){
		$model = $this->getModel('terms');
		$model->setTerms(1);

		$this->setRedirect(JRoute::_('index.php', false), JText::_('COM_GEOFACTORY_TERMS_WARNING'));
	}
}
