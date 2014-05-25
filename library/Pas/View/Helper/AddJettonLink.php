<?php
/** A view helper for determining whether coin link should be printed
 * @category Pas
 * @package Pas_View_Helper
 * @todo streamline code
 * @todo extend the view helper for auth and config objects
 * @copyright DEJ Pett
 * @license GNU
 * @version 1
 * @since 29 September 2011
 * @author dpett
 */
class Pas_View_Helper_AddJettonLink extends Zend_View_Helper_Abstract
{
    protected $_noaccess = array('public', NULL);
    protected $_restricted = array('member','research','hero');
    protected $_recorders = array('flos');
    protected $_higherLevel = array('admin','fa','treasure');
    protected $_findID;
    protected $_institution;
    protected $_secuid;
    protected $_broadperiod;
    protected $_createdBy;
    protected $_canCreate;
    protected $_missingGroup = 'User is not assigned to a group';
    protected $_message = 'You are not allowed edit rights to this record';

    /** Get the current user to check
     *
     * @return object
     */
    protected function _getUser()
    {
        $person = new Pas_User_Details();

        return $person->getPerson();
    }

    /** Function to check whether the institution of creator == user's
     *
     * @return boolean
     */
    protected function _checkInstitution()
    {
        if ($this->_institution === $this->_getUser()->institution) {
            return true;
        } else {
            return false;
    }
    }

    /** Function to check creator of record against user's id
     *
     * @return boolean
     */
    protected function _checkCreator()
    {
        $userid = (int) $this->_getUser()->id;
        if ($this->_createdBy === $userid) {
            return true;

        } else {
            return false;

        }
    }

    /** Set the find ID
     *
     * @param  int                            $findID
     * @return \Pas_View_Helper_AddJettonLink
     * @throws Zend_Exception
     */
    public function setFindID($findID)
    {
        if (is_int($findID)) {
            $this->_findID = $findID;

        } else {
            throw new Zend_Exception('The find ID must be an integer', 500);
    }

        return $this;
    }

    /** Function to set the secuid
     *
     * @param  string                         $secuid
     * @return \Pas_View_Helper_AddJettonLink
     * @throws Zend_Exception
     */
    public function setSecUid( $secuid)
    {
        if (is_string($secuid)) {
            $this->_secuid = $secuid;

        } else {
            throw new Zend_Exception('The secure id set must be a string', 500);
    }

        return $this;
    }

    /** Function to set the broadperiod
     *
     * @param  string                         $broadperiod
     * @return \Pas_View_Helper_AddJettonLink
     * @throws Zend_Exception
     */
    public function setBroadperiod( $broadperiod)
    {
        if (is_string($broadperiod)) {
            $this->_broadperiod = $broadperiod;

        } else {
            throw new Zend_Exception('The broadperiod set must be a string', 500);
    }

        return $this;
    }

    /** Function to set the institution
     *
     * @param  string                         $institution
     * @return \Pas_View_Helper_AddJettonLink
     * @throws Zend_Exception
     */
    public function setInstitution( $institution)
    {
        if (is_string($institution)) {
            $this->_institution = $institution;

        } else {
            throw new Zend_Exception('The institution must be a string', 500);
    }

    return $this;
    }

    /** Function to set created by
     *
     * @param  int                            $createdBy
     * @return \Pas_View_Helper_AddJettonLink
     * @throws Zend_Exception
     */
    public function setCreatedBy($createdBy)
    {
        if (is_int($createdBy)) {
            $this->_createdBy = $createdBy;

        } else {
            throw new Zend_Exception('The creator must be an integer', 500);
    }

    return $this;
    }

    /** Function to check that all parameters are set
     *
     * @return boolean
     * @throws Zend_Exception
     */
    private function _checkParameters()
    {
        $parameters = array(
            $this->_broadperiod,
            $this->_createdBy,
            $this->_findID,
            $this->_secuid);
        foreach ($parameters as $parameter) {
            if ( is_null( $parameter ) ) {
                throw new Zend_Exception('A parameter is missing');
            }
            }

            return true;
    }

    /** Function to run internal checks
     *
     * @return \Pas_View_Helper_AddJettonLink
     */
    private function _performChecks()
    {
        $user = $this->_getUser();
    if ($user) {
            $role = $user->role;
        } else {
            $role = null;
    }
        if ( in_array( $role, $this->_restricted ) ) {
            if( ( $this->_checkCreator() && !$this->_checkInstitution() )
                    || ( $this->_checkCreator() && $this->_checkInstitution() ) ) {
                $this->_canCreate = true;
                }
                    } elseif (in_array($role,$this->_higherLevel)) {
                        $this->_canCreate = true;
                    } elseif (in_array($role,$this->_recorders)) {
                        if( ( $this->_checkCreator() && !$this->_checkInstitution() )
                                || ( $this->_checkCreator() && $this->_checkInstitution() )
                                || ( !$this->_checkCreator() && $this->_checkInstitution() )
                                || ( !$this->_checkCreator() && $this->_institution === 'PUBLIC' ) ) {
                            $this->_canCreate = true;
                                }
                                } else {
                                    $this->_canCreate = false;
                                }

    return $this;
    }

    /** Function to add the coin link html
     *
     * @return \Pas_View_Helper_AddJettonLink
     */
    public function addJettonLink()
    {
        return $this;

    }

    /** Function to return the html
     * @todo might be worth moving the html to a partial
     * @return string
     */
    private function _buildHtml()
    {
        $this->_checkParameters();
        $this->_performChecks();
        if ($this->_canCreate) {
            $params = array(
                'module' => 'database',
        'controller' => 'jettons',
        'action' => 'add',
        'broadperiod' => $this->_broadperiod,
        'findID' => $this->_secuid,
        'returnID' => $this->_findID
    );
    $url = $this->view->url($params,NULL,TRUE);
    $string = '<a class="btn btn-primary" href="' . $url . '" title="Add '
    . $this->_broadperiod . ' coin data" accesskey="m">Add ' . $this->_broadperiod
    .' token or jetton data</a>';

        return $string;

        } else {
            return '';
    }
    }

    /** Function magic method to return string
     *
     * @return string function
     */
    public function __toString()
    {
        return $this->_buildHtml();
    }
}
