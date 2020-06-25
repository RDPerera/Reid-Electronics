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

class EventsImagelist extends Configuration
{
    public function getThumbnailHeight() {
        return (int)$this->get('events_image_list_thumbnail_height', 250);
    }

    public function getThumbnailJitter() {
        return (int)$this->get('events_image_list_thumbnail_jitter', 50);
    }

    public function getFirstItemRowHeight() {
        return (int)$this->get('events_image_list_thumbnail_first_item_height', 2);
    }
}
