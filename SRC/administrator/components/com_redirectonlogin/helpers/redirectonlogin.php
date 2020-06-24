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

class redirectonloginHelper{	

	public $menutypes = 0;
	public $menuitems = 0;
	public $dynamics = 0;	
	public $joomla_version;
	
	function __construct(){	
		$this->menutypes = 0;
		$this->menuitems = 0;
		$this->dynamics = 0;	
		
		$version = new JVersion;
		$this->joomla_version = $version->RELEASE;		
	}

	public static function addSubmenu($vName = 'redirectonlogin'){
		JSubMenuHelper::addEntry(
			JText::_('COM_REDIRECTONLOGIN_CONFIGURATION'),
			'index.php?option=com_redirectonlogin&view=configuration',
			$vName == 'configuration'
		);		
		JSubMenuHelper::addEntry(
			JText::_('COM_REDIRECTONLOGIN_ALLUSERS'),
			'index.php?option=com_redirectonlogin&view=allusers',
			$vName == 'allusers'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_REDIRECTONLOGIN_USERGROUPS'),
			'index.php?option=com_redirectonlogin&view=usergroups',
			$vName == 'usergroups' || $vName == 'usergroup'
		);		
		JSubMenuHelper::addEntry(
			JText::_('COM_REDIRECTONLOGIN_ACCESSLEVELS'),
			'index.php?option=com_redirectonlogin&view=accesslevels',
			$vName == 'accesslevels' || $vName == 'accesslevel'
		);	
		JSubMenuHelper::addEntry(
			JText::_('COM_REDIRECTONLOGIN_USERS'),
			'index.php?option=com_redirectonlogin&view=users',
			$vName == 'users' || $vName == 'user'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECTS'),
			'index.php?option=com_redirectonlogin&view=dynamicredirects',
			$vName == 'dynamicredirects' || $vName == 'dynamicredirect'
		);	
		JSubMenuHelper::addEntry(
			JText::_('COM_REDIRECTONLOGIN_SUPPORT'),
			'index.php?option=com_redirectonlogin&view=support',
			$vName == 'support'
		);
	}
	
	//get a select of the menu-items	
	function menuitems($element_name, $element_properties, $selection){	
						
		$menus = $this->get_menutypes();			
		$items = $this->get_menuitems();			
		$menu_items_select = '<select name="'.$element_name.'" '.$element_properties.'>';
		$menu_items_select .= '<option value="0"> - ';
		if($element_name=='menuitem_id_finder'){
			$menu_items_select .= JText::_('COM_REDIRECTONLOGIN_FIND').' '.JText::_('COM_REDIRECTONLOGIN_MENUITEM').' '.JText::_('JGRID_HEADING_ID');	
		}else{			
			$menu_items_select .= JText::_('COM_REDIRECTONLOGIN_SELECT_MENU_ITEM');	
		}	
		$menu_items_select .= ' - </option>';	
		foreach ($menus as $menu) {				
			$menu_items_select .= '<optgroup label='.$menu->text.'>';				
			foreach($items as $item){
				if($item->menutype==$menu->value){
					$item->text = str_repeat('- ',$item->level).$item->text;
					$menu_items_select .= '<option';
					if(in_array($item->value, $selection)){
						$menu_items_select .= ' selected="selected"';
					}
					$menu_items_select .= ' value="'.$item->value.'">';
					if($element_name=='menuitem_id_finder'){
						$menu_items_select .= $item->value.' ';	
					}
					$menu_items_select .= $item->text.'</option>';
				}
			}				
			$menu_items_select .= '</optgroup>';				
		}
		$menu_items_select .= '</select>';
		
		return $menu_items_select;
	}
	
	function get_menutypes(){
	
		if(!$this->menutypes){
			$database = JFactory::getDBO();
			$database->setQuery(
				'SELECT menutype AS value, title AS text' .
				' FROM #__menu_types' .
				' ORDER BY title'
			);
			$this->menutypes = $database->loadObjectList();
		}
		return $this->menutypes;
	}
	
	function get_menuitems(){
	
		if(!$this->menuitems){
			$database = JFactory::getDBO();
			$query = $database->getQuery(true);
			$query->select('a.id AS value, a.title AS text, a.level, a.menutype');
			$query->from('#__menu AS a');
			$query->where('a.parent_id > 0');
			$query->where('a.type <> '.$database->quote('url'));
			$query->where('a.client_id = 0');
			$query->where('a.published = 1');
			$query->order('a.lft');
			$database->setQuery($query);
			$this->menuitems = $database->loadObjectList();
		}
		return $this->menuitems;
	}
	
