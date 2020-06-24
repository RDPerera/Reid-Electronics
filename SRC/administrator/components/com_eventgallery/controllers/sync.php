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

class EventgalleryControllerSync extends JControllerForm
{


    /**
     * The root folder for the physical images
     *
     * @var string
     */

    protected $default_view = 'sync';

    public function __construct($config = array())
    {

        parent::__construct($config);
    }

    public function getModel($name = 'Sync', $prefix = 'EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * just cancels this view
     * @param null $key
     * @return bool|void
     */
    public function cancel($key = NULL)
    {
        $this->setRedirect('index.php?option=com_eventgallery&view=events');
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

        $newFolders = $model->findNewFolders();
        /**
         * @var EventgalleryLibraryFolder[] $existingfolders
         */
        $existingfolders = $model->getFolders();

        $result = [];

        foreach($newFolders as $newFolder) {
            array_push($result, ['foldername' => $newFolder->foldername, 'error'=>$newFolder->error, 'isNew'=>true, 'foldertype'=>$newFolder->getFoldertype()]);
        }

        foreach($existingfolders as $folder) {
            array_push($result, ['foldername' => $folder->getFolderName(), 'error'=>null, 'isNew'=>false, 'foldertype'=>$folder->getFolderType()->getId()]);
        }

        echo json_encode($result);
    }


    /**
     * Syncs one folder
     *
     * @param bool $cachable
     * @param array $urlparams
     */
    public function processFolder(/** @noinspection PhpUnusedParameterInspection */
        $cachable = false, $urlparams = array())
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        $use_htacces_to_protect_original_files = $config->getImage()->doUseHtaccessToProtectOriginalFiles();

        $folder = $this->input->getString('folder', '');
        $foldertype = $this->input->getInt('foldertype', null);
        $syncResult = $this->getModel()->syncFolder($folder, $foldertype, $use_htacces_to_protect_original_files);

        $result = Array();
        $result['folder'] = htmlspecialchars($folder);
        $result['status'] = $syncResult['status'];
        $result['files'] = $syncResult['files'];

        echo json_encode($result);
    }

    public function processFiles()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $folders = $this->input->get('folder',array(), 'array');
        $files = $this->input->get('file', array(), 'array');

        $result = [];

        for($i=0;$i<count($folders);$i++) {
            array_push($result, $this->getModel()->syncFile(urldecode($folders[$i]), urldecode($files[$i])));
        }

        echo json_encode($result);
    }


}
