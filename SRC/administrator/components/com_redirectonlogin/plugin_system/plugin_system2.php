<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemRedirectonlogin extends JPlugin{		

	//cant use onAfterIni because itemid is not readable
	function onAfterRender(){	
			
		$database = JFactory::getDBO();		
		$app = JFactory::getApplication();		
		$session_id = session_id();	
		if(empty($session_id)){
			session_start();
			$session_id = session_id();
		}
		
		//get config
		$rol_config = $this->get_config();		
				
		$buffer = JResponse::getBody();			
		if(strpos($buffer, '<span class="com_redirectonlogin_message">')){			
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET message='', message_type='' WHERE session_id='$session_id' ");
			$database->query();				
		}		
		
		//if user came from the login page after getting no menu access, but browsed to another page, clear the cookie			
		$option = JRequest::getVar('option', '');
		$view = JRequest::getVar('view', '');
		$task = JRequest::getVar('task', '');
		$ds = DIRECTORY_SEPARATOR;
		
		
		
		//check if there is a (silent) redirect set			
		$database->setQuery("SELECT url "
		." FROM #__redirectonlogin_sessions "
		." WHERE session_id='$session_id' "	
		." LIMIT 1 "		
		);
		$rows = $database->loadObjectList();
		$url = 0;						
		foreach($rows as $row){				
			$url = $row->url;							
		}
			
		//frontend
		if($app->isSite()){	
		
			if($url){
				//reset
				$database->setQuery( "UPDATE #__redirectonlogin_sessions SET url='', silent='', opening_site_home='' WHERE session_id='$session_id' ");
				$database->query();				
				
				$app->redirect($url);
				return;//don't take the message out before it has been displayed	
			}	
			
			//if jevents is installed, include lang file
			if(file_exists(JPATH_ROOT.$ds.'components'.$ds.'com_jevents'.$ds.'jevents.php')){
				$lang = JFactory::getLanguage();
				$lang->load('com_jevents', JPATH_ROOT, null, false);
			}
			
			//if no access to menu item, and config is set to redirect to page, set session
			if(($option=='com_users' && $view=='login') || ($option=='com_comprofiler' && $task=='login') || ($option=='com_community' && $task=='frontpage')){			
				if(($rol_config['after_no_access_page']=='page' || $rol_config['after_no_access_page']=='pagerolno') && strpos($buffer, JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'))){
					$data = $app->getUserState('users.login.form.data', array());
					$return = $data['return'];					
					$app->setUserState("com_redirectonlogin.return_url_after_unauthorised_access", $return);
				}
			}	
			
			//purge sessions table about every 100 pageloads
			$rand = rand(1, 100);	
			if($rand==27){
				$database = JFactory::getDBO();	
				$time = time();	
				//one day is 24*60*60=86400 seconds
				$one_day_old = $time-86400;		
				$database->setQuery("DELETE FROM #__redirectonlogin_sessions WHERE unixtime<='$one_day_old' ");
				$database->query();
			}			
			
		}	
		
		$return_url = $app->getUserState("com_redirectonlogin.return_url_after_unauthorised_access", '');
		if($return_url && !($option=='com_users' && $task=='user.login') && !($option=='com_users' && $view=='login') && !($option=='com_comprofiler' && $task=='login') && !($option=='com_community' && $task=='frontpage')){				
			$app->setUserState("com_redirectonlogin.return_url_after_unauthorised_access", '');
		}
			
	}	
	
	function onAfterInitialise(){			
		
		$app = JFactory::getApplication();
		$database = JFactory::getDBO();	
		$time = time();	

		//get session id
		$session_id = session_id();
		if(empty($session_id)){
			session_start();
			$session_id = session_id();	
		}
		
		//do adoption if there is any						
		$database->setQuery( "UPDATE #__redirectonlogin_sessions SET session_id='$session_id', unixtime='$time' WHERE session_id='adopt_me' ");
		$database->query();	
		
		//check if session is in database yet
		$database->setQuery("SELECT * "
		." FROM #__redirectonlogin_sessions "
		." WHERE session_id='$session_id' "	
		." LIMIT 1 "	
		);
		$rows = $database->loadObjectList();
		$adopt_id = 0;
		$unixtime = 0;		
		$url = 0;
		$message = 0;
		$message_type = '';
		$logout = 0;		
		foreach($rows as $row){	
			$adopt_id = $row->id;
			$unixtime = $row->unixtime;			
			$url = $row->url;
			$message = $row->message;	
			$message_type = $row->message_type;
			$logout = $row->logout;		
		}
		
		if(!$unixtime){
			//session is not in table, so get it in there
			
			//check if the user just logged out to adopt the session
			$ip = $_SERVER['REMOTE_ADDR'];
			$database->setQuery("SELECT * "
			." FROM #__redirectonlogin_sessions "
			." WHERE session_id='adopt_me' "
			." AND ip='$ip' "
			." LIMIT 1 "		
			);
			$rows = $database->loadObjectList();
			$adopt_id = 0;
			$url = 0;
			$message = 0;			
			$logout = 0;						
			foreach($rows as $row){					
				$adopt_id = $row->id;
				$url = $row->url;
				$message = $row->message;
				$logout = $row->logout;										
			}
			
			if($adopt_id){
				//there is a session up for adoption, lets update it
				$database->setQuery( "UPDATE #__redirectonlogin_sessions SET session_id='$session_id', unixtime='$time' WHERE session_id='adopt_me' ");
				$database->query();
			}else{								
				//insert					
				$database->setQuery( "INSERT INTO #__redirectonlogin_sessions SET session_id='$session_id', unixtime='$time' ");
				$database->query();	
			}	
			
		}else{
			//session is in table
			
			//update time
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET unixtime='$time' WHERE session_id='$session_id' ");
			$database->query();	
				
		}	
		
		if($logout){				
			$ip = $_SERVER['REMOTE_ADDR'];
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET session_id='adopt_me', logout='', ip='$ip', unixtime='$time' WHERE session_id='$session_id' ");
			$database->query();	
				
			$session = JFactory::getSession();
			$session->destroy();
			
			$app->redirect($url);
			return;	
		}			
		
		if($message){
			$message_wrapped = '<span class="com_redirectonlogin_message">'.$message.'</span>';			
			//JError::raiseWarning(403, $message_wrapped);
			//make sure there is only one message in the que
			$messages = $app->getMessageQueue();			
			$message_in_que = 0;
			foreach($messages as $mess){
				if($mess['message'] == $message_wrapped){
					$message_in_que = 1;
				}				
			}
			if(!$message_in_que){
				$helper = $this->get_helper();	
				if($helper->joomla_version >= '3.0'){	
					$app->enqueueMessage($message_wrapped, $message_type);										
				}else{
					//joomla 2.5
					if($message_type=='info'){
						$app->enqueueMessage($message_wrapped);
					}elseif($message_type=='notice'){
						JError::raiseNotice(403, $message_wrapped);
					}else{
						JError::raiseWarning(403, $message_wrapped);
					}
				}
			}								
		}
		
			
		/*
		if($url){		
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET url='' WHERE session_id='$session_id' ");
			$database->query();	
			$app->redirect($url);	
		}
		*/
				
	}
	
	function get_config(){	
			
		$database = JFactory::getDBO();			
		
		$database->setQuery("SELECT config "
		."FROM #__redirectonlogin_config "
		."WHERE id='1' "
		."LIMIT 1"
		);		
		$raw = $database->loadResult();		
		
		$params = explode( "\n", $raw);
		
		for($n = 0; $n < count($params); $n++){		
			$temp = explode('=',$params[$n]);
			$var = $temp[0];
			$value = '';
			if(count($temp)==2){
				$value = trim($temp[1]);				
			}							
			$config[$var] = $value;	
		}	
		
		//reformat redirect url	
		$config['opening_site_url'] = str_replace('[equal]','=',$config['opening_site_url']);	
		$config['url_registration'] = str_replace('[equal]','=',$config['url_registration']);		
				
		return $config;			
	}
	
	function get_helper(){		
		require_once(JPATH_ROOT.'/administrator/components/com_redirectonlogin/helpers/redirectonlogin.php');
		$helper = new redirectonloginHelper();
		return $helper;
	}
	
	
	
}
?>