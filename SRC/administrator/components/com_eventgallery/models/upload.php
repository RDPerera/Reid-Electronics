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

jimport( 'joomla.application.component.model' );
jimport('joomla.html.pagination');
require_once('files.php');

class EventgalleryModelUpload extends EventgalleryModelFiles
{
    /**
     * @param string $folderStr
     * @throws Exception
     */
  public function upload($folderStr) {
      $user = JFactory::getUser();

      $canUpload = $user->authorise('core.edit','com_eventgallery');
      if (!$canUpload) {
          echo JText::_('COM_EVENTGALLERY_EVENT_UPLOAD_NO_PERMISSION');
          die();
      }

      $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
      $use_htacces_to_protect_original_files = $config->getImage()->doUseHtaccessToProtectOriginalFiles();

      @mkdir(COM_EVENTGALLERY_IMAGE_FOLDER_PATH);
      EventgalleryLibraryCommonSecurity::protectFolder(COM_EVENTGALLERY_IMAGE_FOLDER_PATH);
      EventgalleryLibraryCommonSecurity::writeIndexHtmlFile(COM_EVENTGALLERY_IMAGE_FOLDER_PATH);

      $folder=JFolder::makeSafe($folderStr);

      /**
       * @var EventgalleryLibraryFactoryFolder $folderFactory
       */
      $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
      $folderObj = $folderFactory->getFolder($folder);
      if ($folderObj == null) {
          throw new Exception("Folder does not exist");
      }

      $path=COM_EVENTGALLERY_IMAGE_FOLDER_PATH.$folder.DIRECTORY_SEPARATOR ;
      @mkdir($path);

      EventgalleryLibraryCommonSecurity::writeIndexHtmlFile($path);

      if ($use_htacces_to_protect_original_files) {
          EventgalleryLibraryCommonSecurity::protectFolder($path);
      }

      $filename = basename($_FILES["file"]["name"]);
      $filename=JFile::makeSafe($filename);

      $allowedExtensions = Array('jpg', 'gif', 'png', 'jpeg');

      if (!in_array(strtolower( pathinfo ( $filename , PATHINFO_EXTENSION) ), $allowedExtensions) ) {
          echo "Unsupported file extension";
          die();
      }

      $uploadedFiles = Array();

      if(!move_uploaded_file($_FILES["file"]["tmp_name"], $path. $filename)){
          echo $filename . 'failed';
          die();
      }

      array_push($uploadedFiles, $filename);

      $db = JFactory::getDbo();

      if (file_exists($path.$filename)) {

          $query = $db->getQuery(True)
              ->select('count(1)')
              ->from($db->quoteName('#__eventgallery_file'))
              ->where('folder=' . $db->quote($folder))
              ->where('file=' . $db->quote($filename));
          $db->setQuery($query);
          if ($db->loadResult() == 0) {
              $query = $db->getQuery(true)
                  ->insert($db->quoteName('#__eventgallery_file'))
                  ->columns('folder,file,userid,created,modified,ordering')
                  ->values(
                      $db->quote($folder).','.
                      $db->quote($filename).','.
                      $db->quote($user->id).','.
                      'now(),now(),0');
          }else{
              $query = $db->getQuery(true)
                  ->update($db->quoteName('#__eventgallery_file'))
                  ->set('userid='.$db->quote($user->id))
                  ->set('created=now()')
                  ->set('modified=now()')

                  ->where('folder='.$db->quote($folder))
                  ->where('file='.$db->quote($filename));
          }

          $db->setQuery($query);
          $db->execute();
          EventgalleryLibraryFileLocal::updateMetadata($path.$filename, $folder, $filename);

          /**
           * @var EventgalleryLibraryFactoryFile $fileFactory
           */
          $fileFactory = EventgalleryLibraryFactoryFile::getInstance();
          $file = $fileFactory->getFile($folder, $filename);
          echo $filename . ' done. <img alt="Done '.$filename.'" class="thumbnail" src="'.$file->getThumbUrl(240).'" />';
      }
  }

}
