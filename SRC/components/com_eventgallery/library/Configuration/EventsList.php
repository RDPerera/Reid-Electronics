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

class EventsList extends Configuration
{
    public function getMaxEventsPerPage() {
        return (int)$this->get('max_events_per_page', 12);
    }

    public function getMaxImagesPerPage() {
        return (int)$this->get('max_images_per_page', 20);
    }

    public function getTags() {
        return $this->get('tags', []);
    }

    public function getEventLayout() {
        return $this->get('event_layout', '_:imagelist');
    }

    public function getEventsLayout() {
        return $this->get('events_layout', 'default');
    }

    public function getGreetings() {
        return $this->get('greetings', '');
    }

    public function getSortByEvents() {
        return $this->get('sort_events_by', 'ordering');
    }

    public function getSortFilesByColumn() {
        return $this->get('sort_files_by_column', 'ordering');
    }

    public function getSortFilesByDirection() {
        return $this->get('sort_files_by_direction', 'ASC');
    }

    public function doShuffleImages() {
        return $this->get('shuffle_images', 0) == 1;
    }

    public function getCatId() {
        return $this->get('catid', '');
    }

    public function doShowImageCount() {
        return $this->get('show_imagecount', 1) == 1;
    }

    public function doShowEventHits() {
        return $this->get('show_eventhits', 0) == 1;
    }

    public function doShowImageCaptionOverlay() {
        return $this->get('show_image_caption_overlay', 0) == 1;
    }

    public function doEventPaging() {
        return $this->get('use_event_paging', 0) == 1;
    }

    public function doHideMainImageForPasswordProtectedEvent() {
        return $this->get('hide_mainimage_for_password_protected_event', 0) == 1;
    }

    public function doHideMainImageForUserGroupProtectedEvent() {
        return $this->get('hide_mainimage_for_usergroup_protected_event', 0) == 1;
    }

    public function doUseBackButton() {
        return $this->get('use_back_button', 0) == 1;
    }

    public function renderEventHeadTag($text, $cssClass) {
        $tagname = $this->get('event_header_tag', 'H1');
        return "<$tagname class=\"$cssClass\">$text</$tagname>";
    }

    /**
     * Is used to steer the thumbnail rendering internally.
     *
     * @return mixed
     */
    public function getEventThumbnailLinkMode() {
        return $this->get('event_thumb_link_mode', 'lightbox');
    }
}