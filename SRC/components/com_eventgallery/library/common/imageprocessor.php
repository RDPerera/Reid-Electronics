<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
use lsolesen\pel\Pel;
use lsolesen\pel\PelDataWindow;
use lsolesen\pel\PelException;
use lsolesen\pel\PelExif;
use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelTag;

defined('_JEXEC') or die();

require_once JPATH_ROOT . '/components/com_eventgallery/helpers/vendors/class.jpeg_icc.php';



/**
 * provides the ability to create thumbnails of an image file
 *
 * Class EventgalleryLibraryCommonImageprocessor
 */
class EventgalleryLibraryCommonImageprocessor
{
    private static $jpeg_orientation_translation = Array(
        0 => 0,
        1 => 0,
        2 => 0,
        3 => 180,
        4 => 0,
        5 => 0,
        6 => -90,
        7 => 0,
        8 => 90
    );

    /**
     * @var PelJpeg
     */
    private $input_jpeg;
    /**
     * @var PelExif
     */
    private $exif;
    private $im_original;
    private $im_thumbnail;
    private $width;
    private $height;
    private $orig_width;
    private $orig_height;
    private $orig_ratio;

    /**
     * loads the image data from the given image file
     *
     * @param $filename
     */
    public function loadImage($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!file_exists($filename)) {
            throw new Exception(JText::_("COM_EVENTGALLERY_EVENT_FILE_NOT_FOUND"), 404);
        }

        if (strtolower($ext) == "gif") {
            if (!$im_original = imagecreatefromgif($filename)) {
                throw new Exception(JText::_("COM_EVENTGALLERY_EVENT_FILE_NOT_FOUND"), 404);
            }
        } else if(strtolower($ext) == "jpg" || strtolower($ext) == "jpeg") {

            // try to use PEL first. If things fail, use the php internal method to get the JPEG
            try {
                $this->input_jpeg = new PelJpeg($filename);

                /* Retrieve the original Exif data in $jpeg (if any). */
                $this->exif = $this->input_jpeg->getExif();


                /* The input image is already loaded, so we can reuse the bytes stored
                 * in $input_jpeg when creating the Image resource. */
                if (!$im_original = imagecreatefromstring($this->input_jpeg->getBytes())) {
                    throw new Exception(JText::_("COM_EVENTGALLERY_EVENT_FILE_NOT_FOUND"), 404);
                }
            } catch (Exception $e){
                if (!$im_original = imagecreatefromjpeg($filename)) {
                    throw new Exception(JText::_("COM_EVENTGALLERY_EVENT_FILE_NOT_FOUND"), 404);
                }
            }

        } else if(strtolower($ext) == "png") {
            if (!$im_original = imagecreatefrompng($filename)) {
                throw new Exception(JText::_("COM_EVENTGALLERY_EVENT_FILE_NOT_FOUND"), 404);
            }
        } else {
            throw new Exception(JText::_("COM_EVENTGALLERY_EVENT_FILEEXTENSION_NOT_SUPPORTED"));
        }



        $this->im_original = $im_original;


