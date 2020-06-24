<?php
/**
* Modelo Securitychecks para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Chequeamos si el archivo está incluído en Joomla!
defined('_JEXEC') or die();

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;

// Load library
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_securitycheck'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'loader.php');

/**
* Modelo Securitycheck
*/
class SecuritychecksModelSecuritychecks extends SecuritycheckModel
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

function __construct()
{
	parent::__construct();

	global $mainframe, $option;
		
	$mainframe = JFactory::getApplication();	
	$jinput = $mainframe->input;
 
	// Obtenemos las variables de paginación de la petición
	$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$jinput = JFactory::getApplication()->input;
	$limitstart = $jinput->get('limitstart', 0, 'int');

	// En el caso de que los límites hayan cambiado, los volvemos a ajustar
	$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

	$this->setState('limit', $limit);
	$this->setState('limitstart', $limitstart);	
}

/* 
* Función para obtener todo los datos de la BBDD 'securitycheck' en forma de array 
*/
function getTotal()
{
// Cargamos el contenido si es que no existe todavía
if (empty($this->_total)) {
	$query = $this->_buildQuery();
	$this->_total = $this->_getListCount($query);	
}
return $this->_total;
}

/* 
* Función para la paginación 
*/
function getPagination()
{
// Cargamos el contenido si es que no existe todavía
if (empty($this->_pagination)) {
	jimport('joomla.html.pagination');
$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
}
return $this->_pagination;
}

/*
* Devuelve todos los componentes almacenados en la BBDD 'securitycheck'
*/
function _buildQuery()
{
$query = ' SELECT * '
. ' FROM #__securitycheck '
;
return $query;
}

/*
Obtiene la versión de un determinado componente en una de las BBDD. Pasamos como parámetro la BBDD donde buscar, el campo de la tabla sobre el que hacerlo y
el nombre que buscamos.
 */
function version_componente($nombre,$database,$campo)
{

// Creamos el nuevo objeto query
$db = $this->getDbo();
$query = $db->getQuery(true);
	
// Sanitizamos las entradas
$database = $db->escape($database);
$campo = $db->escape($campo);
$nombre = $db->Quote($db->escape($nombre));

// Construimos la consulta
$query->select('Installedversion');
$query->from('#__' .$database);
$query->where($campo .'=' .$nombre);

$db->setQuery( $query );
$result = $db->loadResult();
return $result;
}

