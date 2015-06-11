<?php

/** A front controller plugin for catching bootstrap errors
 *
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller
 * @subpackage Plugin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class Pas_Controller_Plugin_BootstrapError extends Zend_Controller_Plugin_Abstract
{
    /** Handle the errors
     * @access public
     * @param string $errno the error number
     * @param string $errstr the error string
     * @param string $errfile the error file
     * @param string $errline the error line
     * @return void|Exception
     */
    public static function handle($errno, $errstr, $errfile, $errline)
    {
        if (!error_reporting()) return;
        throw new Exception($errstr . " in $errfile:$errline" . $errno);
    }

    /** Set the error handler
     * @access public
     * @return void
     */
    public static function set()
    {
        set_error_handler(array(__CLASS__, 'handle'));
    }
}