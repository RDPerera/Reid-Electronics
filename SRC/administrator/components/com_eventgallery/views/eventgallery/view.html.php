<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

/** @noinspection PhpUndefinedClassInspection */
class EventgalleryViewEventgallery extends EventgalleryLibraryCommonView
{

	function display($tpl = null)
	{
        $app = JFactory::getApplication();

        if (EVENTGALLERY_EXTENDED) {
            $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();

            $downloadid = $config->getGeneral()->getDownloadId();

            if (strlen($downloadid)<10) {
                $app->enqueueMessage(JText::_('COM_EVENTGALLERY_OPTIONS_COMMON_DOWNLOADID_MISSING_WARNING'),'warning');
            }

        }

		EventgalleryHelpersEventgallery::addSubmenu('eventgallery');
		$this->sidebar = JHtmlSidebar::render();
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		JToolbarHelper::title(   EventgalleryHelpersEventgallery::getTitle() . " ". EVENTGALLERY_VERSION . ' (build ' . EVENTGALLERY_VERSION_SHORTSHA . ')', 'generic.png' );
		JToolbarHelper::preferences('com_eventgallery', '550');

		JToolbarHelper::spacer(100);

		$bar = JToolbar::getInstance('toolbar');

		// Add a trash button.

		$bar->appendButton('Link', 'trash', 'COM_EVENTGALLERY_SUBMENU_CLEAR_CACHE',  JRoute::_('index.php?option=com_eventgallery&view=cache'), false);
		$bar->appendButton('Link', 'checkin', 'COM_EVENTGALLERY_SUBMENU_SYNC_DATABASE',  JRoute::_('index.php?option=com_eventgallery&view=sync'), false);
        $bar->appendButton('Link', 'checkin', 'COM_EVENTGALLERY_GOOGLEPHOTOSSYNC',  JRoute::_('index.php?option=com_eventgallery&view=googlephotossync'), false);
		$bar->appendButton('Link', 'checkin', 'COM_EVENTGALLERY_IMPEX_LABEL',  JRoute::_('index.php?option=com_eventgallery&view=impex'), false);

	}
}

