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

class EventgalleryModelMessage extends JModelAdmin
{
    protected $text_prefix = 'COM_EVENTGALLERY';

    public function getTable($type = 'message', $prefix = 'EventgalleryTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }


    /**
     * Abstract method for getting the form from the model.
     *
     * @param   array $data Data for the form.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  \JForm|boolean  A \JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        return false;
    }
}
