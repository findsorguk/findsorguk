<?php
/**  
 * A view helper for search facet menu, to elaborate on section name
 * 
 * This view helper is for rendering the correct search facet name
 * 
 * <code>
 * <?php
 * echo $this->facetContentSection()->setString($string);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @version 1
 */
class Pas_View_Helper_FacetContentSection extends Zend_View_Helper_Abstract {
    
    /** Section names array for cleaning
     * @access protected
     * @var array Array of section names to friendly names
     */
    protected $_sections = array(
        'databasehelp' => 'Database help',
        'help' => 'Site help',
        'getinvolved' => 'Get involved',
        'bronzeage' => 'Bronze Age guide',
        'ironage' => 'Iron Age guide',
        'profiles' => 'Staff profiles',
        'reports' => 'Annual reports',
        'treports' => 'Treasure reports',
        'info' => 'General information',
        'medievalcoins' => 'Medieval coin guide',
        'postmedievalcoins' => 'Post medieval coin guide',
        'byzantinecoins' => 'Byzantine coin guide',
        'earlymedievalcoins' => 'Early medieval coins',
        'romancoins' => 'Roman coin guide',
        'frg' => 'Finds recording guide',
        'oai' => 'OAI documentation',
        'staffs' => 'Staffordshire hoard symposium',
        'ironagecoins' => 'Iron Age coin guide',
        'greekromancoins' => 'Greek and Roman coin guide',
        'api' => 'API documentation'
        );

    /** The string to replace
     * @access protected
     * @var string
     */
    protected $_string;
    
    /** Get the string to replace
     * @access public
     * @return $string Get the string
     */
    public function getString() {
        return $this->_string;
    }

    /** Set the string
     * @access public
     * @param string $string
     * @return \Pas_View_Helper_FacetContentSection
     */
    public function setString($string) {
        $this->_string = $string;
        return $this;
    }
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FacetContentSection
     */
    public function facetContentSection() {
        return $this;
    }
    
    
    /** The to string function
     * @access public
     * @return \stringReplace
     */
    public function __toString() {
        return $this->stringReplace( $this->getString() );
    }
    /** Function to replace string
     * @access public
     * @param type $string
     * @return string
     */
    public function stringReplace( $string) {
        $newString = '';
        if (in_array($string ,array_keys($this->_sections))) {
            $newString .= array_search($string, array_flip($this->sections));
        }
        return $newString;
    }
}