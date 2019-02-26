<?php
/**
 * Class FormRecaptcha
 *
 * @package Cgsmith
 * @license MIT
 */
class Pas_View_Helper_FormRecaptcha extends Zend_View_Helper_FormElement
{
    /**
     * For google recaptcha div to render properly
     *
     * @param $name
     * @param null $value
     * @param null $attribs
     * @param null $options
     * @param string $listsep
     * @return string
     * @throws \Zend_Exception
     */
    public function formRecaptcha($name, $value = null, $attribs = null, $options = null, $listsep = '')
    {
        $customClasses = '';
        if( isset( $attribs['classes'] )) {
            if( is_array( $attribs['classes'] ) ) {
                $customClasses = implode(' ', $attribs['classes']);
            } else {
                $customClasses = $attribs['classes'];
            }
        }
        return '<div class="g-recaptcha ' . $customClasses . '" data-sitekey="' . $attribs['siteKey'] . '"></div>';
        return $captcha;
    }
}
