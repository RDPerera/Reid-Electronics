<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controllerform' );


class EventgalleryControllerGooglephotos extends JControllerForm
{
    public function getAlbums() {
        header('Content-Type: application/json');
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        $id = $app->input->getInt('id');

        /**
         * @var EventgalleryLibraryFactoryGooglephotosaccount $accountFactory
         *
         */
        $accountFactory = EventgalleryLibraryFactoryGooglephotosaccount::getInstance();
        $account = $accountFactory->getGooglePhotosAccountById($id);

        $albums = \Joomla\Component\Eventgallery\Site\Library\Connector\GooglePhotos::getAlbums(COM_EVENTGALLERY_GOOGLE_PHOTOS_ALBUMS_CACHE_LIFETIME, $account->getClientId(), $account->getSecret(), $account->getRefreshToken(), $db);

        echo '{"albums":' . json_encode($albums) . '}';
        die();
    }

}
