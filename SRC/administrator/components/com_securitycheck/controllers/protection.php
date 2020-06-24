<?php
/**
* Protection Controller para Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Session\Session as JSession;

// Load framework base classes
jimport('joomla.application.component.controller');

/**
* Securitycheckpros  Controller
*
*/
class SecuritychecksControllerProtection extends JControllerLegacy
{
/**
* constructor (registers additional tasks to methods)
* @return void
*/
function __construct()
{
parent::__construct();

}

/* Redirecciona las peticiones al componente */
function redireccion()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=securitycheck' );
}

/* Redirecciona las peticiones al Panel de Control */
function redireccion_control_panel()
{
	$this->setRedirect( 'index.php?option=com_securitycheck' );
}

/* Guarda los cambios y redirige al cPanel */
public function save()
{
	JSession::checkToken() or die('Invalid Token');
	$model = $this->getModel('protection');
	$jinput = JFactory::getApplication()->input;
	$data = $jinput->getArray($_POST);
	$model->saveConfig($data);

	$this->setRedirect('index.php?option=com_securitycheck&view=cpanel&'. JSession::getFormToken() .'=1',JText::_('COM_SECURITYCHECK_CONFIGSAVED'));
}

/* Guarda los cambios */
public function apply()
{
	$this->save();
	$this->setRedirect('index.php?option=com_securitycheck&controller=protection&view=protection&'. JSession::getFormToken() .'=1',JText::_('COM_SECURITYCHECK_CONFIGSAVED'));
}

/* Modifica o crear el archivo .htaccess con las configuraciones seleccionadas por el usuario */
function protect()
{
	$model = $this->getModel("protection");

	$status = $model->protect();
	$url = 'index.php?option=com_securitycheck&controller=protection&view=protection&'. JSession::getFormToken() .'=1';
	if($status) {
		$this->setRedirect($url,JText::_('COM_SECURITYCHECK_PROTECTION_APPLIED'));
	} else {
		$this->setRedirect($url,JText::_('COM_SECURITYCHECK_PROTECTION_NOTAPPLIED'),'error');
	}
	
}

/* Borra el archivo .htaccess */
function delete_htaccess()
{
	$model = $this->getModel("protection");

	$status = $model->delete_htaccess();
	$url = 'index.php?option=com_securitycheck&controller=protection&view=protection&'. JSession::getFormToken() .'=1';
	if($status) {
		$this->setRedirect($url,JText::_('COM_SECURITYCHECK_HTACCESS_DELETED'));
	} else {
		$this->setRedirect($url,JText::_('COM_SECURITYCHECK_HTACCESS_NOT_DELETED'),'error');
	}
	
}

/* Muestra las configuraciones escogidas en una ventana, en lugar de aplicarlas mediante un archivo .htaccess.  Esto es necesario en servidores NGINX*/
function generate_rules()
{
	$txt_content = '';
	
	$model = $this->getModel("protection");

	$rules = $model->generate_rules();
	
	$txt_content .= $rules;
	// Mandamos el contenido al navegador
	@ob_end_clean();	
	ob_start();	
	header( 'Content-Type: text/plain' );
	header( 'Content-Disposition: attachment;filename=securitycheck_nginx_rules.txt' );
	print $txt_content;
	exit();
		
}

/* Redirecciona las peticiones a System Info */
function redireccion_system_info()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=filemanager&view=sysinfo&'. JSession::getFormToken() .'=1' );
}

}