	function get_dynamics_select($element_name, $selection){		
		
		$dynamics = $this->get_dynamics();						
		$dynamics_select = '<select name="'.$element_name.'">';			
		$dynamics_select .= '<option value="0"> - '.JText::_('COM_REDIRECTONLOGIN_SELECT_DYNAMIC_REDIRECT').' - </option>';			
		foreach ($dynamics as $dynamic){				
			$dynamics_select .= '<option';
			if($dynamic->id==$selection){
				$dynamics_select .= ' selected="selected"';
			}
			$dynamics_select .= ' value="'.$dynamic->id.'">'.$dynamic->name.'</option>';
		}
		$dynamics_select .= '</select>';
		
		return $dynamics_select;
	}
	
	function get_dynamics(){
	
		if(!$this->dynamics){
			$database = JFactory::getDBO();
			$database->setQuery(
				'SELECT id, name ' .
				' FROM #__redirectonlogin_dynamics ' .
				' ORDER BY name'
			);
			$this->dynamics = $database->loadObjectList();
		}
		return $this->dynamics;
	}
	
	function redirect_type_list($default_type, $type){
		$return = '';
		if($type=='' || $type=='none'){
			$type = $default_type;
		}
		if($type=='normal'){				
			$return = $this->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_NORMAL'));
		}
		if($type=='url'){				
			$return = JText::_('COM_REDIRECTONLOGIN_URL');
		}
		if($type=='component'){				
			$return = JText::_('COM_REDIRECTONLOGIN_COMPONENT');
		}
		if($type=='logout'){				
			$return = $this->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_BLOCK_LOGIN'));
		}
		if($type=='same'){				
			$return = $this->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_SAME_PAGE'));
		}
		if($type=='no'){				
			$return = JText::_('COM_REDIRECTONLOGIN_NO');
		}
		if($type=='yes'){				
			$return = JText::_('COM_REDIRECTONLOGIN_YES');
		}	
		if($type=='menuitem'){				
			$return = JText::_('COM_REDIRECTONLOGIN_MENUITEM');
		}
		if($type=='dynamic'){				
			$return = JText::_('COM_REDIRECTONLOGIN_DYNAMIC');
		}
		if($type=='inherit'){				
			$return = JText::_('COM_REDIRECTONLOGIN_INHERIT');
		}	
		return $return;		
	}
	
	function redirect_type_list_yes($default_type, $type, $type2){	
		if($type=='yes'){
			$return = $this->redirect_type_list($default_type, $type2);
		}else{
			$return = $this->redirect_type_list($default_type, $type);
		}
		return $return;		
	}
	
	function rol_strtolower($string){
		if(function_exists('mb_strtolower')){			
			$string = mb_strtolower($string, 'UTF-8');
		}
		return $string;
	}
	
	function get_dynamic_link($dynamic_id, $user_id=0){		
	
		return array('', '', 0, '');
	}
	
	function get_usergroup_array($user_id){
		
		$database = JFactory::getDBO();
		
		//get user groups from this user
		jimport( 'joomla.user.helper' );
		$groups = JUserHelper::getUserGroups($user_id);	
		
		//make clean array
		$groups_array = array();
		for($n = 0; $n < count($groups); $n++){
			$row = each($groups);		
			$groups_array[] = $row['value'];
		}
		
		//get all groups in frontend order		
		$database->setQuery("SELECT group_id "
		."FROM #__redirectonlogin_order_groups "
		."ORDER BY redirect_order_front ASC "		
		);
		$usergroups = $database->loadObjectList();
		
		$return = 'array(';
		$count_return_groups = 0;
		$first = 1;
		foreach($usergroups as $usergroup){
			if(in_array($usergroup->group_id, $groups_array)){
				if($first){
					$first = 0;
				}else{
					$return .= ',';
				}
				$return .= $usergroup->group_id;
				$count_return_groups++;
			}	
		}
		$return .= ')';	
		
		if(count($groups_array)!=$count_return_groups){		
			//not all groups were in the order table
			//so just make an array of the groups without the order applied
			$return = 'array(';		
			$first = 1;
			foreach($groups_array as $group){			
				if($first){
					$first = 0;
				}else{
					$return .= ',';
				}
				$return .= $group;			
			}
			$return .= ')';	
		}
		
		return $return;
	}
	
