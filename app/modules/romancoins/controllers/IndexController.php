<?php
/** Controller for displaying Roman index pages
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_IndexController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
        }
	/** Set up the index page with slides
	* @todo when the solr indexer comes online replace the slides model
	*/
	public function indexAction() {
	$content = new Content();
	$this->view->front =  $content->getFrontContent('romancoins');
	}

}
