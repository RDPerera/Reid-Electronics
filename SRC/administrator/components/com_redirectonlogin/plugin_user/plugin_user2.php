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

class plgUserRedirectonlogin extends JPlugin{	

	protected $rol_config;		
	protected $rol_version_type = 'free';
	protected $grouplevel_redirect_url = '';
	protected $grouplevel_redirect_url_silent = '';
	protected $grouplevel_open_site_home = 0;
	protected $grouplevel_message = '';
	protected $grouplevel_message_type = '';
	protected $grouplevel_logout = 0;
	
	function init(){			
		$this->rol_config = $this->get_config();
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
		
		//reformat redirect urls		
		$config['redirect_url_backend'] = str_replace('[equal]','=',$config['redirect_url_backend']);	
		$config['redirect_component_backend'] = str_replace('[equal]','=',$config['redirect_component_backend']);
		$config['redirect_url_frontend'] = str_replace('[equal]','=',$config['redirect_url_frontend']);
		$config['redirect_url_frontend_logout'] = str_replace('[equal]','=',$config['redirect_url_frontend_logout']);
		$config['opening_site_url'] = str_replace('[equal]','=',$config['opening_site_url']);	
		
		$lang = JFactory::getLanguage();
		
		//get default message frontend
		if($config['lang_type_login_front']=='custom'){
			//custom message
			if($config['logout_message_frontend']=='COM_REDIRECTONLOGIN_YOU_CANT_LOGIN'){
				$config['logout_message_frontend'] = JText::_('COM_REDIRECTONLOGIN_YOU_CANT_LOGIN');
			}
		}else{
			//from language file
			$lang->load('com_redirectonlogin', JPATH_ROOT, null, false);
			$config['logout_message_frontend'] = JText::_('COM_REDIRECTONLOGIN_YOU_CANT_LOGIN_FRONTEND');
		}
		
		//get default message backend
		if($config['lang_type_login_back']=='custom'){	
			//custom message
			if($config['logout_message_backend']=='COM_REDIRECTONLOGIN_YOU_CANT_LOGIN'){
				$config['logout_message_backend'] = JText::_('COM_REDIRECTONLOGIN_YOU_CANT_LOGIN');
			}
		}else{
			//from language file
			$lang->load('com_redirectonlogin', JPATH_ROOT, null, false);
			$config['logout_message_backend'] = JText::_('COM_REDIRECTONLOGIN_YOU_CANT_LOGIN_BACKEND');
		}		
				
		return $config;			
	}

