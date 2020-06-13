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
require_once JPATH_COMPONENT.'/helpers/geofactory.php';
jimport('joomla.log.log');

class GeofactoryControllerGeocodes extends JControllerAdmin{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_GEOFACTORY';
	/**
	 * @var		string	The context for persistent state.
	 * @since	1.6
	 */
	//protected $context = 'com_geofactory.geocodes';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Geocodes', $prefix = 'GeofactoryModel', $config = array('ignore_request' => true)){
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	// dessine le contenu de la page de geocode
	public function geocodeselected(){
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($ids);
		if (!is_array($ids))
			$ids = array($ids);

		// rien du tout ?
		if (count($ids)<1){
			$this->setRedirect(JRoute::_('index.php?option=com_geofactory&view=geocodes', false), JText::_('COM_GEOFACTORY_GEOCODE_NO_SELECTION'));
			return;
		}

		$view = $this->getView("Geocodes", "batch") ;		
		$model = $this->getModel('Geocodes');

		// dessine le form en lui disant ce que je veux geocoder
		$view->idsToGc = $ids;
		$view->display('batch');
	}

	// geocode les éléments filtrés
	public function geocodefiltered(){
		$view = $this->getView("Geocodes", "batch") ;
		$model = $this->getModel('Geocodes');

		// le model crée automatiquement la query avec getListQuery, mais il y ajoute la limite de pagination, donc me retourne toujours
		// les éléments de LIMIT 20, 10. Donc afin de travailler avec MES données, je lance ma propre query, qui me retourne TOUS les éléments
		$ids = $model->getListIdsToGeocode() ;

		// dessine le form en lui disant que je veux tout geocoder
		$view->idsToGc = $ids;
		$view->display('batch');
	}

	public function geocodecurrentitem(){
		static 		$log;
		$app 		= JFactory::getApplication();

		$id 		= $app->input->getInt('curId', -1) ;
		$cur 		= $app->input->getInt('cur', -1) ;
		$max 		= $app->input->getInt('total', -1) ;
		$err 		= $app->input->getInt('errors', -1) ;
		$type 		= $app->input->get('type') ;
		$assign 	= $app->input->getInt('assign') ;

		$model 		= $this->getModel('Geocodes');
		$vAssign	= GeofactoryHelperAdm::getAssignArray($assign) ;

		$config = JComponentHelper::getParams('com_geofactory');
		$geocodeLog =  intval($config->get('geocodeLog'));
		if ($geocodeLog){
			if ($log == null){
				$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CLIENTIP}\t{MESSAGE}';
				$options['text_file'] = 'com_geofactory.geocode.php';
				$log = JLog::addLogger($options, JLog::INFO);
			}
		}

		//$query 		= $model->getGeocodeQuery($type) ;
		//$id  		= $model->getCurGeocodeId($query, $cur);

		JLog::add("____________________________________");
		JLog::add("New entry for -{$type}- : {$id}");

		$adr 		= $model->getAdress($id, $type, $vAssign);
		$ggUrl 		= $model->getGoogleGeocodeQuery($adr);

		JLog::add($ggUrl);

		$coor 		= $model->geocodeItem($ggUrl);
		$save 		= $model->saveCoord($id, $coor, $type, $vAssign);
		$msg 		= $model->htmlResult($cur, $max, $adr, $save) ;

		// si les coordonnées sont valides, on les ajoute en toute fin du message
		if (is_array($coor) AND count($coor)>1)
			$msg .= "#-@".implode('#-@', $coor);

		echo $msg ;

		$app->close();

