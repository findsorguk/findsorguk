<?php 
 /** Action helper to get the redirect for a user upon login
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @author Mary Chester-Kadwell <mchester-kadwell @ britishmuseum.org>
 * @version 1
 * @since 1
 * @uses Pas_User_Details
 * @uses Zend_Exception
 * 
 */


class Pas_Controller_Action_Helper_LoginRedirect extends Zend_Controller_Action_Helper_Abstract {

  /** Direct method for getting the user's redirect
  */
  public function direct() {
    return $this->_getUserRedirect();
  }
  
  /** Get the user's redirect from the model
  * @access private
  * @return $string
  */
  private function _getUserRedirect() {
    $redirects = new LoginRedirects();
    $redirect = $redirects->getRedirect();
    $clean = array_flip($redirect);
    return $clean[0];
  }
  
  
}
