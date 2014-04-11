<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** A wrapper for interfacing with the MapIt api, specifically the postcode call.
 * This extends the Mapit base class.
 *
 * @category Pas
 * @package Pas_Geo_Mapit
 * @subpackage Postcode
 * @version 1
 * @since 6/2/12
 * @copyright Daniel Pett, British Museum
 * @license GNU public
 * @see http://mapit.mysociety.org/
 * @author Daniel Pett
 * @uses Pas_Validate_ValidPostcode
 * @uses Pas_Geo_Mapit_Exception
 *
 * USAGE
 *
 * $m = new Pas_Geo_Mapit_Postcode();
 * You have two options to search for a partial or a full postcode
 * The full postcode is validated for format. I haven't bothered for partial.
 * To set full postcode (used British Museum for eg):
 * $m->setFullPostCode('WC1B 3DG');
 * $m->get(); - returns json response
 * To set partial postcode:
 * $m->setPartialPostCode('WC1B');
 * $m->get();
 *
 */
class Pas_Geo_Mapit_Postcode extends Pas_Geo_Mapit {

    /** Set the api method to use
     *
     */
    const APIMETHOD = 'postcode';

    /** The postcode variable
     * @access protected
     * @var string
     */
    protected $_postcode;

    /** the partial postcode variable
     * @access protected
     * @var string
     */
    protected $_partialPostCode;

    /** The partial string
     * @access protected
     * @var type
     */
    protected $_partial = null;

    /** set the full postcode
     * @access public
     * @param string $postcode
     * @throws Pas_Geo_Mapit_Exception
     * @return string
     */
    public function setFullPostCode($postcode){
    $validator = new Pas_Validate_ValidPostCode();
    if($validator->isValid($postcode)){
         $this->_postcode = str_replace(' ', '', $postcode);
    } else {
         throw new Pas_Geo_Mapit_Exception('Invalid post code specified');
    }
    }

    /** Get the postcode submitted
     * @access public
     * @return type
     */
    public function getPostcode() {
        return $this->_postcode;
    }


    /** Get the partial postcode
     * @access public
     * @return type
     */
    public function getPartialPostCode() {
        return $this->_partialPostCode;
    }

    /** Set the partial postcode
     * @access public
     * @param type $partialPostCode
     */
    public function setPartialPostCode($partialPostCode) {
        $this->_partialPostCode = $partialPostCode;
        $this->_partial = 'partial';
    }


    /** Get the data from the api
     * @access public
     * @return type
     * @throws Pas_Geo_Mapit_Exception
     */
    public function get(){
        if(isset($this->_postcode) && isset($this->_partialPostCode)){
            throw new Pas_Geo_Mapit_Exception('You cannot use both methods');
        }
    $params = array(
         $this->_partial,
         $this->_postcode,
         $this->_partialPostCode
    );
    return parent::get(self::APIMETHOD, $params);
    }

    public function appendGeneration($generation){
        if(is_numeric($generation)){
            $this->_generation = '?generation=' . $generation;
        } else {
            throw new Pas_Geo_Mapit_Exception('The generation must be an integer');
        }

    }
}
