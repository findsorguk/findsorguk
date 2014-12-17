<?php

/**
 * A view helper to provide a link url for the analytics page.
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->analyticsLink();
 * ?>
 * </code>
 * @package PAS
 * @uses Zend_View_Helper_Abstract
 * @uses Pas_User_Details
 * @uses Pas_View_Helper_CurUrl
 * @version 1
 * @since 19/5/2014
 * @copyright Daniel Pett <dpett@britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 *
 */
class Pas_View_Helper_AnalyticsLink extends Zend_View_Helper_Abstract
{
    /** The delimiter between the string for the url
     * @var string
     * @access protected
     */
    protected $_delimiter = '/';

    /** The default role
     * @access protected
     * @var string
     */
    protected $_role = 'public';

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
     * @param  string $delimiter
     * @return \Pas_View_Helper_AnalyticsLink
     */
    public function setDelimiter($delimiter)
    {
        $this->_delimiter = $delimiter;
        return $this;
    }

    /** Function to get the user role and determine whether to proceed
     * @access public
     * @return string $_role;
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
            $this->_role = $person->role;
        }
        return $this->_role;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_AnalyticsLink
     */
    public function analyticsLink()
    {
        return $this;
    }

    /** Get the current page url
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
        return $this->_delimiter . substr($path, 1);
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
     * @access public
     * @return string
     */
    public function url()
    {
        $html = '';
        if ($this->getRole()) {
            $params = array(
                'module' => 'analytics',
                'controller' => 'content',
                'action' => 'page',
                'url' => rawurlencode($this->encodePath())
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
