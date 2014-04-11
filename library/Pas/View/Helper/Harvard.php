<?php
/**
 * A view helper for displaying references in correct Harvard bibliographic style
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Harvard extends Zend_View_Helper_Abstract {

	public function harvard($refs){
	switch($refs['publication_type'])	 {
	case  1: 
		$html = '<li>' 
		. $refs->authors 
		. ', ' 
		. $refs->publication_year
		. ' . <em><a href="' 
		. $refs->url(array(
		'controller' => 'database',
		'action' => 'publication',
		'id' => $refs->id),
		null,
		true) 
		. '" title="View reference work\'s details">' 
		. $refs->title 
		. '</a></em>  ' 
		. $refs->publication_place 
		. ' : ' 
		. $refs->publisher
		. '.</li>';
		break;
	case 2:
		$html = '<li>'
		. $refs->authors
		. ', ' 
		. $refs->publication_year 
		. '. <em><a href="'
		. $refs->url(array(
		'controller' => 'database',
		'action' => 'publication',
		'id' => $refs->id)
		,null,
		true)
		. '" title="View reference work\'s details">'
		. $refs->title
		. '</a></em> '
		. $refs->publication_place 
		. ' : '
		. $refs->publisher
		. '.</li>';
		break;
	case 3: 
		$html = '<li>'
		. $refs->authors
		. ', '
		. $refs->publication_year
		. '. <em><a href="'
		. $refs->url(array(
		'controller' => 'database',
		'action' => 'publication',
		'id' => $refs->id)
		,null,
		true)
		. '" title="View reference work\'s details">'
		. $refs->title
		. '</a></em> '
		. $refs->vol_no 
		. ', '
		. $refs->pp
		. '.</li>';		
		break;
	case 4:
		$html = "Do this";
		break;
	case 5:
		$html = '<li>' 
		. $refs->authors 
		. ', '
		. $refs->publication_year
		. '<em><a href="'
		. $refs->url(array(
		'controller' => 'database',
		'action' => 'publication',
		'id' => $refs->id)
		,null
		,true)
		. '" title="View this reference work\'s details">'
		. $refs->title 
		. '</a></em>, '
		. $refs->vol_no 
		. ', '.$refs->pp 
		. '.</li>';
		break;
	case 6:
		$html = '<li>'
		. $refs->authors 
		. ', '
		. $refs->publication_year
		. '. <em>' . $refs->title
		. '</em> [' . $refs->medium . '] Available at: <a href="'
		. $refs->url .'" title="View webpage referenced">'
		. $refs->url . '</a> [Accessed ' 
		. $refs->accessedDate . '].</li>';
		break;
	default:
		$html = '<li>'
		. $refs->authors
		. ', '
		. $refs->publication_year
		. '. <em><a href="'
		. $refs->url(array(
		'controller' => 'database',
		'action' => 'publication',
		'id' => $refs->id),
		null,
		true)
		. '" title="View reference work\'s details">'
		. $refs->title
		. '</a></em>  '
		. $refs->publication_place
		. ' : '
		. $refs->publisher . '  ' 
		. $refs->vol_no .	', ' 
		. $refs->pp
		. '.</li>';
		break;

	}
		return $html;

}


}