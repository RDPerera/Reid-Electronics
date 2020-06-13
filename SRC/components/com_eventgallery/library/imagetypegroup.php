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

class EventgalleryLibraryImagetypegroup extends EventgalleryLibraryDatabaseObject
{
    /**
     * @var EventgalleryTableImagetypegroup
     */
    protected $_imagetypegroup = NULL;
    protected $_imagetypegroup_id = NULL;
    protected $_ls_displayname = NULL;
    protected $_ls_description = NULL;

    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException("Can't initialize Imagetypegroup without a Data Object");
        }

        $this->_imagetypegroup = $object;
        $this->_imagetypegroup_id = $object->id;

        $this->_ls_displayname = new EventgalleryLibraryDatabaseLocalizablestring($this->_imagetypegroup->displayname);
        $this->_ls_description = new EventgalleryLibraryDatabaseLocalizablestring($this->_imagetypegroup->description);

        parent::__construct();
    }

    /**
     * @return string the id of the image type
     */
    public function getId()
    {
        return $this->_imagetypegroup->id;
    }

    /**
     * @return string display name of the image type
     */
    public function getName()
    {
        return $this->_imagetypegroup->name;
    }

    /**
     * @return string display name of the image type
     */
    public function getDisplayName()
    {
        return $this->_ls_displayname->get();
    }

    /**
     * @return string description name of the image type
     */
    public function getDescription()
    {
        return $this->_ls_description->get();
    }

    /**
     * @return bool
     */
    public function isPublished() {
        return $this->_imagetypegroup->published==1;
    }
}
