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

class EventgalleryModelSync extends JModelList
{

    /**
     * adds new folders to the databases
     * @return EventgalleryLibraryFolderAddresult[]
     */
    public function findNewFolders() {
        /**
         * @var EventgalleryLibraryManagerFolder $folderMgr
         */
        $folderMgr = EventgalleryLibraryManagerFolder::getInstance();
        return $folderMgr->findNewFolders();

    }

    /*
    * returns the folders
    * @return EventgalleryLibraryFolder[]
    */
    public function getFolders() {
        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */

        function filter($folder){
            /**
             * @var EventgalleryLibraryFolder $folder
             */
            if ($folder->getFolderType()->getId() == EventgalleryLibraryFolderLocal::ID || $folder->getFolderType()->getId() == EventgalleryLibraryFolderS3::ID) {
                return true;
            }
            return false;
        }

        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();

        return array_filter($folderFactory->getAllFolders(), "filter");
    }

    /**
     * @param string $foldername
     * @param string $filename
     * @return array
     */
    public function syncFile($foldername, $filename) {
        /**
         * @var EventgalleryLibraryFactoryFile $fileFactory
         * @var EventgalleryLibraryFile $file
         */
        $fileFactory = EventgalleryLibraryFactoryFile::getInstance();
        $file = $fileFactory->getFile($foldername, $filename);

        $syncResult = EventgalleryLibraryManagerFolder::$SYNC_STATUS_FAILED;

        try {
            if (null != $file) {
                $syncResult = $file->syncFile();
            }
        } catch (Exception $e) {

        }

        $result = "";

        if ($syncResult == EventgalleryLibraryManagerFolder::$SYNC_STATUS_NOSYNC) {
            $result = "nosync";
        }

        if ($syncResult == EventgalleryLibraryManagerFolder::$SYNC_STATUS_SYNC)  {
            $result = "sync";
        }

        if ($syncResult == EventgalleryLibraryManagerFolder::$SYNC_STATUS_DELTED)  {
            $result = "deleted";
        }

        if ($syncResult == EventgalleryLibraryManagerFolder::$SYNC_STATUS_FAILED)  {
            $result = "failed";
        }

        return ['sync' => $result,
            'foldername' => $foldername,
            'filename' => $filename,
            'id' => $file->getId()
        ];
    }

    /*
    * syncs a folder and returns the status
    */
    public function syncFolder($folder, $foldertype, $use_htacces_to_protect_original_files) {

        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         * @var EventgalleryLibraryFolder $folderClass
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
        $folderObject = $folderFactory->getFolder($folder);

        if ($folderObject == null) {
            /**
             * @var $folderMgr EventgalleryLibraryManagerFolder
             */
            $folderMgr = EventgalleryLibraryManagerFolder::getInstance();
            $folderMgr->addNewFolder($folder, $foldertype);
            $folderObject = $folderFactory->getFolder($folder);
        }

        $folderClass = $folderObject->getFolderType()->getFolderHandlerClassname();
        $syncResult = $folderClass::syncFolder($folder, $use_htacces_to_protect_original_files);

        $result = ["status"=>"", "files" => isset($syncResult['files'])?$syncResult['files']:array()];

        if ($syncResult['status'] == EventgalleryLibraryManagerFolder::$SYNC_STATUS_NOSYNC) {
            $result['status'] = "nosync";
        }

        if ($syncResult['status'] == EventgalleryLibraryManagerFolder::$SYNC_STATUS_SYNC)  {
            $result['status'] = "sync";
        }

        if ($syncResult['status'] == EventgalleryLibraryManagerFolder::$SYNC_STATUS_DELTED)  {
            $result['status'] = "deleted";
        }

        return $result;
    }
}
