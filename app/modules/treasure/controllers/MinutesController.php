<?php 
/** Controller for rendering the files in the treasure valuation committee minutes folder
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * 
*/
class Treasure_MinutesController extends Pas_Controller_Action_Admin {

    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow(null);
    }

    /** The index function
     * @access public
     * @return void
     */
    public function indexAction() {
        //Magic in view
    }
}