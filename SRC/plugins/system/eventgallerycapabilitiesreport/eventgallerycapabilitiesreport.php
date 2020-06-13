<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die;

/**
 * Updates picasa albums during a request.
 *
 * @package     Joomla.Plugin
 * @since       2.5
 */
class plgSystemEventgallerycapabilitiesreport extends JPlugin
{

    public function onPrivacyCollectAdminCapabilities()
    {
        // If a plugin does not have its language files autoloaded, ensure you manually load the language files now otherwise the below may not be translated
        $this->loadLanguage();

        return array(
            JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_EXTENSION_NAME') => array(
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_COOKIES'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_GOOGLEPHOTOS'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_PICASAPHOTOS'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_FLICKR'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_S3'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_CHECKOUT'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_PAYMENT_STRIPE'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_PAYMENT_PAYPAL'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_PAYMENT_BRAINTREE'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_SHARING'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_LOGS'),
                JText::_('PLG_SYSTEM_EVENTGALLERY_CAPABILITIES_REPORT_YOUTUBE'),
            ),
        );
    }

}