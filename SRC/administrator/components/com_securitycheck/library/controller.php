<?php
/**
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Session\Session as JSession;
use Joomla\CMS\MVC\Controller\BaseController as JController;

if(!class_exists('JoomlaCompatController')) {
	if(interface_exists('JController')) {
		abstract class JoomlaCompatController extends JControllerLegacy {}
	} else {
		class JoomlaCompatController extends JController {}
	}
}

class SecuritycheckController extends JoomlaCompatController {
	
function __construct()
{
parent::__construct();
}

/* Redirecciona las peticiones al Panel de Control */
function redireccion_control_panel()
{
	$this->setRedirect( 'index.php?option=com_securitycheck' );
}

/* Redirecciona las peticiones a System Info */
function redireccion_system_info()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=filemanager&view=sysinfo&'. JSession::getFormToken() .'=1' );
}

}