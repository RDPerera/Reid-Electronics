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

class EventgalleryLibraryFactoryFoldertype extends EventgalleryLibraryFactoryFactory
{

    protected $_foldertype;
    protected $_foldertype_published;

    public function __construct() {
        $items = json_decode('[
          {
            "id": 0,
            "name": "local",
            "folderhandlerclassname": "EventgalleryLibraryFolderLocal",
            "displayname": "Local Images",
            "default": 1,
            "ordering": 1,
            "published": 1
          },
          {
            "id": 1,
            "name": "picasa",
            "folderhandlerclassname": "EventgalleryLibraryFolderPicasa",
            "displayname": "Picasa Images (deprecated)",
            "default": 0,
            "ordering": 2,
            "published": 1
          },
          {
            "id": 2,
            "name": "flickr",
            "folderhandlerclassname": "EventgalleryLibraryFolderFlickr",
            "displayname": "Flickr Images",
            "default": 0,
            "ordering": 3,
            "published": 1
          },
          {
            "id": 3,
            "name": "s3",
            "folderhandlerclassname": "EventgalleryLibraryFolderS3",
            "displayname": "Amazon S3 Images",
            "default": 0,
            "ordering": 4,
            "published": 1
          },
          {
            "id": 4,
            "name": "googlephotos",
            "folderhandlerclassname": "EventgalleryLibraryFolderGooglephotos",
            "displayname": "Google Photos (experimental)",
            "default": 0,
            "ordering": 5,
            "published": 1
          }
        ]');

        foreach ($items as $item) {
            /**
             * @var EventgalleryLibraryFoldertype $itemObject
             */
            $itemObject = new EventgalleryLibraryFoldertype($item);
            if ($itemObject->isPublished()) {
                $this->_foldertype_published[$itemObject->getId()] = $itemObject;
            }
            $this->_foldertype[$itemObject->getId()] = $itemObject;
        }

        parent::__construct();
    }

    /**
     * Return all folder types
     *
     * @param $publishedOnly
     * @return array
     */
    public function getFolderTypes($publishedOnly) {

        if ($publishedOnly) {
            return $this->_foldertype_published;
        } else {
            return $this->_foldertype;
        }
    }

    /**
     * Returns the default folder type
     *
     * @param bool $publishedOnly returns only published folder type
     * @return EventgalleryLibraryFoldertype
     */
    public function getDefaultFolderType($publishedOnly) {
        $sets = array_values($this->getFolderTypes($publishedOnly));
        if (isset($sets[0])) {
            return $sets[0];
        }
        return null;

    }

    /**
     * Determines a Folder Type by a given ID
     *
     * @param $id
     * @return EventgalleryLibraryFoldertype
     */
    public function getFolderTypeById($id)
    {
        $sets = $this->getFolderTypes(false);
        if (isset($sets[$id]))
        {
            return $sets[$id];
        }
        return $this->getDefaultFolderType(true);

    }
}