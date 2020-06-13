<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

// Load SMS Notification Model
jimport('joomla.application.component.model');

// The class name must always be the same as the filename (in camel case)
class JFormFieldContact extends JFormFieldList {
    
    // The field class must know its own type through the variable $type.
    protected $type = 'contact';
    
    protected function getOptions() {
    	$options = array();
    	
    	JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_smsnotification/models');
    	
    	$smsNotificationModel = JModelLegacy::getInstance('SMSNotification', 'SMSNotificationModel');
    	
    	$users = $smsNotificationModel->getUsers();
    	foreach ($users as $user) {
    		$name = $user[0];
    		$phoneNumber = $user[1];
    		$option = JHtml::_('select.option', $phoneNumber, $name);
    		$options[] = $option;
    	}
    	
    	array_unshift($options, JHtml::_('select.option', '0', JText::_('Select a user')));
    	
    	return array_merge(parent::getOptions(), $options);
    }
}