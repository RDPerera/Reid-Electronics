<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.53Free
	* Creation date: Mars 2019
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/
defined('_JEXEC') or die;

class GmapfpRouter extends JComponentRouterBase
{
	public function build(&$query)
	{
		$segments = array();
			
		// get a menu item based on Itemid or currently active
		$app	= JFactory::getApplication('site');
		$menu	= $app->getMenu();
		if (empty($query['Itemid'])) {
			$menuItem = $menu->getActive();
		} else {
			$menuItem = $menu->getItem($query['Itemid']);
		}

		$mView		= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
		$mLayout	= (empty($menuItem->query['layout'])) ? null : $menuItem->query['layout'];
		$mCatid		= (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
		$mId		= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];
		
		if(isset($mView))
		{
			if(empty($query['Itemid'])) {
				if (!isset($query['layout']) || !$query['layout']=='article') $segments[] = $query['view'];
			}else {
				if(($mView=='gmapfplist' or $mView=='gmapfplistcomplex') and array_key_exists('layout', $query) and 
					($query['layout']!='print_article' and $query['layout']!='item_carte' and $query['layout']!='item_msg')) {
					unset($query['layout']);
					unset($query['catid']);
				}
			}
			unset($query['view']);
		};

		if(isset($query['layout']))
		{
			if ($query['layout']!='article')
				$segments[] = $query['layout'];
			unset($query['layout']);
		};

		if (isset($query['catid'])) {
			$segments[] = $query['catid'];
			unset($query['catid']);
		};

		if(isset($query['id'])) {
			$segments[] = $query['id'];
			unset($query['id']);
		};

		if(isset($query['cid'])) {
			$segments[] = $query['cid'];
			unset($query['cid']);
		};

		if(isset($query['controller']))
		{
			unset($query['controller']);
		};

		if(isset($query['task']))
		{
			unset($query['task']);
		};

		if(isset($query['tmpl']))
		{
	//		unset($query['tmpl']);
		};

		if(isset($query['id_perso']))
		{
			unset($query['id_perso']);
		};
	//die(print_r($segments));
		return $segments;
	}

	public function parse(&$segments)
	{
		$vars = array();
		
		//Get the active menu item
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();

		// Count route segments
		$count = count($segments);
	//die(print_r($segments));
		if ($segments[0] == 'print_article' or $segments[0] == 'horaires_item')
		{
			$vars['view']  = 'gmapfp';
			$vars['layout']  = $segments[0];
			$vars['id']    = $segments[1];
			return $vars;
		}

		//Standard routing for articles
		if(!isset($item))
		{
			$mainframe = JFactory::getApplication(); 
			$params = clone($mainframe->getParams('com_gmapfp'));
			$Itemid = $params->get('gmapfp_default_item');
			if ($Itemid) $vars['Itemid'] = $Itemid;

			if ($count == 1)
			{
				$vars['view']  = 'gmapfp';
				$vars['layout']  = 'article';
				$vars['id']    = $segments[0];
				return $vars;
			}
			if ($count == 2)
			{
				$vars['view']  = 'gmapfp';
				$vars['layout']  = $segments[0];
				$vars['id']    = $segments[1];
				return $vars;
			}
			if ($count > 2)
			{
				$vars['view']  = $segments[0];
				$vars['layout']  = $segments[1];
				$vars['id']    = $segments[$count - 1];
				return $vars;
			}
		}

		//Handle View and Identifier
		switch($item->query['view'])
		{
			case 'gmapfp' :
			case 'gmapfplist'   :
			case 'gmapfplistcomplex'   :
			{
				if($count == 1) {
					$vars['view']  = 'gmapfp';
					$vars['layout']  = 'article';
					$vars['id'] = $segments[0];
				}
				if($count == 2) {
					$vars['view']  = 'gmapfp';
					$vars['layout']  = $segments[0];
					$vars['id'] = $segments[1];
				}
			} break;

			case 'gmapfpcontact' :
			{
				if($count == 2) {
					$vars['view']  = 'gmapfpcontact';
					$vars['layout']  = $segments[0];
					$vars['id'] = $segments[1];
				}
			} break;

			case 'editlieux'   :
			{
				$vars['view'] = 'editlieux';
				if ($segments[0] == 'article') $vars['view'] = 'gmapfp';
				$vars['layout']  = $segments[0];
				$vars['id'] = @$segments[1];
			} break;

			case 'gestionlieux'   :
			{
				if (!empty($segments[0]))
				{
					switch ($segments[0])
					{
						case 'default' :
						{
							$vars['view'] = 'gestionlieux';
							$vars['layout'] = 'default';
						} break;

						default :
						{
							if ($count == 1) {
								$vars['controller'] = 'gestionlieux';
								$vars['task'] = 'edit';
								$vars['cid'] = $segments[0];						
							} else {
								$vars['view'] = 'editlieux';
								if ($segments[0] == 'article') $vars['view'] = 'gmapfp';
								$vars['layout'] = $segments[0];
								$vars['cid'] = $segments[$count-1];						
							}
						}
					}
				}else{
					$vars['view'] = 'gestionlieux';
				}
			} break;

		}
	//die(print_r($item->query['view']));
	//die(print_r($segments));
		return $vars;
	}
}

function GmapfpBuildRoute(&$query)
{
	$router = new GmapfpRouter;

	return $router->build($query);
}

function GmapfpParseRoute($segments)
{
	$router = new GmapfpRouter;

	return $router->parse($segments);
}
