<?php

/** Controller for accessing Bronze Age guide objects page
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author     Daniel Pett
*/
class Bronzeage_ObjectsController extends Pas_Controller_Action_Admin {

    /**
    * Set up ACL
    */
    public function init() {
    $this->_helper->_acl->allow('public',null);
    }

    /** Render the index pages
    */
    public function indexAction() {
    $content = new Content();
    if(!in_array($this->_getParam('slug'),array('gold','other'))){
        $this->view->content = $content->getContent('bronzeage', $this->_getParam('slug'));
    } else {
    if($this->_getParam('slug') == 'gold'){
        $this->view->content = $content->getContent('bronzeage', $this->_getParam('slug'));
        $this->view->menu = 'gold';
    } else if($this->_getParam('slug') == 'other'){
        $this->view->content = $content->getContent('bronzeage', $this->_getParam('slug'));
        $this->view->menu = 'other';
    }
    }
    }

}

