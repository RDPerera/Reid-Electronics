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

class Slider extends Configuration
{
    /**
     * @return bool
     */
    public function isSliderEnabled() {
        return $this->get('use_slider', 0) == 1;
    }

    /**
     * @return int
     */
    public function getNumberOfRows() {
        return (int)$this->get('slider_rows', 1);
    }

    /**
     * @return int
     */
    public function getAutoplay() {
        return (int)$this->get('slider_autoplay', 0);
    }

    /**
     * @return bool
     */
    public function doShowNav() {
        return $this->get('slider_shownav', 1) == 1;
    }

    public function getJavaScriptConfigurationString($elementSelector) {
        $sliderAttributes = '';

        if ($this->isSliderEnabled()) {
            $sliderAttributes = ' data-slider="1"';
            $sliderAttributes .= ' data-slider-autoplay="' . $this->getAutoplay() . '"';
            $sliderAttributes .= ' data-slider-show-nav="' . ($this->doShowNav()?'true':'false') . '"';
            $sliderAttributes .= ' data-slider-number-of-rows-per-slide="' . $this->getNumberOfRows() . '"';
            $sliderAttributes .= ' data-slider-slides-elements-selector="'.$elementSelector.'"';
        }

        return $sliderAttributes;
    }
}
