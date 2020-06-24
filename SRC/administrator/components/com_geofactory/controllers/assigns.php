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

/**
 * pour chaque plugin, on a 1 enregistrement obligatoire nomé Default - Nom produit,
 * qui ne peut être supprimé et qui est crée par defaut automatiquement
 */
class GeofactoryControllerAssigns extends JControllerAdmin{
	public function getModel($name = 'Assign', $prefix = 'GeofactoryModel', $config = array('ignore_request' => true)){
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
