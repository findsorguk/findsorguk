<?php
/** Form for editing and adding images
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ImageEditForm extends Pas_Form {
	
	protected $_auth = NULL;

	protected $_copyright = NULL;
	
	public function __construct($options = null) {
	$counties = new Counties();
	$county_options = $counties->getCountyname2();
	
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();
	
	$copyrights = new Copyrights();
	$copy = $copyrights->getStyles();
	
	$licenses = new LicenseTypes();
	$license = $licenses->getList();

	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth;
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	
	if(!is_null($user->copyright)){
	$this->_copyright = $user->copyright;
		} elseif(!is_null($user->fullname)) {
			$this->_copyright = $user->forename . ' ' . $user->surname;
		} else {
			$this->_copyright = $user->fullname;
		}
	} 
	
	parent::__construct($options);
	$copyList = array_filter(array_merge(array($this->_copyright => $this->_copyright), $copy));
	$this->setName('imageeditfind');


	$imagelabel = new Zend_Form_Element_Text('label');
	$imagelabel->setLabel('Image label')
		->setRequired(true)
		->setAttribs(array('size' => 70, 'class' => 'span6' ))
		->addErrorMessage('You must enter a label')
		->addFilters(array('StringTrim','StripTags'));
		
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(true)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => NULL,'Choose a period' => $period_options))
		->addValidator('inArray', false, array(array_keys($period_options)));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(NULL => NULL,'Choose a county' => $county_options))
		->addValidator('inArray', false, array(array_keys($county_options)));

	$copyright = new Zend_Form_Element_Select('copyrighttext');
	$copyright->setLabel('Image copyright: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(true)
		->addErrorMessage('You must enter a licence holder')
		->addMultiOptions(array(NULL => 'Select a licence holder','Valid copyrights' => $copyList))
		->setDescription('You can set the copyright of your image here to your institution. If you are a public recorder, it 
		should default to your full name. For institutions that do not appear contact head office for getting it added.')
		->setValue($this->_copyright);
	
	$licenseField = new Zend_Form_Element_Select('ccLicense');
	$licenseField->setDescription('Our philosophy is to make our content available openly, by default we set the license as
	use by attribution to gain the best public benefit. You can choose a different license if you wish.');
	$licenseField->setRequired(true)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setLabel('Creative Commons license:')
		->addMultiOptions(array(NULL => 'Select a license', 'Available licenses' => $license))
		->setValue(5)
		->addValidator('Int');
		
	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Image type: ')
		->setRequired(true)
		->addMultiOptions(array('Please choose publish state' => array(
		'digital' => 'Digital image', 'illustration' => 'Scanned illustration')))
		->setValue('digital')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'));;

	$rotate = new Zend_Form_Element_Radio('rotate');
	$rotate->setLabel('Rotate the image: ')
		->setRequired(false)
		->addValidator('Int')
		->addMultiOptions(array(
		'-90' => '90 degrees anticlockwise', '-180' => '180 degrees anticlockwise', 
		'-270' => '270 degrees anticlockwise', '90' => '90 degrees clockwise',
		'180' => '180 degrees clockwise', '270' => '270 degrees clockwise'));

	$regenerate = new Zend_Form_Element_Checkbox('regenerate');
	$regenerate->setLabel('Regenerate thumbnail: ');

	$filename = new Zend_Form_Element_Hidden('filename');
	$filename->removeDecorator('label')
	->addFilters(array('StringTrim','StripTags'));
		   
	$imagedir = new Zend_Form_Element_Hidden('imagedir');

				
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$imagelabel, $county, $period,
	$copyright, $licenseField, $type, $rotate,
	$regenerate, $filename, $imagedir,
	$submit));

	$this->setMethod('post');
	
	$this->addDisplayGroup(array(
	'label', 'county', 'period',
	'imagerights', 'copyrighttext', 'ccLicense','type', 'rotate',
	'regenerate'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	$this->details->setLegend('Attach an image');

	parent::init();
	}
}