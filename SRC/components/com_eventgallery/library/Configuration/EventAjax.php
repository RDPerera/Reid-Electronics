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

class EventAjax extends Configuration
{
    public function getThumbnailSize() {
        return (int)$this->get('event_ajax_list_thumbnail_size', 75);
    }

    public function getNumberOfThumbnailsOnFirstPage() {
        return (int)$this->get('event_ajax_list_number_of_thumbnail_on_first_page', 11);
    }

    public function getNumberOfThumbnailsPerPage() {
        return (int)$this->get('event_ajax_list_number_of_thumbnail_per_page', 22);
    }

    public function doShowInfoInline() {
        return $this->get('event_ajax_show_info_inline', 1) == 1;
    }
}
