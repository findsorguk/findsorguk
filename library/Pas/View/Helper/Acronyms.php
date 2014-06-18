<?php
/**
 * A view helper for automatically inserting HTML abbreviations
 * Not sure if this was inspired by a wordpress plugin or not?!
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    GNU Public
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Acronyms extends Zend_View_Helper_Abstract
{

    protected $_acronyms;

    protected $_string;

    /** Set up configuration
     * 
     * @param $string 
     */
    public function __construct( $string ) {
        $this->_acronyms = new Acronyms();
        $this->_string = $string;
    }

    /** Get the acronyms
     * 
     */
    public function getAcronyms()
    {
        return $this->_acronyms->getValid();
    }

    /** Get the html to render
     * 
     */
    public function generate()
    {
        $text = " $this->_string ";
        $abbrev = $this->getAcronyms();
        foreach($abbrev as $acronym => $expanded) {
            $text = preg_replace( "|(?!<[^<>]*?)(?<![?.&])\b$acronym\b(?!:)(?![^<>]*?>)|msU",
	"<abbr title=\"$expanded\">$acronym</abbr>" , $text );
			$newText = trim($text);
        }
        return $newText;
    }

    /** Function to add acronyms/abbreviations
     *
     */
    public function Acronyms()
    {
        return $this;
    }

    /** Magic method return string
     *
     * @return function
     */
    public function __toString() {
        return $this->generate();
    }
}