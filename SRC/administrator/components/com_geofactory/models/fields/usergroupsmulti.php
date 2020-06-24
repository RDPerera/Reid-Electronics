<?php
/**
 *
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 *
 */
defined('JPATH_BASE') or die;

class JFormFieldUserGroupsMulti extends JFormFieldList {
	protected $type = 'UserGroupsMulti';

	protected function getOptions() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level')
			->from($db->quoteName('#__usergroups') . ' AS a')
			->join('LEFT', $db->quoteName('#__usergroups') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->group('a.id, a.title, a.lft, a.rgt')
			->order('a.lft ASC');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		for ($i = 0, $n = count($options); $i < $n; $i++)
			$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;

		array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_ACCESS_SHOW_ALL_GROUPS')));

		return $options ;
	}
}



