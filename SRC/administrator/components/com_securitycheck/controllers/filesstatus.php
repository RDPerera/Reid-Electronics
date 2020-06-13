<?php
/**
* Securitycheck Pro FileStatus Controller
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protección frente a accesos no autorizados
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;

/**
 * Controlador de la clase FileManager
 *
 */
class SecuritychecksControllerFilesStatus extends JControllerLegacy
{

public function  __construct() {
		parent::__construct();
}

/* Mostramos el Panel de Control del Gestor de archivos */
public function display($cachable = false, $urlparams = Array())
{
	$jinput = JFactory::getApplication()->input;
	$jinput->set('view', 'filesstatus');
	$jinput->set('hidemainmenu', 1);
		
	parent::display();
}
	

/* Redirecciona las peticiones al Panel de Control de la Gestión de Archivos */
function redireccion_file_manager_control_panel()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=filemanager&view=filemanager&'. JSession::getFormToken() .'=1' );
}

public function getEstado() {
	$model = $this->getModel("filemanager");
	$message = $model->get_campo_filemanager('estado_cambio_permisos');
	$message = JText::_('COM_SECURITYCHECK_FILEMANAGER_' .$message);
	echo $message;
}

}