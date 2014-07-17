<?php
/** Form for adding an image to a ruler's biographical page
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/
class AddRulerImageForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

	parent::__construct($options);

	$this->setAttrib('enctype', 'multipart/form-data');

	$this->setName('AddRulerImage');

	$image = new Zend_Form_Element_File('image');
	$image->setLabel('Upload an image: ')
                ->setRequired(true)
		->setDestination('./assets/rulers/')
		->addValidator('Size', false, 2097152 )
		->addValidator('Extension', false, 
                        'jpeg,tif,jpg,png,gif,JPG,TIFF')
		->setMaxFileSize(1024000)
		->setAttribs(array('class'=> 'textInput'))
		->addValidator('Count', false, array('min' => 1, 'max' => 1))
		->addDecorator('File');

	$caption = new Zend_Form_Element_Text('caption');
	$caption->setLabel('Image caption')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a label');

	$rulerID = new Zend_Form_Element_Hidden('rulerID');
	$rulerID ->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addValidator('Int')
		->setRequired(true);

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Upload an image of a ruler')
                ->setAttribs(array('class'=> 'large'));

	$this->addElements(array($image, $rulerID, $caption, $submit, $hash))
	->setLegend('Add an image to a ruler profile');

	$this->addDisplayGroup(array('image','caption'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}