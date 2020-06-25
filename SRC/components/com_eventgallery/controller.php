<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;


jimport('joomla.application.component.controller');
jimport('joomla.mail.mail');


/** @noinspection PhpUndefinedClassInspection */
class EventgalleryController extends JControllerLegacy
{

	public function __construct($config = array())
    {
        $this->input = JFactory::getApplication()->input;

        // Article frontpage Editor contentpluginbutton proxying:
        if ($this->input->get('view') === 'events' && $this->input->get('layout') === 'contentpluginbutton')
        {
        	$language = JFactory::getLanguage();
			$language->load('com_eventgallery' , JPATH_COMPONENT_ADMINISTRATOR, $language->getTag(), true);
	
            $config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
        }

        parent::__construct($config);
    }

    public function display($cachable = false, $urlparams = array())
    {
        $safeurlparams = array(
            'catid' => 'STRING',
            'folder' => 'STRING',
            'file' => 'STRING',
            'Itemid' => 'INT',
            'limitstart' => 'INT',
            'limit' => 'INT');

        if ($this->input->getMethod() == 'POST')
        {
            $cachable = false;
        }



        parent::display($cachable, $safeurlparams);
    }

    
    /**
     * resets the view cache so we can run multiple test to the same view but different layouts.
     */
    public function resetViewCache() {
        parent::$views = null;
    }
}

