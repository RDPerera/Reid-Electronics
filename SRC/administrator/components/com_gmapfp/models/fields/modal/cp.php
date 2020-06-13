<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_39F
	* Creation date: Avril 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class plgSystemCp extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

    function onAfterDispatch()
    {
        if (JFactory::getApplication()->isSite()) {
			$doc = JFactory::getDocument();
			$cacheBuf = $doc->getBuffer('component');

			$find_1 = strpos($cacheBuf, '<div\>');
			$find_2 = strpos($cacheBuf, '<form\>');
			if ($find_1 or $find_2){
				$html = '';
				$html .= '<div style="text-align:center;">';
				$html .= '<a href="http://gmapfp.org" target="_blank">GMapFP</a>';
				$html .= '</div>';
				if ($find_1) $cacheBuf = str_replace('<div\>', $html.'</div>', $cacheBuf);
				if ($find_2) $cacheBuf = str_replace('<form\>', $html.'</form>', $cacheBuf);
				$doc->setBuffer($cacheBuf ,'component');
			}
            return true;
        }
    }

}