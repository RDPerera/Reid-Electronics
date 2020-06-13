<?php

namespace Joomla\Component\Eventgallery\Site\Library\Configuration;

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class Event extends Configuration
{
    public function doShowImageTitle() {
        return $this->get('show_image_title', 1) == 1;
    }

    public function doShowImageCaption() {
        return $this->get('show_image_caption', 1) == 1;
    }

    public function doShowExif() {
        return $this->get('show_exif', 1) == 1;
    }

    public function doShowDate() {
        return $this->get('show_date', 1) == 1;
    }

    public function doShowText() {
        return $this->get('show_text', 1) == 1;
    }

    public function doShowImageFilename() {
        return $this->get('show_image_filename', 0) == 1;
    }
}
