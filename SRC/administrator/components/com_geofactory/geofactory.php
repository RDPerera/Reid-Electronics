<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */

// _JEXEC is after the next comment

defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_geofactory')){
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller	= JControllerLegacy::getInstance('Geofactory');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
