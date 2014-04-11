<?php
/** Form for contacting the Scheme
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ContactUsForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);

	$this->setName('comments');



	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('NotEmpty')
	->addErrorMessage('Please enter a valid name!')
	->setDescription('If you are offering us SEO services, you will be added to the akismet spam list.');

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
	->setRequired(true)
	->addValidator('EmailAddress')
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addErrorMessage('Please enter a valid email address!')
	->setDescription('* This will not be displayed to the public.');

	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addValidator('NotEmpty')
	->addErrorMessage('Please enter a valid address!')
	->setDescription('Not compulsory');


	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet', 'Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
	->addErrorMessage('Please enter something in the comments box!');


	$captcha = new Zend_Form_Element_Captcha('captcha', array(
                        	'captcha' => 'ReCaptcha',
							'label' => 'Please fill in this reCaptcha to prove human life exists at your end!',
                            'captchaOptions' => array(
                            'captcha' => 'ReCaptcha',
                            'privKey' => $this->_privateKey,
                            'pubKey' => $this->_pubKey,
							'theme'=> 'clean')
                        ));


	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setAttrib('class','large')
	->setLabel('Submit your query');


	$auth = Zend_Auth::getInstance();
	if(!$auth->hasIdentity()) {
	$this->addElements(array(
	$comment_author,
	$comment_author_email, $comment_content,	$comment_author_url,
	$captcha, $submit, $hash));

	$this->addDisplayGroup(array(
	'comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content', 'captcha'), 'details');
	$this->details->setLegend('Enter your comments: ');

	} else {
	$user = $auth->getIdentity();
	$comment_author->setValue($user->fullname);
	$comment_author_email->setValue($user->email);

	$this->addElements(array(
	$comment_author,
	$comment_author_email, $comment_content,$comment_author_url,
	$submit, $hash));

	$this->addDisplayGroup(array(
	'comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content'), 'details');
	$this->details->setLegend('Enter your comments: ');
	}
	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}

}