<?php
/**  A view helper for search facet menu, to elaborate on section name
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_FacetContentSection extends Zend_View_Helper_Abstract  {
   
	protected $sections = array(
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
	
	public function facetContentSection($string){
	if(in_array($string,array_keys($this->sections))){
	$text = " $string ";
	foreach ($this->sections as $key => $value) {
	$text = preg_replace( "|(?!<[^<>]*?)(?<![?.&])\b$key\b(?!:)(?![^<>]*?>)|msU", 
	$value , $text );	
	}
	} else {
	$text = $string;
	}
	return ucfirst($text);
	}
	

	
}