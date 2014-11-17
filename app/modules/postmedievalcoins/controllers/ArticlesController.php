<?php

/** Controller for displaying Post medieval articles data
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * @uses Pas_Exception_Param
 * @author Daniel Pett <dpett at britishmuseum.org>
 *
 */
class Postmedievalcoins_ArticlesController extends Pas_Controller_Action_Admin
{

    /** The content model
     * @access protected
     * @var \Content
     */
    protected $_content;

    /** Set up the ACL and contexts
     */
    public function init()
    {

        $this->_helper->acl->allow('public', null);
        $this->_content = new Content();
    }

    /** Set up the article index page
     */
    public function indexAction()
    {
        $this->view->contents = $this->_content->getSectionContents('postmedievalcoins');
    }

    /** Individual page details
     */
    public function pageAction()
    {
        if ($this->_getParam('slug', false)) {
            $this->view->contents = $this->_content->getContent('postmedievalcoins', $this->_getParam('slug'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}