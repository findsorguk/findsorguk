<?php
/** Form for submitting complaints about the Scheme
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ComplaintsForm extends Pas_Form {
	public function __construct($options = null)
	{

	parent::__construct($options);

	$this->setName('complaints');

	$user_ip = new Zend_Form_Element_Hidden('user_ip');
	$user_ip->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setValue($_SERVER['REMOTE_ADDR'])
	->addValidator('Ip')
	->setRequired(true);

	$user_agent = new Zend_Form_Element_Hidden('user_agent');
	$user_agent->addFilters(array('StripTags','StringTrim'))
	->setValue($_SERVER['HTTP_USER_AGENT']);;

	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Please enter a valid name!');

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
	->addValidator('EmailAddress')
	->addErrorMessage('Please enter a valid email address!')
	->setDescription('This will not be displayed to the public.');

	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
	->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
	->addValidator('NotEmpty')
	->addErrorMessage('Please enter a valid address!')
	->setDescription('Not compulsory');

	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',80)
	->setAttrib('ToolbarSet', 'Finds')
	->addFilters(array('HtmlBody','EmptyParagraph','WordChars'))
	->addErrorMessage('Please enter something in the comments box!');


	$captcha = new Zend_Form_Element_Captcha('captcha', array(
                        		'captcha' => 'ReCaptcha',
								'label' => 'Please prove you are not a spammer',
                                'captchaOptions' => array(
                                'captcha' => 'ReCaptcha',
                                'privKey' => $this->_privateKey,
                                'pubKey' => $this->_pubKey,
								'theme'=> 'clean')
                        ));


	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Submit your query');


	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$auth = Zend_Auth::getInstance();
	if(!$auth->hasIdentity()){
	$this->addElements(array(
	$user_ip, $user_agent, $comment_author,
	$comment_author_email, $comment_content, $comment_author_url,
	$captcha, $submit, $hash));

	$this->addDisplayGroup(array('comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content','captcha'), 'details');

	$this->details->setLegend('Enter your comments: ');
	} else {
	$user = $auth->getIdentity();
	$comment_author->setValue($user->fullname);
	$comment_author_email->setValue($user->email);
	$this->addElements(array(
	$comment_author,
	$comment_author_email, $comment_content, $comment_author_url,
	$submit, $hash));

	$this->addDisplayGroup(array('comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content'), 'details');
	$this->details->setLegend('Enter your comments: ');
	}
	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}

}