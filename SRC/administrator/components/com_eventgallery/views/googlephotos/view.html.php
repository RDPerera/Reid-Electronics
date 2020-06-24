<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;



jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');


class EventgalleryViewGooglephotos extends EventgalleryLibraryCommonView
{

    /**
     * @var EventgalleryLibraryGooglephotosaccount
     */
    public $googlePhotosAccount;
    /**
     * Display the view
     * @param null $tpl
     * @return bool|mixed
     */
	public function display($tpl = null)
	{

        $app = JFactory::getApplication();

        $id = $app->input->getInt('id');

        /**
         * @var EventgalleryLibraryFactoryGooglephotosaccount $accountFactory
         *
         */
        $accountFactory = EventgalleryLibraryFactoryGooglephotosaccount::getInstance();
        $this->googlePhotosAccount = $accountFactory->getGooglePhotosAccountById($id);

        return parent::display($tpl);
	}
}