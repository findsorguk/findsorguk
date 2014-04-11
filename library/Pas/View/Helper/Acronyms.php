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
 class Pas_View_Helper_Acronyms extends Zend_View_Helper_Abstract {
	
	/** Function to add acronyms/abbreviations
	* 
	* @param $string text to add abbreviations to
	* @return $string Text with abbreviations inserted into html
	*/
  	public function Acronyms($string) {
	//Retrieve acronyms array from the database
	$acros = new Acronyms();
	$acronyms = $acros->getValid();
	$text = " $string ";
	foreach ( $acronyms as $acronym => $fulltext )
	$text = preg_replace( "|(?!<[^<>]*?)(?<![?.&])\b$acronym\b(?!:)(?![^<>]*?>)|msU", 
	"<abbr title=\"$fulltext\">$acronym</abbr>" , $text );
	$text = trim($text);
	return $text;
	}

}