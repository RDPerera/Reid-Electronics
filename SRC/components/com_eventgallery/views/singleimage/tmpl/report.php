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
 * @var EventgalleryLibraryFile $file
 */

$file = $this->model->file;
?>

<h2><?php echo JText::_('COM_EVENTGALLERY_MESSAGES_REPORT_HEADLINE'); ?></h2>
<p><?php echo JText::_('COM_EVENTGALLERY_MESSAGES_REPORT_DESCRIPTION'); ?></p>

<p><?php echo $file->getThumbImgTag(100,100,'thumbnail', false, null, false, false)?></p>

<form action="<?php echo JRoute::_("index.php?option=com_eventgallery&view=singleimage&task=saveReport&layout=report") ?>"
      method="post" class="form-validate form-horizontal">

    <fieldset class="userdata-fieldset">
        <?php foreach ($this->messageForm->getFieldset() as $field): ?>
            <div class="control-group">
                <?php if (!$field->hidden): ?>
                    <?php echo $field->label; ?>
                <?php endif; ?>
                <div class="controls">
                    <?php echo $field->input; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </fieldset>
    <fieldset>
        <div class="form-actions">
            <input type="submit" class="validate btn btn-primary"
                   value="<?php echo JText::_('COM_EVENTGALLERY_MESSAGES_SEND_REPORT') ?>"/>
        </div>
    </fieldset>
    <input type="hidden" name="folder" value="<?php echo $file->getFolderName(); ?>">
    <input type="hidden" name="file" value="<?php echo $file->getFileName(); ?>">
    <?php echo JHtml::_('form.token'); ?>
</form>
