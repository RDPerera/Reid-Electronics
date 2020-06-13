<?php
/**
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModel;

if(!class_exists('JoomlaCompatModel')) {
	if(interface_exists('JModel')) {
		abstract class JoomlaCompatModel extends JModelLegacy {}
	} else {
		class JoomlaCompatModel extends JModel {}
	}
}

class SecuritycheckModel extends JoomlaCompatModel
{

/**
* Array de datos
* @var array
*/
var $_data;
/**
/**
* Total items
* @var integer
*/
var $_total = null;
/**
/**
* Objeto Pagination
* @var object
*/
var $_pagination = null;
/**
* Columnas de #__securitycheck
* @var integer
*/
var $_dbrows = null;

private $config = null;

private $defaultConfig = array(
	'blacklist'			=> '',
	'whitelist'		=> '',
	'dynamic_blacklist'		=> 1,
	'dynamic_blacklist_time'		=> 600,
	'dynamic_blacklist_counter'		=> 5,
	'blacklist_email'		=> 0,
	'priority'		=> 'Blacklists first',
	'methods'			=> 'GET,POST,REQUEST',
	'mode'			=> 1,
	'logs_attacks'			=> 1,
	'log_limits_per_ip_and_day'			=> 0,
	'redirect_after_attack'			=> 1,
	'redirect_options'			=> 1,
	'second_level'			=> 1,
	'second_level_redirect'			=> 1,
	'second_level_limit_words'			=> 3,
	'second_level_words'			=> 'drop,update,set,admin,select,user,password,concat,login,load_file,ascii,char,union,from,group by,order by,insert,values,pass,where,substring,benchmark,md5,sha1,schema,version,row_count,compress,encode,information_schema,script,javascript,img,src,input,body,iframe,frame',
	'email_active'			=> 0,
	'email_subject'			=> 'Securitycheck Pro alert!',
	'email_body'			=> 'Securitycheck Pro has generated a new alert. Please, check your logs.',
	'email_add_applied_rule'			=> 1,
	'email_to'			=> 'youremail@yourdomain.com',
	'email_from_domain'			=> 'me@mydomain.com',
	'email_from_name'			=> 'Your name',
	'email_max_number'			=> 20,
	'check_header_referer'			=> 1,
	'check_base_64'			=> 1,
	'base64_exceptions'			=> 'com_hikashop',
	'strip_tags_exceptions'			=> 'com_jdownloads,com_hikashop,com_phocaguestbook',
	'duplicate_backslashes_exceptions'			=> 'com_kunena',
	'line_comments_exceptions'			=> 'com_comprofiler',
	'sql_pattern_exceptions'			=> '',
	'if_statement_exceptions'			=> '',
	'using_integers_exceptions'			=> 'com_dms,com_comprofiler,com_jce,com_contactenhanced',
	'escape_strings_exceptions'			=> 'com_kunena,com_jce',
	'lfi_exceptions'			=> '',
	'second_level_exceptions'			=> '',	
	'session_protection_active'			=> 1,
	'session_hijack_protection'			=> 1,
	'tasks'			=> 'alternate',
	'launch_time'			=> 2,
	'periodicity'			=> 1,
	'control_center_enabled'	=> '0',
	'secret_key'	=> '',
	'exclude_exceptions_if_vulnerable'	=>	1,
);


function __construct()
{
	parent::__construct();

	global $mainframe, $option;
		
	$mainframe = JFactory::getApplication();
	
	$jinput = $mainframe->input;
 
	// Obtenemos las variables de paginación de la petición
	$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');	
	$limitstart = $jinput->get('limitstart',0,'int');
	
	// En el caso de que los límites hayan cambiado, los volvemos a ajustar
	$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
	$this->setState('limit', $limit);
	$this->setState('limitstart', $limitstart);	
}

protected function populateState()
{
	// Inicializamos las variables
	$app		= JFactory::getApplication();
	
	$extension_type = $app->getUserStateFromRequest('filter.extension_type', 'filter_extension_type');
	$this->setState('filter.extension_type', $extension_type);
	$lists = $app->getUserStateFromRequest('filter.lists_search', 'filter_lists_search');
	$this->setState('filter.lists_search', $lists);
				
	parent::populateState();
}

/* Obtiene el valor de una opción de configuración */
public function getValue($key, $default = null, $key_name = 'cparams')
{
	if(is_null($this->config)) $this->load($key_name);
	
	if(version_compare(JVERSION, '3.0', 'ge')) {
		return $this->config->get($key, $default);
	} else {
		return $this->config->getValue($key, $default);
	}
}