	function onUserLogin($user, $options){	
	
		$database = JFactory::getDBO();
		$app = JFactory::getApplication();
		$helper = $this->get_helper();
		
		//free version so only frontend
		if($app->isAdmin()){
			return true;
		}
			
		$this->init();	
		$this->clean_sessions();	
		
		//check if redirect is enabled		
		if($this->rol_config['enable_redirection']=='no'){
			return true;
		}			
		
		$redirect_url = '';	
		$redirect_url_silent = '';
		$message = '';
		$message_type = '';
		$opening_site_home = 0;	
		$logout = 0;
		$silent = 0;
		$return_url = base64_decode(JRequest::getVar('return'));
		
		//had no access to menuitem
		$return_url3 = $app->getUserState("com_redirectonlogin.return_url_after_unauthorised_access", '');
		$return_after_no_access_menuitem = 0;
		if($return_url3){			
			//if set to 'rol' this session would not be triggered at all
			if($this->rol_config['after_no_access_page']=='page'){
				$redirect_url = $return_url3;		
			}
			if($this->rol_config['after_no_access_page']=='pagerolno'){
				if(strpos($return_url, 'rol=no')){
					$redirect_url = $return_url3;
				}			
			}
			$return_after_no_access_menuitem = 1;		
			$app->setUserState("com_redirectonlogin.return_url_after_unauthorised_access", '');			
		}
		
		//had no access to some other page
		$return_url2 = $app->getUserState("com_redirectonlogin.return_url_after_unauthorised_access2", '');
		$return_after_no_access_page = 0;
		if($return_url2){
			if($app->getUserState("com_redirectonlogin.return_url_after_unauthorised_access_pageloads", '') > 0){
				$redirect_url = $return_url2;						
				$app->setUserState("com_redirectonlogin.return_url_after_unauthorised_access2", '');
				$app->setUserState("com_redirectonlogin.return_url_after_unauthorised_access_pageloads", '');	
			}	
			$return_after_no_access_page = 1;	
		}
				
		$current_url = '';
		$current_url_base64 = '';
		if(isset($_SERVER['HTTP_REFERER'])){
			$current_url = $_SERVER['HTTP_REFERER'];				
			$current_url_base64 = base64_encode($current_url);							
		}
		
		//$user_id = $user['id']; why on earth is the user id not parsed?	
		$user_name = $user['username'];					
		
		//get user id from email (as those are unique)
		$database->setQuery("SELECT id "
		."FROM #__users  "
		."WHERE username='$user_name' "
		."LIMIT 1 "
		);
		$rows = $database->loadObjectList();		
		
		$user_id = 0;
		foreach($rows as $row){	
			$user_id = $row->id;	
		}				
		
		//check for user specific redirect
		$database->setQuery("SELECT * "
		."FROM #__redirectonlogin_users  "
		."WHERE user_id='$user_id' "
		."LIMIT 1 "
		);
		$rows = $database->loadObjectList();
		$user_backend_type = '';
		$user_frontend_type = '';
		$user_opening_site = 0;
		$user_opening_site_home = '';
		$user_open_type = '';
		$user_first_type = '';
		foreach($rows as $row){			
			$user_frontend_type = $row->frontend_type;	
			$user_frontend_url = $row->frontend_url;	
			$user_backend_type = $row->backend_type;	
			$user_backend_url = $row->backend_url;	
			$user_backend_component = $row->backend_component;	
			$user_opening_site = $row->opening_site;
			$user_opening_site_url = $row->opening_site_url;
			$user_opening_site_home = $row->opening_site_home;			
			$user_menuitem_login = $row->menuitem_login;	
			$user_menuitem_open = $row->menuitem_open;	
			$user_menuitem_logout = $row->menuitem_logout;	
			$user_dynamic_login = $row->dynamic_login;	
			$user_dynamic_open = $row->dynamic_open;	
			$user_dynamic_logout = $row->dynamic_logout;	
			$user_open_type = $row->open_type;				
		}		
		
		//frontend	
		
		//usergroup/accesslevel redirect
		if($redirect_url==''){				
			if($this->rol_config['frontend_u_or_a']=='a'){
				$grouplevel_id = $this->get_first_accesslevel($user_id);
			}else{
				$grouplevel_id = $this->get_first_usergroup($user_id, 'front');	
			}
			if($grouplevel_id){
				$this->set_grouplevel_redirect_url($grouplevel_id, $current_url, 'login');
				$redirect_url = $this->grouplevel_redirect_url;	
				$message = $this->grouplevel_message;	
				$logout = $this->grouplevel_logout;	
				$message_type = $this->grouplevel_message_type;		
			}				
		}//end if redirect for group or level
		
		//general redirect
		if($redirect_url==''){										
			if($this->rol_config['redirect_type_frontend']!='none'){					
				if($this->rol_config['redirect_type_frontend']=='no'){
					$redirect_url = 'no';					
				}
				if($this->rol_config['redirect_type_frontend']=='same'){
					$redirect_url = $current_url;					
				}
				if($this->rol_config['redirect_type_frontend']=='menuitem'){					
					$redirect_url = $helper->get_link_from_menuitem($this->rol_config['menuitem_login']);			
				}
				if($this->rol_config['redirect_type_frontend']=='url' && $this->rol_config['redirect_url_frontend']!=''){
					$redirect_url = $this->rol_config['redirect_url_frontend'];				
				}
				if($this->rol_config['redirect_type_frontend']=='logout'){
					$message = $this->rol_config['logout_message_frontend'];
					$logout = 1;
					$redirect_url = $current_url;											
				}
			}			
		}	
		
		if($redirect_url!='' && $redirect_url!='no'){	
		

			$redirect_url = JRoute::_($redirect_url, false);				
			
			//get session id
			$session_id = session_id();
			if(empty($session_id)){
				session_start();
				$session_id = session_id();
			}
						
			$message = addslashes($message);
			
			//if there is no session for this user yet, ceate one
			$database->setQuery("SELECT id FROM #__redirectonlogin_sessions WHERE session_id='$session_id' ");
			if(!$database->loadResult()){
				$time = time();	
				$database->setQuery( "INSERT INTO #__redirectonlogin_sessions SET session_id='$session_id', unixtime='$time' ");
				$database->query();	
			}
						
			//set redirect for this session	
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET session_id='adopt_me', url='$redirect_url', message='$message', message_type='$message_type', opening_site_home='$opening_site_home', logout='$logout', silent='$silent' WHERE session_id='$session_id' ");
			$database->query();	
		}	
	
		if($logout){
			//$this->logout(); //do not use here as it conflicts with Joomla sessions and gives a nasty error
			
			//return false; //Joomla ignores this ! User is logged in, even if this returns false.
		}else{		
			return true;
		}
				
	}	
	
