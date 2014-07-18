<?php
/** Form for adding and editing quotes
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new QuoteForm();
 * $form->details->setLegend('Add a new quote or announcement');
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/QuotesController.php
*/
class QuoteForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

	parent::__construct($options);

	$this->setName('quotes');

	$quote = new Pas_Form_Element_CKEditor('quote');
	$quote->setLabel('Quote or announcement: ')
                ->setRequired(true)
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Basic')
                ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$quotedBy = new Zend_Form_Element_Text('quotedBy');
	$quotedBy->setLabel('Origin of quote/announcement: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please state where this comes from.');

	$expire = new Zend_Form_Element_Text('expire');
	$expire->setLabel('Expires from use: ')
		->setRequired(true)
		->setAttrib('size',10)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please provide expiry date.');

	$valid = new Zend_Form_Element_Checkbox('status');
	$valid->setLabel('Quote/Announcement is in use: ')
		->setRequired(true)
		->addValidator('Int')
		->addFilters(array('StripTags','StringTrim'));

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Type: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setValue('quote')
		->addMultiOptions(array(
                    null => 'Choose type', 
                    'quote' => 'Quote', 
                    'announcement' => 'Announcement'
                    ));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
            $quote, $quotedBy, $valid,
            $expire, $type, $submit,
	$hash));
	
	$this->addDisplayGroup(array(
            'quote', 'quotedBy', 'status',
            'expire', 'type', 'submit'),
                'details');

	parent::init();      
    }
}