	function get_accesslevel_array($user_id){
	
		$database = JFactory::getDBO();

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
		
		$return = 'array(';
		$count_return_levels = 0;
		$first = 1;
		foreach($accesslevels as $accesslevel){
			if(in_array($accesslevel->level_id, $levels_array)){
				if($first){
					$first = 0;
				}else{
					$return .= ',';
				}
				$return .= $accesslevel->level_id;
				$count_return_levels++;
			}	
		}
		$return .= ')';	
		
		if(count($levels_array)!=$count_return_levels){		
			//not all levels were in the order table
			//so just make an array of the levels without the order applied
			$return = 'array(';		
			$first = 1;
			foreach($levels_array as $level){			
				if($first){
					$first = 0;
				}else{
					$return .= ',';
				}
				$return .= $level;			
			}
			$return .= ')';	
		}	
		
		return $return;
	}
	
	function get_session_id(){
		$session_id = session_id();
		if(empty($session_id)){
			session_start();
			$session_id = session_id();	
		}
		return $session_id;
	}
	
	function get_link_from_menuitem($menu_id){

		$database = JFactory::getDBO();	
		$app = JFactory::getApplication();
		$router = $app->getRouter();		
		
		$url = '';
		$type = '';
		if($menu_id!=''){
			
			$database->setQuery("SELECT link, type, params "
			." FROM #__menu "
			." WHERE id='$menu_id' "
			." limit 1 "
			);
			$rows = $database->loadObjectList();
			$link = '';
			$type = '';
			$params = '';
			foreach($rows as $row){	
				$link = $row->link;	
				$type = $row->type;
				$params = $row->params;				
			}
			if($link!='') {
				if($router->getMode() == JROUTER_MODE_SEF) {
					$url = 'index.php?Itemid='.$menu_id;
				}else{
					$url = $link.'&Itemid='.$menu_id;
				}				
			}
			
		}	
		
		$url = JRoute::_($url);
		$url = str_replace('&amp;','&',$url);
		
		if($type=='alias'){
			//get the menu-item-id this alias points to			
			$registry = new JRegistry;
			$registry->loadString($params);
			$result = $registry->toArray();	
			$alias_menu_id = $result['aliasoptions'];		
			$url = $this->get_url_from_alias($alias_menu_id);			
		}	
		return $url;		
	}
	
	function get_url_from_alias($menu_id){
		//to recurse if menuitemtype is alias
		$url = $this->get_link_from_menuitem($menu_id);
		return $url;		
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
		return $config;			
	}
	
	function search_toolbar($show_search, $show_ordering, $show_orderdirection, $show_limitbox, $search, $sortfields, $list_dir, $limitbox){		
		
		$return = '';
		//search
		if($show_search){
			if($this->joomla_version >= '3.0'){			
				$return .= '<div class="filter-search btn-group pull-left">';
			}
			$return .= '<input type="text" name="filter_search" id="filter_search" value="'.$search.'" class="text_area"  />';
			if($this->joomla_version >= '3.0'){
				$return .= '</div>';
			}
			if($this->joomla_version >= '3.0'){
				$return .= '<div class="btn-group pull-left hidden-phone">';
				$return .= '<button class="btn hasTooltip" type="submit" title="'.JText::_('JSEARCH_FILTER_SUBMIT').'">';
				$return .= '<i class="icon-search"></i></button>';
				$return .= '<button class="btn hasTooltip" type="button" title="'.JText::_('JSEARCH_FILTER_CLEAR').'" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">';
				$return .= '<i class="icon-remove"></i></button>';
				$return .= '</div>';				
			}else{
				$return .= '&nbsp;<button onclick="this.form.submit();">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
				$return .= '&nbsp;<button onclick="document.adminForm.filter_search.value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';				
			}
		}
		
		//show_limitbox
		if($show_orderdirection && $this->joomla_version >= '3.0'){		
			$return .= '<div class="btn-group pull-right hidden-phone">';
			$return .= '<label for="limit" class="element-invisible">'.JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC').'</label>';
			$return .= $limitbox;
			$return .= '</div>';
		}
			
		//orderdirection
		if($show_orderdirection && $this->joomla_version >= '3.0'){		
			$return .= '<div class="btn-group pull-right hidden-phone">';
			$return .= '<label for="directionTable" class="element-invisible">'.JText::_('JFIELD_ORDERING_DESC').'</label>';
			$return .= '<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">';
			$return .= '<option value="">'.JText::_('JFIELD_ORDERING_DESC').'</option>';
			$return .= '<option value="asc"';
			if($list_dir == 'asc'){
				$return .= ' selected="selected"';
			}			
			$return .= '>'.JText::_('JGLOBAL_ORDER_ASCENDING').'</option>';
			$return .= '<option value="desc"';
			if($list_dir == 'desc'){
				$return .= ' selected="selected"';
			}			
			$return .= '>'.JText::_('JGLOBAL_ORDER_DESCENDING').'</option>';
			$return .= '</select>';
			$return .= '</div>';
		}
		
		//ordering
		if($show_ordering && $this->joomla_version >= '3.0'){		
			$return .= '<div class="btn-group pull-right">';
			$return .= '<label for="sortTable" class="element-invisible">'.JText::_('JGLOBAL_SORT_BY').'</label>';
			$return .= '<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">';
			$return .= '<option value="">'.JText::_('JGLOBAL_SORT_BY').'</option>';
			$return .= $sortfields;
			$return .= '</select>';
			$return .= '</div>';			
		}	
		
		return $return;
	}
	
