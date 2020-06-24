<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;


class EventgalleryViewSync extends EventgalleryLibraryCommonView
{
    public $config;

	function display($tpl = null)
	{
	    $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        $this->addToolbar();
		EventgalleryHelpersEventgallery::addSubmenu('overview');		
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	protected function addToolbar() {
		$bar = JToolbar::getInstance('toolbar');
		JToolbarHelper::title(   JText::_('COM_EVENTGALLERY_SUBMENU_SYNC_DATABASE') );
		JToolbarHelper::cancel( 'sync.cancel', 'Close' );
		$bar->appendButton('Link', 'checkin', 'COM_EVENTGALLERY_SUBMENU_THUMBNAILGENERATOR',  JRoute::_('index.php?option=com_eventgallery&view=thumbnailgenerator'), false);
	}
}

