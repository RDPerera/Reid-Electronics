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
jimport('joomla.application.pathway');
jimport('joomla.html.pagination');
jimport('joomla.application.categories');


/** @noinspection PhpUndefinedClassInspection */
class EventgalleryViewEvent extends EventgalleryLibraryCommonView
{
    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;
    protected $state;
    protected $pageNav;
    protected $entries;
    protected $entriesCount;
    protected $currentItemid;
    /**
     * @var EventgalleryLibraryFolder
     */
    protected $folder;
    protected $imageset;
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
        $this->state = $this->get('State');
        $params = $app->getParams();


        /* Default Page fallback*/
        $active = $app->getMenu()->getActive();
        if (NULL == $active) {
            $params->merge($app->getMenu()->getDefault()->getParams());
            $active = $app->getMenu()->getDefault();
            //just in case the default menu item sets something else.
            $this->setLayout('default');
        }

        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance($params);

        $this->currentItemid = $active->id;

        if ($this->getLayout()=='default' && $layout = $this->config->getEventsList()->getEventLayout()) {
            //override the layout with the menu item setting in case we link directly to an event
            if ($active != null && isset($active->query['layout']) && $active->component=='com_eventgallery')  {
                $layout = $active->query['layout'];
            }
            $this->setLayout($layout);
        }

        $this->catid = $app->input->getInt('catid', null);
        if ($this->catid == 0) {
            $this->catid = 'root';
        }

        $options = array();

        /**
         * @var JCategories $categories
         */
        $categories = JCategories::getInstance('Eventgallery', $options);

        /**
         * @var JCategoryNode $root
         */

        if (null != $this->catid) {
            $this->category = $categories->get($this->catid);
        }

        if ($this->category!=null && $this->category->published!=1) {
            throw new Exception(JText::_('JGLOBAL_CATEGORY_NOT_FOUND'), 404);
        }



        // legacy fix since I renamed default to pageable
        if ($this->getLayout()=='default') {
            $this->setLayout('imagelist');
        }

        /**
         * @var EventgalleryModelEvent $model
         */
        $model = $this->getModel('event');


        $pageNav = $model->getPagination($app->input->getString('folder', ''));


        if ($this->getLayout() == 'ajaxpaging' || !$this->config->getEventsList()->doEventPaging()) {
            $entries = $model->getEntries($app->input->getString('folder',''), -1, -1);
        } else {
            $entries = $model->getEntries($app->input->getString('folder', ''));
        }

        $titleEntries = $model->getEntries($app->input->getString('folder', ''), -1, -1, true);

        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
        $folder = $folderFactory->getFolder($app->input->getString('folder', ''));

 		if (!is_object($folder) || $folder->isPublished() != 1) {
            throw new Exception(JText::_('COM_EVENTGALLERY_EVENT_NO_PUBLISHED_MESSAGE'), 404);
        }



        if (!$folder->isVisible()) {
            $user = JFactory::getUser();
            if ($user->guest) {

                $redirectUrl = JRoute::_("index.php?option=com_eventgallery&view=event&folder=" . $folder->getFolderName(), false);
                $redirectUrl = urlencode(base64_encode($redirectUrl));
                $redirectUrl = '&return='.$redirectUrl;
                $joomlaLoginUrl = 'index.php?option=com_users&view=login';
                $finalUrl = JRoute::_($joomlaLoginUrl . $redirectUrl, false);
                $app->redirect($finalUrl);
            } else {
                $this->setLayout('noaccess');
            }
        }

        $password = $app->input->getString('password', '');
        $accessAllowed = EventgalleryHelpersFolderprotection::isAccessAllowed($folder, $password);

