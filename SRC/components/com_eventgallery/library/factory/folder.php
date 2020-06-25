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

class EventgalleryLibraryFactoryFolder extends EventgalleryLibraryFactoryFactory
{
    protected $_folders;
    protected $_allFolderDatabaseObject = NULL;

    /**
     * Returns a folder
     *
     * @param $foldername string|object
     * @return EventgalleryLibraryFolder
     */
    public function getFolder($foldername) {

        if (null == $foldername) {
            return null;
        }

        if (!is_string($foldername)) {
            throw new InvalidArgumentException("can get a folder by String only.");
        }

        return $this->getFolderFromDatabaseObject($foldername);
    }



    public function getAllFolders() {
        $allFolders = $this->getAllFoldersFromDatabase();
        foreach($allFolders as $folder) {
            $this->getFolderFromDatabaseObject($folder->folder);
        }
        return $this->_folders;
    }

    /**
     * @param $id integer
     */
    public function getFolderById($id) {
        foreach ($this->getAllFoldersFromDatabase() as $folderFromDatabase) {
            if ($folderFromDatabase->id == $id) {
                return $this->getFolderFromDatabaseObject($folderFromDatabase->folder);
            }
        }

        return null;
    }

    protected function getFolderFromDatabaseObject($foldername) {
        $allFolders = $this->getAllFoldersFromDatabase();


        if (!isset($this->_folders[$foldername])) {

            $databaseFolder = null;

            if (isset($allFolders[$foldername])) {
                $databaseFolder = $allFolders[$foldername];
            }

            if (isset($databaseFolder->folderhandlerclassname)) {
                $folderClass = $databaseFolder->folderhandlerclassname;
                /**
                 * @var EventgalleryLibraryFolder $folderClass
                 * */
                $this->_folders[$foldername] = new $folderClass($databaseFolder);
            } else {
                $this->_folders[$foldername] = null;
            }

        }

        return $this->_folders[$foldername];
    }

    protected function getAllFoldersFromDatabase() {
        if (NULL === $this->_allFolderDatabaseObject) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('f.*');
            $query->from('#__eventgallery_folder f');


            $db->setQuery($query);
            $result = $db->loadObjectList();

            $this->_allFolderDatabaseObject = array();
            /**
             * @var EventgalleryLibraryFactoryFoldertype $folderTypeFactory
             */
            $folderTypeFactory = EventgalleryLibraryFactoryFoldertype::getInstance();
            foreach($result as $databaseFolder) {
                $databaseFolder->folderhandlerclassname = $folderTypeFactory->getFolderTypeById($databaseFolder->foldertypeid)->getFolderHandlerClassname();
                $this->_allFolderDatabaseObject[$databaseFolder->folder] = $databaseFolder;
            }
        }

        return $this->_allFolderDatabaseObject;
    }

    public static function clear() {

        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = self::getInstance();
        $folderFactory->_folders = null;
        $folderFactory->_allFolderDatabaseObject = null;


        parent::clear();
    }
}