/* Establece el valor de una opción de configuración ' */
public function setValue($key, $value, $save = false, $key_name = 'cparams')
{
	if(is_null($this->config)) {
		$this->load($key_name);
	}
		
	if(version_compare(JVERSION, '3.0', 'ge')) {
		$x = $this->config->set($key, $value);
	} else {
		$x = $this->config->setValue($key, $value);
	}
	if($save) $this->save($key_name);
	return $x;
}

/* Hace una consulta a la tabla espacificada como parámetro ' */
public function load($key_name)
{
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query 
		->select($db->quoteName('storage_value'))
		->from($db->quoteName('#__securitycheck_storage'))
		->where($db->quoteName('storage_key').' = '.$db->quote($key_name));
	$db->setQuery($query);
	$res = $db->loadResult();
		
	if(version_compare(JVERSION, '3.0', 'ge')) {
		$this->config = new JRegistry();
	} else {
		$this->config = new JRegistry('securitycheck');
	}
	if(!empty($res)) {
		$res = json_decode($res, true);
		$this->config->loadArray($res);
	}
}

/* Guarda la configuración en la tabla pasada como parámetro */
public function save($key_name)
{
	if(is_null($this->config)) {
		$this->load($key_name);
	}
		
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	
	$data = $this->config->toArray();
	$data = json_encode($data);
	
	$query
		->delete($db->quoteName('#__securitycheck_storage'))
		->where($db->quoteName('storage_key').' = '.$db->quote($key_name));
	$db->setQuery($query);
	$db->execute();
		
	$object = (object)array(
		'storage_key'		=> $key_name,
		'storage_value'		=> $data
	);
	$db->insertObject('#__securitycheck_storage', $object);
}

/* Obtiene la configuración de los parámetros del Firewall Web */
function getConfig()
{
	if(interface_exists('JModel')) {
		$params = JModelLegacy::getInstance('ControlCenter','SecuritychecksModel');
	} else {
		$params = JModel::getInstance('ControlCenter','SecuritychecksModel');
	}
	
	$config = array();
	foreach($this->defaultConfig as $k => $v) {
		$config[$k] = $params->getValue($k, $v, 'plugin');
	}
	return $config;
}


/* Obtiene la configuración de los parámetros del Control Center */
function getControlCenterConfig()
{
	if(interface_exists('JModel')) {
		$params = JModelLegacy::getInstance('ControlCenter','SecuritychecksModel');
	} else {
		$params = JModel::getInstance('ControlCenter','SecuritychecksModel');
	}
	
	$config = array();
	foreach($this->defaultConfig as $k => $v) {
		$config[$k] = $params->getValue($k, $v, 'controlcenter');
	}
	return $config;
}

/* Guarda la modificación de los parámetros de la opción 'Mode' */
function saveConfig($newParams, $key_name = 'cparams')
{
	if(interface_exists('JModel')) {
		$params = JModelLegacy::getInstance('ControlCenter','SecuritychecksModel');
	} else {
		$params = JModel::getInstance('ControlCenter','SecuritychecksModel');
	}

	foreach($newParams as $key => $value)
	{
		// Do not save unnecessary parameters
		if(!array_key_exists($key, $this->defaultConfig)) continue;
		$params->setValue($key,$value,'',$key_name);
	}
	
	$params->save($key_name);
}

/* Limpia un string de caracteres no válidos según la opción especificada */
function clearstring($string_to_clear, $option = 1)
{
	// Eliminamos espacios y retornos de carro entre los elementos
	switch ($option) {
		case 1:
			// Transformamos el string array para poder manejarlo mejor
			$string_to_array = explode(',',$string_to_clear);
			// Eliminamos los espacios en blanco al principio y al final de cada elemento
			$string_to_array = array_map( function ($element) { return trim($element); },$string_to_array );
			// Eliminamos los retornos de carro, nuevas líneas y tabuladores de cada elemento
			$string_to_array = array_map( function ($element) { return str_replace(array("\n", "\t", "\r"), '', $element); },$string_to_array );
			// Volvemos a convertir el array en string
			$string_to_clear = implode(',',$string_to_array);
			break;
		case 2:
			$string_to_clear = str_replace(array(" ", "\n", "\t", "\r"), '', $string_to_clear);
			break;
	} 
		
	return $string_to_clear;
}

