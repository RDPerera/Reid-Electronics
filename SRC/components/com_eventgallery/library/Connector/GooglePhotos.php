<?php

namespace Joomla\Component\Eventgallery\Site\Library\Connector;

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.error.log');
require_once JPATH_ROOT.'/components/com_eventgallery/config.php';
require_once JPATH_ROOT.'/components/com_eventgallery/library/common/logger.php';

class GooglePhotos
{

    /**
     * This cache is necessary to avoid requesting an auth token too many times. This happens
     * if the refresh token is invalid but not empty.
     *
     * @var array
     */
    static $tokenCache = [];

    public static $cachebasedir;
    public static $cache_life = '86400'; //caching time, in seconds
    public static $requesttimeout = 5;


    /**
     * @return string
     */
    const COM_EVENTGALLERY_GOOGLEPHOTOS_LOGFILENAME = 'com_eventgallery_googlephotos.log.php';

    public static function doRequest($cachelifetime, $method, $url, $hash, $data, $doCache)
    {
        \JLog::addLogger(
            array(
                'text_file' => self::COM_EVENTGALLERY_GOOGLEPHOTOS_LOGFILENAME,
                'logger' => 'Eventgalleryformattedtext'
            ),
            \JLog::ALL,
            'com_eventgallery'
        );
        //\JLog::add('processing url '.$url, \JLog::INFO, 'com_eventgallery');


        self::initCacheDirs();

        $cachefilename = self::$cachebasedir . $hash . '.json';

        if ($doCache && file_exists($cachefilename) && (time() - filemtime($cachefilename) <= $cachelifetime)) {
            // no need to do anything since the cache is still valid

        } else {

            //\JLog::add('have writen new cache file for '.$url, \JLog::INFO, 'com_eventgallery');
            /**
             * @var \JHttpResponse $result
             */

            try {
                if ($method == 'POST') {
                    $result = \JHttpFactory::getHttp()->post($url, $data, ['Content-Type' => 'application/json'], self::$requesttimeout);
                } else {
                    $result = \JHttpFactory::getHttp()->get($url, null, self::$requesttimeout);
                }

                if ($result->code < 300) {
                    $fh = fopen($cachefilename, 'w') or die("can't open file $cachefilename");
                    fwrite($fh, $result->body);
                    fclose($fh);
                } else {
                    \JLog::add('unable to connect to remote host. Make sure curl is available or allow_url_fopen=On and the server has access to the internet. url: '.$url, \JLog::INFO, 'com_eventgallery');
                }

                // make sure we don't reload issue causing elements too often. Max every minute should do.
                if (strlen($result->body) == 0) {
                    touch($cachefilename, time() - (self::$cache_life - 60)) ;
                }
                $result = NULL;

            } catch (\RuntimeException $e) {
                \JLog::add('Error for url '. $url .': '.$e->getMessage(), \JLog::INFO, 'com_eventgallery');
            }

        }

        return $cachefilename;

    }

    public static  function initCacheDirs() {

        if (!is_dir(JPATH_CACHE)) {
            mkdir(JPATH_CACHE);
        }

        self::$cachebasedir = COM_EVENTGALLERY_GOOGLE_PHOTOS_CACHE_PATH;

        if (!is_dir(self::$cachebasedir)) {
            mkdir(self::$cachebasedir);
        }
    }

