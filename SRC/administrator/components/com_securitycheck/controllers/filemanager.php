<?php
/**
* Securitycheck FileManager Controller
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protección frente a accesos no autorizados
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory as JFactory;
use Joomla\Filesystem\File as JFile;


/**
 * Controlador de la clase FileManager
 *
 */
class SecuritychecksControllerFileManager extends SecuritycheckController
{

public function  __construct() {
		parent::__construct();
}

/* Mostramos el Panel de Control del Gestor de archivos */
public function display($cachable = false, $urlparams = Array())
{
	$jinput = JFactory::getApplication()->input;
	$jinput->set('hidemainmenu', 1);
		
	parent::display();
}

/* Redirecciona las peticiones al Panel de Control */
function redireccion_control_panel()
{
	$this->setRedirect( 'index.php?option=com_securitycheck' );
}

/* Redirecciona las peticiones al Panel de Control de la Gestión de Archivos  y borra el fichero de logs*/
function redireccion_control_panel_y_borra_log()
{
	jimport( 'joomla.application.component.helper' );

	// Obtenemos la ruta al fichero de logs, que vendrá marcada por la entrada 'log_path' del fichero 'configuration.php'
	$app = JFactory::getApplication();
	$logName = $app->getCfg('log_path');
	$filename = $logName . DIRECTORY_SEPARATOR ."change_permissions.log.php";
	
	// ¿ Debemos borrar el archivo de logs?
	$params = JComponentHelper::getParams('com_securitycheck');
	$delete_log_file = $params->get('delete_log_file',1);
	if ( $delete_log_file == 1 ) {
		// Si no puede borrar el archivo, Joomla muestra un error indicándolo a través de JERROR
		$result = JFile::delete($filename);
	}
	
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=filemanager&view=filemanager&'. JSession::getFormToken() .'=1' );
}

/* Mostramos los permisos de los archivos analizados */
public function view_file_permissions()
{
	$jinput = JFactory::getApplication()->input;
	$jinput->set('view', 'filesstatus');
	
	parent::display();
}

/* Mostramos el Panel para borrar los datos de la BBDD  */
public function initialize_data()
{
	$jinput = JFactory::getApplication()->input;
	$jinput->set('view', 'initialize_data');
	
	parent::display();
}

/* Acciones al pulsar el escaneo de archivos manual */
function acciones(){
	$model = $this->getModel("filemanager");
	
	$model->set_campo_filemanager('files_scanned',0);
	$model->set_campo_filemanager('last_check',date('Y-m-d H:i:s'));
	$message = JText::_('COM_SECURITYCHECK_FILEMANAGER_IN_PROGRESS');
	echo $message; 
	$model->set_campo_filemanager('estado','IN_PROGRESS'); 
	$model->scan();
}

/* Acciones al pulsar el borrado de la información de la BBDD */
function acciones_clear_data(){
	
	$message = JText::_('COM_SECURITYCHECK_CLEAR_DATA_DELETING_ENTRIES');
	echo $message; 
	$this->initialize_database();
	$model = $this->getModel("filemanager");
	$model->set_campo_filemanager('estado_clear_data','ENDED');
}

/* Borra los datos de la tabla '#__securitycheck_file_permissions' */
function initialize_database()
{
	$model = $this->getModel("filemanager");
	$model->initialize_database();
	
}

/* Obtiene el estado del proceso de análisis de permisos de archivos consultando la tabla '#__securitycheck_file_manager'*/
public function getEstado() {
	$model = $this->getModel("filemanager");
	$message = $model->get_campo_filemanager('estado');
	$message = JText::_('COM_SECURITYCHECK_FILEMANAGER_' .$message);
	echo $message;
}

/* Obtiene el estado del proceso de hacer un drop y crear de nuevo la tabla '#__securitycheck_file_permissions'*/
public function getEstadoClearData() {
	$model = $this->getModel("filemanager");
	$message = $model->get_campo_filemanager('estado_clear_data');
	$message = JText::_('COM_SECURITYCHECK_FILEMANAGER_' .$message);
	echo $message;
}

public function currentDateTime() {
    echo date('Y-m-d D H:i:s');
}

/* Obtiene el estado del proceso de análisis de permisos de los archivos consultando los datos de sesión almacenados previamente */
public function get_percent() {
	$model = $this->getModel("filemanager");
	$message = $model->get_campo_filemanager('files_scanned');
	echo $message;
	
}

/* Obtiene la diferencia, en horas, entre dos tareas de chequeo de permisos. Si la diferencia es mayor de 3 horas, devuelve el valor 20000 */
public function getEstado_Timediff() {
	$model = $this->getModel("filemanager");
	$datos = null;
		
	(int) $timediff = $model->get_timediff();
	$estado = $model->get_campo_filemanager('estado');
	$datos = json_encode(array(
				'estado'	=> $estado,
				'timediff'		=> $timediff
			));
			
	echo $datos;		
}

/* Redirecciona a la opción de mostrar las vulnerabilidades */
function GoToVuln()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=securitycheck&'. JSession::getFormToken() .'=1' );	
}

/* Redirecciona a la opción de mostrar los permisos de archivos/directorios */
function GoToPermissions()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=filemanager&view=filemanager&'. JSession::getFormToken() .'=1' );	
}

/* Redirecciona a la opción htaccess protection */
function GoToHtaccessProtection()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=protection&view=protection&'. JSession::getFormToken() .'=1' );	
}

/* Redirecciona al Cponel */
function GoToCpanel()
{
	$this->setRedirect( 'index.php?option=com_securitycheck' );	
}

/* Redirecciona a las excepciones del firewall */
function GoToFirewallExceptions()
{
	// Obtenemos las opciones del Cpanel
	require_once JPATH_ROOT.'/administrator/components/com_securitycheck/models/cpanel.php';
	$CpanelOptions = new SecuritychecksModelCpanel();
	$sc_plugin_id = $CpanelOptions->get_plugin_id(1);
	
	$this->setRedirect( 'index.php?option=com_plugins&task=plugin.edit&extension_id='. $sc_plugin_id );
}

}