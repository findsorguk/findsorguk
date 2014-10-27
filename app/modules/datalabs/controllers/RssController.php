<?php

/** Controller for RSS section
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 *
 */
class Datalabs_RssController extends Pas_Controller_Action_Admin
{

    /** Setup the contexts by action and the ACL.
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
    }

    /** Display list of RSS feeds.
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('rss');
    }
}