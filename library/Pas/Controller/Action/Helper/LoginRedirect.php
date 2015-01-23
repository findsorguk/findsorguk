<?php

/** Action helper to get the redirect for a user upon login
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $this->_redirect( $this->_helper->loginRedirect() );
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @author Mary Chester-Kadwell <mchester-kadwell @ britishmuseum.org>
 * @version 1
 * @since 1
 * @uses Pas_User_Details
 * @uses Zend_Exception
 * @example /app/modules/users/controllers/IndexController.php
 *
 */
class Pas_Controller_Action_Helper_LoginRedirect extends Zend_Controller_Action_Helper_Abstract
{

    /** Direct method for getting the user's redirect
     * @access public
     * @return string
     */
    public function direct()
    {
        return $this->_getUserRedirect();
    }

    /** Get the user's redirect from the model
     * @access private
     * @return $string
     */
    private function _getUserRedirect()
    {
        $redirects = new LoginRedirect();
        $redirect = $redirects->getConfig();
        if(is_array($redirect)) {
            $clean = array_flip($redirect);
            $uri = array_values($clean);
        } else {
            $uri = array('/database' => 'Simple search');
        }
        return $uri[0];
    }
}