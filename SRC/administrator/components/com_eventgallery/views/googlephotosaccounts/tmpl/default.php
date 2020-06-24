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
JHtml::_('formbehavior.chosen', 'select');
?>


<form action="<?php echo JRoute::_('index.php?option=com_eventgallery&view=googlephotosaccount'); ?>"
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
                <div class="clearfix"> </div>
                <p>
                    <?php echo JText::_('COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNTS_HELP'); ?>
                </p>
                <p>
                    <?php echo JText::_('COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNTS_VIDEO'); ?>
                </p>

            <p class="well">
                <strong>
                    <?php echo JText::_('COM_EVENTGALLERY_GOOGLEPHOTOS_LIMITATIONS_HINT'); ?>
                </strong>
            </p>


            <table class="table">
                    <thead>
                        <tr>
                            <th width="20">
                                <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                            </th>
                            <th class="nowrap" width="1%">

                            </th>
                            <th width="1%">
                                <?php echo JText::_( 'COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNT_ORDER' ); ?>
                                <?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'googlephotosaccount.saveorder' ); ?>
                            </th>
                            <th>
                                <?php echo JText::_( 'COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNT_NAME' ); ?>
                            </th>
                            <th>
                                <?php echo JText::_( 'COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNT_CLIENTID' ); ?>
                            </th>
                            <th>
                                <?php echo JText::_( 'COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNT_COMPLETED' ); ?>
                            </th>
                        </tr>
                    </thead>


                    <tbody>
                    <?php $n=count($this->items); foreach ($this->items as $i => $item) :
                    /**
                     * @var EventgalleryLibraryGooglephotosaccount $item;
                     */
                    ?>

                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center">
                                <?php echo JHtml::_('grid.id', $i, $item->getId()); ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a title="<?php echo JText::_('COM_EVENTGALLERY_BUTTON_EDIT_DESC'); ?>" class="btn btn-micro" href="<?php echo
                                        JRoute::_('index.php?option=com_eventgallery&task=googlephotosaccount.edit&id='.$item->getId()); ?>">
                                    <i class="icon-edit"></i></a>
                                </div>
                            </td>
                            <td class="order nowrap">
                                <div class="input-prepend">
                                    <span class="add-on"><?php echo $this->pagination->orderUpIcon( $i, true, 'googlephotosaccount.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span>
                                    <span class="add-on"><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'googlephotosaccount.orderdown', 'JLIB_HTML_MOVE_UP', true ); ?></span>
                                    <input title="<?php echo JText::_('COM_EVENTGALLERY_COMMON_ORDERING')?>" class="width-40 text-area-order" type="text" name="order[]" size="3"  value="<?php echo $item->getOrdering(); ?>" />
                                </div>
                            </td>
                            <td>
                                    <?php echo $this->escape($item->getName()) ?><br>
                                    <small><?php echo $this->escape($item->getDescription()) ?></small>
                            </td>
                            <td>
                                <?php echo $this->escape($item->getClientId()) ?>
                            </td>
                            <td>

                                <?php echo JText::_($this->escape($item->isUsable())?"JYES":"JNO") ?>
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

