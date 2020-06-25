<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 29.04.2018
 * Time: 13:33
 */

namespace components\com_eventgallery\site\library\Data;


class Exif
{
    public $model = null;
    public $focallength = null;
    public $fstop = null;
    public $exposuretime = null;
    public $iso = null;
    public $creation_date = null;

    public function __construct($jsonStringData = null) {
        if (isset($jsonStringData)) {
            $data = json_decode($jsonStringData);
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->model = isset($data->model)?$data->model : null;
                $this->focallength = isset($data->focallength)?$data->focallength : null;
                $this->fstop = isset($data->fstop)?$data->fstop : null;
                $this->exposuretime = isset($data->exposuretime)?$data->exposuretime : null;
                $this->iso = isset($data->iso)?$data->iso : null;
                $this->creation_date = isset($data->creation_date)?$data->creation_date : null;
            }
        }
    }

    public function toJson() {
        return json_encode($this);
    }
}

