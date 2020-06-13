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

class EventgalleryLibraryCommonS3client
{
    const ACL_PRIVATE = 'private';
    const ACL_PUBLIC_READ = 'public-read';
    const ACL_PUBLIC_READ_WRITE = 'public-read-write';
    const ACL_AUTHENTICATED_READ = 'authenticated-read';

    /**
     * @var S3
     */
    private $s3client = null;
    private $bucketForOriginalImages;
    private $bucketForThumbnails;
    private static $instance;
    private $key;
    private $secret;
    private $region;
    private $signatureVersion;
    private $cloundfrontDomain;

    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    private $config;


    /**
     * @return EventgalleryLibraryCommonS3client
     */
    public static function getInstance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    /**
     * @var bool defines if the S3 client is ready to use
     */
    private $isActive = false;

    private function __construct()
    {
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        $this->region = $this->config->getStorage()->getS3Region();
        $this->key = $this->config->getStorage()->getS3CredentialsKey();
        $this->secret = $this->config->getStorage()->getS3CredentialsSecret();
        $this->signatureVersion = $this->config->getStorage()->getS3SignatureVersion();
        $this->bucketForOriginalImages = $this->config->getStorage()->getS3BucketOriginals();
        $this->bucketForThumbnails = $this->config->getStorage()->getS3BucketResized();
        $this->cloundfrontDomain = $this->config->getStorage()->getS3CloundfrontDomain();


        if (!empty($this->region) && !empty($this->key) && !empty($this->secret) && !empty($this->bucketForOriginalImages) && !empty($this->bucketForThumbnails)) {
            $this->isActive = true;
        }
    }

    private function getS3Instance() {
        if ($this->s3client == null) {
            $this->s3client = new S3($this->key, $this->secret, true, 's3.amazonaws.com', $this->region);
            $this->s3client->setSignatureVersion($this->signatureVersion);
        }
        return $this->s3client;
    }

    /**
     * @return String
     */
    public function getBucketForOriginalImages()
    {
        return $this->bucketForOriginalImages;
    }

    /**
     * @return String
     */
    public function getBucketForThumbnails()
    {
        return $this->bucketForThumbnails;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function getObjects($bucket, $prefix) {
        $s3result = array();
        $items = $this->getS3Instance()->getBucket($bucket, $prefix);
        foreach($items as $item) {
            $s3result[] = $this->translateToS3Object($item);
        }
        return $s3result;
    }

    public function getObject($args) {
        throw new Exception("getObject not implemented " . print_r($args, true));
    }

    public function putObject($args) {
        throw new Exception("putObject not implemented " . print_r($args, true));
    }

    public function getObjectToFile($bucket, $key, $filename) {
        $item = $this->getS3Instance()->getObject($bucket, $key, $filename);
        return array('Key' => $key, 'ETag' => $item->headers['hash']);
    }

    public function putObjectFile($bucket, $key, $sourcefilename, $permission) {
        $success = $this->getS3Instance()->putObjectFile($sourcefilename, $bucket, $key, $permission);
        if (!$success) {
            return null;
        }
        $item = $this->getS3Instance()->getObjectInfo($bucket, $key);
        return array('Key' => $key, 'ETag' => $item['hash']);
    }

    private function translateToS3Object($item) {
        return array(
            'Key' => $item['name'],
            'ETag' => $item['hash']
        );
    }

    /**
     * @param String $bucket
     * @param String $key
     * @param boolean $useCloudfrontDomain defines if we create the URL with the cloundfront domain
     * @return string
     */
    public function getURL($bucket, $key, $useCloudfrontDomain) {

        if (!$this->isActive) {
            return "";
        }

        if ($useCloudfrontDomain && !empty($this->cloundfrontDomain)) {
            $url = "https://" . $this->cloundfrontDomain . "/" . $key;
        } else {
            $url = "https://" . $bucket . ".s3.amazonaws.com/" . $key;
        }
        //return $this->getS3Instance()->getObjectUrl($bucket, $key);
        return $url;
    }

    /**
     * @param $bucket
     * @param $key
     * @param string $time something like '+10 minutes'
     * @return String
     */
    public function getSignedURL($bucket, $key, $time) {

        if (!$this->isActive) {
            return "";
        }

        $cmd = $this->getS3Instance()->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $key
        ]);

        $request = $this->getS3Instance()->createPresignedRequest($cmd, $time);

        return (string) $request->getUri();
    }
}
