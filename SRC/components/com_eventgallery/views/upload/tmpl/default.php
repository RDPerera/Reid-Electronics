<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

?>

<div><a href="<?php echo $this->returnUrl ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENT_UPLOAD_RETURN_TO_PREV_PAGE')?></a></div>

<?php
if (EVENTGALLERY_EXTENDED) {
    include(JPATH_ADMINISTRATOR . '/components/com_eventgallery/views/upload/tmpl/default.php');
} else {
    include(JPATH_ADMINISTRATOR . '/components/com_eventgallery/views/snippets/tmpl/eventgallery_extended_hint.php');
}
