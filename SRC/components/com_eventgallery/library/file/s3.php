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
jimport('joomla.error.log');
require_once JPATH_ROOT.'/components/com_eventgallery/library/common/logger.php';

class EventgalleryLibraryFileS3 extends EventgalleryLibraryFile
{
    private $expriationTime = '+10 minutes';

    /**
     * creates the lineitem object. $dblineitem is the database object of this line item
     *
     * @param object $object
     * @throws Exception
     */
    function __construct($object)
    {
        parent::__construct($object);

        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();

        if ($this->_file->width <= 0) {
            $this->_file->width = 1000;
        }
        if ($this->_file->height <= 0) {
            $this->_file->height = 1000;
        }

    }

    public function getImageUrl($width=104,  $height=104, $fullsize, $larger=false) {
        $s3client = EventgalleryLibraryCommonS3client::getInstance();
        if ($fullsize) {
            return $s3client->getURL($s3client->getBucketForThumbnails(), $this->calculateS3Key(COM_EVENTGALLERY_IMAGE_ORIGINAL_MAX_WIDTH), true);
        } else {
            $width = $this->getSizeCode($width, $height);
            return $s3client->getURL($s3client->getBucketForThumbnails(), $this->calculateS3Key($width), true);
        }
    }

    public function getThumbUrl ($width=104, $height=104, $larger=true) {
        $s3client = EventgalleryLibraryCommonS3client::getInstance();
        $width = $this->getSizeCode($width, $height);
        return $s3client->getURL($s3client->getBucketForThumbnails(), $this->calculateS3Key($width), true);
    }

    public function getOriginalImageUrl() {

        return JUri::base().substr(JRoute::_('index.php?option=com_eventgallery&view=download&folder='.$this->getFolderName().'&file='.urlencode($this->getFileName()) ), strlen(JUri::base(true)) + 1);

    }

    public function getSharingImageUrl() {

        return JUri::base().substr(JRoute::_('index.php?option=com_eventgallery&is_for_sharing=true&view=download&folder='.$this->getFolderName().'&file='.urlencode($this->getFileName()) ), strlen(JUri::base(true)) + 1);

    }

    private function getSizeCode($width, $height) {
        $sizeSet = new EventgalleryHelpersSizeset();
        return $sizeSet->getSizeCode($width, $height, $this->getWidth(), $this->getHeight());
    }

    public function getETag() {
        return $this->_file->s3_etag;
    }

    /**
     * returns an array(s3key => etag)
     *
     * @return array
     */
    public function getETagForThumbnails() {
        return json_decode($this->_file->s3_etag_thumbnails, true);
    }

    /**
     * increases the hit counter in the database
     */
    public function countHit() {
        /**
         * @var EventgalleryTableFile $table
         */
        $table = JTable::getInstance('File', 'EventgalleryTable');
        $table->hit($this->_file->id);
    }

    public function syncFile() {
        $db = JFactory::getDbo();
        $s3client = EventgalleryLibraryCommonS3client::getInstance();
        $key = $this->getFolderName() . "/" . $this->getFileName();

        $config   = JFactory::getConfig();
        $tempFileName = tempnam($config->get('tmp_path'), 'eg');

        $s3file = $s3client->getObjectToFile($s3client->getBucketForOriginalImages(), $key, $tempFileName);

        $etag = $this->cleanETag($s3file['ETag']);

        EventgalleryLibraryFileLocal::updateMetadata($tempFileName, $this->getFolderName(),$this->getFileName());
        unset($s3file);
        gc_collect_cycles();
        if(is_file($tempFileName)) {
            //chown($tempFileName,666);
            unlink($tempFileName);
        }

        if ($etag != $this->getETag()) {
            // update the etag since it has changed.
            $query = $db->getQuery(true);
            $query->update('#__eventgallery_file')
                ->set('s3_etag=' . $db->quote($etag))
                ->set('s3_etag_thumbnails=""')
                ->where('id=' . $db->quote($this->getId()));
            $db->setQuery($query);
            $db->execute();
        }

        return EventgalleryLibraryManagerFolder::$SYNC_STATUS_SYNC;

    }

