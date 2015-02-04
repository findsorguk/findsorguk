<?php

/** Form for editing and adding images
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new ImageEditForm();
 * $form->submit->setLabel('Update image..');
 * $this->view->form = $form;
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @example /app/modules/database/controllers/ImagesController.php
 * @uses Counties
 * @uses Periods
 * @uses Copyrights
 * @uses LicenseTypes
 */
class ImageEditForm extends Pas_Form
{

    /** The auth object
     * @access public
     * @var Zend_Auth
     */
    protected $_auth;

    /** The copyright statement
     * @access public
     * @var string
     */
    protected $_copyright = NULL;

    public function getCopyright()
    {
        $auth = Zend_Auth::getInstance();
        $this->_auth = $auth;
        if ($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            if (!is_null($user->copyright)) {
                $this->_copyright = $user->copyright;
            }
        }
        return $this->_copyright;
    }

    public function getCopyrights()
    {
        $copyrights = new Copyrights();
        $copy = $copyrights->getTypes();
        $auth = Zend_Auth::getInstance();
        $this->_auth = $auth;
        if ($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            if (is_null($user->fullname)) {
                $userCopyright = $user->forename . ' ' . $user->surname;
            } else {
                $userCopyright = $user->fullname;
            }
        }
        $personal = array($userCopyright => $userCopyright);
        return array_merge($copy, $personal);
    }

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null)
    {

        $counties = new OsCounties();
        $county_options = $counties->getCountyNames();

        $periods = new Periods();
        $period_options = $periods->getPeriodFrom();

        $licenses = new LicenseTypes();
        $license = $licenses->getList();

        parent::__construct($options);

        $this->setName('imageeditfind');

        $imagelabel = new Zend_Form_Element_Text('label');
        $imagelabel->setLabel('Image label')
            ->setRequired(true)
            ->setAttribs(array('size' => 70, 'class' => 'span6'))
            ->addErrorMessage('You must enter a label')
            ->addFilters(array('StringTrim', 'StripTags'));

        $period = new Zend_Form_Element_Select('period');
        $period->setLabel('Period: ')
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->setRequired(true)
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addMultiOptions(array(
                null => 'Choose a period',
                'Available periods' => $period_options
            ))
            ->addValidator('inArray', false, array(array_keys($period_options)));

        $county = new Zend_Form_Element_Select('county');
        $county->setLabel('County: ')
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addMultiOptions(array(
                null => 'Choose a county',
                'Available counties' => $county_options
            ))
            ->addValidator('inArray', false, array(array_keys($county_options)));

        $copyright = new Zend_Form_Element_Select('imagerights');
        $copyright->setLabel('Image copyright: ')
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->setRequired(true)
            ->addErrorMessage('You must enter a licence holder')
            ->addMultiOptions(array(null => 'Select a licence holder', 'Valid copyrights' => $this->getCopyrights()))
            ->setDescription('You can set the copyright of your image here
                    to your institution. If you are a public recorder, it
                    should default to your full name. For institutions that do
                    not appear contact head office to suggest its addition.')
            ->setValue($this->getCopyright());

        $licenseField = new Zend_Form_Element_Select('ccLicense');
        $licenseField->setDescription('Our philosophy is to make our content
            available openly, by default we set the license as use by attribution
            to gain the best public benefit. You can choose a different license
            if you wish.');
        $licenseField->setRequired(true)
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->setLabel('Creative Commons license:')
            ->addMultiOptions(array(
                null => 'Select a license',
                'Available licenses' => $license
            ))
            ->setValue(4)
            ->addValidator('Int');

        $type = new Zend_Form_Element_Select('type');
        $type->setLabel('Image type: ')
            ->setRequired(true)
            ->addMultiOptions(array('Please choose publish state' => array(
                'digital' => 'Digital image', 'illustration' => 'Scanned illustration')))
            ->setValue('digital')
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addFilters(array('StringTrim', 'StripTags'));;

        $rotate = new Zend_Form_Element_Radio('rotate');
        $rotate->setLabel('Rotate the image: ')
            ->setRequired(false)
            ->addValidator('Int')
            ->addMultiOptions(array(
                -90 => '90 degrees anticlockwise',
                -180 => '180 degrees anticlockwise',
                -270 => '270 degrees anticlockwise',
                90 => '90 degrees clockwise',
                180 => '180 degrees clockwise',
                270 => '270 degrees clockwise'
            ));

        $regenerate = new Zend_Form_Element_Checkbox('regenerate');
        $regenerate->setLabel('Regenerate thumbnail: ');

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array(
            $imagelabel, $county, $period,
            $copyright, $licenseField, $type,
//            $rotate, $regenerate,
            $submit
        ));

        $this->setMethod('post');

        $this->addDisplayGroup(array(
            'label', 'county', 'period',
            'imagerights', 'ccLicense',
            'type',
//            'rotate', 'regenerate'
        ),
            'details');

        $this->addDisplayGroup(array('submit'), 'buttons');
        $this->details->setLegend('Edit metadata on an image');

        parent::init();
    }
}