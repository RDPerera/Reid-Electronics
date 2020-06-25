<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */

echo $this->loadSnippet('cart');

echo $this->loadSnippet('event/uploadbutton');

echo $this->loadSnippet('event/backbutton');

echo $this->loadSnippet('social');

$this->config->set('event_thumb_link_mode','singleimagepage');

$this->config->set('event_image_list_thumbnail_height', $this->config->getEventPageable()->getThumbnailHeight());
$this->config->set('event_image_list_thumbnail_jitter', $this->config->getEventPageable()->getThumbnailJitter());
$this->config->set('event_image_list_thumbnail_first_item_height', $this->config->getEventPageable()->getFirstItemRowHeight());
$this->config->set('show_image_caption_overlay', $this->config->getEventPageable()->doShowImageCaptionOverlay());

echo $this->loadSnippet('event/imagelist');

echo $this->loadSnippet('footer_disclaimer');