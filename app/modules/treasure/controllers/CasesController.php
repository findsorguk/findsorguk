<?php
/** Controller for all getting data on existing treasure cases
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses TreasureCases
 *
*/
class Treasure_CasesController extends Pas_Controller_Action_Admin {

    /** Init the controller
     * @access public
     * @return void
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->_acl->allow('public',null);
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction() {
        $treasure = new TreasureCases();
        $this->view->treasurecases = $treasure->getCases($this->_getAllParams());
        $current_year = date('Y');
        $years = range(1998, $current_year);
        $yearslist = array();
        foreach($years as $key => $value) {
            $yearslist[] = array('year' => $value);
        }
        $this->view->years = $yearslist;
    }
}