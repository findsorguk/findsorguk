<?php

/**
 * A view helper for retrieving a slideshare set
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see  Zend_View_Helper_Abstract
 * @uses Zend_Service_SlideShare
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->slideshare()->setUsername($username)
 * ->setSecret($secret)
 * ->setPassword($password)
 * ->setLimit(5)
 * ->setOffset(1)
 * ->setKey($key)
 * ->setUserID($id);
 * ?>
 * </code>
 */
class Pas_View_Helper_Slideshare extends Zend_View_Helper_Abstract
{

    /** The password
     * @var  null
     */
    protected $_password = NULL;

    /** The username
     * @var null
     */
    protected $_username = NULL;

    /** The secret
     * @var null
     */
    protected $_secret = NULL;

    /** The key
     * @var null
     */
    protected $_key = NULL;

    /** The user id
     * @var null
     */
    protected $_userID = NULL;

    /** The offset
     * @var int
     */
    protected $_offset = 0;

    /** The limit
     * @var int
     */
    protected $_limit = 4;

    /** Get the userid for slideshare
     * @access public
     * @return null
     */
    public function getUserID()
    {
        return $this->_userID;
    }

    /** Set the slideshare userid
     * @access public
     * @param null $userID
     */
    public function setUserID($userID)
    {
        $this->_userID = $userID;
        return $this;
    }

    /** Get the slideshare api key
     * @access public
     * @return mixed
     */
    public function getKey()
    {
        return $this->_key;
    }

    /** Set the slideshare api key
     * @access public
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->_key = $key;
        return $this;
    }

    /** Get the password to query
     * @access public
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /** Set the password
     * @access public
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }

    /** Get the slideshare api secret
     * @access public
     * @return mixed
     */
    public function getSecret()
    {
        return $this->_secret;
    }

    /** Set the slideshare api secret
     * @access public
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->_secret = $secret;
    }

    /** Get the username to query
     * @access public
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /** Set the username to query
     * @access public
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }

    /** Get the limit to return
     * @access public
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /** Set the limit to show
     * @access public
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    /** Get the offset
     * @access public
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /** Set the offset
     * @access public
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;
        return $this;
    }

    /** Build HTML response based on slideshare array
     * @access public
     * @param  string $ss_user
     * @return string $html
     */
    public function buildHtml($shows)
    {
        $html = '';
        if (!empty($shows)) {
            $html .= '<div class="row"><h3 class="lead">Most recent presentations</h3>';
            $html .= '<ul class="thumbnails">';
            $html .= $this->view->partialLoop('partials/database/thirdParty/slideshare.phtml', $shows);
            $html .= '</ul>';
            $html .= '</div>';
        }
        return $html;
    }

    /** Get the data array from slideshare's api
     * @access public
     * @return array $shows
     */
    public function getSlideshareData()
    {
        $shows = array();
        $online = new OnlineAccounts();
        $ssid = $online->getSlideshare($this->getUserID());
        if (!empty($ssid)) {
            $ssidno = $ssid['0']['account'];
            $slideshare = new Zend_Service_SlideShare($this->getKey(), $this->getSecret(), $this->getUsername(), $this->getPassword());
            $data = $slideshare->getSlideShowsByUserName($ssidno, $this->getOffset(), $this->getLimit());
            foreach ($data as $slideshow) {
                $shows[] = array(
                    'title' => $slideshow->getTitle(),
                    'permalink' => $slideshow->getPermalink(),
                    'thumbnail' => $slideshow->getThumbnailUrl(),
                    'views' => $slideshow->getNumViews()

                );
            }
        }
        return $shows;
    }

    /** The slideshare function to return
     * @access public
     *
     */
    public function slideshare()
    {
        return $this;
    }

    /** The to string function
     * @access public
     */
    public function __toString()
    {
        return $this->buildHtml($this->getSlideshareData());
    }
}