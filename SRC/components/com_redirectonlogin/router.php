<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

defined('_JEXEC') or die;

function redirectonloginBuildRoute( &$query ){

	$segments = array();
	if(isset($query['view'])){
		$segments[] = $query['view'];
		unset( $query['view'] );
	}
	if(isset($query['id'])){
		$segments[] = $query['id'];
		unset( $query['id'] );
	}
	return $segments;
}

function redirectonloginParseRoute($segments){

	$vars = array();
	switch($segments[0]){
	
		case 'logout':
			$vars['view'] = 'logout';					  
			break;	
		case 'dynamicredirect':
			$vars['view'] = 'dynamicredirect';	
			$vars['id'] = $segments[1];				  
			break;
	}
	//print_r($vars);
	//exit;
	return $vars;
}

?>