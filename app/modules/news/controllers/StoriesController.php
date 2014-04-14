<?php
/** Controller for all the Scheme's news stories
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_StoriesController extends Pas_Controller_Action_Admin {

    public function init() {
    $this->_helper->_acl->allow('public',null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
        ->addActionContext('article', array('xml','json'))
        ->initContext();
    }

    public function indexAction(){
    $format = $this->_request->getParam('format');

    if(in_array($format,array('georss','rss','atom'))){
    $news = new News();
    $news = $news->getNews();
        // prepare an array that our feed is based on
        $feedArray = array(
                'title' => 'Latest news from the Portable Antiquities Scheme',
                'link' => $this->view->serverUrl() . '/news/',
                'charset' => 'utf-8',
                'description' => 'The latest news stories published by the Portable Antiquities Scheme',
                'author' => 'The Portable Antiquities Scheme',
                'image' => $this->view->serverUrl() . '/images/logos/pas.gif',
                'email' => 'info@finds.org.uk',
                'copyright' => 'Creative Commons Licenced',
                'generator' => 'The Scheme database powered by Zend Framework and Dan\'s magic',
                'language' => 'en',
                'entries' => array()
        );
        foreach ($news as $new) {

        //$latlong = $new['declat'] .' ' .$new['declong'];
        $feedArray['entries'][] = array(
            'title' => $new['title'],
            'link' => $this->view->serverUrl() . '/news/story/id/' . $new['id'],
            'guid' => $this->view->serverUrl() .'/news/story/id/' . $new['id'],
            'description' => $this->EllipsisString($new['contents'],200),
            'lastUpdate' => strtotime($new['datePublished']),
                        //'georss'=> $latlong,
                        //'enclosure' => array()
                        );

                        /*if($object['i'] != NULL) {
                        $feedArray['enclosure'][] = array(array(
                        'url' => 'http://www.findsdatabase.org.uk/view/thumbnails/pas/'.$object['i'].'.jpg',
                        'type' => 'image/jpeg' //always sets to jpeg as the thumbnails are derived from there.
                        ));
                        }*/
        }
            $feed = Zend_Feed::importArray($feedArray, $format);
            $feed->send();
            } else {
	$this->_redirect('/news/');
	}
	}


	public function articleAction() {
	if($this->_getParam('id',false)){
	$news = new News();
	$this->view->news = $news->getStory($this->_getParam('id'));
	$comments = new Comments();
	$this->view->comments = $comments->getCommentsNews($this->_getParam('id'));
	$form = new CommentFindForm();
	$form->submit->setLabel('Add a new comment');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
	$data = $this->_helper->akismet($form->getValues());
	$data['contentID'] = $this->_getParam('id');
	$data['comment_type'] = 'newsComment';
	$data['comment_approved'] = 'moderation';
	$comments->add($data);
	$this->_flashMessenger->addMessage('Your comment has been entered and will appear shortly!');
	$this->_redirect('/news/stories/article/id/' . $this->_getParam('id'));
	$this->_flashMessenger->addMessage('There are problems with your comment submission');
	$form->populate($form->getValues());
	}
	} else {
	throw new Exception('No parameter on the url string');
	}

	}

	public function newsfeedAction(){
	}

	public function ellipsisString($string, $max = 300, $rep = '...') {
		if (strlen($string) < $max) {
		return $string;
		} else {
		$leave = $max - strlen ($rep);
		}
	    return strip_tags(substr_replace($string, $rep, $leave),'<br><a><em>');
	}


}