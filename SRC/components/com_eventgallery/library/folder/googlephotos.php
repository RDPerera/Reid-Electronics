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

use \Joomla\Component\Eventgallery\Site\Library\Connector\GooglePhotos;

class EventgalleryLibraryFolderGooglephotos extends EventgalleryLibraryFolder
{
    const ID = 4;

    private $_album_updated = false;
    private $_albums_updated = false;

    /**
     * @param int $limitstart
     * @param int $limit
     * @param int $imagesForEvents if true load the main images at the first position
     * @return array
     */
    public function getFiles($limitstart = 0, $limit = 0, $imagesForEvents = 0, $sortAttribute='', $sortDirection='ASC') {

        if ($imagesForEvents == 1) {
            $this->updateAlbums();
        } else {
            $this->updateAlbum();
        }

        $result =  parent::getFiles($limitstart, $limit, $imagesForEvents, $sortAttribute, $sortDirection);

        if (EVENTGALLERY_EXTENDED) {
            return $result;
        } else {
            return array_slice($result, 0, 30);
        }
    }

    /**
     * This special method uses a fixed cache lifetime so we can get data from Google more often.
     *
     * @param int $limitstart
     * @param int $limit
     * @param int $imagesForEvents
     * @param string $sortAttribute
     * @param string $sortDirection
     * @return array
     */
    public function getFilesForImages($limitstart = 0, $limit = 0, $imagesForEvents = 0, $sortAttribute='', $sortDirection='ASC') {

        if ($imagesForEvents == 1) {
            $this->updateAlbums(COM_EVENTGALLERY_GOOGLE_PHOTOS_IMAGE_CACHE_LIFETIME);
        } else {
            $this->updateAlbum(COM_EVENTGALLERY_GOOGLE_PHOTOS_IMAGE_CACHE_LIFETIME);
        }

        return parent::getFiles($limitstart, $limit, $imagesForEvents, $sortAttribute, $sortDirection);
    }

    public function getGooglePhotosAccountId() {
        return $this->_folder->googlephotosaccountid;
    }

    public function getGooglePhotosTitle() {
        return $this->_folder->googlephotostitle;
    }

    public function getGooglePhotosAccount() {
        /**
        * @var EventgalleryLibraryFactoryGooglephotosaccount $accountFactory
        *
        */
        $accountFactory = EventgalleryLibraryFactoryGooglephotosaccount::getInstance();
        return $accountFactory->getGooglePhotosAccountById($this->getGooglePhotosAccountId());
    }

    /**
     * returns the picasa album id
     *
     * @return string
     */
    public function getAlbumId() {
        return $this->_foldername;
    }

    /**
     * Updates the album
     */
    public function updateAlbum($cachelifetime = null) {
        if ($this->_album_updated == NULL) {
            $this->_album_updated = true;

            $cachelifetime = $cachelifetime != null?$cachelifetime : $this->config->getGeneral()->getGooglePhotosCacheLifetime();
            $account = $this->getGooglePhotosAccount();
            if ($account == null || !$account->isUsable()) {
                JFactory::getApplication()->enqueueMessage( JText::sprintf('COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNT_FOR_EVENT_INVALID', $this->getDisplayName()));
                return;
            }
            $refresh_token = $account->getRefreshToken();
            $api_clientid = $account->getClientId();
            $api_secret = $account->getSecret();

            GooglePhotos::syncAlbum($cachelifetime, $api_clientid, $api_secret, $refresh_token, JFactory::getDbo(), $this->getAlbumId());
        }
    }

    /**
     * Updates the album
     */
    public function updateAlbums($cachelifetime = null) {
        if ($this->_albums_updated == NULL) {
            $this->_albums_updated = true;

            $cachelifetime = $cachelifetime != null?$cachelifetime : $this->config->getGeneral()->getGooglePhotosCacheLifetime();

            /**
             * @var EventgalleryLibraryFactoryGooglephotosaccount $accountFactory
             *
             */
            $accountFactory = EventgalleryLibraryFactoryGooglephotosaccount::getInstance();
            $accounts = $accountFactory->getUsableGooglePhotosAccounts();

            foreach($accounts as $account) {
                $refresh_token = $account->getRefreshToken();
                $api_clientid = $account->getClientId();
                $api_secret = $account->getSecret();

                GooglePhotos::syncAlbums($cachelifetime, $api_clientid, $api_secret, $refresh_token, JFactory::getDbo());
            }
        }
    }

    public static function syncFolder($foldername, $use_htacces_to_protect_original_files) {
        return ['status' => EventgalleryLibraryManagerFolder::$SYNC_STATUS_NOSYNC];
    }

    public static function findNewFolders() {
        return Array();
    }

    public static function getFileFactory() {
        return EventgalleryLibraryFactoryFileGooglephotos::getInstance();
    }

    public function isSortable() {
        return false;
    }

    public function supportsFileUpload() {
        return false;
    }

    public function supportsFileDeletion() {
        return false;
    }

    public function supportsImageDataEditing() {
        return false;
    }

}
