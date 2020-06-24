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
 * @var EventgalleryLibraryFolder $folder
 */
$folder = $this->folder;

$safeFolderName = JFolder::makeSafe($folder->getFolderName());
$validFolderName = strcmp($folder->getFolderName(), $safeFolderName) == 0 ?  true : false;

if (!$folder->supportsFileUpload()) {
    echo JText::_('COM_EVENTGALLERY_EVENT_UPLOAD_NOT_SUPPORTED');
    return;
}
?>

<?php IF (!$validFolderName): ?>
    <h2><?php echo JText::sprintf('COM_EVENTGALLERY_SYNC_DATABASE_SYNC_ERROR_FOLDERNAME', $folder->getFolderName(), $safeFolderName);?></h2>
<?php ELSE: ?>
        <legend><?php echo $folder->getFolderName()?></legend>
        <div id="uploader"
             data-max-file-size="30000000"
             data-upload-url="<?php echo JRoute::_("index.php?option=com_eventgallery&view=upload&task=upload.upload&folder=".htmlspecialchars($folder->getFolderName(), ENT_QUOTES, 'UTF-8')."&format=raw&",false); ?>"
             data-i18n-COM_EVENTGALLERY_EVENT_UPLOAD_FILES_TO_UPLOAD="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_UPLOAD_FILES_TO_UPLOAD' ); ?>"
             data-i18n-COM_EVENTGALLERY_EVENT_UPLOAD_PENDING="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_UPLOAD_PENDING' ); ?>";
             data-i18n-COM_EVENTGALLERY_EVENT_UPLOAD_FINISHED="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_UPLOAD_FINISHED' ); ?>";
        ></div>
<?PHP ENDIF ?>


<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">

<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>

</form>