	function onUserLogout($user){
		
		return true;				
	}
	
	function get_usergroups($user_id){
		$database = JFactory::getDBO();		
		$database->setQuery("SELECT m.group_id "
		."FROM #__user_usergroup_map AS m "	
		."WHERE m.user_id='$user_id' "		
		);
		$rows = $database->loadObjectList();		
		$group_ids = array();
		foreach($rows as $row){	
			$group_ids[] = $row->group_id;	
		}
		return $group_ids;
	}
	
	function get_first_usergroup($user_id, $front_back){	
		$database = JFactory::getDBO();				
		$database->setQuery("SELECT m.group_id "
		."FROM #__user_usergroup_map AS m "			
		."LEFT JOIN #__redirectonlogin_order_groups AS o "
		."ON o.group_id=m.group_id "			
		."WHERE m.user_id='$user_id' "
		."ORDER BY o.redirect_order_".$front_back." ASC "
		."LIMIT 1"
		);
		$usergroup = $database->loadResult();		
		return $usergroup;
	}	
	
	function get_link_from_menuitem($menu_id){

		$database = JFactory::getDBO();	
		$app = JFactory::getApplication();
		$router = $app->getRouter();
		
		$url = '';
		if ($menu_id!=''){
			
			$database->setQuery("SELECT link "
			." FROM #__menu "
			." WHERE id='$menu_id' "
			." limit 1 "
			);
			$rows = $database->loadObjectList();
			$link = '';
			foreach($rows as $row){	
				$link = $row->link;	
			}
			if($link!='') {
				if($router->getMode() == JROUTER_MODE_SEF) {
					$url = 'index.php?Itemid='.$menu_id;
				}else{
					$url = $link.'&Itemid='.$menu_id;
				}				
			}
		}	
		$url = JRoute::_($url, false);
		//$url = str_replace('&amp;','&',$url);
		return $url;
	}	
	
