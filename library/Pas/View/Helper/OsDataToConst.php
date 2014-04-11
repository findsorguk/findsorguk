<?php
/**
 *
 * @author dpett
 * @version
 */

/** A view helper for getting a count of OS antiquity/roman records within a
 * constituency
 * 
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @license GNU
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @version 1
 * @uses viewHelper Pas_View_Helper extends Zend_View_Helper_Abstract
 */
class Pas_View_Helper_OsDataToConst extends Zend_View_Helper_Abstract {

	/** Get the osdata
         *
         * @param string $constituency
         * @return null
         */
	public function osDataToConst($constituency) {
	$os = $this->getRecords($constituency);
	if(count($os)){
	return $this->buildHtml($os);
	}else {
	return null;
	}
	}

        /** Get the records from the model
         *
         * @param string $constituency
         * @return array
         */
	public function getRecords($constituency) {
        $osdata = new Osdata();
        return $osdata->getGazetteerConstituency($constituency);
	}

        /** Build the html
         *
         * @param data $os
         * @return string
         */
	public function buildHtml($os) {
        $string = '<p>There are ' . count($os);
        $string .= ' mapped ancient sites (Roman and other) listed in the OS';
        $string .= '1:50K Gazetteer for this constituency.</p>';
        return $string;
	}
}

