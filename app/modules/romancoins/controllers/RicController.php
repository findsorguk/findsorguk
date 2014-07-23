<?php
/** Controller for displaying Roman Imperial Coinage
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 */
class Romancoins_RicController extends Pas_Controller_Action_Admin {
	
    /** Init the controller
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(NULL);
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction() {
        //Magic in view
    }
}