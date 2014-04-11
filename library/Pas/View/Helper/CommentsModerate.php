<?php
class Pas_View_Helper_CommentsModerate extends Zend_View_Helper_Abstract

{

public function getComments()
	{
	$comments = new Comments();
	$data = $comments->getCommentsTypeModerate();
	return $data;
	
	}

public function buildHtml()
	{
	$data = $this->getComments();
	if(count($data)){
	$html = '';
	$html .= '<li class="red">';
	$html .= '<p>';
	foreach($data as $d) {
	$html .= $d['comments'].' '.$d['type'].' comments'; 
	$html .= '<br />';
	}
	$html .= '</p>';
	$html .= '</li>';
	return $html;
	} else {
	return false;
	}
	}
	
public function CommentsModerate()
	{
	return $this->buildHtml();
	}


}
