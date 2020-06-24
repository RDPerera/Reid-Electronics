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
require_once JPATH_COMPONENT.'/helpers/geofactory.php';

class GeofactoryTableOldmap extends JTable{

	public function __construct(&$_db){
		$config = JComponentHelper::getParams('com_geofactory');
		$extDb =  $config->get('import-database');
		if (strlen($extDb)>0){ 
			$_db = GeofactoryHelperAdm::loadExternalDb();
		}

		parent::__construct('#__geocode_factory_maps', 'id', $_db);
	}

	public function load($id = null, $reset = true){
		// charge l'objet de base
		parent::load($id) ;
		// charge les params multi de l'ancien GF
		$listVar = array('kml_file'=>'', 'wheelZoom'=>0, 'mapTypeBar'=>"DEFAULT", 'drawCircle'=>1, 'frontDistSelect'=>'10,25,50,100,500', 'fe_rad_unit'=>0, 'clickRadius'=>1, 'cacheTime'=>0, 'showFriends'=>1, 'useTabs'=>0, 'allowDbl'=>0, 'useGallery'=>0, 'tiles'=>'', 'bubbleOnOver'=>0, 'lineMyAddresses'=>0, 'useRoutePlaner'=>0, 'useBrowserRadLoad'=>0, 'mapTypeAvailable'=>"SATELLITE,HYBRID,TERRAIN,ROADMAP",'pegman'=>1,'scaleControl'=>1, 'rotateControl'=>1, 'overviewMapControl'=>1, 'gridSize'=>'', 'imagePath'=>'', 'imageSizes'=>'', 'minimumClusterSize'=>'', 'randomMarkers'=>1, 'layers'=>"", 'trackOnOver'=>0, 'trackZoom'=>0, 'mapStyle'=>'', 'salesRadMode'=>0,'acCountry'=>'','acTypes'=>0);
		GeofactoryHelperAdm::loadMultiParamFor($listVar, 1, $id, $this);	
	}
}
