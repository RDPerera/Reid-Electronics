<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.40F
	* Creation date: Avril 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class GMapFPsControllerDropFiles extends GMapFPsController
{

    protected $allowed_ext = array('jpg','jpeg','png','gif');

    public function upload_image(){
		// Check for request forgeries
		if (!JSession::checkToken('request')) {
 			$this->exit_upload(false, JText::_( 'JINVALID_TOKEN'));
		}
		
		$lang = JFactory::getLanguage();
		$lang->load('com_media');
		
		$app 	= JFactory::getApplication(); 
		$config = JComponentHelper::getParams('com_gmapfp');
		$params = JComponentHelper::getParams('com_media');

        $type_image = array(".gif",".jpg",".jpeg",".png",".bmp"); 
		$loaderror = false;
		
		$file = $this->input->files->get('image1', '', 'array');

        $ext = strrchr($file['name'],'.');
        $ext = strtolower($ext);
        if (!in_array( $ext, $type_image )) 
        {
 			$this->exit_upload(false, JText::_( 'GMAPFP_BAD_EXT'));
        }

		if (!$this->authoriseUser('create'))
		{
 			$this->exit_upload(false, JText::_('JLIB_APPLICATION_ERROR_CREATE_NOT_PERMITTED'));
		}

		// Total length of post back data in bytes.
		$contentLength = (int) $_SERVER['CONTENT_LENGTH'];

		// Instantiate the media helper
		$mediaHelper = new JHelperMedia;

		// Maximum allowed size of post back data in MB.
		$postMaxSize = $mediaHelper->toBytes(ini_get('post_max_size'));

		// Maximum allowed size of script execution in MB.
		$memoryLimit = $mediaHelper->toBytes(ini_get('memory_limit'));

		// Check for the total size of post back data.
		if (($postMaxSize > 0 && $contentLength > $postMaxSize)
			|| ($memoryLimit != -1 && $contentLength > $memoryLimit))
		{
 			$this->exit_upload(false, JText::_( 'COM_MEDIA_ERROR_WARNUPLOADTOOLARGE'));
		}

		$uploadMaxSize = $params->get('upload_maxsize', 0) * 1024 * 1024;
		$uploadMaxFileSize = $mediaHelper->toBytes(ini_get('upload_max_filesize'));

		$file['name']     = JFile::makeSafe($file['name']);
        $file['name'] = str_replace(" ","_",$file['name']);
		if (($file['error'] == 1)
			|| ($uploadMaxSize > 0 && $file['size'] > $uploadMaxSize)
			|| ($uploadMaxFileSize > 0 && $file['size'] > $uploadMaxFileSize))
		{
			// File size exceed either 'upload_max_filesize' or 'upload_maxsize'.
 			$this->exit_upload(false, JText::_( 'COM_MEDIA_ERROR_WARNFILETOOLARGE'));
		}
		if (!isset($file['name']))
		{
			// No filename (after the name was cleaned by JFile::makeSafe)
 			$this->exit_upload(false, JText::_( 'COM_MEDIA_INVALID_REQUEST'));
		}

		if ((substr($config->get('gmapfp_chemin_img'), 0, 1) != '/') and (substr($config->get('gmapfp_chemin_img'), 0, 1) != '\\'))
			$config->set('gmapfp_chemin_img', '/'.$config->get('gmapfp_chemin_img'));
		
		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');
		JPluginHelper::importPlugin('content');

		if (!$mediaHelper->canUpload($file, 'com_media'))
		{
			// The file can't be uploaded
			$msg = $app->getMessageQueue();
			$this->exit_upload(false, $msg[0]);
		}

        if (strlen($file['tmp_name']) > 0 and $file['name'] != "none"){	
			//si je n'ai pas déjà ce fichier, je le copi		
			if (!JFile::upload($file['tmp_name'], JPATH_SITE.$config->get('gmapfp_chemin_img').strtolower($file['name'])))
			{
				// Error in upload
				$msg = JText::_( 'GMAPFP_UPLOAD_NOK').' => '.strtolower($file['name']).JText::_( 'GMAPFP_EXIST');
				$this->exit_upload(false,$msg);
			} else {
				$this->exit_upload(true,strtolower($file['name']));				
			}
		}
 		$this->exit_upload(false,'Nom de fichier non défini');
   }

	function exit_upload($status,$datas='') {
		$response = array('response'=>$status,'datas'=>$datas);            
		echo json_encode($response);
		JFactory::getApplication()->close();
	}

	protected function authoriseUser($action)
	{
		if (!JFactory::getUser()->authorise('core.' . strtolower($action), 'com_media'))
		{
			// User is not authorised
			return false;
		}

		return true;
	}
}
?>
