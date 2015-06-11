<?php
 /**
  * A helper for displaying current site announcements
  *
  * An example of use:
  * 
  * <code>
  * <?php
  * $this->view->announcements = $this->_helper->announcements();
  * ?>
  * </code>
  * @author Daniel Pett <dpett@britishmuseum.org>
  * @copyright (c) 2014 Daniel Pett
  * @uses Zend_Controller_Action_Helper_Abstract
  * @category Pas
  * @package Controller
  * @subpackage Controller_Action
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
  * @version 1
  * @example /library/Pas/Controller/Action/Admin.php
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