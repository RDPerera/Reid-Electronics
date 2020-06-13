<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_50F
	* Creation date: Octobre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class GMapFPsViewCSS extends JViewLegacy
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'GMAPFP_CSS_MANAGER' ).': <small><small>[CSS]</small></small>', 'themes.png' );
		JToolBarHelper :: custom( 'saveCss', 'save.png', 'save.png', 'JTOOLBAR_APPLY', false, false );

		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}
}
