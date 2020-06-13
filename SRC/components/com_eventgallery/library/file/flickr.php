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
require_once JPATH_ROOT.'/components/com_eventgallery/config.php';

class EventgalleryLibraryFileflickr extends EventgalleryLibraryFile
{
    /**
     * creates the lineitem object. $dblineitem is the database object of this line item
     *
     * @param object $object
     * @throws Exception
     */
    function __construct($object)
    {
        parent::__construct($object);
    }

    public function getFarmId() {
        return $this->_file->flickr_farm;
    }

    public function getServerId() {
        return $this->_file->flickr_server;
    }

    public function getSecret() {
        return $this->_file->flickr_secret;
    }

    public function getSecretO() {
        return $this->_file->flickr_secret_o;
    }

    public function getSecretH() {
        return $this->_file->flickr_secret_h;
    }

    public function getSecretK() {
        return $this->_file->flickr_secret_k;
    }


    public function getLazyThumbImgTag($width = 104, $height = 104, $cssClass = "", $crop = false, $customDataAttributes, $showImageTitle, $showImageCaption) {

        $customDataAttributes  = ' data-farm="'.$this->getFarmId().'"';
        $customDataAttributes .= ' data-server="'.$this->getServerId().'"';
        $customDataAttributes .= ' data-secret="'.$this->getSecret().'"';
        $customDataAttributes .= ' data-secret_o="'.$this->getSecretO().'"';
        $customDataAttributes .= ' data-secret_h="'.$this->getSecretH().'"';
        $customDataAttributes .= ' data-secret_k="'.$this->getSecretK().'"';
        $customDataAttributes .= ' data-id="'.$this->getFileName().'"';
        $customDataAttributes .= ' ';

        return parent::getLazyThumbImgTag($width, $height, $cssClass, $crop, $customDataAttributes, $showImageTitle, $showImageCaption);
    }

    public function getImageUrl($width=104,  $height=104, $fullsize, $larger=false) {
        if ($fullsize) {
            return $this->getThumbUrl(1600, 1600);
        } else {
            return $this->getThumbUrl($width, $height);
        }
    }

    public function getThumbUrl ($width=104, $height=104, $larger=true) {
        if ($width == 0) {
            $width = 104;
        }
        if ($height == 0) {
            $height = 104;
        }
        return $this->flickrUrlBuilder($this->getFlickrSizeCode($width, $height));
    }

    public function getOriginalImageUrl() {
    	return $this->flickrUrlBuilder('o');
    }

    public function getSharingImageUrl() {
        return $this->getOriginalImageUrl();
    }

    /**
     *
     * s    klein, quadratisch, 75 x 75
     * q    large square 150x150
     * t    Thumbnail, 100 an der Längsseite
     * m    klein, 240 an der Längsseite
     * n    small, 320 on longest side
     * -    mittel, 500 an der Längsseite
     * z    mittel 640, 640 an der längsten Seite
     * c    mittel 800, 800 an der längsten Seite†
     * b    groß, 1024 an der längsten Seite*
     * h    groß mit 1600 Pixel, 1600 an längster Seite†
     * k    groß mit 2048 Pixel, 2048 an längster Seite†
     * o    Originalbild, entweder JPG, GIF oder PNG, je nach Quellformat
     *
     * @param $size
     * @return string
     */
    private function flickrUrlBuilder($sizeCode) {
        switch ($sizeCode) {
            case "h":
                $secret = $this->getSecretH();
                break;
            case "k":
                $secret = $this->getSecretK();
                break;
            case "o":
                $secret = $this->getSecretO();
                break;
            default:
                $secret = $this->getSecret();
        }

        $size = $sizeCode == '-' ? '' : '_'.$sizeCode;
        return 'https://farm'.$this->getFarmId().'.staticflickr.com/'.$this->getServerId().'/'.$this->getFileName().'_'.$secret.$size.'.'.$this->getFileExtension();
    }

    private function getFlickrSizeCode($width, $height) {

        $longSideSize = $width;
        $originalLongSideSize = $this->getWidth();

        if ($height>$width) {
            $longSideSize = $height;
            $originalLongSideSize = $this->getHeight();
        }

        if ($height == $width) {
            $ratio = $this->getWidth() / $this->getHeight();
            if ($ratio < 1) {
                // landscape
                $longSideSize = $width / $ratio;
            } else {
                //portait
                $longSideSize = $width * $ratio;
            }
            if ($longSideSize>2048) {
                $longSideSize = 2048;
            }
        }

        if ($originalLongSideSize < $longSideSize) {
            return 'o';
        }

        $sizes = array(100 => 't', 240 => 'm', 320 => 'n', 500 => '-', 640 => 'z', 800 => 'c', 1024 => 'b');

        if ($this->getSecretH() != "") {
            $sizes[1600] = 'h';
        }

        if ($this->getSecretK() != "") {
            $sizes[2048] = 'k';
        }


        foreach($sizes as $size=>$sizeCode) {
            if ($size >= $longSideSize) {
                return $sizeCode;
            }
        }

        return 'o';
    }

    private function getFileExtension() {
        $ext = $this->_file->flickr_originalformat;
        return strlen($ext)>0? $ext : 'jpg';
    }

}
