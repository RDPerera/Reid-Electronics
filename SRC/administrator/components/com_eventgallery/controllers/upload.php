<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controllerform' );
require_once JPATH_ROOT.'/components/com_eventgallery/config.php';
require_once(__DIR__.'/../controller.php');

class EventgalleryControllerUpload extends JControllerForm
{

    protected $default_view = 'upload';

	public function getModel($name = 'Upload', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

	function upload() {

        $folder = $this->input->getString('folder');
		$this->getModel()->upload($folder);
		die();
	}
	
	public function cancel($key = NULL) {
		$this->setRedirect( 'index.php?option=com_eventgallery&view=events');
	}
}