        if (!$accessAllowed) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=password&folder=" . $folder->getFolderName(), false)
            );
        }

        // remove the password from the url.
        if (strlen($password)>0) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=event&folder=" . $folder->getFolderName(), false)
            );
        }

        if( ($this->config->getEventsList()->doShuffleImages() || $folder->doShuffleImages())
        	&& !$this->config->getEventsList()->doEventPaging()) {
            $allowedLayouts = Array(
                    'ajaxpaging',
                    'imagelist',
                    'simple',
                    'tiles'
                );
            if (in_array($this->getLayout(), $allowedLayouts)) {
                shuffle($entries);
            }
        }

        $folder->countHits();

        $this->pageNav = $pageNav;
        $this->entries = $entries;
        $this->entriesCount = count($entries);
        $this->titleEntries = $titleEntries;

        $this->folder = $folder;

        $this->imageset = $folder->getImageTypeSet();

        /**
         * @var JPathway $pathway
         */
        $pathway = $app->getPathway();

        // add the category but avoid adding a category which is defined in the menu item
        if ($active->query['view']=='categories' && $active->query['catid'] != $folder->getCategoryId()) {
            $rootCategoryId = 0;
            $skipRoot = false;
            if ( isset($active->query['catid']) ) {
                $rootCategoryId = $active->query['catid'];
                $skipRoot = true;
            }
            EventgalleryHelpersCategories::addCategoryPathToPathway($pathway, $rootCategoryId, $folder->getCategoryId(), $this->currentItemid, $skipRoot);
        }

        // add the event
        $pathway->addItem($folder->getDisplayName());

        $this->_prepareDocument();
        $this->addOpenGraphTags($this->folder, $this->document, $this->config);

        EventgalleryHelpersMedialoader::load($this->config);

        parent::display($tpl);
    }

    /**
     * @param $folder EventgalleryLibraryFolder
     * @param $document JDocument
     * @param $config \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected function addOpenGraphTags($folder, $document, $config) {
        $document->setMetaData("og:title", strip_tags ($folder->getDisplayName()), "property");
        $document->setMetaData("og:description", strip_tags ($folder->getText()), "property");


        if (!$config->getSocial()->doUseSocialSharingButton()) {
            return;
        }

        $files = $folder->getFiles(0,1,1);
        if (count($files)>0) {
            $file = $files[0];
            $document->setMetaData("og:image", $file->getSharingImageUrl(), "property");
            $document->setMetaData("og:type", "website", "property");
      }

    }
    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app    = JFactory::getApplication();
        $menus  = $app->getMenu();
        $menu = $menus->getActive();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        if ($menu)
        {
            $this->config->set('page_heading', $this->config->getMenuItem()->getPageTitle());
        }

        $title = $this->config->getMenuItem()->getPageTitle();

        // checks for empty title or sets the folder description if
        // the current menu item is not the event view. This avoids
        // having the title of them menu item on all sub events
        if ( empty($title) ||
            (isset($menu->query['view']) && strcmp($menu->query['view'],'event')!=0)
           ) {
            $title = $this->folder->getDisplayName();
        }

        // Check for empty title and add site name if param is set
        if (empty($title)) {
            $title = $app->get('sitename');
        }
        elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        }
        elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }
        if (empty($title)) {
            $title = $this->folder->getDisplayName();
        }
        $this->document->setTitle($title);


        $description = $title = $this->config->getMenuItem()->getMetaDescription();

        if (empty($description) || ( isset($menu->query['view']) && strcmp($menu->query['view'],'event')!=0)) {
            $description = $this->folder->getMetadata()->get('metadesc');

            // set the text of the folder as description if the meta desc is not set
            // or the menu item does not link to a single event
            if (empty($description)) {
                $description = strip_tags($this->folder->getText());
            }
        }

        $this->document->setDescription($description);


        $keys = $this->folder->getMetadata()->get('metakey');

        if ($this->config->getMenuItem()->getMetaKeywords())
        {
            $keys = $this->config->getMenuItem()->getPageTitle();
        }

        $this->document->setMetaData('keywords', $keys);

        if ($this->config->getMenuItem()->getRobots())
        {
            $this->document->setMetaData('robots', $this->config->getMenuItem()->getRobots());
        }
    }
}


