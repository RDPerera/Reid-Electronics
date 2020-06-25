<?php

namespace Joomla\Component\Eventgallery\Site\Library\Configuration;

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\Component\Eventgallery\Site\Library\Connector\Flickr;
use Joomla\Component\Eventgallery\Site\Library\Connector\GooglePhotos;

defined('_JEXEC') or die;

class General extends Configuration
{
    public function getDownloadId() {
        return $this->get('downloadid', '');
    }

    public function getAdminUserGroupId() {
        return $this->get('admin_usergroup', '8');
    }

    public function getFlickrAPIKey() {
        return $this->get('flickr_api_key', Flickr::DEFAULT_FLICKR_API_KEY);
    }

    public function getDefaultUsergroup() {
        return $this->get('eventgallery_default_usergroup', [1]);
    }

    public function doShowUserGroupProtectedEventsInList() {
        return $this->get('show_usergroup_protected_events_in_list', 0) ==1;
    }

    public function doHideUserGroupProtectedEventsInList() {
        return !$this->doShowUserGroupProtectedEventsInList();
    }

    /**
     * @return integer
     */
    public function doLoadResponsiveCSS() {
        return intval($this->get('load_responsive_css', 1));
    }

    public function doUseCaching() {
        return $this->get('use_caching', 0) == 1;
    }

    public function doDebug() {
        return $this->get('debug', 0) == 1;
    }

    /**
     * @deprecated
     * @return mixed
     */
    public function getGooglePhotosRefreshToken() {
        return $this->get('google_photos_refresh_token', '');
    }

    public function getFlickrCacheLifetime() {
        return (int)$this->get('cache_flickr_lifetime',Flickr::$cache_life);
    }

    public function getGooglePhotosCacheLifetime() {
        return (int)$this->get('cache_picasa_lifetime',GooglePhotos::$cache_life);
    }
}
