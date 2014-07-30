<?php
/** Form for uploading images
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new ImageForm();
 * $form->submit->setLabel('Submit a new image.');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/ImagesController.php
 * @todo This needs replacing when we build the drag and drop
 * @uses Counties
 * @uses Periods
 * @uses Copyrights
 * @uses LicenseTypes
 */

class ImageForm extends Pas_Form {

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

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	$counties = new Counties();
	$county_options = $counties->getCountyname2();

	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

	$copyrights = new Copyrights();
	$copy = $copyrights->getTypes();

	$licenses = new LicenseTypes();
	$license = $licenses->getList();

	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth;

	if($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            if(!is_null($user->copyright)){
                $this->_copyright = $user->copyright;
            } elseif(!is_null($user->fullname)) {
                $this->_copyright = $user->first_name . ' ' . $user->last_name;

            } else {
                $this->_copyright = $user->fullname;
            }
	}

	$copyList = array_filter(array_merge(
                array($this->_copyright => $this->_copyright), $copy
                ));

        parent::__construct($options);

	$this->setName('imagetofind');

	$image = new Zend_Form_Element_File('filename');
	$image->setLabel('Upload an image: ')
                ->setRequired(true)
                ->setAttribs(array('size' => 20, 'class' => 'required'))
                ->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif,tiff,JPG,JPEG,GIF,PNG,TIFF,TIF')
                ->setDescription('Filename should not include spaces,commas,( or )')
                ->addErrorMessage('You must upload a file with the correct file extension in this array - jpeg,tif,jpg,png,gif');

	$imagelabel = new Zend_Form_Element_Text('label');
	$imagelabel->setLabel('Image label: ')
		->setRequired(true)
		->setAttribs(array('size' => 60, 'class' => 'span6 required'))
		->addErrorMessage('You must enter a label')
		->setDescription('This must be descriptive text about the image - NOT THE FILE or FIND NUMBER/NAME - and follow the
		conventions outlined below this form')
		->addFilters(array('StripTags','StringTrim'));

	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
		->setRequired(true)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow required')
		->addErrorMessage('You must enter a period for the image')
		->addMultiOptions(array(
                    null => 'Select a period',
                    'Valid periods' => $period_options
                ))
		->addValidator('inArray', false, array(array_keys($period_options)));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow required')
		->setRequired(true)
		->addErrorMessage('You must enter a county of origin')
		->addMultiOptions(array(
                    null => 'Select a county of origin',
                    'Valid counties' => $county_options
                ))
		->addValidator('inArray', false, array(array_keys($county_options)));

	$copyright = new Zend_Form_Element_Select('imagerights');
	$copyright->setLabel('Image copyright: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRequired(true)
		->addErrorMessage('You must enter a licence holder')
		->addMultiOptions(array(
                    null => 'Select a licence holder',
                    'Valid copyrights' => $copyList
                ))
		->setDescription('You can set the copyright of your image here
                    to your institution. If you are a public recorder, it should
                    default to your full name. For institutions that do not
                    appear contact head office to suggest its addition')
		->setValue($this->_copyright);

	$licenseField = new Zend_Form_Element_Select('ccLicense');
	$licenseField->setDescription('Our philosophy is to make our content available openly, by default we set the license as
	use by attribution to gain the best public benefit. You can choose a different license if you wish.');
	$licenseField->setRequired(true)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setLabel('Creative Commons license:')
		->addMultiOptions(array(
                    null => 'Select a license',
                    'Available licenses' => $license
                ))
		->setValue(5)
		->addValidator('Int');

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Image type: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRequired(true)
		->addMultiOptions(array(
                    null => 'Select the type of image',
                    'Image types' => array(
                        'digital' => 'Digital image',
                        'illustration' => 'Scanned illustration'
                        )
                    ))
		->setValue('digital');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
            $image, $imagelabel, $county,
            $period, $copyright, $type,
            $licenseField, $submit
                ));

	$this->setMethod('post');

        $this->addDisplayGroup(array(
            'filename', 'label', 'county',
            'period', 'imagerights', 'ccLicense',
            'type'),'details');

	$this->addDisplayGroup(array('submit'), 'buttons')->removeDecorator('HtmlTag');

        $this->details->setLegend('Attach an image');

	parent::init();
    }
}