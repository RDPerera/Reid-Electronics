<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/0
	* Creation date: Mars 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

$bubble = ($this->_num_marqueurs+1000).", \"<div class='gmapfp_marqueur' style='overflow-y:auto;';><span class='titre'>".$nom."</span>";
$bubble.="<br />".$plus_detail."</div>\",\"";

return $bubble;
?>