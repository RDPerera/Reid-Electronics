<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access

// shows a thumbnail for an event.
defined('_JEXEC') or die('Restricted access');

/**
 * @var EventgalleryLibraryFile $file
 */
$file = $this->entry;
$folder = $file->getFolder();

if (isset($this->rendermode) && $this->rendermode == 'module') {
    $link = JRoute::_(EventgalleryHelpersRoute::createEventRoute($folder->getFolderName(), $folder->getTags(), $folder->getCategoryId(), $this->config->getMenuItem()->getMenuItemId()));
} else {
    $link = "index.php?option=com_eventgallery&view=event&folder=" . $folder->getFolderName() . "&Itemid=" . $this->currentItemid;
    if (isset($this->category) && $this->category->id != 'root') {
        $link .= "&catid=" . $this->category->id;
    }
    $link = JRoute::_($link);
}

?><a class="event-thumbnail <?php if (isset($this->cssClass)) {echo $this->cssClass;}?>" href="<?php echo $link; ?>">
    <?php echo $file->getLazyThumbImgTag(50, 50, "", false, null, false, false); ?>
    <div class="event-content">
        <div class="data">
            <?php IF($this->config->getEvent()->doShowDate()):?><div class="date"><?php echo JHtml::date($folder->getDate());?></div><?php ENDIF ?>
            <div class="title"><?php echo $folder->getDisplayName();?></div>
            <?php IF($this->config->getEvent()->doShowText()):?><div class="text"><?php echo JHtml::_('content.prepare', $folder->getIntroText(), '', 'com_eventgallery.event'); ?></div><?php ENDIF ?>
            <?php IF($this->config->getEventsList()->doShowImageCount()):?><div class="imagecount"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $folder->getFileCount();?></div><?php ENDIF ?>
            <?php IF($this->config->getEventsList()->doShowEventHits()):?><div class="eventhits"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_HITS') ?> <?php echo $folder->getHits();?></div><?php ENDIF ?>
        </div>
    </div>

</a>
