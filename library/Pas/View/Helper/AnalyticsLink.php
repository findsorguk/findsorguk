<?php
/**
 * A view helper to provide a link url for the analytics page.
 *
 * @package PAS
 * @uses Zend_View_Helper_Abstract
 * @uses Pas_User_Details
 * @uses Pas_View_Helper_CurUrl
 * @author dpett <dpett@britishmuseum.org>
 * @version 1
 * @since 19/5/2014
 * @copyright Daniel Pett <dpett@britishmuseum.org>
 *
 */

class Pas_View_Helper_AnalyticsLink extends Zend_View_Helper_Abstract
{
    /** The delimiter between the string for the url
     * @var string
     * @access protected
     */
    protected $_delimiter = '/';

    /** get the delimiter to use
     * @access public
     * @return string
     */
    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    /** Set the delimiter
     * @access public
     * @param  type                           $delimiter
     * @return \Pas_View_Helper_AnalyticsLink
     */
    public function setDelimiter($delimiter)
    {
        $this->_delimiter = $delimiter;

        return $this;
    }

    /** Function to get the user role and determine whether to proceed
     * @access public
     * @return string|false
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
            return $person->role;
        } else {
        return false;
    }
    }

    /**The function to return
     * @access public
     */
    public function analyticsLink()
    {
        return $this;
    }

    /** get the current page url
     * @access private
     * @return type
     */
    private function getCurUrl()
    {
        return $this->view->curUrl();
    }

    /** Get the path of the url
     * @access private
     * @return string
     */
    private function getPath()
    {
        $path = parse_url($this->getCurUrl(), PHP_URL_PATH);

        return  self::SLASH . substr($path, 1);
    }

    /** Encode the path
     * @access private
     * @return string
     */
    private function encodePath()
    {
        $raw = base64_encode($this->getPath());

        return $raw;
    }

    /** Return html for the url
     * @acces private
     * @return string
     */
    private function url()
    {
        $html = '';
        if ($this->getRole()) {
        $params = array(
                'module' 		=> 'analytics',
                'controller' 	=> 'content',
                'action'		=> 'page',
                'url'			=> rawurlencode($this->encodePath())
        );
        $url = $this->view->url($params, 'default', true);
        $html .= '<a rel="nofollow" class="btn" href="';
        $html .= $url . '">View analytics <i class="icon-signal"></i></a>';
        }

        return $html;
    }

    /** Magic string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->url();
    }
}
