<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventgalleryHelper extends JHelperContent {
    public static function addSubmenu($vName = 'events') {
        EventgalleryHelpersEventgallery::addSubmenu($vName);
    }
}

class EventgalleryHelpersEventgallery extends JHelperContent
{
    /**
     * Returns the name of the extionsion.
     *
     * @return string
     */
    public static function getTitle() {
        $name = 'COM_EVENTGALLERY_EVENTGALLERY';
        if (EVENTGALLERY_EXTENDED) {
            $name =   'COM_EVENTGALLERY_EVENTGALLERY_EXTENDED';
        }

        return JText::_($name);

    }

	public static function addSubmenu($vName = 'events')
	{
        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_EVENTGALLERY'),
            'index.php?option=com_eventgallery',
            $vName == 'eventgallery'
        );

        JHtmlSidebar::addEntry(
            '<hr>',
            '#',
            false);

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS'),
			'index.php?option=com_eventgallery&view=events',
			$vName == 'events' || $vName=='event' || $vName=='files' || $vName=='file'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS'),
			'index.php?option=com_eventgallery&view=orders',
			$vName == 'orders' || $vName == 'order');

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_MESSAGES'),
            'index.php?option=com_eventgallery&view=messages',
            $vName == 'messages' || $vName == 'message');

        JHtmlSidebar::addEntry(
            '<hr>',
            '#',
            false);

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_CATEGORIES'),
            'index.php?option=com_categories&extension=com_eventgallery',
            $vName == 'categories');

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_GOOGLEPHOTOSACCOUNTS'),
            'index.php?option=com_eventgallery&view=googlephotosaccounts',
            $vName == 'googlephotosaccounts' || $vName == 'googlephotosaccount');

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_WATERMARKS'),
            'index.php?option=com_eventgallery&view=watermarks',
            $vName == 'watermarks' || $vName == 'watermark');

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES'),
            'index.php?option=com_eventgallery&view=imagetypes',
            $vName == 'imagetypes' || $vName == 'imagetype');

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPEGROUPS'),
            'index.php?option=com_eventgallery&view=imagetypegroups',
            $vName == 'imagetypegroups' || $vName == 'imagetypegroup');

        JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS'),
			'index.php?option=com_eventgallery&view=imagetypesets',
			$vName == 'imagetypesets' || $vName == 'imagetypeset');

 		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_ORDERSTATUSES'),
			'index.php?option=com_eventgallery&view=orderstatuses',
			$vName == 'orderstatuses' || $vName == 'orderstatuse');

        JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES'),
			'index.php?option=com_eventgallery&view=surcharges',
			$vName == 'surcharges' || $vName == 'surcharge');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS'),
			'index.php?option=com_eventgallery&view=shippingmethods',
			$vName == 'shippingmethods' || $vName == 'shippingmethod');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS'),
			'index.php?option=com_eventgallery&view=paymentmethods',
			$vName == 'paymentmethods' || $vName == 'paymentmethod');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_EMAILTEMPLATES'),
			'index.php?option=com_eventgallery&view=emailtemplates',
			$vName == 'emailtemplates' || $vName == 'emailtemplate');

        JHtmlSidebar::addEntry(
            '<hr>',
            '#',
            false);

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION'),
			'index.php?option=com_eventgallery&view=documentation',
			$vName == 'documentation'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SYSTEMCHECK'),
			'index.php?option=com_eventgallery&view=systemcheck',
			$vName == 'systemcheck'
		);

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_GDPR'),
            'index.php?option=com_eventgallery&view=gdpr',
            $vName == 'gdpr'
        );
	}

}
