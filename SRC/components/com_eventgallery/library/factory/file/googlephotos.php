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

class EventgalleryLibraryFactoryFileGooglephotos extends EventgalleryLibraryFactoryFileLocal
{

    /**
     * Returns a file
     *
     * @param $foldername string
     * @param $filename string
     * @return EventgalleryLibraryFilePicasa
     */
    public function getFile($foldername, $filename) {

        return new EventgalleryLibraryFileGooglephotos($this->getFileDBData($foldername, $filename));

    }


}