<?php
/**
 * Created by PhpStorm.
 * User: danielpett
 * Date: 07/01/15
 * Time: 14:33
 */

class Pas_View_Helper_SketchFabAddEditDeleteLink extends Zend_View_Helper_Abstract {

    protected $_findID;

    protected $_createdBy;

    protected $_institution;

    protected $_type = 'artefacts';

    protected $_returnID;

    /**
     * @return mixed
     */
    public function getFindID()
    {
        return $this->_findID;
    }

    /**
     * @param mixed $findID
     */
    public function setFindID($findID)
    {
        $this->_findID = $findID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->_createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstitution()
    {
        return $this->_institution;
    }

    /**
     * @param mixed $institution
     */
    public function setInstitution($institution)
    {
        $this->_institution = $institution;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnID()
    {
        return $this->_returnID;
    }

    /**
     * @param mixed $returnID
     */
    public function setReturnID($returnID)
    {
        $this->_returnID = $returnID;
        return $this;
    }



    public function sketchFabAddEditDeleteLink()
    {
        return $this;
    }

    public function __toString()
    {
        $html = '';

        return $html;
    }
}