    /**
     * Updates the album with the database
     * @param $cachelifetime
     * @param $api_clientid
     * @param $api_secret
     * @param $refresh_token
     * @param $db \JDatabaseDriver
     * @return null
     */
    public static function syncAlbums($cachelifetime, $api_clientid, $api_secret, $refresh_token, $db)
    {
        self::initCacheDirs();

        $hash_prefix = md5( $api_clientid.$api_secret.$refresh_token);
        $filename =  $hash_prefix . '.albums.obj';
        $serOBjectPath = self::$cachebasedir . $filename;


        if (file_exists($serOBjectPath) && (time() - filemtime($serOBjectPath) <= $cachelifetime)) {
            return null;
        }

        $access_token = self::getAccessToken($db, $api_clientid, $api_secret, $refresh_token);

        $url = 'https://photoslibrary.googleapis.com/v1/albums/?pageSize=50&prettyPrint=true';
        $url .= "&access_token=" . $access_token;

        $currentPageNumber = 1;
        $cachefilename = self::doRequest($cachelifetime, 'GET', $url, $hash_prefix.'.albums.'.$currentPageNumber , null, true);
        $jsonAlbums = json_decode(@file_get_contents($cachefilename));

        if (json_last_error() != JSON_ERROR_NONE) {
            \JLog::add('unable to load json content from file. File Name: '. $cachefilename.' for albums.', \JLog::INFO, 'com_eventgallery');
            return;
        }

        $albums = $jsonAlbums->albums;

        while (!empty($jsonAlbums->nextPageToken)) {
            $currentPageNumber++;
            $pagingUrl = $url . '&pageToken=' . $jsonAlbums->nextPageToken;
            $cachefilename = self::doRequest($cachelifetime, 'GET', $pagingUrl, $hash_prefix.'.albums.'.$currentPageNumber, null, true);
            $jsonAlbums = json_decode(file_get_contents($cachefilename));
            if (json_last_error() != JSON_ERROR_NONE) {
                \JLog::add('unable to load json content from file. File Name: '. $cachefilename.' for albums.', \JLog::INFO, 'com_eventgallery');
                return;
            }
            $albums = array_merge($albums, $jsonAlbums->albums);
        }

        foreach($albums as $album) {
            $query = $db->getQuery(true);
            $query->update('#__eventgallery_file')
                ->set('googlephotos_baseurl='.$db->quote("".$album->coverPhotoBaseUrl))
                ->where('folder='.$db->quote($album->id) .' AND ismainimage=1');
            $db->setQuery($query)->execute();

        }

        $c = 'just dummy content';//serialize($album);
        file_put_contents($serOBjectPath, $c);

        return;
    }

    /**
     * retrieved albums from Google
     * @param $cachelifetime
     * @param $api_clientid
     * @param $api_secret
     * @param $refresh_token
     * @param $db \JDatabaseDriver
     * @return null
     */
    public static function getAlbums($cachelifetime, $api_clientid, $api_secret, $refresh_token, $db) {
        self::initCacheDirs();

        $hash_prefix = md5( $api_clientid.$api_secret.$refresh_token);

        $access_token = self::getAccessToken($db, $api_clientid, $api_secret, $refresh_token);

        $url = 'https://photoslibrary.googleapis.com/v1/albums/?pageSize=50&prettyPrint=true';
        $url .= "&access_token=" . $access_token;

        $currentPageNumber = 1;
        $cachefilename = self::doRequest($cachelifetime, 'GET', $url, $hash_prefix.'.albums.'.$currentPageNumber , null, true);
        $jsonAlbums = json_decode(@file_get_contents($cachefilename));

        if (json_last_error() != JSON_ERROR_NONE) {
            \JLog::add('unable to load json content from file. File Name: '. $cachefilename.' for albums.', \JLog::INFO, 'com_eventgallery');
            return;
        }

        $albums = $jsonAlbums->albums;

        while (!empty($jsonAlbums->nextPageToken)) {
            $currentPageNumber++;
            $pagingUrl = $url . '&pageToken=' . $jsonAlbums->nextPageToken;
            $cachefilename = self::doRequest($cachelifetime, 'GET', $pagingUrl, $hash_prefix.'.albums.'.$currentPageNumber, null, true);
            $jsonAlbums = json_decode(file_get_contents($cachefilename));
            if (isset($jsonAlbums->albums)) {
                $albums = array_merge($albums, $jsonAlbums->albums);
            }
        }

        return $albums;

    }

