<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_51F
	* Creation date: Novembre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class GMapFPsViewGMapFP extends JViewLegacy
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 

        $lang 		= JFactory::getLanguage(); 
        $tag_lang	= $lang->getTag();
		if (($tag_lang!='en-AU') AND ($tag_lang!='en-GB') AND ($tag_lang!='pt-BR') AND ($tag_lang!='pt-PT') AND ($tag_lang!='zh-CN') AND ($tag_lang!='zh-TW'))
			{$tag_lang=(substr($lang->getTag(),0,2)); };

		JHtml::_('jquery.framework');	
        $this->document->addStyleSheet('../components/com_gmapfp/assets/dropfiles.css');
	    $this->document->addScript( '../components/com_gmapfp/assets/jquery.filedrop.js');
	    $this->document->addScript( '../components/com_gmapfp/assets/bootbox.min.js');
		
		JText::script('GMAPFP_BROWSER_NOT_SUPPORT_HTML5');
		JText::script('GMAPFP_TOO_MANY_FILES');
		JText::script('GMAPFP_FILE_TOO_LARGE');
		JText::script('GMAPFP_FILE_TYPE_NOT_ALLOWED');
		JText::script('GMAPFP_EXTENSION_TYPE_NOT_ALLOWED');
		JText::script('GMAPFP_ONLY_IMAGE_ALLOWED');
		JText::script('GMAPFP_ALREADY_EXIST');

		$config 	= JComponentHelper::getParams('com_gmapfp');
		$key 	= $config->get('gmapfp_google_key');

		$https = "";
		if ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) OR (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) $https = "s";
        
		$this->document->setMetaData('viewport', 'initial-scale=1.0, user-scalable=no');
        $this->document->addCustomTag( '<script type="text/javascript" src="http'.$https.'://maps.googleapis.com/maps/api/js?language='.$tag_lang.'&key='.$key.'"></script>'); 

		$gmapfp		= $this->get('Data');
		$marqueurs	= $this->get('Marqueurs');
		$isNew		= ($gmapfp->id < 1);
		
		$text = $isNew ? JText::_( 'JTOOLBAR_NEW' ) : JText::_( 'JTOOLBAR_EDIT' );
		JToolBarHelper::title(   JText::_( 'GMAPFP_LIEUX_MANAGER' ).': <small>[ ' . $text.' ]</small>', 'frontpage.png' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel('gmapfp.cancel');
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'gmapfp.cancel', 'JTOOLBAR_CLOSE' );
		}
		JHTML::_('behavior.tooltip');

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, nom AS text'
			. ' FROM #__gmapfp'
			//. ' WHERE catid = ' . (int) $gmapfp->catid
			. ' ORDER BY ordering';

		$lists['ordering'] 			= JHTML::_('list.ordering',  'ordering', $query, '', $gmapfp->id, 1 );

		// build list of categories
		$categories = JHtml::_('category.options', $option);
		array_unshift($categories, JHTML::_('select.option',  '', JText::_('JOPTION_SELECT_CATEGORY')));
		$lists['catid'] = JHTML::_(
			'select.genericlist',
			$categories,
			'catid',
			'class="inputbox required" size="1" ',
			'value', 'text',
			intval( $gmapfp->catid )
		);


		$this->assignRef('gmapfp',		$gmapfp);
		$this->assignRef('marqueurs',	$marqueurs);
		$this->assignRef('config',	$config);
		$this->assignRef('lists',		$lists);

		parent::display($tpl);
	}
}
