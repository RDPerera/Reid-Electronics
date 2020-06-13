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

defined('_JEXEC') or die;

class PlgButtonPlg_geofactory_btn_jc30 extends JPlugin{
	protected $autoloadLanguage = true;

	public function onDisplay($name){
		JHtml::_('behavior.modal');

		// récpère l'article si front ou back
		// option=com_content&view=article&layout=edit&id=16
		// option=com_content&view=form&layout=edit&a_id=1&
		$option 	= JRequest::getString('option');
		$view 		= JRequest::getString('view');
		$id 		= JRequest::getInt('id', 0);
		$a_id 		= JRequest::getInt('a_id', 0);
		$cid 		= JRequest::getInt('cid', 0);

		// en fonction de l'action en cours, je sais si je suis en back ou en front ...
		$idArt = 0 ;
		// pour le moment je ne gère que joomla articles
		if 			(strcasecmp($option, 'com_content')==0) {
			if (strcasecmp($view, 'article')==0)			$idArt = $id;
			if (strcasecmp($view, 'form')==0)				$idArt = $a_id;
		}else if 	(strcasecmp($option, 'com_k2')==0) {
			if ((strcasecmp($view, 'item')==0)AND $id>0)	$idArt = $id;		// frontend
			if ((strcasecmp($view, 'item')==0)AND $cid>0)	$idArt = $cid;		// backend
		}else{
			return;
		}
		
		// ... pas d'id article, c'est que l'article n'est pas encore sauvé, avant j'affichait une erreur dans le dialogue. Ici je génère un ID unqiue,
		// je le mets dans la session du user, et lors de la sauvegarde de l'article, je remplace le numéro unique par l'id de l'article.
		if ($idArt<1){
			$idArt = time(); // 1427965360 // secondes depuis 1970
	        $session    = JFactory::getSession();
	        $session->clear('gf_temp_art_id');
	        $session->set('gf_temp_art_id',$idArt);
			//return ;
		}

		$btntxt = JText::_('COM_GEOFACTORY_BTN_PLG_NAME'); 
		// pas chargé (dans certains cas le language n'est pas chargé)? 
		if ($btntxt == 'COM_GEOFACTORY_BTN_PLG_NAME')
			$btntxt = 'Geocode';

		$lat = $this->params->get('defLat', '');
		$lng = $this->params->get('defLng', '');
		$link =	'index.php?option=com_geofactory&amp;view=button&amp;tmpl=component&amp;idArt='.$idArt.'&amp;dla='.$lat.'&amp;dln='.$lng.'&amp;c_opt='.$option;
		if (strtolower(substr(JPATH_BASE, -13)) == 'administrator' ){
			$link =	'index.php?option=com_geofactory&amp;view=ggmap&amp;layout=editorbtn&amp;tmpl=component&amp;idArt='.$idArt.'&amp;dla='.$lat.'&amp;dln='.$lng.'&amp;c_opt='.$option;
		}
 		
		$button 		= new JObject;
		$button->modal 	= true;
		$button->class 	= 'btn';
		$button->link  	= $link;
		$button->text  	= $btntxt ;
		$button->name  	= 'location';
		$button->options= "{handler: 'iframe', size: {x: 600, y: 500}}";

		return $button;
	}
}
