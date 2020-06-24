<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class EventgalleryHelpersBackendmedialoader
{

    static $loaded = false;

    public static function load()
    {

        if (self::$loaded) {
            return;
        }

        self::$loaded = true;

        include_once JPATH_ROOT . '/administrator/components/com_eventgallery/version.php';

        $document = JFactory::getDocument();

        JHtml::_('behavior.formvalidator');

        // Add the modal field script to the document head.
        \JHtml::_('jquery.framework');
        \JHtml::_('script', 'system/fields/modal-fields.min.js', array('version' => 'auto', 'relative' => true));


        $CSSs = Array();
        $JSs = Array();

        JHtml::_('jquery.framework');

        $JSs[] = 'common/js/jquery/namespace.js';
        $JSs[] = 'dist/backend.js';

        $CSSs[] = 'dist/backend.css';

        if (version_compare(JVERSION, '4.0', '<' ) == 1) {
            $CSSs[] = 'backend/css/joomla3.css';
        }

        $JSs = array_merge($JSs, Array(                
        ));

        foreach($CSSs as $css) {
            $script = JUri::root() . 'media/com_eventgallery/'.$css.'?v=' . EVENTGALLERY_VERSION;
            $document->addStyleSheet($script);
        }

        foreach($JSs as $js) {
            $script = JUri::root() . 'media/com_eventgallery/'.$js.'?v=' . EVENTGALLERY_VERSION;
            $document->addScript($script);
        }

        $googlePhotosConfiguration = Array();
        $googlePhotosConfiguration['albumUrl'] = str_replace('administrator/', NULL, JRoute::_('index.php?option=com_eventgallery&view=googlephotosapi&task=getAlbum&format=raw', false));
        $googlePhotosConfiguration['albumsUrl'] = str_replace('administrator/', NULL, JRoute::_('index.php?option=com_eventgallery&view=googlephotosapi&task=getAlbums&format=raw', false));

        $document->addScriptDeclaration("EventGalleryGooglePhotosConfiguration=" . json_encode($googlePhotosConfiguration) . ";");

    }

}

	
	
