<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsControllerElement extends GMapFPsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

	}

	function element()
	{
		JRequest::setVar( 'view', 'element_lieu' );
		JRequest::setVar( 'layout', 'element'  );

		parent::display();
	}

	function perso()
	{
		JRequest::setVar( 'view', 'element_perso' );
		JRequest::setVar( 'layout', 'element'  );

		parent::display();
	}

}
?>
