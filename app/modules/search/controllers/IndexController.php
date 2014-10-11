<?php
/** Controller for the Staffordshire symposium
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Solarium_Client
*/

class Search_IndexController extends Pas_Controller_Action_Admin {

    /** The solr client
     * @access protected
     * @var \Solarium_Client
     */
    protected $_solr;
    
    /** The config options
     * @access protected
     * @var array
     */
    protected $_config = array(
        'adapteroptions' => array(
        'host' => '127.0.0.1',
        'port' => 8080,
        'path' => '/solr/beowulf/',
        ));
    
    /** Set up the ACL
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
        $this->_solr = new Solarium_Client($this->_config);
        
    }

    /** List of the papers available
     * @access public
     * @return void
     */
    public function indexAction() {
        $ping = $this->_solr->createPing();
        if (  !$this->_solr->ping($ping) ) {
            echo '<h2>Search engine system error</h2>';
            echo '<p>Solr service not responding.</p>';
        } else {
            $form = new SolrForm();
            $form->removeElement('thumbnail');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    $this->redirect($this->view->url(
                            array(
                                'module' => 'search',
                                'controller' => 'results',
                                'action' => 'index',
                                'q' => $data['q'])
                            ));
                } else {
                    $form->populate($form->getValues());
                }
            }
        }
    }

}

