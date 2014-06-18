<?php
/**
 * A view helper for automatically inserting HTML abbreviations
 * Not sure if this was inspired by a wordpress plugin or not?!
 * 
 * An example of use:
 * <code>
 * <?php
 * echo $this->acronyms()->setString($string);
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    GNU Public
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Acronyms
 */
class Pas_View_Helper_Acronyms extends Zend_View_Helper_Abstract
{
    /** The string to show acronyms in
     * @access protected
     * @var string
     */
    protected $_string;
    
    /** Get the string to analyse
     * @access protected
     * @return string
     */
    public function getString() {
        return $this->_string;
    }

    /** Set the string to analyse
     * @access public
     * @param string $string
     * @return \Pas_View_Helper_Acronyms
     */
    public function setString( $string) {
        $this->_string = $string;
        return $this;
    }

    /** Get the acronyms
     * @access public
     * @return array Array of acronyms
     */
    public function getAcronyms(){
        $acronyms = new Acronyms();
        return $acronyms->getValid();
    }

    /** Get the html to render
     * @access public
     * @return string The string with acronyms in the html
     */
    public function generate() {
        $text = $this->getString();
        $abbrev = $this->getAcronyms();
        foreach ($abbrev as $acronym => $expanded) {
            $text = preg_replace( "|(?!<[^<>]*?)(?<![?.&])\b$acronym\b(?!:)(?![^<>]*?>)|msU",
    "<abbr title=\"$expanded\">$acronym</abbr>" , $text );
            $newText = trim($text);
        }

        return $newText;
    }
    /** Magic method return string
     * @access public
     * @return function
     */
    public function __toString() {
        return $this->generate();
    }
    
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_Acronyms
     */
    public function acronyms() {
        return $this;
    }
}