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
require_once JPATH_ROOT.'/components/com_eventgallery/config.php';

use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelIfd;
use lsolesen\pel\Pel;
use lsolesen\pel\PelTag;

class EventgalleryLibraryFileLocal extends EventgalleryLibraryFile
{

    protected $_image_script_path = 'components/com_eventgallery/helpers/image.php?';

    /**
     * creates the lineitem object. $dblineitem is the database object of this line item
     *
     * @param object $object
     * @throws Exception
     */
    function __construct($object)
    {

        parent::__construct($object);

        if ($this->config->getImage()->doUseLegacyImageRendering()) {
            $this->_image_script_path = "index.php?option=com_eventgallery&view=resizeimage";
        }

        // this is necessary to avoid an exception while running in CLI mode
        if (array_key_exists('REQUEST_METHOD', $_SERVER)) {
            $currentApplicationName = JFactory::getApplication()->getName();

            if ($currentApplicationName == 'administrator') {
                $this->_image_script_path .= '&site=1';
            }
        }
    }

    public function getImageUrl($width=104,  $height=104, $fullsize, $larger=false) {
        if ($fullsize) {
            return JUri::root().$this->_image_script_path."&width=".COM_EVENTGALLERY_IMAGE_ORIGINAL_MAX_WIDTH."&folder=".$this->getFolderName()."&file=".urlencode($this->getFileName());
        } else {
            $sizeSet = new EventgalleryHelpersSizeset();
            $width = $sizeSet->getSizeCode($width, $height, $this->getWidth(), $this->getHeight());
            return JUri::root().$this->_image_script_path."&width=".$width."&folder=".$this->getFolderName()."&file=".urlencode($this->getFileName());
        }
    }

    public function getThumbUrl ($width=104, $height=104, $larger=true) {
        $sizeSet = new EventgalleryHelpersSizeset();
        $width = $sizeSet->getSizeCode($width, $height, $this->getWidth(), $this->getHeight());
        return JUri::root().$this->_image_script_path."&width=".$width."&folder=".$this->getFolderName()."&file=".urlencode($this->getFileName());
    }

    public function getOriginalImageUrl() {

    	return JUri::base().substr(JRoute::_('index.php?option=com_eventgallery&view=download&folder='.$this->getFolderName().'&file='.urlencode($this->getFileName()) ), strlen(JUri::base(true)) + 1);
        
    }

    public function getSharingImageUrl() {

        return JUri::base().substr(JRoute::_('index.php?option=com_eventgallery&is_for_sharing=true&view=download&&folder='.$this->getFolderName().'&file='.urlencode($this->getFileName()) ), strlen(JUri::base(true)) + 1);

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
        $folderpath = COM_EVENTGALLERY_IMAGE_FOLDER_PATH.$this->getFolderName();
        self::updateMetadata($folderpath.DIRECTORY_SEPARATOR.$this->getFileName(), $this->getFolderName(), $this->getFileName());

        return EventgalleryLibraryManagerFolder::$SYNC_STATUS_SYNC;
    }

