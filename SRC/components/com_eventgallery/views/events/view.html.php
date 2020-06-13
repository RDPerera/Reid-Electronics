<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;


jimport('joomla.application.component.view');


class EventgalleryViewEvents extends EventgalleryLibraryCommonView
{


    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    public $config;
    protected $entries;
    protected $fileCount;
    protected $folderCount;
    protected $eventModel;
    protected $pageNav;
    protected $entriesCount;
    protected $currentItemid;

    protected $folder;


    /**
     * @var JDocument
     */
    public $document;


    function display($tpl = NULL)
    {
        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();

        $params = $app->getParams();

        /* Default Page fallback*/
        $active = $app->getMenu()->getActive();
        if (NULL == $active) {
            $params->merge($app->getMenu()->getDefault()->getParams());
            $active = $app->getMenu()->getDefault();
        }

        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance($params);
        $this->currentItemid = $active->id;

        $entriesPerPage = $this->config->getEventsList()->getMaxEventsPerPage();

        $filterEventsByUserGroup = $this->config->getGeneral()->doHideUserGroupProtectedEventsInList();

        $model = $this->getModel('events');
        $eventModel = JModelLegacy::getInstance('Event', 'EventgalleryModel');

        $recursive = $this->config->getCategories()->doShowItemsPerCategoryRecursive();

        $user = JFactory::getUser();
        $usergroups = JUserHelper::getUserGroups($user->id);

        $entries = $model->getEntries(true, $app->input->getInt('start', 0), $entriesPerPage, $this->config->getEventsList()->getTags(), $this->config->getEventsList()->getSortByEvents(), $usergroups, $this->config->getEventsList()->getCatId(), $recursive, $filterEventsByUserGroup);

        $this->pageNav = $model->getPagination();

        $this->entries = $entries;
        $this->eventModel = $eventModel;

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app    = JFactory::getApplication();
        $menus  = $app->getMenu();
        $title  = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if($menu)
        {
            $this->config->set('page_heading', $this->config->getMenuItem()->getPageTitle());
        }

        $title = $this->config->getMenuItem()->getPageTitle();
        if (empty($title)) {
            $title = $app->get('sitename');
        }
        elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        }
        elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->config->getMenuItem()->getMetaDescription())
        {
            $this->document->setDescription($this->config->getMenuItem()->getMetaDescription());
        }

        if ($this->config->getMenuItem()->getMetaKeywords())
        {
            $this->document->setMetaData('keywords', $this->config->getMenuItem()->getMetaKeywords());
        }

        if ($this->config->getMenuItem()->getRobots())
        {
            $this->document->setMetaData('robots', $this->config->getMenuItem()->getRobots());
        }
    }

}
