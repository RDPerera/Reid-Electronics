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
	// disable the content section of this option if turned off.
	if (!isset($this->showContent) || !$this->config->getEventsList()->doShowImageCaptionOverlay()) {
		$this->showContent = false;
	}
	echo $this->loadSnippet('event/inc/thumb_'.$this->config->getEventsList()->getEventThumbnailLinkMode());