        $this->orig_width = imagesx($this->im_original);
        $this->orig_height = imagesy($this->im_original);
        $this->orig_ratio = $this->orig_width / $this->orig_height;

    }

    /**
     * initializes the size calculation
     * throws an exception if the image was not loaded before.
     *
     * @param int $width
     * @param int $height
     * @throws Exception
     */
    public function setTargetImageSize($width = -1, $height = -1, $doFindMatingSize = true) {

        if ($this->im_original == null) {
            throw new Exception('Can\'t set the target image size. The image needs to be loaded first');
        }

        if ($height > $width) {
            $width = $height;
        }

        $sizeCalc = new EventgalleryHelpersSizecalculator($this->orig_width, $this->orig_height, (int)$width, $doFindMatingSize);
        $this->height = $sizeCalc->getHeight();
        $this->width = $sizeCalc->getWidth();

        //adjust height to not enlarge images
        if ($this->width > $this->orig_width) {
            $this->width = $this->orig_width;
        }

        if ($this->height > $this->orig_height) {
            $this->height = $this->orig_height;
        }

        $canvasWidth = $this->width;
        $canvasHeight = ceil($this->width / $this->orig_ratio);

        if ($canvasHeight > $this->height) {
            $canvasHeight = $this->height;
            $canvasWidth = ceil($this->height * $this->orig_ratio);
        }

        $this->width = $canvasWidth;
        $this->height = $canvasHeight;


    }

    /**
     * Performs the image transformations
     *  - resizing
     *  - rotation
     *  - sharping
     *  - watermarking
     *
     * @param $doAutorotate
     * @param $doWatermarking
     * @param $watermark
     * @param $doSharping
     * @param $doSharpingForOriginalImage
     * @param $sharpenMatrix
     */
    public function processImage($doAutorotate, $doWatermarking, $watermark, $doSharping, $doSharpingForOriginalImage, $sharpenMatrix)
    {
        $this->resize();

        if ($doAutorotate == true) {
            $this->doAutoRotate();
        }

        // do sharpen the image if it's allowed and we're not dealing with an original sized image where we don't allow sharping.
        if ($doSharping && !($this->isOriginalSize() && !$doSharpingForOriginalImage)) {
            $this->addSharpening($sharpenMatrix);
        }

        if ($doWatermarking) {
            $this->addWatermark($watermark);
        }
    }

    /**
     * adds a watermark to the current image
     *
     * @param $watermark EventgalleryLibraryWatermark
     */
    private function addWatermark($watermark) {
        if (null != $watermark && $watermark->isPublished()) {
            $watermark->addWatermark($this->im_thumbnail);
        }
    }

    /**
     * Applies the sharpening matrix to the image
     *
     * @param String $sharpeningMatrix a JSON string containing the 3x3 matrix
     */
    private function addSharpening($sharpeningMatrix) {

        // configure the sharpening
        $stringSharpenMatrix = $sharpeningMatrix;

        $sharpenMatrix = json_decode($stringSharpenMatrix);
        if (null == $sharpenMatrix || count($sharpenMatrix)!=3) {
            $sharpenMatrix = array(
                array(-1,-1,-1),
                array(-1,16,-1),
                array(-1,-1,-1)
            );
        }

        $divisor = array_sum(array_map('array_sum', $sharpenMatrix));
        $offset = 0;

        if (function_exists('imageconvolution'))
        {
            imageconvolution($this->im_thumbnail, $sharpenMatrix, $divisor, $offset);

        }
    }

    /**
     * performs the auto rotation of an image based in the exif orientation data.
     *
     * @throws PelException
     */
    private function doAutoRotate() {
        if ($this->exif == null) {
            return;
        }

        $tiff = $this->exif->getTiff();
        $ifd0 = $tiff->getIfd();
        $orientation = $ifd0->getEntry(PelTag::ORIENTATION);
        if ($orientation != null) {
            $degree = self::$jpeg_orientation_translation[$orientation->getValue()];
            if ($degree !== 0) {
                $this->im_thumbnail = imagerotate($this->im_thumbnail, $degree, 0);
                $orientation->setValue(1);
            }
        }
    }

    /**
     * performs the resizing of the original image and creates the thumbnail image.
     */
    private function resize() {

        if ($this->isOriginalSize()) {
            $this->im_thumbnail = $this->im_original;
            return;
        }

        $im_output = imagecreatetruecolor($this->width, $this->height);

        // set background to white
        $white = imagecolorallocate($im_output, 255, 255, 255);
        imagefill($im_output, 0, 0, $white);

        $resize_faktor = $this->orig_height / $this->height;
        $new_height = $this->height;
        $new_width = $this->orig_width / $resize_faktor;

        if ($new_width < $this->width) {
            $resize_faktor = $this->orig_width / $this->width;
            $new_width = $this->width;
            $new_height = $this->orig_height / $resize_faktor;
        }

        imagecopyresampled($im_output, $this->im_original,
            ($this->width/2)-($new_width/2),
            ($this->height/2)-($new_height/2),
            0,0,
            $new_width,$new_height,$this->orig_width,$this->orig_height);


        $this->im_thumbnail = $im_output;
    }

    /**
     * saves a thumbnail as JPEG file to the disk
     *
     * @param string $filename
     * @param int $image_quality the jpeg quality setting (0-100)
     */
    public function saveThumbnail($filename, $image_quality) {

        $dir = dirname($filename);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        EventgalleryLibraryCommonSecurity::protectFolder(COM_EVENTGALLERY_IMAGE_CACHE_PATH);

        if ($this->input_jpeg != null) {
            Pel::setJPEGQuality($image_quality);
            /* We want the raw JPEG data from $scaled. Luckily, one can create a
             * PelJpeg object from an image resource directly: */
            /** @noinspection PhpParamsInspection */
            $output_jpeg = new PelJpeg($this->im_thumbnail);


            /* If no Exif data was present, then $exif is null. */
            if ($this->exif != null) {
                $empty_image = imagecreate(1, 1);
                $d = new PelDataWindow($empty_image);
                $idf = $this->exif->getTiff()->getIfd();
                do {
                    if (strlen($idf->getThumbnailData()) > 0) {
                        $idf->setThumbnail($d);
                    }
                    $idf = $idf->getNextIfd();
                } while($idf != null);

                $output_jpeg->setExif($this->exif);
                unset($empty_image);
                unset($d);
            }

            /* We can now save the scaled image. */
            $writeSuccess = true;
            $output_jpeg->saveFile($filename);
        } else {
            $writeSuccess = imagejpeg($this->im_thumbnail, $filename, $image_quality);
        }

        if (!$writeSuccess) {
            throw new Exception(JText::_("COM_EVENTGALLERY_EVENT_CACHEFILE_NOT_FOUND"));
        }

        $time = time() + 315360000;
        touch($filename, $time);
    }

    /**
     * copys the ICC profile of the source file to the target file.
     *
     * @param $sourceFileName
     * @param $targetFileName
     */
    public function copyICCProfile($sourceFileName, $targetFileName) {
        // add the ICC profile
        try {
            $o = new JPEG_ICC();
            $o->LoadFromJPEG($sourceFileName);
            $o->SaveToJPEG($targetFileName);
        } catch (Exception $e) {

        }
    }

    /**
     * checks if the desired thumbnail size equals the original image size
     *
     * @return bool
     */
    private function isOriginalSize() {
        $isOriginalSize = false;
        if ($this->height == $this->orig_height && $this->width == $this->orig_width) {
            $isOriginalSize = true;
        }

        return $isOriginalSize;
    }

    /**
     * removes possible harmful 'directory' characters like /\. in a string.
     *
     * @param $value
     * @return String
     */
    private static function cleanValue($value) {
        $value = str_replace("\.\.", "", $value);
        $value = str_replace("/", "", $value);
        $value = str_replace("\\", "", $value);
        return $value;
    }

    public static function calculateCacheThumbnailName($width, $doFindMatingSize, $filename, $foldername, $isMainImage) {

        $file = self::cleanValue($filename);
        $folder = self::cleanValue($foldername);
        $width = self::cleanValue((int)$width);
        $isMainImage = self::cleanValue((boolean)$isMainImage);

        $sizeSet = new EventgalleryHelpersSizeset();
        if ($doFindMatingSize) {
            $saveAsSize = $sizeSet->getMatchingSize($width);
        } else {
            $saveAsSize = $width;
        }

        $cachebasedir = COM_EVENTGALLERY_IMAGE_CACHE_PATH;
        $cachedir_thumbs = $cachebasedir . $folder;

        /**
         * nocrop is only for legacy reasons!
         */
        $image_thumb_file = $cachedir_thumbs . DIRECTORY_SEPARATOR . ($isMainImage ? 'mainimage_':'') . "nocrop" . $saveAsSize . $file;

        return $image_thumb_file;
    }

    /**
     * This method calculates the image
     *
     * @param $folder
     * @param $file
     * @param $width
     * @param $height
     * @param $doFindMatingSize defined if we try to find a size in the list of possible images sized
     * @param $doWatermarking
     * @param $doSharping
     *
     * @return the name of the thumbnail file.
     *
     */
    public static function createThumbnail($folder, $file, $width = -1, $doFindMatingSize = true, $doWatermarking = true, $doSharping = true) {


        $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();

        /**
         * @var EventgalleryLibraryFactoryFile $fileFactory
         */
        $fileFactory = EventgalleryLibraryFactoryFile::getInstance();
        $fileObject = $fileFactory->getFile($folder, $file);


        $image_thumb_file = EventgalleryLibraryCommonImageprocessor::calculateCacheThumbnailName($width, $doFindMatingSize, $file, $folder, $fileObject->isMainImage());


        $debug = false;

        if ($debug || !file_exists($image_thumb_file)) {

            $folderObject = $fileObject->getFolder();
            $watermark = $folderObject->getWatermark();

            if ($fileObject->isMainImage()) {
                $doWatermarking = $doWatermarking && $config->getImage()->doUseWatermarkForMainImages();
            }

            // load default watermark
            if (null == $watermark || !$watermark->isPublished()) {
                /**
                 * @var EventgalleryLibraryFactoryWatermark $watermarkFactory
                 * @var EventgalleryLibraryWatermark $watermark
                 */
                $watermarkFactory = EventgalleryLibraryFactoryWatermark::getInstance();
                $watermark = $watermarkFactory->getDefaultWatermark();
            }

            $image_file = COM_EVENTGALLERY_IMAGE_FOLDER_PATH . $folder . DIRECTORY_SEPARATOR . $file;

            $imageProcessor = new EventgalleryLibraryCommonImageprocessor();
            $imageProcessor->loadImage($image_file);

            $imageProcessor->setTargetImageSize($width, -1, $doFindMatingSize);
            $imageProcessor->processImage(
                $config->getImage()->doAutoRotate(),
                $doWatermarking,
                $watermark,
                $doSharping && $config->getImage()->doUseSharpening(),
                $config->getImage()->doUseSharpeningForOriginalSizes(),
                $config->getImage()->getImageSharpenMatrix()
            );
            $imageProcessor->saveThumbnail($image_thumb_file, $config->getImage()->getImageQuality());
            $imageProcessor->copyICCProfile($image_file, $image_thumb_file);

            Pel::clearExceptions();
        }

        gc_collect_cycles();

        return $image_thumb_file;
    }

    /**
     * This method calculates the image and delivers it to the client.
     *
     * @param $folder
     * @param $file
     * @param $width
     * @param $height
     * @param $doFindMatingSize defined if we try to find a size in the list of possible images sized
     * @param $doCache
     * @param $doWatermarking
     * @param $doSharping
     *
     */
    public static function renderThumbnail($folder, $file, $width = -1, $doFindMatingSize = true, $doCache = true, $doWatermarking = true, $doSharping = true) {

        $image_thumb_file = self::createThumbnail($folder, $file, $width, $doFindMatingSize, $doWatermarking, $doSharping);

        $last_modified = gmdate('D, d M Y H:i:s T', filemtime ($image_thumb_file));
        $mime = ($mime = getimagesize($image_thumb_file)) ? $mime['mime'] : $mime;
        $size = filesize($image_thumb_file);
        $fp   = fopen($image_thumb_file, "rb");
        if (!($mime && $size && $fp)) {
            throw new Exception(COM_EVENTGALLERY_EVENT_CACHEFILE_NOT_FOUND);
        }

        header("Content-Type: " . $mime);
        header("Content-Length: " . $size);
        header("Last-Modified: $last_modified");

        fpassthru($fp);

        fclose($fp);
        if (!$doCache) {
            unlink($image_thumb_file);
        }
    }

}
