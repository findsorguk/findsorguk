<?php
/** Controller for displaying information topics
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Help
 * 
*/
class Help_DatabaseController extends Pas_Controller_Action_Admin {

    /** The help model
     * @access protected
     * @var \Help
     */
    protected $_help;

    /** The init function
     * @access public
     * @return void
     */
    public function init()  {
        $this->_helper->acl->allow('public',null);
        $this->_help = new Help();
    }
    /** Display the help topics
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->help = $this->_help->getTopics(
                $this->_getParam('page'),
                'databasehelp'
                );
    }
    
    /** Display an individual topic
     * @access public
     * @return void
     */
    public function topicAction() {
        $this->view->help = $this->_help->getTopic(
                'databasehelp',
                $this->_getParam('id')
                );
    }
	
}