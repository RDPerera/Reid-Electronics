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

class GeofactoryControllerOldmaps extends JControllerAdmin{
	public function getModel($name = 'Oldmap', $prefix = 'GeofactoryModel', $config = array('ignore_request' => true)){
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function import(){
		$model = $this->getModel('oldmaps');
		$model->import();
		$this->setRedirect(JRoute::_('index.php?option=com_geofactory&view=oldmaps', false), JText::_('COM_GEOFACTORY_IMP_DONE'));
	}
}