/*
* Compara los componentes de la BBDD de 'securitycheck' con los de 'securitycheck_db" y actualiza los componentes que sean vulnerables 
*/
function chequear_vulnerabilidades(){
	/* Extraemos los componentes de 'securitycheck'*/
	$db = JFactory::getDBO();
	$query = $this->_buildQuery();
	$db->setQuery( $query );
	$components = $db->loadAssocList();
	/* Extraemos los componentes vulnerables de 'securitycheck_db'*/
	$db = JFactory::getDBO();
	$query = 'SELECT * FROM #__securitycheck_db';
	$db->setQuery( $query );
	$vuln_components = $db->loadAssocList();	
	$i = 0;
	foreach ($components as $indice){
		$nombre = $components[$i]['Product'];
		$j = 0;
		$componente_vulnerable = false;  // Indica si la versión del componente es vulnerable
		$actualizar_campo_vulnerable = false;  // Indica si tenemos que actualizar el campo 'Vulnerable' del componente porque es vulnerable
		$valor_campo_vulnerable = "Si"; // Valor que tendrá el campo 'Vulnerable' cuando se actualice. También puede tener el valor '???'.
		foreach ($vuln_components as $indice2){
			$nombre_vuln = $vuln_components[$j]['Product'];
			if ($nombre == $nombre_vuln){  // El componente es vulnerable, chequeamos la versión del producto y la de Joomla 
				$modvulnversion = $vuln_components[$j]['modvulnversion']; //Modificador sobre la versión del componente
				$db_version = $components[$i]['Installedversion']; // Versión del componente instalada
				$vuln_version = $vuln_components[$j]['Vulnerableversion']; // Versión del componente vulnerable
				
				// Usamos la funcion 'version_compare' de php para comparar las versiones del producto instalado y la del componente vulnerable
				$version_compare = version_compare($db_version,$vuln_version,$modvulnversion);
				if ( $version_compare ){
					$componente_vulnerable = true;					
				} else if ($vuln_version == '---') { //No conocemos la versión del producto vulnerable
					$componente_vulnerable = true;					
				}
				
				if ($componente_vulnerable){ //La versión del componente es vulnerable; chequeamos si lo es para nuestra versión de Joomla
					// Inicializamos las variables 
					$vuln_joomla_version = ""; // Versión de Joomla para la que es vulnerable la extensión
					$modvulnjoomla = ""; // Modificador de la versión de Joomla
					$local_joomla_branch = explode(".",JVERSION); // Versión de Joomla instalada
					$array_element = 0; // Índice del array de versiones y modificadores
					
					/* Array con todas las versiones y modificadores para las que es vulnerable el producto */
					$modvulnjoomla_array = explode(",",$vuln_components[$j]['modvulnjoomla']);
					$vuln_joomla_version_array = explode(",",$vuln_components[$j]['Joomlaversion']); // Versión de Joomla para la que es vulnerable el componente
										
					foreach ($vuln_joomla_version_array as $joomla_version) {
						$vulnerability_branch = explode(".",$joomla_version);
						if ( $vulnerability_branch[0] == $local_joomla_branch[0] ) {							
							$vuln_joomla_version = $vuln_joomla_version_array[$array_element];							
							$modvulnjoomla = $modvulnjoomla_array[$array_element];
							break;
						}
						$array_element++;
					}
					
					/* Obtenemos y guardamos la versión de Joomla */
					$jversion = new JVersion();
					$joomla_version = $jversion->getShortVersion();
					switch ($vuln_joomla_version) {
						case "Notdefined": // El componente es vulnerable pero no sabemos para qué versión de Joomla. 
							$actualizar_campo_vulnerable = true;
							$valor_campo_vulnerable = "???";
							break;
						default: // El componente es vulnerable y sabemos para qué versión de Joomla
							// Usamos la funcion 'version_compare' de php para comparar las versiones de Joomla
							$joomla_version_compare = version_compare($joomla_version,$vuln_joomla_version,$modvulnjoomla);
							if ( $joomla_version_compare ){
								$actualizar_campo_vulnerable = true;
								$valor_campo_vulnerable = "Si";
							} else {
								/* Nos aseguramos que el componenete tiene el valor "No" en el campo "Vulnerable". Esto es útil cuando se cambia la versión	del componente y pasa de 'vulnerable' o 'Notdefined' a 'no vulnerable' */
								$valor_campo_vulnerable = "No";
								$res_actualizar = $this->actualizar_registro($nombre,'securitycheck','Product',$valor_campo_vulnerable,'Vulnerable');
							}
					}
					if ($actualizar_campo_vulnerable) {
						$res_actualizar = $this->actualizar_registro($nombre_vuln,'securitycheck','Product',$valor_campo_vulnerable,'Vulnerable');
						if ($res_actualizar){ // Se ha actualizado la BBDD correctamente							
						} else {
							JFactory::getApplication()->enqueueMessage('COM_SECURITYCHECK_UPDATE_VULNERABLE_FAILED' ."'" .$nombre_vuln ."'", 'error');
						}
					}
				} else {
					/* Nos aseguramos que el componenete tiene el valor "No" en el campo "Vulnerable". Esto es útil cuando se cambia la versión	del componente y pasa de 'vulnerable' o 'Notdefined' a 'no vulnerable' */
					$valor_campo_vulnerable = "No";
					$res_actualizar = $this->actualizar_registro($nombre,'securitycheck','Product',$valor_campo_vulnerable,'Vulnerable');				
				}
			}
		$j++;
		}
	$i++;
	}
}


/*
Actualiza el campo '$campo_set'  de un registro en la BBDD pasada como parámetro.
 */
function actualizar_registro($nombre,$database,$campo,$nuevo_valor,$campo_set)
{

// Creamos el nuevo objeto query
$db = $this->getDbo();
$query = $db->getQuery(true);
	
// Sanitizamos las entradas
$nombre = $db->Quote($db->escape($nombre));
$database = $db->escape($database);
$campo = $db->escape($campo);
$nuevo_valor = $db->Quote($db->escape($nuevo_valor));
$campo_set = $db->escape($campo_set);


// Construimos la consulta
$query->update('#__' .$database);
$query->set($campo_set .'=' .$nuevo_valor);
$query->where($campo .'=' .$nombre);

$db->setQuery( $query );
$result = $db->execute();
return $result;

}


/*
Busca el nombre de un registro en la BBDD pasada como parámetro. Devuelve true si existe y false en caso contrario.
 */