    /**
     * Updates the album with the database
     * @param $cachelifetime
     * @param $api_clientid
     * @param $api_secret
     * @param $refresh_token
     * @param $db \JDatabaseDriver
     * @param $albumId
     * @return null
     */
    public static function syncAlbum($cachelifetime, $api_clientid, $api_secret, $refresh_token, $db, $albumId)
    {
        $startTime = microtime(true);
        self::initCacheDirs();

        $serOBjectPath = self::$cachebasedir . $albumId .'.obj';

        if (file_exists($serOBjectPath) && (time() - filemtime($serOBjectPath) <= $cachelifetime)) {
            return null;
        }

        $access_token = self::getAccessToken($db, $api_clientid, $api_secret, $refresh_token);
        $hash_prefix = $albumId;

        $url = 'https://photoslibrary.googleapis.com/v1/albums/'. $albumId .'?prettyPrint=false';
        $url .= "&access_token=" . $access_token;

        $cachefilename = self::doRequest($cachelifetime, 'GET', $url, $hash_prefix.'.album' , null, true);
        $jsonAlbum = json_decode(@file_get_contents($cachefilename));

        $currentPageNumber = 1;
        $url = 'https://photoslibrary.googleapis.com/v1/mediaItems:search?pageSize=100&prettyPrint=false';
        $url .= "&access_token=" . $access_token;
        $data = json_encode(
            [
                'albumId' => $albumId
            ]);
        $cachefilename = self::doRequest($cachelifetime, 'POST', $url, $hash_prefix.'.items.'.$currentPageNumber, $data, true);

        $jsonMediaItems = json_decode(@file_get_contents($cachefilename));

        if (json_last_error() != JSON_ERROR_NONE) {
            \JLog::add('unable to load json content from file. File Name: '. $cachefilename.' for ' . $albumId, \JLog::INFO, 'com_eventgallery');
            $album['photos'] = [];
            $album['overallCount'] = 0;
            return (object)$album;
        }

        $mediaItems = $jsonMediaItems->mediaItems;

        while (!empty($jsonMediaItems->nextPageToken)) {
            $currentPageNumber++;
            $pagingUrl = $url . '&pageToken=' . $jsonMediaItems->nextPageToken;
            $cachefilename = self::doRequest($cachelifetime, 'POST', $pagingUrl, $hash_prefix.'.items.'.$currentPageNumber, $data, true);
            $jsonMediaItems = json_decode(file_get_contents($cachefilename));
            $mediaItems = array_merge($mediaItems, $jsonMediaItems->mediaItems);
        }


        $album = Array();
        $photos = Array();
        $ordering = 0;
        #echo "After DOM loaded:". memory_get_usage() . "<br>\n";

        foreach ($mediaItems as $mediaItem) {

            $photo = Array();
            if (!isset($mediaItem->mediaMetadata->photo)) {
                continue;
            }

            $photo['baseurl'] = $mediaItem->baseUrl;
            $photo['width'] = $mediaItem->mediaMetadata->width;
            $photo['height'] = $mediaItem->mediaMetadata->height;


            $photo['caption'] = isset($mediaItem->description)?$mediaItem->description:'';
            $photo['title'] = "";//$mediaItem->filename;
            $photo['date'] = $mediaItem->mediaMetadata->creationTime;
            $photo['folder'] = $albumId;
            $photo['file'] = $mediaItem->id;
            $photo['googlephotos_filename'] = $mediaItem->filename;


            $exif = Array();

            $exif['fstop'] = isset($mediaItem->mediaMetadata->photo->apertureFNumber)? $mediaItem->mediaMetadata->photo->apertureFNumber:'';
            $exif['focallength'] = isset($mediaItem->mediaMetadata->photo->focalLength)?$mediaItem->mediaMetadata->photo->focalLength:'';
            $exif['exposuretime'] = isset($mediaItem->mediaMetadata->photo->exposureTime)?$mediaItem->mediaMetadata->photo->exposureTime:'';
            $exif['model'] = (isset($mediaItem->mediaMetadata->photo->cameraModel)?$mediaItem->mediaMetadata->photo->cameraModel:'') . ' ' .(isset($mediaItem->mediaMetadata->photo->cameraModel)?$mediaItem->mediaMetadata->photo->cameraModel:'');
            $exif['iso'] = isset($mediaItem->mediaMetadata->photo->isoEquivalent)?$mediaItem->mediaMetadata->photo->isoEquivalent:'';
            $exif['filename'] = isset($mediaItem->filename)?$mediaItem->filename:'';

            $photo['exif'] = (object)$exif;
            $photo['published'] = 1;

            $photo['ordering'] = $ordering++;

            $photos[$photo['file']] = $photo;
            unset($photo);
        }

        $album['folder'] = $albumId;
        $album['file'] = $jsonAlbum->coverPhotoMediaItemId;
        $album['photos'] = $photos;
        $album['overallCount'] = $jsonAlbum->mediaItemsCount;

        $album['description'] = $jsonAlbum->title;

        $album['width'] = 1440;
        $album['height'] = 1440;

        $album['title'] = $jsonAlbum->title;
        $album['baseurl'] = $jsonAlbum->coverPhotoBaseUrl;

        #echo "Finally:". memory_get_usage() . "\n<br>";
        #echo memory_get_peak_usage() . "\n<br>";

        #echo "<pre>"; 		print_r($album);		echo "</pre>\n";

        $start = microtime(true);

        $album = (object)$album;

        $db->transactionStart();

        try {

            $query = $db->getQuery(true);
            $query->delete('#__eventgallery_file')
                ->where('folder='.$db->quote($albumId));
            $db->setQuery($query);
            $db->execute();

            if (count($photos)>0) {

                foreach(array_chunk($photos, 100) as $photosChunk) {
                    self::addPhotosToDatabase($photosChunk, $albumId, $db);
                }

                $query = $db->getQuery(true);
                $query->update('#__eventgallery_file')
                    ->set('ismainimage=1')
                    ->where('folder=' . $db->quote($album->folder) . ' AND file=' . $db->quote($album->file));
                $db->setQuery($query);
                $db->execute();

                $query = $db->getQuery(true);
                $query->update('#__eventgallery_folder')
                    ->set('googlephotostitle=' . $db->quote($album->title))
                    ->where('folder=' . $db->quote($album->folder));
                $db->setQuery($query);
                $db->execute();

            }
            $db->transactionCommit();

        } catch (\JDatabaseExceptionExecuting $e) {
            $db->transactionRollback();
        }

        $c = 'just dummy content';//serialize($album);
        file_put_contents($serOBjectPath, $c);

        $endTime = microtime(true);

        // no need to return anything since this is just an update message.
        return null;
    }

