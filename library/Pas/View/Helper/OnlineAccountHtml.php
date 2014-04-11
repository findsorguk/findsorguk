<?php
/**
 * A view helper for displaying the online accounts in html format
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_OnlineAccountHtml extends Zend_View_Helper_Abstract
{
	/** Retrieve a person's online accounts
	 * @access public
	 * @param integer $id
	 */
	public function OnlineAccountHtml($id) {
	$accts = new OnlineAccounts();
	$data = $accts->getAccounts($id);
	if(count($data)){
	return $this->buildHtml($data);
	}
	}

	/** Build HTML response
	 * @access public
	 * @param array $data
	 * @return string $html
	 */
	public function buildHtml($data) {
	$html ='';
	$html .= '<p>Social profiles: ';
	$html .=  $this->view->partialLoop('partials/contacts/foafAccts.phtml',$data);
	$html .= '</p>';
	return  $html;
	}

}