function buscar_registro($nombre,$database,$campo)
{
$encontrado = false;

// Creamos el nuevo objeto query
$db = $this->getDbo();
$query = $db->getQuery(true);
	
// Sanitizamos las entradas
$database = $db->escape($database);
$campo = $db->escape($campo);
$nombre = $db->Quote($db->escape($nombre));

// Construimos la consulta
$query->select('*');
$query->from('#__' .$database);
$query->where($campo .'=' .$nombre);

$db->setQuery( $query );
$result = $db->loadAssocList();

if ( $result ){
$encontrado = true;
}

return $encontrado;
}

/*
Inserta un registro en la BBDD. Devuelve true si ha tenido éxito y false en caso contrario.
 */
function insertar_registro($nombre,$version,$tipo)
{
$db = JFactory::getDBO();

// Sanitizamos las entradas
$nombre = $db->escape($nombre);
$version = $db->escape($version);
$tipo = $db->escape($tipo);

$valor = (object) array(
'Product' => $nombre,
'Installedversion' => $version,
'Type' => $tipo
);

$result = $db->insertObject('#__securitycheck', $valor, 'id');
return $result;
}

/*
Compara la BBDD #_securitycheck con #_extensions para eliminar componentes desinstalados del sistema y que figuran en dicha BBDD. Los componentes que 
figuran en #_securitycheck se pasan como variable */
function eliminar_componentes_desinstalados()
{
$db = JFactory::getDBO();
$query = 'SELECT * FROM #__securitycheck';
$db->setQuery( $query );
$db->execute();
$regs_securitycheck = $db->loadAssocList();
$i = 0;
$comp_eliminados = 0;
foreach ($regs_securitycheck as $indice){
	$nombre = $regs_securitycheck[$i]['Product'];
	$database = 'extensions';
	$buscar_componente = $this->buscar_registro( $nombre, $database, 'element' );
	if ( !($buscar_componente) ){ /*Si el componente no existe en #_extensions, lo eliminamos  de #_securitycheck */
		if ($nombre != 'Joomla!'){ /* Este componente no existe como extensión*/
			$db = JFactory::getDBO();
			// Sanitizamos las entradas
			$nombre = $db->Quote($db->escape($nombre));
			$query = 'DELETE FROM #__securitycheck WHERE Product=' .$nombre;
			$db->setQuery( $query );
			$db->execute();
			$comp_eliminados++;			
		}
	}	
	$i++;
} 
$mensaje_eliminados = JText::_('COM_SECURITYCHECK_DELETED_COMPONENTS');
$jinput->set('comp_eliminados', $mensaje_eliminados .$comp_eliminados);
}

/*
Extrae los nombres de los componentes instalados y actualiza la BBDD de nuestro componente con dichos nombres.
Un ejemplo de cómo almacena Joomla esta información es el siguiente:

{"legacy":false,"name":"Securitycheck","type":"component","creationDate":"2011-04-12","author":"Jose A. Luque","copyright":"Copyright Info",
"authorEmail":"contacto@protegetuordenador.com","authorUrl":"http:\/\/www.protegetuordenador.com","version":"1.00",
"description":"COM_SECURITYCHECK_DESCRIPTION","group":""} 

Esta función debe extraer la información que nos interesa truncando substrings.
 */
