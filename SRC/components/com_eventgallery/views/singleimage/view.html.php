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
jimport('joomla.application.categories');



class EventgalleryViewSingleimage extends EventgalleryLibraryCommonView
{
    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    public $config;
    public $state;
    public $currentItemid;
    public $messageForm;
    /**
     * @var EventgalleryLibraryFolder
     */
    public $folder;

    /**
     * @var EventgalleryLibraryFile
     */
    public $file;

    public $position;
    public $imageset;
    public $model;
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


        $model = $this->getModel('singleimage');
        $model->getData($app->input->getString('folder'), $app->input->getString('file'));

        $this->model = $model;
        $this->file = $model->file;

        if (!is_object($this->file) || $this->file->isPublished() != 1) {
            throw new Exception(JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NO_PUBLISHED_MESSAGE'), 404);
        }

        $this->folder = $this->file->getFolder();
        $this->position = $model->position;

        /** Default Page fallback
         * @var JMenu $active
        */
        $active = $app->getMenu()->getActive();
        if (NULL == $active) {
            $params->merge($app->getMenu()->getDefault()->getParams());
            $active = $app->getMenu()->getDefault();
        }
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance($params);

        $this->currentItemid = $active->id;

        if (!is_object($this->folder) || $this->folder->isPublished() != 1) {
            throw new Exception(JText::_('COM_EVENTGALLERY_EVENT_NO_PUBLISHED_MESSAGE'), 404);
        }


        if (!isset($this->file) || strlen($this->file->getFileName()) == 0 || $this->file->isPublished() != 1) {
            throw new Exception(JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NO_PUBLISHED_MESSAGE'), 404);
        }

        if (!$this->folder->isVisible()) {
            $user = JFactory::getUser();
            if ($user->guest) {

                $redirectUrl = JRoute::_("index.php?option=com_eventgallery&view=singleimage&folder=" . $this->folder->getFolderName()."&file=".$this->file->getFileName(), false);
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
        $accessAllowed = EventgalleryHelpersFolderprotection::isAccessAllowed($this->folder, $password);
        if (!$accessAllowed) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=password&folder=" . $this->folder->getFolderName(), false)
            );
        }

        // remove the password from the url
        if (strlen($password)>0) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=singleimage&folder=" . $this->folder->getFolderName()."&file=".$this->file->getFileName(), false)
            );
        }

        $this->imageset = $this->folder->getImageTypeSet();

        $pathway = $app->getPathWay();

        if ($active->query['view']=='categories') {
            EventgalleryHelpersCategories::addCategoryPathToPathway($pathway, $app->input->getInt('catid', 0), $this->folder->getCategoryId(), $this->currentItemid);
        }

        $pathway->addItem(
            $this->folder->getDisplayName(), JRoute::_('index.php?option=com_eventgallery&view=event&folder=' . $this->folder->getFolderName())
        );
        $pathway->addItem($model->position . ' / ' . $model->overallcount);

        if ($this->document->getType() == 'raw') {
            $this->setLayout($app->input->getString('layout','minipage'));
        } else {
            $this->_prepareDocument();
            $this->addOpenGraphTags($this->folder, $this->file, $this->document, $this->config);

            EventgalleryHelpersMedialoader::load($this->config);
        }

        if ($this->getLayout() == 'report') {
            $this->messageForm = $model->getMessageForm();
            $this->messageForm->bind($app->input->post->getArray());
        }

        parent::display($tpl);
    }

    /**
     * @param $folder EventgalleryLibraryFolder
     * @param $file EventgalleryLibraryFile
     * @param $document JDocument
     * @param $config \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected function addOpenGraphTags($folder, $file, $document, $config) {
        $titles = [];
        if (strlen($folder->getDisplayName())>0) {
            $titles[] = $folder->getDisplayName();
        } else {
            $titles[] = $file->getFolderName();
        }


        if (strlen($file->getTitle($this->config->getEvent()->doShowImageFilename(), $this->config->getEvent()->doShowExif(), $this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption()))>0) {
            $titles[] = $file->getPlainTextTitle($this->config->getEvent()->doShowImageTitle(), $this->config->getEvent()->doShowImageCaption());
        } else {
            $titles[] = $file->getFileName();
        }

        $titles = array_filter($titles, function($value) { return strlen($value)>0; });
        $title = implode(" - ", $titles);

        $description = $file->getFileCaption();
        if (strlen($description) == 0) {
            $description = $folder->getText();
        }


        $document->setMetaData("og:title", strip_tags ($title), "property");
        $document->setMetaData("og:description", strip_tags ($description), "property");


        if (!$config->getSocial()->doUseSocialSharingButton()) {
            return;
        }

        $document->setMetaData("og:image", $file->getSharingImageUrl(), "property");
        $document->setMetaData("og:type", "website", "property");

    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app    = JFactory::getApplication();
        $menus  = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu)
        {
            $this->config->set('page_heading', $this->config->getMenuItem()->getPageTitle());
        }


        $title = $this->config->getMenuItem()->getPageTitle();

        if ($this->folder->getDisplayName()) {
            $title = $this->folder->getDisplayName();
        }

        $title .= " - ".$this->position.' / '.$this->folder->getFileCount();


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

        if ($this->document) {

            $description = $this->folder->getMetadata()->get('metadesc');
            if (!empty($description)) {
                $this->document->setDescription($description);
            }
            elseif ($this->folder->getText())
            {
                $this->document->setDescription(strip_tags($this->folder->getText()));
            }
            elseif (!$this->folder->getText() && $this->config->getMenuItem()->getMetaDescription())
            {
                $this->document->setDescription($this->config->getMenuItem()->getMetaDescription());
            }

            if ($this->config->getMenuItem()->getMetaKeywords())
            {
                $this->document->setMetaData('keywords', $this->config->getMenuItem()->getMetaKeywords());
            } else {
                $this->document->setMetaData('keywords', $this->folder->getMetadata()->get('metakey'));
            }

            if ($this->config->getMenuItem()->getRobots())
            {
                $this->document->setMetaData('robots', $this->config->getMenuItem()->getRobots());
            }

            $this->document->setTitle($title);
        }
    }

}
