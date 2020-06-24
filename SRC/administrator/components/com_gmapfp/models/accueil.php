<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.38F
	* Creation date: Mars 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class GMapFPsModelAccueil extends JModelLegacy
{
	function getPublishedTabs() {
	
        $lang = JFactory::getLanguage(); 
        $tag_lang=(substr($lang->getTag(),0,2)); 

		$config = JComponentHelper::getParams('com_gmapfp');

		$tabs = array();

		/*$onglet = new stdClass();
		$onglet->title = 'Donation';
		$onglet->name = 'Donation';
		$onglet->alert = false;
		$tabs[] = $onglet;*/

		if ($config->get('gmapfp_news')) {
			$onglet = new stdClass();
			$onglet->title = 'News';
			$onglet->name = 'News';
			$onglet->alert = false;
			$tabs[] = $onglet;
		}

		$onglet = new stdClass();
		if ($tag_lang=='fr'){
			$onglet->title = 'GMapFP c\'est aussi : ';
		} else {
			$onglet->title = 'GMapFP is also: ';
		}
		$onglet->name = 'GMapFP';
		$onglet->alert = true;
		$tabs[] = $onglet;

		return $tabs;
	}




}
?>