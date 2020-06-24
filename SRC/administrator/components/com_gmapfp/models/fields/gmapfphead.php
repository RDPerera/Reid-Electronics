<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.0pro
	* Creation date: Mai 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class JFormFieldGMapFPHead extends JFormField
{

	public $type = 'GMapFPHead';

	protected function getInput()
	{
		if ($this->element['default']) {
			return '<label style="background: #CCE6FF; color: #0069CC; padding:5px; width: 100%; margin-left: -160px;"><strong>' . JText::_($this->element['default']) . '</strong></label>';
		} else {
			return '';
		}
	}

	public function getLabel() {
		 return '';
	}
}

?>