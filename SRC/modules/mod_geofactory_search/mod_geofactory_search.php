<?php
/**
 * @name		Geocode Factory Search module
 * @package		mod_geofactory_search
 * @copyright	Copyright © 2014 - All rights reserved.
 * @license		GNU/GPL
 * @author		Cédric Pelloquin
 * @author mail	info@myJoom.com
 * @website		www.myJoom.com
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

 
// Include the syndicate functions only once
require_once __DIR__.'/helper.php';
modGeofactorySearchHelper::setJsInit($params) ;
$labels		= modGeofactorySearchHelper::getLabels($params) ;
$lmb		= modGeofactorySearchHelper::getLocateMeBtn($params);
$radInpHtml	= modGeofactorySearchHelper::getRadiusInput($params,$lmb);
$radDistHtml= modGeofactorySearchHelper::getRadiusDistances($params);
$radIntro	= modGeofactorySearchHelper::getRadiusIntro($params);
$buttons	= modGeofactorySearchHelper::getButtons($params,$labels);
$barHtml	= modGeofactorySearchHelper::getSideBar($params);
$listHtml 	= modGeofactorySearchHelper::getSideLists($params);
require(JModuleHelper::getLayoutPath( 'mod_geofactory_search' ));

?>