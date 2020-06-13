<?php
/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/0
	* Creation date: Mars 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

class gmapfpTableMarqueurs extends JTable
{

	function __construct( &$db ) {
		parent::__construct('#__gmapfp_marqueurs', 'id', $db);
	}
}
?>