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
require_once JPATH_SITE.'/components/com_geofactory/helpers/geofactoryPlugin.php';

class GeofactoryHelperUpdater {
	// retourne tout ce qui est installé pour GF avec le max d'infos à ce sujuet
	public static function getUpdatesList(){
		// recherche toutes mes extensions
		$vExts = GeofactoryHelperUpdater::getInstalledGfExt();

		$isPackage = GeofactoryHelperUpdater::ifInstalledGfPakage($vExts);

		// recherche les mises à jours dispo
		$vUpd = GeofactoryHelperUpdater::getUpdatesExt() ;

		// recherche toutes les apps possibles
		$vAll = GeofactoryHelperUpdater::getComponentCatalog($isPackage);

		// récupère la subscription
		$subs = GeofactoryHelperUpdater::getUserSubs();

		// pour chaque extension possible, je traite au cas par cas
		$vLinks = array() ;
		foreach($vAll as $ext=>$name){
			$dummy = new stdClass() ;
			$dummy->file 	= $ext ;
			$dummy->name 	= $name ;
			$dummy->tag 	= '' ;
			$dummy->link 	= '' ;
			$dummy->alert 	= '' ;

			// pas installée ?
			if (!GeofactoryHelperUpdater::isInstalled($vExts, $dummy))
				continue ;

			GeofactoryHelperUpdater::addExtUpdate($dummy, $vUpd, $subs);
			$vLinks[] = GeofactoryHelperUpdater::buildLink($dummy);
		}

		return $vLinks ;
	}

	protected static function buildLink($dummy){
		$link  	= $dummy->name ;
		if (strlen($dummy->tag)>0 && strlen($dummy->link)>0){
			$button	= strlen($dummy->tag)>0?'<span class="label label-info">'.$dummy->tag.'</span>':'' ;
			$option	= strlen($dummy->alert)>0?'onclick="alert(\''.$dummy->alert.'\');return false;" ':'class="modal"';
			$link 	= $link.'<a style="float:right;" href="'.$dummy->link.'" '.$option.'>'.$button.'</a>' ;
		}

		return $link ;
	}

	protected static function getUpdatesExt(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('element')
			->from('#__updates')
			->where('extension_id != 0');
		$db->setQuery($query);
		$updates = $db->loadObjectList();
		if (!is_array($updates) or !count($updates))
			return array('');		

		// pour chaque valeur lue je la met
		$res = array ();
		foreach($updates as $upd)
			$res[] = strtolower($upd->element) ;

		return $res ;
	}

	// retourne la liste des extensions GF existantes, pas forcément installées, et
	// dans l'ordre d'apparition dans la liste
	protected static function getComponentCatalog($isPackage){
		return array(	
			'com_geofactory'		=>'Geocode Factory core',
			'mod_geofactory_map'	=>'Module - show map',
			'mod_geofactory_search'	=>'Module - search map',

			'plg_geofactory_load_map'=>'Plugin - Load map in articles',
			'plg_geofactory_content_jc30'=>'Plugin - Joomla Content ',
			'plg_geofactory_btn_jc30'=>'Plugin - Joomla Content Button',
			'plg_geofactory_gw_jc30'=>'Gateway - Joomla Contents',

            'plg_geofactory_gw_adsm'=>'Gateway - Ads Manager',
			'plg_geofactory_gw_cb19'=>'Gateway - Community Builder',
			'plg_geofactory_gw_f2c'=>'Gateway - Form2content',
			'plg_geofactory_gw_js30'=>'Gateway - Jomsocial',
			'plg_geofactory_gw_je30'=>'Gateway - Jomsocial Events',
			'plg_geofactory_gw_mt30'=>'Gateway - Mosets Tree',
			'plg_geofactory_gw_k2'=>'Gateway - K2',
			'plg_geofactory_gw_sbl'=>'Gateway - Seblod',
			'plg_geofactory_gw_sp10'=>'Gateway - Sobipro',
			'plg_geofactory_levels'=>'Plugin - Levels',

			'plg_geofactory_profile_js30'=>'Profile plugin - Jomsocial',
			'plg_geofactory_profile_cb19'=>'Profile plugin - Community Builder',
		);


		//le package lui meme ne peut pas être utilisé facilement en version check, donc pour moi c'est plus simple de tout mettre a jour si il fait le core (en plus il install les nouveaux plugins/modules)
/*
		$ar = array();
		if ($isPackage){
			$ar = array('pkg_geofactory'		=>'Geocode Factory - package');
		}else{
			$ar = array('com_geofactory'		=>'Geocode Factory - core',
						'mod_geofactory_map'	=>'Module - show map',
						'mod_geofactory_search'	=>'Module - search map',

						'plg_geofactory_load_map'=>'Plugin - Load map in articles',
						'plg_geofactory_content_jc30'=>'Plugin - Joomla Content ',
						'plg_geofactory_btn_jc30'=>'Plugin - Joomla Content Button',
						'plg_geofactory_gw_jc30'=>'Gateway - Joomla Contents');
		}

		$arCom = array(	'plg_geofactory_gw_cb19'=>'Gateway - Community Builder',
						'plg_geofactory_gw_f2c'=>'Gateway - Form2content',
						'plg_geofactory_gw_js30'=>'Gateway - Jomsocial',
						'plg_geofactory_gw_je30'=>'Gateway - Jomsocial Events',
						'plg_geofactory_gw_mt30'=>'Gateway - Mosets Tree',
						'plg_geofactory_gw_sp10'=>'Gateway - Sobipro',
						'plg_geofactory_levels'=>'Plugin - Levels',

						'plg_geofactory_profile_js30'=>'Profile plugin - Jomsocial',
						'plg_geofactory_profile_cb19'=>'Profile plugin - Community Builder'
					);

		return $ar + $arCom;*/
	}

