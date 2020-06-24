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

<style type="text/css">
	.eventgallery-row {
		margin-bottom: 20px;
	}
</style>
<div class="row">
	<div id="j-sidebar-container" class="span2 col-md-2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10 col-md-10">
        <div class="container">

            <div class="row-fluid eventgallery-row">
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS_DESC')?>
                        <a href="<?php echo str_replace('administrator/', '', JUri::root().'index.php?option=com_eventgallery&view=events')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS_PREVIEW')?></a>
                    </p>
                    <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=events')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS_DESC')?></p>
                    <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=orders')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_DESC')?></p>
                    <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=documentation')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION')?></a>
                </div>
            </div>

            <?php IF (!EVENTGALLERY_EXTENDED):?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="alert alert-success">
                           <?php echo $this->loadSnippet('eventgallery_extended_hint')?>
                        </div>
                    </div>
                </div>
            <?php ENDIF ?>

            <hr>

            <div class="row-fluid eventgallery-row">

                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS')?></h2>

                    <dl class="dl-horizontal">
                        <dt><?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_EVENTS')?></dt><dd><?php echo $this->get('FolderCount')?></dd>
                        <dt><?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_FILES')?></dt><dd><?php echo $this->get('FileCount')?> (<?php echo $this->get('FileTotalCount')?>, <a title="<?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_FILES_CLEANUP_TITLE')?>" href="<?php echo JRoute::_('index.php?option=com_eventgallery&task=eventgallery.removeOldFiles')?>"><?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_FILES_CLEANUP')?></a>)</dd>
                        <dt><?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_CARTS')?></dt><dd><?php echo $this->get('CartCount')?> (<a title="<?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_CARTS_CLEANUP_TITLE')?>" href="<?php echo JRoute::_('index.php?option=com_eventgallery&task=eventgallery.removeOldCarts')?>"><?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_CARTS_CLEANUP')?></a>) </dd>
                        <dt><?php echo JText::_('COM_EVENTGALLERY_OVERVIEW_STATISTICS_ORDERS')?></dt><dd><?php echo $this->get('OrderCount')?></dd>
                    </dl>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUPPORT_TITLE')?></h2>
                    <p>
                        <?php echo JText::_('COM_EVENTGALLERY_SUPPORT_CONTENT1')?>
                         <a target="_blank" href="https://www.svenbluege.de">www.svenbluege.de</a>.
                    </p>
                    <p>
                        <?php echo JText::_('COM_EVENTGALLERY_SUPPORT_CONTENT2')?>
                         <a target="_blank" href="https://www.svenbluege.de/support"><?php echo JText::_('COM_EVENTGALLERY_SUPPORT_TRACKER')?></a>
                    </p>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_NEWSLETTER_TITLE')?></h2>
                    <p>
                        <?php echo JText::_('COM_EVENTGALLERY_NEWSLETTER_SIGNUP')?><br>
                    </p>
                    <p>
                        <a class="btn btn-primary" target="_blank" href="https://eepurl.com/dt-rfH"><?php echo JText::_('COM_EVENTGALLERY_NEWSLETTER_SUBMIT')?></a>
                    </p>
                </div>

            </div>

            <div style="clear:both;"></div>

            <hr>

            <div class="row-fluid eventgallery-row">
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_GETTING_STARTED_HEADLINE')?></h2>
                    <p>
                        <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/U9PBZVm8K8Q" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>
                    </p>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_WATERMARKS')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_WATERMARKS_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=watermarks')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_WATERMARKS')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_CATEGORIES')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_CATEGORIES_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_categories&extension=com_eventgallery')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_CATEGORIES')?></a>
                </div>
            </div>

            <div style="clear:both;"></div>
            <hr>

            <div class="row-fluid eventgallery-row">
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=imagetypes')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPEGROUPS')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPEGROUPS_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=imagetypes')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=imagetypesets')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS')?></a>
                </div>
            </div>

            <div class="row-fluid eventgallery-row">

                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=surcharges')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=shippingmethods')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=paymentmethods')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS')?></a>
                </div>
            </div>

            <div class="row-fluid eventgallery-row">

                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EMAILTEMPLATES')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EMAILTEMPLATES_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=emailtemplates')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EMAILTEMPLATES')?></a>
                </div>
                <div class="span4">
                    <h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERSTATUSES')?></h2>
                    <p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERSTATUSES_DESC')?></p>
                    <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=orderstatuses')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERSTATUSES')?></a>
                </div>
                <div class="span4">
                </div>
            </div>

        </div>

        <form action="<?php echo JRoute::_('index.php'); ?>" method="post" id="adminForm" name="adminForm">
            <input type="hidden" name="option" value="com_eventgallery" />
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>
