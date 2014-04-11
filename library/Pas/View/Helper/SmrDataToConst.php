<?php
/**
 *
 * @author dpett
 * @version
 */

/** A view helper for getting a count of SMR records within a constituency
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @license GNU
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @version 1
 * @uses viewHelper Pas_View_Helper extends Zend_View_Helper_Abstract
 */
class Pas_View_Helper_SmrDataToConst extends Zend_View_Helper_Abstract {


    /** Get all SMR data for a consituency
     *
     * @param string $constituency
     * @return null
     */
    public function SmrDataToConst($constituency) {
    $os = $this->getRecords($constituency);
    if($os){
    return $this->buildHtml($os);
    }else {
    return null;
    }
    }

    /** Get the records from the database
     *
     * @param string $constituency
     * @return
     */
    public function getRecords($constituency) {
    $osdata = new ScheduledMonuments();
    return $osdata->getSmrsConstituency($constituency);

    }

    /** Build the HTML
     *
     * @param array $os
     * @return string
     */
    public function buildHtml($os){
    $string = '<p>There are ' . count($os);
    $string .= ' scheduled monuments listed in the National Monuments Records';
    $string .= 'from EH for this constituency.</p>';
    return $string;
    }
}

