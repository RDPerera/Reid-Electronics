<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: default.php 2012-04-05 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Image Slider
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

defined('_JEXEC') or die('Restircted access');

class JElementSlider extends JElement
{
	var	$_name = 'Title';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$doc 		=& JFactory::getDocument();
		$fieldName	= $control_name.'['.$name.']';
		$db 		=& JFactory::getDBO();

		$query = "SELECT name text,id value FROM #__cis_sliders WHERE published = '1'";
		$db->setQuery($query);
		$options = $db->loadObjectList();

		$html = array();
		
		$html[] = "<select name=\"$fieldName\">";
		//$html[] = '<option value="0">'.JText::_("All").'</option>';
		foreach($options AS $o) {
			$html[] = '<option value="'.$o->value.'"'.(($o->value == $value) ? ' selected="selected"' : '').'>';
			$html[] = $o->text;
			$html[] = '</option>';
		}
		$html[] = "</select>";
		
		return implode("", $html);
	}
}
?>
