<?php
/**
 * Created by PhpStorm.
 * User: danielpett
 * Date: 12/10/2014
 * Time: 08:16
 *
 * @todo Add in all the checking logic for who can edit.
 */
class Pas_View_Helper_EditDeleteSummary extends Zend_View_Helper_Abstract {

    /** The ID number of the record
     * @access protected
     * @var  $_ID
     */
    protected $_ID;

    /** The user's role
     * @access protected
     * @var $_role
     */
    protected $_role;

    /** The hoardID to return to
     * @access protected
     * @var $_hoardID
     */
    protected $_hoardID;

    /** The array of allowed IDs
     * @access protected
     * @var array $_allowed
     */
    protected $_allowed = array(
        'fa', 'hoard', 'flos',
        'treasure', 'admin'
    );

    /** The array of roles that have restricted access
     * @access protected
     * @var array $_restricted
     */
    protected $_restricted = array('member', 'research');

    /** Get the user role
     * @return string
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        $this->_role = $user->getRole();
        return $this->_role;
    }

    /** Get the hoard ID to return to
     * @return mixed
     */
    public function getHoardID()
    {
        return $this->_hoardID;
    }

    /** Set the hoard ID to return to
     * @access public
     * @param mixed $hoardID
     */
    public function setHoardID($hoardID)
    {
        $this->_hoardID = $hoardID;
        return $this;
    }

    /** Get the ID of the summary record to work on
     * @access public
     * @return mixed
     */
    public function getID()
    {
        return $this->_ID;
    }

    /** Set the ID of the record
     * @access public
     * @param mixed $ID
     */
    public function setID($ID)
    {
        $this->_ID = $ID;
        return $this;
    }

    /** The view helper function
     * @access public
     * @return \Pas_View_Helper_Edit_Delete_Summary
     */
    public function editDeleteSummary()
    {
        return $this;
    }

    /** Return the html string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml();
    }

    /** Build the html to return as a string
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $html = '';
        $html .= $this->view->partial('partials/hoards/coinSummaryEdit.phtml', array(
            'id' => $this->getID(), 'hoardID' =>  $this->getHoardID()
            )
        );
        return $html;
    }
}