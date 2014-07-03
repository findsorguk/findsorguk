<?php
 /**
  * A helper for displaying current site announcements
  *
  * @author Daniel Pett <dpett@britishmuseum.org>
  * @copyright (c) 2014 Daniel Pett
  * @uses Zend_Controller_Action_Helper_Abstract
  * @category Pas
  * @package Controller
  * @subpackage Controller_Action
  * @license http://URL name
  * @version 1
  *
  */
class Pas_Controller_Action_Helper_Announcements
    extends Zend_Controller_Action_Helper_Abstract {

    /** Get the announcements from the model
     * @access public
     * @return array
     */
    public function direct(){
        $announcements = new Quotes();
        return $announcements->getAnnouncements();
    }
}