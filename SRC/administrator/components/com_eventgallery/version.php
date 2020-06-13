<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

if (!defined('EVENTGALLERY_VERSION')) {

	$db = JFactory::getDbo();

	$sql = $db->getQuery(true)
		->select($db->quoteName('name'))
		->from($db->quoteName('#__extensions'))
		->where($db->quoteName('type').' = '.$db->quote('package'))
		->where($db->quoteName('element').' = '.$db->quote('pkg_eventgallery_full'));
	$db->setQuery($sql);
	$result = $db->loadResult();

	$isFull = $result!=null?true:false;


	define('EVENTGALLERY_EXTENDED', $isFull);
	define('EVENTGALLERY_VERSION', '3.11.4');
	define('EVENTGALLERY_VERSION_SHORTSHA', '87b57f74');
	define('EVENTGALLERY_DATABASE_VERSION', '3.11.2_2019-11-20');
	define('EVENTGALLERY_DATE', '06/03/2020');
}
