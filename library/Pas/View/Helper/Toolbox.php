<?php
/**
 * A view helper for displaying toolbox of links
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_RecordEditDeleteLinks
 */


class Pas_View_helper_Toolbox extends Zend_View_Helper_Abstract {

	protected $_allowed = array('fa','flos','admin', 'treasure');
	
	protected $_role;
	
	public function __construct(){
	$user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    $this->_role = $person->role;
    } else {
    	return false;
    }
    }
	/** Display the toolbox, crappy code
	 *
	 * @param integer $id
	 * @param string $oldfindID
	 * @param string $createdBy
	 */
	public function toolbox($id, $oldfindID, $createdBy) {
	$this->view->inlineScript()->appendFile('/js/bootstrap-modal.js', $type='text/javascript');
	$this->view->inlineScript()->captureStart();
	echo '$(document).ready(function() {
	$(\'#print\').click(function() {
	window.print();
	return false;
	});

    $(\'.overlay\').click(function(e) {
    e.preventDefault();
    var href = $(e.target).attr(\'href\');
    if (href.indexOf(\'#\') == 0) {
        $(href).modal(\'open\');
    } else {
        $.get(href, function(data) {
            $(\'<div class="modal fade" >\' + data + \'</div>\').modal();
        });
    }
	});
	});';
	$this->view->inlineScript()->captureEnd();
	$class = 'btn btn-small btn-primary overlay';
	echo '<div id="toolBox"><p>';
	echo '<a class="' . $class . '"  href="'
	. $this->view->serverUrl() . $this->view->url(array('module' => 'database','controller' => 'ajax','action' => 'webcite','id' => $id),null,true)
	. '" title="Get citation information">Cite record</a> <a class="' . $class . '" href="'
	. $this->view->url(array('module' => 'database','controller' => 'ajax', 'action' => 'embed', 'id' =>  $id),null,true)
	. '" title="Get code to embed this record in your webpage">Embed record</a> ';
	echo $this->view->RecordEditDeleteLinks($id,$oldfindID,$createdBy);
	echo ' <a class="' . $class . '" href="#print" id="print">Print <i class="icon-print icon-white"></i></a> ';
	echo $this->view->Href(array(
            'module' => 'database',
            'controller'=>'artefacts',
            'action'=>'add',
            'checkAcl'=>true,
            'acl'=>'Zend_Acl',
            'content'=>'Add record <i class="icon-white icon-plus"></i>',
            'attribs' => array(
                'title' => 'Add new object',
                'accesskey' => 'a',
                'class' => 'btn btn-small btn-primary')
            ));
	//echo ' <a class="' . $class . '" href="'.$this->view->url(array('module' => 'database','controller' => 'artefacts','action' => 'record','id' => $id,'format' => 'pdf'),null,true)
	//. '" title="Report format">Report</a>';
	if(in_array($this->_role,$this->_allowed)){
	echo  ' <a class="btn btn-small btn-danger" href="'. $this->view->url(array(
            'module' => 'database',
            'controller'=>'artefacts',
            'action'=>'workflow','findID' => $id),null,true) . '">Change workflow</a>';	
	echo ' <a class="' . $class . '"  href="'. $this->view->url(array(
            'module' => 'database',
            'controller'=>'ajax',
            'action'=>'forceindexupdate','findID' => $id),null,true) . '">Force index update</a>';	
	}
	echo'</p></div>';
	}

}
