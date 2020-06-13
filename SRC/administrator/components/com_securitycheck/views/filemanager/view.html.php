<?php
/**
* FileManager View para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/
// Chequeamos si el archivo está incluido en Joomla!
defined('_JEXEC') or die();


/**
* FileManager View
*
*/
class SecuritychecksViewFileManager extends JViewLegacy{

protected $state;
/**
* FileManager view método 'display'
**/
function display($tpl = null)
{
JToolBarHelper::title( JText::_( 'Securitycheck' ).' | ' .JText::_('COM_SECURITYCHECK_CPANEL_FILE_MANAGER_CONTROL_PANEL_TEXT'), 'securitycheck' );
JToolBarHelper::custom('redireccion_control_panel','arrow-left','arrow-left','COM_SECURITYCHECK_REDIRECT_CONTROL_PANEL',false);
JToolBarHelper::custom('redireccion_system_info','arrow-left','arrow-left','COM_SECURITYCHECKPRO_REDIRECT_SYSTEM_INFO',false);

// Obtenemos los datos del modelo
$model = $this->getModel("filemanager");
$last_check = $model->loadStack("filemanager_resume","last_check");
$files_scanned = $model->loadStack("filemanager_resume","files_scanned");
$incorrect_permissions = $model->loadStack("filemanager_resume","files_with_incorrect_permissions");

$task_ended = $model->get_campo_filemanager("estado");

// Si no se está ejecutando ninguna tarea, mostramos la opción 'view files integrity'
if ( strtoupper($task_ended) != 'IN_PROGRESS' ) {
	JToolBarHelper::custom( 'view_file_permissions', 'eye', 'eye', 'COM_SECURITYCHECK_VIEW_FILE_PERMISSIONS',false );
}

// Ponemos los datos en el template
$this->last_check = $last_check;
$this->files_scanned = $files_scanned;
$this->incorrect_permissions = $incorrect_permissions;

parent::display($tpl);
}
}