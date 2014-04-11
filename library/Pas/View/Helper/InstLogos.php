<?php 
/**
 * A view helper for displaying logos for an institution
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    GNU Public
 * @author 	   Daniel Pett
 * @version    1
 * @since      17 November 2011
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_InstLogos extends Zend_View_Helper_Abstract {

	/** Get a logo from database by institution
	 * 
	 * @param string $inst
	 */
	public function InstLogos($inst) {
	$logos = new InstLogos();
	$data = $logos->getLogosInst($inst);
	if(count($data)) {
	return $this->buildHtml($data);
	} else {
	return false;
	}
	}
	/** Build and return html using partial loop
	 * 
	 * @param $data
	 */
	public function buildHtml($data) {
	$html = '';
	$html .= '<ul class="ilogo">';
	$html .= $this->view->partialLoop('partials/contacts/logos.phtml',$data);
	$html .= '</ul>';
	return $html;
	}

}