<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventgalleryControllerGooglephotosapi extends JControllerLegacy
{
    /**
     * @param bool  $cachable
     * @param array $urlparams
     *
     * @return JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = array())
    {
        parent::display(false, $urlparams);
    }

    public function getAlbum()
    {
        header('Content-Type: application/json');
        $result = [];

        $foldername = $this->input->getString('folder', NULL);

        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();

        $folder = $folderFactory->getFolder($foldername);
        if ($folder->isAccessible() && $folder->isVisible()) {
            /**
             * @var EventgalleryLibraryFolderGooglephotos $folder
             */
            foreach($folder->getFilesForImages() as $file) {
                /**
                 * @var EventgalleryLibraryFileGooglephotos $file
                 */

                $result[$file->getFileName()]  = $file->getBaseUrl();
            }
        }

        echo json_encode($result);
        $this->endExecution();
    }

    public function getAlbums()
    {
        header('Content-Type: application/json');
        $result = [];
        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();

        $allFolders = $folderFactory->getAllFolders();
        foreach ($allFolders as $folder) {
            /**
             * @var EventgalleryLibraryFolder $folder
             */
            if ($folder->getFolderType()->getId() != EventgalleryLibraryFolderGooglephotos::ID) {
                continue;
            }

            /**
             * @var EventgalleryLibraryFolderGooglephotos $folder
             */

            $files = $folder->getFilesForImages(0, 1, 1);
            if (count($files) == 0 ) {
                continue;
            }

            /**
             * @var EventgalleryLibraryFileGooglephotos $file
             */
            $file = $files[0];

            $result[$folder->getFolderName()] = [$file->getFileName() => $file->getBaseUrl()];

        }

        echo json_encode($result);
        $this->endExecution();
    }

    public function endExecution() {
        die();
    }


}
