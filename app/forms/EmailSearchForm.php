<?php
/** Form for searching via email name
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new EmailSearchForm();
 * $this->view->form = $form;
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/SearchController.php
 * @uses Roles
 * @uses Institutions
 * @uses ProjectTypes
 */

class EmailSearchForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

        parent::__construct($options);

        $roles = new Roles();
        $role_options = $roles->getRoles();

        $inst = new Institutions();
        $inst_options = $inst->getInsts();

        $projecttypes = new ProjectTypes();
        $projectype_list = $projecttypes->getTypes();

        $this->setName('emailsearch');
        ZendX_JQuery::enableForm($this);

        $message = new Zend_Form_Element_Textarea('messageToUser');
        $message->setLabel('Message to user: ')
                ->setRequired(true)
                ->addFilters(
                        array(
                            'StringTrim',
                            'WordChars',
                            'BasicHtml',
                            'EmptyParagraph'
                            ))
                ->setAttribs(array('rows' => 10))
                ->addFilter('BasicHtml')
                ->addErrorMessage('You must enter a message to your recipient.');

        $fullname = new Zend_Form_Element_Text('fullname');
        $fullname->setLabel('Send this to: ')
                ->addFilters(array('StringTrim','StripTags', 'Purifier'))
                ->setAttrib('size',30);

        $email = $this->addElement('text', 'email',
                array('label' => 'Their email Address', 'size' => '30'))->email;
        $email->addValidator('EmailAddress')
                ->addFilters(array('StringTrim','StripTags','StringToLower'))
                ->setRequired(true)
                ->addErrorMessage('Please enter a valid address!');

        $this->addElement(
            (new Pas_Form_Element_Recaptcha('captcha'))
                ->setLabel('Please complete the Captcha field to prove you exist')
        );

        //Submit button
        $submit = new Zend_Form_Element_Submit('submit');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(60);

        $this->addElement($hash);

        $this->addElements(array( $fullname, $submit,$message));

        $this->addDisplayGroup(array('fullname','email','messageToUser', 'captcha'), 'details');
        $this->details->setLegend('Details: ');
        $this->addDisplayGroup(array('submit'), 'buttons');
        parent::init();
    }
}