function actualizarbbdd($registros)
{
$i = 0;
/* Obtenemos y guardamos la versión de Joomla */
$jversion = new JVersion();
$joomla_version = $jversion->getShortVersion();
$buscar_componente = $this->buscar_registro( 'Joomla!', 'securitycheck', 'Product' );
if ( $buscar_componente ){ 
	$version_componente = $this->version_componente( 'Joomla!', 'securitycheck', 'Product' );	
	if ($joomla_version <> $version_componente){
	 /* Si la versión instalada en el sistema es distinta de la de la bbdd, actualizamos la bbdd. Esto sucede cuando se actualiza la versión de Joomla */
	 $resultado_update = $this->actualizar_registro('Joomla!', 'securitycheck', 'Product', $joomla_version, 'InstalledVersion');
	 $mensaje_actualizados = JText::_('COM_SECURITYCHECK_CORE_UPDATED');
	 $jinput->set('core_actualizado', $mensaje_actualizados);	 
	}
} else {  /* Hacemos un insert en la base de datos con el nombre y la versión del componente */
$resultado_insert = $this->insertar_registro( 'Joomla!', $joomla_version, 'core' );
} 
$componentes_actualizados = 0;
foreach ($registros as $indice){
	$nombre = $registros[$i]['element'];
	/*Sobre el ejemplo, 'sub_str1' contiene    "version":"1.00","description":"COM_SECURITYCHECK_DESCRIPTION","group":""} */
	$sub_str1 = strstr($registros[$i]['manifest_cache'], "version");
	/*Sobre el ejemplo, 'sub_str2' contiene   :"1.00","description":"COM_SECURITYCHECK_DESCRIPTION","group":""} */
	$sub_str2 = strstr($sub_str1, ':');
	/*Sobre el ejemplo, 'sub_str3' contiene   "1.00" */	
	$sub_str3 = substr($sub_str2, 1, strpos($sub_str2,',')-1); 
	/*Sobre el ejemplo, 'version' contiene   1.00 */
	$version = trim($sub_str3,'"');
	$buscar_componente = $this->buscar_registro( $nombre, 'securitycheck', 'Product' );
	if ( $buscar_componente )
	{ /* El componente existe en la BBD; hacemos un update de la versión .  */
		$version_componente = $this->version_componente( $nombre, 'securitycheck', 'Product' );
		if ($version <> $version_componente){		
			/* Si la versión instalada en el sistema es distinta de la de la bbdd, actualizamos la bbdd. Esto sucede cuando se actualiza el componente */
			$resultado_update = $this->actualizar_registro($nombre, 'securitycheck', 'Product', $version, 'InstalledVersion');
			$componentes_actualizados++;			
		}
	} else {  /* Hacemos un insert en la base de datos con el nombre y la versión del componente */
		$resultado_insert = $this->insertar_registro( $nombre, $version, 'component');	
		$componentes_actualizados++;
	} 
	$i++;
}

if ($componentes_actualizados > 0){
	$mensaje_actualizados = JText::_('COM_SECURITYCHECK_COMPONENTS_UPDATED');
	$jinput = JFactory::getApplication()->input;
	$jinput->set('componentes_actualizados', $mensaje_actualizados .$componentes_actualizados);
}

/* Chequeamos si existe algún componente el la BBDD que haya sido desinstalado. Esto se comprueba comparando el número de registros en #_securitycheck ($dbrows)
y el de #_extensions  ($registros)*/
$query = $this->_buildQuery();
$this->_dbrows = $this->_getListCount($query);
$registros_long = count($registros);

if ( $this->_dbrows == $registros_long + 1)  /* $dbrows siempre contiene un elemento más que $registros_long porque incluye el core de Joomla */
{
} else {
$this->eliminar_componentes_desinstalados();
}

/* Chequeamos los componentes instalados con la lista de vulnerabilidades conocidas y actualizamos los componentes vulnerables */
$this->chequear_vulnerabilidades();
}

/*
Busca los componentes instaladas en el equipo. 
 */
function buscar()
{
	
	$jinput = JFactory::getApplication()->input;
	
$db = JFactory::getDBO();
$query = 'SELECT * FROM #__extensions WHERE (state=0 and type="component")';
$db->setQuery( $query );
$db->execute();
$num_rows = $db->getNumRows();
$result = $db->loadAssocList();
$this->actualizarbbdd( $result );
$eliminados = $jinput->get('comp_eliminados',0,'int');
$jinput->set('eliminados', $eliminados);
$core_actualizado = $jinput->get('core_actualizado',0,'int');
$jinput->set('core_actualizado', $core_actualizado);
$comps_actualizados = $jinput->get('componentes_actualizados',0,'int');
$jinput->set('comps_actualizados', $comps_actualizados);
$comp_ok = JText::_( 'COM_SECURITYCHECK_CHECK_OK' );
$jinput->set('comp_ok', $comp_ok);
return true;
}
	
/*
* Obtiene los datos de la BBDD 'securitycheck'
*/
function getData()
{
// Cargamos el contenido si es que no existe todavía
if (empty( $this->_data ))
{
$this-> buscar();
$query = $this->_buildQuery();
$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
}
return $this->_data;
}

/* Función que obtiene el id del plugin de: '1' -> Securitycheck Pro Update Database  */
function get_plugin_id($opcion) {

	$db = JFactory::getDBO();
	if ( $opcion == 1 ) {
		$query = 'SELECT extension_id FROM #__extensions WHERE name="System - Securitycheck Pro Update Database" and type="plugin"';
	} 
	$db->setQuery( $query );
	$db->execute();
	$id = $db->loadResult();
	
	return $id;
}

}