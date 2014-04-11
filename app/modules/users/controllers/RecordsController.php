<?php
/** Controller for displaying user entered records
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_RecordsController extends Pas_Controller_Action_Admin {
    /** Set up the ACL and contexts
    */
    public function init() {
    $this->_helper->_acl->deny('public');
    $this->_helper->_acl->allow('member',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
     /** Protected function for personal details
     *
     */
    protected function _getDetails() {
    $user = new Pas_User_Details();
    return $user->getPerson();
    }

    /** Set up the index list
    */
    public function indexAction() {

    if(!is_null($this->_getDetails()->peopleID)){
    $params = $this->_getAllParams();
    $params['finderID'] = $this->_getDetails()->peopleID;

    $params['-createdBy'] = $this->_getDetails()->id;
    $search = new Pas_Solr_Handler('objects');
    $search->setFields(array(
    	'id', 'identifier', 'objecttype',
    	'title', 'broadperiod','imagedir',
    	'filename','thumbnail','old_findID',
    	'description', 'county', 'workflow')
    );

    $search->setFacets(array('objectType','county','broadperiod','institution'));
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->_createPagination();
    $this->view->finds = $search->_processResults();
    $this->view->facets = $search->_processFacets();
    } else {
        $this->_redirect('/error/accountconnection');
    }
    }
    /** Display the map
    */
    public function mappedAction() {
    }

}