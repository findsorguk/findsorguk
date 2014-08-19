<?php
/** A modified version of oai xml generator abstract
 * @category Pas
 * @package Pas_OaiPmhRepository
 * @author John Flatness, Yu-Hsun Lin, Daniel Pett
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @copyright Daniel Pett 2011
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @uses Zend_Http_UserAgent
 * @uses Zend_Controller_Front
 * Changes made on 6/2/12
 */

class Pas_OaiPmhRepository_OaiXmlGeneratorAbstract extends Pas_OaiPmhRepository_XmlGeneratorAbstract {

    // =========================
    // General OAI-PMH constants
    // =========================

    /** The general namespace     */
    const OAI_PMH_NAMESPACE_URI    = 'http://www.openarchives.org/OAI/2.0/';
    
    /** The OAI schema */
    const OAI_PMH_SCHEMA_URI       = 'http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd';
    
    /** The protocol version */
    const OAI_PMH_PROTOCOL_VERSION = '2.0';
    // =========================
    // Error codes
    // =========================

    /** Bad argument error */
    const OAI_ERR_BAD_ARGUMENT              = 'badArgument';
    
    /** Bad resumption token */
    const OAI_ERR_BAD_RESUMPTION_TOKEN      = 'badResumptionToken';
    
    /** Bad verb */
    const OAI_ERR_BAD_VERB                  = 'badVerb';
    
    /** Cannot return that format **/
    const OAI_ERR_CANNOT_DISSEMINATE_FORMAT = 'cannotDisseminateFormat';
    
    /** That OAI ID does not exist */
    const OAI_ERR_ID_DOES_NOT_EXIST         = 'idDoesNotExist';
    
    /** No records match your query */
    const OAI_ERR_NO_RECORDS_MATCH          = 'noRecordsMatch';
    
    /** No metadata formats available */
    const OAI_ERR_NO_METADATA_FORMATS       = 'noMetadataFormats';
    
    /** No set hierarchy exists */
    const OAI_ERR_NO_SET_HIERARCHY          = 'noSetHierarchy';

    // =========================
    // Date/time constants
    // =========================

    /**
     * PHP date() format string to produce the required date format.
     * Must be used with gmdate() to conform to spec.
     */
    const OAI_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';
    const DB_DATE_FORMAT  = 'Y-m-d H:i:s';

    const OAI_DATE_PCRE     = "/^\\d{4}\\-\\d{2}\\-\\d{2}$/";
    const OAI_DATETIME_PCRE = "/^\\d{4}\\-\\d{2}\\-\\d{2}T\\d{2}\\:\\d{2}\\:\\d{2}Z$/";

    /** How date should be formatted */
    const OAI_GRANULARITY_STRING   = 'YYYY-MM-DDThh:mm:ssZ';
    const OAI_GRANULARITY_DATE     = 1;
    const OAI_GRANULARITY_DATETIME = 2;
    
    /** Added the name for the Scheme repository as a constant  */
    const REPOSITORY = 'Portable Antiquities Scheme';

    /** Flags if an error has occurred during the response.
     * @access protected
     * @var bool
     */
    protected $_error;

    /** The server url
     * @access protected
     * @var string
     */
    protected $_serverUrl;
    /**
     * Throws an OAI-PMH error on the given response.
     * @access public
     * @param string $error OAI-PMH error code.
     * @param string $message Optional human-readable error message.
     */
    public function throwError($error, $message = null)  {
        $this->_error = true;
        $errorElement = $this->document->createElement('error', $message);
        $this->document->documentElement->appendChild($errorElement);
        $errorElement->setAttribute('code', $error);
    }

    /**
     * Converts the given Unix timestamp to OAI-PMH's specified ISO 8601 format.
     * @access static
     * @param int $timestamp Unix timestamp
     * @return string Time in ISO 8601 format
     */
    static function unixToUtc($timestamp) {
        return gmdate(self::OAI_DATE_FORMAT, $timestamp);
    }

    /**
     * Converts the given Unix timestamp to the Omeka DB's datetime format.
     * @access static
     * @param int $timestamp Unix timestamp
     * @return string Time in Omeka DB format
     */
    static function unixToDb($timestamp) {
       return date(self::DB_DATE_FORMAT, $timestamp);
    }

    /**
     * Converts the given time string to OAI-PMH's specified ISO 8601 format.
     * Used to convert the timestamps output from the Omeka database.
     *
     * @param string $databaseTime Database time string
     * @return string Time in ISO 8601 format
     * @uses unixToUtc()
     */
    static function dbToUtc($databaseTime)
    {
        return self::unixToUtc(strtotime($databaseTime));
    }

    /**
     * Converts the given time string to the Omeka database's format.
     *
     * @param string $databaseTime Database time string
     * @return string Time in Omeka DB format
     * @uses unixToDb()
     */
    static function utcToDb($utcDateTime)
    {
       return self::unixToDb(strtotime($utcDateTime));
    }

    /**
     * Returns the granularity of the given utcDateTime string.  Returns zero
     * if the given string is not in utcDateTime format.
     *
     * @param string $dateTime Time string
     * @return int OAI_GRANULARITY_DATE, OAI_GRANULARITY_DATETIME, or zero
     */
    static function getGranularity($dateTime)
    {
        if(preg_match(self::OAI_DATE_PCRE, $dateTime)) {
            return self::OAI_GRANULARITY_DATE;
        } else if(preg_match(self::OAI_DATETIME_PCRE, $dateTime)) {
            return self::OAI_GRANULARITY_DATETIME;
        } else {
            return false;
        }
    }

    /** Get the user agent
     * @access public
     * @return string
     */
    public function _userAgent(){
        $useragent = new Zend_Http_UserAgent();
        return $useragent->getUserAgent();
    }

    /** Get the ip address
     * @access public
     * @return string
     */
    public function _ipAddress(){
    return Zend_Controller_Front::getInstance()->getRequest()->getClientIp();
    }
}