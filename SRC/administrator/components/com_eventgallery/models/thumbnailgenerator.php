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
require __DIR__ . '/sync.php';

class EventgalleryModelthumbnailgenerator extends JModelList
{
    private $s3;
    const ORIGINAL_ETAG = 'originaletag';

    public function __construct(array $config)
    {

        $this->s3 = EventgalleryLibraryCommonS3client::getInstance();
        parent::__construct($config);
    }

    /*
    * returns the folders
     *
    * @return EventgalleryLibraryFolder[]
    */
    /**
     * @param $folderTypes int[]
     * @return array
     */
    public function getFolders($folderTypeIds = [EventgalleryLibraryFolderLocal::ID, EventgalleryLibraryFolderS3::ID]) {
        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */



        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();

        return array_filter($folderFactory->getAllFolders(),  function($folder) use ($folderTypeIds) {
            /**
             * @var EventgalleryLibraryFolder $folder
             */

            if (in_array($folder->getFolderType()->getId(), $folderTypeIds)) {
                return true;
            }
            return false;
        });
    }

    /**
     * get all filenames which need new thumbnails
     *
     * @param $foldername
     * @return array
     */
    public function getFilesToSync($foldername, $saveETagOfThumbnailsToDatabase = false) {
        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         * @var EventgalleryLibraryFolderS3 $folder
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
        $folder = $folderFactory->getFolder($foldername);
        return $folder->getFilesToSync($saveETagOfThumbnailsToDatabase);
    }

    /**
     * create a thumbnail for the given file
     *
     * @param $folder
     * @param $file
     * @return array
     */
    public function createThumbnails($folder, $file) {

        /**
         * @var EventgalleryLibraryFactoryFile $fileFactory
         * @var EventgalleryLibraryFileS3 $fileObject
         */
        $fileFactory = EventgalleryLibraryFactoryFile::getInstance();
        $fileObject = $fileFactory->getFile($folder, $file);
        return $fileObject->createThumbnails();
    }
}