/* Función para chequear si una ip pertenece a una lista en la que podemos especificar rangos. Podemos tener una ip del tipo 192.168.*.* y una ip 192.168.1.1 entraría en ese rango */
function chequear_ip_en_lista($ip,$lista){
	$aparece = false;
	$array_ip_peticionaria = explode('.',$ip);
		
	if (strlen($lista) > 0) {
		// Eliminamos los caracteres en blanco antes de introducir los valores en el array
		$lista = str_replace(' ','',$lista);
		$array_ips = explode(',',$lista);
		if ( is_int(array_search($ip,$array_ips)) ){	// La ip aparece tal cual en la lista
			$aparece = true;
		} else {
			foreach ($array_ips as &$indice){
					if (strrchr($indice,'*')){ // Chequeamos si existe el carácter '*' en el string; si no existe podemos ignorar esta ip
					$array_ip_lista = explode('.',$indice); // Formato array:  $array_ip_lista[0] = '192' , $array_ip_lista[1] = '168'
					$k = 0;
					$igual = true;
					while (($k <= 3) && ($igual)) {
						if ($array_ip_lista[$k] == '*') {
							$k++;
						}else {
							if ($array_ip_lista[$k] == $array_ip_peticionaria[$k]) {
								$k++;
							} else {
								$igual = false;
							}
						}
					}
					if ($igual) { // $igual será true cuando hayamos recorrido el array y todas las partes del mismo coincidan con la ip que realiza la petición
						$aparece = true;
						return $aparece;
					}
				}
			}
		}
	}
	return $aparece;
}


function encrypt($text_to_encrypt,$encryption_key) {
	// Generate an initialization vector
	// This *MUST* be available for decryption as well
	$iv = openssl_random_pseudo_bytes(8);
	$iv = bin2hex($iv);
				
	// Encrypt $data using aes-128-cbc cipher with the given encryption key and 
	// our initialization vector. The 0 gives us the default options, but can
	// be changed to OPENSSL_RAW_DATA or OPENSSL_ZERO_PADDING
	$encrypted = openssl_encrypt($text_to_encrypt, 'aes-128-cbc', $encryption_key, 0, $iv);
				
	$encrypted = $encrypted . ':' . $iv;
	
	return $encrypted;
}

function decrypt($text_to_decrypt,$encryption_key) {
	// To decrypt, separate the encrypted data from the initialization vector ($iv)
	$parts = explode(':', $text_to_decrypt);
	// $parts[0] = encrypted data
	// $parts[1] = initialization vector

	$decrypted = openssl_decrypt($parts[0], 'aes-128-cbc', $encryption_key, 0, $parts[1]);
	
	return $decrypted;
}

/* Función para determinar si el plugin pasado como argumento ('1' -> Securitycheck Pro, '2' -> Securitycheck Pro Cron, '3' -> Securitycheck Pro Update Database) está habilitado o deshabilitado. También determina si el plugin Securitycheck Pro Update Database (opción 4)  está instalado */
function PluginStatus($opcion) {
		
	$db = JFactory::getDBO();
	if ( $opcion == 1 ) {
		$query = 'SELECT enabled FROM #__extensions WHERE name="System - Securitycheck"';
	} else if ( $opcion == 2 ) {
		$query = 'SELECT enabled FROM #__extensions WHERE name="System - Securitycheck Pro Cron"';
	} else if ( $opcion == 3 ) {
		$query = 'SELECT enabled FROM #__extensions WHERE name="System - Securitycheck Pro Update Database"';
	} else if ( $opcion == 4 ) {
		$query = 'SELECT COUNT(*) FROM #__extensions WHERE name="System - Securitycheck Pro Update Database"';
	} else if ( $opcion == 5 ) {
		$query = 'SELECT enabled FROM #__extensions WHERE name="System - Securitycheck Spam Protection"';
	} else if ( $opcion == 6 ) {
		$query = 'SELECT COUNT(*) FROM #__extensions WHERE name="System - Securitycheck Spam Protection"';
	}
	
	$db->setQuery( $query );
	$db->execute();
	$enabled = $db->loadResult();
	
	return $enabled;
}

/* Función que consulta el valor de una bbdd pasados como argumentos */
function get_campo_bbdd($bbdd,$campo)
{
	// Creamos el nuevo objeto query
	$db = JFactory::getDbo();
		
	// Consultamos el campo de la bbdd
	$query = $db->getQuery(true)
		->select($db->quoteName($campo))
		->from($db->quoteName('#__' . $bbdd));
	$db->setQuery($query);
	$valor = $db->loadResult();
	
	return $valor;
}

}