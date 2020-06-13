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
 * @var EventgalleryLibraryFactoryGooglephotosaccount $accountFactory
 *
 */
$accountFactory = EventgalleryLibraryFactoryGooglephotosaccount::getInstance();
$accounts = $accountFactory->getUsableGooglePhotosAccounts();


?>

<div id="j-main-container">

    <p>

    <?php echo JText::_( 'COM_EVENTGALLERY_GOOGLEPHOTOSSYNC_DESC' ); ?>

     </p>

    <p>
        <?php echo JText::_( 'COM_EVENTGALLERY_GOOGLEPHOTOSSYNC_WARNING' ); ?>

    </p>

    <form id="upload" action="<?php echo JRoute::_("index.php?option=com_eventgallery&task=googlephotossync.sync",false); ?>" method="POST">

        <p>
            <strong><?php echo JText::_('COM_EVENTGALLERY_GOOGLEPHOTOSSYNC_ACCOUNT');?></strong>
        </p>

        <select name="googlephotosaccountid">
            <?php
            foreach($accounts as $account) {
                /**
                 * @var EventgalleryLibraryGooglephotosaccount $account
                 */

                echo  '<option value="'.$account->getId().'">'.$account->getName().'</option>';

            }
            ?>
            </select>

        <p>
            <?php echo JText::_("COM_EVENTGALLERY_GOOGLEPHOTOSSYNC_DRYRUN_LABEL");?> <input type="checkbox" name="dryrun" checked="checked">
        </p>

        <div id="submitbutton">
            <button class="btn btn-danger" type="submit"><?php echo JText::_( 'COM_EVENTGALLERY_GOOGLEPHOTOSSYNC_SYNCBUTTON_LABEL' ); ?></button>
        </div>

    </form>



    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">

        <input type="hidden" name="option" value="com_eventgallery" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </form>
</div>
