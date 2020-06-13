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


class EventgalleryTableGooglephotosaccount extends JTable
{
    public $id;
    public $clientid;
    public $secret;
    public $refreshtoken;
    public $name;
    public $description;
    public $modified;
    public $created;
    public $published;
    public $ordering;

    /**
     * Constructor
     * @param JDatabaseDriver $db
     */

	function __construct( &$db ) {
		parent::__construct('#__eventgallery_googlephotos_account', 'id', $db);
	}
    public function store( $updateNulls=false )
    {
        $this->clientid = trim($this->clientid);
        $this->secret = trim($this->secret);
        $this->refreshtoken = trim($this->refreshtoken);

        return parent::store($updateNulls);
    }

}
