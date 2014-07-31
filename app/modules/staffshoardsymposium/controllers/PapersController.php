<?php
/** Controller for the Staffordshire symposium paper page
 *  
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * @uses Pas_Exception_Param
 */


class Staffshoardsymposium_PapersController extends Pas_Controller_Action_Admin {
    /** The default action - show the home page
     * @access public
     * @return void
     */
    public function init()  {
        $this->_helper->_acl->allow('public',null);	
    }

    /** The index pages
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function indexAction() {
        if($this->_getParam('slug',0)){	
            $content = new Content();
            $this->view->content = $content->getContent('staffs', $this->_getParam('slug'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

}

