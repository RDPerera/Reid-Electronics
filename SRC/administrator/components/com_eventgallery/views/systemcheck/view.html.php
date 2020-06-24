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
class EventgalleryViewSystemcheck extends EventgalleryLibraryCommonView
{

    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
	protected $config;
	protected $schemaversions;
	protected $installedextensions;
    protected $doShowLogs = false;

	function display($tpl = null)
	{				
        /**
		 * @var eventgalleryModelSystemcheck $model
		 */
		$model = $this->getModel();
		$this->schemaversions = $model->getSchemaversions();
		$this->installedextensions = $model->getInstalledextensions();
		$this->changeset = $model->getChangeSet();
        $this->changeseterrors = $this->changeset->check();
        $app = JFactory::getApplication();
        $this->doShowLogs = $app->input->getBool('showlogs', false);

		$this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
		EventgalleryHelpersEventgallery::addSubmenu('systemcheck');
		$this->sidebar = JHtmlSidebar::render();
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		JToolbarHelper::title(   EventgalleryHelpersEventgallery::getTitle() . " ". EVENTGALLERY_VERSION . ' (build ' . EVENTGALLERY_VERSION_SHORTSHA . ')', 'generic.png' );

	}
}

