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
require_once JPATH_ROOT.'/components/com_eventgallery/config.php';

class EventgalleryControllerResizeimage extends JControllerLegacy {

    public function display($cachable = false, $urlparams = array()) {
        $file = $this->input->getString('file');
        $folder = $this->input->getString('folder');

        $width = $this->input->getInt('width', -1);

        /**
         * @var EventgalleryLibraryFactoryFile $fileFactory
         */
        $fileFactory = EventgalleryLibraryFactoryFile::getInstance();
        $fileObj = $fileFactory->getFile($folder, $file);
        $folderObj = $fileObj->getFolder();

        $user = JFactory::getUser();
        if (!$user->authorise('core.manage', 'com_eventgallery')){
            if (!$fileObj->isMainImage()) {
                if (!$folderObj->isVisible() || !$folderObj->isAccessible()) {
                    $url = JUri::root().'' . COM_EVENTGALLERY_IMAGE_NO_ACCESS;
                    header("HTTP/1.1 302 Found");
                    header("Location: $url");
                    header('Content-Type: text/plain');
                    header('Connection: close');
                    flush();
                    die();
                }
            }
        }


        $this->renderThumbnail($folder, $file, $width);
        $this->endExecution();
    }

    public function endExecution() {
        die();
    }


    /**
     * This method calculates the image and delivers it to the client.
     *
     * @param $folder
     * @param $file
     * @param $width
     * @param $height
     * @param $doFindMatingSize defined if we try to find a size in the list of possible images sized
     * @param $doCache
     * @param $doWatermarking
     * @param $doSharping
     *
     */
    public function renderThumbnail($folder, $file, $width = -1, $doFindMatingSize = true, $doCache = true, $doWatermarking = true, $doSharping = true) {

        EventgalleryLibraryCommonImageprocessor::renderThumbnail($folder, $file, $width, $doFindMatingSize, $doCache, $doWatermarking, $doSharping);
    }


    
    


}


