<?php
/** Controller for displaying comments on finds records
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_CommentsController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('public',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoJsonSerialization(false)
		->setAutoDisableLayout(true)
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', array('xml','rss','atom','json'))
		->initContext();
    }

	/** Display the index page of comments.
	*/
	public function indexAction() {
	$comments = new Comments();
	$comments = $comments->getCommentsToFinds($this->_getParam('page'));
	$contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
	$context = $contextSwitch->getCurrentContext();

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
		'email' => NULL,
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
