<?php
/** Statistical events Controller
*
* @category		Pas
* @package		Pas_Controller
* @subpackage	ActionAdmin
* @copyright	Copyright (c) Daniel Pett
* @license		GNU General Public License
* @version		1
* @author		Daniel Pett
* @since		Sept 23 2011
*/
class Events_StatisticsController extends Pas_Controller_Action_Admin {

	/** An array of contexts available to the consumer
	 * 
	 * @var array $_contextsindex
	 */
	protected $_contextsindex = array('xml','rss','json','atom');
	/** Initialise the ACL for access levels, context switch, messages
	*/
    public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		
	$this->_helper->acl->allow('public',null);
	

	$contextSwitch = $this->_helper->contextSwitch();
	$contextSwitch->setAutoDisableLayout(true)
		->addContext('csv',array('suffix' => 'csv'))
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('georss',array('suffix' => 'georss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addContext('ics',array('suffix' => 'ics'))
		->addContext('rdf',array('suffix' => 'rdf'))
		->addContext('xcs',array('suffix' => 'xcs'))
		->addActionContext('index', $this->_contextsindex)
		->addActionContext('upcoming', $this->_contextsindex)
		->addActionContext('event', $this->_contextsindex)
		->initContext();
    }

    /** The index action
	*/
	public function indexAction() {
	$events = new Events();
	$this->view->stats = $events->getStatistics();
	}		
		
}