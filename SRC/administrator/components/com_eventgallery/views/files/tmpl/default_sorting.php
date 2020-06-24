<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.form.form' );
JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
$this->form = JForm::getInstance('com_eventgallery.filesorting','filesorting');

?>
<form>
    <input type="hidden" name="option" value="com_eventgallery" />
    <input type="hidden" name="task" value="files.sort" />
    <input type="hidden" name="folderid" value="<?php echo $this->folder->getId(); ?>" />
    <?php echo JHtml::_('form.token'); ?>

    <div style="font-size: 12px; padding: 10px;" id="collapseModalSorting">
        <div class="modal-body">
        <?php echo $this->loadSnippet('formfields'); ?>
        </div>
        <div class="modal-footer">
            <button class="btn" type="button" data-dismiss="modal">
                <?php echo JText::_('JCANCEL'); ?>
            </button>
            <button class="btn btn-primary" type="submit">
                <?php echo JText::_('COM_EVENTGALLERY_FILE_SORTING_APPLY'); ?>
            </button>
        </div>
    </div>
</form>