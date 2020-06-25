<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;



class EventgalleryHelpersMedialoader
{

    static $loaded = false;

    /**
     * @param Joomla\Component\Eventgallery\Site\Library\Configuration\ $config
     * @throws Exception
     */
    public static function load($config = null)
    {

        if (self::$loaded) {
            return;
        }

        self::$loaded = true;

    	include_once JPATH_ROOT . '/administrator/components/com_eventgallery/version.php';

        $document = JFactory::getDocument();
        $app = JFactory::getApplication();

        //JHtml::_('behavior.framework', true);
        JHtml::_('behavior.formvalidator');

        if ($config == null) {
            $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        }

        $doDebug = $config->getGeneral()->doDebug();
        $doManualDebug = $app->input->getString('debug', '') == 'true';
		$loadResponsiveCSS = $config->getGeneral()->doLoadResponsiveCSS();

        $CSSs = Array();
        $JSs = Array();

        JHtml::_('jquery.framework');

        $JSs[] = 'common/js/jquery/namespace.js';
        
        // load script and styles in debug mode or compressed
        if ($doDebug || $doManualDebug) {

            $CSSs[] = 'dist/eventgallery-debug.css';
            if ($loadResponsiveCSS == 1) {
                $CSSs[] = 'dist/responsive-static-debug.css';
            }
            if ($loadResponsiveCSS == 2) {
                $CSSs[] = 'dist/responsive-fluid-debug.css';
            }
            $JSs[] = 'dist/eventgallery-debug.js';

        } else {
            $CSSs[] = 'dist/eventgallery.css';
            if ($loadResponsiveCSS == 1) {
                $CSSs[] = 'dist/responsive-static.css';
            }
            if ($loadResponsiveCSS == 2) {
                $CSSs[] = 'dist/responsive-fluid.css';
            }
            $JSs[] = 'dist/eventgallery.js';
        }

        foreach($CSSs as $css) {
            $script = JUri::root(true) . '/media/com_eventgallery/'.$css.'?v=' . EVENTGALLERY_VERSION;
            $document->addStyleSheet($script);
        }

        foreach($JSs as $js) {
            $script = JUri::root(true) . '/media/com_eventgallery/'.$js.'?v=' . EVENTGALLERY_VERSION;
            $document->addScript($script);
        }


        $lightboxConfiguration = Array();
        $lightboxConfiguration['navigationFadeDelay'] = $config->getLightbox()->getNavigationFadeDelay();
        $lightboxConfiguration['slideshowSpeed'] = $config->getLightbox()->getSlideshowSpeed();
        $lightboxConfiguration['doUseSlideshow'] = $config->getLightbox()->doUseSlideshow();
        $lightboxConfiguration['doUseAutoplay'] = $config->getLightbox()->doUseAutoplay();
        $lightboxConfiguration['doPreventRightClick'] = $config->getLightbox()->doPreventRightClick();
        $lightboxConfiguration['KEY_CLOSE'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_CLOSE');
        $lightboxConfiguration['KEY_SHARE'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_SHARE');
        $lightboxConfiguration['KEY_BUY'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_BUY');
        $lightboxConfiguration['KEY_ZOOM'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_ZOOM');
        $lightboxConfiguration['KEY_PREVIOUS'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_PREVIOUS');
        $lightboxConfiguration['KEY_NEXT'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_NEXT');
        $lightboxConfiguration['KEY_FULLSCREEN'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_FULLSCREEN');
        $lightboxConfiguration['KEY_PLAYSLIDESHOW'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_PLAYSLIDESHOW');
        $lightboxConfiguration['KEY_PAUSESLIDESHOW'] = JText::_('COM_EVENTGALLERY_LIGHTBOX_PAUSESLIDESHOW');
        $document->addScriptDeclaration("window.EventGalleryLightboxConfiguration=" . json_encode($lightboxConfiguration) . ";");

        $cartConfiguration = Array();
        $cartConfiguration['add2carturl'] = JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=imagesetselection&format=raw', false);
        $document->addScriptDeclaration("EventGalleryCartConfiguration=" . json_encode($cartConfiguration) . ";");

        $googlePhotosConfiguration = Array();
        $googlePhotosConfiguration['albumUrl'] = JRoute::_('index.php?option=com_eventgallery&view=googlephotosapi&task=getAlbum&format=raw', false);
        $googlePhotosConfiguration['albumsUrl'] = JRoute::_('index.php?option=com_eventgallery&view=googlephotosapi&task=getAlbums&format=raw', false);

        $document->addScriptDeclaration("EventGalleryGooglePhotosConfiguration=" . json_encode($googlePhotosConfiguration) . ";");
    }

}



