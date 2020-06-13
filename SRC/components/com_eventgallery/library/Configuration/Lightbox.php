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

class Lightbox extends Configuration
{
    public function getNavigationFadeDelay() {
        return $this->get('lightbox_navgation_fade_delay', 0);
    }

    public function doUseSwipe() {
        return $this->get('lightbox_enable_swipe', 1) == 1;
    }

    public function doUseSlideshow() {
        return $this->get('use_lightbox_slideshow', 1) == 1;
    }

    public function doUseAutoplay() {
        return $this->get('use_lightbox_slideshow_autoplay', 0) == 1;
    }

    public function getSlideshowSpeed() {
        return (int)$this->get('lightbox_slideshow_speed', 3000);
    }

    public function doPreventRightClick() {
        return $this->get('lightbox_prevent_right_click', 0) == 1;
    }
}
