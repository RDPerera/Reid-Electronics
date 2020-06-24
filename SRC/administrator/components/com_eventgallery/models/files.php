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

jimport( 'joomla.application.component.modellist' );

class EventgalleryModelFiles extends JModelList
{

    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;
    protected $_id = null;
    protected $_item = null;

    public function __construct() {
        $app = JFactory::getApplication();
        $ids = $app->input->getString('folderid');
        $this->_id = $ids;
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        parent::__construct();
    }

	function getListQuery()
	{
		// Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

		$query->select('file.*');

		$query->from('#__eventgallery_file AS file');
        $query->join('','#__eventgallery_folder AS folder on folder.folder=file.folder');
		$query->where('folder.id='.$this->_db->quote($this->_id));
		$query->group('file.id');

        $sortAttribute = $this->getItem()->getSortAttribute();
        $sortDirection = $this->getItem()->getSortDirection();

        if (empty($sortAttribute)) {
            $sortAttribute = $this->config->getEventsList()->getSortFilesByColumn();
        }
        if (empty($sortDirection)) {
            $sortDirection = $this->config->getEventsList()->getSortFilesByDirection();
        }

        $sortBy = "";
        if (!empty($sortAttribute)) {
            $sortBy = $db->quoteName($sortAttribute) . ' ' . (strtoupper($sortDirection) == 'ASC'?'ASC':'DESC') . ',';
        }


        // find files which are allowed to show in a list
        $query->order($sortBy . 'ordering DESC, file.file');

		return $query;
	}

    /**
     * @return EventgalleryLibraryFolder
     */
    function getItem()
    {
        if (empty( $this->_item )) {
            /**
             * @var EventgalleryLibraryFactoryFolder $folderMgr
             */
            $folderMgr = EventgalleryLibraryFactoryFolder::getInstance();
            $this->_item = $folderMgr->getFolderById($this->_id);
        }

        return $this->_item;
    }

	


}
