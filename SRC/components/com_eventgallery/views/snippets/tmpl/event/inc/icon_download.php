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

$file = $this->entry;
$folder = $file->getFolder();
if ($folder->doAllowDownloadAtAll($this->config)) {
    $downloadimageurl = $file->getOriginalImageUrl();
    $allowdownload = true;
} else {
    $downloadimageurl = $this->config->getSocial()->getRedirectURL();
    $allowdownload = false;
}

IF ($file->isShareable() && $this->config->getSocial()->doUseSocialSharingButton()):
    IF ($this->config->getSocial()->doShowDedicatedDownloadButton() && $this->config->getSocial()->doUseDownload() && $folder->getAttribs()->get('use_social_sharing_download', 1)==1):?>
        <span class="eg-download" <?php IF ($allowdownload):?>data-download="<?php echo $file->getFileName();?>"<?php ENDIF;?> data-href="<?php echo $downloadimageurl; ?>"><i class="egfa egfa-2x egfa-cloud-download" alt="Download" title="Download"></i></span>
    <?php ENDIF;
ENDIF;
