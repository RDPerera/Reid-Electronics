<?php 
	/*
	* GMapFP Component Google Map for Joomla! 2.5.x
	* Version 10.92
	* Creation date: Août 2013
	* Author: Fabrice4821 - www.gmapfp.francejoomla.net
	* Author email: fayauxlogescpa@gmail.com
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 
$config = JComponentHelper::getParams('com_gmapfp'); 
$params = $config->toarray(); 
print_r('Param&eacute;tres<br/>');
foreach ($params as $key => $param) {
	print_r('&nbsp;&nbsp;&nbsp;&nbsp;['.$key.'] => '.$param.'<br />'."\n");
}

print_r('<br/>');
print_r('Extensions<br/>');
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$query->select('name, manifest_cache')->from('#__extensions')->where('element LIKE "%gmapfp%"');
$db->setQuery($query);
$extensions = $db->loadObjectList();
foreach($extensions as $extension){
	print_r('&nbsp;&nbsp;&nbsp;&nbsp;['.$extension->name.'] => '.'<br />'."\n");
	$datas = json_decode($extension->manifest_cache, true);
	foreach($datas as $key => $data){
		print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;['.$key.'] => '.$data.'<br />'."\n");
	}
}
die;
?>
