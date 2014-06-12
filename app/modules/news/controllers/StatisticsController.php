<?php
/** 
 * Statistical events Controller
 *
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright (c) 2014, Daniel Pett
 * @license http://URL name
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 2
 * @since September 23 2011
*/
class Events_StatisticsController extends Pas_Controller_Action_Admin {

    /** Contexts available for this action
     * @access protected
     * @var array
     */
    protected $_contextsindex = array('xml','rss','json','atom');

    /** Initialise contexts, action helpers
     * @access public
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$contextSwitch = $this->_helper->contextSwitch();
	$contextSwitch->setAutoDisableLayout(true)
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', $this->_contextsindex)
		->initContext();
    }

    /** The index action
     */
    public function indexAction() {
        $events = new Events();
        $this->view->stats = $events->getStatistics();
    }		
}