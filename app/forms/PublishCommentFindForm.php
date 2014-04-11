<?php
/** Form for publishing comments on finds
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PublishCommentFindForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);


	$this->setName('comments');


    $commentType = new Zend_Form_Element_Hidden('comment_type');
    $commentType->addFilters(array('StripTags','StringTrim'));

	$comment_findID = new Zend_Form_Element_Hidden('contentID');
	$comment_findID->addFilters(array('StripTags','StringTrim'));

	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->addErrorMessage('Please enter a valid name!');

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->addValidator('EmailAddress',false,array('mx' => true))
		->addErrorMessage('Please enter a valid email address!')
		->setDescription('* This will not be displayed to the public.');

	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->addErrorMessage('Please enter a valid address!')
		->addValidator('Url')
		->setDescription('* Not compulsory');


	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');

    $hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);

	$status = new Zend_Form_Element_Radio('commentStatus');
	$status->setLabel('Message status:')
		->addMultiOptions(array('isspam' => 'Set as spam',
                    'isham' => 'Submit ham?',
                    'notspam' => 'Spam free'))
		->setValue('notSpam')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->setOptions(array('separator' => ''));

       $commentApproval = new Zend_Form_Element_Radio('comment_approved');
       $commentApproval->setLabel('Approval:')
		->addMultiOptions(array('moderation' => 'Moderation','approved' => 'Approved'))
		->setValue('approved')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->setOptions(array('separator' => ''));

	$this->addElements(array(
	$comment_author, $comment_author_email, $comment_content,
        $comment_author_url, $comment_findID, $commentApproval,
        $commentType, $status, $hash, $submit)
	);

	$this->addDisplayGroup(array(
	'comment_author','comment_author_email','comment_author_url',
	'comment_content', 'commentStatus', 'comment_approved',
        'contentID', 'comment_type'),
        'details');

	$this->details->setLegend('Enter your comments: ');

	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}