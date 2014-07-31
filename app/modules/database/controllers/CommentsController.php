<?php
/** Controller for displaying comments on finds records
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Comments
*/
class Database_CommentsController extends Pas_Controller_Action_Admin {

    /** The comments model
     * @access protected
     * @var \Comments
     */
    protected $_comments;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow('public',null);
	
	$this->_helper->contextSwitch()
		->setAutoJsonSerialization(false)
		->setAutoDisableLayout(true)
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', array('xml','rss','atom','json'))
		->initContext();
        $this->_comments = new Comments();
    }

    /** Display the index page of comments.
     * @todo this sucks
     * @access public
     * @return void
    */
    public function indexAction() {
        $comments = $this->_comments->getCommentsToFinds($this->_getParam('page'));
        $context = $this->_helper->contextSwitch()->getCurrentContext();

        if(in_array($context,array('rss','atom'))) {
            $feed = new Zend_Feed_Writer_Feed;
            $feed->setTitle('All published comments on finds records');
            $feed->setLink($this->view->serverUrl());
            $feed->setFeedLink($this->view->CurUrl(), $context);
            $feed->addAuthor(array(
                    'name'  => 'The Portable Antiquities Scheme',
                    'email' => 'info@finds.org.uk',
                    'uri'   => $this->view->serverUrl(),
            ));

            $feed->setDateModified(time());
            $feed->addHub('http://pubsubhubbub.appspot.com/');
            $feed->setDescription('This feed contains all published comments on the Scheme\'s database');

            /**
            * Add one or more entries. Note that entries must
            * be manually added once created.
            */
            foreach($comments as $comment) {
                $entry = $feed->createEntry();
                $entry->setTitle('Comment entered on '.$comment['old_findID']);
                $entry->setLink($this->view->serverUrl() . '/database/artefacts/record/id/'
                        . $comment['id'].'#comm');
                $entry->addAuthor(array(
                        'name'  => $comment['comment_author'],
                        'email' => null,
                        'uri'   => $comment['comment_author_url'],
                ));
                $entry->setDateModified(time());
                $entry->setDateCreated(time());
                $entry->setDescription('Comment regarding '.$comment['old_findID']);
                $entry->setContent(strip_tags($comment['comment_content']));
                $feed->addEntry($entry);
            }
            $out = $feed->export($context);
            echo $out;
            $this->getResponse()->setHeader('Content-type', $context.'+xml');
        } else {
            $this->view->comments = $comments;
        }
    }
}
