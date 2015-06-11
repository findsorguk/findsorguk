<?php

/**
 * An example of code:
 *
 * <code>
 * <?php
 * $form = new MetaDataForm();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class MetaDataForm extends Pas_Form
{

    /** The auth object
     * @access protected
     * @var \Zend_Auth
     */
    protected $_auth;

    /** The copyright notice
     * @access protected
     * @var string
     */
    protected $_copyright;

    /** Get the county dropdown
     * @access public
     * @return array
     */
    public function getCounties()
    {
        $counties = new OsCounties();
        return $counties->getCountyNames();
    }

    /** Get the image copyrights for a user
     * @access public
     * @return array
     */
    public function getCopyrights()
    {
        $copyrights = new Copyrights();
        $copy = $copyrights->getTypes();

        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            if (!is_null($user->copyright)) {
                $this->_copyright = $user->copyright;
            } elseif (!is_null($user->fullname)) {
                $this->_copyright = $user->first_name . ' ' . $user->last_name;
            } else {
                $this->_copyright = $user->fullname;
            }
            if (!is_null($user->fullname)) {
                $copy[] = $user->first_name . ' ' . $user->last_name;
            } else {
                $copy[] = $user->fullname;
            }
        }

        $copyList = array_filter(array_merge(
            array($this->_copyright => $this->_copyright), $copy
        ));
        return $copyList;
    }

    /** Get the periods dropdown
     * @access public
     * @return array
     */
    public function getPeriods()
    {
        $periods = new Periods();
        return $periods->getPeriodFrom();
    }

    /** Get the array of image licences
     * @access public
     * @return array
     */
    public function getLicenses()
    {
        $licenses = new LicenseTypes();
        return $licenses->getList();
    }

    /** Init the form and display fields
     * @access public
     * @return void
     */
    public function init()
    {
        // Create user sub form: username and password
        $user = new Zend_Form_SubForm();


        // Create demographics sub form: given name, family name, and
        // location
        $meta = new Zend_Form_SubForm();
        $period = new Zend_Form_Element_Select('period');
        $period->setLabel('Period: ')
            ->setRequired(true)
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow required')
            ->addErrorMessage('You must enter a period for the image')
            ->addMultiOptions(array(
                null => 'Select a period',
                'Valid periods' => $this->getPeriods()
            ))
            ->addValidator('inArray', false, array(array_keys($this->getPeriods())));

        $country = new Zend_Form_Element_Select('country');
        $country->setLabel('Country: ')
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow required')
            ->setRequired(true)
            ->addErrorMessage('You must enter a country of origin')
            ->addMultiOptions(array(
                null => 'Select a country of origin',
                'Valid countries' => array('England' => 'England', 'Wales' => 'Wales')
            ))
            ->addValidator('inArray', false, array(array_keys(array('England' => 'England', 'Wales' => 'Wales'))));

        $county = new Zend_Form_Element_Select('county');
        $county->setLabel('County: ')
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow required')
            ->setRequired(true)
            ->addErrorMessage('You must enter a county of origin')
            ->addMultiOptions(array(
                null => 'Select a county of origin',
                'Valid counties' => $this->getCounties()
            ))
            ->addValidator('inArray', false, array(array_keys($this->getCounties())));

        $meta->addElements(array($period, $country, $county));


        $images = new Zend_Form_SubForm();

        // Attach sub forms to main form
        $this->addSubForms(array(
            'metadata' => $meta,
            'images' => $images,
        ));
    }

    /** Prepare subform
     * @param string $spec
     * @access public
     * @return void
     */
    public function prepareSubForm($spec)
    {
        if (is_string($spec)) {
            $subForm = $this->{$spec};
        } elseif ($spec instanceof Zend_Form_SubForm) {
            $subForm = $spec;
        } else {
            throw new Exception('Invalid argument passed to ' .
                __FUNCTION__ . '()');
        }
        $this->setSubFormDecorators($subForm)
            ->addSubmitButton($subForm)
            ->addSubFormActions($subForm);
        return $subForm;
    }

//    /**
//     * Add form decorators to an individual sub form
//     *
//     * @param  Zend_Form_SubForm $subForm
//     * @return My_Form_Registration
//     */
//    public function setSubFormDecorators(Zend_Form_SubForm $subForm)
//    {
//        $subForm->setDecorators(array(
//            'FormElements',
//            array('HtmlTag', array('tag' => 'dl',
//                'class' => 'zend_form')),
//            'Form',
//        ));
//        return $this;
//    }

    /**
     * Add a submit button to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubmitButton(Zend_Form_SubForm $subForm)
    {
        $subForm->addElement(new Zend_Form_Element_Submit(
            'save',
            array(
                'label' => 'Save and continue',
                'required' => false,
                'ignore' => true,
            )
        ));
        return $this;
    }

    /**
     * Add action and method to sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubFormActions(Zend_Form_SubForm $subForm)
    {
        $subForm->setAction('/registration/process')
            ->setMethod('post');
        return $this;
    }
}