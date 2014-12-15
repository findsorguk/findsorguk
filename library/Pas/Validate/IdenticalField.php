<?php

/** A validator for identical field matching
 *
 * @category   Pas
 * @package    Pas_Validate
 * @copyright  This work is licenced under a Attribution Non-commercial Share Alike Creative Commons licence
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 * @copyright  This work is licenced under a Attribution Non-commercial Share Alike Creative Commons licence
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 * @example /app/forms/ChangePasswordForm.php
 */
class Pas_Validate_IdenticalField extends Zend_Validate_Abstract
{

    /** The not matching constant
     *
     */
    const NOT_MATCH = 'notMatch';

    /** The missing field name constant
     *
     */
    const MISSING_FIELD_NAME = 'missingFieldName';

    /** The invalid field name constant
     *
     */
    const INVALID_FIELD_NAME = 'invalidFieldName';

    /** An array of messages for the error constants
     * @access protected
     * @var array
     */

    protected $_messageTemplates = array(
        self::MISSING_FIELD_NAME =>
            'A field for comparison to be run on was not entered',
        self::INVALID_FIELD_NAME =>
            'The field "%fieldName%" was not provided to match against.',
        self::NOT_MATCH =>
            'Does not match %fieldTitle%.'
    );

    /** The message variable array
     * @access protected
     * @var array
     */
    protected $_messageVariables = array(
        'fieldName' => '_fieldName',
        'fieldTitle' => '_fieldTitle'
    );

    /** The name of the field to compare
     * @access protected
     * @var string
     */
    protected $_fieldName;

    /** Title of the field to display in an error message.
     * If evaluates to false then will be set to $this->_fieldName.
     * @access protected
     * @var string
     */
    protected $_fieldTitle;

    /** Sets validator options
     * @access public
     * @param  string $fieldName
     * @param  string $fieldTitle
     * @return void
     */
    public function __construct($fieldName, $fieldTitle = null)
    {
        $this->setFieldName($fieldName);
        $this->setFieldTitle($fieldTitle);
    }

    /** Returns the field name.
     * @access public
     * @return string
     */
    public function getFieldName()
    {
        return $this->_fieldName;
    }

    /** Sets the field name.
     * @access public
     * @param string $fieldName
     * @return \Pas_Validate_IdenticalField
     */
    public function setFieldName($fieldName)
    {
        $this->_fieldName = $fieldName;
        return $this;
    }

    /** Returns the field title.
     * @access public
     * @return string
     */
    public function getFieldTitle()
    {
        return $this->_fieldTitle;
    }

    /** Sets the field title.
     * @access public
     * @param string $fieldTitle
     * @return \Pas_Validate_IdenticalField
     */
    public function setFieldTitle($fieldTitle = null)
    {
        $this->_fieldTitle = $fieldTitle ? $fieldTitle : $this->_fieldName;
        return $this;
    }

    /** Defined by Zend_Validate_Interface
     * Returns true if and only if a field name has been set.
     * @access public
     * @param string $value
     * @param string $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        $field = $this->getFieldName();

        if (empty($field)) {
            $this->_error(self::MISSING_FIELD_NAME);
            return false;
        } elseif (!isset($context[$field])) {
            $this->_error(self::INVALID_FIELD_NAME);
            return false;
        } elseif (is_array($context)) {
            if ($value == $context[$field]) {
                return true;
            }
        } elseif (is_string($context) && ($value == $context)) {
            return true;
        }
        $this->_error(self::NOT_MATCH);
        return false;
    }
}