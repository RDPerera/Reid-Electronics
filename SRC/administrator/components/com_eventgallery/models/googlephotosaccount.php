<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.modeladmin' );

class EventgalleryModelGooglephotosaccount extends JModelAdmin
{

    public function getTable($type = 'Googlephotosaccount', $prefix = 'EventgalleryTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }	

    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm('com_eventgallery.googlephotosaccount', 'googlephotosaccount', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)){
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.googlephotosaccount.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }
        
		if (method_exists($this, 'preprocessData')) {
        	$this->preprocessData('com_eventgallery.googlephotosaccount', $data);
        }

        return $data;
    }
}
