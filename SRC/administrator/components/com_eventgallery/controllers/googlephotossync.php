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

use \Joomla\Component\Eventgallery\Site\Library\Connector\GooglePhotos;

require_once(__DIR__.'/../controller.php');

class EventgalleryControllerGooglephotossync extends JControllerForm
{

    protected $default_view = 'googlephotossync';

	public function getModel($name = 'Googlephotossync', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * function to provide the Google Photos Sync-View
     * @throws Exception
     */
	function sync()
	{
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        $accountId = $this->input->getInt('googlephotosaccountid');
        $isDryRun = $this->input->getBool('dryrun');

        /**
         * @var EventgalleryLibraryFactoryGooglephotosaccount $accountFactory
         */

        $accountFactory = EventgalleryLibraryFactoryGooglephotosaccount::getInstance();
        $account = $accountFactory->getGooglePhotosAccountById($accountId);

        $albums = GooglePhotos::getAlbums(COM_EVENTGALLERY_GOOGLE_PHOTOS_ALBUMS_CACHE_LIFETIME, $account->getClientId(), $account->getSecret(), $account->getRefreshToken(), $db);

        /**
         * @var EventgalleryModelGooglephotossync $model
         */
        $model = $this->getModel();
        $albumsAdded = 0;

        foreach($albums as $album) {

            if (!$model->eventExists($album->id)) {
                if (!$isDryRun) {
                    $model->addEvent($account->getId(), $album);
                }
                $albumsAdded++;
            }
        }

        if ($isDryRun) {
            $app->enqueueMessage(JText::sprintf('COM_EVENTGALLERY_GOOGLEPHOTOSSYNC_DRYRUN_DONE', $albumsAdded));
        }
        $app->enqueueMessage(JText::sprintf('COM_EVENTGALLERY_GOOGLEPHOTOSSYNC_DONE', $albumsAdded));

        $this->display();
	}

	public function cancel($key = NULL) {
		$this->setRedirect( 'index.php?option=com_eventgallery&view=events');
	}
}
