<?php
/**
 * @package   akeebabackup
 * @copyright Copyright (c)2006-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JDEBUG ? define('AKEEBADEBUG', 1) : null;

define('AKEEBA_COMMON_WRONGPHP', 1);
$minPHPVersion         = '5.6.0';
$recommendedPHPVersion = '7.3';
$softwareName          = 'Akeeba Backup';
$silentResults         = true;

if (!require_once(JPATH_COMPONENT_ADMINISTRATOR . '/View/wrongphp.php'))
{
	die;
}

if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	throw new RuntimeException('FOF 3.0 is not installed', 500);
}

FOF30\Container\Container::getInstance('com_akeeba')->dispatcher->dispatch();
