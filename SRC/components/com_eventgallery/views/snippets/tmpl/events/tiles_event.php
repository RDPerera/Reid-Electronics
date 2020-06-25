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

<div class="eventgallery-tile">
	<div class="wrapper">
		<a href="<?php echo $link ?>">
			<div class="event-thumbnails">
				<?php
		            $files = $this->entry->getFiles(0, 1, 1);
				?>
				
				<?php
		            /**
		            * @var EventgalleryLibraryFile $file
		            */?>

					<div class="event-thumbnail">
						<?php IF (($this->config->getEventsList()->doHideMainImageForPasswordProtectedEvent() && !$this->entry->isAccessible()) ||
                                  ($this->config->getEventsList()->doHideMainImageForUserGroupProtectedEvent() && !$this->entry->isVisible()) ): ?>
							<img class="locked-event" data-width="1000" data-height="1000" src="<?php echo JUri::root(true)?>/media/com_eventgallery/frontend/images/locked.png">
						<?php ELSE: ?>
							<?php if (isset($files[0])) echo $files[0]->getLazyThumbImgTag(50,50, "", false, null, $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()); ?>
						<?php ENDIF; ?>
					</div>											
			</div>
			<div class="content">				
				<div class="data">
					<?php IF($this->config->getEvent()->doShowDate()):?><div class="date"><small class="muted"><?php echo JHtml::date($this->entry->getDate());?></small></div><?php ENDIF ?>
					<div class="title"><h2><?php echo $this->entry->getDisplayName();?></h2></div>
					<?php IF($this->config->getEvent()->doShowText()):?><div class="text"><?php echo JHtml::_('content.prepare', $this->entry->getIntroText(), '', 'com_eventgallery.events'); ?></div><?php ENDIF ?>
					<?php IF($this->config->getEventsList()->doShowImageCount() || $this->config->getEventsList()->doShowEventHits() ): ?><hr /><?php ENDIF ?>
					<?php IF($this->config->getEventsList()->doShowImageCount()):?><div class="imagecount"><small class="muted"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $this->entry->getFileCount();?></small></div><?php ENDIF ?>
					<?php IF($this->config->getEventsList()->doShowEventHits()):?><div class="eventhits"><small class="muted"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_HITS') ?> <?php echo $this->entry->getHits();?></small></div><?php ENDIF ?>
					<div style="clear:both"></div>
				</div>

			</div>					
		</a>
	</div>	
</div>
