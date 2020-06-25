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

$app = JFactory::getApplication();
/**
 * @var EventgalleryLibraryFile $entry
 */
$entry = $this->entry;

/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */
?><?php IF (!$entry->hasUrl()):?><a class="event-thumbnail <?php if (isset($this->cssClass)) {echo $this->cssClass;}?>" href="<?php echo $entry->getImageUrl(null, null, true); ?>"
   title="<?php echo htmlspecialchars($entry->getPlainTextTitle($this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()), ENT_COMPAT, 'UTF-8') ?>"
   data-pid="<?php echo $entry->getId();?>" data-width="<?php echo $entry->getWidth();?>" data-height="<?php echo $entry->getHeight();?>"
   data-title="<?php echo rawurlencode($entry->getLightBoxTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption())) ?>"
   data-gid="gallery<?php if (isset($this->rel)) {echo $this->rel;} echo md5($entry->getFolderName()); ?>"
   data-eg-lightbox="gallery"><?php echo $entry->getLazyThumbImgTag(50, 50, "", false, null, $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption() ); ?>
   <?php echo $this->loadSnippet('event/inc/thumbs_content'); ?>
   <div class="eventgallery-icon-container"><?php echo $this->loadSnippet('event/inc/icons'); ?></div>
</a>
<?php ELSE: ?>
<a class="event-thumbnail <?php if (isset($this->cssClass)) {echo $this->cssClass;}?>" href="<?php echo $entry->getUrl();?>"
   title="<?php echo htmlspecialchars($entry->getPlainTextTitle($this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()), ENT_COMPAT, 'UTF-8') ?>">
   <?php echo $entry->getLazyThumbImgTag(50, 50, "", false, null, $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()); ?>
   <?php echo $this->loadSnippet('event/inc/thumbs_content'); ?>
</a>
<?php ENDIF;

