<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
/**
 * @var EventgalleryLibraryFactoryFile $fileFactory
 */
$fileFactory = EventgalleryLibraryFactoryFile::getInstance();
?>



<form action="<?php echo JRoute::_('index.php?option=com_eventgallery&view=messages'); ?>"
      method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div id="j-sidebar-container" class="span2 col-md-2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10 col-md-10">
            <div id="filter-bar" class="btn-toolbar">
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            </div>

            <p><?php echo JText::_('COM_EVENTGALLERY_MESSAGES_DESCRIPTION'); ?></p>

            <div class="clearfix"> </div>

            <table class="table">
                <thead>
                    <tr>
                        <th width="20">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th>

                        </th>
                        <th>

                        </th>

                    </tr>
                </thead>
                <tbody>
                <?php foreach ($this->items as $i => $item) :
                    $file = null;
                    try {
                        $file = $fileFactory->getFile($item->folder, $item->file);
                    } catch (Exception $e) {

                    }
                ?>

                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>

                        <td>
                            <?php IF ($file != null): ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=file&id=' . $file->getId())?>">
                                <?php echo $file->getThumbImgTag(150, 150, 'eventgallery-image', false, null, false, false); ?>
                                </a>
                            <?php ENDIF;?>

                        </td>
                        <td>
                            <small><?php echo $this->escape($item->created) ?></small><br />
                            <small><?php echo $this->escape($item->email) ?></small><br />
                            <?php echo $this->escape($item->message) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pagination pagination-toolbar">
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        </div>
    </div>
    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>" />


</form>
