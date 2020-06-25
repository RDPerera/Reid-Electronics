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

$app          = \JFactory::getApplication();
$user         = \JFactory::getUser();
/**
 * @var EventgalleryLibraryFolder $folder
 */
$folder = $this->folder;

$frontediting = ($app->isClient('site') && $app->get('frontediting', 1) && !$user->guest);
$eventEditing = $user->authorise('core.edit', 'com_eventgallery');

$uri    = JUri::getInstance();
$encodedReturnUrl = base64_encode($uri->toString(array('scheme', 'host', 'port', 'path', 'query')));


if ($frontediting && $eventEditing && $folder->supportsFileUpload()) {
    ?>
    <div>
        <a class="btn" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=upload&return='.$encodedReturnUrl.'&folderid='.$folder->getId());?>"><?php echo JText::_("COM_EVENTGALLERY_EVENT_UPLOAD_BUTTON")?></a>
    </div>
    <?php
}

