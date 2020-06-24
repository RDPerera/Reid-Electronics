<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
/**
 *  @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */

?>

<p class="well">
    <?php echo JText::_('COM_EVENTGALLERY_SYNC_START2_DESC'); ?>
</p>

<div id="filesync"
     data-csrf-token="<?php echo JSession::getFormToken()?>"
     data-load-folders-url="<?php echo JRoute::_('index.php?option=com_eventgallery&format=raw&task=sync.init', false);?>"
     data-file-sync-url="<?php echo JRoute::_('index.php?option=com_eventgallery&format=raw&task=sync.processFiles', false);?>"
     data-folder-sync-url="<?php echo JRoute::_('index.php?option=com_eventgallery&format=raw&task=sync.processFolder', false);?>"
     data-file-batch-size="<?php echo $this->config->getStorage()->getMaxItemsPerBatch() ?>"
     data-i18n-COM_EVENTGALLERY_SYNC_CHECK_ALL="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_CHECK_ALL' ); ?>"
     data-i18n-COM_EVENTGALLERY_SYNC_CHECK_NONE="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_CHECK_NONE' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STOP_QUEUE="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_STOP_QUEUE' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_ERROR_HEADLINE="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_ERROR_HEADLINE' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP1="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_STEP1' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP2="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_STEP2' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP2_HINT="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_SELECT_AN_EVENT_HINT' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP2_BUTTON_LABEL="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_FOLDERS' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP3="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_STEP3' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP3_HINT="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_FILE_SYNC_HINT_DESC' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP3_BUTTON_LABEL="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_FILES' ); ?>";
></div>



<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="option" value="com_eventgallery" />
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>