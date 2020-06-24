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
require_once JPATH_ADMINISTRATOR.'/components/com_geofactory/helpers/geofactoryUpdater.php';
require_once JPATH_ADMINISTRATOR.'/components/com_geofactory/tables/assign.php';
class GeofactoryHelperAdm {
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu($vName = 'geofactory'){
		JSubMenuHelper::addEntry(
			JText::_('COM_GEOFACTORY_MENU_CPANEL'),
			'index.php?option=com_geofactory&view=accueil',
			$vName == 'accueil'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_GEOFACTORY_MENU_MAPS_MANAGER'),
			'index.php?option=com_geofactory&view=ggmaps',
			$vName == 'ggmaps'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_GEOFACTORY_MENU_MARKERSETS_MANAGER'),
			'index.php?option=com_geofactory&view=markersets',
			$vName == 'markersets'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_GEOFACTORY_MENU_GEOCODING'),
			'index.php?option=com_geofactory&view=geocodes',
			$vName == 'geocodes'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_GEOFACTORY_MENU_ASSIGN_PATTERN'),
			'index.php?option=com_geofactory&view=assigns',
			$vName == 'assigns'
		);
	}

	public static function getLinksEditShortCuts($items, $task){
		$res = array() ;
		if (is_array($items) AND count($items)>0){
			foreach($items as $item){
				$res[] = '<a style="font-size:0.75em!important;" class="label btn-mini" href="index.php?option=com_geofactory&task='.$task.'.edit&id='.$item->value.'">'.$item->text.'</a>';
			} 
		}
		return '<br /> '.implode(' ',$res); 
	}
	
	// Gets a list of the actions that can be performed.
	public static function getActions(){
		$user		= JFactory::getUser();
		$result		= new JObject;
		$assetName 	= 'com_geofactory';
		$level 		= 'component';
		$actions 	= JAccess::getActions('com_geofactory', $level);

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}

	public static function loadJsCode($js){
		$config = JComponentHelper::getParams('com_geofactory');

		// la fonction accepte du code JS sous for d'array de lignes 
		if (!is_array($js))
			$js = array($js) ;
		
		// si pas debug, on compile le code
		if ((int) $config->get('isDebug', 0)==1){
			$js = implode($js, "\n") ; 
		} else {
			$js = str_replace("  ",'', implode($js)) ;
		}
		
 		$document = JFactory::getDocument();
		$document->addCustomTag('<script type="text/javascript">'.$js.'</script>');		
	}

	public static function getArrayObjTypeListe($unique=false){
		JPluginHelper::importPlugin( 'geocodefactory');
		$dispatcher = JDispatcher::getInstance();
		$vvNames = $dispatcher->trigger( 'getPlgInfo', array());
		
		$options = array() ;
		// pour chaque extension 
		$added = array() ;
		
		foreach($vvNames as $vNames){
			// il peut y avoir plusieur section différents
			foreach($vNames as $id=>$name){
				if (count($name)!=2)
					continue ;

				$text = $name[1] ;
				$text = explode(' - ',$text);
				if (count($text)>0)
					$text = $text[0];

				// on test ?
				if ($unique){
					if (in_array($text, $added))
						continue ;
				} else{
					$text = $name[1] ;
				}

				$tmp		= new stdClass() ;
				$tmp->value	= $name[0] ;
				$tmp->text	= $text ;
				$options[]	= $tmp ; 
				$added[]	= $text ;
				unset($tmp) ;
			}
		}

		return $options ;
	}

	// ajoute les élément du formulaire a MASQUER dans le mode basic, pour la carte
	public static function getExpertMap(){
		return array(	'allowDbl', 'totalmarkers', 'minZoom','maxZoom', 'pegman', 'scaleControl', 'rotateControl', 
						'overviewMapControl', 'useRoutePlaner', 'useTabs', 'cacheTime', 'mapStyle', 'mapTypeAvailable', 
						'maptypeavailable','mapTypeOnStart','tiles', 'kml_file', 'radFormMode', 'radFormSnipet', 
						'acTypes', 'useBrowserRadLoad','gridSize','imagePath','imageSizes','minimumClusterSize');
	}

	// ajoute les élément du formulaire a MASQUER dans le mode basic, pour le markerset
	public static function getExpertMarkerset(){
		return array('j_menu_id', 'accuracy', 'bubblewidth');	
	}

	public static function getArrayMapsFromMs($id){
		// pas encore de ms ?
		if($id<1)
			return ;

		$maps 	= array();
		$db		= JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select('id_map');
		$query->from('#__geofactory_link_map_ms');
		$query->where('id_ms='.$id);

		$db->setQuery($query);
		$res = $db->loadObjectList();

		if (!is_array($res) OR !count($res))
			return ;

		foreach ($res as $v) {
			if ($v->id_map<1)
				continue ;

			$maps[] = $v->id_map ;
		}

		return $maps;
	}

