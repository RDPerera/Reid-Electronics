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


class EventgalleryTableImagelineitem extends JTable
{

    public $id;
    public $folder;
    public $file;
    public $original_filename;
    public $quantity;
    public $imagetypeid;
    public $taxrate;
    public $price;
    public $priceincluded;
    public $singleprice;
    public $currency;
    public $buyernote;
    public $sellernote;
    public $lineitemcontainerid;
    public $modified;
    public $created;

    /**
     * Constructor
     * @param JDatabaseDriver $db
     */
    function __construct( &$db ) {
		parent::__construct('#__eventgallery_imagelineitem', 'id', $db);
	}
}
