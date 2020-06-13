<?php

$link =  JRoute::_('index.php?option=com_eventgallery&view=categories&catid='.$this->subCategory->id);
$event = $this->subCategory->event;
/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */
$categoryTitle = $this->escape(EventgalleryHelpersCategories::getCategoryTitle($this->subCategory));
if($this->config->getCategories()->doShowItemsPerCategoryCountRecursive()) {
    $numItems = $this->subCategory->getNumItems(true);
}
else {
    $numItems = $this->subCategory->getNumItems(false);
}

if ($event == null) {
    return;
}

?>

<div class="item-container">
    <div class="wrapper">
        <a href="<?php echo $link ?>">
            <div class="content">
                <div class="event-thumbnails">
                    <?php
                    $files = $event->getFiles(0, 1, 1);
                    ?>

                    <?php
                    /**
                     * @var EventgalleryLibraryFile $file
                     */?>

                    <div class="event-thumbnail">
                        <?php IF (($this->config->getEventsList()->doHideMainImageForPasswordProtectedEvent() && !$event->isAccessible()) ||
                            ($this->config->getEventsList()->doHideMainImageForUserGroupProtectedEvent() && !$event->isVisible()) ): ?>
                            <img class="locked-event" data-width="1000" data-height="1000" src="<?php echo JUri::root(true)?>/media/com_eventgallery/frontend/images/locked.png">
                        <?php ELSE: ?>
                            <?php if (isset($files[0])) echo $files[0]->getLazyThumbImgTag(50,50, "", false, null, $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()); ?>
                        <?php ENDIF; ?>
                    </div>
                </div>

                <div class="data">
                    <div class="title"><h2><?php echo $categoryTitle;?></h2></div>
                    <?php if($this->config->getCategories()->doShowItemsPerCategoryCount()): ?><div class="imagecount">(<?php echo $numItems;?>)</div><?php ENDIF ?>
                    <div style="clear:both"></div>
                </div>

            </div>
        </a>
    </div>
</div>
