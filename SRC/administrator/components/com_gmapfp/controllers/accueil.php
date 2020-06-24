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

class GMapFPsControllerAccueil extends GMapFPsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function view()
	{
		JRequest::setVar( 'view', 'accueil' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 0);

		parent::display();
	}

}
?>
