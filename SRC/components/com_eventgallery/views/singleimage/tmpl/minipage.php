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

$this->document->setMetaData("og:url", JRoute::_( 'index.php?option=com_eventgallery&view=singleimage&layout=minipage&folder='.$this->model->file->getFolderName().'&file='.$this->model->file->getFileName(), false, -1), "property");

?>
<?php IF ($this->config->getSocial()->getSharingLinkType() == 'singleimage_to_event'): ?>
    <script type="text/javascript">
        window.location = "<?php echo JRoute::_('index.php?option=com_eventgallery&view=event&folder='.$this->model->file->getFolderName())?>";
    </script>
<?php ENDIF; ?>

<?php IF ($this->model->file->hasTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption())): ?>
    <div class="well displayname"><?php echo $this->model->file->getTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()); ?></div>
<?php ELSEIF ($this->config->getEvent()->doShowImageFilename()): ?>
    <div class="well displayname"><div class="img-id"><?php echo JText::_('COM_EVENTGALLERY_IMAGE_ID'); ?> <?php echo $this->model->file->getFileName() ?></div></div>
<?php ENDIF ?>

<p>
    <a style="display: block;" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=event&folder='.$this->model->file->getFolderName())?>">
    <img style="display: block; margin: auto;" src="<?php echo  $this->model->file->getImageUrl(600, 600, false) ?>">
    </a>
</p>

<p>
    <a href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=event&folder='.$this->model->file->getFolderName())?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_MINIPAGE_OPEN_EVENT');?></a>
</p>
