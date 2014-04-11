<?php
/**
* Form for commenting on a find
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class CommentFindForm extends Pas_Form
{


public function __construct($options = null)
{

parent::__construct($options);

	$this->setName('comments');


	/**
	 * Form hash to prevent CSRF
     */
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	/**
	 * The name of the author
     */
	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Please enter a valid name!');

	/**
	 * Email address element
     */
	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addValidator('EmailAddress')
	->addErrorMessage('Please enter a valid email address!')
	->setDescription('* This will not be displayed to the public.');

	/**
	 * url of the commenter
     */
	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addErrorMessage('Please enter a valid address!')
	->setAttrib('placeholder', 'for example http://finds.org.uk')
	->setDescription('* Not compulsory');


	$comment_content = new Zend_Form_Element_Textarea('comment_content');
	$comment_content->setLabel('Enter your comment: ')
	->setRequired(true)
	->addFilters(array('StringTrim','BasicHtml','EmptyParagraph'))
	->setAttrib('rows',10)
	->setAttrib('cols',80)
	->setAttrib('class', 'input-xxlarge')
	->setAttrib('placeholder', 'If you are not entering an error report, please do comment here. If not use the error report link above left.')
	->addErrorMessage('Please enter something in the comments box.')
	->setDescription('The following HTML tags can be used - a,p,ul,li,em,strong,br,img,a - and
	paragraphs will be automatically created');
	$recaptcha = new Zend_Service_ReCaptcha($this->_pubKey, $this->_privateKey);
	$captcha = new Zend_Form_Element_Captcha('captcha', array(
                        		'captcha' => 'ReCaptcha',
								'label' => 'Prove you are not a robot/spammer',
                                'captchaOptions' => array(
                                'captcha' => 'ReCaptcha',
                                'service' => $recaptcha,
								'theme'=> 'clean')
                        ));

	$submit = new Zend_Form_Element_Submit('submit');

	$auth = Zend_Auth::getInstance();
	if(!$auth->hasIdentity()) {
	$this->addElements(array( $comment_author,
	$comment_author_email, $comment_content, $comment_author_url,
	$captcha, $submit, $hash));

	$this->addDisplayGroup(array('comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content', 'captcha'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');
	} else {
	$user = $auth->getIdentity();
	$comment_author->setValue($user->fullname);
	$comment_author_email->setValue($user->email);

	$this->addElements(array(
        $comment_author,
	$comment_author_email,
	$comment_content,
	$comment_author_url,
	$submit, $hash));

	$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url',
	'comment_content'), 'details');
	}

	$this->details->setLegend('Enter your comments: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

		parent::init();
	}
}