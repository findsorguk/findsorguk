<?php
/** Index controller for news module
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_IndexController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL and contexts
	*/
	public function init() {
 	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
                ->setAutoDisableLayout(true)
                ->addContext('rss',array('suffix' => 'rss','header' => 'application/rss+xml'))
                ->addContext('atom',array('suffix' => 'atom','header' => 'application/atom+xml'))
                ->addActionContext('index', array('xml','json','rss','atom'))
                ->initContext();
    }

	public function indexAction() {
	$news = new News();
	$this->view->news = $news->getAllNewsArticles($this->_getAllParams());
        }
}