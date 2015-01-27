<?php

/** A view helper for determining whether coin link should be printed.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $this->view->addCoinLink()
 * ->setFindID($id)
 * ->setCreatedBy($createBy)
 * ->setBroadperiod($broadperiod)
 * ->setInstitution($institution);
 * ?>
 * </code>
 *
 *
 * @category Pas
 * @package Pas_View_Helper
 * @copyright DEJ Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 29 September 2011
 * @author dpett
 */
class Pas_View_Helper_SketchFabAddLink extends Zend_View_Helper_Abstract
{
    /** Array of roles with limited access
     * @access protected
     * @var array
     */
    protected $_noaccess = array('public', null);

    /** Array of roles with restricted access
     * @access protected
     * @var array
     */
    protected $_restricted = array('member', 'research', 'hero');

    /** The recorders array
     * @access protected
     * @var array
     */
    protected $_recorders = array('flos');

    /** The array of foles with higher level access
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin', 'fa', 'treasure');

    protected $_type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }


    /** The find ID to use
     * @access protected
     * @var string
     */
    protected $_findID;

    /** The institution to use
     * @access protected
     * @var string
     */
    protected $_institution;

    /** The secureID to query
     * @access protected
     * @var string
     */
    protected $_returnID;

    /** The created by integer
     * @access protected
     * @var int
     */
    protected $_createdBy;

    /** Can create
     * @access protected
     * @var string
     */
    protected $_canCreate;

    /** Exception text to return for missing group
     * @access protected
     * @var string
     */
    protected $_missingGroup = 'User is not assigned to a group';

    /** The error message to throw
     * @access protected
     * @var string
     */
    protected $_message = 'You are not allowed edit rights to this record';

    /** Get the current user to check
     * @access public
     * @return object
     */
    public function getUser()
    {
        $person = new Pas_User_Details();
        return $person->getPerson();
    }

    /** The role of the user
     * @access protected
     * @var string
     */
    protected $_role = 'public';

    /** The user's id from the model
     * @access protected
     * @var int
     */
    protected $_userID = null;

    /** The user's institution from the model
     * @access protected
     * @var string
     */
    protected $_userInst = null;

    /** Get the user's institution
     * @access public
     * @return string
     */
    public function getUserInst()
    {
        if ($this->getUser()) {
            $this->_userInst = $this->getUser()->institution;
        }
        return $this->_userInst;
    }

    /** Get the user's role from the model
     * @access public
     * @return string
     */
    public function getRole()
    {
        if ($this->getUser()) {
            $this->_role = $this->getUser()->role;
        }
        return $this->_role;
    }

    /** Get the user's ID from the model
     * @access public
     * @return int
     */
    public function getUserID()
    {
        if ($this->getUser()) {
            $this->_userID = $this->getUser()->id;
        }
        return $this->_userID;
    }

    /** Get the set findID for the find
     * @access public
     * @return int
     */
    public function getFindID()
    {
        return $this->_findID;
    }

    /** Get the set institution for the find
     * @access public
     * @return string
     */
    public function getInstitution()
    {
        return $this->_institution;
    }

    /** Get the set secure ID for the find
     * @access public
     * @return string
     */
    public function getReturnID()
    {
        return $this->_returnID;
    }


    /** Get the creator of the find
     * @access public
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->_createdBy;
    }


    /** Function to check whether the institution of creator == user's
     * @access protected
     * @return boolean
     */
    protected function _checkInstitution()
    {
        if ($this->getInstitution() === $this->getUserInst()) {
            return true;
        } else {
            return false;
        }
    }

    /** Function to check creator of record against user's id
     * @access protected
     * @return boolean
     */
    protected function _checkCreator()
    {
        if ($this->getCreatedBy() === $this->getUserID()) {
            return true;
        } else {
            return false;
        }
    }

    /** Set the find ID
     * @access public
     * @param  int $findID
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setFindID($findID)
    {
        $this->_findID = $findID;
        return $this;
    }

    /** Function to set the secuid
     * @access public
     * @param  string $secuid
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setReturnID($returnID)
    {
        $this->_returnID = $returnID;
        return $this;
    }


    /** Function to set the institution
     * @access public
     * @param  string $institution
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setInstitution($institution)
    {
        $this->_institution = $institution;
        return $this;
    }

    /** Function to set created by
     * @access public
     * @param  int $createdBy
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setCreatedBy($createdBy)
    {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /** Function to check that all parameters are set
     * @access private
     * @return boolean
     * @throws Zend_Exception
     */
    private function _checkParameters()
    {
        $parameters = array(
            'createdBy' => $this->getCreatedBy(),
            'findID' => $this->getFindID(),
            'inst' => $this->getInstitution()
        );
        foreach ($parameters as $parameter => $value) {
            if (is_null($value)) {
                echo 'A parameter ' .  $parameter  . ' is missing';
            }
        }
        return true;
    }

    /** Function to run internal checks
     * @access private
     * @return \Pas_View_Helper_AddCoinLink
     */
    private function _performChecks()
    {
        if (in_array($this->getRole(), $this->_restricted)) {
            if (($this->_checkCreator() && !$this->_checkInstitution())
                || ($this->_checkCreator() && $this->_checkInstitution())
            ) {
                $this->_canCreate = true;
            }
        } elseif (in_array($this->getRole(), $this->_higherLevel)) {
            $this->_canCreate = true;
        } elseif (in_array($this->getRole(), $this->_recorders)) {
            if (($this->_checkCreator() && !$this->_checkInstitution())
                || ($this->_checkCreator() && $this->_checkInstitution())
                || (!$this->_checkCreator() && $this->_checkInstitution())
                || (!$this->_checkCreator() && $this->getUserInst() === 'PUBLIC')
            ) {
                $this->_canCreate = true;
            }
        } else {
            $this->_canCreate = false;
        }
        return $this;
    }

    /** Function to add the coin link html
     * @access public
     * @return \Pas_View_Helper_AddCoinLink
     */
    public function sketchFabAddLink()
    {
        return $this;
    }

    /** Function to return the html
     * @access private
     * @return string
     */
    private function _buildHtml()
    {
        $this->_checkParameters();
        $this->_performChecks();
        $string = '';
        if ($this->_canCreate) {
            $params = array(
                'module' => 'database',
                'controller' => 'sketchfab',
                'action' => 'add',
                'findID' => $this->getFindID(),
                'returnID' => $this->getReturnID(),
                'recordType' => $this->getType()
            );
            $url = $this->view->url($params, null, TRUE);
            $string .= '<a class="btn btn-primary btn-small" href="';
            $string .= $url;
            $string .= '" title="Add Sketchfab model">Add 3D model</a>';
        }
        return $string;
    }

    /** Function magic method to return string
     * @access public
     * @return string function
     */
    public function __toString()
    {
        return $this->_buildHtml();
    }
}