    private static function addPhotosToDatabase($photos, $albumId, $db) {

        $query = $db->getQuery(true);

        $query->insert($db->quoteName('#__eventgallery_file'))
            ->columns(
                'folder,
                        file,
                        googlephotos_filename,
                        width,
                        height,
                        caption,
                        title,
                        googlephotos_baseurl,
                        url,
                        exif,
                        ordering,
                        ismainimage,
                        ismainimageonly,
                        hits,
                        published,
                        userid,
                        modified,
                        created,
                        creation_date'
            );


        foreach ($photos as $photo) {
            $query->values(implode(',', array(
                $db->quote($albumId),
                $db->quote($photo['file']),
                $db->quote($photo['googlephotos_filename']),
                $db->quote($photo['width']),
                $db->quote($photo['height']),
                $db->quote($photo['title']),
                $db->quote($photo['caption']),
                $db->quote($photo['baseurl']),
                $db->quote(''),
                $db->quote(json_encode($photo['exif'])),
                $db->quote($photo['ordering']),
                0,
                0,
                0,
                1,
                0,
                'now()',
                'now()',
                $db->quote(date('YmdHis', strtotime($photo['date'])))
            )));

        }

        $db->setQuery($query);
        $db->execute();
    }
    /**
     * use a refresh token to create an access token.
     *
     * @return mixed
     */
    private static function getAccessToken($db, $api_clientid, $api_secret, $refresh_token)
    {

        \JLog::addLogger(
            array(
                'text_file' => self::COM_EVENTGALLERY_GOOGLEPHOTOS_LOGFILENAME,
                'logger' => 'Eventgalleryformattedtext'
            ),
            \JLog::ALL,
            'com_eventgallery'
        );

        $refreshTokenHash = hash('sha256', $refresh_token);

        if (isset(self::$tokenCache[$refreshTokenHash])) {
            return self::$tokenCache[$refreshTokenHash];
        }

        $query = $db->getQuery(true);
        $query->select('access_token')
            ->from('#__eventgallery_auth_token')
            ->where('valid_until > now()')
            ->andWhere('refresh_token_hash='.$db->quote($refreshTokenHash));
        $db->setQuery($query);
        $access_token = $db->loadResult();

        if ($access_token !== null && strlen($access_token) > 0) {
            self::$tokenCache[$refreshTokenHash] = $access_token;
            return $access_token;
        }


        $data = [
            'client_id' => $api_clientid,
            'client_secret' => $api_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token'
        ];

        $response = null;
        try {
            $response = \JHttpFactory::getHttp()->post('https://www.googleapis.com/oauth2/v4/token', $data, [], 2);
            $response = json_decode($response->body);
        } catch (\Exception $e) {
            \JLog::add('unable to get access token from Google. Check the refresh token for this account. Error message: ' . $e->getMessage(), \JLog::INFO, 'com_eventgallery');
        }




        /**
         * Save the stuff to the database and set the timestamp to the future. Remove some seconds so we avoid edge cases.
         */
        $access_token = "";
        if (isset($response->access_token)) {
            $access_token = $response->access_token;
            $validityTime = (int)$response->expires_in - 120;

            $query = $db->getQuery(true);
            $query->delete()->from('#__eventgallery_auth_token')
                ->where('refresh_token_hash='.$db->quote($refreshTokenHash));
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $query->insert('#__eventgallery_auth_token')
                ->columns(Array('refresh_token_hash','access_token','valid_until'))
                ->values(implode(',', Array(
                    $db->quote($refreshTokenHash), $db->quote($access_token), 'DATE_ADD(NOW(), INTERVAL '. (int)$validityTime .' SECOND)')));
            $db->setQuery($query);
            $db->execute();

        }

        self::$tokenCache[$refreshTokenHash] = $access_token;

        return $access_token;
    }
}
