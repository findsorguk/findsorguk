<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * AuditLogs helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_AuditLogs extends Zend_View_Helper_Abstract{
	
	protected $_allowed = array('flos', 'hero', 'treasure', 'fa', 'admin');
	
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

    
    public function auditLogs($id) {
	if(!is_null($this->_role)){
		return $this->buildHtml($id);
	}
	}
	
	public function buildHtml($id){
		$html = '<ul id="tab" class="nav nav-tabs">';
		$html .= '<li class="active"><a href="#findAudit" data-toggle="tab">Finds audit</a></li>';
		$html .= '<li><a href="#fspot" data-toggle="tab">Findspot audit</a></li>';
		$html .= '<li><a href="#coinAudit" data-toggle="tab">Numismatic audit</a></li>';
		$html .= '</ul>';
		$html .= '<div id="myTabContent" class="tab-content">';
        $html .= '<div class="tab-pane fade in active" id="findAudit">';
		$html .= $this->view->changesFind($id);
		$html .= '</div>';
		$html .= '<div class="tab-pane fade" id="fspot">';
		$html .= $this->view->changesFindSpot($id);
		$html .= '</div>';
		$html .= '<div class="tab-pane fade" id="coinAudit">';
		$html .= $this->view->changesCoins($id);
		$html .= '</div></div>';
		
		return $html;
	}
}

