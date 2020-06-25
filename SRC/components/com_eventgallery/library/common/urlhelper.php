<?php
/**
 * Created by PhpStorm.
 * User: sven.bluege
 * Date: 16.07.2018
 * Time: 13:44
 */

class EventgalleryLibraryCommonUrlhelper
{
    /**
     * Returns the parts [protocal]://servername of the current request. Can take forward headers into account.
     *
     * @param $s
     * @param bool $use_forwarded_headers
     * @return string
     */
    public static function url_origin($s, $use_forwarded_headers=false)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        if ($use_forwarded_headers) {
            if (isset($s['HTTP_X_FORWARDED_PROTO'])) {
                if ($s['HTTP_X_FORWARDED_PROTO'] == 'https') {
                    $protocol = 'https';
                } else {
                    $protocol = 'http';
                }
            }
        }
        $port = $s['SERVER_PORT'];

        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = ($use_forwarded_headers && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }
}