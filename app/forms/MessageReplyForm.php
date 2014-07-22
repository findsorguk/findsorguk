<?php
/** Form for replying to messages sent via public users
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new MessageReplyForm();
 * $form->submit->setLabel('Send reply');
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/MessagesController.php
*/
class MessageReplyForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setName('comments');

	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
                ->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid name!');

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
		->addValidator('EmailAddress')   
		->addErrorMessage('Please enter a valid email address!')
		->setDescription('* This will not be displayed to the public.');

	$comment_content = new Pas_Form_Element_CKEditor('comment_content');
	$comment_content->setLabel('Message submitted by user: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$messagetext = new Pas_Form_Element_CKEditor('messagetext');
	$messagetext->setLabel('Your reply: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
            $comment_author, $comment_author_email,
            $comment_content, $messagetext, $submit
                ));

	$this->addDisplayGroup(array(
            'comment_author','comment_author_email','comment_author_url',
            'comment_content','messagetext'), 
                'details');
	
	$this->details->setLegend('Enter your comments: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
        parent::init();
    }
}