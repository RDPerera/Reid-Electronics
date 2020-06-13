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

class EventgalleryLibraryGooglephotosaccount extends EventgalleryLibraryDatabaseObject
{

    /**
     * @var EventgalleryTableGooglephotosaccount
     */
    protected $_googlephotosaccount = NULL;
    protected $_googlephotosaccount_id = NULL;

    public function __construct($dbGooglePhotosAccount)
    {
        if (!is_object($dbGooglePhotosAccount)) {
            throw new InvalidArgumentException("Can't initialize Google Photos Account Object without a Data Object.");
        }
        $this->_googlephotosaccount = $dbGooglePhotosAccount;
        $this->_googlephotosaccount_id = $dbGooglePhotosAccount->id;



        parent::__construct();
    }

    public function getId()
    {
        return $this->_googlephotosaccount->id;
    }   

    public function getName()
    {
        return $this->_googlephotosaccount->name;
    }


    public function getDescription()
    {
        return $this->_googlephotosaccount->description;
    }

    public function getClientId() {
        return $this->_googlephotosaccount->clientid;
    }

    public function getSecret() {
        return $this->_googlephotosaccount->secret;
    }

    public function getRefreshToken() {
        return $this->_googlephotosaccount->refreshtoken;
    }

    public function isPublished() {
        return $this->_googlephotosaccount->published==1;
    }

    public function getOrdering() {
        return $this->_googlephotosaccount->ordering;
    }

    /**
     * return true if all the necessary credentials are set.
     *
     * @return bool
     */
    public function isUsable() {
        return !empty($this->getClientId()) && !empty($this->getSecret()) && !empty($this->getRefreshToken());
    }


}
