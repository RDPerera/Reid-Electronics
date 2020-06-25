<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


//jimport( 'joomla.application.component.helper' );

class EventgalleryModelSingleimage extends JModelForm
{

    /**
     * @var EventgalleryLibraryFolder
     */
    var $folder = NULL;
    /**
     * @var EventgalleryLibraryFile
     */
    var $file = NULL;
    var $nextFile = NULL;
    var $prevFile = NULL;
    var $firstFile = NULL;
    var $lastFile = NULL;
    var $position = 0;
    var $overallcount = 0;
    var $_dataLoaded = false;
    var $currentLimitStart = 0;

    var $useEventPaging;

    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;

    function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        $this->useEventPaging = $this->config->getEventsList()->doEventPaging();
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $this->config->getEventsList()->getMaxImagesPerPage(), 'int');
        $this->setState('limit', $limit);
    }


    function getData($foldername, $filename)
    {
        if (!$this->_dataLoaded) {
            $this->loadFolder($foldername);

            if ($this->folder == null) {
                return null;
            }

            // picasa files are not stored in the database
            $files = $this->folder->getFiles(0, -1, 0, $this->config->getEventsList()->getSortFilesByColumn(), $this->config->getEventsList()->getSortFilesByDirection());


            $i = 0;
            $filesCount = count($files);

            /**
             * @var EventgalleryLibraryFile $file
             */
            foreach ($files as $file) {
                if (strcmp($file->getFileName(), $filename) == 0) {
                    /**
                     * Update Hits
                     */

                    $file->countHit();

                    /**
                     * Set Data
                     */
                    $this->_dataLoaded = true;
                    $this->file = $file;
                    $this->prevFile = $files[max(0, $i - 1)];
                    $this->nextFile = $files[min($filesCount - 1, $i + 1)];
                    $this->lastFile = $files[count($files) - 1];
                    $this->firstFile = $files[0];
                    $this->overallcount = count($files);
                    $this->position = $i + 1;

                    if ($this->getState('limit') > 0 && $this->useEventPaging) {
                        $this->currentLimitStart = $i - ($i % $this->getState('limit'));
                    } else {
                        $this->currentLimitStart = 0;
                    }


                }


                $i++;
            }

        }
    }

    function loadFolder($foldername)
    {
        if (!$this->folder) {
            /**
             * @var EventgalleryLibraryFactoryFolder $folderFactory
             */
            $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
            $this->folder = $folderFactory->getFolder($foldername);
        }
    }

    /**
     * Abstract method for getting the form from the model.
     *
     * @param   array $data Data for the form.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  \JForm|boolean  A \JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // TODO: Implement getForm() method.
    }

    public function getMessageForm($data = array(), $loadData = true)
    {
        $xmlPath = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_eventgallery'
        . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR;

        $form = JForm::getInstance('message', $xmlPath . 'message.xml');

        if ($loadData) {
            $form->bind($data);
        }

        return $form;
    }
}
