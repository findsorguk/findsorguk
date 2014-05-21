<?php

/**
 * Output a QR code block.
 *
 * Currently, only via Google Chart API is supported.
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @uses Zend_View_Helper_CurUrl
 */
class Pas_View_Helper_QrCode extends Zend_View_Helper_Abstract
{
    protected $template = '%s';

    protected $params = array();

    /** Get the parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /** Set the parameters
     *
     * @param  array                   $params
     * @return \Pas_View_Helper_QrCode
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /** Get the template to use
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /** Set the template to use
     *
     * @param  string                  $template
     * @return \Pas_View_Helper_QrCode
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    /** The google uri to use
     *
     */
    const APIURL = 'http://chart.apis.google.com/chart';

    /** The QRcode function
     *
     * @return \Pas_View_Helper_QrCode
     */
    public function qrCode()
    {
        return $this;
    }

    /** Magic to string method
     *
     * @return string
     */
    public function toString()
    {
        return $this->google();
    }

    /** Generate the chart
     *
     * @return string
     */
    public function google()
    {
        $default = array(
            'text'  =>  $this->_view->curUrl(),
            'size'  =>  '250x250',
            'correction'    =>  'M',
            'margin'    =>  0
            );

        $parameters = array_merge($default, $this->getParams());

        $parameters['text']   = urlencode($parameters['text']);
    $parameters['margin'] = (int) $parameters['margin'];

        if (!in_array($parameters['correction'], array('L', 'M', 'Q', 'H'))) {
            $parameters['correction'] = 'M';
    }
    if (!preg_match('/^\d+x\d+$/', $parameters['size'])) {
            $parameters['size'] = '100x100';
    }

    $url = self::APIURL
    . '?cht=qr&chl=' . $parameters['text']  . '&chld='  . $parameters['correction']
    . '|'  . $parameters['margin']  . '&chs='  . $parameters['size'];

    return sprintf($this->template, $url);
    }
}
