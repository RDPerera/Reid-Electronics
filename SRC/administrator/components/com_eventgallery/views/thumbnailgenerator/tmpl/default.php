<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
?>

<div id="thumbnailcreator"
     data-csrf-token="<?php echo JSession::getFormToken()?>"
     data-i18n-COM_EVENTGALLERY_THUMBNAILGENERATOR_START2_DESC="<?php echo JText::_( 'COM_EVENTGALLERY_THUMBNAILGENERATOR_START2_DESC' ); ?>";
     data-i18n-COM_EVENTGALLERY_THUMBNAILGENERATOR_REFRESHETAGS_DESC="<?php echo JText::_( 'COM_EVENTGALLERY_THUMBNAILGENERATOR_REFRESHETAGS_DESC' ); ?>";
     data-load-folders-url="<?php echo JRoute::_('index.php?option=com_eventgallery&format=raw&task=thumbnailgenerator.init', false);?>"
     data-file-sync-url="<?php echo JRoute::_('index.php?option=com_eventgallery&format=raw&task=thumbnailgenerator.processfile', false);?>"
     data-folder-sync-url="<?php echo JRoute::_('index.php?option=com_eventgallery&format=raw&task=thumbnailgenerator.processfolder', false);?>"
     data-file-batch-size="5"
     data-i18n-COM_EVENTGALLERY_SYNC_CHECK_ALL="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_CHECK_ALL' ); ?>"
     data-i18n-COM_EVENTGALLERY_SYNC_CHECK_NONE="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_CHECK_NONE' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STOP_QUEUE="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_STOP_QUEUE' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_ERROR_HEADLINE="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_ERROR_HEADLINE' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP1="<?php echo JText::_( 'COM_EVENTGALLERY_SYNC_STEP1' ); ?>";
     data-i18n-COM_EVENTGALLERY_SYNC_STEP2_HINT="<?php echo JText::_( 'COM_EVENTGALLERY_THUMBNAILGENERATOR_GETMISSINGTHUMBNAILS' ); ?>";
     data-i18n-COM_EVENTGALLERY_THUMBNAILGENERATOR_GETMISSINGTHUMBNAILS="<?php echo JText::_( 'COM_EVENTGALLERY_THUMBNAILGENERATOR_GETMISSINGTHUMBNAILS' ); ?>";
     data-i18n-COM_EVENTGALLERY_THUMBNAILGENERATOR_START="<?php echo JText::_( 'COM_EVENTGALLERY_THUMBNAILGENERATOR_START' ); ?>";
     data-i18n-COM_EVENTGALLERY_THUMBNAILGENERATOR_START_THUMBNAILCREATION="<?php echo JText::_( 'COM_EVENTGALLERY_THUMBNAILGENERATOR_START_THUMBNAILCREATION' ); ?>";
></div>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="option" value="com_eventgallery" />
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>