<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_52F
	* Creation date: Janvier 2018
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class GMapFPsViewEditLieux extends JViewLegacy
{
    public function display($cachable = false, $urlparams = false)
	{
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$option = $input->get('option'); 
		$this->Itemid = $input->get('Itemid', 0, 'INT'); 
		$layout = $input->get('layout',  0, '', 'string');
		$doc 	= JFactory::getDocument();

		//only registered user can add events
		$user		= JFactory::getUser();
		$cid    	= $mainframe->input->get('cid',  0, 'array'); 

		$config_media = JComponentHelper::getParams('com_media');
		$params = clone($mainframe->getParams('com_gmapfp'));

		//gestion des droits
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/gmapfp.php';
		$canDo	= GMapFPHelper::getActions();
		// Make sure you are logged in and have the necessary access rights
		if ((!$canDo->get('core.create') and !(int)$cid[0]) || ((int)$cid[0] > 0 and (!$canDo->get('core.edit') && !$canDo->get('core.edit.own')))) {
				$link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$Itemid, false);
				$returnURL = JUri::current();
				$fullURL = new JURI($link);
				$fullURL->setVar('return', base64_encode($returnURL));
				$link = $fullURL->toString();
				$mainframe->redirect($link);
			return;
		}

		//dectection si android pour affichage de léditeur par défaut ou d'une zone textearea
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		define('GMAPFP_ANDROID', stristr($user_agent,'iPhone') || stristr($user_agent,'iPod') || stristr($user_agent,'android'));

		$lang = JFactory::getLanguage(); 
		$tag_lang=(substr($lang->getTag(),0,2)); 
		
		$https = "";
		if ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) OR (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) $https = "s";
		
		if (!defined( '_JOS_GMAPFP_APIV3' ))
		{
			/** verifi que la fonction n'est défini qu'une fois */
			define( '_JOS_GMAPFP_APIV3', 1 );
		
			$key 	= $params->get('gmapfp_google_key');

            $doc->setMetaData( "viewport", "initial-scale=1.0, user-scalable=no");
            $doc->addScript( 'http'.$https.'://maps.googleapis.com/maps/api/js?language='.$tag_lang.'&key='.$key);
		}

		JHtml::_('jquery.framework');	
        $doc->addStyleSheet('components/com_gmapfp/assets/dropfiles.css');
	    $doc->addScript( 'components/com_gmapfp/assets/jquery.filedrop.js');
	    $doc->addScript( 'components/com_gmapfp/assets/bootbox.min.js');
		
		JText::script('GMAPFP_BROWSER_NOT_SUPPORT_HTML5');
		JText::script('GMAPFP_TOO_MANY_FILES');
		JText::script('GMAPFP_FILE_TOO_LARGE');
		JText::script('GMAPFP_FILE_TYPE_NOT_ALLOWED');
		JText::script('GMAPFP_EXTENSION_TYPE_NOT_ALLOWED');
		JText::script('GMAPFP_ONLY_IMAGE_ALLOWED');
		JText::script('GMAPFP_ALREADY_EXIST');

		 /**
		  * Affichage de la barre de menu
		   **/
		$doc->addCustomTag ('<link rel="stylesheet" href="'.$this->baseurl.'/components/com_gmapfp/views/editlieux/css/icon.css" type="text/css" media="screen" />');
		$doc->addCustomTag ('<link rel="stylesheet" href="'.$this->baseurl.'/components/com_gmapfp/views/editlieux/css/general.css" type="text/css" media="screen" />');

		$items		= $this->get('Data');
		$marqueurs	= $this->get('Marqueurs');
		$custom		= $this->get('Custom');
        $images     = $this->get('images');
		$isNew		= ($items->id < 1);

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, nom AS text'
			. ' FROM #__gmapfp'
			. ' ORDER BY ordering';

		$lists['ordering'] 			= JHTML::_('list.ordering',  'ordering', $query, '', $items->id, 1 );

		// build list of categories
		$select[] = JHtml::_('select.option', '', '-- '.JText::_( 'GMAPFP_SELECT_ITEM' ).' --' );
		$catids = JHtml::_('category.options', 'com_gmapfp');
		$catids = array_merge($select, $catids);
		$lists['catid'] = JHtml::_('select.genericlist', $catids, 'catid', 'size="1" class="inputbox required"', 'value', 'text', intval( $items->catid ) );

		$this->config_media = $config_media;
		$this->custom = $custom;
		$this->items = $items;
		$this->marqueurs = $marqueurs;
		$this->params = $params;
		$this->lists = $lists;
		$this->images = $images;
		parent::display();
	}

    function setImage($index = 0)
    {
        if (isset($this->images[$index])) {
            $this->_tmp_img = &$this->images[$index];
        } else {
            $this->_tmp_img = new JObject;
        }
    }

}