    public function createThumbnails() {
        $lambdaURL = $this->config->getStorage()->getS3ResizeAPIUrl();

        if (strlen($lambdaURL) > 0) {
            return $this->createThumbnailsRemote();
        } else {
            return $this->createThumbnailsLocal();
        }
    }

    /**
     * Create thumbnails using a remote API
     *
     *
     */
    public function createThumbnailsRemote() {

        $s3client = EventgalleryLibraryCommonS3client::getInstance();
        $sizeSet = new EventgalleryHelpersSizeset();
        $availableSizes = array_unique($sizeSet->availableSizes);

        $url = $this->config->getStorage()->getS3ResizeAPIUrl();
        $apiKey = $this->config->getStorage()->getS3ResizeAPIKey();

        $data = [
            "bucketOriginals"  => $s3client->getBucketForOriginalImages(),
            "bucketThumbnails"  => $s3client->getBucketForThumbnails(),
            "autorotate" => $this->config->getImage()->doAutoRotate(),
            "sharpImage" => $this->config->getImage()->doUseSharpening(),
            "sharpOriginalImage" => $this->config->getImage()->doUseSharpeningForOriginalSizes(),
            "doWatermark" => false,
            "watermark"  => [
                "src"  => "https://www.svenbluege.de/images/SvenBluege-Photography-Logo.png"
            ],
            "sizes" => $availableSizes,
            "folder" => $this->getFolderName(),
            "files" => [$this->getFileName()]
        ];

        $data_string = json_encode($data);


        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'x-api-key:' . $apiKey
                )
        );

        $result = curl_exec($ch);


        if ($result === false) {
            return ["ERROR" => curl_error($ch)];
        }

        curl_close($ch);

        $result = json_decode($result, true);

        $fileData = $result['original'][$this->getFileName()];
        $originalImageETag = $fileData['etag'];
        $width =  $fileData['size']['width'];
        $height =  $fileData['size']['height'];


        $thumbnailETags = $result['thumbnails'][$this->getFileName()];

        $this->saveETags($originalImageETag, $thumbnailETags);

        $exif = new \components\com_eventgallery\site\library\Data\Exif;
        if (isset($fileData['exif'])) {
            if (isset($fileData['exif']['exposuretime'])) { $exif->exposuretime = $fileData['exif']['exposuretime'];}
            if (isset($fileData['exif']['focallength'])) { $exif->focallength = $fileData['exif']['focallength'];}
            if (isset($fileData['exif']['fstop'])) { $exif->fstop = $fileData['exif']['fstop'];}
            if (isset($fileData['exif']['iso'])) { $exif->iso = $fileData['exif']['iso'];}
            if (isset($fileData['exif']['model'])) { $exif->model = $fileData['exif']['model'];}
        }

        EventgalleryLibraryFileLocal::storeMetadata($this->getFolderName(), $this->getFileName(), $width, $height, $exif->toJson(), "", false, false, null, null);

        return [$availableSizes];
    }

    /**
     * Create Thumbnails using the local server and upload them to S3
     *
     *
     * @return array
     */
    public function createThumbnailsLocal() {


        JLog::addLogger(
            array(
                'text_file' => 'com_eventgallery_s3.log.php',
                'logger' => 'Eventgalleryformattedtext'
            ),
            JLog::ALL,
            'com_eventgallery'
        );

        $time_start = microtime(true);

        $s3client = EventgalleryLibraryCommonS3client::getInstance();
        $sizeSet = new EventgalleryHelpersSizeset();
        $availableSizes = array_unique($sizeSet->availableSizes);
        $doWatermarking = true;
        $doSharping = true;

        $folderObject = $this->getFolder();
        $watermark = $folderObject->getWatermark();
        // load default watermark
        if (null == $watermark || !$watermark->isPublished()) {
            /**
             * @var EventgalleryLibraryFactoryWatermark $watermarkFactory
             * @var EventgalleryLibraryWatermark $watermark
             */
            $watermarkFactory = EventgalleryLibraryFactoryWatermark::getInstance();
            $watermark = $watermarkFactory->getDefaultWatermark();
        }

        $tempFileName = JPATH_CACHE . '/' . $this->getFileName();
        $s3file = $s3client->getObjectToFile($s3client->getBucketForOriginalImages(), $this->getFolderName() . "/" . $this->getFileName(), $tempFileName);

        $originalFileETag = $this->cleanETag($s3file['ETag']);



        $thumbnailETags = array();

        $config   = JFactory::getConfig();

        foreach($availableSizes as $size) {
            $imageProcessor = new EventgalleryLibraryCommonImageprocessor();
            $imageProcessor->loadImage($tempFileName);
            $image_thumb_file = tempnam($config->get('tmp_path'), 'eg');


            $imageProcessor->setTargetImageSize($size, -1);
            $imageProcessor->processImage(
                $this->config->getImage()->doAutoRotate(),
                $doWatermarking,
                $watermark,
                $doSharping && $this->config->getImage()->doUseSharpening(),
                $this->config->getImage()->doUseSharpeningForOriginalSizes(),
                $this->config->getImage()->getImageSharpenMatrix()
            );
            $imageProcessor->saveThumbnail($image_thumb_file, $this->config->getImage()->getImageQuality());
            $imageProcessor->copyICCProfile($tempFileName, $image_thumb_file);


            $key = $this->calculateS3Key($size);

            $thumbnail = $s3client->putObjectFile($s3client->getBucketForThumbnails(),
                $key,
                $image_thumb_file,
                EventgalleryLibraryCommonS3client::ACL_PUBLIC_READ
            );

            $thumbnailETags[$key] = $this->cleanETag($thumbnail['ETag']);

            // call this to clear the filenhandler. Otherwise we can't delete the temp file
            gc_collect_cycles();
            unlink($image_thumb_file);
        }

        EventgalleryLibraryFileLocal::updateMetadata($tempFileName, $this->getFolderName(),$this->getFileName());

        unset($s3file);
        gc_collect_cycles();
        unlink($tempFileName);

        $this->saveETags($originalFileETag, $thumbnailETags);

        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start);

        JLog::add('processing file '.$this->getFolderName(). '/'. $this->getFileName() . " in $execution_time s.", JLog::INFO, 'com_eventgallery');
        return [$availableSizes];
    }

    /**
     * remove double quoutes at the beginning and the end of an etag
     *
     * @param $etag
     * @return String
     */
    private function cleanETag($etag) {
        return str_replace("\"", "", $etag);
    }

    private function saveETags($originalFileETag, $thumbnailETags) {
        $db = JFactory::getDbo();
        // update the etag since it has changed.
        $query = $db->getQuery(true);
        $query->update('#__eventgallery_file')
            ->set('s3_etag='. $db->quote($originalFileETag))
            ->set('s3_etag_thumbnails='. $db->quote(json_encode($thumbnailETags)))
            ->where('id=' . $db->quote($this->getId()));
        $db->setQuery($query);
        $db->execute();
    }

    public function calculateS3Key($size) {
        return $this->getFolderName() . '/'. $this->calculateS3KeyPart($size);
    }


    private function calculateS3KeyPart($size) {
        return 's'. $size . '/' . $this->getFileName();
    }

    public function getOriginalFile()
    {
        $s3client = EventgalleryLibraryCommonS3client::getInstance();
        $tempFileName = JPATH_CACHE . '/' . $this->getFileName();
        $image_thumb_file = null;
        $s3client->getObjectToFile(
            $s3client->getBucketForOriginalImages(),
            $this->getFolderName() . "/" . $this->getFileName(),
            $tempFileName);

        $fileContent = file_get_contents($tempFileName);

        unlink($tempFileName);

        return $fileContent;
    }

}