	static public function tab_set_start($id, $active, $cookie, $tabs){
	
		$app = JFactory::getApplication();		
		$get_tab = JRequest::getVar('tab', '', 'get');		
		if(self::joomla_version() < 3){
			if($cookie){
				$cookie = true;
			}else{
				$cookie = false;
			}
			if($active){
				for($n = 0; $n < count($tabs); $n++){
					if($active==$tabs[$n]){
						$active_index = $n;
					}
				}				
			}
			if($get_tab){
				for($n = 0; $n < count($tabs); $n++){
					if($get_tab==$tabs[$n]){
						$active_index = $n;
					}
				}				
			}
			$options = array(
			'onActive' => 'function(title, description){
				description.setStyle("display", "block");
				title.addClass("open").removeClass("closed");
			}',
			'onBackground' => 'function(title, description){
				description.setStyle("display", "none");
				title.addClass("closed").removeClass("open");
			}',
			'startOffset' => $active_index,  // 0 starts on the first tab, 1 starts the second, etc...
			'useCookie' => $cookie, // this must not be a string. Don't use quotes.
			);
			echo JHtml::_('tabs.start', $id, $options);
		}else{			
			$session = $app->getUserState( "com_redirectonlogin.tab_".$id, '');			
			if($session!=''){
				$active = $session;
			}				
			if($get_tab && in_array($get_tab, $tabs)){				
				$active = $get_tab;			
			}		
			echo JHtml::_('bootstrap.startTabSet', $id, array('active' => $active));
			if($cookie){				
				$script = '<script>'."\n";
				$script .= 'var JNC_jQuery = jQuery.noConflict();'."\n";
				$script .= 'JNC_jQuery(function($){'."\n";
				$script .= '$(\'#'.$id.'Tabs a\').click(function(e){'."\n";				
				$script .= 'do_tab_session(\''.$id.'\',this.href);'."\n";
				$script .= '});'."\n";	
				$script .= '});'."\n";	
				$script .= '</script>'."\n";
				echo $script;
			}
		}		
	}
	
	static public function tab_add($set, $tab, $label){	
	
		if(self::joomla_version() < 3){
			echo JHtml::_('tabs.panel', $label, $set);
		}else{
			echo JHtml::_('bootstrap.addTab', $set, $tab, JText::_($label, true));//make label javascript save
		}
	}
	
	static public function tab_end(){	
	
		if(self::joomla_version() >= 3){			
			echo JHtml::_('bootstrap.endTab');
		}
	}
	
	static public function tab_set_end(){	
	
		if(self::joomla_version() < 3){
			echo JHtml::_('tabs.end');
		}else{
			echo JHtml::_('bootstrap.endTabSet');
		}
	}
	
	public static function joomla_version(){
		
		static $joomla_version;
		if(!$joomla_version){
			$version = new JVersion;
			$joomla_version = $version->RELEASE;
		}
		return $joomla_version;
	}
	
	
	
	
	
	
}
?>