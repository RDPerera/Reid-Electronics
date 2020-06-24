<?php
/**
* Modelo SysInfos para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Chequeamos si el archivo está incluído en Joomla!
defined('_JEXEC') or die();

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Version as JVersion;
use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;
use Joomla\CMS\Component\ComponentHelper as JComponentHelper;

/**
* Modelo Securitycheck
*/
class SecuritychecksModelSysinfo extends SecuritycheckModel
{

/* @var array somme system values  */
protected $info = null;

/**
 * method to get the system information
 *
 * @return array system information values
 */
public function &getInfo()
{
	if (is_null($this->info)){
		$this->info = array();
		$version = new JVersion;
		$db = JFactory::getDBO();
				
		// Obtenemos el tamaño de la variable 'max_allowed_packet' de Mysql
		$db->setQuery('SHOW VARIABLES LIKE \'max_allowed_packet\'');
		$keys = $db->loadObjectList();
		$array_val = get_object_vars($keys[0]);
		$tamanno_max_allowed_packet = (int) ($array_val["Value"]/1024/1024);
		
		// Obtenemos el tamaño máximo de memoria establecido
		$params = JComponentHelper::getParams('com_securitycheck');
		$memory_limit = $params->get('memory_limit','128M');
		
		// Obtenemos las opciones de configuración
		require_once JPATH_ROOT.'/components/com_securitycheck/models/json.php';
		$values = new SecuritychecksModelJson();
		$values->getStatus(false);
		
		// Obtenemos las opciones del Cpanel
		require_once JPATH_ROOT.'/administrator/components/com_securitycheck/models/cpanel.php';
		$CpanelOptions = new SecuritychecksModelCpanel();
		$firewall_plugin_enabled = $CpanelOptions->PluginStatus(1);
		$spam_protection_plugin_enabled = $CpanelOptions->PluginStatus(5);
				
		// Obtenemos los parámetros del Firewall
		$plugin = JPluginHelper::getPlugin('system','securitycheck');
		$plugin = new JRegistry($plugin->params);
		$exclude_exceptions_if_vulnerable = $plugin->get('exclude_exceptions_if_vulnerable',0);
		$logs_attacks = $plugin->get('logs_attacks',1);
		$second_level = $plugin->get('second_level',1);
		$strip_tags_exceptions = $plugin->get('strip_tags_exceptions','');
		$sql_pattern_exceptions = $plugin->get('sql_pattern_exceptions','');
		$lfi_exceptions = $plugin->get('lfi_exceptions','');
		$session_protection_active = $plugin->get('session_protection_active','');
		$FirewallOptions = array(
			'exclude_exceptions_if_vulnerable'	=>	0,
			'logs_attacks'	=>	0,
			'second_level'	=>	0,
			'strip_tags_exceptions'	=>	'',
			'sql_pattern_exceptions'	=>	'',
			'lfi_exceptions'	=>	'',
			'session_protection_active'	=>	0
		);
		$FirewallOptions['exclude_exceptions_if_vulnerable'] = $exclude_exceptions_if_vulnerable;
		$FirewallOptions['logs_attacks'] = $logs_attacks;
		$FirewallOptions['second_level'] = $second_level;
		$FirewallOptions['strip_tags_exceptions'] = $strip_tags_exceptions;
		$FirewallOptions['sql_pattern_exceptions'] = $sql_pattern_exceptions;
		$FirewallOptions['lfi_exceptions'] = $lfi_exceptions;
		$FirewallOptions['session_protection_active'] = $session_protection_active;
				
		// Obtenemos las opciones de protección .htaccess
		require_once JPATH_ROOT.'/administrator/components/com_securitycheck/models/protection.php';
		$ConfigApplied = new SecuritychecksModelProtection();
		$ConfigApplied = $ConfigApplied->GetConfigApplied();	
		
		$this->info['phpversion']	= phpversion();
		$this->info['version']		= $version->getLongVersion();
		//$this->info['platform']		= $platform->getLongVersion();
		$this->info['max_allowed_packet']		= $tamanno_max_allowed_packet;
		$this->info['memory_limit']		= $memory_limit;
		//Security
		$this->info['coreinstalled']		= $values->data['coreinstalled'];
		$this->info['corelatest']		= $values->data['corelatest'];
		$this->info['files_with_incorrect_permissions']		= $values->data['files_with_incorrect_permissions'];
		$this->info['files_with_bad_integrity']		= -1;
		$this->info['vuln_extensions']		= $values->data['vuln_extensions'];
		$this->info['suspicious_files']		= -1;
		// Si el directorio de administración está protegido con contraseña, marcamos la opción de protección del backend como habilitada
		if ( !$ConfigApplied['hide_backend_url'] ) {
			if ( file_exists(JPATH_ADMINISTRATOR. DIRECTORY_SEPARATOR . '.htpasswd') ) {				
				$ConfigApplied['hide_backend_url'] = '1';
			}
		}
		$this->info['backend_protection']		= $ConfigApplied['hide_backend_url'];
		// Existe el fichero kickstart.php
		$this->info['kickstart_exists']		= $this->check_kickstart();
		$this->info['firewall_options']		= $FirewallOptions;		
		$this->info['twofactor_enabled']	= $this->get_two_factor_status();		
		$this->info['overall_joomla_configuration']		= $this->getOverall($this->info,1);
		//Extension status
		$this->info['firewall_plugin_enabled']		= $firewall_plugin_enabled;
		$this->info['spam_protection_plugin_enabled']		= $spam_protection_plugin_enabled;
		$this->info['firewall_options']		= $FirewallOptions;
		$this->info['last_check']		= $values->data['last_check'];
		$this->info['last_check_integrity']		= $values->data['last_check_integrity'];
		//Htaccess protection
		$this->info['htaccess_protection']		= $ConfigApplied;
		$this->info['overall_web_firewall']		= $this->getOverall($this->info,2);			
	}
	return $this->info;
}

// Obtiene el estado del segundo factor de autenticación de Joomla (Google y Yubikey)
function get_two_factor_status() {
	$enabled = 0;
	
	$db = $this->getDbo();
	$query = $db->getQuery(true)
		->select(array($db->quoteName('enabled')))
		->from($db->quoteName('#__extensions'))
		->where($db->quoteName('name').' = '.$db->quote('plg_twofactorauth_totp'));
	$db->setQuery($query);
	$enabled = $db->loadResult();
	
	if ( $enabled == 0 ) {
		$query = $db->getQuery(true)
			->select(array($db->quoteName('enabled')))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('name').' = '.$db->quote('plg_twofactorauth_yubikey'));
		$db->setQuery($query);
		$enabled = $db->loadResult();
	}
	