	// si un fichier a une mise a jour dispo, alors je lui met les infos de mise a jour
	protected static function addExtUpdate(&$dummy, $vUpd, $subs){
		// est-ce que ce produit a une mise à jour disponible ?
		if (!in_array($dummy->file, $vUpd))
			return ; 

		$dummy->tag 	= 'Update' ;

		switch ($dummy->file) {
			case 'com_geofactory':
				$dummy->link 	= 'index.php?option=com_geofactory&view=accueil&task=accueil.update&file=geofactory.zip&free=1' ;
				break;

			case 'mod_geofactory_map':
				$dummy->link 	= 'index.php?option=com_geofactory&view=accueil&task=accueil.update&file=mod_geofactory_map.zip&free=1' ;
				break;

			case 'plg_geofactory_content_jc30':
				$dummy->link 	= 'index.php?option=com_geofactory&view=accueil&task=accueil.update&file=plg_geofactory_content_jc30.zip&free=1' ;
				break;

			case 'plg_geofactory_gw_jc30':
				$dummy->link 	= 'index.php?option=com_geofactory&view=accueil&task=accueil.update&file=plg_geofactory_gw_jc30.zip&free=1' ;
				break;

			case 'plg_geofactory_btn_jc30':
				$dummy->link 	= 'index.php?option=com_geofactory&view=accueil&task=accueil.update&file=plg_geofactory_btn_jc30.zip&free=1' ;
				break;

			case 'plg_geofactory_profile_cb19':
				$dummy->tag 	= 'Manual' ;
				$dummy->alert 	= 'CB do not allow automatic plugin update.' ;
				break ;

			case 'plg_geofactory_gw_js30':
			case 'plg_geofactory_gw_cb19':
			case 'plg_geofactory_gw_mt30':
			case 'plg_geofactory_gw_adsm':
			case 'plg_geofactory_gw_k2':
			case 'plg_geofactory_gw_f2c':
			case 'plg_geofactory_gw_sp10':
			case 'plg_geofactory_gw_sbl':
			case 'plg_geofactory_profile_js30':
			case 'plg_geofactory_levels':

				if ($subs=='ok'){
					$file = $dummy->file;
					if ($file=='plg_geofactory_gw_cb19')$file='plg_geofactory_gw_cb';

					$dummy->link = 'index.php?option=com_geofactory&view=accueil&task=accueil.update&file='.$file.'.zip' ;
				} else {
					$dummy->alert 	= $subs ;
					$dummy->link 	= '#';
				}
				break;
		}
	}

	// récupère les infos de l'extension
	protected static function isInstalled($extDb, &$dummy){
		foreach ($extDb as $row){
			if (strcasecmp($row->element, $dummy->file)==0){
				// j'en profite pour ajouter le settings gear
				if (substr($dummy->file, 0, 4)=='plg_'){
					// lien sur les settings
					$settings = '<a href="index.php?option=com_plugins&task=plugin.edit&extension_id='.$row->extension_id.'" target="_blank"><i class="icon-cog"></i></a> ' ;
					$dummy->name= $settings .$dummy->name ;
				}

				return true ;
			}
		}

		return false ;
	}

	// est-ce que GF est installé sous forme de package?
	protected static function ifInstalledGfPakage($extDb){
		foreach ($extDb as $row){
			if (strcasecmp($row->element, 'pkg_geofactory')==0)
				return true ;
		}

		return false ;
	}

	// retourne tout ce qui est installé pour GF avec le max d'infos à ce sujuet
	protected static function getInstalledGfExt(){
		// recherche toutes mes extensions
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__extensions');
		$query->where('name LIKE '.$db->Quote('%geo%'));// limite le nombre 
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	// check la validité de la soubscription du user, on s'en tape si il en a une ou si il a 
	// des exts commerciales, ca on le gere plus tard...
	protected static function getUserSubs(){
		$config 	= JComponentHelper::getParams('com_geofactory');
		$subsEnd = $config->get('subsEnd');//$subsEnd = "13.04.2014 16:59:29"; L=19  -> devient la licence avec FAS
		$subsEnd = trim($subsEnd);
		
		// soit pas saisi, soi mal saisi
		if (strlen($subsEnd)<9) 
			return JText::_('COM_GEOFACTORY_ENTER_YOUR_SUBSCRIPTION_IN_SETTINGS') ;
/*
		// transforme en 2014-04-13 16:59:29
		$date = substr($subsEnd,6,4).'-'.substr($subsEnd,3,2).'-'.substr($subsEnd,0,2).substr($subsEnd,10,9) ;
		if (strtotime($date) < time())
			return JText::_('COM_GEOFACTORY_EXPIRED_SUBSCRIPTION') ;
*/
		// pour l'affichage la subscription est valide, maintenant je teste pas ici si elle est
		// réelement juste, ca c'est au niveau de l'update. Mais si il a pas essayer de tricher
		// il peut compter desus.
		return 'ok' ;
	}

}
