<?php
/** Controller for displaying user entered records
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses Pas_Solr_Handler
*/
class Users_RecordsController extends Pas_Controller_Action_Admin {

    /** Set up the ACL and contexts
     * @access public
     * @return void
    */
    public function init() {
        $this->_helper->_acl->deny('public');
        $this->_helper->_acl->allow('member',null);
        
    }

    /** Set up the index list
     * @access public
     * @return void
     */
    public function indexAction() {
        $person = $this->getAccount();
        if(!is_null($person->peopleID)){
            $params = $this->_getAllParams();
            $params['finderID'] = $person->peopleID;
            $params['-createdBy'] = $person->id;
            $search = new Pas_Solr_Handler();
            $search->setCore('objects');
            $search->setFields(array(
                'id', 'identifier', 'objecttype',
                'title', 'broadperiod','imagedir',
                'filename','thumbnail','old_findID',
                'description', 'county', 'workflow')
            );

            $search->setFacets(array('objectType','county','broadperiod','institution'));
            $search->setParams($params);
            $search->execute();
            $this->view->paginator = $search->createPagination();
            $this->view->finds = $search->processResults();
            $this->view->facets = $search->processFacets();
        } else {
            $this->_redirect('/error/accountconnection');
        }
    }
    /** Display the map
     * @access public
     * @return void
     */
    public function mappedAction() {
        //Magic in view
    }
}