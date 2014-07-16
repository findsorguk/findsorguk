<?php
/** Form for adding a profile photo to a user's account (staff only)
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license    GNU General Public License
 * @version 1
*/
class AddStaffPhotoForm extends Pas_Form {
    /** The constructor
     * @access public
     * @param array $options
     * @return void 
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setAttrib('enctype', 'multipart/form-data');

	$this->setName('AddAvatar');

	$avatar = new Zend_Form_Element_File('image');
	$avatar->setLabel('Upload staff photo: ')
		->setRequired(true)
		->setDestination('./assets/staffphotos/')
                ->addValidator('NotEmpty')
                ->addValidator('Size', false, 2097152)
            	->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif')
                ->setMaxFileSize(2097152)
		->setAttribs(array('class'=> 'textInput'))
		->addValidator('Count', false, array('min' => 1, 'max' => 1))
                ->setDescription('We only accept JPG, TIFF, PNG or GIF files of
                    2MB or less');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Upload a photo');

	$this->addElements(array($avatar,$submit, $hash))
	->setLegend('Add an active denomination');

	$this->addDisplayGroup(array('image'), 'details');

	$this->details->setLegend('Add a staff photograph: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
    }

}