<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventgalleryControllerUpload extends EventgalleryController
{

    public function __construct($config = array())
    {
        $language = JFactory::getLanguage();
        $extension = 'com_eventgallery';
        $language_tag = $language->getTag(); // loads the current language-tag
        $base_dir = JPATH_ADMINISTRATOR;
        $language->load($extension, $base_dir, $language_tag, true);
        $base_dir = JPATH_COMPONENT_ADMINISTRATOR;
        $language->load($extension, $base_dir, $language_tag, true);

        parent::__construct($config);
    }

    public function getModel($name = 'Upload', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    function upload() {
        $folder = $this->input->getString('folder');
        $this->getModel()->upload($folder);
        die();
    }

}
