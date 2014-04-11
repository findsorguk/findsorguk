<?php
 
/**
 * Output a QR code block.
 *
 * Currently, only via Google Chart API is supported, but it has
 * room to add other sources of qrcode generation.
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 */
class Pas_View_Helper_QrCode extends Zend_View_Helper_Abstract {
    
	protected $template = '%s';
 
	const APIURL = 'http://chart.apis.google.com/chart';
    /**
     * Constructor.
     *
     * @return  Pas_View_Helper_QrCode
     */
    public function qrCode($template = null) {
        if (null !== $template) {
            $this->template = $template;
        }
        return $this;
    }
 
    /**
     * Generate the QR code image via Google's Chart API.
     *
     * @param  array  $params
     * @return string
     */
    public function google($params = array())  {
	$default = array(
//	'text'       => $_SERVER['SCRIPT_URI'],
	'text'		 => $this->_view->curUrl(),
	'size'       => '250x250',
	'correction' => 'M',
	'margin'     => 0
	);
	$params = array_merge($default, $params);
	$params['text']   = urlencode($params['text']);
	$params['margin'] = (int)$params['margin'];
	if (!in_array($params['correction'], array('L', 'M', 'Q', 'H'))) {
	$params['correction'] = 'M';
	}
	if (!preg_match('/^\d+x\d+$/', $params['size'])) {
	$params['size'] = '100x100';
	}
 
	$url = self::APIURL 
	. '?cht=qr&chl=' 
	. $params['text'] 
	. '&chld=' 
	. $params['correction'] 
	. '|' 
	. $params['margin'] 
	. '&chs=' 
	. $params['size'];
	return sprintf($this->template, $url);
    }
}