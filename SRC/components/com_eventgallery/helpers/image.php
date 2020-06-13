<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
define('_JEXEC', 1);

// useless, just to satisfy the jedChecker
defined('_JEXEC') or die;


if (!defined('_JDEFINES')) {
    // remove the first 3 folders because
    // we're in a subfolder and have not
    // native Joomla help. Doing this will
    // enable this comonent to run in a subdirectory
    // like http://foo.bar/foobar
    $basefolders = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
    $basefolders = array_splice($basefolders, 0, count($basefolders) - 3);
    define('JPATH_BASE', implode(DIRECTORY_SEPARATOR, $basefolders));
    require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . '/components/com_eventgallery/config.php';

function image404() {
    header("HTTP/1.0 404 Not Found");
    echo "This image is not available";
    die();
}


$ji	 = new JInput();

$file = $ji->getString('file');
$folder = $ji->getString('folder');
$width = $ji->getInt('width', -1);
$site = $ji->getInt('site', 0);


$file = str_replace("\.\.", "", $file);
$folder = str_replace("\.\.", "", $folder);
$width = str_replace("\.\.", "", $width);

$file = str_replace("/", "", $file);
$folder = str_replace("/", "", $folder);
$width = str_replace("/", "", $width);

$file = str_replace("\\", "", $file);
$folder = str_replace("\\", "", $folder);
$width = str_replace("\\", "", $width);

if (version_compare(JVERSION, '4.0', '>=') == 1) {

    // Boot the DI container
    $container = \Joomla\CMS\Factory::getContainer();

    /*
     * Alias the session service keys to the web session service as that is the primary session backend for this application
     *
     * In addition to aliasing "common" service keys, we also create aliases for the PHP classes to ensure autowiring objects
     * is supported.  This includes aliases for aliased class names, and the keys for aliased class names should be considered
     * deprecated to be removed when the class name alias is removed as well.
     */
    $container->alias('session.web', 'session.web.site')
        ->alias('session', 'session.web.site')
        ->alias('JSession', 'session.web.site')
        ->alias(\Joomla\CMS\Session\Session::class, 'session.web.site')
        ->alias(\Joomla\Session\Session::class, 'session.web.site')
        ->alias(\Joomla\Session\SessionInterface::class, 'session.web.site');

    if ($site == 1) {
        $app = $container->get(\Joomla\CMS\Application\AdministratorApplication::class);
    } else {
        $app = $container->get(\Joomla\CMS\Application\SiteApplication::class);
    }
    \Joomla\CMS\Factory::$application = $app;
}

if ($site == 1) {
    $app = JFactory::getApplication('administrator');
} else {
    $app = JFactory::getApplication('site');
}
$app->loadSession();

require_once JPATH_BASE . '/components/com_eventgallery/helpers/sizeset.php';

$sizeSet = new EventgalleryHelpersSizeset();
$saveAsSize = $sizeSet->getMatchingSize($width);


$basedir = COM_EVENTGALLERY_IMAGE_FOLDER_PATH;
$sourcedir = $basedir . $folder;
$cachebasedir = str_replace(DIRECTORY_SEPARATOR . 'administrator' , '', JPATH_CACHE . DIRECTORY_SEPARATOR . 'com_eventgallery_images' . DIRECTORY_SEPARATOR);

$cachedir = $cachebasedir . $folder;
$cachedir_thumbs = $cachebasedir . $folder;

$image_file = $sourcedir . DIRECTORY_SEPARATOR . $file;


define('JPATH_COMPONENT', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' .  DIRECTORY_SEPARATOR . 'com_eventgallery');
//load classes
require_once JPATH_ROOT . '/components/com_eventgallery/vendor/autoload.php';
JLoader::registerPrefix('Eventgallery', JPATH_COMPONENT);




$currentUser = JFactory::getUser();

/**
 * Fix the guest user group. This is normally set by the application. But since we're in a
 * simple script here we need to take care our own.
 */
if ($currentUser->guest)
{
    $guestUsergroup = JComponentHelper::getParams('com_users')->get('guest_usergroup', 1);
    $currentUser->groups = array($guestUsergroup);
}

/**
 * @var EventgalleryLibraryFactoryFile $fileFactory
 */
$fileFactory = EventgalleryLibraryFactoryFile::getInstance();
try {
    $fileObj = $fileFactory->getFile($folder, $file);
} catch (InvalidArgumentException $e) {
    image404();
}

if ($fileObj == null) {
    image404();
}
$image_thumb_file = EventgalleryLibraryCommonImageprocessor::calculateCacheThumbnailName($saveAsSize, true, $file, $folder, $fileObj->isMainImage());

$folderObj = $fileObj->getFolder();
if ($site==0 || !$currentUser->authorise('core.manage', 'com_eventgallery')) {
    if (!$fileObj->isMainImage()) {
        if (!$folderObj->isVisible() || !$folderObj->isAccessible()) {
            $url = JUri::root().'../../../' . COM_EVENTGALLERY_IMAGE_NO_ACCESS;
            header("HTTP/1.1 302 Found");
            header("Location: $url");
            header('Content-Type: text/plain');
            header('Connection: close');
            flush();
            die();
        }
    }
}

if (!file_exists($image_thumb_file)) {

    $url = EventgalleryLibraryCommonUrlhelper::url_origin($_SERVER, true).$_SERVER['REQUEST_URI'];
    if ($site == 0) {
        $url = str_replace('components/com_eventgallery/helpers/image.php', 'index.php', $url);
        $url .= '&option=com_eventgallery&view=resizeimage';
    } else {
        $url = str_replace('components/com_eventgallery/helpers/image.php', 'administrator/index.php', $url);
        $url .= '&option=com_eventgallery&task=resizeimage.display';
    }
    header("HTTP/1.1 302 Found");
    header("Location: $url");
    header('Content-Type: text/plain');
    header('Connection: close');
    flush();
    die();
}

$mime = ($mime = getimagesize($image_thumb_file)) ? $mime['mime'] : $mime;
$size = filesize($image_thumb_file);
$fp   = fopen($image_thumb_file, "rb");
if (!($mime && $size && $fp)) {
    // Error.
    return;
}

$last_modified = gmdate('D, d M Y H:i:s T', filemtime ($image_file));

header("Content-Type: " . $mime);
header("Content-Length: " . $size);
header("Last-Modified: $last_modified");


fpassthru($fp);
die();
