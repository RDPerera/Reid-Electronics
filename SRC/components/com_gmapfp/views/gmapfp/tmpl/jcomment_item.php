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

foreach ($this->lieux as $lieu) {
  $jcomments =  JPATH_SITE.'/components/com_jcomments/jcomments.php';
  if (file_exists($jcomments)) {
    require_once($jcomments);
    echo '<div style="clear: both;">';
    echo JComments::showComments($lieu->id, 'com_gmapfp', $lieu->nom);
    echo '</div>';
  }
};?>
