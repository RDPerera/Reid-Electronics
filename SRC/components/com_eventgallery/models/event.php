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

jimport('joomla.application.component.model');
jimport('joomla.html.pagination');


/** @noinspection PhpUndefinedClassInspection */
class EventgalleryModelEvent extends JModelLegacy
{
    protected $_pagination;
    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;

    function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        $limitstart = $app->input->getInt('limitstart', 0);
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $this->config->getEventsList()->getMaxImagesPerPage(), 'int');
        $this->setState('limit', $limit);
        $this->setState('com_eventgallery.event.limitstart', $limitstart);
    }

    function getEntries($foldername = '', $limitstart = 0, $limit = 0, $imagesForEvents = 0)
    {
        if ($limit == 0) {
            $limit = $this->getState('limit');
        }

        if ($limitstart == 0) {
            $limitstart = $this->getState('com_eventgallery.event.limitstart');
        }

        // fix issue with events list where paging was working
        if ($limitstart < 0) {
            $limitstart = 0;
        }
        // do the picasa web handling here

        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
        $folder = $folderFactory->getFolder($foldername);

        if ($folder == null) {
        	return Array();
        }

        return $folder->getFiles($limitstart, $limit, $imagesForEvents, $this->config->getEventsList()->getSortFilesByColumn(), $this->config->getEventsList()->getSortFilesByDirection());

    }

    function getPagination($folder = '')
    {

        $app = JFactory::getApplication();

        if (empty($this->_pagination)) {

            $total = $this->getTotal($folder);
            $limit = (integer)$this->getState('limit');
            $limitstart = $this->getState('com_eventgallery.event.limitstart');


            if ($limitstart > $total || $app->input->getInt('limitstart', '0') == 0) {
                $limitstart = 0;
                $this->setState('com_eventgallery.event.limitstart', $limitstart);
            }

            $this->_pagination = new JPagination($total, $limitstart, $limit);
        }

        return $this->_pagination;

    }

    function getTotal($folder = '')
    {
        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
        $folder = $folderFactory->getFolder($folder);
        if ($folder == null) {
        	return 0;
        }
        return $folder->getFileCount(true);
    }


}