		// au dernier il faudra fait un redirect sur la liste
	}

	public function getcurrentitemaddressraw(){
		$app 		= JFactory::getApplication();

		$id 		= $app->input->getInt('curId', -1) ;
		$cur 		= $app->input->getInt('cur', -1) ;
		$max 		= $app->input->getInt('total', -1) ;
		$err 		= $app->input->getInt('errors', -1) ;
		$type 		= $app->input->get('type') ;
		$assign 	= $app->input->getInt('assign') ;

		$model 		= $this->getModel('Geocodes');
		$vAssign	= GeofactoryHelperAdm::getAssignArray($assign) ;

		$adr 		= $model->getAdress($id, $type, $vAssign);
		$adr 		= trim(implode(' ', $adr));
		echo $adr ;

		$app->close();
	}

	public function axsavecoord(){
		$app 		= JFactory::getApplication();
		$coor 		= array() ;

		$id 		= $app->input->getInt('curId', -1) ;
		$cur 		= $app->input->getInt('cur', -1) ;
		$max 		= $app->input->getInt('total', -1) ;
		$err 		= $app->input->getInt('errors', -1) ;
		$type 		= $app->input->get('type') ;
		$assign 	= $app->input->getInt('assign') ;
		$coor[] 	= $app->input->getFloat('savlat') ;
		$coor[] 	= $app->input->getFloat('savlng') ;
		$coor[] 	= $app->input->get('savMsg') ;
		$adresse 	= $app->input->getString('adresse','-') ;
		$adresse 	= array($adresse);

		$model 		= $this->getModel('Geocodes');
		$vAssign	= GeofactoryHelperAdm::getAssignArray($assign) ;

		$save 		= $model->saveCoord($id, $coor, $type, $vAssign);
		$msg 		= $model->htmlResult($cur, $max, $adresse, $save) ;
		echo $msg ;

		$app->close();
	}

	public function geocodeuniqueitem(){
		$app 		= JFactory::getApplication();

		$id 		= $app->input->getInt('cur', -1) ;
		$type 		= $app->input->get('type') ;
		$assign 	= $app->input->getInt('assign') ;

		$model 		= $this->getModel('Geocodes');
		$vAssign	= GeofactoryHelperAdm::getAssignArray($assign) ;
		$adr 		= $model->getAdress($id, $type, $vAssign);
		$ggUrl 		= $model->getGoogleGeocodeQuery($adr);

		$coor 		= $model->geocodeItem($ggUrl);

		$save 		= $model->saveCoord($id, $coor, $type, $vAssign);
		$msg 		= $model->htmlResult($id, 1, $adr, $save, false) ;

		$config 	= JComponentHelper::getParams('com_geofactory');
		$ggApikey 	=  strlen( $config->get('ggApikeySt') ) > 3 ? "&key=".$config->get('ggApikeySt') : "" ;
		$http 		= $config->get('sslSite');
		if(empty($http)) $http = (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS']))?"https://":"http://" ; 

		$img		= '<img src="'.$http.'maps.googleapis.com/maps/api/staticmap?center='.$coor[0].','.$coor[1].'&zoom=15&size=200x100&markers='.$coor[0].','.$coor[1].$ggApikey.'">';	
		echo $img.'<br />'.$msg ;

		$app->close();
	}

	public function getaddress(){
		$app 		= JFactory::getApplication();
		$id 		= $app->input->getInt('idCur', -1) ;
		$type 		= $app->input->getString('type') ;
		$assignId	= $app->input->getInt('assign') ;
		$gglink		= (bool) $app->input->getint('gglink') ;
		
		$model 		= $this->getModel('Geocodes');
		$vAssign	= GeofactoryHelperAdm::getAssignArray($assignId) ;
		$adr 		= $model->getAdress($id, $type, $vAssign);

		// si demandé, et si il y a une addresse
		$ggUrl 		= "" ;
		if (count($adr)){
			$ggUrl 	= $model->getGoogleGeocodeQuery($adr);
			$ggUrl	= ' href="'.$ggUrl.'" ' ; 
		}
		else {
			$ggUrl	= ' onclick="alert(\''.JText::_('COM_GEOFACTORY_NO_ADDRESS_FOUND').'\');" ' ; 
		}

		if ($gglink)	echo '<br /><a '.$ggUrl.' target="_blank">'.JText::_('COM_GEOFACTORY_GEOCODE_ERROR').'</a>' ;
		else 			echo '<br /><strong>'.JText::_('COM_GEOFACTORY_ADDRESS').' : </strong><br />'.implode("<br />", $adr) ;

		$app = JFactory::getApplication();
		$app->close();
	}


	public function deletek2(){
		$app 		= JFactory::getApplication();
		$type 		= $app->input->get('typeliste') ;
		$assign 	= $app->input->getInt('assign') ;

		$model 		= $this->getModel('Geocodes');
		$model->deleteFromGFTable('com_k2');

		$this->setRedirect(JRoute::_('index.php?option=com_geofactory&view=geocodes&typeliste='.$type.'&assign='.$assign, false));
	}

	
	public function deletejc(){
		$app 		= JFactory::getApplication();
		$type 		= $app->input->get('typeliste') ;
		$assign 	= $app->input->getInt('assign') ;

		$model 		= $this->getModel('Geocodes');
		$model->deleteFromGFTable('com_content');

		$this->setRedirect(JRoute::_('index.php?option=com_geofactory&view=geocodes&typeliste='.$type.'&assign='.$assign, false));
	}
	
	
	public function deletejct(){
		$app 		= JFactory::getApplication();
		$type 		= $app->input->get('typeliste') ;
		$assign 	= $app->input->getInt('assign') ;

		$model 		= $this->getModel('Geocodes');
		$model->deleteFromGFTable('com_contact');

		$this->setRedirect(JRoute::_('index.php?option=com_geofactory&view=geocodes&typeliste='.$type.'&assign='.$assign, false));
	}

}
