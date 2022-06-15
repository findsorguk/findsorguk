<?php
/** Form for entering data about cultural ascription
 *
 * An example of code:
 * 
 * <code>
 * <?php
 * $form = new CultureForm();
 * ?>
 * </code>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * 
*/
class CultureForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);
	$this->setName('Culture');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Ascribed Culture name: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->setAttrib('size',60)
                ->addErrorMessage('Please enter a valid title for this culture!');

	$termdesc = new Pas_Form_Element_CKEditor('termdesc');
	$termdesc->setLabel('Description of ascribed culture: ')
                ->setRequired(true)
                ->setAttrib('rows',30)
                ->setAttrib('cols',60)
                ->addErrorMessage('You must enter a description for the ascribed culture')
                ->addFilter('HtmlBody')
                ->setAttrib('Height',400)
                ->addFilter('EmptyParagraph')
                ->addFilter('StringTrim')
                ->addFilter('WordChars');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
                ->setRequired(true)
                ->addErrorMessage('You must set the status of this term');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$submit = new Zend_Form_Element_Submit('submit');


	$this->addElements(array($term, $termdesc, $valid, $submit, $hash));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
    }
}