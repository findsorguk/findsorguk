<?php
/** Controller redirecting old legacy urls
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version    1
 * @since 25 October 2011
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Finds
 * @uses Pas_Exception_Param
 */
class Database_LegacyController extends Pas_Controller_Action_Admin {
	
    /** The init function
     * @access public
     * @return void
     */
    public function init() {	
        $this->_helper->_acl->allow('public',array());
        
    }
    /** The redirect
     * 
     */
    const REDIRECT = '/database/artefacts/record/id/';

    /** Redirect old url pattern to new find number
     * @todo move the db call to finds model and cache.
     */
    public function redirectAction() {
        $this->_helper->layout->disableLayout();
        if($this->_getParam('id',false)){
            $finds = new Finds();
            $results = $finds->fetchRow($finds->select()->where('secuid = ?', $this->_getParam('id')));
            if(!is_null($results)){
                $id = (int)$results ->id;	
            } else {
                throw new Pas_Exception_Param($this->_nothingFound);
            }
            $this->getFlash()->addMessage('You have been redirected from an outdated link');
            $this->_redirect(self::REDIRECT . $id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}