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
JLoader::register('EventgalleryHelpersBackendmedialoader', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/backendmedialoader.php');

class EventgalleryViewUpload extends EventgalleryLibraryCommonView
{
    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;
    protected $folder;
    protected $returnUrl;

    /**
     * @var JDocument
     */
    public $document;

    function display($tpl = NULL)
    {

        EventgalleryHelpersBackendmedialoader::load();

        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();
        $this->returnUrl = base64_decode($app->input->getString('return'));

        $this->folder = $this->get('Item');
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance($app->getParams());

        $user = JFactory::getUser();

        $canUpload = $user->authorise('core.edit', "com_eventgallery");
        if (!$canUpload) {
            $app->enqueueMessage(JText::_('COM_EVENTGALLERY_EVENT_UPLOAD_NO_PERMISSION'), 'error');
            $app->redirect(JRoute::_('index.php?view=events'));
        }

        parent::display($tpl);
    }

}
