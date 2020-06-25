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


class EventgalleryLibraryFileGooglephotos extends EventgalleryLibraryFile
{

    /**
     * @var EventgalleryLibraryFolderGooglephotos
     */
    protected $_folder;

    private $imageRatio;

    public $_google_loading_script_path = COM_EVENTGALLERY_GOOGLEPHOTOS_LOADING_IMAGE_PATH.'/gp.svg';

    /**
     * creates the lineitem object. The foldername can either be a string or a file data object
     *
     * @param object $object
     */
    /** @noinspection PhpMissingParentConstructorInspection */
    function __construct($object)
    {
        parent::__construct($object);

        if (isset($this->_file->height)) {
            $this->imageRatio = $this->_file->width / $this->_file->height;
        } else {
            $this->imageRatio = 1;
        }
    }


    /**
     * @return EventgalleryLibraryFolderGooglephotos
     */
    public function getFolder() {
        return $this->_folder;
    }

    public function getFullImgTag($width, $height, $showImageTitle, $showImageCaption)
    {
        if ($this->imageRatio >= 1) {
            $height = round($width / $this->imageRatio);
        } else {
            $width = round($height * $this->imageRatio);
        }

        return parent::getFullImgTag($width, $height, $showImageTitle, $showImageCaption);
    }

    public function getImageUrl($width, $height, $fullsize, $larger = false)
    {
        $url = "";

        if ($this->_file == null) {
            return $url;
        }

        if ($fullsize) {
            $url =  $this->getThumbUrl(COM_EVENTGALLERY_IMAGE_ORIGINAL_MAX_WIDTH, COM_EVENTGALLERY_IMAGE_ORIGINAL_MAX_WIDTH);
        } else {
            if ($this->imageRatio < 1) {
                $url = $this->getThumbUrl($height * $this->imageRatio, $height, $larger);
            } else {
                $url =  $this->getThumbUrl($width, $width / $this->imageRatio, $larger);
            }
        }

        return $url;
    }

    public function getThumbUrl($width = 104, $height = 104, $larger = true)
    {

        if ($this->_file == null) {
            return "";
        }

        if ($width == 0) {
            $width = 104;
        }
        if ($height == 0) {
            $height = 104;
        }


        if ($this->_file->width > $this->_file->height) {
            // querformat
            $googlewidth = $width;
            $resultingHeight = $googlewidth / $this->imageRatio;
            if ($resultingHeight < $height) {
                $googlewidth = round($height * $this->imageRatio);
            }
        } else {
            //hochformat
            $googlewidth = $height;
            $resultingWidth = $googlewidth * $this->imageRatio;
            if ($resultingWidth < $width) {
                $googlewidth = round($height / $this->imageRatio);
            }
        }

        $sizeSet = new EventgalleryHelpersSizeset();
        $saveAsSize = $sizeSet->getMatchingSize($googlewidth);

        $thumbUrl = JUri::root(). $this->_google_loading_script_path.'#m='.$this->isMainImage().'&folder='.$this->getFolderName().'&file='.$this->getFileName().'&width='.$saveAsSize;

        return $thumbUrl;
    }

    public function getOriginalImageUrl() {

        $url = JUri::base().substr(JRoute::_('index.php?option=com_eventgallery&view=download&folder='.$this->getFolderName().'&file='.urlencode($this->getFileName()) ), strlen(JUri::base(true)) + 1);
        $url = str_replace('/administrator/','/', $url);
        return $url;

    }

    public function getSharingImageUrl() {
        $url = JUri::base().substr(JRoute::_('index.php?option=com_eventgallery&is_for_sharing=true&view=download&folder='.$this->getFolderName().'&file='.urlencode($this->getFileName()) ), strlen(JUri::base(true)) + 1);
        $url = str_replace('/administrator/','/', $url);
        return $url;

    }

    public function getBaseUrl() {
        return $this->_file->googlephotos_baseurl;
    }

    /**
     * returns the filename which was used to upload that file to Google Photos
     *
     * @return |null
     */
    public function getOriginalFilename() {
        return $this->_file->googlephotos_filename;
    }

    public function getGoogleImageUrl($width) {
        $this->getFolder()->updateAlbum(COM_EVENTGALLERY_GOOGLE_PHOTOS_IMAGE_CACHE_LIFETIME);
        return $this->getBaseUrl() . "=w" . $width;
    }

}
