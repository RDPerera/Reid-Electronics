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

if ( !$this->config->getEventsList()->doUseBackButton()) {
    return;
}

$app = JFactory::getApplication();


/* Default Page fallback*/

$active = $app->getMenu()->getActive();
if (NULL == $active) {
    $active = $app->getMenu()->getDefault();
}




$entriesPerPage = $this->config->getEventsList()->getMaxEventsPerPage();
$filterEventsByUserGroup = $this->config->getGeneral()->doHideUserGroupProtectedEventsInList();
/**
 * @var EventgalleryModelEvents $model
 */
$model = JModelLegacy::getInstance('Events', 'EventgalleryModel');
$recursive = $this->config->getCategories()->doShowItemsPerCategoryRecursive();
$user = JFactory::getUser();
$usergroups = JUserHelper::getUserGroups($user->id);
$viewname = $active->query['view'];


$catid = $this->config->getEventsList()->getCatId();
if ($viewname == 'categories') {
    $catid=$this->category->id;
}

$entries = $model->getEntries(true, 0, -1, $this->config->getEventsList()->getTags(), $this->config->getEventsList()->getSortByEvents(), $usergroups, $catid, $recursive, $filterEventsByUserGroup);

$pos = 0;
foreach($entries as $entry) {
    if ($entry->getId() == $this->folder->getId()) {

        break;
    }
    $pos++;
}

$limitstart = $pos - ($pos % $entriesPerPage);



$link = null;

if ($viewname == 'events') {

    $menuItemCatid = $active->getParams()->get('catid', 0);
    $link = "index.php?option=com_eventgallery&Itemid=" . $this->currentItemid;

    if ($limitstart > 0 ) {
        $link .= "&limitstart=". (int)$limitstart;
    }

    $link = JRoute::_($link);
}

if ($viewname == 'categories') {

    // the categories view uses the catid as query parameter, the events view as param

    $menuItemCatid = $active->query['catid'];

    $link = "index.php?option=com_eventgallery&Itemid=" . $this->currentItemid;
    if (isset($this->category) && $this->category->id != 'root') {
        $link .= "&view=categories&catid=".$this->category->id;
    }

    if ($limitstart > 0 ) {
        $link .= "&limitstart=". (int)$limitstart;
    }

    $link = JRoute::_($link);
}



?>
<?php IF ($link != null ) : ?>
    <a class="eventgallery-back-button" href="<?php echo $link; ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENT_BACK_BUTTON_LABEL')?></a>
<?php ENDIF ?>


