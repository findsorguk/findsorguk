<?php
/** A controller for experimenting with AWS cloudfront
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 *
 */
class Experiments_VideoController extends Pas_Controller_Action_Admin {

    /** The init for allowing access
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction() {
        //Magic in the view
    }
}

