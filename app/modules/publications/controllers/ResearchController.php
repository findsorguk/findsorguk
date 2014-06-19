<?php
/** Controller for research publications list
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @author     Daniel Pett <dpett@britishmuseum.org>
* @copyright  Daniel Pett <dpett@britishmuseum.org>
* @license    GNU General Public License
*/
class Publications_ResearchController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
 	$this->_helper->_acl->allow(null);
	}
	
	/** Return the index page
	*/ 
	public function indexAction() {
    }
    
	
}