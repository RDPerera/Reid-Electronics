<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/components/com_eventgallery/config.php';

class EventgalleryHelpersSizeset
{
    public $availableSizes
        = Array(
            48, 104, 160, 288, 320, 400, 512, 640, 720, 800, 1024, 1280, 1440, COM_EVENTGALLERY_IMAGE_ORIGINAL_MAX_WIDTH
        );

    public function getMatchingSize($size)
    {
        $finalSize = $this->availableSizes[count($this->availableSizes) - 1];
        foreach ($this->availableSizes as $option) {
            if ($option >= $size) {
                return $option;
            }
        }
        return $finalSize;
    }

    /**
     * returns the width of an images to it matches both the requested height and width
     *
     * @param int $width
     * @param int $height
     * @param int $originalWidth
     * @param int $originalHeight
     * @return int
     */
    public function getSizeCode($width, $height, $originalWidth, $originalHeight)
    {
        $longSideSize = $width;

        if ($height > $width) {
            $longSideSize = $height;
        }

        if ($height == $width) {
            $ratio = $originalWidth /$originalHeight;
            if ($ratio > 1) {
                // landscape
                $longSideSize = $width * $ratio;
            } else {
                //portait
                $longSideSize = $width / $ratio;
            }
        }

        return $this->getMatchingSize($longSideSize);
    }
}
