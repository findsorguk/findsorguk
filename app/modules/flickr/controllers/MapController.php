<?php
/** Controller for displaying map of flickr photos from the PAS account
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/
class Flickr_MapController extends Pas_Controller_Action_Admin {
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow('public',null);
    }
    /** Setup index page
     * @access public
     * @return void
     */
    public function indexAction() {
       //Magic in view
    }
}