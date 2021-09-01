<?php
/** Form for retrieval of username via email
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * 
 */

class ForgotUsernameForm extends Pas_Form {

    /** Initialise form
     * @access public
     * @return void
     */
    public function init() {

        $email = $this->addElement('Text', 'email',
        array('label' => 'Email Address: ', 'size' => '30'))->email;
        $email->addValidator('emailAddress')
              ->setRequired(true)
              ->addErrorMessage('Please enter a valid address!')
              ->addValidator('Db_RecordExists', false,
                           array('table' => 'users', 'field' => 'email'))
              ->addFilters(array('StringTrim','StripTags'));

        $this->addElement((new Pas_Form_Element_Recaptcha('captcha'))
                              ->setLabel('Please complete the Captcha field to prove you exist')
        );

        $submit = $this->addElement('submit', 'submit');

        $this->addDisplayGroup(array('email', 'captcha', 'submit'), 'details');

        $this->setLegend('Reset my password: ');
        parent::init();
    }
}