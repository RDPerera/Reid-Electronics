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
 * @var EventgalleryLibraryFolder $folder
 * @var EventgalleryLibraryFile $file
 */


$folder = $this->model->folder;
$file = $this->model->file;
$this->displayname = $folder->getDisplayName();
$this->subject = $folder->getDisplayName()." ";
if ($this->config->getSocial()->getSharingLinkType() == 'event') {
	$this->link =  JUri::root() . trim( JRoute::_( 'index.php?option=com_eventgallery&view=event&folder='.$file->getFolderName() ), '/' );
} else {
	$this->link =  JUri::root() . trim( JRoute::_( 'index.php?option=com_eventgallery&view=singleimage&layout=minipage&folder='.$file->getFolderName().'&file='.$file->getFileName() ), '/' );
}
$this->image = $file->getImageUrl(500,500, false);
$this->twitter = rawurlencode($this->displayname);


// handle picasa images
$this->imageurl = $file->getSharingImageUrl();

if ($folder->doAllowDownloadAtAll($this->config)) {
    $downloadimageurl = $file->getOriginalImageUrl();
    $allowDownload = true;
} else {
    $downloadimageurl = $this->config->getSocial()->getRedirectURL();
    $allowDownload = false;
}


$this->imagename = $file->getFileName();




?>
<?php IF ($this->config->getSocial()->doUseSocialSharingButton() && $folder->isShareable()):?>
<a href="#" class="social-share-button-close"><i class="egfa egfa-2x egfa-share-alt-square"></i></a>

    <?php IF ($folder->isPublicVisible() && $folder->hasPassword() == false):?>

        <?php IF ($this->config->getSocial()->doUseFacebook() && $folder->getAttribs()->get('use_social_sharing_facebook',1)==1):?>

            <a href="#" id="facebook-post-image"><i class="egfa egfa-2x egfa-facebook-square" alt="Facebook" title="Facebook"></i></a>
            <script type="text/javascript">
                (function(jQuery){

                    var shareFunction = function(e) {
                        e.preventDefault();

                        window.open(
                            'https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($this->link)?>',
                            'facebook-share-dialog',
                            'width=626,height=436');
                    };

                    jQuery('#facebook-post-image').click(shareFunction);
                })(eventgallery.jQuery);

            </script>

        <?php ENDIF ?>

        <?php IF ($this->config->getSocial()->doUsePinterest() && $folder->getAttribs()->get('use_social_sharing_pinterest', 1)==1):?>
            <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($this->link)?>&media=<?php echo urlencode($this->image)?>&description=<?php echo rawurlencode($this->displayname)?>"
                onclick="window.open(this.href,
              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');return false;"><i class="egfa egfa-2x egfa-pinterest-square" alt="Pinterest" title="Pinterest"></i></a>
        <?php ENDIF ?>

    <?php ENDIF ?>

    <?php IF ($this->config->getSocial()->doUseTwitter() && $folder->getAttribs()->get('use_social_sharing_twitter',1)==1):?>
        <a href="https://twitter.com/intent/tweet?source=webclient&text=<?php echo $this->twitter?>"
           onclick="window.open('http://twitter.com/share?url=<?php echo urlencode($this->link)?>&text=<?php echo urlencode($this->twitter)?>', 'twitterwindow', 'height=450, width=550, toolbar=0, location=1, menubar=0, directories=0, scrollbars=auto'); return false;"
        ><i class="egfa egfa-2x egfa-twitter-square" alt="Twitter" title="Twitter"></i></a>
    <?php ENDIF ?>


	<?php IF ($this->config->getSocial()->doUseEmail() && $folder->getAttribs()->get('use_social_sharing_email', 1)==1):?>
		<a href="mailto:?subject=<?php echo rawurlencode($this->subject) ?>&body=<?php echo urlencode($this->link)?>" onclick=""> <i class="egfa egfa-2x egfa-envelope-square" alt="Mail" title="Mail"></i></a>
	<?php ENDIF ?>

	<?php IF ($this->config->getSocial()->doUseDownload() && $folder->getAttribs()->get('use_social_sharing_download', 1)==1):?>
		<a <?php IF($allowDownload):?> download="<?php echo $this->imagename;?>"<?PHP ENDIF;?> href="<?php echo $downloadimageurl; ?>" lt="Download" title="Download"><i class="egfa egfa-2x egfa-cloud-download" alt="Download" title="Download"></i></a>
	<?php ENDIF ?>

    <?php IF ($this->config->getSocial()->doUseImageReporting() && $folder->getAttribs()->get('use_image_reporting', 1)==1):?>
        <a href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=report&folder='.$file->getFolderName().'&file='.$file->getFileName())?>"><i class="egfa egfa-2x egfa-exclamation-triangle" title="<?php echo JText::_('COM_EVENTGALLERY_MESSAGES_REPORT_SHARING_BUTTON_TITLE')?>" alt="<?php echo JText::_('COM_EVENTGALLERY_MESSAGES_REPORT_SHARING_BUTTON_TITLE')?>"></i></a>
    <?php ENDIF ?>
<?php ENDIF ?>
