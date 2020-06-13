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
JHTML::_( 'behavior .modal' );
$config = JComponentHelper::getParams('com_gmapfp');

?>
<div class="contentpane<?php echo $config->get( 'pageclass_sfx' ); ?>">
<?php
echo $this->map;
?>
</div >