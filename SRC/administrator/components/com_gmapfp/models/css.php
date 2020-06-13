<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/13
	* Creation date: Decembre 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

// No direct access
defined( '_JEXEC' ) or die;

class GMapFPsModelCss extends JModelLegacy
{
	function store()
	{	
		$file 			= JPATH_COMPONENT_SITE.'/views/gmapfp/gmapfp.css';
		$csscontent	 	= JRequest::getVar('csscontent', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if( $fp = @fopen( $file, 'w' )) {
			fputs( $fp, stripslashes( $csscontent ) );
			fclose( $fp );
			return true;
		}else{
			return false;
		}
	}
}
?>