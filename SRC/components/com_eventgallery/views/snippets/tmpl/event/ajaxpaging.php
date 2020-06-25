<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */
?>

<?php echo $this->loadSnippet('event/ajaxpaging_script'); ?>

<div class="ajaxpaging">

    <?php
    $pageCount = 0;
    $imageCount = 0;
    $imagesOnPage = 0;
    $imagesFirstPage = $this->config->getEventAjax()->getNumberOfThumbnailsOnFirstPage();
    $imagesPerPage = $this->config->getEventAjax()->getNumberOfThumbnailsPerPage();

    $pagesCount = ceil((count($this->entries) - $imagesFirstPage) / $imagesPerPage) + 1;
    ?>

    <?php echo $this->loadSnippet('imageset/orderimages'); ?>

    <div class="navigation">
    	<?php IF (!$this->config->getEventAjax()->doShowInfoInline()): ?>
        	<div class="information">
        		<?php echo $this->loadSnippet('event/ajaxpaging_information'); ?>
        	</div>
        <?php ENDIF; ?>
        <div id="pagerContainer">
            <div id="thumbs">
                <div id="pageContainer">

                    <div id="page<?php echo $pageCount++; ?>" class="page">

                        <?php foreach ($this->entries as $entry) :/** @var EventgalleryLibraryFile $entry */ ?>
                        <?php IF ($pageCount == 1 && $imageCount == 0): ?>
	                        <?php IF ($this->config->getEventAjax()->doShowInfoInline()): ?>
	                        	<?php echo $this->loadSnippet('event/ajaxpaging_information'); ?>
	                        <?php ENDIF; ?>
                            <div class="ajax-thumbnails">
                        <?php ENDIF; ?>


                        <?php $imagesOnPage++ ?>

                            <div class="ajax-thumbnail-container" id="image<?php echo $imageCount++; ?>">
                                <a longdesc="<?php echo $entry->getImageUrl(NULL, NULL, true); ?>"
                                   class="ajax-thumbnail thumbnail"
                                   href="<?php echo $entry->getImageUrl(NULL, NULL, true); ?>"
                                   title="<?php echo htmlspecialchars($entry->getPlainTextTitle($this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()), ENT_COMPAT, 'UTF-8'); ?>"
                                   rel="<?php echo $entry->getImageUrl(50, 50, false, false); ?>"
                                   data-folder="<?php echo $entry->getFolderName(); ?>"
                                   data-file="<?php echo $entry->getFileName(); ?>"
                                   <?php IF ($entry->getFolder()->getFolderType()->getId() == 2):?>
                                      data-farm="<?php echo $entry->getFarmId(); ?>"
                                      data-server="<?php echo $entry->getServerId(); ?>"
                                      data-secret="<?php echo $entry->getSecret(); ?>"
                                      data-secret_o="<?php echo $entry->getSecretO(); ?>"
                                      data-secret_h="<?php echo $entry->getSecretH(); ?>"
                                      data-secret_k="<?php echo $entry->getSecretK(); ?>"
                                   <?php ENDIF; ?>
                                   <?php IF ($this->config->getCart()->doShowCartConnector()):?>
                                       data-cart-connector-link="<?php echo rawurlencode(EventgalleryHelpersCartconnector::getLink($entry->getFolderName(), $entry->getFileName()));?>"
                                   <?php ENDIF ?>
                                   data-id="folder=<?php echo urlencode($entry->getFolderName()) ?>&amp;file=<?php echo urlencode($entry->getFileName()) ?>"
                                   data-width="<?php echo $entry->getWidth(); ?>"
                                   data-height="<?php echo $entry->getHeight(); ?>"
                                   data-description="<?php if ($this->config->getEvent()->doShowDate()) {
                                       echo JHtml::date($this->folder->getDate()) . ' - ';
                                   }
                                   echo htmlentities($this->folder->getDisplayName() . "<br> " . JText::_(
                                           'COM_EVENTGALLERY_EVENT_AJAX_IMAGE_CAPTION_IMAGE'
                                       ) . " $imageCount " . JText::_('COM_EVENTGALLERY_EVENT_AJAX_IMAGE_CAPTION_OF')
                                       . " $this->entriesCount", ENT_QUOTES, "UTF-8") ?>
                                            <br /><?php echo rawurlencode($entry->getTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption())); ?>"
                                   data-title="<?php echo rawurlencode($entry->getLightBoxTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption())); ?>"
                                   <?php IF ($this->config->getSocial()->doUseSocialSharingButton()):?>
                                        data-social-sharing-link="<?php echo rawurlencode(JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=share&folder='.$entry->getFolderName().'&file='.$entry->getFileName()."&Itemid=".$this->currentItemid.'&format=raw', false) ); ?>"
                                   <?php ENDIF ?>
                                    >
                                    <?php echo $entry->getThumbImgTag(
                                        $this->config->getEventAjax()->getThumbnailSize(),
                                        $this->config->getEventAjax()->getThumbnailSize(),
                                        '',
                                        true,
                                        null,
                                        $this->config->getEvent()->doShowImageTitle(),
                                        $this->config->getEvent()->doShowImageCaption()
                                    ); ?>
                                </a>
                            </div>

                        <?php IF (($imagesOnPage % $imagesPerPage == 0)
                        || ($pageCount == 1
                            && ($imagesOnPage % $imagesFirstPage == 0))): ?>
                        </div>
                    </div>
                    <div id="page<?php echo $pageCount++; ?>" class="page">
                        <div class="ajax-thumbnails">
                        <?php $imagesOnPage = 0; ?>
                        <?php ENDIF; ?>

                        <?php endforeach ?>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="pagination">
            <ul id="count"></ul>
        </div>

    </div>

    <div class="image">

        <div id="bigimageContainer">
            <img src="<?php echo JUri::base() . 'media/com_eventgallery/frontend/images/loading.gif' ?>" alt=""
                 id="bigImage"/>
            <span id="bigImageDescription" class="img_overlay img_overlay_fotos overlay_3"><?php echo JText::_(
                    'COM_EVENTGALLERY_EVENT_AJAX_LOADING'
                ) ?></span>
        </div>

    </div>
    <div style="clear:both"></div>

</div>
