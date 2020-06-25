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


if (isset($this->rendermode) && $this->rendermode == 'module') {
	$link = JRoute::_(EventgalleryHelpersRoute::createEventRoute($this->entry->getFolderName(), $this->entry->getTags(), $this->entry->getCategoryId(), $this->config->getMenuItem()->getMenuItemId()));
} else {
    $link = "index.php?option=com_eventgallery&view=event&folder=" . $this->entry->getFolderName() . "&Itemid=" . $this->currentItemid;
    if (isset($this->category) && $this->category->id != 'root') {
        $link .= "&catid=" . $this->category->id;
    }
    $link = JRoute::_($link);
}
?>

<div class="item-container item-container-big">
	<div class="item item_first">
		<a href="<?php echo $link ?>">
			<div class="eg-content">
				<div class="data">
					<?php IF($this->config->getEvent()->doShowDate()):?><div class="date"><?php echo JHtml::date($this->entry->getDate());?></div><?php ENDIF ?>
					<div class="title"><?php echo $this->entry->getDisplayName();?></div>
					<?php IF($this->config->getEvent()->doShowText()):?><div class="text"><?php echo JHtml::_('content.prepare', $this->entry->getIntroText(), '', 'com_eventgallery.event'); ?></div><?php ENDIF ?>
					<?php IF($this->config->getEventsList()->doShowImageCount()):?><div class="imagecount"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $this->entry->getFileCount();?></div><?php ENDIF ?>
					<?php IF($this->config->getEventsList()->doShowEventHits()):?><div class="eventhits"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_HITS') ?> <?php echo $this->entry->getHits();?></div><?php ENDIF ?>
				</div>
				
				<div class="images event-thumbnails">
                    <?php
                        $files = $this->entry->getFiles(0, 1, 1);
                    ?>

                    <?php foreach($files as $file):
                        /**
                        * @var EventgalleryLibraryFile $file
                        */?>

                        <div class="event-thumbnail">
                            <?php IF (($this->config->getEventsList()->doHideMainImageForPasswordProtectedEvent() && !$this->entry->isAccessible()) ||
                                      ($this->config->getEventsList()->doHideMainImageForUserGroupProtectedEvent() && !$this->entry->isVisible()) ): ?>
                                <img class="locked-event" data-width="1000" data-height="1000" src="<?php echo JUri::root(true)?>/media/com_eventgallery/frontend/images/locked.png">
                            <?php ELSE: ?>
                                <?php echo $file->getLazyThumbImgTag(50,50, "", true, null, $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()); ?>
                            <?php ENDIF; ?>
                        </div>
                    <?php ENDFOREACH?>
					<div style="clear:both"></div>
				</div>
			</div>	
		</a>					
	</div>
</div>