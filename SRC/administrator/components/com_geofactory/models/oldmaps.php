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

class GeofactoryModelOldmaps extends JModelList{
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function getListQuery(){
		$app 	= JFactory::getApplication();
		$prefix = $app->getCfg('dbprefix');

		$db 	= $this->getDbo();

		// essaie de connecter une autre database
		$config = JComponentHelper::getParams('com_geofactory');
		$extDb =  $config->get('import-database');
		if (strlen($extDb)>0){ 
			$prefix = $config->get('import-prefix');
			$db = GeofactoryHelperAdm::loadExternalDb();
			parent::setDbo($db);
		}

		$tables = $db->getTableList();

		if (! in_array($prefix."geocode_factory_maps", $tables)){
			$app->enqueueMessage(JText::_('COM_GEOFACTORY_IMP_NO_OLD_TABLES'), 'error');
		} 
		
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id AS id,'.
				'a.title AS title'
				)
		);

		$query->from($db->quoteName('#__geocode_factory_maps').' AS a');

		// Join over the markersets for counting
		$query->select('COUNT(ms.id) as nbrMs');
		$query->join('LEFT', '#__geocode_factory_markersets AS ms ON a.id = ms.id_map');

		$query->group('a.id, a.title');

	//	echo nl2br(str_replace('#__','pec30_',$query));
		return $query;
	}
	
	public function import(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->get('cid', array(), 'array');
		JArrayHelper::toInteger($ids);
		if (!is_array($ids))
			$ids = array($ids);
			
		foreach($ids as $id){
			$this->_importMap($id) ;
		}
	}

	protected function _importMap($idMap){
		$config = JComponentHelper::getParams('com_geofactory');
		$prefix = trim($config->get('prefix_old', ''));

		// charge l'ancienne carte
		$old = JTable::getInstance('Oldmap', 'GeofactoryTable');
		$old->load($idMap) ;
		if ($old->id<1){
			$this->setError(JText::sprintf('COM_GEOFACTORY_IMP_ERR_LOAD_MAP', $idMap)) ;
			return ;
		}
		
		// crée une nouvele carte
		$new = JTable::getInstance('Ggmap', 'GeofactoryTable');
		$new->importFromOldGF($old) ;
		$new->name = strlen($prefix) > 0 ? $prefix.'_'.$new->name : $new->name ;

		$new->check();
		if (!$new->store()){
			$new->setError($table->getError());
		}
		$newMapId = $new->id;
		
		// récupère la liste des markersets attachés à l'ancienne map
		$idsMs = $this->_getOldIdsMs($idMap) ;
		JArrayHelper::toInteger($idsMs);
		if (!is_array($idsMs))
			$idsMs = array($idsMs);

		if (count($idsMs)<1)
			return ;

		foreach($idsMs as $idMs){
			if ($idMs<1)
				continue ;
			$this->_importMs($idMs, $idMap, $prefix, $newMapId);
		}
	}
	
	// recherche les anciens markersets de cette carte
	protected function _getOldIdsMs($idM){
		$db = $this->getDbo();

		$config = JComponentHelper::getParams('com_geofactory');
		$extDb =  $config->get('import-database');
		if (strlen($extDb)>0){ 
			$db = GeofactoryHelperAdm::loadExternalDb();
			parent::setDbo($db);
		}

		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__geocode_factory_markersets') );
		$query->where('id_map = '.(int) $idM );
		$query->order($db->escape('ordering').' '.$db->escape( 'ASC'));
		$db->setQuery($query); 

		$res =  $db->loadObjectList() ;
		$olds = array() ;
		foreach($res as $r)
			$olds[] = $r->id ;

		return $olds ;
	}
	
	protected function _importMs($idMs, $idMap, $prefix, $newMapId){
		// charge l'ancien MS
		$old = JTable::getInstance('OldMarkerset', 'GeofactoryTable');
		$old->load($idMs) ;
		$old->idmaps = GeofactoryHelperAdm::getArrayMapsFromMs($idMs) ;

		if ($old->id<1){
			$this->setError(JText::sprintf('COM_GEOFACTORY_IMP_ERR_LOAD_MS', $idMs)) ;
			return ;
		}
		
		// crée un nouveau markerset
		$new = JTable::getInstance('Markerset', 'GeofactoryTable');
		$new->importFromOldMS($old, $idMap, $newMapId) ;
		$new->name = strlen($prefix) > 0 ? $prefix.'_'.$new->name : $new->name ;
		$new->check();
		if (!$new->store()){
			$new->setError($new->getError());
		}

		$new->bindMapMarkerset($newMapId);		
	}
}
