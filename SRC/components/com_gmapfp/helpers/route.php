<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.32F
	* Creation date: Août 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

abstract class GMapFPHelperRoute
{
	protected static $lookup;
	protected static $id;

	public static function getArticleRoute($id, $catid = 0, $language = 0)
	{
		$lang	= '';
		$db		= JFactory::getDBO();
		
		$needles = array(
			'id'  => (int) $id
		);

		//Create the link
		if (empty($catid)) {
			$query	= $db->getQuery(true);
			$query->select('catid');
			$query->from('#__gmapfp');
			$query->where('id='.(int)$id);
			$db->setQuery($query);
			$catid = $db->loadResult();
		}
		if ((int)$catid > 1) {
			$categories = JCategories::getInstance('GMapFP');
			$category = $categories->get((int)$catid);
			if($category)
			{
				$needles['catid'] = array_reverse($category->getPath());
			}
		}
		self::$id = $id;

		if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
			$query	= $db->getQuery(true);
			$query->select('a.sef AS sef');
			$query->select('a.lang_code AS lang_code');
			$query->from('#__languages AS a');
			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang) {
				if ($language == $lang->lang_code) {
					$language = $lang->sef;
					$lang .= '&lang='.$language;
				}
			}
		}
		if ($item = self::_findItem($needles)) {
			if (array_key_exists('id', $item)) $link = 'index.php?&Itemid='.$item['id'].$lang;
			elseif (array_key_exists('catid', $item)) $link = 'index.php?option=com_gmapfp&view=gmapfp&layout=article&id='.$id.'&Itemid='.$item['catid'].$lang;
			elseif (array_key_exists('all', $item)) $link = 'index.php?option=com_gmapfp&view=gmapfp&layout=article&id='.$id.'&Itemid='.$item['all'].$lang;
		} else {
			$link = 'index.php?option=com_gmapfp&view=gmapfp&layout=article&id='.$id.''.$lang;
		}

		return $link;
	}

	public static function getCategoryRoute($catid, $display = '')
	{
		$link = 'index.php?option=com_gmapfp&view=gmapfp&catid='.$catid;

		//recherche si cat appartient a un groupe
		if ((int)$catid > 1)
		{
			$categories = JCategories::getInstance('GMapFP');
			$category = $categories->get((int)$catid);
			if($category)
			{
				$needles['catid'] = array_reverse($category->getPath());
			}
		}

		$view = '&view=gmapfp';
		if ($display  = 'list') $view = '&view=gmapfplist';

		//Create the link
		if ($item = self::_findItem($needles)) {
			if (array_key_exists('catid', $item)) $link = 'index.php?option=com_gmapfp'.$view.'&catid='. $id.'&Itemid='.$item['catid'];
			elseif (array_key_exists('all', $item)) $link = 'index.php?option=com_gmapfp'.$view.'&catid='. $id.'&Itemid='.$item['all'];
		}

		return $link;
	}

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$views		= array('gmapfp', 'gmapfplist', 'gmapfplistcomplex');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_gmapfp');
			$items		= $menus->getItems('component_id', $component->id);
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					if (in_array($view, $views)) {
						$params = $item->params;
						$where = $params->get('gmapfp_filtre_sql', '');
						$canAddIt = 1;
						if ($where) {
							self::$lookup['where'][$where] = $item->id;
							self::$lookup[$item->id]['where'] = $where;
							$canAddIt = (strpos($where, 'a.catid')===false);
						}
						$catid	= $params->get('catid');
						if ($catid && $canAddIt) {
							self::$lookup['catid'][$catid] = $item->id;
							self::$lookup[$item->id]['catid'] = $catid;
						}
						$id	= $params->get('id');
						if ($id) {
							self::$lookup['id'][$id] = $item->id;
						}
						if (!$id and !$catid and !$where) {
							self::$lookup['all'][] = $item->id;
						}
					}
				}
			}
		}
		
		//priorité au menu actif
		$active = $menus->getActive();
		if ($active && $active->component == 'com_gmapfp' && isset($active->query['view']) && in_array($active->query['view'], $views)) {
			if (isset($active->query['layout']) and $active->query['layout'] == 'article') {
				if($active->params->get('id') == $needles['id']) return array('all'=>$active->id);
			} else {
				if (self::_idExist($needles['id'], self::$lookup, $active->id)) return array('all'=>$active->id);			
			}
		}

		//sinon recherche par article, catégories et tout.
		if ($needles)
		{
			foreach ($needles as $type => $ids)
			{
				if (isset(self::$lookup[$type]) && ($type == 'id' || $type == 'catid' || $type == 'all'))
				{
					if (is_array($ids))
						foreach($ids as $id)
						{
							if (isset(self::$lookup[$type][(int)$id])) {
								if (self::_idExist($needles['id'], self::$lookup, $id)) return array($type=>self::$lookup[$type][(int)$id]);
							}
						}
					else
						if (isset(self::$lookup[$type][(int)$ids])) {
							if (self::_idExist($needles['id'], self::$lookup, $ids)) return array($type=>self::$lookup[$type][(int)$ids]);
						}
				}
			}
			if (isset(self::$lookup['all']))
				return array('all'=>self::$lookup['all'][0]);
		}

		return null;
	}

	protected static function _idExist($lieu_id = null, $options = null, $menuId) {
		$db			= JFactory::getDBO();
		
		$query	= $db->getQuery(true);
		$query
			->select('a.id as mon_id')
			->from('#__gmapfp as a')
			->join('INNER', $db->quoteName('#__categories', 'b') . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id') . ')')
			->join('LEFT OUTER', $db->quoteName('#__content', 'c') . ' ON (' . $db->quoteName('a.article_id') . ' = ' . $db->quoteName('c.id') . ')');
		if (isset($options[$menuId]['catid'])) $query->where('a.catid='.$options[$menuId]['catid']);
		if (isset($options[$menuId]['where'])) $query->where($options[$menuId]['where']);
		$db->setQuery($query);
		$ids = $db->loadObjectList('mon_id');
		
		return (isset($ids[$lieu_id])); 
	}
}
?>
