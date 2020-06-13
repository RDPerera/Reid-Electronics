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

require_once(__DIR__.'/../controller.php');

class EventgalleryControllerThumbnailgenerator extends JControllerForm
{


    /**
     * The root folder for the physical images
     *
     * @var string
     */

    protected $default_view = 'thumbnailgenerator';

    public function __construct($config = array())
    {

        parent::__construct($config);
    }

	public function getModel($name = 'Thumbnailgenerator', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * just cancels this view
     * @param null $key
     * @return bool|void
     */
	public function cancel($key = NULL) {
		$this->setRedirect( 'index.php?option=com_eventgallery&view=events');
	}

    /**
     * initializes the syncronization.
     *
     * @param bool $cachable
     * @param array $urlparams
     */
    public function init($cachable = false, $urlparams = array())
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        /**
         * @var EventgalleryModelSync $model
         */
        $model = $this->getModel();


        /**
         * @var EventgalleryLibraryFolder[] $existingfolders
         */
        $existingfolders = $model->getFolders();

        $result = [];

        foreach($existingfolders as $folder) {
            array_push($result, ['foldername' => $folder->getFolderName(), 'error'=>null, 'isNew'=>false, 'foldertype'=>$folder->getFolderType()->getId()]);
        }

        echo json_encode($result);
    }

    public function processfolder(/** @noinspection PhpUnusedParameterInspection */$cachable = false, $urlparams = array()) {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $folder = $this->input->getString('folder','');
        $refreshETagsStr = $this->input->getString('refreshetags', "true");
        $refreshETags = !strcasecmp($refreshETagsStr, "false") == 0;
        /**
         * @var EventgalleryLibraryFile[] $files
         */
        $files =  $this->getModel()->getFilesToSync($folder, $refreshETags);
        $filenames = [];
        forEach($files as  $file) {
            array_push($filenames, $file->getFileName());
        }

        $result = Array();
        $result['folder'] = htmlspecialchars($folder);
        $result['status'] = 'sync';
        $result['files'] = $filenames;

        echo json_encode($result);
    }

    public function processfile() {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $foldernames = $this->input->getString('folder','');
        $filenames = $this->input->getString('file','');

        if (!is_array($foldernames) || !is_array($filenames)) {
            echo '[]';
            return;
        }
        $result = [];
        for($i=0; $i<count($foldernames); $i++) {
            array_push($result, [
                'foldername'=>$foldernames[$i],
                'filename'=>$filenames[$i],
                'sizes' => $this->getModel()->createThumbnails($foldernames[$i], $filenames[$i])
            ]);
        }

        echo json_encode($result);
    }


}
