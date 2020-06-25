<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/0
	* Creation date: Mars 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

class GMapFPsController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$view = $this->input->get('view', '', '', 'string');
		if ($view == 'gmapfplist') {
			$view = $this->getView( 'gmapfplist', 'html');
			$view->setModel( $this->getModel( 'gmapfp' ), true );
			$view->display();
		} else {	
			if ($view == 'gmapfpcontact') {
				$view = $this->getView( 'gmapfpcontact', 'html');
				$view->setModel( $this->getModel( 'gmapfp' ), true );
				$view->display();
			} else{
				parent::display();
			};
		};
	}
}
