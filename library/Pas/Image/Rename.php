<?php

/**
 * Created by PhpStorm.
 * User: danielpett
 * Date: 04/12/14
 * Time: 10:19
 */
class Pas_Image_Rename
{

    public function strip($filename, $extension)
    {
        return preg_replace('/\W+/', '', $filename) . '.' . $extension;
    }
} 