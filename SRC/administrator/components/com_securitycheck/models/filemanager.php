<?php
/**
* Modelo FileManager para el Componente Securitycheckpro
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Chequeamos si el archivo está incluído en Joomla!
defined('_JEXEC') or die();

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\Filesystem\File as JFile;
use Joomla\Filesystem\Folder as JFolder;


/**
* Modelo Filemanager
*/
class SecuritychecksModelFileManager extends JModelLegacy
{

/** @var object Pagination */
var $_pagination = null;

/** @var int Total number of files of Pagination */
var $total = 0;

/** @var array The files to process */
private $Stack = array();

/** @var int Total numbers of file/folders in this site */
public $files_scanned = 0;

/** @var int Numbers of files/folders with  incorrect permissions*/
public $files_with_incorrect_permissions = 0;

/** @var array Skip subdirectories and files of these directories */
private $skipDirs = array();

/** @var int Percent of files processed each time */
private $last_percent_permissions = 0;

/** @var int Percent of files processed each time */
private $files_processed_permissions = 0;

/** @var boolean Task completed */
private $task_completed = false;

/** @var string Path to the folder where scans will be stored */
private $folder_path = '';

/** @var string filemanager's name */
private $filemanager_name = '';


function __construct()
{
	parent::__construct();
	
	// Establecemos la ruta donde se almacenarán los escaneos
	$this->folder_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_securitycheck'.DIRECTORY_SEPARATOR.'scans';
	
	// Establecemos el tamaño máximo de memoria que el script puede consumir
	$params = JComponentHelper::getParams('com_securitycheck');
	$memory_limit = $params->get('memory_limit','128M');
	ini_set('memory_limit',$memory_limit);
	
	// Añadimos los directorios 'cache', 'tmp' y 'log' a la lista de excepciones
	$this->skipDirs[] = rtrim(JPATH_CACHE,'/');
	$this->skipDirs[] = rtrim(JPATH_ROOT.'/cache','/');
	if(version_compare(JVERSION, '3.0', 'ge')) {
		$this->skipDirs[] = rtrim(JFactory::getConfig()->get('tmp_path', JPATH_ROOT.'/tmp'), '/');
		$this->skipDirs[] = rtrim(JFactory::getConfig()->get('log_path', JPATH_ROOT.'/logs'), '/');
	} else {
		$this->skipDirs[] = rtrim(JFactory::getConfig()->getValue('tmp_path', JPATH_ROOT.'/tmp'), '/');
		$this->skipDirs[] = rtrim(JFactory::getConfig()->getValue('log_path', JPATH_ROOT.'/logs'), '/');
	}
	
	// Obtenemos las excepciones extablecidas por el usuario
	$params = JComponentHelper::getParams('com_securitycheck');
	$exceptions = $params->get('file_manager_path_exceptions',null);
	
	// Creamos un array que contendrá rutas de archivos o directorios exentos del chequeo de permisos
	$exceptions_array= null;
	if ( !is_null($exceptions) ) {
		$exceptions_array = explode(',',$exceptions);
		// Añadimos las excepciones al array de excepciones
		foreach($exceptions_array as $exception_path) {
			$this->skipDirs[] = rtrim($exception_path, '/');
		}
	}

	// Obtenemos el nombre de los escaneos anteriores
	$db = $this->getDbo();
	$query = $db->getQuery(true)
		->select(array($db->quoteName('storage_value')))
		->from($db->quoteName('#__securitycheck_storage'))
		->where($db->quoteName('storage_key').' = '.$db->quote('filemanager_resume'));
	$db->setQuery($query);
	$stack = $db->loadResult();
	$stack = json_decode($stack, true);
	
	if( (!empty($stack)) && (isset($stack['filename'])) ) {
		$this->filemanager_name = $stack['filename'];
	}
}

/* Función que obtiene todos los archivos del sitio */
public function getFiles($root = null)
{
	/* Cargamos el lenguaje del sitio */
	$lang = JFactory::getLanguage();
	$lang->load('com_securitycheck',JPATH_ADMINISTRATOR);
		
	if(empty($root)) $root = JPATH_ROOT;

	if(empty($root)) {
		$root = '..';
		$root = realpath($root);
	}
		
	jimport('joomla.filesystem.folder');

	$files_name = JFolder::files($root,'.',true,true);
	// Buscamos si existe el archivo .htaccess en la ruta a escanear (sólo lo buscamos en la ruta base, no en subdirectorios)
	if ( file_exists($root . DIRECTORY_SEPARATOR . ".htaccess") ) {
		$files_name[] = $root . DIRECTORY_SEPARATOR . ".htaccess";
	}
	$this->files_scanned += count($files_name);
	
	$files = array();
	if ( !empty($files_name) ) {
		foreach($files_name as $file) {
			
			$this->files_processed_permissions++;
				$percent_permissions = intval(round(($this->files_processed_permissions / $this->files_scanned) * 100));
				if ( (($percent_permissions - $this->last_percent_permissions) >= 10) && ($percent_permissions < 100) ) {

					$this->set_campo_filemanager("files_scanned",$percent_permissions);
					$this->last_percent_permissions = $percent_permissions;
				} else if ( $percent_permissions == 100 ) {
					$this->task_completed = true;
				}
				
			/* Dejamos sin efecto el tiempo máximo de ejecución del script. Esto es necesario cuando existen miles de archivos a escanear */
			set_time_limit(0);
			$safe = 1;
			// Comprobamos que si el archivo está explícitamente en la lista de excepciones
			 if ( (!is_null($this->skipDirs)) && (in_array($file,$this->skipDirs)) ) {
				$safe = (int) 2;
			} else {
				 // Comprobamos si el archivo pertenece a un directorio que está incluido en la lista de excepciones
				if ( !is_null($this->skipDirs) ) {
					$i = 0;
					foreach ($this->skipDirs as $excep){
						if ( strstr($file . DIRECTORY_SEPARATOR,$excep . DIRECTORY_SEPARATOR) ) {  // Añadimos una barra invertida a la comparación por si la excepción es un directorio
							$safe = (int) 2;
						}
						$i++;
					}
				}
			}
			
			$permissions = $this->file_perms($file);
			// Obtenemos la extensión del archivo
			$last_part = explode('.',$file);
			$extension = end($last_part);
			if ( ($permissions > '0644') && ($safe != 2) ) {
				$safe = 0;
				$this->files_with_incorrect_permissions = $this->files_with_incorrect_permissions+1;
			}
			$last_part = explode('/',$file);
			$last_part_2 = explode('.',end($last_part));
			$files[] = array(
				'path'      => $file,
				'name'		=> reset($last_part_2),
				'extension' => $extension,
				'size'      => filesize($file),
				'kind'    => $lang->_('COM_SECURITYCHECK_FILEMANAGER_FILE'),
				'permissions' => $permissions,
				'last_modified' => date('Y-m-d H:i:s',filemtime($file)),
				'safe' => $safe
			);
		}
	}
	
	if( !empty($files) ) {
		$this->Stack = array_merge($this->Stack, $files);
	}
}

/* Función que obtiene todos los directorios del sitio */
public function getDirectories($root = null)
{
	/* Cargamos el lenguaje del sitio */
	$lang = JFactory::getLanguage();
	$lang->load('com_securitycheck',JPATH_ADMINISTRATOR);
	
	if(empty($root)) $root = JPATH_ROOT;
	
	$folders_name = JFolder::folders($root,'.',true,true);
	$this->files_scanned += count($folders_name);
	
	//Inicializamos el porcentaje de ficheros escaneados
	$this->set_campo_filemanager("files_scanned",0);
	
	// Actualizamos la BBDD para mostrar información del estado del chequeo
	$this->set_campo_filemanager('estado','IN_PROGRESS');
	
	$folders = array();
	if ( !empty($folders_name) ) {
		foreach($folders_name as $folder) {
		
			$this->files_processed_permissions++;
			$percent_permissions = intval(round(($this->files_processed_permissions / $this->files_scanned) * 100));
			if ( (($percent_permissions - $this->last_percent_permissions) >= 10) && ($percent_permissions < 100) ) {
				$this->set_campo_filemanager("files_scanned",$percent_permissions);
				$this->last_percent_permissions = $percent_permissions;
			} else if ( $percent_permissions == 100 ) {
				$this->task_completed = true;
			}
				
			$safe = 1;
			 // Comprobamos que si el archivo está explícitamente en la lista de excepciones
			if ( (!is_null($this->skipDirs)) && (in_array($folder,$this->skipDirs)) ) {
				$safe = (int) 2;
			}
		
			$permissions = $this->file_perms($folder);
			if ( ($permissions > '0755') && ($safe != 2) ) {
				$safe = 0;
				$this->files_with_incorrect_permissions = $this->files_with_incorrect_permissions+1;
			}
			$last_part = explode('/',$folder);
			$folders[] = array(
				'path'      => $folder,
				'name'		=> end($last_part),
				'extension' => null,
				'size'      => null,
				'kind'    => $lang->_('COM_SECURITYCHECK_FILEMANAGER_DIRECTORY'),
				'permissions' => $permissions,
				'last_modified' => date('Y-m-d H:i:s',filemtime($folder)),
				'safe' => $safe
			);
		}
	}
	
	if( !empty($folders) ) {
		$this->Stack = array_merge($this->Stack, $folders);
	}
}

/* Función que guarda en la BBDD, en formato json, el contenido de un array con todos los ficheros y directorios */
private function saveStack()
{
	$result_permissions = true;
	$result_permissions_resume = true;
		
	// Creamos el nuevo objeto query
	$db = $this->getDbo();
	
	// Buscamos ficheros antiguos que no hayan sido borrados...
	$old_files = JFolder::files($this->folder_path,'.',false,true,array('index.html','web.config','.htaccess',$this->filemanager_name));
	
	// ... y los borramos
	foreach($old_files as $old_file) {
		JFile::delete($old_file);		
	}
	
	// Borramos el fichero del escaneo anterior...
	if ( file_exists($this->folder_path.DIRECTORY_SEPARATOR.$this->filemanager_name) ) {
		$delete_permissions_file = JFile::delete($this->folder_path.DIRECTORY_SEPARATOR.$this->filemanager_name);
	}
		
	// ... y escribimos el contenido del array a un nuevo fichero
	$filename = $this->generateKey();
	try {
		$content_permissions = utf8_encode(json_encode(array('files_folders'	=> $this->Stack)));
		$content_permissions = "#<?php die('Forbidden.'); ?>" . PHP_EOL . $content_permissions;
		$result_permissions = JFile::write($this->folder_path.DIRECTORY_SEPARATOR.$filename, $content_permissions);		
			
	} catch (Exception $e) {	
		$this->set_campo_filemanager('estado','DATABASE_ERROR');
		$result_permissions = false;
	}
				
	$query = $db->getQuery(true)
		->delete($db->quoteName('#__securitycheck_storage'))
		->where($db->quoteName('storage_key').' = '.$db->quote('filemanager_resume'));
	$db->setQuery($query);
	$db->execute();
		
	$object = (object)array(
		'storage_key'	=> 'filemanager_resume',
		'storage_value'	=> utf8_encode(json_encode(array(
			'files_scanned'		=> $this->files_scanned,
			'files_with_incorrect_permissions'	=> $this->files_with_incorrect_permissions,
			'last_check'	=> $this->currentDateTime_func(),
			'filename'		=> $filename
		)))
	);
		
	try {
		$result_permissions_resume = $db->insertObject('#__securitycheck_storage', $object);
	} catch (Exception $e) {		
		$this->set_campo_filemanager('estado','DATABASE_ERROR');
		$result_permissions_resume = false;
	}
				
	if ( ($this->task_completed == true) && ($result_permissions) && ($result_permissions_resume) ) {
		$this->set_campo_filemanager('estado','ENDED');
	}
	$this->set_campo_filemanager("files_scanned",100);
}

/* Función que obtiene un array con los datos que serán mostrados en la opción 'file manager' */
function loadStack($opcion,$field)
{
	$db = $this->getDbo();
	
	// Establecemos el tamaño máximo de memoria que el script puede consumir
	$params = JComponentHelper::getParams('com_securitycheck');
	$memory_limit = $params->get('memory_limit','128M');
	ini_set('memory_limit',$memory_limit);
	
	switch ($opcion) {
		case "permissions":
			// Leemos el contenido del fichero
			$stack = file_get_contents($this->folder_path.DIRECTORY_SEPARATOR.$this->filemanager_name);
			
			if(empty($stack)) {
				$this->Stack = array();
				return;
			}
			break;
		case "filemanager_resume":
			$query = $db->getQuery(true)
				->select(array($db->quoteName('storage_value')))
				->from($db->quoteName('#__securitycheck_storage'))
				->where($db->quoteName('storage_key').' = '.$db->quote('filemanager_resume'));
			$db->setQuery($query);
			$stack = $db->loadResult();
			
			if(empty($stack)) {
				$this->files_scanned = 0;
				$this->files_with_incorrect_permissions = 0;
				return;
			}
			break;
	}

	$stack = json_decode($stack, true);
	
	switch ($field) {
		case "file_manager":
			$this->Stack = array_splice($stack['files_folders'], $this->getState('limitstart'), $this->getState('limit'));
			return ($this->Stack);
		case "files_scanned":
			$this->files_scanned = $stack['files_scanned'];
			return ($this->files_scanned);
		case "files_with_incorrect_permissions":
			$this->files_with_incorrect_permissions = $stack['files_with_incorrect_permissions'];
			return ($this->files_with_incorrect_permissions);
		case "last_check":
			return ($stack['last_check']);
	}
}

/* Función que escanea el sitio para obtener los permisos de los archivos y directorios */
function scan(){
	// Obtenemos la ruta sobre la que vamos a hacer el chequeo
	$params = JComponentHelper::getParams('com_securitycheck');
	$file_check_path = $params->get('file_manager_path',JPATH_ROOT);
	
	if ( ($file_check_path == "JPATH_ROOT") || ($file_check_path == JPATH_ROOT) ) {
		$file_check_path = JPATH_ROOT;
	} else {
		$file_check_path = JPATH_ROOT . DIRECTORY_SEPARATOR . $file_check_path;
	}
	
	$this->getDirectories($file_check_path);
	$this->getFiles($file_check_path);
	$this->saveStack();	
}

/* Función para establecer el valor de un campo de la tabla '#_securitycheckpro_file_manager' */
function set_campo_filemanager($campo,$valor)
{
	// Creamos el nuevo objeto query
	$db = $this->getDbo();
	$query = $db->getQuery(true);
	
	// Sanitizamos las entradas
	$campo_sanitizado = $db->escape($campo);
	$valor_sanitizado = $db->Quote($db->escape($valor));

	// Construimos la consulta...
	$query->update('#__securitycheck_file_manager');
	$query->set($campo_sanitizado .'=' .$valor_sanitizado);
	$query->where('id=1');

	// ... y la lanzamos
	$db->setQuery( $query );
	$db->execute();
}

/* Función para obtener el valor de un campo de la tabla '#_securitycheckpro_file_manager' */
function get_campo_filemanager($campo)
{
	// Creamos el nuevo objeto query
	$db = $this->getDbo();
	$query = $db->getQuery(true);
	
	// Sanitizamos las entradas
	$campo_sanitizado = $db->Quote($db->escape($campo));
	
	// Construimos la consulta...
	$query->select($campo);
	$query->from('#__securitycheck_file_manager');
	$query->where('id=1');
	
	// ... y la lanzamos
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	// Devolvemos el resultado
	return $result;	
}

/* Obtiene los permisos de un archivo o directorio en formato octal */
function file_perms($file) {
	return substr( sprintf('%o', fileperms($file)), -4 );

}

/* Función que devuelve la hora y fecha actuales */
public function currentDateTime_func() {
    return (date('Y-m-d H:i:s'));
}

/* Inicializa todas las tablas que contienen información */
function initialize_database(){
	// Creamos el nuevo objeto query
	$db = $this->getDbo();
	
	// Borramos la tabla '#__securitycheck_file_permissions' (no debería existir)
	$query = 'DROP TABLE IF EXISTS #__securitycheck_file_permissions';
	$db->setQuery( $query );
	$db->execute();
	
	// Actualizamos los campos de la tabla '#__securitycheck_file_manager'
	$query = 'UPDATE #__securitycheck_file_manager SET last_check=null,files_scanned=0,files_with_incorrect_permissions=0,estado="ENDED" where id=1';
	$db->setQuery( $query );
	$db->execute();
	
	// Obtenemos el nombre del escaneo anterior...
	$query = $db->getQuery(true)
		->select(array($db->quoteName('storage_value')))
		->from($db->quoteName('#__securitycheck_storage'))
		->where($db->quoteName('storage_key').' = '.$db->quote('filemanager_resume'));
	$db->setQuery($query);
	$stack = $db->loadResult();
	$stack = json_decode($stack, true);
	
	if(!empty($stack)) {
		$this->filemanager_name = $stack['filename'];
	}
	
	// ... y borramos el fichero
	$delete_permissions_file = JFile::delete($this->folder_path.DIRECTORY_SEPARATOR.$this->filemanager_name);
	
	// Nos aseguramos que los permisos de la carpeta 'scans' son los correctos
	chmod($this->folder_path,0755);
	
	// Borramos la tabla '#__securitycheck_storage'
	$query = $db->getQuery(true)
		->delete($db->quoteName('#__securitycheck_storage'))
		->where( '(' .$db->quoteName('storage_key').' = '.$db->quote('fileintegrity_resume') .') OR (' .$db->quoteName('storage_key').' = '.$db->quote('filemanager_resume') .')' );
	$db->setQuery($query);
	$db->execute();
}

/* Obtiene la diferencia en horas entre dos tareas */
function get_timediff() {
	(int) $interval = 0;
	
	$last_check_start_time = new DateTime(date('Y-m-d H:i:s',strtotime($this->get_campo_filemanager('last_check'))));
	$now = new DateTime($this->currentDateTime_func());
	$interval = date_diff($last_check_start_time,$now);
	// Extraemos el número total de días entre las dos fechas. Si es cero, no ha transcurrido ningún día, por lo que devolvemos la diferencia de horas. Si ha transcurrido un día o más, devolvemos un valor suficientemente alto para activar los disparadores necesarios
	if ( $interval->format('%a') == 0 ) {
		// Extraemos el número total de horas que han pasado desde el último chequeo
		$interval = $interval->format('%h');
	} else {
		$interval = 20000;
	}	
	return $interval;
}

/*Genera un nombre de fichero .php  de 20 caracteres */
function generateKey() {
	
	$chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; //available characters
	srand( ( double ) microtime() * 1000000 ); //random seed
	$pass = '' ;
		
	for ( $i = 1; $i <= 20; $i++ ) {
		$num = rand() % 33;
		$tmp = substr( $chars, $num, 1 );
		$pass = $pass . $tmp;
	}

	return $pass.'.php';	
}

}