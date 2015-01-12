<?php

class Pas_Controller_Plugin_BootstrapError extends Zend_Controller_Plugin_Abstract
{
    public static function handle($errno, $errstr, $errfile, $errline)
    {
        if (!error_reporting()) return;
        throw new Exception($errstr . " in $errfile:$errline". $errno);
    }

    public static function set()
    {
        set_error_handler(array(__CLASS__, 'handle'));
    }



}