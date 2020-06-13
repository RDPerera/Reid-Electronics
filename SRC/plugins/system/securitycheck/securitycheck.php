<?php
/*
* Securitychecks View para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
class plgSystemSecuritycheck extends JPlugin{
	function __construct( &$subject, $config ){
		parent::__construct( $subject, $config );		
	}
	
	
	/* Función para grabar los logs en la BBDD */
	function grabar_log($ip,$tag_description,$description,$type,$uri,$original_string,$component){
		$db = JFactory::getDBO();
		
		// Sanitizamos las entradas
		$ip = filter_var($ip, FILTER_SANITIZE_STRING);
		$ip = $db->escape($ip);
		$tag_description = filter_var($tag_description, FILTER_SANITIZE_STRING);
		$tag_description = $db->escape($tag_description);
		$description = filter_var($description, FILTER_SANITIZE_STRING);
		$description = $db->escape($description);
		$type = filter_var($type, FILTER_SANITIZE_STRING);
		$type = $db->escape($type);
		$uri = filter_var($uri, FILTER_SANITIZE_STRING);
		$uri = $db->escape($uri);
		$component = filter_var($component, FILTER_SANITIZE_STRING);
		$component = $db->escape($component);
		// Guardamos el string original en formato base64 para evitar problemas de seguridad
		$original_string = filter_var($original_string, FILTER_SANITIZE_STRING);
		$original_string = base64_encode($original_string);
		
		// Consultamos el último log para evitar duplicar entradas
		$query = "SELECT tag_description,original_string,ip from `#__securitycheck_logs` WHERE id=(SELECT MAX(id) from `#__securitycheck_logs`)" ;			
		$db->setQuery( $query );
		$row = $db->loadRow();
			
		$result_tag_description = $row['0'];
		$result_original_string = $row['1'];
		$result_ip = $row['2'];
			
		if ( (!($result_tag_description == $tag_description )) || (!($result_original_string == $original_string )) || (!($result_ip == $ip )) ){
			$sql = "INSERT INTO `#__securitycheck_logs` (`ip`, `time`, `tag_description`, `description`, `type`, `uri`, `component`, `original_string` ) VALUES ('{$ip}', now(), '{$tag_description}', '{$description}', '{$type}', '{$uri}', '{$component}', '{$original_string}')";
			$db->setQuery($sql);
			$db->execute();
		}		
		
		// Borramos las entradas con más de un mes de antigüedad
		/*$sql = "DELETE FROM `#__securitycheck_logs` WHERE (DATE_ADD(`time`, INTERVAL 1 MONTH)) < NOW();";
		$db->setQuery($sql);
		$result = $db->execute();*/
	}
		
	/* Determina si un valor está codificado en base64 */	
	function is_base64($value){
		$res = false; // Determines if any character of the decoded string is between 32 and 126, which should indicate a non valid european ASCII character
	
		$min_len = mb_strlen($value)>7;
		if ($min_len) {
			
			$decoded = base64_decode(chunk_split($value));
			$string_caracteres = str_split($decoded); 
			if ( empty($string_caracteres) ) {
				return false;  // It´s not a base64 string!
			} else {
				foreach ($string_caracteres as $caracter) {
					if ( (empty($caracter)) || (ord($caracter)<32) || (ord($caracter)>126) ) { // Non-valid ASCII value
						return false; // It´s not a base64 string!
					}
				}
			}
			
		$res = true; // It´s a base64 string!
		}
		
		return $res;
	}
	
	/* Determina si un string tiene caracteres ascii no válidos */	
	function is_ascii_valid($string){
		$res = true; // Determines if any character of the decoded string is between 32 and 126, which should indicate a non valid european ASCII character
	
			
		$string_caracteres = str_split($string); 
		if ( empty($string_caracteres) ) {
			return true;  // There are no chars
		} else {
			foreach ($string_caracteres as $caracter) {
				if ( (empty($caracter)) || (ord($caracter)<32) || (ord($caracter)>126) ) { // Non-valid ASCII value
					return false; // There are non-valid chars
				}
			}
		}
						
		return $res;
	}
	
	/* Función para convertir en string una cadena hexadecimal */
	function hexToStr($hex){
		
		$hex = trim(preg_replace("/(\%|0x)/","",$hex));
				
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;		 
	}
	
	/* Función que realiza la misma función que mysql_real_escape_string() pero sin necesidad de una conexión a la BBDD */
	function escapa_string($value){
		$search = array("\x00", "'", "\"", "\x1a");
		$replace = array("\\x00", "\'", "\\\"", "\\\x1a");
	
		return str_replace($search, $replace, $value);
	}
	
	// Chequea si la extensión pasada como argumento es vulnerable
	private function check_extension_vulnerable($option) {
		
		// Inicializamos las variables
		$vulnerable = false;
		
		// Creamos el nuevo objeto query
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
	
		// Sanitizamos el argumento
		$sanitized_option = $db->Quote($db->escape($option));
	
		// Construimos la consulta
		$query = "SELECT COUNT(*) from `#__securitycheck_db` WHERE (type = {$sanitized_option})" ;		
				
		$db->setQuery( $query );
		$result = $db->loadResult();
		
		if ( $result > 0 ) {
			$vulnerable = true;
		} 
		
		// Devolvemos el resultado
		return $vulnerable;
	
	}	
	
	/* Función para 'sanitizar' un string. Devolvemos el string "sanitizado" y modificamos la variable "modified" si se ha modificado el string */
	function cleanQuery($ip,$string,$methods_options,$a,$request_uri,&$modified,$check,$option){
		$string_sanitized='';
		$base64=false;
		$pageoption='';
		$existe_componente = false;
		$extension_vulnerable = false;
		
		if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
		} else {
			$user_agent = 'Not set';
		}
		
		if ( isset($_SERVER['HTTP_REFERER']) ) {
			$referer = $_SERVER['HTTP_REFERER'];
		} else {
			$referer = 'Not set';
		}
		
		$app = JFactory::getApplication();
		$is_admin = $app->isClient('administrator');
		
		$user = JFactory::getUser();
		
		/* Excepciones */
		$base64_exceptions = $this->params->get('base64_exceptions');
		$strip_tags_exceptions = $this->params->get('strip_tags_exceptions');
		$duplicate_backslashes_exceptions = $this->params->get('duplicate_backslashes_exceptions');
		$line_comments_exceptions = $this->params->get('line_comments_exceptions');
		$sql_pattern_exceptions = $this->params->get('sql_pattern_exceptions');
		$if_statement_exceptions = $this->params->get('if_statement_exceptions');
		$using_integers_exceptions = $this->params->get('using_integers_exceptions');
		$escape_strings_exceptions = $this->params->get('escape_strings_exceptions');
		$lfi_exceptions = $this->params->get('lfi_exceptions');
		$check_header_referer = $this->params->get('check_header_referer',1);
		$exclude_exceptions_if_vulnerable = $this->params->get('exclude_exceptions_if_vulnerable',1);
						
		if ( !($is_admin) ){  // No estamos en la parte administrativa
		
		/* Chequeamos si el usuario tiene permisos para instalar en el sistema. Si no los tiene, continuamos con el script. Esto nos permite tener más control para
		determinar si estamos en el backend, ya que algunos componentes/plugins no nos permiten discernir
		en qué parte estamos, obteniendo errores al continuar con el script. Así, si un usuario tiene permisos para instalar podemos obviar la ejecución del script, 
		puesto que suponemos que sus permisos están bien configurados */		
		if (!($user->authorise('com_installer'))) { 
		
		$pageoption = $option;
		
		// Si hemos podido extraer el componente implicado en la petición, vemos si la versión instalada es vulnerable
		if ( (!empty($option)) && ($exclude_exceptions_if_vulnerable) ) {
			$extension_vulnerable = $this->check_extension_vulnerable($option);										
		}
		
		if ( (!(is_array($string))) && (mb_strlen($string)>0) && ($pageoption != '') ){
			/* Base64 check */
			if ($check) {
				/* Chequeamos si el componente está en la lista de excepciones */
				if ( !(strstr($base64_exceptions,$pageoption)) ){
					$is_base64 = $this->is_base64($string);
						if ($is_base64) {
							$decoded = base64_decode(chunk_split($string));
							$base64=true;
							$string = $decoded;
						}
				}
			}
			
			/* Hexadecimal check */
			if ( preg_match("/(\%[a-zA-Z0-9]{2}|0x{4,})/",$string) ) {
				$string_temp = $this->hexToStr($string);		
				$is_valid = $this->is_ascii_valid($string_temp);
				if ( $is_valid ) {  // El string contiene caracteres hexadecimales y su conversión a caracteres ASCII es válida
					$string = $string_temp;					
				}
			}   
			
			/* XSS Prevention */
				//Strip html and php tags from string
			if ( ( !(strstr($strip_tags_exceptions,$pageoption)) || $extension_vulnerable ) && !(strstr($strip_tags_exceptions,'*')) ){
				$string_sanitized = strip_tags($string);
				if (strcmp($string_sanitized,$string) <> 0){ //Se han eliminado caracteres; escribimos en el log
					if ($base64){
						$this->grabar_log($ip,'TAGS_STRIPPED','[' .$methods_options .':' .$a .']','XSS_BASE64',$request_uri,$string,$pageoption);
					}else {
						$this->grabar_log($ip,'TAGS_STRIPPED','[' .$methods_options .':' .$a .']','XSS',$request_uri,$string,$pageoption);
					}
					
					$string = $string_sanitized;	
					$modified = true;
				}
			}
			
			/* SQL Injection Prevention */
			if (!$modified) {
				if ( !(strstr($duplicate_backslashes_exceptions,$pageoption)) && !(strstr($duplicate_backslashes_exceptions,'*')) ){
					// Prevents duplicate backslashes
					if (PHP_VERSION_ID < 50400 && get_magic_quotes_gpc())
					{
						$string_sanitized = stripslashes($string);
						if (strcmp($string_sanitized,$string) <> 0){ //Se han eliminado caracteres; escribimos en el log
							if ($base64){
								$this->grabar_log($ip,'DUPLICATE_BACKSLASHES','[' .$methods_options .':' .$a .']','SQL_INJECTION_BASE64',$request_uri,$string,$pageoption);
							}else {
								$this->grabar_log($ip,'DUPLICATE_BACKSLASHES','[' .$methods_options .':' .$a .']','SQL_INJECTION',$request_uri,$string,$pageoption);
							}
							
							if ( strlen($string_sanitized)>0 ){
								$string = $string_sanitized;
							}
						}
					}
				}
				
				if ( !(strstr($line_comments_exceptions,$pageoption)) && !(strstr($line_comments_exceptions,'*')) && ($pageoption != 'com_users') ){
					// Line Comments
					$lineComments = array("/--/","/[^\=]#/","/\/\*/","/\*\//");
					$string_sanitized = preg_replace($lineComments, "", $string);
										
					if (strcmp($string_sanitized,$string) <> 0){ //Se han eliminado caracteres; escribimos en el log
						if ($base64){
							$this->grabar_log($ip,'LINE_COMMENTS','[' .$methods_options .':' .$a .']','SQL_INJECTION_BASE64',$request_uri,$string,$pageoption);
						}else {
							$this->grabar_log($ip,'LINE_COMMENTS','[' .$methods_options .':' .$a .']','SQL_INJECTION',$request_uri,$string,$pageoption);
						}
						
						$string = $string_sanitized;
						$modified = true;
					}
				}
				
				$sqlpatterns = array("/delete(?=(\s|\+|%20|%u0020|%uff00)).+(from)(?=(\s|\+|%20|%u0020|%uff00))/i","/update(?=(\s|\+|%20|%u0020|%uff00)).+(set)(?=(\s|\+|%20|%u0020|%uff00))/i",
				"/drop(?=(\s|\+|%20|%u0020|%uff00)).+(database|user|table|index)(?=(\s|\+|%20|%u0020|%uff00))/i",
				"/insert(?=(\s|\+|%20|%u0020|%uff00)).+(values|set|select)(?=(\s|\+|%20|%u0020|%uff00))/i", "/union(?=(\s|\+|%20|%u0020|%uff00)).+(select)(?=(\s|\+|%20|%u0020|%uff00))/i",
				"/select(?=(\s|\+|%20|%u0020|%uff00)).+(from|ascii|char|concat)(?=(\s|\+|%20|%u0020|%uff00))/i","/benchmark\(.*\)/i",
				"/md5\(.*\)/i","/sha1\(.*\)/i","/ascii\(.*\)/i","/concat\(.*\)/i","/char\(.*\)/i",
				"/substring\(.*\)/i","/(\s|\+|%20|%u0020|%uff00)(or|and)(\s|\+|%20|%u0020|%uff00)(\w+)(=|<|>|<=|>=)(\w+)/i");
					
				if ( ( !(strstr($sql_pattern_exceptions,$pageoption)) || $extension_vulnerable ) && !(strstr($sql_pattern_exceptions,'*')) ){							
					$string_sanitized = preg_replace($sqlpatterns, "", $string);
											
					if (strcmp($string_sanitized,$string) <> 0){ //Se han eliminado caracteres; escribimos en el log	
						if ($base64){
							$this->grabar_log($ip,'SQL_PATTERN','[' .$methods_options .':' .$a .']','SQL_INJECTION_BASE64',$request_uri,$string,$pageoption);
						}else {
							$this->grabar_log($ip,'SQL_PATTERN','[' .$methods_options .':' .$a .']','SQL_INJECTION',$request_uri,$string,$pageoption);
						}
						
						$string = $string_sanitized;
						$modified = true;					
					}	
				}
				
				//IF Statements
				$ifStatements = array("/if\(.*,.*,.*\)/i");
					
				if ( ( !(strstr($if_statement_exceptions,$pageoption)) || $extension_vulnerable ) && !(strstr($if_statement_exceptions,'*')) ){		
					$string_sanitized = preg_replace($ifStatements, "", $string);
					
					if (strcmp($string_sanitized,$string) <> 0){ //Se han eliminado caracteres; escribimos en el log
						if ($base64){
							$this->grabar_log($ip,'IF_STATEMENT','[' .$methods_options .':' .$a .']','SQL_INJECTION_BASE64',$request_uri,$string,$pageoption);
						}else {
							$this->grabar_log($ip,'IF_STATEMENT','[' .$methods_options .':' .$a .']','SQL_INJECTION',$request_uri,$string,$pageoption);
						}						
						
						$string = $string_sanitized;
						$modified = true;
					}
				}
				
				//Using Integers
				$usingIntegers = array("/0x(?=[0-9])/i");
					
				if ( !(strstr($using_integers_exceptions,$pageoption)) && !(strstr($using_integers_exceptions,'*')) ){	
					$string_sanitized = preg_replace($usingIntegers, "", $string);
					
					if (strcmp($string_sanitized,$string) <> 0){ //Se han eliminado caracteres; escribimos en el log
						if ($base64){
							$this->grabar_log($ip,'INTEGERS','[' .$methods_options .':' .$a .']','SQL_INJECTION_BASE64',$request_uri,$string,$pageoption);
						}else {
							$this->grabar_log($ip,'INTEGERS','[' .$methods_options .':' .$a .']','SQL_INJECTION',$request_uri,$string,$pageoption);
						}
						
						$string = $string_sanitized;
						$modified = true;
					}
					
				}
				
				if ( !(strstr($escape_strings_exceptions,$pageoption)) && !(strstr($escape_strings_exceptions,'*')) && ($modified) ){
					$string_sanitized = $this->escapa_string($string);
										
					if (strcmp($string_sanitized,$string) <> 0){ //Se han añadido barras invertidas a ciertos caracteres; escribimos en el log							
						if ($base64){
							$this->grabar_log($ip,'BACKSLASHES_ADDED','[' .$methods_options .':' .$a .']','SQL_INJECTION_BASE64',$request_uri,$string,$pageoption);
						}else {
							$this->grabar_log($ip,'BACKSLASHES_ADDED','[' .$methods_options .':' .$a .']','SQL_INJECTION',$request_uri,$string,$pageoption);
						}
						if ( strlen($string_sanitized)>0 ){
							$string = $string_sanitized;
						}
					}
				}
					
			}	
			
			/* LFI  Prevention */
			$lfiStatements = array("/\.\.\//");
			if ( ( !(strstr($lfi_exceptions,$pageoption)) || $extension_vulnerable ) && !(strstr($lfi_exceptions,'*')) ){
				if (!$modified) {
					$string_sanitized = preg_replace($lfiStatements,'', $string);
					if (strcmp($string_sanitized,$string) <> 0){ //Se han eliminado caracteres; escribimos en el log
						if ($base64){
							$this->grabar_log($ip,'LFI','[' .$methods_options .':' .$a .']','LFI_BASE64',$request_uri,$string,$pageoption);
						}else {
							$this->grabar_log($ip,'LFI','[' .$methods_options .':' .$a .']','LFI',$request_uri,$string,$pageoption);
						}
						
						$string = $string_sanitized;
						$modified = true;
					}
				}
			}
			
			/* Header and user-agent check */
			if ( (!$modified) && ($check_header_referer) ) {
				$modified = $this->check_header_and_user_agent($user,$user_agent,$referer,$ip,$methods_options,$a,$request_uri,$sqlpatterns,$ifStatements,$usingIntegers,$lfiStatements,$pageoption);
			}
		}
		}
		}
		return $string;
		
	}
	
	/* Función que chequea el 'Header' y el 'user-agent' en busca de ataques */
	function check_header_and_user_agent($user,$user_agent,$referer,$ip,$methods_options,$a,$request_uri,$sqlpatterns,$ifStatements,$usingIntegers,$lfiStatements,$pageoption) {
		$modified = false; 
		
		if ( $user->guest ) {
			/***** User-agent checks *****/
			if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
				/* XSS Prevention in USER_AGENT*/
				//Strip html and php tags from string
				$header_sanitized = strip_tags($user_agent);
				if (strcmp($header_sanitized,$user_agent) <> 0){ //Se han eliminado caracteres; escribimos en el log
					$this->grabar_log($ip,'TAGS_STRIPPED','[' .$methods_options .':' .$a .']','USER_AGENT_MODIFICATION',$request_uri,$user_agent,$pageoption);
					
					$modified = true;
				} 
				/* SQL Injection in USER_AGENT*/
				$header_sanitized = preg_replace($sqlpatterns, "", $user_agent);
				if (strcmp($header_sanitized,$user_agent) <> 0){ //Se han eliminado caracteres; escribimos en el log
					$this->grabar_log($ip,'SQL_PATTERN','[' .$methods_options .':' .$a .']','USER_AGENT_MODIFICATION',$request_uri,$user_agent,$pageoption);
					
					$modified = true;
				}
				/* SQL Injection in USER_AGENT*/
				$header_sanitized = preg_replace($ifStatements, "", $user_agent);
				if (strcmp($header_sanitized,$user_agent) <> 0){ //Se han eliminado caracteres; escribimos en el log
					$this->grabar_log($ip,'IF_STATEMENT','[' .$methods_options .':' .$a .']','USER_AGENT_MODIFICATION',$request_uri,$user_agent,$pageoption);
					
					$modified = true;
				} 
				/* SQL Injection in USER_AGENT*/
				$header_sanitized = preg_replace($usingIntegers, "", $user_agent);
				if (strcmp($header_sanitized,$user_agent) <> 0){ //Se han eliminado caracteres; escribimos en el log
					$this->grabar_log($ip,'INTEGERS','[' .$methods_options .':' .$a .']','USER_AGENT_MODIFICATION',$request_uri,$user_agent,$pageoption);
				
					$modified = true;
				} 
				/* LFI in USER_AGENT*/
				$header_sanitized = preg_replace($lfiStatements, '', $user_agent);
				if (strcmp($header_sanitized,$user_agent) <> 0){ //Se han eliminado caracteres; escribimos en el log
					$this->grabar_log($ip,'LFI','[' .$methods_options .':' .$a .']','USER_AGENT_MODIFICATION',$request_uri,$user_agent,$pageoption);
					
					$modified = true;
				}
			}
			/***** Referer checks *****/
			if (!$modified) {
				if ( isset($_SERVER['HTTP_REFERER']) ) {
					/* XSS Prevention in REFERER*/
					//Strip html and php tags from string
					$header_sanitized = strip_tags($referer);
					if (strcmp($header_sanitized,$referer) <> 0){ //Se han eliminado caracteres; escribimos en el log
						$this->grabar_log($ip,'TAGS_STRIPPED','[' .$methods_options .':' .$a .']','REFERER_MODIFICATION',$request_uri,$referer,$pageoption);
					
						$modified = true;
					} 				
					/* SQL Injection in REFERER*/
					$header_sanitized = preg_replace($sqlpatterns, "", $referer);
					if (strcmp($header_sanitized,$referer) <> 0){ //Se han eliminado caracteres; escribimos en el log
						$this->grabar_log($ip,'SQL_PATTERN','[' .$methods_options .':' .$a .']','REFERER_MODIFICATION',$request_uri,$referer,$pageoption);
						
						$modified = true;
					}
					/* SQL Injection in REFERER*/
					$header_sanitized = preg_replace($ifStatements, "", $referer);
					if (strcmp($header_sanitized,$referer) <> 0){ //Se han eliminado caracteres; escribimos en el log
						$this->grabar_log($ip,'IF_STATEMENT','[' .$methods_options .':' .$a .']','REFERER_MODIFICATION',$request_uri,$referer,$pageoption);
						
						$modified = true;
					} 
					/* LFI in REFERER*/
					$header_sanitized = preg_replace($lfiStatements, '', $referer);
					if (strcmp($header_sanitized,$referer) <> 0){ //Se han eliminado caracteres; escribimos en el log
						$this->grabar_log($ip,'LFI','[' .$methods_options .':' .$a .']','REFERER_MODIFICATION',$request_uri,$referer,$pageoption);
						
						$modified = true;
					}
				}
			}
		}
		return $modified;
	}
	
	/* Función para contar el número de palabras "prohibidas" de un string*/
	function second_level($request_uri,$string,$a,&$found,$option){
		$occurrences=0;
		$string_sanitized=$string;
		$application = JFactory::getApplication();
		$user = JFactory::getUser();
		$dbprefix = $application->getCfg('dbprefix');
		$pageoption='';
		$existe_componente = false;
		$extension_vulnerable = false;
		
		$app = JFactory::getApplication();
		$is_admin = $app->isClient('administrator');
		
		$pageoption = $option;
		
		/* Excepciones */
		$second_level_exceptions = $this->params->get('second_level_exceptions');
		
		// Chequeamos si hemos de excluir los componentes vulnerables de las excepciones
		$exclude_exceptions_if_vulnerable = $this->params->get('exclude_exceptions_if_vulnerable',1);
		
		// Si hemos podido extraer el componente implicado en la peticin, vemos si la versin instalada es vulnerable
		if ( (!empty($option)) && ($exclude_exceptions_if_vulnerable) ) {
			$extension_vulnerable = $this->check_extension_vulnerable($option);										
		}
		
		
		if (!($user->authorise('com_installer'))) { 
			if ( ( !($is_admin) ) && ($pageoption != '') && !(is_array($string)) ){  // No estamos en la parte administrativa
				if ( !(strstr($second_level_exceptions,$pageoption)) || $extension_vulnerable ){
					/* SQL Injection Prevention */
					// Prevents duplicate backslashes
					if (PHP_VERSION_ID < 50400 && get_magic_quotes_gpc())
					{
						$string_sanitized = stripslashes($string);
					}
					// Line Comments
					$lineComments = array("/--/","/[^\=]#/","/\/\*/","/\*\//");
					$string_sanitized = preg_replace($lineComments,"", $string_sanitized);
				
					$string_sanitized = $this->escapa_string($string);
										
					$suspect_words = array("drop","update","set","admin","select","user","password","concat",
					"login","load_file","ascii","char","union","from","group by","order by","insert","values",
					"pass","where","substring","benchmark","md5","sha1","schema","version","row_count",
					"compress","encode","information_schema","script","javascript","img","src","input","body",
					"iframe","frame");
					
					foreach ($suspect_words as $word){
						if ( (is_string($string_sanitized)) && (!empty($word)) && (!empty($string_sanitized)) ) {
							if (substr_count(strtolower($string_sanitized),$word)){
								$found = $found .', ' .$word;
								$occurrences++;
							}
						}
					}
				}
			}
		}
		return $occurrences;
		
	}
	
	/* Función para chequear si una ip pertenece a una lista */
	function chequear_ip_en_lista($ip,$lista){
		$aparece = false;
				
		if (strlen($lista) > 0) {
			// Eliminamos los caracteres en blanco antes de introducir los valores en el array
			$lista = str_replace(' ','',$lista);
			$array_ips = explode(',',$lista);
						
			if ( is_int(array_search($ip,$array_ips)) ){	// La ip aparece tal cual en la lista
				$aparece = true;
			} 
		}
		return $aparece;
	}
		
	/*  Función que chequea el número de sesiones activas del usuario y, si existe más de una, toma el comportamiento pasado como argumento*/
	function sesiones_activas($attack_ip,$request_uri){
		/* Cargamos el lenguaje del sitio */
		$lang = JFactory::getLanguage();
		$lang->load('com_securitycheck',JPATH_ADMINISTRATOR);
		
		// Chequeamos si la opción de compartir sesiones está activa; en este caso no aplicaremos esta opción para evitar una denegación de entrada
		$params          = JFactory::getConfig();		
		$shared_session_enabled = $params->get('shared_session');
		
		if ( $shared_session_enabled ) {
			return;
		}
		
		$user = JFactory::getUser();
		$user_id = (int) $user->id;
		if ( $user->guest ) {
			/* El usuario no se ha logado; no hacemos nada */
		} else {
			// Creamos el nuevo objeto query
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			// Construimos la consulta
			$query = "SELECT COUNT(*) from `#__session` WHERE (userid = {$user_id})" ;
			
			$db->setQuery( $query );
			$result = $db->loadResult();
			
			if ( $result > 1 ) {  // Ya existe una sesión activa del usuario
					/*Cerramos todas las sesiones activas del usuario, tanto del frontend (clientid->0) como del backend (clientid->1); este código es
					necesario porque no queremos modificar los archivos de Joomla , pero esta comprobación podría incluirse en la función onUserLogin*/
					$mainframe= JFactory::getApplication();
					$mainframe->logout( $user_id,array("clientid" => 0) );
					$mainframe->logout( $user_id,array("clientid" => 1) ); 
					
					$session_protection_description = $lang->_('COM_SECURITYCHECK_SESSION_PROTECTION_DESCRIPTION');
					$username = $lang->_('COM_SECURITYCHECK_USERNAME');
					
					// Grabamos el log correspondiente...
					$this->grabar_log($attack_ip,'SESSION_PROTECTION',$session_protection_description,'SESSION_PROTECTION',$request_uri,$username .$user->username,'---');
									
					// ... y redirigimos la petición para realizar las acciones correspondientes
					$session_protection_error = $lang->_('COM_SECURITYCHECK_SESSION_PROTECTION_ERROR');
					JFactory::getApplication()->enqueueMessage($session_protection_error, 'error');
			}										
		}
	}
	
	function onAfterInitialise(){
	
		/* Cargamos el lenguaje del sitio */
		$lang = JFactory::getLanguage();
		$lang->load('com_securitycheck',JPATH_ADMINISTRATOR);
		$not_applicable = $lang->_('COM_SECURITYCHECK_NOT_APPLICABLE');

		$methods = $this->params->get('methods','GET,POST,REQUEST');
		$blacklist_ips = $this->params->get('blacklist');
		$whitelist_ips = $this->params->get('whitelist');
		$secondlevel = $this->params->get('second_level',1);
		$check_base_64 = $this->params->get('check_base_64',1);
		$session_protection_active = $this->params->get('session_protection_active',1);
		
		
		$attack_ip = $this->get_ip();
		$request_uri = $_SERVER['REQUEST_URI'];

		
		$aparece_lista_negra = $this->chequear_ip_en_lista($attack_ip,$blacklist_ips);
		$aparece_lista_blanca = $this->chequear_ip_en_lista($attack_ip,$whitelist_ips);
		
	/* Protección de la sesión del usuario */	
	if ( $session_protection_active ) {
		$this->sesiones_activas($attack_ip,$request_uri);
	}
	
	/* Chequeamos si la ip remota se encuentra en la lista negra */
	if ( $aparece_lista_negra ){
			/* Grabamos una entrada en el log con el intento de acceso de la ip prohibida */
			$access_attempt = $lang->_('COM_SECURITYCHECK_ACCESS_ATTEMPT');
			$this->grabar_log($attack_ip,'IP_BLOCKED',$access_attempt,'IP_BLOCKED',$request_uri,$not_applicable,'---');
									
			/* Redirección a nuestra página de "Prohibido" */
			$error_403 = $lang->_('COM_SECURITYCHECK_403_ERROR');
			JFactory::getApplication()->enqueueMessage($error_403, 'error');
	} else {
	
		/* Chequeamos si la ip remota se encuentra en la lista blanca */
		if ( $aparece_lista_blanca ){
			/* Grabamos una entrada en el log con el acceso de la ip permitida 
				$access = $lang->_('COM_SECURITYCHECK_ACCESS');
				$this->grabar_log($attack_ip,'IP_PERMITTED',$access,'IP_PERMITTED',$request_uri,$not_applicable); */
		} else {
			
			foreach(explode(',', $methods) as $methods_options){
				switch ($methods_options){
					case 'GET':
						$method = $_GET;
						break;
					case 'POST':
						$method = $_POST;
						break;
					case 'COOKIE':
						$method = $_COOKIE;
						break;
					case 'REQUEST':
						$method = $_REQUEST;
						break;
				}
			
			foreach($method as $a => &$req){
			
				if(is_numeric($req)) continue;
				
				$modified = false;
				
				// ¿Cómo hemos de extraer el componente?
				$params = JComponentHelper::getParams('com_securitycheck');
				$determine_option_accurately = $params->get('determine_option_accurately',1);
									
				if ( $determine_option_accurately == 1 ) {
							
					// Obtenemos el componente de la petición
					$app = JFactory::getApplication();
					$uri = clone JUri::getInstance();
					$router = $app->getRouter();
					$parsed = $router->parse($uri);
					if ( isset($parsed['option']) ) {
						$option = $parsed['option'];
					} else {
						$option = 'com_content';
					}
					
				} else {
								
					// Obtenemos el componente de la petición
					$app = JFactory::getApplication()->input;
					$uriQuery = $app->getArray();
					if ( array_key_exists('option',$uriQuery) ) {
						$option = $uriQuery['option'];
					} else {
						// No hemos podido obtener el componente; lo establecemos por defecto
						$option = 'com_content';
					}			
				}
		
				$new_option = '';
						
				//Si obtenemos 'com_content' como contenido activo, quizá el parseo no ha podido extraer el componente. Lo intentamos con 'JInput'
				if ( $option == 'com_content' ){
					$input = new JInput();
					$new_option = $input->getCmd('option','Not_defined');
					if ( $new_option != 'Not_defined' ){
						$option = $new_option;
					}
				}
				
				// Sanitizamos la salida
				$option = filter_var($option, FILTER_SANITIZE_STRING);
				
				$req = $this->cleanQuery($attack_ip,$req,$methods_options,$a,$request_uri,$modified,$check_base_64,$option);
																
				if ($modified) {
					/* Redirección a nuestra página de "Hacking Attempt" */
					$error_400 = $lang->_('COM_SECURITYCHECK_400_ERROR');
					JFactory::getApplication()->enqueueMessage($error_400,'error');
				} else if ($secondlevel) {  // Second level protection
					$words_found='';
					$num_keywords = $this->second_level($request_uri,$req,$a,$words_found,$option);
					if ($num_keywords > 2) {
						$this->grabar_log($attack_ip,'FORBIDDEN_WORDS',$words_found,'SECOND_LEVEL',$request_uri,$not_applicable,$option);
						$error_401 = $lang->_('COM_SECURITYCHECK_401_ERROR');
						JFactory::getApplication()->enqueueMessage($error_401, 'error');
					}
				} 
			}
			}
		}
	}
	 

	}
	
	public function onAfterDispatch() {
		// ¿Tenemos que eliminar el meta tag?
		$params = JComponentHelper::getParams('com_securitycheck');
		$remove_meta_tag = $params->get('remove_meta_tag',1);
		
		$code  = JFactory::getDocument();
		if ( $remove_meta_tag ) {
			$code->setGenerator('');
		}
	}
	
	/* Obtiene la IP remota que realiza las peticiones */
	public function get_ip(){
		// Inicializamos las variables 
		$clientIpAddress = 'Not set';
		$ip_valid = false;
		
		// Ignoramos las cabeceras X-Forwarded-For; en la versión Pro podemos elegir otra opción
		if ( isset($_SERVER['REMOTE_ADDR']) ) {
			$clientIpAddress = $_SERVER['REMOTE_ADDR'];			
		}
			
		$ip_valid = filter_var($clientIpAddress, FILTER_VALIDATE_IP);
		// Si la ip no es válida entonces bloqueamos la petición y mostramos un error 403
		if ( !$ip_valid ) {
			/* Cargamos el lenguaje del sitio */
			$lang = JFactory::getLanguage();
			$lang->load('com_securitycheck',JPATH_ADMINISTRATOR);
			$error_403 = $lang->_('COM_SECURITYCHECK_403_ERROR');
			JFactory::getApplication()->enqueueMessage($error_403, 'error');				
		} else {
			return $clientIpAddress;
		}
	}
		
}