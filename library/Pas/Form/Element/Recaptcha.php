<?php
/** Form element for recaptcha V2
 * @package Pas_Form
 * @subpackage Element
 * @author Daniel Pett
 * @copyright Daniel Pett
 */
class Pas_Form_Element_Recaptcha extends Zend_Form_Element
{
    public $helper = 'formRecaptcha';

    /** Construct the form element for use as recaptcha
     * @param array|string|Zend_Config $specification
     * @param array $options
     * @access public
     * @throws Pas_Exception
     */
    public function __construct($specification = null, $options = null) {
        $options = $this->getKeysFromConfig();
        $specification = $this->setDefaultSpec($specification);
        if (empty($options['siteKey']) || empty($options['secretKey'])) {
            throw new Pas_Exception('Your keys must be set to use ReCaptcha');
        }

        $this->addValidator('Recaptcha', false, ['secretKey' => $options['secretKey']]);

        $this->setAllowEmpty(false);
        parent::__construct($specification, $options);
    }

    /** Get the config keys from the config object
     * @param array $options
     * @access protected
     * @return array
     */
    protected function getKeysFromConfig()
    {
        $params = Zend_Registry::get('config')->webservice->recaptcha->toArray();

        // Put in test for localhost and use testing keys
        $options['siteKey'] = trim($params['pubkey']);
        $options['secretKey'] = trim($params['privatekey']);

        return $options;
    }

    /** Set the default specification
     * @param string $specification
     * @access protected
     * @return string
     */
    protected function setDefaultSpec($specification) {
        if (empty($specification)) {
            $specification = 'g-recaptcha-response';
        }
        return $specification;
    }

    public function init() {
        $this->addPrefixPath(
            'Pas\\Validate\\',
            APPLICATION_PATH . '/../Pas/Validate/',
            \Zend_Form_Element::VALIDATE
        );
    }
} 
