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

class ModGeofactoryMapHelper{
	public static function checkTasks($params){
		//----- partie spécifique a SP, permet d'enregistrer la session dans le cas ou on est dans une recherche
		// on est pas dans SP result, on ressort
		$task = JRequest::getString('task');
		$session = JFactory::getSession();
		if (strcasecmp($task,"search.results")==0){
			//132411480096.95.90
			$ssid = JRequest::getVar('SPro_ssid','','COOKIE');
			if (strlen($ssid)>0){
				$session->set('gf_sp_ssid',$ssid);
			}
		} else {
			$session->clear('gf_sp_ssid') ;
		}
		//----- fin partie spécifique a SP

		$ignoreTasks	= explode(',', strtolower($params->get( 'taskToIgnore' )));
		$forceTasks		= explode(',', strtolower($params->get( 'taskToForce' )));
		if ((!empty($forceTasks)) AND (count($forceTasks)>0)){
			foreach($forceTasks as $pair){
				$vPair = explode('=',trim($pair));
				if (count($vPair)!=2)
					continue ;

				$task=JRequest::getVar(trim($vPair[0]));	

				if (($vPair[1]=="?") AND (strlen($task) > 0))
					return true;
				else if (($vPair[1]=="?") AND (strlen($task) < 1)) // force a ne pas afficher si la variable est vide et qu'elle doit etre remplie pour afficher
					return false ;

				if (strlen($task)>0 AND strtolower($task) == strtolower(trim($vPair[1])))
					return true ;
			}
		}

		// si force zoom, il a deja returné true !
		if ((!empty($ignoreTasks)) AND (count($ignoreTasks)>0)){
			foreach($ignoreTasks as $pair){
				$vPair = explode('=',trim($pair));
				if (count($vPair)!=2)
					continue ;

				// cas spécial de Sobipro
				if (($vPair[0]=='task') AND ($vPair[1]=='entry.details')){
					$pid = JRequest::getInt('pid');
					$sid = JRequest::getInt('sid');
					if ($pid>0 AND $sid>0)
						return false ;
				}

				$task=JRequest::getVar(trim($vPair[0]));
				if (($vPair[1]=="?") AND (strlen($task) > 0))
					return false;

				if (strlen($task)>0 AND strtolower($task) == strtolower(trim($vPair[1])))
					return false ;
			}
		}

		// defaut ...
		return true ;
	}

	public static function addScript($params){
		$tab_id = $params->get('usetab_id') ;
		if (strlen($tab_id) < 1)
			return ;
		$js = "
			jQuery( function() {
				jQuery( '#stabs' ).bind( 'tabsshow', function( event, ui ) {
					if ( ui.panel.id == '{$tab_id}' ) {
						google.maps.event.trigger( {$mapVar}.map, 'resize' );
						if(centerPointGFmap){
							{$mapVar}.map.setCenter( centerPointGFmap );
						}
					}
				} );
			} );";

		//a tester$js = str_replace(array('  ', '\n', '\t'), ' ', $js) ;

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
	}
}
