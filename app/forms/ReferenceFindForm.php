<?php
/** Form for linking references to finds.
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new ReferenceFindForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/ReferencesController.php
 * @uses Publications
*/
class ReferenceFindForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

	parent::__construct($options);

	$authors = new Publications();
	$authorList = $authors->getAuthors();
	
	$this->setName('addreference');
	
	$author = new Zend_Form_Element_Select('authors');
	$author->setRequired(true)
		->setLabel('Principal authors: ')
		->addFilters(array('StripTags', 'StringTrim'))
                ->setRegisterInArrayValidator(false)
		->setAttribs(array('class' => 'span8 selectpicker show-menu-arrow'))
		->addMultiOptions(array(
                    null => 'Choose an author or authors', 
                    'Available authors' => $authorList));

	$title = new Zend_Form_Element_Select('pubID');
	$title->setLabel('Publication title: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttribs(array('class' => 'span8 selectpicker show-menu-arrow'))
		->addMultiOptions(array(
                    null => 'Choose a title once you have chosen an author or authors'))
		->setRegisterInArrayValidator(false);

	$pages = new Zend_Form_Element_Text('pages_plates');
	$pages->setLabel('Pages or plate number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size', 9);

	$reference = new Zend_Form_Element_Text('reference');
	$reference->setLabel('Reference number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size', 15);

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$title, $author, $pages,
	$reference, $submit));


	$this->addDisplayGroup(array('authors', 'pubID', 'pages_plates', 'reference'), 'details');
	
	$this->details->setLegend('Add a new reference');
	$this->addDisplayGroup(array('submit'),'buttons');
	parent::init();
    }
}