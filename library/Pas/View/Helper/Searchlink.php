<?php 
class Pas_View_Helper_Searchlink extends Zend_View_Helper_Abstract

{

public function searchlink($id)
{
$parameter =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();

$url = $this->view->url(array('module' => 'database','controller' => 'search', 'action' => 'results', $parameter => $id),null,true);
$string = '<p>Search the database for <a href="'.$url.'" title="Search the database for examples">all examples</a> recorded.</p>'; 
return $string;

}
}