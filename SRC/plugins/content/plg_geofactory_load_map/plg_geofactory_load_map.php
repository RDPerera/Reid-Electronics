<?php
/**
 * @name		Geocode Factory - Content plugin
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author		Cédric Pelloquin <info@myJoom.com>
 * @website		www.myJoom.com
 * 
 */

// ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
jimport('joomla.plugin.plugin');
require_once JPATH_ROOT	.'/components/com_geofactory/helpers/geofactory.php';
require_once JPATH_SITE.'/components/com_geofactory/helpers/externalMap.php';
require_once JPATH_SITE.'/components/com_geofactory/views/map/view.html.php' ;

// uncomment this for debugging
//ini_set("display_errors","on");	error_reporting(E_ALL);

class PlgContentPlg_geofactory_load_map extends JPlugin{
	var $m_plgCode 	= 'load_gf_map' ;

	function plg_geofactory_load_map( &$subject, $params ){
		parent::__construct( $subject, $params );
	}

	// dessine la carte ou au moins supprime le code {}	
	public function onContentPrepare($context, &$article, &$params, $limitstart=0){
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}

		if(!is_object($article) OR !isset($article->id)) 
			return false ;

		//http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=26421
		//$article->text = $article->introtext;
		$article->text	= $this->_prepareArticle($article->text, $article->id) ;
	}

	private function _prepareArticle($text, $id) {
		// simple performance check
		if ( JString::strpos($text, $this->m_plgCode ) === false ) 
			return $text ;

		$regex = '/{load_gf_map\s+(.*?)}/i';
 
		// plugin pas actif, je supprime mon code de l'article
		if ( !$this->params->get( 'enabled', 1 ) ) {
			return preg_replace( $regex, '', $text);
		}
 
	 	// find all instances of plugin and put in $matches
		preg_match_all( $regex, $text, $matches );
	 	$count = count( $matches[0] );		
		if ( $count ) {
			return $this->_replaceMap($text, $count, $regex, $matches);
		}

		return $text ;
	}

	// je ne remplace que la premiere map, les autres on les remplace par un vide
	function _replaceMap($text, $count, $regex, $matches){
//		$done = false ;
		for ($i=0; $i < $count; $i++ ){
			// recherche la carte en cours
			$idMap = 0 ;
			$idMap = str_replace( $this->m_plgCode, '', $matches[0][$i] );
	 		$idMap = str_replace( '{', '', $idMap );
	 		$idMap = str_replace( '}', '', $idMap );
 			$idMap = trim( $idMap );

			// une seule map par article....
//			if ($done OR $idMap<1){
//				$text = preg_replace( $regex, '', $text );
//				continue ;
//			}

//			$done 		= true ; 
			$map 		= GeofactoryExternalMapHelper::getMap($idMap, 'lm');//eventuellement???, $lat, $lng);
			$res 		= $map->formatedTemplate;
			$text = preg_replace($regex, $res, $text, 1);
		}
		
		return $text ;
	}
}
