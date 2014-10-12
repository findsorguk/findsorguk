<?php
/** A controller for pulling in data for experimenting on data from the Middle
 * East Apis and services
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Exception_Param
 */
class Experiments_MiddleeastController extends Pas_Controller_Action_Admin {

    /** The init function
     * Set ACL up
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
    }

    /** The default action
     * @access public
     * @return void
     */
    public function indexAction() {
        //Action and magic in view
    }

    /** Look up an individual person
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function personAction() {
        if($this->_getParam('called',false)){

        } else {
            throw new Pas_Exception_Param('No name has been called', 500);
        }
    }
}