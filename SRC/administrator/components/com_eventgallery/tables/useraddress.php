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


class EventgalleryTableUseraddress extends JTable
{


    public $id;
    public $firstname;
    public $lastname;
    public $companyname;
    public $taxid;
    public $address1;
    public $address2;
    public $address3;
    public $city;
    public $state;
    public $country;
    public $zip;
    public $modified;
    public $created;

    public $userid;

    /**
     * Constructor
     * @param JDatabaseDriver $db
     */

	function __construct( &$db ) {
		parent::__construct('#__eventgallery_useraddress', 'id', $db);
	}
}