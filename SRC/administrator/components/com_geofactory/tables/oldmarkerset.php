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


class GeofactoryTableOldmarkerset extends JTable{

	public function __construct(&$_db){
		$config = JComponentHelper::getParams('com_geofactory');
		$extDb =  $config->get('import-database');
		if (strlen($extDb)>0){ 
			$_db = GeofactoryHelperAdm::loadExternalDb();
		}

		parent::__construct('#__geocode_factory_markersets', 'id', $_db);
	}

	public function load($id = null, $reset = true){
		// charge l'objet de base
		parent::load($id) ;

		// charge les param multi en fonction du MS
		$listVar = array();
		if ($this->typeList=="MS_CB")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 'avatarAsIcon'=>0, 	'avatarSizeH'=>'','avatarSizeW'=>'', 'salesRadField'=>0,	'onlyOnline'=>0, 'onlineTmp'=>'<span style="color:green; font-weight:bold;">ONLINE</span>', 'offlineTmp'=>'<span style="color:red; font-weight:bold;">OFFLINE</span>', 'field_spec_lat'=>0, 'field_spec_lng'=>0);
		if ($this->typeList=="MS_JS")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 'avatarAsIcon'=>0, 	'avatarSizeH'=>'','avatarSizeW'=>'', 'salesRadField'=>0,	'onlyOnline'=>0, 'onlineTmp'=>'<span style="color:green; font-weight:bold;">ONLINE</span>', 'offlineTmp'=>'<span style="color:red; font-weight:bold;">OFFLINE</span>', 'field_spec_lat'=>0, 'field_spec_lng'=>0);
		if ($this->typeList=="MS_S2")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 'avatarAsIcon'=>0, 	'avatarSizeH'=>'','avatarSizeW'=>'', 'salesRadField'=>0,	'pline'=>0, 'catAuto'=>0, 'field_spec_lat'=>0, 'field_spec_lng'=>0, 'categoryAsIcon'=>0);
		if ($this->typeList=="MS_MT")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 'avatarAsIcon'=>0, 	'avatarSizeH'=>'','avatarSizeW'=>'', 'salesRadField'=>0,	'pline'=>0, 'catAuto'=>0, 'categoryAsIcon'=>0);
		if ($this->typeList=="MS_JSEV")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 'avatarAsIcon'=>0, 	'avatarSizeH'=>'','avatarSizeW'=>'',						'pline'=>0);
		if ($this->typeList=="MS_SP")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 'avatarAsIcon'=>0, 	'avatarSizeH'=>'','avatarSizeW'=>'', 'salesRadField'=>0,	'pline'=>0, 'catAuto'=>0, 'field_spec_lat'=>0, 'field_spec_lng'=>0, 'section'=>0, 'avatarImage'=>0, 'filter_opt'=>'', 'categoryAsIcon'=>0);
		if ($this->typeList=="MS_AM")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 'avatarAsIcon'=>0, 	'avatarSizeH'=>'','avatarSizeW'=>'', 'salesRadField'=>0,	'pline'=>0, 'catAuto'=>0);
		if ($this->typeList=="MS_JEV")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 			 			'avatarSizeH'=>'','avatarSizeW'=>'', 						'pline'=>0, 					'dateFormat'=>"d-m-Y", 'allEvents'=>0);
		if ($this->typeList=="MS_JC")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 			 			'avatarSizeH'=>'','avatarSizeW'=>'', 						'pline'=>0, 'catAuto'=>0);
		if ($this->typeList=="MS_GT")	$listVar = array('sidebar_template'=>'', 'markerIconType'=>99, 'j_menu_id'=>0, 'bubblewidth'=>'', 'mapicon'=>'', 						'avatarSizeH'=>'','avatarSizeW'=>'', 						'pline'=>0,			 		'dateFormat'=>"%d-%m-%Y");


		GeofactoryHelperAdm::loadMultiParamFor($listVar, 2, $id, $this);
	}
}
