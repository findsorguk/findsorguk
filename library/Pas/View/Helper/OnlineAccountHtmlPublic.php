<?php
class Pas_View_Helper_OnlineAccountHtmlPublic
    extends Zend_View_Helper_Abstract {

    public function OnlineAccountHtmlPublic($id) {
    $accts = new OnlineAccounts();
    $data = $accts->getAllAccounts($id);
    if($data){
    $this->buildHtml($data);
    }
    }

    public function buildHtml($data)  {
    $html ='';
    $html .= '<p>Social profiles: ';
    $html .=  $this->view->partialLoop('partials/contacts/foafAccts.phtml',$data);
    $html .= '</p>';
    echo $html;
    }

}