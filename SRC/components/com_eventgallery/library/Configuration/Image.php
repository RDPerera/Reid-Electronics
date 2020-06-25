<?php

namespace Joomla\Component\Eventgallery\Site\Library\Configuration;

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class Image extends Configuration
{
    public function doUseLegacyImageRendering() {
        return $this->get('use_legacy_image_rendering', 0) == 1;
    }

    public function getImageQuality() {
        return (int)$this->get('image_quality', 85);
    }

    public function doUseSharpening() {
        return $this->get('use_sharpening', 1) == 1;
    }

    public function doUseSharpeningForOriginalSizes() {
        return $this->get('use_sharpening_for_originalsize', 0) == 1;
    }

    public function getImageSharpenMatrix() {
        return $this->get('image_sharpenMatrix', '[[-1,-1,-1],[-1,16,-1],[-1,-1,-1]]');
    }

    public function doAutoRotate() {
        return $this->get('use_autorotate', 1) == 1;
    }

    public function doUseIPTCData() {
        return $this->get('use_iptc_data', 1) == 1;
    }

    public function doOverwriteWithIPTCData() {
        return $this->get('overwrite_with_iptc_data', 0) == 1;
    }

    public function doUseHtaccessToProtectOriginalFiles() {
        return $this->get('use_htacces_to_protect_original_files', 1) == 1;
    }

    public function doUseLazyLoadingForImages() {
        return $this->get('use_lazyloading_for_images', 1) == 1;
    }

    public function doUseWatermarkForMainImages() {
        return $this->get('use_watermark_for_mainimages', 1) == 1;
    }
}



