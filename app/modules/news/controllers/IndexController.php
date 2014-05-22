<?php
/** Controller for index of the news module
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @author     Daniel Pett <dpett@britishmuseum.org>
* @copyright  Daniel Pett 2011 <dpett@britishmuseum.org>
* @license    GNU General Public License
*/
class News_IndexController extends Pas_Controller_Action_Admin
{

	/** Initialise the ACL and contexts
	*/
	public function init() {
 	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
                ->setAutoDisableLayout(true)
                ->addContext('rss',array('suffix' => 'rss','header' => 'application/rss+xml'))
                ->addContext('atom',array('suffix' => 'atom','header' => 'application/atom+xml'))
                ->addActionContext('index', array('xml','json','rss','atom'))
                ->initContext();
    }

    	/** Generate the list of news articles for the index page
	*/
	public function indexAction() {
	$news = new News();
	$this->view->news = $news->getAllNewsArticles($this->_getAllParams());
        
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
}