	function get_first_accesslevel($user_id){
	
		$database = JFactory::getDBO();
		$app = JFactory::getApplication();
		$first_level = 0;
	
		//get user levels from this user
		jimport( 'joomla.access.access' );
		$levels_array = JAccess::getAuthorisedViewLevels($user_id);
		$levels_array = array_unique($levels_array);
		
		//get all levels order		
		$database->setQuery("SELECT level_id "
		."FROM #__redirectonlogin_order_levels "
		."ORDER BY redirect_order ASC "		
		);
		$accesslevels = $database->loadObjectList();	
		
		$count_return_levels = 0;
		$first = 1;
		foreach($accesslevels as $accesslevel){
			if(in_array($accesslevel->level_id, $levels_array)){
				if($first){
					$first_level = $accesslevel->level_id;
					$first = 0;
				}				
				$count_return_levels++;
			}	
		}		
		
		if(count($levels_array)!=$count_return_levels){		
			//not all levels were in the order table
			//so just make an array of the levels with the order of the level table
			$database->setQuery("SELECT id "
			."FROM #__viewlevels "
			."ORDER BY ordering DESC "
			."LIMIT 1 "		
			);
			$first_level = $database->loadResult();			
		}			
		
		return $first_level;
	}
	
	function set_grouplevel_redirect_url($id, $current_url, $login_logout, $user_id=0){
		
		$database = JFactory::getDBO();	
		$app = JFactory::getApplication();
		$helper = $this->get_helper();
		
		$redirect_url = '';
		$type = 'none';		
		$url = '';
		$logout = 0;	
		$message = '';
		$message_type = '';	
		$dynamic = 0;			
				
		$logout_message = $this->rol_config['logout_message_frontend'];
		if($this->rol_config['frontend_u_or_a']=='a'){
			//levels				
			$database->setQuery("SELECT * "
			."FROM #__redirectonlogin_levels  "
			."WHERE group_id='$id' "
			."LIMIT 1 "
			);								
		}else{		
			//groups			
			$database->setQuery("SELECT * "
			."FROM #__redirectonlogin_groups  "
			."WHERE group_id='$id' "
			."LIMIT 1 "
			);								
		}
		$redirects = $database->loadObjectList();
		foreach($redirects as $redirect){
			if($login_logout=='login'){
				//login
				$type = $redirect->frontend_type;
				$url = $redirect->frontend_url;
				$menuitem = $redirect->menuitem_login;	
				$dynamic = $redirect->dynamic_login;
				$inherit = $redirect->inherit_login;
			}						
		}
		//end frontend
			
		
		if($type!='none' && $type!='normal'){
			//usergroup has redirect					
			if($type=='url' && $url!=''){
				$redirect_url = $url;				
			}
			if($type=='no'){						
				$redirect_url = 'no';					
			}
			if($type=='same'){						
				$redirect_url = $current_url;				
			}			
			if($type=='menuitem'){						
				$redirect_url = $helper->get_link_from_menuitem($menuitem);									
			}
			if($type=='inherit'){	
				//recursive							
				$this->set_grouplevel_redirect_url($inherit, $current_url, $login_logout);	
				return false;				
			}
			if($type=='component'){
				if($component!=''){												
					$redirect_url = $component;
				}
			}
			if($type=='logout'){
				$message = $logout_message;
				$logout = 1;
				$redirect_url = $current_url;													
			}												
		}		
		$this->grouplevel_redirect_url = $redirect_url;	
		$this->grouplevel_message = $message;
		$this->grouplevel_message_type = $message_type;	
		$this->grouplevel_logout = $logout;	
	}	
	
	function get_helper(){
		$ds = DIRECTORY_SEPARATOR;
		require_once(JPATH_ROOT.$ds.'administrator'.$ds.'components'.$ds.'com_redirectonlogin'.$ds.'helpers'.$ds.'redirectonlogin.php');
		$helper = new redirectonloginHelper();
		return $helper;
	}
	
	function clean_sessions(){
		$database = JFactory::getDBO();	
		$time = time();	
		//one day is 24*60*60=86400 seconds
		$one_day_old = $time-86400;		
		$database->setQuery("DELETE FROM #__redirectonlogin_sessions WHERE unixtime<='$one_day_old' ");
		$database->query();
	}
	
	
}
?>