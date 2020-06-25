<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT.'/components/com_eventgallery/config.php';
require_once 'Resizeimage.php';

class EventgalleryControllerDownload extends EventgalleryControllerResizeimage
{

    const COM_EVENTGALLERY_LOGFILENAME = 'com_eventgallery_orderdownload.log.php';

    public function __construct($config = array())
    {
        parent::__construct($config);

        \JLog::addLogger(
            array(
                'text_file' => self::COM_EVENTGALLERY_LOGFILENAME,
                'logger' => 'Eventgalleryformattedtext'
            ),
            \JLog::ALL,
            'com_eventgallery_orderdownload'
        );

    }

    public function redirectToUrl($url) {
        /**
         * @var Joomla\CMS\Application\SiteApplication $app
         */
        $app = JFactory::getApplication();
        $app->redirect($url);
        $app->close();
    }

    public function display($cachable = false, $urlparams = array())
    {
        /**
         * @var JApplicationSite $app
         * @var \Joomla\Registry\Registry $registry
         */
        $app = JFactory::getApplication();
        $params = $app->getParams();
        $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance($params);

        $str_folder = $app->input->getString('folder', null);
        $str_file = $app->input->getString('file', null);
        $is_sharing_download = $app->input->getBool('is_for_sharing', false);

        /**
         * @var EventgalleryLibraryFactoryFile $fileFactory
         */
        $fileFactory = EventgalleryLibraryFactoryFile::getInstance();

        $file = $fileFactory->getFile($str_folder, $str_file);

        $allowDownloadOfOriginalImage = $file->getFolder()->doAllowDownloadOfOriginalImage($config);
        $allowDownloadAtAll = $file->getFolder()->doAllowDownloadAtAll($config);
        $redirectUrl = $config->getSocial()->getRedirectURL();

        if (!is_object($file) || !$file->isPublished()) {
            throw new Exception(JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NO_PUBLISHED_MESSAGE'), 404);
        }

        $folder = $file->getFolder();

        // Check of the user has the permission to grab the image
        if (!$folder->isPublished() || !$folder->isVisible() || !$folder->isAccessible()) {
            throw new Exception(JText::_('COM_EVENTGALLERY_EVENT_NO_PUBLISHED_MESSAGE'), 404);
        }

        // if this is a sharing URL and we don't allow the download until here,
        // we allow the download but disable the shared download.
        if ($is_sharing_download && !$allowDownloadAtAll) {
            $allowDownloadOfOriginalImage = false;
            $allowDownloadAtAll = true;
        }

        if (!$allowDownloadAtAll) {
            if (empty($redirectUrl)) {
                throw new Exception(JText::_('COM_EVENTGALLERY_EVENT_NO_DOWNLOAD_ALLOWED'), 403);
            }
            $this->redirectToUrl($redirectUrl);
        }

        // allow the download if at least one sharing type is enabled both global and for the event
        if (
                ($config->getSocial()->doUseFacebook() && $folder->getAttribs()->get('use_social_sharing_facebook', 1)==1)
            ||  ($config->getSocial()->doUseTwitter() && $folder->getAttribs()->get('use_social_sharing_twitter', 1)==1)
            ||  ($config->getSocial()->doUsePinterest() && $folder->getAttribs()->get('use_social_sharing_pinterest', 1)==1)
            ||  ($config->getSocial()->doUseEmail() && $folder->getAttribs()->get('use_social_sharing_email', 1)==1)
            ||  ($config->getSocial()->doUseDownload() && $folder->getAttribs()->get('use_social_sharing_download', 1)==1)

            ) {
        	// nothing to do there since the sharing options are fine.
        } else {
            $allowDownloadOfOriginalImage = false;
        }

        if ($file->getFolder()->getFolderType()->getId() == EventgalleryLibraryFolderS3::ID) {
            $this->downloadS3Image($allowDownloadOfOriginalImage, $is_sharing_download, $file);
        } else if ($file->getFolder()->getFolderType()->getId() == EventgalleryLibraryFolderGooglephotos::ID) {
            /**
             * @var EventgalleryLibraryFileGooglephotos $file
             */
            $app->redirect($file->getGoogleImageUrl($file->getWidth()));
        } else {
            $this->downloadLocalImage($allowDownloadOfOriginalImage, $is_sharing_download, $file);
        }

    }

    /**
     * @param $doProvideOriginalImage
     * @param $is_sharing_download
     * @param EventgalleryLibraryFile $file
     */
    private function downloadLocalImage($doProvideOriginalImage, $is_sharing_download, $file) {

        $basename = COM_EVENTGALLERY_IMAGE_FOLDER_PATH . $file->getFolderName() . '/';

        if ( $doProvideOriginalImage ) {

            // try the path to a possible original file
            $filename = $basename. COM_EVENTGALLERY_IMAGE_ORIGINAL_SUBFOLDER .'/'.$file->getFileName();

            if (!file_exists($filename)) {
                $filename = $basename . $file->getFileName();
            }

            $mime = ($mime = getimagesize($filename)) ? $mime['mime'] : $mime;
            $size = filesize($filename);
            $fp   = fopen($filename, "rb");
            if (!($mime && $size && $fp)) {
                // Error.
                return;
            }


            header("Content-type: " . $mime);
            header("Content-Length: " . $size);
            if (!$is_sharing_download) {
                header("Content-Disposition: attachment; filename=" . $file->getFileName());
            }
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            fpassthru($fp);
            fclose($fp);
            return $this->endExecution();
        } else {
            if (!$is_sharing_download) {
                header("Content-Disposition: attachment; filename=" . $file->getFileName());
            }
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            $this->renderThumbnail($file->getFolderName(), $file->getFileName(), COM_EVENTGALLERY_IMAGE_ORIGINAL_MAX_WIDTH);
            return $this->endExecution();
        }
    }

    /**
     * @param $doProvideOriginalImage
     * @param $is_sharing_download
     * @param EventgalleryLibraryFileS3 $file
     */
    private function downloadS3Image($doProvideOriginalImage, $is_sharing_download, $file) {

        $s3client = EventgalleryLibraryCommonS3client::getInstance();

        $config   = JFactory::getConfig();
        $tempFileName =  tempnam($config->get('tmp_path'), 'eg');

        if ( $doProvideOriginalImage ) {
            $s3client->getObjectToFile(
                $s3client->getBucketForOriginalImages(),
                $file->getFolderName() . "/" . $file->getFileName(),
                $tempFileName
            );
        } else {
            $s3client->getObjectToFile(
                $s3client->getBucketForThumbnails(),
                $file->calculateS3Key(COM_EVENTGALLERY_IMAGE_ORIGINAL_MAX_WIDTH),
                $tempFileName
            );
        }

        $mime = ($mime = getimagesize($tempFileName)) ? $mime['mime'] : $mime;
        $size = filesize($tempFileName);
        $fp   = fopen($tempFileName, "rb");
        if (!($mime && $size && $fp)) {
            // Error.
            return $this->endExecution();
        }


        header("Content-type: " . $mime);
        header("Content-Length: " . $size);
        if (!$is_sharing_download) {
            header("Content-Disposition: attachment; filename=" . $file->getFileName());
        }
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        if (!$is_sharing_download) {
            header("Content-Disposition: attachment; filename=" . $file->getFileName());
        }
        fpassthru($fp);
        fclose($fp);

        unlink($tempFileName);
        return $this->endExecution();
    }

    /**
     * allow to download small thumbnails in case a user did a order
     *
     * @throws Exception
     */
    public function mailthumb() {
        $app = JFactory::getApplication();

        $str_orderid = $this->input->getString('orderid', null);
        $str_lineitemid = $this->input->getString('lineitemid', null);
        $str_token = $this->input->getString('token', null);


        /**
         * @var EventgalleryLibraryFactoryOrder $orderFactory
         */
        $orderFactory = EventgalleryLibraryFactoryOrder::getInstance();
        $order = $orderFactory->getOrderById($str_orderid);
        if ($order == null) {
            throw new Exception("Invalid Request.");
        }

        if ($order->getToken() != $str_token) {
            throw new Exception("Invalid Request.");
        }

        $lineitem = $order->getLineItem($str_lineitemid);
        if ($lineitem == null) {
            throw new Exception("Invalid Request.");
        }

        if (strcmp($str_token, $order->getToken())!=0) {
            throw new Exception("Invalid Request.");
        }

        $file = $lineitem->getFile();

        if ($file->getFolder()->getFolderType()->getId() == EventgalleryLibraryFolderLocal::ID) {
            $this->renderThumbnail($file->getFolderName(), $file->getFileName(), 104);
            return $this->endExecution();
        } else if ($file->getFolder()->getFolderType()->getId() == EventgalleryLibraryFolderGooglephotos::ID) {
            /**
             * @var EventgalleryLibraryFileGooglephotos $file
             */
            $app->redirect($file->getGoogleImageUrl(104));
        }else {
            $app->redirect($file->getThumbUrl(104, 104));
        }

    }

    /**
     * This method is used to enable the download of files.
     *
     * @throws Exception
     */
    public function order() {

        $app = JFactory::getApplication();

        $str_orderid = $this->input->getString('orderid', null);
        $str_lineitemid = $this->input->getString('lineitemid', null);
        $str_token = $this->input->getString('token', null);


        /**
         * @var EventgalleryLibraryFactoryOrder $orderFactory
         */
        $orderFactory = EventgalleryLibraryFactoryOrder::getInstance();
        $order = $orderFactory->getOrderById($str_orderid);
        if ($order == null) {
            throw new Exception("Invalid Request.");
        }

        if ($order->getToken() != $str_token) {
            throw new Exception("Invalid Request.");
        }

        $lineitem = $order->getLineItem($str_lineitemid);
        if ($lineitem == null) {
            throw new Exception("Invalid Request.");
        }

        if (strcmp($str_token, $order->getToken())!=0) {
            throw new Exception("Invalid Request.");
        }

        $file = $lineitem->getFile();

        if ($file->getFolder()->getFolderType()->getId() == EventgalleryLibraryFolderLocal::ID) {
            $this->handleLocalOrderDownload($file, $order, $lineitem);
        } elseif ($file->getFolder()->getFolderType()->getId() == EventgalleryLibraryFolderS3::ID) {
            $this->handleS3OrderDownload($file, $order, $lineitem);
        } else {
            $app->redirect($file->getOriginalImageUrl());
        }

    }

    /**
     * Allows to download the order as a zip file. It simply calls the order links for each image and adds the result to the zip file
     * Why? Because the order link will handle the resizing and we don't need to do it twice.
     *
     * @throws Exception
     */
    public function zip() {
        $str_orderid = $this->input->getString('orderid', null);
        $str_token = $this->input->getString('token', null);


        /**
         * @var EventgalleryLibraryFactoryOrder $orderFactory
         */
        $orderFactory = EventgalleryLibraryFactoryOrder::getInstance();
        $order = $orderFactory->getOrderById($str_orderid);
        if ($order == null) {
            throw new Exception("Invalid Request.");
        }

        if ($order->getToken() != $str_token) {
            throw new Exception("Invalid Request.");
        }


        $zip = new ZipArchive();
        $config   = JFactory::getConfig();

        $tmpZipFilename = tempnam($config->get('tmp_path'), 'eg');


        if ($zip->open($tmpZipFilename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$tmpZipFilename>\n");
        }

        foreach($order->getLineItems() as $lineitem) {


            /**
             * @var EventgalleryLibraryImagelineitem $lineitem
             */

            if ($lineitem->getImageType()->isDigital()) {
                $file = $lineitem->getFile();

                $context = stream_context_create(
                    array(
                        'http' => array(
                            'follow_location' => true
                        )
                    )
                );

                $url = str_replace("/administrator", "", JRoute::_("index.php?option=com_eventgallery&view=download&task=order&orderid=" . $order->getId() . "&lineitemid=" . $lineitem->getId() . "&token=" . $order->getToken(), false, -1));
                $download_file = false;
                $http_response_header = null;

                try{
                    $http = \JHttpFactory::getHttp();
                    $http->setOption('follow_location', true);
                    $response = $http->get($url);
                    $download_file = $response->body;
                    $http_response_header = $response->headers;

                } catch (\Exception $e) {
                    \JLog::add('error while getting image from url ' . $url . ' Error message: ' . $e->getMessage(), \JLog::INFO, 'com_eventgallery_orderdownload');
                }

                if ($download_file !== false) {

                    $fileuri = $file->getFolderName() . '/' . $file->getFileName();

                    if (in_array($file->getFolder()->getFolderType()->getId(), [EventgalleryLibraryFolderPicasa::ID, EventgalleryLibraryFolderGooglephotos::ID])) {
                        if (in_array('Content-Type: image/jpeg', $http_response_header)) {
                            $fileuri .= '.jpg';
                        }
                    }

                    $zip->addFromString($fileuri, $download_file);
                }
                unset($download_file);

            }

        }

        $zip->close();



        $size = filesize($tmpZipFilename);
        $fp   = fopen($tmpZipFilename, "rb");
        if (!($size && $fp)) {
            echo "Can't read zip file.";
            return $this->endExecution();
        }

        ob_clean();
        ob_end_flush();

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $order->getDocumentNumber(). '.zip');
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$size);

        fpassthru($fp);
        fclose($fp);
        unlink($tmpZipFilename);
        return $this->endExecution();

    }


