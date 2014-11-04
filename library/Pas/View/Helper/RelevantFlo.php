<?php

/** A view helper to get the correct flo for an error report
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->relevantFlo()
 * ->setFindID($findID);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @copyright (c) 2014, Daniel Pett
 *
 *
 */
class Pas_View_Helper_RelevantFlo extends Zend_View_Helper_Abstract
{

    /** The find ID variable
     * @access protected
     * @var  integer
     */
    protected $_findID;

    /** Set the find id integer
     * @access public
     * @return integer
     */
    public function setFindID($findID)
    {
        $this->_findID = $findID;
        return $this;
    }

    /** Get the find ID as an integer
     * @access public
     * @return integer
     */
    public function getFindID()
    {
        return $this->_findID;
    }

    /** Get the contact responsible
     * @access public
     * @return array
     */
    public function getContacts()
    {
        $contacts = new Contacts();
        return $contacts->getOwner($this->getFindID());
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_RelevantAdviser
     */
    public function relevantFlo()
    {
        return $this;
    }


    /** The to string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml($this->getContacts());
    }

    /** Build up the html
     * @access public
     * @return string The html to return
     */
    public function buildHtml($contacts)
    {
        $html = '';
        if (!empty($contacts)) {
            $html .= '<ul>';
            foreach ($contacts as $contact) {
                $html .= '<li>';
                $html .= $contact['name'];
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}