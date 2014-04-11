<?php
/** Controller for displaying information topics
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Info_GithubController extends Pas_Controller_Action_Admin {
	
	protected $_github = null;
	
	protected $_cache;
	
    /** Setup the contexts by action and the ACL.
    */
    public function init(){
    $this->_helper->acl->allow('public', null);
    $this->_github = new Github_Client();
    $this->_cache = Zend_Registry::get('cache');
    }

    /** Display the list of topics or individual pages.
    */	
    public function indexAction(){
    }
    
    /** 
     * Get the list of commits for this repo
     * 
     */
    public function commitsAction()
    {
    $key = 'commits' . $this->_getParam('sha');
	if (!($this->_cache->test($key))) {
	$commits = $this->_github->getCommitApi()->getCommits('portableant', 'Beowulf---PAS', $this->_getParam('sha'));
	$this->_cache->save($commits);
	} else {
	$commits = $this->_cache->load($key);
	}	
	$this->view->commits = $commits;
    }
    
    /** 
     * Get the details for a specific commit
     */
    public function commitAction()
    {
	$key1 = md5('commit' . $this->_getParam('sha'));
	$key2 = md5('commitdetails' . $this->_getParam('sha'));
	if (!($this->_cache->test($key1))) {
		$commit = $this->_github->getCommitApi()->getCommit('portableant', 'Beowulf---PAS', $this->_getParam('sha'));
	$this->_cache->save($commit);
	} else {
		$commit = $this->_cache->load($key1);
	}
	if (!($this->_cache->test($key2))) {
		$commitDetails = $this->_github->getCommitApi()->getCommitDetails('portableant', 'Beowulf---PAS', $this->_getParam('sha'));
	$this->_cache->save($commitDetails);
	} else {
		$commitDetails = $this->_cache->load($key2);
	}	
	$this->view->commit = $commit;
	$this->view->commitDetails = $commitDetails;
    }

    /** 
     * Get a list of open issues for the Scheme's codebase.
     * 
     */
    public function issuesAction()
    {
	$key = 'issuesOpen';
	if (!($this->_cache->test($key))) {
		$issues = $this->_github->getIssueApi()->getList('portableant', 'Beowulf---PAS', array('state' => 'open'));
	} else {
		$issues = $this->_cache->load($key);
	}
	$this->view->issues = $issues;
    }

    /** 
     * Get Details for an issue on github
     * 
     */
    public function issueAction()
    {
	$key1 = md5('issue' . $this->_getParam('number'));
	$key2 = md5('comments' . $this->_getParam('number'));
	if (!($this->_cache->test($key1))) {
    	$issue = $this->_github->getIssueApi()->show('portableant', 'Beowulf---PAS', $this->_getParam('number'));
    	$this->_cache->save($issue);
	} else {
		$issue = $this->_cache->load($key1);
	}	
	if (!($this->_cache->test($key2))) {
    	$comments = $this->_github->getIssueApi()->getComments('portableant', 'Beowulf---PAS', $this->_getParam('number'));
    	$this->_cache->save($comments);
	} else {
		$comments = $this->_cache->load($key2);
	}	
	$this->view->issue = $issue;
	$this->view->comments = $comments;
    }
	
}