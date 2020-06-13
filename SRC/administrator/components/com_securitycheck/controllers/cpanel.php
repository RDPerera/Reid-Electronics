<?php
/**
* Securitycheck Pro Cpanel Controller
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory as JFactory;


/**
 * The Control Panel controller class
 *
 */
class SecuritychecksControllerCpanel extends JControllerLegacy
{
	public function  __construct() {
		parent::__construct();
		
	}

	/**
	 * Displays the Control Panel 
	 */
	public function display($cachable = false, $urlparams = Array())
	{
		$jinput = JFactory::getApplication()->input;
		$jinput->set('view', 'cpanel');
		
		// Display the panel
		parent::display();
	}

	/* Acciones al pulsar el botón para establecer 'Easy Config' */
	function Set_Easy_Config(){
		$model = $this->getModel("cpanel");
	
		$applied = $model->Set_Easy_Config();
		
		echo $applied;
	}
	
	/* Acciones al pulsar el botón para establecer 'Default Config' */
	function Set_Default_Config(){
		$model = $this->getModel("cpanel");
	
		$applied = $model->Set_Default_Config();
		
		echo $applied;
	}
	
	/* Acciones al pulsar el botón 'Disable' de Update database */
	function disable_update_database(){
		$model = $this->getModel("cpanel");
		$model->disable_plugin('update_database');
		
		$this->setRedirect( 'index.php?option=com_securitycheck' );
		
	}
	
	/* Acciones al pulsar el botón 'Enable' de Update database */
	function enable_update_database(){
		$model = $this->getModel("cpanel");
		$model->enable_plugin('update_database');
		
		$this->setRedirect( 'index.php?option=com_securitycheck' );
		
	}
	
	/* Hace una consulta a la tabla especificada como parámetro */
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
	
	/* Acciones al pulsar el botón para exportar la configuración */
	function Export_config(){
		$db = JFactory::getDBO();
	
		// Obtenemos los valores de las distintas opciones del Firewall Web
		$query = $db->getQuery(true)
			->select(array('*'))
			->from($db->quoteName('#__securitycheck_storage'));
		$db->setQuery($query);
		$params = $db->loadAssocList();
			
		// Extraemos los valores de los array...
		$json_string = array_values($params);
		// ...Y los codificamos en formato json
		$json_string = json_encode($json_string);
		
		// Cargamos los parámetros del Control Center porque necesitamos eliminar su clave secreta
		$this->load("controlcenter");
		
		// Buscamos si el campo ha sido configurado
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$secret_key = $this->config->get("secret_key", false);
		} else {
			$secret_key = $this->config->getValue("secret_key", false);
		}
				
		// Si ha sido configurado, buscamos su valor en el string_json y lo borramos
		if ( $secret_key ) {
			$json_string = str_replace($secret_key,"",$json_string);
		}
							
		// Mandamos el contenido al navegador
		@ob_end_clean();	
		ob_start();	
		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment;filename=securitycheck_export.txt' );
		print $json_string;
		exit();
	}
	
/* Redirecciona las peticiones a System Info */
function Go_system_info()
{
	$this->setRedirect( 'index.php?option=com_securitycheck&controller=filemanager&view=sysinfo&'. JSession::getFormToken() .'=1' );
}

/* Acciones al pulsar el boton 'Enable' del Spam Protection */
	function enable_spam_protection(){
		$model = $this->getModel("cpanel");
		$model->enable_plugin('spam_protection');
		
		$this->setRedirect( 'index.php?option=com_securitycheck' );
		
	}
	
	/* Acciones al pulsar el botn 'Disable' de Spam Protection */
	function disable_spam_protection(){
		$model = $this->getModel("cpanel");
		$model->disable_plugin('spam_protection');
		
		$this->setRedirect( 'index.php?option=com_securitycheck' );
		
	}

}