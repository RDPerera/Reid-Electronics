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

class Social extends Configuration
{
    public function doUseSocialSharingButton() {
        return $this->get('use_social_sharing_button', 0) == 1;
    }

    public function getSharingLinkType() {
        return $this->get('social_sharing_link_type', 'singleimage');
    }

    public function doUseFacebook() {
        return $this->get('use_social_sharing_facebook', 0) == 1;
    }

    public function doUseTwitter() {
        return $this->get('use_social_sharing_twitter', 0) == 1;
    }

    public function doUsePinterest() {
        return $this->get('use_social_sharing_pinterest', 0) == 1;
    }

    public function doUseEmail() {
        return $this->get('use_social_sharing_email', 0) == 1;
    }

    public function doUseDownload() {
        return $this->get('use_social_sharing_download', 0) == 1;
    }

    public function doShowDedicatedDownloadButton() {
        return $this->get('use_dedicated_download_button', 0) == 1;
    }

    /**
     * @return bool
     */
    public function doDownloadOriginalImages() {
        return $this->get('download_original_images', 0) > 0;
    }

    /**
     * @return bool
     */
    public function doLowResImages() {
        return $this->get('download_original_images', 0) == 0 || $this->get('download_original_images', 0) == 2;
    }

    public function getRedirectURL() {
        $ls = new \EventgalleryLibraryDatabaseLocalizablestring($this->get('download_original_images_redirect', ''));
        return $ls->get();
    }

    /**
     * @return array
     */
    public function getDefaultOriginalImageDownloadUsergroups() {
        return $this->get('download_original_images_usergroup', [1]);
    }

    public function doUseImageReporting() {
        return $this->get('use_image_reporting', 0) == 1;
    }
}