	// retourne la liste des parttern, avec ou sans restriction de type
	public static function getArrayObjAssign($curType=null){
		$options = array();
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id AS value, name AS text');
		$query->from('#__geofactory_assignation AS a');
		if ($curType)
			$query->where('a.typeList='.$db->Quote($curType));
		$query->order('a.name');

		// Get the options.
		$db->setQuery($query);

		try{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e){
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
	
	// retourne un vecteur de cartes
	public static function getArrayListMaps(){
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id AS value, name AS text');
		$query->from('#__geofactory_ggmaps AS a');
		$query->order('a.name');
		$query->where('state=1');

		// Get the options.
		$db->setQuery($query);

		try{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e){
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	public static function getMapsOptions($type=0){
		$options = GeofactoryHelperAdm::getArrayListMaps() ;

		if ($type==1)	array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_GEOFACTORY_NO_MAPS')));

		return $options;
	}

	// retourne un vecteur de markersets
	public static function getArrayListMarkersets(){
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id AS value, name AS text');
		$query->from('#__geofactory_markersets AS a');
		$query->order('a.name');

		// Get the options.
		$db->setQuery($query);

		try{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e){
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	public static function getMarkersetsOptions($type=0){
		$options = GeofactoryHelperAdm::getArrayListMarkersets() ;

		if ($type==1)	array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_GEOFACTORY_NO_MS')));

		return $options;
	}

	// utilisé exclusivement pour les import, rassurez vous !
	public static function loadMultiParamFor($listVal, $typ, $id, &$obj){
		$opm = null ;
		$where = " 1 " ;
		if 		($typ==1)	$where = "id_map='{$id}' ";
		elseif 	($typ==2)	$where = "id_markerset='{$id}' ";

		$db = JFactory::getDBO();
		$config = JComponentHelper::getParams('com_geofactory');
		$extDb =  $config->get('import-database');
		if (strlen($extDb)>0){ 
			$db = GeofactoryHelperAdm::loadExternalDb();
		}

		$query = $db->getQuery(true);
		$query->select('keynom,valeur');
		$query->from('#__geocode_factory_parametres');
		$query->where($where);


		$db->setQuery($query);
		$opm = $db->loadObjectList();

		// je m'assure que les variables de base aient au moins la valeur par defaut
		if (!is_array( $opm )) {
			$opm = array(0);		
		}

		// j'initialise l'objet param, avec les vals par def, et ensuite je mettreai les valeurs de la db
		if (!$obj)	
			$obj = new stdClass() ;
			
		foreach($listVal as $k=>$v){
			$obj->$k = $v ;
		}

		// pour chaque valeur lue je la met
		foreach($opm as $dbVal){
			if (!isset($dbVal->keynom))
				continue ;
			
			$varCur = $dbVal->keynom ;

			// si il existe dans la db je mets la bonne valeur
			if ((isset($dbVal->valeur)) AND (isset($obj->$varCur))){
				$obj->$varCur = $dbVal->valeur ;
			}
		}

		// met a jour pour pas que le user qui a updaté ne doive tout refaire
		if (isset($obj->markerIconType)/*&&($obj->markerIconType==99)*/){
			// si pas update je met a 0
			$obj->markerIconType = 0 ;

			// sinon check ce qu'il avait
			if (strlen($obj->marker)>3){	
				$obj->markerIconType = 1 ;
			} else if ((isset($obj->avatarAsIcon)) AND ($obj->avatarAsIcon == 1)){
				$obj->markerIconType = 3 ;
			}else if (isset($obj->catAuto) && $obj->catAuto == 1){
				$obj->markerIconType = 4 ;
			}			
		}
	}

	// supprime le cache
	public static function delCacheFiles($idMap) {
		foreach (glob(JPATH_CACHE.DIRECTORY_SEPARATOR."_geocodeFactory_{$idMap}*.xml") as $filename) {
		   JFile::delete($filename);
		}
	}

	// retourne l'array pour le geocode
	public static function getAssignArray($id){
		$t = JTable::getInstance('Assign', 'GeofactoryTable');
		$t->load($id) ;

		$vRet = array() ;
		$fields = array("field_latitude","field_longitude","field_street","field_postal","field_city","field_county","field_state","field_country") ;
		// pour chaque champs, si il est utilisé on le mets dans le vecteur.
		foreach ($fields as $f){ 
			if ((isset($t->$f)) AND ($t->$f != '0'))	
				$vRet[$f] = $t->$f ;
		} 

		return $vRet ;
	}

	// Codemirror should be enabled
	public static function isCodeMirrorEnabled(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__extensions as a');
		$query->where('(a.name ='.$db->quote('plg_editors_codemirror').' AND a.enabled = 1) ');
		$db->setQuery($query);
		$state = $db->loadResult();
		if ((int) $state < 1 )
			return false ;

		return true ;
	}

	// Editor None should be enabled
	public static function isEditorNoneEnabled(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__extensions as a');
		$query->where('(a.name ='.$db->quote('plg_editors_none').' AND a.enabled = 1)');
		$db->setQuery($query);
		$state = $db->loadResult();
		if ((int) $state < 1 )
			return false ;

		return true ;
	}

	public static function loadExternalDb(){
			$config = JComponentHelper::getParams('com_geofactory');
			$option = array();
			$option['driver']   = $config->get('import-driver');
			$option['host']     = $config->get('import-host');
			$option['user']     = $config->get('import-user');
			$option['password'] = $config->get('import-password');
			$option['database'] = $config->get('import-database');
			$option['prefix']   = $config->get('import-prefix');

			$db = JDatabase::getInstance( $option );
			return $db ;
	}

}
