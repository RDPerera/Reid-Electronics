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

foreach ($this->lieux as $lieu) { ?>
	<h2>
	<?php echo JText::_('GMAPFP_HORAIRES_PRIX'); ?>
    </h2>
	<?php echo $lieu->horaires_prix;
};?>
