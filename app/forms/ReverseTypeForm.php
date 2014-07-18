<?php
/** Form for manipulating Roman reverse type information 
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new ReverseTypeForm();
 * $form->submit->setLabel('Add a new reverse type');
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Reeces
 * @example /app/modules/admin/controllers/NumismaticsController.php
 * 
*/
class ReverseTypeForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
	
	$reeces = new Reeces();
	$reeces_options = $reeces->getRevTypes();
	
	parent::__construct($options);
	$this->setName('reversetype');

	$type = new Zend_Form_Element_Text('type');
	$type->setLabel('Reverse type inscription: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter an inscription.')
		->setAttrib('size',70);

	$translation = new Zend_Form_Element_Text('translation');
	$translation->setLabel('Translation: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a translation.')
		->setAttrib('size',70);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Description: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a translation.')
		->setAttrib('size',70);

	$gendate = new Zend_Form_Element_Text('gendate');
	$gendate->setLabel('General date for reverse type: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a general date for this reverse type.')
		->setAttrib('size',30);

	$reeceID = new Zend_Form_Element_Select('reeceID');
	$reeceID->setLabel('Reece period: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose a Reece period',
                    'Available Reece period' => $reeces_options))
		->addValidator('InArray', false, 
                        array(array_keys($reeces_options)));

	$common = new Zend_Form_Element_Radio('common');
	$common->setLabel('Is this reverse type commonly found: ')
		->setRequired(false)
		->addMultiOptions(array('1' => 'Yes','2' => 'No'))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->setOptions(array('separator' => ''));

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);

	$this->addElements(array(
            $type, $gendate, $description,
            $translation, $reeceID, $common,
            $submit, $hash));

	$this->addDisplayGroup(array(
            'type', 'translation', 'description',
            'gendate', 'reeceID', 'common',
            'submit'), 
                'details');
	
        $this->details->setLegend('Reverse type details: ');
	
        $this->details->setLegend('Issuer or ruler details: ');
	
        parent::init();
    }
}