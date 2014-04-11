<?php 
/**
 * A view helper for displaying a count of unpublished comments on news
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_NewsCommentCount extends Zend_View_Helper_Abstract  {

	/** Get a count of the comments from the model
	 * @access private
	 * @param integer $id
	 */
	private function getCount($id) {
	$comments = new Comments();
	$comments = $comments->getCommentsNews($id);
	$total = count($comments);
	return $total;
	}
	
	/** Create the html for rendering
	 * @access private
	 * @param integer $total
	 * @param integer $id
	 * @return string $string
	 */
	private function buildHtml($total, $id) {
	$url = $this->view->url(array(
	'module' => 'news',
	'controller' => 'stories',
	'action' => 'article',
	'id' => $id),NULL,true); 
	if($total >= 1) {
	$string = '<p><strong>Comments: </strong> There are already ' . $total 
	. ' comments, but you could <a href="' . $url . '" title="Add some commentary">add more</a></p>';
	} else {
	$string = '<p><strong>Comments: </strong> <a href="' . $url 
	. '" title="Add some commentary">Be the first to comment</a></p>';
	}
	return $string;
	}
	
	/** Get the comment count and build the html
	 * @access public
	 * @param integer $id
	 * @return string $html
	 */
	public function NewsCommentCount($id) {
	$total = $this->getCount($id);
	$html = $this->buildHtml($total,$id);
	return $html;
	
	}

}