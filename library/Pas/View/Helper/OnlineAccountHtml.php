<?php

/**
 * A view helper for displaying the online accounts in html format
 * Example of use:
 * <code>
 * <?php
 * echo $this->onlineAccountHtml()->setId($id);
 * ?>
 * </code>
 *
 * @author     Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see        Zend_View_Helper_Abstract
 */
class Pas_View_Helper_OnlineAccountHtml extends Zend_View_Helper_Abstract
{

    /** The id number to query
     *
     * @access public
     * @var int
     */
    protected $_id;

    /** Get the ID number
     *
     * @access public
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /** Set the ID number
     *
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_OnlineAccountHtml
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /** Retrieve a person's online accounts
     *
     * @access public
     * @return \Pas_View_Helper_OnlineAccountHtml
     */
    public function OnlineAccountHtml()
    {
        return $this;
    }

    /** Get the data from the model
     *
     * @access public
     * @param int $id
     * @return array
     */
    public function getData($id)
    {
        $accts = new OnlineAccounts();
        return $accts->getAccounts($id);
    }

    /** To string function
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->buildHtml($this->getData($this->getId()));
        } catch (Exception $e) {
        }
    }

    /** Build HTML response
     *
     * @access public
     * @param array $data
     * @return string $html
     */
    public function buildHtml($data)
    {
        $html = '';
        if ($data) {
            $html .= '<br />';
            $html .= '<p><strong>Social profiles:</strong></p>';
            $html .= '<div class="btn-group">';
            $html .= $this->view->partialLoop('partials/contacts/foafAccts.phtml', $data);
            $html .= '</div>';
        }
        return $html;
    }

}
