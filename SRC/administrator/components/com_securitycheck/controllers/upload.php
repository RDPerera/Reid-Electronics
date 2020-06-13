<?php
/**
* @ Copyright (c) 2011 - Jose A. Luque
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// Protect from unauthorized access
defined('_JEXEC') or die();

use Joomla\CMS\Session\Session as JSession;

class SecuritychecksControllerUpload extends SecuritycheckController
{
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	/* Acciones al pulsar el botón 'Import settings' */
	function read_file(){
		$model = $this->getModel("upload");
		$res = $model->read_file();
		
		if ($res) {
			$this->setRedirect( 'index.php?option=com_securitycheck' );		
		} else {
			$this->setRedirect( 'index.php?option=com_securitycheck&controller=filemanager&view=upload&'. JSession::getFormToken() .'=1' );	
		}
	}
			
}
