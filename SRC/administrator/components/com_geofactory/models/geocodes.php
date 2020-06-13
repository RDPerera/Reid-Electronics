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
class GeofactoryModelGeocodes extends JModelList{
	/**
	 * @since	1.6
	 */
	protected $basename;
	protected $geocodeQuery="" ;

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()){
		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery(){
		// récupère le type de liste, de pattern choisis et crée la query si nécessaire
		if (strlen($this->geocodeQuery)<3){
			$this->geocodeQuery = $this->getGeocodeQuery($this->getState('filter.typeliste')); 
		}

		return $this->geocodeQuery ;
	}

	// recherche la liste des entrées à geocoder sans LIMIT 0,10
	public function	getListIdsToGeocode() {
		if (strlen($this->geocodeQuery)<3){
			$app 		= JFactory::getApplication('administrator');
			$type 	= $app->input->get('typeliste', -1) ; 
			$this->geocodeQuery = $this->getGeocodeQuery($type); 
		}

		$db	= JFactory::getDbo();
		$db->setQuery($this->geocodeQuery); 
		$res =  $db->loadObjectList() ;
		if (!is_array($res) || count($res)<1)
			return ;

		$vRes = array() ;
		foreach($res as $r){
			$vRes[] = $r->item_id;
		}

		return $vRes ;		
	}

	protected function getGeocodeQuery($typeListe){
		$query = "SELECT '0' as id" ;
		if (!$typeListe)
			return $query ;

		$app 		= JFactory::getApplication('administrator');
		$assign 	= $app->input->get('assign', -1) ; 
		if ($assign<1)
			return $query ;			

		$vAssign	= GeofactoryHelperAdm::getAssignArray($assign) ;
		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();

		$filters = array(	$app->input->get('filter_search'), 
							$app->input->get('list_direction'),
							$app->input->get('filter_geocoded')) ;
		$queries = $dispatcher->trigger( 'getListQueryBackGeocode', array($typeListe, $filters, $this, $vAssign) );

		$ok = false ;
		foreach($queries as $q){
			if (count($q)!=2)								continue ;
			if (strtolower($q[0])!=strtolower($typeListe))	continue ;
			if (!$q[1])										continue ;

			$ok = true ;
			$query=$q[1] ;
			break ;
		}
 
		if (!$ok)
			return $query ;

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query ;
	}

	public function getAdress($id, $type, $vAssign){
		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger( 'getItemPostalAddress', array($type,$id,$vAssign) );

		foreach($results as $r){
			// recherche le result du bon plugin
			if (count($r)!=2)							continue ;
			if (strtolower($r[0])!=strtolower($type))	continue ;

			return $r[1] ;
		}

		return array("Error");
	}

	public function getGoogleGeocodeQuery($vAdd){
		if (!is_array($vAdd))			$vAdd = array($vAdd);
		if (!count($vAdd))				return '' ;

		$config 	= JComponentHelper::getParams('com_geofactory');
		$region 	= trim($config->get('ggRegion',''));
		$ggSeparator= trim($config->get('ggSeparator'), '+');
		$ggSeparator= (strlen($ggSeparator)<1)?'+':$ggSeparator;

		// Api key...
		$ggApikey	= trim($config->get('ggApikey'));
   		$ggApikey 	= (strlen($ggApikey)>4)?"&key=".$ggApikey:'';
		$ggApikeyGc	= trim($config->get('ggApikeyGc'));
   		$ggApikey 	= (strlen($ggApikeyGc)>4)?"&key=".$ggApikeyGc:$ggApikey;

		$http 	= $config->get('sslSite');
		if(empty($http)) 		$http = (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS']))?"https://":"http://" ; 
		if (strlen($region)==2)	$region = "&region={$region}" ;

		$adresse 	= implode($ggSeparator, $vAdd);
//		$adresse 	= str_replace(array(' ','Æ','æ','Ø','ø','Å','å','Ñ', 'ñ'), array('+','AE','ae','O','o','A','a','N','n'), $adresse);
//		$adresse 	= utf8_encode($adresse);
		//$server 	= "{$http}maps.googleapis.com/maps/api/geocode/xml?{$region}{$ggApikey}";
		$server 	= "https://maps.googleapis.com/maps/api/geocode/xml?{$region}{$ggApikey}";
		$urlRequest	= $server . "&address=" . urlencode($adresse) ;

		return $urlRequest; 
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null){
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		$curType = $app->input->get('typeliste', -1) ; 
		$this->setState('filter.typeliste', $curType);

		$assign = $app->input->get('assign', -1) ; 
		$this->setState('filter.assign', $assign);

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$geocoded = $this->getUserStateFromRequest($this->context.'.filter.geocoded', 'filter_geocoded');
		$this->setState('filter.geocoded', $geocoded);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_geofactory');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}

	public function saveCoord($id, $coor, $type, $vAssign){
		$id = (int) $id;
		if ($id<1)
			return JText::_('COM_GEOFACTORY_GEOCODE_SAVE_ERROR_BAD_ID') ;

		// invalid data in entry
		if (!is_array($coor) OR count($coor)<2)
			return JText::_('COM_GEOFACTORY_GEOCODE_SAVE_ERROR_BAD_COORD') ;

		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger( 'setItemCoordinates', array($type,$id,$coor, $vAssign) );

		foreach($results as $r){
			// recherche le result du bon plugin
			if (count($r)!=2)							continue ;
			if (strtolower($r[0])!=strtolower($type))	continue ;

			return $r[1] ;
		}

		return JText::_('COM_GEOFACTORY_GEOCODE_SAVE_ERROR') ;
	}

	public function htmlResult($cur, $max, $adr, $save, $progress=true) {
		$html = array() ;
		if ($progress){
			if($cur==-999){
				$cur = $max ;
				$save = JText::_('COM_GEOFACTORY_GEOCODE_DONE') ;
			}

			$cur	+=1 ;
			$pc 	= ($cur*100)/$max ;
			$step 	= JText::sprintf('COM_GEOFACTORY_CUR_MAX_X_PC_DONE',$pc , $cur, $max);

			$html[] = '<span style="font-weight:bold;">'.$step.' </span><div style="height:20px;width:100%;border:1px black solid;"><div style="height:20px;width:'.$pc.'%;background-color:#66CC66;"></div></div>';
		}

		if (is_array($adr))
			$html[] = JText::_('COM_GEOFACTORY_GEOCODE_NOW') . " : ".implode(",",$adr) ;

		$html[] = JText::_('COM_GEOFACTORY_GEOCODE_MESSAGE') ." : ".$save ;

		return implode("<br />",$html);
	}

	public function geocodeItem($urlRequest){
		$config 		= JComponentHelper::getParams('com_geofactory');
		$delay 			= 333333; // attend 1/3 de seconde qui sera incrémenté au fur a mesure si il arrive pas à geocoder.....
		$coor 			= array(255,255, JText::_('COM_GEOFACTORY_GEOCODE_ERR_UNKNOWN')) ;
		$geocodePending	= true ;


		// si pas debug, on compile le code
		if ((int) $config->get('isDebug', 0)==1)
			echo '<a href="'.$urlRequest.'">debug</a><br>';

		// Pas d'adresse ?
		if (strlen($urlRequest) < 3){
			$coor[2] = 'No address to geocode';
			JLog::add('NO_ADDRESS :: '.$coor[2]);

			return $coor ;
		}

		while($geocodePending){
			//..... jusqu'a 15 secondes maximum 
			if ($delay > 15000000){
				$coor[2] = 'Geocode time out, more than 15 secondes to geocode';
				JLog::add('TIME_OUT :: '.$coor[2]);

				return $coor ;
			}

			// je fait la pause au début pour éviter les messages de OVER_QUERY_LIMIT
			usleep($delay) ;

			$http = JHttpFactory::getHttp();
			$response = $http->get($urlRequest);
			if (200 != $response->code){
				$coor[2] = JText::_('COM_GEOFACTORY_GEOCODE_ERR_SERVER');
				JLog::add('BAD_SERVER_RESPONSE :: '.$coor[2]);

				return $coor ; 
			}

			// je ne peux pas passer direct le fichier, car certains serveurs crashent avec acces distant (URL file-access is disabled in the server configuration)
			$xml = simplexml_load_string($response->body);
			$status = $xml->status;

			if (strcmp($status, "OK")==0){
				$geocodePending = false ;

				JLog::add($status);
				return array((double) $xml->result->geometry->location->lat,(double) $xml->result->geometry->location->lng,"Successfull geocoded") ;
				// cas du reverse geocode ...
				/*
				if ($fetchaddress){
					foreach($xml->result[0]->address_component as $addcomp){
						if (!$addcomp)
							continue ;
					
						if ($addcomp->type[0] == "route")
							$this->geocoded_address["street"] = $addcomp->long_name ;
						if ($addcomp->type[0] == "street_number")
							$this->geocoded_address["street"] = $this->geocoded_address["street"] ." " . $addcomp->long_name ;
						if ($addcomp->type[0] == "locality")
							$this->geocoded_address["city"] = $addcomp->long_name ;
						if ($addcomp->type[0] == "administrative_area_level_2")
							$this->geocoded_address["county"] = $addcomp->long_name ;
						if ($addcomp->type[0] == "administrative_area_level_2")
							$this->geocoded_address["state"] = $addcomp->long_name ;
						if ($addcomp->type[0] == "country")
							$this->geocoded_address["country"] = $addcomp->long_name ;
						if ($addcomp->type[0] == "postal_code")
							$this->geocoded_address["postal"] = $addcomp->long_name ;
					}
				}*/
			}
			else if (strcmp($status, "ZERO_RESULTS")==0){
				$geocodePending = false ;
				$coor[2] = JText::_('COM_GEOFACTORY_GEOCODE_ERR_NO_RESULT');
				JLog::add($status.' :: '.$coor[2]);
				return $coor ;
			}
			else if (strcmp($status, "OVER_QUERY_LIMIT")==0){
				$geocodePending = false ;
				$coor[2] = JText::_('COM_GEOFACTORY_GEOCODE_ERR_OVER_LIMIT');
				JLog::add($status.' :: '.$coor[2]);
				return $coor ;
			}
			else if (strcmp($status, "REQUEST_DENIED")==0){
				$coor[2] = JText::_('COM_GEOFACTORY_GEOCODE_ERR_DENIED');
				JLog::add($status.' :: '.$coor[2]);
				return $coor ;
			}
			else if (strcmp($status, "INVALID_REQUEST")==0){
				$coor[2] = JText::_('COM_GEOFACTORY_GEOCODE_ERR_INVALID');
				JLog::add($status.' :: '.$coor[2]);
				return $coor ;
			}
			else {
				// trop rapide ajoute des microsecondes
				$delay += 100000 ;
			}
		}

		return $coor ;
	}

	// cherche l'id courantà geocoder
	public function getCurGeocodeId($query, $cur){
		$db	= JFactory::getDbo();
		$db->setQuery($query, $cur, 1);
		echo $query ; 
		$res =  $db->loadObjectList() ;

		if (count($res)<1)
			return -1 ;

		$r = $res[0] ;
		return $r->item_id ;
	}

	// supprime de la table GF
	public function deleteFromGFTable($type){
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($ids);
		if (!is_array($ids))
			$ids = array($ids);

		if (count($ids)<1)
			return;

		// supprime les coordonnées de la sélection ET en profite pour purger celle qui sont temporaires
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$db->setQuery('delete from #__geofactory_contents where type='.$db->quote($type).' AND (id IN ('.implode(',',$ids).') OR (id_content>100000000))');
		$db->execute();

	}
		
}

