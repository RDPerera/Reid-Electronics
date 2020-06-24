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


class EventgalleryTableMessage extends JTable
{


    public $id;
    public $folder;
    public $file;
    public $message;
    public $email;
    public $status;
    public $modified;
    public $created;


    /**
     * Constructor
     * @param JDatabaseDriver $db
     */

	function __construct( &$db ) {
		parent::__construct('#__eventgallery_message', 'id', $db);
	}

    public function store($updateNulls = false) {
	    if (empty($this->id)) {
            $this->created = date("Y-m-d H:i:s");
        }
        return parent::store($updateNulls);
    }
}
