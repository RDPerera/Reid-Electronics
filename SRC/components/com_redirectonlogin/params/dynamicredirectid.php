<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// No direct access
defined('_JEXEC') or die;

class JFormFieldDynamicredirectid extends JFormField{

	var $type = 'dynamicredirectid';
	
	 protected function getInput() {
		
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('id, name');
		$query->from('#__redirectonlogin_dynamics');		
		$query->order('name');
		$rows = $db->setQuery($query);				
		$rows = $db->loadObjectList();
		
		$options = array();
		foreach($rows as $row){		
			$options[]	= JHtml::_('select.option',	$row->id, $row->name);	
		}	
		
		$return = '<select id="jformrequestid" name="jform[request][id]">';
			$return .= '<option value=""> - '.JText::_('COM_REDIRECTONLOGIN_SELECT_DYNAMIC_REDIRECT').' - </option>';
			$return .= JHtml::_('select.options', $options, 'value', 'text', $this->value);
		$return .= '</select>';
		
		return $return;		
	}	

}

?>