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
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration|EventgalleryLibraryCommonView $this
 * @var EventgalleryLibraryFile $file
 * @var EventgalleryModelSingleimage $model
 * @var JCategoryNode $category
 */


$file = $this->file;

$category = $this->category;
$model = $this->model;

?>

<?php echo $this->loadSnippet('social'); ?>

<script type="text/javascript">

(function(jQuery){


    jQuery(document).keyup(function (event) {

        if (event.keyCode == 37) {
            // left
            if (Eventgallery.lightbox.isOpen() === true) {
                if (Eventgallery.lightbox.getCurrentSlide().thumbnailContainer.getAttribute('data-eg-lightbox').indexOf('cart')>-1) {
                    return;
                }
            }
            if (jQuery('#prev_image').first() != null) {
                document.location.href = jQuery('#prev_image').attr('href');
            }

        } else if (event.keyCode == 39) {
            // right
            if (Eventgallery.lightbox.isOpen()) {
                if (Eventgallery.lightbox.getCurrentSlide().thumbnailContainer.getAttribute('data-eg-lightbox').indexOf('cart')>-1) {
                    return;
                }
            }
            if (jQuery('#next_image')) {
                document.location.href = jQuery('#next_image').attr('href');
            }
        }
    });

})(eventgallery.jQuery);

</script>

<?php  echo  $this->loadSnippet("cart"); ?>

<div id="singleimage">

    <?php IF ($this->config->getEvent()->doShowDate()): ?>
        <h4 class="date">
            <?php echo JHtml::date($this->folder->getDate()) ?>
        </h4>
    <?php ENDIF ?>

    <?php echo $this->config->getEventsList()->renderEventHeadTag($this->folder->getDisplayName(), 'displayname'); ?>

    <a name="image"></a>

    <div class="btn-group">
        <a class="btn singleimage-overview" href="<?php

        $link = "index.php?option=com_eventgallery&view=event&folder=" . $this->folder->getFolderName();
        if ($model->currentLimitStart > 0) {
            $link .= "&limitstart=" . $model->currentLimitStart;
        }
        if (isset($category) && $category->id != 'root') {
            $link .= "&catid=" . $category->id;
        } $link = JRoute::_($link);

        echo $link;  ?>" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_OVERVIEW') ?>"><i class="egfa egfa-list"></i></a>

        <?php IF (  $model->firstFile && $model->firstFile->getId() != $file->getId()): ?>
            <a class="btn singleimage-first" href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=singleimage&folder=" . $model->firstFile->getFolderName()
                . "&file=" . $model->firstFile->getFileName()
            ) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_START') ?>"><i
                    class="egfa egfa-fast-backward"></i></a>
        <?php ENDIF ?>

        <?php IF ($model->prevFile && $model->prevFile->getId() != $file->getId()): ?>
            <a class="btn singleimage-prev" id="prev_image" href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=singleimage&folder=" . $model->prevFile->getFolderName() . "&file="
                . $model->prevFile->getFileName()
            ) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_PREV') ?>"><i
                    class="egfa egfa-backward"></i></a>
        <?php ENDIF ?>

        <?php IF ($model->nextFile && $model->nextFile->getId() != $file->getId()): ?>
            <a class="btn singleimage-next" id="next_image" href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=singleimage&folder=" . $model->nextFile->getFolderName() . "&file="
                . $model->nextFile->getFileName()
            ) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_NEXT') ?>"><i
                    class="egfa egfa-forward"></i></a>
        <?php ENDIF ?>

        <?php IF ($model->lastFile && $model->lastFile->getId() != $file->getId()): ?>
            <a class="btn singleimage-last" href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=singleimage&folder=" . $model->lastFile->getFolderName() . "&file="
                . $model->lastFile->getFileName()
            ) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_END') ?>"><i
                    class="egfa egfa-fast-forward"></i></a>
        <?php ENDIF ?>

        <a class="btn singleimage-zoom" href="<?php echo $file->getImageUrl(NULL, NULL, true) ?>"><i class="egfa egfa-search-plus"></i></a>


        <?php IF ($this->folder->isCartable()  && $this->config->getCart()->doUseCart()): ?>
            <a href="#" data-id="<?php echo "folder=" . urlencode($file->getFolderName()) . "&file=" . urlencode($file->getFileName()); ?>" title="<?php echo JText::_(
                'COM_EVENTGALLERY_CART_ITEM_ADD2CART'
            ) ?>" class="btn btn-primary eventgallery-openAdd2cart"><?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGE') ?></a>
        <?php ENDIF ?>

        <?php IF ($this->folder->isCartable() && $this->config->getCart()->doShowCartConnector()): ?>
            <a href="<?php echo EventgalleryHelpersCartconnector::getLink(
                $file->getFolderName(), $file->getFileName()
            ); ?>" class="btn button-cart-connector" title="<?php echo JText::_('COM_EVENTGALLERY_CART_CONNECTOR') ?>"
               data-folder="<?php echo $file->getFolderName() ?>"
               data-file="<?php echo $file->getFileName(); ?>"><i class="egfa egfa-cart-plus"></i></a>
        <?php ENDIF ?>

		<?php IF ($this->config->getSocial()->doUseSocialSharingButton() && $this->folder->isShareable()):?>
			<a class="btn social-share-button social-share-button-open" rel="nofollow" href="#" data-link="<?php echo JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=share&folder='.$file->getFolderName().'&file='.$file->getFileName().'&format=raw'); ?>" class="social-share-button" title="<?php echo JText::_('COM_EVENTGALLERY_SOCIAL_SHARE')?>" ><i class="egfa egfa-share-alt"></i></a>
		<?php ENDIF ?>

        <?php IF ($file->getHitCount()>0 && $this->config->getEventPageable()->doShowImageHits()): ?>
            <div class="btn singleimage-hits"><?php echo JText::_(
                    'COM_EVENTGALLERY_SINGLEIMAGE_HITS'
                ) ?> <?php echo $file->getHitCount() ?></div>
        <?php ENDIF ?>
    </div>

    
    <div class="singleimage eventgallery-imagelist" data-rowheight="100" data-rowheightjitter="0" data-firstimagerowheight="1" data-dofilllastrow="true">
        <a class="thumbnail"
           id="bigimagelink"
           data-pid="<?php echo $file->getId();?>" data-width="<?php echo $file->getWidth();?>" data-height="<?php echo $file->getHeight();?>"
           data-gid="singleimage"
           data-title="<?php echo rawurlencode($file->getLightBoxTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption())) ?>"
           data-eg-lightbox="gallery"
           href="<?php echo $file->getImageUrl(NULL, NULL, true) ?>"
           >
            <?php echo $file->getLazyThumbImgTag(100, 100, "", false, null, $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()); ?>
        </a>



        <?php IF ($file->hasTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption())): ?>
            <div class="well displayname">
                <?php echo $file->getTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()); ?>

            </div>
        <?php ELSEIF ($this->config->getEvent()->doShowImageFilename()): ?>
        	<div class="well displayname"><div class="img-id"><?php echo JText::_('COM_EVENTGALLERY_IMAGE_ID'); ?> <?php echo $file->getFileName() ?></div></div>
        <?php ENDIF ?>
    </div>

</div>

<?php echo $this->loadSnippet('footer_disclaimer'); ?>