    /**
     * upaded meta information
     * @param $path
     * @param $foldername
     * @param $filename
     */
    public static function updateMetadata($path, $foldername, $filename) {
        $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();

        /** @noinspection PhpUnusedLocalVariableInspection */
        @list($width, $height, $type, $attr) = getimagesize($path, $info);


        $creation_date = "";
        $title = "";
        $caption = "";

        if (isset($info["APP13"]) && function_exists("iptcparse")) {
            $iptc = iptcparse($info["APP13"]);
            if (is_array($iptc)) {
                if (isset($iptc["2#005"])) {
                    $title = $iptc["2#005"][0];
                }

                if (isset($iptc["2#055"])) {
                    $creation_date = $iptc["2#055"][0];
                    if (isset($iptc["2#060"])) {
                        $creation_date .= $iptc["2#060"][0];
                    }
                }

                if (isset($iptc["2#120"])) {
                    $caption = $iptc["2#120"][0];
                }
            }
        }


        $exif = new \components\com_eventgallery\site\library\Data\Exif();

        try {
            $input_jpeg = new PelJpeg($path);

            $app1 = $input_jpeg->getExif();

            if ($app1) {
                $tiff = $app1->getTiff();
                $ifd0 = $tiff->getIfd();
                $exifData = $ifd0->getSubIfd(PelIfd::EXIF);

                if ($exifData) {

                    if ( ($data = $exifData->getEntry(PelTag::APERTURE_VALUE)) || ($data=$exifData->getEntry(PelTag::FNUMBER))) {
                        $value = $data->getValue();
                        $aperture = floor(pow(2, $value[0]/$value[1]/2)*10.0)/10.0;
                        $exif->fstop = sprintf('%.1f', $aperture);
                    }

                    if (($data = $exifData->getEntry(PelTag::FOCAL_LENGTH_IN_35MM_FILM)) || ($data = $exifData->getEntry(PelTag::FOCAL_LENGTH))) {
                        $value = $data->getValue();
                        if (is_int($value)) {
                            $exif->focallength = $value;
                        } else {
                            $exif->focallength = sprintf('%.0f', $value[0] / $value[1]);
                        }
                    }
                    if ($data = $exifData->getEntry(PelTag::EXPOSURE_TIME)) {
                        $value = $data->getValue();

                        $exif->exposuretime = $data->formatNumber($value);

                    }

                    if ($data = $ifd0->getEntry(PelTag::MODEL)) {
                        $exif->model = $data->getText();
                    }
                    if ($data = $exifData->getEntry(PelTag::ISO_SPEED_RATINGS)) {
                        $exif->iso = $data->getText();
                    }

                    // we need to store the image size differently if we rotate the image later.
                    if ($config->getImage()->doAutoRotate() && $ifd0 != null) {

                        $orientation = $ifd0->getEntry(PelTag::ORIENTATION);

                        if ($orientation != null) {
                            if ($orientation->getValue()==6 || $orientation->getValue()==8) {
                                $tempWidth = $width;
                                $width = $height;
                                $height = $tempWidth;
                            }
                        }
                    }

                    if ($pelEntryTime = $exifData->getEntry(PelTag::DATE_TIME_ORIGINAL)) {
                        $exif->creation_date = date('YmdHis', $pelEntryTime->getValue());
                    }
                }


            }
        } catch (Exception $e) {

        }


	    if (empty($creation_date)) {
            $creation_date = $exif->creation_date;
        }

        // do some filtering for the content. We do not allow HTML in here.
        $filter = JFilterInput::getInstance();
        $title = $filter->clean($title, 'html');
        $caption = $filter->clean($caption, 'html');        
        $creation_date = $filter->clean($creation_date, 'html');


        $use_iptc_data = $config->getImage()->doUseIPTCData();
        $override_with_iptc_data = $config->getImage()->doOverwriteWithIPTCData();

        EventgalleryLibraryFileLocal::storeMetadata($foldername, $filename, $width, $height, $exif->toJson(), $creation_date, $use_iptc_data, $override_with_iptc_data, $title, $caption);

        Pel::clearExceptions();
        unset($input_jpeg);
    }

    static public function storeMetadata($foldername, $filename, $width, $height, $exifJson, $creation_date, $use_iptc_data, $override_with_iptc_data, $iptcTitle, $iptcCaption) {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->update("#__eventgallery_file");
        $query->set("width=".$db->quote($width));
        $query->set("height=".$db->quote($height));
        $query->set("exif=".$db->quote($exifJson));
        $query->set("creation_date=".$db->quote($creation_date));
        $query->where('folder='.$db->quote($foldername));
        $query->where('file='.$db->quote($filename));
        $db->setQuery($query);
        $db->execute();

        if ($use_iptc_data && !empty($iptcCaption)) {
            $query = $db->getQuery(true);
            $query->update("#__eventgallery_file");
            $query->set("caption=" . $db->quote($iptcCaption));
            $query->where('folder=' . $db->quote($foldername));
            $query->where('file=' . $db->quote($filename));
            if ($override_with_iptc_data == false) {
                $query->where("(caption='' OR caption IS NULL)");
            }
            $db->setQuery($query);
            $db->execute();
        }

        if ($use_iptc_data && !empty($iptcTitle)) {
            $query = $db->getQuery(true);
            $query->update("#__eventgallery_file");
            $query->set("title=" . $db->quote($iptcTitle));
            $query->where('folder=' . $db->quote($foldername));
            $query->where('file=' . $db->quote($filename));
            if ($override_with_iptc_data == false) {
                $query->where("(title='' OR title IS NULL)");
            }
            $db->setQuery($query);
            $db->execute();
        }
    }

    public function createThumbnails() {
        $sizeSet = new EventgalleryHelpersSizeset();
        $availableSizes = array_unique($sizeSet->availableSizes);

        foreach($availableSizes as $availableSize) {
            EventgalleryLibraryCommonImageprocessor::createThumbnail($this->getFolderName(), $this->getFileName(), $availableSize);
        }

        return [$availableSizes];
    }

    public function getOriginalFile()
    {
        $basename = COM_EVENTGALLERY_IMAGE_FOLDER_PATH . $this->getFolderName() . DIRECTORY_SEPARATOR;

        $filename = $basename . $this->getFileName();
        // try the path to a possible original file
        $fullFilename = $basename. COM_EVENTGALLERY_IMAGE_ORIGINAL_SUBFOLDER . DIRECTORY_SEPARATOR . $this->getFileName();

        if (file_exists($fullFilename)) {
            $filename = $fullFilename;
        }

        return file_get_contents($filename);

    }
}