	return $enabled;
}

// Chequea si el fichero kickstart.php existe en la raíz del sitio. Esto sucede cuando se restaura un sitio y se olvida (junto con algún backup) eliminarlo.
public function check_kickstart() {
	$found = false;	
	$akeeba_kickstart_file = JPATH_ROOT . DIRECTORY_SEPARATOR . "kickstart.php";
	
	if ( file_exists($akeeba_kickstart_file) ){
		if ( strpos(file_get_contents($akeeba_kickstart_file),"AKEEBA") !== false ) {
			$found = true;
		}		
	}
	
	return $found;
	
}

// Obtiene el porcentaje general de cada una de las barras de progreso
public function getOverall($info,$opcion) {
	// Inicializamos variables
	$overall = 0;
		
	switch ($opcion) {
		// Porcentaje de progreso de  Joomla Configuration
		case 1:
			if ( $info['kickstart_exists'] ) {
				return 2;
			}
			if ( version_compare($info['coreinstalled'],$info['corelatest'],'==') ) {
				$overall = $overall + 20;
			}
			if ( $info['files_with_incorrect_permissions'] == 0 ) {
				$overall = $overall + 20;
			}			
			if ( $info['vuln_extensions'] == 0 ) {
				$overall = $overall + 40;
			}
			if ( $info['backend_protection'] ) {
				$overall = $overall + 10;
			}
			if ( $info['twofactor_enabled'] == 1 ) {
				$overall = $overall + 10;
			}
			break;
		case 2:
			if ( $info['firewall_plugin_enabled'] ) {
				$overall = $overall + 20;				
				// Configuración del firewall				
				if ( $info['firewall_options']['logs_attacks'] ) {
					$overall = $overall + 6;					
				}
				if ( $info['firewall_options']['second_level'] ) {
					$overall = $overall + 6;					
				}
				if ( !(strstr($info['firewall_options']['strip_tags_exceptions'],'*')) ) {
					$overall = $overall + 12;					
				}
				if ( !(strstr($info['firewall_options']['sql_pattern_exceptions'],'*')) ) {
					$overall = $overall + 10;										
				}
				if ( !(strstr($info['firewall_options']['lfi_exceptions'],'*')) ) {
					$overall = $overall + 12;										
				}
				if ( $info['firewall_options']['session_protection_active'] ) {
					$overall = $overall + 2;					
				}
				if ( $info['spam_protection_plugin_enabled'] ) {
					$overall = $overall + 2;					
				}
				
				// Cron 
				$last_check = new DateTime(date('Y-m-d',strtotime($this->info['last_check'])));
				$now = new DateTime(date('Y-m-d',strtotime(date('Y-m-d H:i:s'))));
					
				// Extraemos los días que han pasado desde el último chequeo
				(int) $interval = $now->diff($last_check)->format("%a");
																		
				if ( $interval < 2 ) {
					$overall = $overall + 30;					
				} else {
					
				}	
				
					
			} else {
				return 2;
			}
			break;		
	}
	return $overall;
}

}