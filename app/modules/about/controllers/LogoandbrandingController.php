<?php
/** Controller for Logo and Branding page of About Us section
 *
 * @category   Pas
 * @package    Pas_Controller
 * @subpackage ActionAdmin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 */
class About_LogoAndBrandingController extends Pas_Controller_Action_Admin {

    /** Setup the contexts by action and the ACL.
     * @access public
     */
    public function init(){
        $this->_helper->acl->allow('public', null);
    }
    /** Display the front page material.
     * @access public
     */
    public function indexAction(){
        $content = new Content();
        $this->view->contents = $content->getFrontContent('logoandbranding');
    }
}