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
?>



<div id="events">
    <div>
        <ul class="events">
        <?php $count=0; foreach($this->entries as $entry) :?>
            <?php $this->entry = $entry;?>
            <?php
                if (isset($this->rendermode) && $this->rendermode == 'module') {
                    $link = JRoute::_(EventgalleryHelpersRoute::createEventRoute($this->entry->getFolderName(), $this->entry->getTags(), $this->entry->getCategoryId(), $this->config->getMenuItem()->getMenuItemId()));
                } else {
                    $link = "index.php?option=com_eventgallery&view=event&folder=" . $this->entry->getFolderName() . "&Itemid=" . $this->currentItemid;
                    if (isset($this->category) && $this->category->id != 'root') {
                        $link .= "&catid=" . $this->category->id;
                    }
                    $link = JRoute::_($link);
                }
            ?>
            <li class="event">
                <a href="<?php echo $link ?>">
                    <?php IF($this->config->getEvent()->doShowDate()):?><span class="date"><?php echo JHtml::date($this->entry->getDate());?></span><?php ENDIF ?>
                    <span class="displayname"><?php echo $this->entry->getDisplayName();?></span>
                </a>
            </li>
        <?php ENDFOREACH; ?>
        </ul>
    </div>

    <?php IF (isset($this->pageNav)): ?>
        <form method="post" name="adminForm">

            <div class="pagination">
            <div class="counter pull-right"><?php echo $this->pageNav->getPagesCounter(); ?></div>
            <div class="float_left"><?php echo $this->pageNav->getPagesLinks(); ?></div>
            <div class="clear"></div>
            </div>

        </form>
    <?php ENDIF; ?>
</div>
