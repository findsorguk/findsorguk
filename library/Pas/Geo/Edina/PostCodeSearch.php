<?php
/** An interface to the Edina PostCodeSearch api call
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage PostCodeSearch
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @since 3/2/12
 * @version 1
 * @copyright Daniel Pett, The British Museum
 * @author Daniel pett
 * @uses Pas_Geo_Edina_Exception
 * @see http://unlock.edina.ac.uk/places/queries/
 *
 * Usage:
 *
 * $edina = new Pas_Geo_Edina_SpatialNameSearch();
 * $edina->setPostCode('WC1B 3DG'); - strips out spaces if validated
 * $edina->setFormat('georss'); - you can use georss, kml, xml, jaon
 * $edina->get();
 * You can also get the postcode queried back from:
 * $edina->getPostCode();
 * If you want to get the url of the api call
 * $edina->getUrl();
 *
 * Then process the object returned
 */
class Pas_Geo_Edina_PostCodeSearch extends Pas_Geo_Edina {

    /** The method to call
     *
     */
    const METHOD = 'postCodeSearch?';

    /** The postcode to query
     * @access protected
     * @var string
     */
    protected $_postCode;

    /** The validator to use to check the postcode
     * @access protected
     * @var object
     */
    protected $_validator;


    /** Construct the validator object
     *
     */
    public function __construct() {
        $this->_validator = new Pas_Validate_ValidPostCode;
        parent::__construct();
    }

    /** Set the postcode
     * @access public
     * @param string $postCode
     */
    public function setPostCode($postCode) {
        $this->_postCode = $postCode;
    }

    /** Return the postcode queried
     * @access public
     * @return string
     */
    public function getPostCode() {
        return $this->_postCode;
    }

     /** Validate the postcode
     * @access protected
     * @throws Pas_Geo_Edina_Exception
     */
    protected function validatePostCode(){
        if(!$this->_validator->isValid($this->_postCode)){
            throw new Pas_Geo_Edina_Exception('Invalid postcode given');
        } else {
            $this->_postCode = str_replace(' ', '', $this->_postCode);
        }
    }

    /** Send parameters to parent function
     * @access public
     * @return array
     */
    public function get(){
        $params = array(
          'postCode' => $this->_postCode,
          'gazetteer' => $this->_gazetteer,
          'format' => $this->_format
        );
    return parent::get(self::METHOD, $params);
    }
}