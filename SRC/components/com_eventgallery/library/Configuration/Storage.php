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

class Storage extends Configuration
{
    public function getMaxItemsPerBatch() {
        return (int)$this->get('sync_max_files_per_batch', 25);
    }

    public function getS3Region() {
        return $this->get('storage_s3_region', '');
    }

    public function getS3CredentialsKey() {
        return $this->get('storage_s3_credentials_key', '');
    }

    public function getS3CredentialsSecret() {
        return $this->get('storage_s3_credentials_secret', '');
    }

    public function getS3SignatureVersion() {
        return $this->get('storage_s3_signature_version', 'v4');
    }

    public function getS3BucketOriginals() {
        return $this->get('storage_s3_bucket_originals', '');
    }

    public function getS3BucketResized() {
        return $this->get('storage_s3_bucket_resized', '');
    }

    public function getS3ResizeAPIUrl() {
        return $this->get('storage_s3_resize_api_url', '');
    }

    public function getS3ResizeAPIKey() {
        return $this->get('storage_s3_resize_api_key', '');
    }

    public function getS3CloundfrontDomain() {
        return $this->get('storage_s3_cloudfront_domain', '');
    }
}
