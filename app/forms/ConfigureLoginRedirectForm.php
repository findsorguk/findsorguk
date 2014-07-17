<?php
/**
* A form for editing login redirect page choice
*
* @category Pas
* @package 		Pas_Form
* @author 		Mary Chester-Kadwell mchester-kadwell @ britishmuseum.org
* @copyright  	Copyright (c) 2014 Mary Chester-Kadwell
@license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
* @version 1
* @since 		9 May 2014
*/

class ConfigureLoginRedirectForm extends Pas_Form
{
    public function __construct(array $options) {
        parent::__construct($options);
        $loginredirect = new LoginRedirect();
        $loginredirect_options = $loginredirect->getOptions();

        $this->setName('configureLoginRedirect');

        $uri = new Zend_Form_Element_Select('uri');
        $uri->setLabel('Page: ')
                ->setRequired(true)
                ->addMultiOptions(array(NULL => 'Please choose a page',
                    'Available pages' => $loginredirect_options))
                ->addValidator('InArray', false,
                        array(array_keys($loginredirect_options)))
                ->setAttribs(array(
                    'class' => 'input-xxlarge selectpicker show-menu-arrow',
                ));

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(4800);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit configuration');

        $this->addElements(array($uri, $submit, $hash));

        $this->addDisplayGroup(array('uri'), 'options');
        $this->options->setLegend('Choose page: ');

        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }


}