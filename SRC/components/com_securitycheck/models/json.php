<?php
/**
* @ author Jose A. Luque
* @ Copyright (c) 2013 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protect from unauthorized access
defined('_JEXEC') or die();
jimport('joomla.filesystem.folder');

use Joomla\Component\Joomlaupdate\Administrator\Model\UpdateModel;

class SecuritychecksModelJson extends SecuritycheckModel
{

	const	STATUS_OK					= 200;	// Normal reply
	const	STATUS_NOT_AUTH				= 401;	// Invalid credentials
	const	STATUS_NOT_ALLOWED			= 403;	// Not enough privileges
	const	STATUS_NOT_FOUND			= 404;  // Requested resource not found
	const	STATUS_INVALID_METHOD		= 405;	// Unknown JSON method
	const	STATUS_ERROR				= 500;	// An error occurred
	const	STATUS_NOT_IMPLEMENTED		= 501;	// Not implemented feature
	const	STATUS_NOT_AVAILABLE		= 503;	// Remote service not activated

	const	CIPHER_RAW			= 1;	// Data in plain-text JSON
	const	CIPHER_AESCBC128		= 2;	// Data in AES-128 standard (CBC) mode encrypted JSON
	const	CIPHER_AESCBC256		= 3;	// Data in AES-256 standard (CBC) mode encrypted JSON

	private	$json_errors = array(
		'JSON_ERROR_NONE' => 'No error has occurred (probably emtpy data passed)',
		'JSON_ERROR_DEPTH' => 'The maximum stack depth has been exceeded',
		'JSON_ERROR_CTRL_CHAR' => 'Control character error, possibly incorrectly encoded',
		'JSON_ERROR_SYNTAX' => 'Syntax error'
	);
	
	// Inicializamos las variables
	private	$status = 200;  // Estado de la petición
	private $cipher = 2;	// Método usado para cifrar los datos
	private $clear_data = '';		// Datos enviados en la petición del cliente (ya en claro)
	public $data = '';		// Datos devueltos al cliente
	private $password = null;
	private $method_name = null;
	private $backupinfo = array('product'=> '', 'latest'=>'', 'latest_status'=>	'', 'latest_type'=>'');
	private $update_database_plugin_needs_update = false;   // Indica si el plugin 'Update Database' necesita actualizarse
	private $info = null;  // Contendrá información sobre el sistema: versión de php, mysql y servidor

	/* Función que realiza una determinada función según los parámetros especificados en la variable pasada como argumento */
	public function execute($json)
	{
				
		// Comprobamos si el frontend está habilitado
		$config = $this->Config('controlcenter');
		if ( !array_key_exists('control_center_enabled', $config) ) {
			$enabled = false;
		} else {
			$enabled = $config['control_center_enabled'];
		}
		
		if ( array_key_exists('secret_key', $config) ) {
			$this->password = $config['secret_key'];
		} else {
			$this->data = 'Remote password not configured';
			$this->status = self::STATUS_NOT_AUTH;
			$this->cipher = self::CIPHER_RAW;
			return $this->sendResponse();
		}
		
		// Si el frontend no está habilitado, devolvemos un error 503
		if(!$enabled)
		{
			$this->data = 'Access denied';
			$this->status = self::STATUS_NOT_AVAILABLE;
			$this->cipher = self::CIPHER_RAW;
			return $this->sendResponse();
		}
		
		$json_trimmed = rtrim($json, chr(0));

		// Comprobamos que el string JSON es válido y que tiene al menos 12 caracteres (longitud mínima de un mensaje válido)
		if ((strlen($json_trimmed) < 12) || (substr($json_trimmed, 0, 1) != '{') || (substr($json_trimmed, -1) != '}')) {
			// El string JSON no es válido, devolvemos un error
			$this->data = 'JSON decoding error';
			$this->status = self::STATUS_ERROR;
			$this->cipher = self::CIPHER_RAW;
			return $this->sendResponse();	
		} else {
			// Decodificamos la petición
			$request = json_decode($json, true);
		}
		
		if(is_null($request))	{
			// Si no podemos decodificar la petición JSON, devolvemos un error
			$this->data = 'JSON decoding error';
			$this->status = self::STATUS_ERROR;
			$this->cipher = self::CIPHER_RAW;
			return $this->sendResponse();			
		}
		
		// Decodificamos el 'body' de la petición
		if( isset($request['cipher']) && isset($request['body']) ) {


			switch( $request['cipher'] ) {
			
				case self::CIPHER_RAW:
					if ( ($request['body']['task'] == "getStatus") || ($request['body']['task'] == "checkVuln") || ($request['body']['task'] == "checkLogs") || ($request['body']['task'] == "checkPermissions") || ($request['body']['task'] == "checkIntegrity") || ($request['body']['task'] == "deleteBlocked") || ($request['body']['task'] == "checkmalware") || ($request['body']['task'] == "UpdateExtension") ) {		
						/* Los resultados de todas las tareas se devuelven cifrados; si recibimos una petición para devolverlos sin cifrar, la rechazamos
							porque será fraudulenta */
						$this->data = 'Go away, hacker!';
						$this->status = self::STATUS_NOT_ALLOWED;
						$this->cipher = self::CIPHER_RAW;
						return $this->sendResponse();
					}
					break;
					
				case self::CIPHER_AESCBC128:
					if ( !is_null($request['body']['data']) ) {
						//$this->clear_data = $this->mc_decrypt_128($request->body->data, $this->password);
					}
					break;

				case self::CIPHER_AESCBC256:
					if ( !is_null($request['body']['data']) ) {
						//$this->clear_data = $this->mc_decrypt_256($request->body->data, $this->password);
					}					
					break;
			}
			
			$this->cipher = $request['cipher'];
			switch( $request['body']['task'] ) {
				case "getStatus":
					$this->getStatus();
					break;
					
				case "checkVuln":
					$this->checkVuln();
					break;
					
				case "checkLogs":
					$this->checkLogs();
					break;
					
				case "checkPermissions":
					$this->checkPermissions();
					break;
					
				case "deleteBlocked":
					$this->deleteBlocked();
					break;
					
				case "update":
					$this->Update();
					break;
					
				case "LatestReleaseInfo":
					$this->LatestReleaseInfo();
					break;
					
				case "UpdateComponent":
					$this->UpdateComponent();
					break;

				case "UpdateExtension":
					$this->UpdateExtension($request['body']['data']);
					break;
					
				case self::CIPHER_AESCBC256:
									
					break;
				default:
					$this->data = 'Method not configured';
					$this->status = self::STATUS_NOT_FOUND;
					$this->cipher = self::CIPHER_RAW;
					return $this->sendResponse();
			}
			return $this->sendResponse();		
		}
	}
	
	/* Función que empaqueta una respuesta en formato JSON codificado, cifrando los datos si es necesario */
	private function sendResponse()
	{
		// Inicializamos la respuesta
		$response = array(
			'cipher'	=> $this->cipher,
			'body'		=> array(
				'status'		=> $this->status,
				'data'			=> null
			)
		);
		
			
		// Codificamos los datos enviados en formato JSON
		$data = json_encode($this->data);
		
		// Ciframos o no los datos según el método establecido en la petición
		switch($this->cipher)
		{
			case self::CIPHER_RAW:
				break;

			case self::CIPHER_AESCBC128:
				$data = $this->encrypt($data, $this->password);
				break;

			case self::CIPHER_AESCBC256:
				//$data = $this->mc_encrypt_256($data, $this->password);
				break;
		}

		// Guardamos los datos...
		$response['body']['data'] = $data;

		// ... y los devolvemos al cliente
		return '###' . json_encode($response) . '###';		
	}
	
	/* Extraemos los parámetros del componente */
	private function Config($key_name)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query 
			->select($db->quoteName('storage_value'))
			->from($db->quoteName('#__securitycheck_storage'))
			->where($db->quoteName('storage_key').' = '.$db->quote($key_name));
		$db->setQuery($query);
		$res = $db->loadResult();
		$res = json_decode($res, true);
			
		return $res;
	}
	
	/* Función que verifica una fecha */
	public function verifyDate($date, $strict = true)
	{
		$dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
		if ($strict) {
			$errors = DateTime::getLastErrors();
			if (!empty($errors['warning_count'])) {
				return false;
			}
		}
		return $dateTime !== false;
	}

	/* Función que devuelve el estado de la extensión remota  */
	public function getStatus($opcion=true) {
	
		// Inicializamos las variables
		$extension_updates = null;
		$installed_version = "0.0.0";
		$hasUpdates = 0;
	
		// Import Securitychecks model
		JLoader::import('joomla.application.component.model');
		JLoader::import('cpanel', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'models');
		JLoader::import('filemanager', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'models');
		JLoader::import('databaseupdates', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'helpers');
		if ( version_compare(JVERSION, '3.0', 'ge') ) {
			$cpanel_model = JModelLegacy::getInstance( 'cpanel', 'SecuritychecksModel');
			$filemanager_model = JModelLegacy::getInstance( 'filemanager', 'SecuritychecksModel');
			$update_model = JModelLegacy::getInstance( 'databaseupdates', 'SecuritychecksModel');
		} else {
			$cpanel_model = JModel::getInstance( 'cpanel', 'SecuritychecksModel');
			$filemanager_model = JModel::getInstance( 'filemanager', 'SecuritychecksModel');
			$update_model = JModel::getInstance( 'databaseupdates', 'SecuritychecksModel');
		}
		
		// Comprobamos el estado del plugin Update Database
		$update_database_plugin_installed = $update_model-> PluginStatus(4);
		$update_database_plugin_version = $update_model->get_database_version();
		$update_database_plugin_last_check = $update_model->last_check();
		
		// Vulnerable components
		$db = JFactory::getDBO();
		$query = 'SELECT COUNT(*) FROM #__securitycheck WHERE Vulnerable="Si"';
		$db->setQuery( $query );
		$db->execute();	
		$vuln_extensions = $db->loadResult();
		
		// Check for unread logs
		(int) $logs_pending = $cpanel_model->LogsPending();
		
		// Get files with incorrect permissions from database
		$files_with_incorrect_permissions = $filemanager_model->loadStack("filemanager_resume","files_with_incorrect_permissions");
		
		// If permissions task has not been launched, set a '0' value.
		if ( is_null($files_with_incorrect_permissions) ) {
			$files_with_incorrect_permissions = 0;
		}
		
		// FileManager last check
		$last_check = $filemanager_model->loadStack("filemanager_resume","last_check");
		
		// Get files with incorrect permissions from database
		$files_with_bad_integrity = 0;
		
		// If permissions task has not been launched, set a '0' value.
		if ( is_null($files_with_bad_integrity) ) {
			$files_with_bad_integrity = 0;
		}
		
		// FileIntegrity last check
		$last_check_integrity = 0;
		
		// Comprobamos el estado del backup
		$this->getBackupInfo();
	
		// Verificamos si el core está actualizado (obviando la caché) 
		if ( version_compare(JVERSION, '3.20', 'lt') ) {
			require_once JPATH_ROOT.'/administrator/components/com_joomlaupdate/models/default.php';
			$updatemodel = new JoomlaupdateModelDefault();
		} else {
			require_once JPATH_ROOT.'/administrator/components/com_joomlaupdate/Model/UpdateModel.php';
			$updatemodel = new UpdateModel();
		}
		$updatemodel->refreshUpdates(true);
		$coreInformation = $updatemodel->getUpdateInformation();
		
		// Si el plugin 'Update Batabase' está instalado, comprobamos si está actualizado
		if ( $update_database_plugin_installed ) {
			$this->update_database_plugin_needs_update = $this->checkforUpdate();
		} else {
			$this->update_database_plugin_needs_update = null;
		}
		
		// Añadimos la información del sistema
		$this->getInfo();
		
		// Añadimos la información sobre las extensiones no actualizadas. Esta opción no es necesaria cuando escogemos la opción 'System Info'
		if ( $opcion ) {
			$extension_updates = $this->getNotUpdatedExtensions();
			$outdated_extensions = json_decode($extension_updates, true);
			$sc_to_find = "Securitycheck Pro";
			$key_sc = array_search($sc_to_find, array_column($outdated_extensions, 2));	
			
			if ( $key_sc !== false ) {
				$installed_version = $outdated_extensions[$key_sc][4];
				$hasUpdates = 1;
			}	
		}
		
		// Si no hay backup establecemos la fecha actual para evitar un error en la bbdd al insertar el valor
		$is_valid_date = $this->verifyDate($this->backupinfo['latest']);
		if ( !$is_valid_date ) {
			$this->backupinfo['latest'] = "0000-00-00 00:00:00";
		}
				
		$this->data = array(
			'vuln_extensions'		=> $vuln_extensions,
			'logs_pending'	=> $logs_pending,
			'files_with_incorrect_permissions'		=> $files_with_incorrect_permissions,
			'last_check' => $last_check,
			'files_with_bad_integrity'		=> $files_with_bad_integrity,
			'last_check_integrity' => "0000-00-00 00:00:00",
			'installed_version'	=> $installed_version,
			'hasUpdates'	=> $hasUpdates,
			'coreinstalled'	=>	$coreInformation['installed'],
			'corelatest'	=>	$coreInformation['latest'],
			'last_check_malwarescan' => null,
			'suspicious_files'		=> 0,
			'update_database_plugin_installed'	=>	$update_database_plugin_installed,
			'update_database_plugin_version'	=>	$update_database_plugin_version,
			'update_database_plugin_last_check'	=>	$update_database_plugin_last_check,
			'update_database_plugin_needs_update'	=>	$this->update_database_plugin_needs_update,
			'backup_info_product'	=>	$this->backupinfo['product'],
			'backup_info_latest'	=>	$this->backupinfo['latest'],
			'backup_info_latest_status'	=>	$this->backupinfo['latest_status'],
			'backup_info_latest_type'	=>	$this->backupinfo['latest_type'],
			'php_version'	=>	$this->info['phpversion'],
			'database_version'	=>	$this->info['dbversion'],
			'web_server'	=>	$this->info['server'],
			'extension_updates'	=>	$extension_updates,
			'last_check_database_optimization'	=> null
		);
	
	}
	
	/* Función que comprueba si existen extensiones vulnerables  */
	private function checkVuln() {
		
		// Import Securitychecks model
		JLoader::import('joomla.application.component.model');
		JLoader::import('securitychecks', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'models');
		JLoader::import('databaseupdates', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'helpers');
		if ( version_compare(JVERSION, '3.0', 'ge') ) {
			$securitycheckpros_model = JModelLegacy::getInstance( 'securitychecks', 'SecuritychecksModel');
			$update_model = JModelLegacy::getInstance( 'databaseupdates', 'SecuritychecksModel');
		} else {
			$securitycheckpros_model = JModel::getInstance( 'securitychecks', 'SecuritychecksModel');
			$update_model = JModel::getInstance( 'databaseupdates', 'SecuritychecksModel');
		}
		
		// Comprobamos si existen nuevas actualizaciones
		$update_model->tarea_comprobacion();
		
		// Comprobamos el estado del plugin Update Database
		$update_database_plugin_installed = $update_model-> PluginStatus(4);
		$update_database_plugin_version = $update_model->get_database_version();
		$update_database_plugin_last_check = $update_model->last_check();
			
		// Hacemos una nueva comprobación de extensiones vulnerables
		$securitycheckpros_model->chequear_vulnerabilidades();
		
		// Vulnerable components
		$db = JFactory::getDBO();
		$query = 'SELECT COUNT(*) FROM #__securitycheck WHERE Vulnerable="Si"';
		$db->setQuery( $query );
		$db->execute();	
		$vuln_extensions = $db->loadResult();
		
		$this->data = array(
			'vuln_extensions'		=> $vuln_extensions,
			'update_database_plugin_installed'	=>	$update_database_plugin_installed,
			'update_database_plugin_version'	=>	$update_database_plugin_version,
			'update_database_plugin_last_check'	=>	$update_database_plugin_last_check
		);
	}
	
	/* Función que comprueba si existen logs por leer  */
	private function checkLogs() {
		// Import Securitycheckpros model
		JLoader::import('joomla.application.component.model');
		JLoader::import('cpanel', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'models');
		if ( version_compare(JVERSION, '3.0', 'ge') ) {
			$cpanel_model = JModelLegacy::getInstance( 'cpanel', 'SecuritychecksModel');			
		} else {
			$cpanel_model = JModel::getInstance( 'cpanel', 'SecuritychecksModel');			
		}
		
		// Check for unread logs
		(int) $logs_pending = $cpanel_model->LogsPending();
		
		$this->data = array(
			'logs_pending'	=> $logs_pending			
		);
		
	}
	
	/* Función que lanza un chequeo de permisos  */
	private function checkPermissions() {
		// Import Securitycheckpros model
		JLoader::import('joomla.application.component.model');
		JLoader::import('filemanager', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'models');
		if ( version_compare(JVERSION, '3.0', 'ge') ) {
			$filemanager_model = JModelLegacy::getInstance( 'filemanager', 'SecuritychecksModel');
		} else {
			$filemanager_model = JModel::getInstance( 'filemanager', 'SecuritychecksModel');
		}
		
		$filemanager_model->set_campo_filemanager('files_scanned',0);
		$filemanager_model->set_campo_filemanager('last_check',date('Y-m-d H:i:s'));
		$filemanager_model->set_campo_filemanager('estado','IN_PROGRESS');
		$filemanager_model->scan("permissions");
		
		// Get files with incorrect permissions from database
		$files_with_incorrect_permissions = $filemanager_model->loadStack("filemanager_resume","files_with_incorrect_permissions");
		
		// If permissions task has not been launched, we set a '0' value.
		if ( is_null($files_with_incorrect_permissions) ) {
			$files_with_incorrect_permissions = 0;
		}
		
		// FileManager last check
		$last_check = $filemanager_model->loadStack("filemanager_resume","last_check");
		
		$this->data = array(
			'files_with_incorrect_permissions'		=> $files_with_incorrect_permissions,
			'last_check' => $last_check
		);
	
	}
	
	/* Borra los logs pertenecientes a intentos de acceso bloqueados */
	private function deleteBlocked() {
		// Import Securitycheckpros model
		JLoader::import('joomla.application.component.model');
		JLoader::import('cpanel', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR. 'com_securitycheck' . DIRECTORY_SEPARATOR . 'models');
		if ( version_compare(JVERSION, '3.0', 'ge') ) {
			$cpanel_model = JModelLegacy::getInstance( 'cpanel', 'SecuritychecksModel');			
		} else {
			$cpanel_model = JModel::getInstance( 'cpanel', 'SecuritychecksModel');			
		}
	
		// Vulnerable components
		$db = JFactory::getDBO();
		$query = 'DELETE FROM #__securitycheck_logs WHERE ( `type` = "IP_BLOCKED" OR `type` = "IP_BLOCKED_DINAMIC" )';
		$db->setQuery( $query );
		$db->execute();	
				
		// Check for unread logs
		(int) $logs_pending = $cpanel_model->LogsPending();
		
		$this->data = array(
			'logs_pending'	=> $logs_pending			
		);
	}
	
	private function Update()
	{
		// Download 
		require_once JPATH_ROOT.'/administrator/components/com_securitycheck/liveupdate/liveupdate.php';
		require_once JPATH_ROOT.'/administrator/components/com_securitycheck/liveupdate/classes/model.php';

		// Do we need to update?
		$updateInformation = LiveUpdate::getUpdateInformation();
		if(!$updateInformation->hasUpdates) {
			return (object)array(
				'download'	=> 0
			);
		}

		$model = new LiveupdateModel();
		$ret = $model->download();

		$session = JFactory::getSession();
		$target		= $session->get('target', '', 'liveupdate');
		$tempdir	= $session->get('tempdir', '', 'liveupdate');

		if(!$ret) {
			// An error ocurred :(
			$this->data = 'Could not download the update package';
			$this->status = self::STATUS_ERROR;
			$this->cipher = self::CIPHER_RAW;
			return $this->sendResponse();			
		} else {
			// Extract
			$ret = $model->extract();

			JLoader::import('joomla.filesystem.file');
			JFile::delete($target);
			
			if(!$ret) {
				// An error ocurred :(
				$this->data = 'Could not extract the update package';
				$this->status = self::STATUS_ERROR;
				$this->cipher = self::CIPHER_RAW;
				return $this->sendResponse();
			} else {
				// Install
				$ret = $model->install();

				if(!$ret) {
					// An error ocurred :(
					$this->data = 'Could not install the update package';
					$this->status = self::STATUS_ERROR;
					$this->cipher = self::CIPHER_RAW;
					return $this->sendResponse();					
				} else {
					// Update cleanup
					$ret = $model->cleanup();

					JLoader::import('joomla.filesystem.file');
					JFile::delete($target);
					
					// Update product info
					$this->getStatus();
				}
			}
		}
	}
	
	/* Obtiene información de la última versión publicada */
	private function LatestReleaseInfo() {
		/* Preguntamos por la información de la última versión */
		require_once JPATH_ROOT.'/administrator/components/com_securitycheck/liveupdate/liveupdate.php';
		$updateInformation = LiveUpdate::getUpdateInformation(1);
		
		$this->data = array(
			'latest_version'	=> $updateInformation->version,
			'release_notes'	=> $updateInformation->releasenotes
		);
	
	}
	
	/* Función queactualiza el Core de Joomla a la última versión disponible  */
	private function UpdateCore() {
		
		// Cargamos las librerías necesarias
		require_once JPATH_ROOT.'/administrator/components/com_joomlaupdate/models/default.php';
				
		// Refrescamos la información de las actualizaciones ignorando la caché
		JoomlaupdateModelDefault::refreshUpdates(true);
		
		// Extraemos la url de descarga
		$coreInformation = JoomlaupdateModelDefault::getUpdateInformation();
		// Realizamos la instalación pasando la url de descarga
		$result = $this->install($coreInformation['object']->downloadurl->_data);
		JoomlaupdateModelDefault::finaliseUpgrade();
		
		if ( !$result ) {
			$this->status = self::STATUS_ERROR;			
		} else {
			$this->data = array(
				'coreinstalled'	=> $coreInformation['latest']
			);
		}		
	
	}
	
	
	/**
	 * Install an extension from either folder, url or upload.
	 *
	 * @return  boolean result of install
	 *
	 * @since   1.5
	 */
	public function install($url)
	{
		$this->setState('action', 'install');

		// Set FTP credentials, if given.
		JClientHelper::setCredentialsFromRequest('ftp');
		$app = JFactory::getApplication();

		// Load installer plugins for assistance if required:
		JPluginHelper::importPlugin('installer');
		$dispatcher = JEventDispatcher::getInstance();

		$package = null;

		// This event allows an input pre-treatment, a custom pre-packing or custom installation (e.g. from a JSON description)
		$results = $dispatcher->trigger('onInstallerBeforeInstallation', array($this, &$package));

		if (in_array(true, $results, true))
		{
			return true;
		}
		elseif (in_array(false, $results, true))
		{
			return false;
		}

		$installType = 'url';

		if ($package === null)
		{
			switch ($installType)
			{
				case 'folder':
					// Remember the 'Install from Directory' path.
					$app->getUserStateFromRequest($this->_context . '.install_directory', 'install_directory');
					$package = $this->_getPackageFromFolder();
					break;

				case 'upload':
					$package = $this->_getPackageFromUpload();
					break;

				case 'url':
					$package = $this->_getPackageFromUrl($url);
					break;

				default:
					$app->setUserState('com_installer.message', JText::_('COM_INSTALLER_NO_INSTALL_TYPE_FOUND'));

					return false;
					break;
			}
		}

		// This event allows a custom installation of the package or a customization of the package:
		$results = $dispatcher->trigger('onInstallerBeforeInstaller', array($this, &$package));

		if (in_array(true, $results, true))
		{
			return true;
		}
		elseif (in_array(false, $results, true))
		{
			if (in_array($installType, array('upload', 'url')))
			{
				JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
			}

			return false;
		}

		// Was the package unpacked?
		if (!$package || !$package['type'])
		{
			if (in_array($installType, array('upload', 'url')))
			{
				JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
			}

			$app->setUserState('com_installer.message', JText::_('COM_INSTALLER_UNABLE_TO_FIND_INSTALL_PACKAGE'));
			return false;
		}

		// Get an installer instance
		$installer = JInstaller::getInstance();

		// Install the package
		if (!$installer->install($package['dir']))
		{
			// There was an error installing the package
			$msg = JText::sprintf('COM_INSTALLER_INSTALL_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
			$result = false;
		}
		else
		{
			// Package installed sucessfully
			$msg = JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
			$result = true;
		}

		// This event allows a custom a post-flight:
		$dispatcher->trigger('onInstallerAfterInstaller', array($this, &$package, $installer, &$result, &$msg));

		// Set some model state values
		$app	= JFactory::getApplication();
		$app->enqueueMessage($msg);
		$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$app->setUserState('com_installer.message', $installer->message);
		$app->setUserState('com_installer.extension_message', $installer->get('extension_message'));
		$app->setUserState('com_installer.redirect_url', $installer->get('redirect_url'));

		// Cleanup the install files
		if (!is_file($package['packagefile']))
		{
			$config = JFactory::getConfig();
			$package['packagefile'] = $config->get('tmp_path') . '/' . $package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		return $result;
	}
	
	
	/**
	 * Install an extension from a URL
	 *
	 * @return  Package details or false on failure
	 *
	 * @since   1.5
	 */
	protected function _getPackageFromUrl($url)
	{
		$input = JFactory::getApplication()->input;

		// Get the URL of the package to install
		//$url = $input->getString('install_url');

		// Did you give us a URL?
		if (!$url)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL'), 'warning');
			return false;
		}

		// Handle updater XML file case:
		if (preg_match('/\.xml\s*$/', $url))
		{
			jimport('joomla.updater.update');
			$update = new JUpdate;
			$update->loadFromXML($url);
			$package_url = trim($update->get('downloadurl', false)->_data);
			if ($package_url)
			{
				$url = $package_url;
			}
			unset($update);
		}

		// Download the package at the URL given
		$p_file = JInstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL'), 'warning');
			return false;
		}

		$config   = JFactory::getConfig();
		$tmp_dest = $config->get('tmp_path');

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest . '/' . $p_file, true);

		return $package;
	}
	
	/* Función que obtiene información del estado del backup  */
	private function getBackupInfo() {
		
		// Instanciamos la consulta
		$db = JFactory::getDBO();
		
		// Consultamos si Akeeba Backup está instalado
		$query = 'SELECT COUNT(*) FROM #__extensions WHERE element="com_akeeba"';
		$db->setQuery( $query );
		$db->execute();	
		$akeeba_installed = $db->loadResult();
		
		if ( $akeeba_installed == 1 ) {
			$this->backupinfo['product'] = 'Akeeba Backup';
			$this->AkeebaBackupInfo();
		} else {
			
			// Consultamos si Xcloner Backup and Restore está instalado
			$query = 'SELECT COUNT(*) FROM #__extensions WHERE element="com_xcloner-backupandrestore"';
			$db->setQuery( $query );
			$db->execute();	
			$xcloner_installed = $db->loadResult();
			
			if ( $xcloner_installed == 1 ) {
				$this->backupinfo['product'] = 'Xcloner - Backup and Restore';
				$this->XclonerbackupInfo();				
			} else {
			
				// Consultamos si Easy Joomla Backup está instalado
				$query = 'SELECT COUNT(*) FROM #__extensions WHERE element="com_easyjoomlabackup"';
				$db->setQuery( $query );
				$db->execute();	
				$ejb_installed = $db->loadResult();
				
				if ( $ejb_installed == 1 ) {
					$this->backupinfo['product'] = 'Easy Joomla Backup';
					$this->EjbInfo();				
				} 
			}
		}
		
	}
	
	/* Función que obtiene información del estado del último backup creado por Akeeba Backup  */
	private function AkeebaBackupInfo() {
		
		// Instanciamos la consulta
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('MAX('.$db->qn('id').')')
			->from($db->qn('#__ak_stats'))
			->where($db->qn('origin') .' != '.$db->q('restorepoint'));
		$db->setQuery($query);
		$id = $db->loadResult();
		
		// Hay al menos un backup creado
		if ( !empty($id) ) {
			$query = $db->getQuery(true)
				->select(array('*'))
				->from($db->quoteName('#__ak_stats'))
				->where('id = '.$id);				
			$db->setQuery($query);
			$backup_statistics = $db->loadAssocList();			
						
			// Almacenamos el resultado
			$this->backupinfo['latest'] = $backup_statistics[0]['backupend'];
			$this->backupinfo['latest_status'] = $backup_statistics[0]['status'];
			$this->backupinfo['latest_type'] = $backup_statistics[0]['type'];
		}
	}
	
	/* Función que obtiene información del estado del último backup creado por Xcloner - Backup and Restore  */
	private function XclonerbackupInfo() {
		
		// Incluimos el fichero de configuración de la extensión
		include JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "com_xcloner-backupandrestore" . DIRECTORY_SEPARATOR . "cloner.config.php";
		
		// Extraemos el directorio donde se encuentran almacenados los backups...
		$backup_dir = $_CONFIG['clonerPath'];
		
		// ... y buscamos dentro los ficheros existentes, ordenándolos por fecha
		$files_name = JFolder::files($backup_dir,'.',true,true);
		$files_name = array_combine($files_name, array_map("filemtime",$files_name));
		arsort($files_name);
		
		// El primer elemento del array será el que se ha creado el último. Formateamos la fecha para guardarlo en la BBDD.
		$latest_backup = date("Y-m-d H:i:s",filemtime(key($files_name)));
		
		// Almacenamos el resultado
		$this->backupinfo['latest'] = $latest_backup;
		$this->backupinfo['latest_status'] = 'complete';
		
	}
	
	/* Función que obtiene información del estado del último backup creado por Easy Joomla Backup  */
	private function EjbInfo() {
		
		// Instanciamos la consulta
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('MAX('.$db->qn('id').')')
			->from($db->qn('#__easyjoomlabackup'));
		$db->setQuery($query);
		$id = $db->loadResult();
		
		// Hay al menos un backup creado
		if ( !empty($id) ) {
			$query = $db->getQuery(true)
				->select(array('*'))
				->from($db->quoteName('#__easyjoomlabackup'))
				->where('id = '.$id);				
			$db->setQuery($query);
			$backup_statistics = $db->loadAssocList();			
						
			// Almacenamos el resultado
			$this->backupinfo['latest'] = $backup_statistics[0]['date'];
			$this->backupinfo['latest_status'] = 'complete';
			$this->backupinfo['latest_type'] = $backup_statistics[0]['type'];
		}
		
	}
	
	/* Función que indica si el plugin 'Update Database' está actualizado */
	private function checkforUpdate() {
	
		//Inicializmaos las variables
		$needs_update = false;
		
		$db = JFactory::getDBO();
		
		// Extraemos el id de la extensión..
		$query = 'SELECT extension_id FROM #__extensions WHERE name="System - Securitycheck Pro Update Database"';
		$db->setQuery( $query );
		$db->execute();
		$extension_id = $db->loadResult();
		
		// ... y hacemos una consulta a la tabla 'updates' para ver si el 'extension_id' figura como actualizable
		if ( !empty($extension_id) ) {
			$query = "SELECT COUNT(*) FROM #__updates WHERE extension_id={$extension_id}";
			$db->setQuery( $query );
			$db->execute();
			$result = $db->loadResult();
			
			if ( $result == '1' ) {
				$needs_update = true;
			}
			
		}
		
		// Devolvemos el resultado
		return $needs_update;		
		
	}
	
	/* Función que actualiza el plugin 'Update Database' */
	private function UpdateComponent() {
	
		// Inicializamos las variables
		$needs_update = 1;
		jimport( 'joomla.updater.update' );
		
		$db = JFactory::getDBO();
		
		// Extraemos el id de la extensión..
		$query = 'SELECT extension_id FROM #__extensions WHERE name="System - Securitycheck Pro Update Database"';
		$db->setQuery( $query );
		$db->execute();
		$extension_id = $db->loadResult();
		
		$query = "SELECT detailsurl FROM #__updates WHERE extension_id={$extension_id}";
		$db->setQuery( $query );
		$db->execute();
		$detailsurl = $db->loadResult();
		
		// Instanciamos el objeto JUpdate y cargamos los detalles de la actualización
		$update = new JUpdate();
		$update->loadFromXML($detailsurl);
		
		// Le pasamos a la función de actualización el objeto con los detalles de la actualización
		$result= $this->install_update($update);
		
		// Si la actualización ha tenido éxito, actualizamos la variable 'needs_update', que indica si el plugin necesita actualizarse.
		if ( $result ) {
			$needs_update = 0;
		}
		
		// Devolvemos el resultado
		$this->data = array(
			'update_plugin_needs_update' => $needs_update
		);
	}
	
	/* Función para actualizar los componentes. Extraída del core de Joomla (administrator/components/com_installer/models/update.php) */
	private function install_update($update) {
	
		/* Cargamos el lenguaje del componente 'com_installer' */
		$lang = JFactory::getLanguage();
		$lang->load('com_installer',JPATH_ADMINISTRATOR);
	
		// Inicializamos la variable $result, que será un array con el resultado y el mensaje devuelto en el proceso
		$result = array();
			
			
			
		$app = JFactory::getApplication();
		if (isset($update->get('downloadurl')->_data)) {
			$url = trim($update->downloadurl->_data);
		} else {
			$result[0][1] = JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_INVALID_EXTENSION_UPDATE'), 'warning');
			$result[0][0] = false;
		}

		$p_file = JInstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file) {
			$result[0][1] = JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_INSTALLER_PACKAGE_DOWNLOAD_FAILED', $url), 'warning');
			$result[0][0] = false;
		}

		$config		= JFactory::getConfig();
		$tmp_dest	= $config->get('tmp_path');

		// Unpack the downloaded package file
		$package	= JInstallerHelper::unpack($tmp_dest . '/' . $p_file);

		// Get an installer instance
		$installer	= JInstaller::getInstance();
		$update->set('type', $package['type']);

		// Install the package
		if (!$installer->update($package['dir'])) {
			// There was an error updating the package
			if ( is_null($package['type']) ) {
				$package['type'] = "COMPONENT";
			}
			$msg = JText::sprintf('COM_INSTALLER_MSG_UPDATE_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_'.strtoupper($package['type'])));
			$result[0][1] = $msg;
			$result[0][0] = false;
			
		} else {
			// Package updated successfully
			if ( is_null($package['type']) ) {
				$package['type'] = "COMPONENT";
			}
			$msg = JText::sprintf('COM_INSTALLER_MSG_UPDATE_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_'.strtoupper($package['type'])));
			$result[0][1] = $msg;
			$result[0][0] = true;
		}

		// Quick change
		$this->type = $package['type'];

		// Cleanup the install files
		if (!is_file($package['packagefile'])) {
			$config = JFactory::getConfig();
			$package['packagefile'] = $config->get('tmp_path') . '/' . $package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		return $result;
	}
	
	// Función que obtiene información del sistema (extraída del core)
	private function getInfo()
	{
		if (is_null($this->info))
		{
			$this->info = array();
			$version = new JVersion;
			$db = JFactory::getDbo();

			if (isset($_SERVER['SERVER_SOFTWARE']))
			{
				$sf = $_SERVER['SERVER_SOFTWARE'];
			}
			else
			{
				$sf = getenv('SERVER_SOFTWARE');
			}

			$this->info['php']			= php_uname();
			$this->info['dbversion']	= $db->getVersion();
			$this->info['dbcollation']	= $db->getCollation();
			$this->info['phpversion']	= phpversion();
			$this->info['server']		= $sf;
			$this->info['sapi_name']	= php_sapi_name();
			$this->info['version']		= $version->getLongVersion();
			//$this->info['platform']		= $platform->getLongVersion();
			$this->info['useragent']	= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
		}
	}
	
	// Función que devuelve información sobre las extensiones no actualizadas
	private function getNotUpdatedExtensions(){
		
		// Habilitamos los sitios deshabilitados
		$enable = $this->enableSites();
		
		// Purgamos la caché y lanzamos la tarea
		$find = $this->findUpdates();
		
		$db = JFactory::getDBO();
		
		// Extraemos el la información de las extensiones que necesitan ser actualizadas, que se caracterizan porque su extension_id es distinto a cero
		$query = 'SELECT update_id,extension_id,name,type,version FROM #__updates WHERE extension_id != 0';
		$db->setQuery( $query );
		$extensions = $db->loadRowList();
						
		// Devolvemos el resultado en formato JSON
		return json_encode($extensions);
		
	
	}
	
	/**
	 * Finds updates for an extension.
	 *
	 * @param   int  $eid            Extension identifier to look for
	 * @param   int  $cache_timeout  Cache timout
	 *
	 * @return  boolean Result
	 *
	 * @since   1.6
	 *
	 * Original en /administrator/components/com_installer/models/update.php
	 */
	public function findUpdates($eid = 0, $cache_timeout = 0)
	{
		// Purge the updates list
		$this->purge();

		$updater = JUpdater::getInstance();
		$updater->findUpdates($eid, $cache_timeout);
		return true;
	}
	
	/**
	 * Removes all of the updates from the table.
	 *
	 * @return  boolean result of operation
	 *
	 * @since   1.6
	 *
	 * Original en /administrator/components/com_installer/models/update.php
	 */
	public function purge()
	{
		$db = JFactory::getDbo();

		// Note: TRUNCATE is a DDL operation
		// This may or may not mean depending on your database
		$db->setQuery('TRUNCATE TABLE #__updates');
		if ($db->execute())
		{
			// Reset the last update check timestamp
			$query = $db->getQuery(true)
				->update($db->quoteName('#__update_sites'))
				->set($db->quoteName('last_check_timestamp') . ' = ' . $db->quote(0));
			$db->setQuery($query);
			$db->execute();
			
		}		
	}
	
	/**
	 * Enables any disabled rows in #__update_sites table
	 *
	 * @return  boolean result of operation
	 *
	 * @since   1.6
	 *
	 * Original en /administrator/components/com_installer/models/update.php
	 */
	public function enableSites()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update('#__update_sites')
			->set('enabled = 1')
			->where('enabled = 0');
		$db->setQuery($query);
		$db->execute();		
	}
	
	/* Función que busca si una extensión pasada como argumento utiliza el mecanismo de actualización de Akeeba LiveUpdate */
	private function LookForPro($extension_id) {
	
		// Inicializamos las variables
		$found = array();
		$db = JFactory::getDBO();
		
		// Extraemos el nombre de la extensión...
		$query = "SELECT element FROM #__updates WHERE extension_id={$extension_id}";
		$db->setQuery( $query );
		$db->execute();
		$extension_name = $db->loadResult();
		
		// ... y miramos si existen los archivos usados por AKeeba Live Update	
		if ( (file_exists(JPATH_ROOT. DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $extension_name . DIRECTORY_SEPARATOR . 'liveupdate' . DIRECTORY_SEPARATOR . 'liveupdate.php')) && (file_exists(JPATH_ROOT. DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $extension_name . DIRECTORY_SEPARATOR . 'liveupdate' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'model.php')) ) {
			$found[0][1] = $this->Update_LiveUpdate($extension_name);
			$found[0][0] = true;			
		}
		
		return $found;		
	
	}
	
	/* Función que actualiza una extensión pasada como argumento que usa el mecanismo de Akeeba LiveUpdate */
	private function Update_LiveUpdate($extension_name)
	{
		// Inicializamos las variables
		$message = JText::_('COM_SECURITYCHECKPRO_UPDATE_SUCCESSFUL');;
		
		// Download 
		require_once JPATH_ROOT. DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $extension_name . DIRECTORY_SEPARATOR . 'liveupdate' . DIRECTORY_SEPARATOR . 'liveupdate.php';
		require_once JPATH_ROOT. DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $extension_name . DIRECTORY_SEPARATOR . 'liveupdate' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'model.php';
		
		// Do we need to update?
		$updateInformation = LiveUpdate::getUpdateInformation();
		if(!$updateInformation->hasUpdates) {
			$message = JText::_('COM_SECURITYCHECKPRO_NO_UPDATES_AVAILABLE');
			return $message;
		}

		$model = new LiveupdateModel();
		$ret = $model->download();

		$session = JFactory::getSession();
		$target		= $session->get('target', '', 'liveupdate');
		$tempdir	= $session->get('tempdir', '', 'liveupdate');

		if(!$ret) {
			// An error ocurred :(
			$this->data = 'Could not download the update package';
			$this->status = self::STATUS_ERROR;
			$this->cipher = self::CIPHER_RAW;
			$message = $this->data;				
		} else {
			// Extract
			$ret = $model->extract();

			JLoader::import('joomla.filesystem.file');
			JFile::delete($target);
			
			if(!$ret) {
				// An error ocurred :(
				$this->data = 'Could not extract the update package';
				$this->status = self::STATUS_ERROR;
				$this->cipher = self::CIPHER_RAW;
				$message = $this->data;	
			} else {
				// Install
				$ret = $model->install();

				if(!$ret) {
					// An error ocurred :(
					$this->data = 'Could not install the update package';
					$this->status = self::STATUS_ERROR;
					$this->cipher = self::CIPHER_RAW;
					$message = $this->data;					
				} else {
					// Update cleanup
					$ret = $model->cleanup();

					JLoader::import('joomla.filesystem.file');
					JFile::delete($target);					
				}
			}
		}
		return $message;
	}
	
	/* Función que actualiza todas las extensiones desactualizadas */
	private function UpdateAll() {
	
		$db = JFactory::getDBO();
		
		// Extraemos el la información de las extensiones que necesitan ser actualizadas, que se caracterizan porque su extension_id es distinto a cero
		$query = 'SELECT extension_id FROM #__updates WHERE extension_id != 0';
		$db->setQuery( $query );
		$extension_id_array = $db->loadRowList();
		
		/* Inicializamos la variable 'data', que contendrán las extensiones a actualizar. Tendrán el formato {"0":"1","1":"43"}, donde el primer número indica
		el elemento del array y el segundo el id de la extensión */
		$data = array();
		/* Esta variable contendrá el índice del array de extensiones; si no lo usamos, el array json que devolvemos tendría la sintaxis {"0":"1","0":"43"} en lugar de {"0":"1","1":"43"}, por lo que todos los índices del array sería el 0 */
		$indice = 0;
		
		foreach( $extension_id_array as $extension_id ){ 			
			foreach( $extension_id as $key => $value ){ 
				// Extraemos el par key:value y lo almacenamos en el array
				$key = '"' . addslashes($key+$indice) . '"';
				$value = '"' . addslashes($value) . '"';
				$data[] = $key . ":" . $value; 
				$indice++;
			}
		}
		
		// Extraemos los datos del array y los codificamos en formato JSON
        $data_json = "{" . implode( ",", $data ) . "}";
		
		// A continuación, llamamos al método UpdateExtension y le pasamos el array en formato json obtenido. Éste se encargará de todo el proceso.
		$this->UpdateExtension($data_json,true);		
		
	}
	
	/* Función que actualiza un array de extensiones (en formato json) pasado como argumento */
	private function UpdateExtension($extension_id_array,$updateall = false) {
	
		// Inicializamos las variables
		$array_result = array();
		$db = JFactory::getDBO();
		jimport( 'joomla.updater.update' );
		
		// Tenemos que actualizar todas las extensiones. En este caso recibimos un string en lugar de un array (en formato json).
		if ( $updateall ) {
			$extension_id_array = json_decode($extension_id_array,true);			
		}
		
		// Para cada extensión, realizamos su actualización
		foreach($extension_id_array as $extension_id) {
		
			if ( $extension_id == 700 ) { // Si el id de la extensión es el 700, se trata del core de Joomla. Lo tratamos de forma diferente.
				$this->UpdateCore();
			} else if ( $extension_id == 0 ) { // Si el id de la extensión es el 0, significa que queremos actualizar todas las extensiones.
				$this->UpdateAll();
				// Devolvemos el array que contiene los resultados
				return $this->data;
			} else {			
				// Extraemos el nombre de la extensión para mostrarlo en los resultados
				$query = "SELECT name FROM #__updates WHERE extension_id={$extension_id}";
				$db->setQuery( $query );
				$db->execute();
				$extension_name = $db->loadResult();
				
				// Extraemos la url de la extensión, que contendrá la información de actualización
				$query = "SELECT detailsurl FROM #__updates WHERE extension_id={$extension_id}";
				$db->setQuery( $query );
				$db->execute();
				$detailsurl = $db->loadResult();
								
				// Instanciamos el objeto JUpdate y cargamos los detalles de la actualización
				$update = new JUpdate();
				$update->loadFromXML($detailsurl);
				
				// Le pasamos a la función de actualización el objeto con los detalles de la actualización
				$result= $this->install_update($update);				
				
				if ( !$result[0][0] ) {  // Se ha producido un error, ¿es una versión que utiliza algún mecanismo de pago?
					$result_pro = $this->LookForPro($extension_id);
					array_push($array_result,array($extension_name,$result_pro));						
				} else {
					// Guardamos el id de la extensión junto con el resultado
					array_push($array_result,array($extension_name,$result));					
				}			
			}
						
		}	
			
		// Devolvemos el resultado
		$this->data = array(
			'update_result'		=> $array_result
		);		
		
	}
}