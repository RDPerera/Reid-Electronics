<?php
/**
* Modelo Protection para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Chequeamos si el archivo está incluído en Joomla!
defined('_JEXEC') or die();

use Joomla\CMS\Factory as JFactory;
use Joomla\Filesystem\File as JFile;

/**
* Modelo Securitycheck
*/
class SecuritychecksModelProtection extends JModelLegacy
{

/* Definimos las variables */
var $defaultConfig = array(
	'hide_backend_url' => '',
);

var $ConfigApplied = array(
	'hide_backend_url' => 0,
);

private $config = null;

/* Obtiene el valor de una opción de configuración de 'htaccess protection' */
public function getValue($key, $default = null)
{
	if(is_null($this->config)) $this->load();
	
	if(version_compare(JVERSION, '3.0', 'ge')) {
		return $this->config->get($key, $default);
	} else {
		return $this->config->getValue($key, $default);
	}
}

/* Establece el valor de una opción de configuración de 'htaccess protection' */
public function setValue($key, $value, $save = false)
{
	if(is_null($this->config)) {
		$this->load();
	}
		
	if(version_compare(JVERSION, '3.0', 'ge')) {
		$x = $this->config->set($key, $value);
	} else {
		$x = $this->config->setValue($key, $value);
	}
	if($save) $this->save();
	return $x;
}

/* Hace una consulta a la tabla #__securitycheck_storage, que contiene la configuración de 'htaccess protection' */
public function load()
{
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query 
		->select($db->quoteName('storage_value'))
		->from($db->quoteName('#__securitycheck_storage'))
		->where($db->quoteName('storage_key').' = '.$db->quote('cparams'));
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

/* Guarda la configuración de 'htaccess protection' con a la tabla #__securitycheck_storage */
public function save()
{
	if(is_null($this->config)) {
		$this->load();
	}
		
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	
	$data = $this->config->toArray();
	$data = json_encode($data);
		
	$query
		->delete($db->quoteName('#__securitycheck_storage'))
		->where($db->quoteName('storage_key').' = '.$db->quote('cparams'));
	$db->setQuery($query);
	$db->execute();
		
	$object = (object)array(
		'storage_key'		=> 'cparams',
		'storage_value'		=> $data
	);
	$db->insertObject('#__securitycheck_storage', $object);
}

/* Obtiene la configuración de los parámetros de la opción 'Protection' */
function getConfig()
{
	$config = array();
	foreach($this->defaultConfig as $k => $v) {
		$config[$k] = $this->getValue($k, $v);
	}
	return $config;
}

/* Guarda la modificación de los parámetros de la opción 'Protection' */
function saveConfig($newParams)
{
	foreach($newParams as $key => $value)
	{
		$this->setValue($key,$value);
	}

	$this->save();
}

/* Devuelve TRUE si el fichero pasado como argumento existe en la raíz del sitio. */
public function ExistsFile($filename)
{
	return file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.$filename);
}

/* Hace una copia del archivo .htaccess si existe*/
function Make_Backup()
{
	return JFile::copy(JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess',JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess.backup');
}

/* Modificamos los valores del array 'ConfigApplied' segúnn las opciones que ya hayan sido aplicadas al archivo .htaccess existentes */
public function GetConfigApplied(){
	/* Variable que almacenará el contenido del archivo .htaccess */
	$rules_applied = null;
	/* Variable que indicará si existe el/los strings en el archivo .htaccess */
	$exists = false;
	
	if ( file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess') ) {
		$rules_applied = file_get_contents(JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess');
		
		/* 'hide_backend_url' habilitado? */
		if ( stripos($rules_applied,"RewriteCond %{QUERY_STRING} !^" . $this->getValue("hide_backend_url")) ) {
		 	$this->ConfigApplied['hide_backend_url'] = 1;
		}
	}
	
	return $this->ConfigApplied;
}

/* Modifica o crea el archivo .htaccess según las opciones escogidas por el usuario */
public function protect()
{
	// Site's url
	$site_url = str_replace('http://',"",JURI::base());
	
	$rules = null;
			
	$ExistsHtaccess = $this->ExistsFile('.htaccess');  // Comprobamos si existe el archivo .htaccess
	if ( $ExistsHtaccess) {  // Si existe, hacemos un backup
		$backup_sucess = $this->Make_Backup();
		// Leemos el contenido del fichero .htaccess existente y lo guardamos en el buffer.
		$rules .= file_get_contents(JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess');
		// Obtenemos los valores que ya están aplicados para evitar duplicar valores
		$this-> ConfigApplied = $this->GetConfigApplied();
		// Borramos el fichero .htaccess 
		$this->delete_htaccess();
	} else {  /* Si no existe el fichero, copiamos el que incorpora Joomla por defecto */
		// Leemos el contenido del fichero .htaccess existente y lo guardamos en el buffer.
		$rules .= file_get_contents(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'default_joomla_htaccess.inc');
		$status = JFile::copy(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'default_joomla_htaccess.inc',JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess');
	}
	
	/* Comprobamos si hay que ocultar la url del backend */
	if ( !is_null($this->getValue("hide_backend_url")) ) {
		if ( ( !$ExistsHtaccess ) || (( $ExistsHtaccess ) &&  ( !$this->ConfigApplied['hide_backend_url'] )) ) {
			$rules .= PHP_EOL . "## Begin Securitycheck Pro Hide Backend Url";
			$rules .= PHP_EOL . "RewriteCond %{HTTP_REFERER} !" . $site_url;
			$rules .= PHP_EOL . "RewriteCond %{QUERY_STRING} !^" . $this->getValue("hide_backend_url");
			$rules .= PHP_EOL . "RewriteRule ^.*administrator/? /not_found [R,L]";
			$rules .= PHP_EOL . "## End Securitycheck Pro Hide Backend Url";
		}
	}
	
	/* Comprobamos si hay algo que aplicar */
	if ( !is_null($rules) ) {
		// Escribimos el contenido del buffer en el fichero '.htaccess'
		$status = JFile::write(JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess', $rules);
	}
	
	return $status;
}

/* Borra el fichero .htaccess*/
function delete_htaccess()
{
	return JFile::delete(JPATH_SITE.DIRECTORY_SEPARATOR.'.htaccess');
}

/*Genera las reglas equivalentes a .htaccess en ficheros NGINX */
function generate_rules() {
	$rules = '';
	
	if ( !is_null($this->getValue("hide_backend_url")) ) {
		$rules .= "# Begin Securitycheck Hide Backend Url" . PHP_EOL;
		$rules .= "\tset \$rule_1 0;" . PHP_EOL;
		$rules .= "\tif (\$http_referer !~* administrator ) { set \$rule_1 6\$rule_1; }" . PHP_EOL;
		$rules .= "\tif (\$args !~ \"^" . $this->getValue("hide_backend_url") . "\") { set \$rule_1 9\$rule_1; }" . PHP_EOL;
		$rules .= "\tif (\$rule_1 = 960) {" . PHP_EOL;
		$rules .= "\t\trewrite ^(.*/)?administrator /not_found redirect;" . PHP_EOL;
		$rules .= "\t\trewrite ^/administrator(.*)$ /not_found redirect;" . PHP_EOL;
		$rules .= "\t}" . PHP_EOL;
		$rules .= "# End Securitycheck Hide Backend Url";
	}
	
	return $rules;
 
}

}