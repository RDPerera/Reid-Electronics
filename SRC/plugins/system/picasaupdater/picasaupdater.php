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
class plgSystemPicasaupdater extends JPlugin
{

public function __construct($subject, array $config = array())
{
    parent::__construct($subject, $config);
        parent::__construct($subject, $config);

        try {
            @include_once JPATH_ROOT . '/components/com_eventgallery/vendor/autoload.php';
            //load classes
            JLoader::registerPrefix('Eventgallery', JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_eventgallery');

            include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_eventgallery/version.php';

        }catch (Exception $e){

        }

}

    /**
	 * Method to catch the onAfterDispatch event.
	 *
	 * This is where we setup the click-through content highlighting for.
	 * The highlighting is done with JavaScript so we just
	 * need to check a few parameters and the JHtml behavior will do the rest.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   2.5
	 */
	public function onAfterDispatch()
	{
		/**
		* no need to run if we don't need to show any event gallery related stuff.
		* This plugin only kicks in if we are in the component and all the classes
		* are registered
		*/
		if (!class_exists('EventgalleryLibraryManagerFolder')) {
			return true;
		}

		if (JFactory::getDocument()->getType() != 'html' ) {
		    return true;
        }

		$db = JFactory::getDbo();

		/**
		* find empty picasa folders
		**/
 		$query = $db->getQuery(true)
                ->select('folder.*')
                ->from($db->quoteName('#__eventgallery_folder') . ' AS folder')
                ->join('LEFT', $db->quoteName('#__eventgallery_file') . ' AS file ON folder.folder = file.folder and file.published=1 and file.ismainimage=0')
                ->where('file.file IS NULL')
                ->where('(folder.foldertypeid=1 OR folder.foldertypeid=2 OR folder.foldertypeid=4)');


		$db->setQuery($query);
		$entries = $db->loadObjectList();

        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();

        // use the picasa reload magic to refresh empty albums.
        foreach ($entries as $entry)
        {
			/**
			 * @var EventgalleryLibraryFolderPicasa $folder
			 */
            $folder = $folderFactory->getFolder($entry->folder);
            // we need to call the method getAlbum in order to start the XML sync
            if (method_exists($folder, 'updateAlbum') ) {
            	$folder->updateAlbum();
            }

			if (method_exists($folder, 'updatePhotoSet') ) {
				$folder->updatePhotoSet();
			}
        }

        return true;
	}

}
