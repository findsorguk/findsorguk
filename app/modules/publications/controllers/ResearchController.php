<?php

/** Controller for research publications list
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @author     Daniel Pett <dpett@britishmuseum.org>
 * @copyright  Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 *
 */
class Publications_ResearchController extends Pas_Controller_Action_Admin
{

    /** The init function
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow(null);

    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //Magic in view
    }
}