    /**
     * @param EventgalleryLibraryFile $file
     * @param EventgalleryLibraryOrder $order
     * @param EventgalleryLibraryImagelineitem $lineitem
     */
    private function handleS3OrderDownload($file, $order, $lineitem) {
        $s3client = EventgalleryLibraryCommonS3client::getInstance();
        $tempFileName = JPATH_CACHE . '/' . $file->getFileName();
        $image_thumb_file = null;
        $s3client->getObjectToFile(
            $s3client->getBucketForOriginalImages(),
            $file->getFolderName() . "/" . $file->getFileName(),
            $tempFileName);

        $imageSize = intval($lineitem->getImageType()->getSize());
        if (is_int($imageSize) && $imageSize>0 ) {
            $imageProcessor = new EventgalleryLibraryCommonImageprocessor();
            $imageProcessor->loadImage($tempFileName);

            $config   = JFactory::getConfig();
            $image_thumb_file = tempnam($config->get('tmp_path'), 'eg');

            $imageProcessor->setTargetImageSize($imageSize, -1, false);
            $imageProcessor->processImage(
                false,
                false,
                null,
                false,
                false,
                null
            );
            $imageProcessor->saveThumbnail($image_thumb_file, 90);
            $imageProcessor->copyICCProfile($tempFileName, $image_thumb_file);
            $filename = $image_thumb_file;
        } else {
            $filename = $tempFileName;
        }

        header("Content-Disposition: attachment; filename=" . $order->getDocumentNumber(). '-' . $lineitem->getId() . '-' . $file->getFileName());
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        $mime = ($mime = getimagesize($filename)) ? $mime['mime'] : $mime;
        $size = filesize($filename);
        $fp   = fopen($filename, "rb");
        if (!($mime && $size && $fp)) {
            // Error.
            return $this->endExecution();
        }

        header("Content-type: " . $mime);
        header("Content-Length: " . $size);
        fpassthru($fp);
        fclose($fp);

        if ($image_thumb_file != null) {
            unlink($image_thumb_file);
        }

        unlink($tempFileName);
        return $this->endExecution();
    }

    /**
     * @param EventgalleryLibraryFile $file
     * @param EventgalleryLibraryOrder $order
     * @param EventgalleryLibraryImagelineitem $lineitem
     * @throws Exception
     */
    private function handleLocalOrderDownload($file, $order, $lineitem) {

        $imageSize = intval($lineitem->getImageType()->getSize());

        header("Content-Disposition: attachment; filename=" . $order->getDocumentNumber(). '-' . $lineitem->getId() . '-' . $file->getFileName());
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        if (is_int($imageSize) && $imageSize>0 ) {
            $this->renderThumbnail($file->getFolderName(), $file->getFileName(), $imageSize , false, false, false, false);
            return $this->endExecution();
        }

        $fileContent = $file->getOriginalFile();

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($fileContent);

        header("Content-type: " . $mimeType);
        header("Content-Length: " .strlen($fileContent));
        echo $fileContent;

        return $this->endExecution();
    }



}
