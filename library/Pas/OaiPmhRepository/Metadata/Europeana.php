<?php

/** Metadata generator for creating ESE OAI outputs
 *
 * Class implementing metadata output for the required ESE metadata format.
 * Also uses grid reference stripping and redisplay tools. This builds on the
 * OAI classes generated for the Omeka system.
 *
 * @category Pas
 * @package  Pas_OaiPmhRepository
 * @subpackage Metadata
 * @author   Daniel Pett
 * @version  1
 * @since    22 September 2011
 */
class Pas_OaiPmhRepository_Metadata_Europeana extends Pas_OaiPmhRepository_Metadata_Abstract
{

    /** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'ese';

    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.europeana.eu/schemas/ese/';

    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.europeana.eu/schemas/ese/ESE-V3.3.xsd';

    /** XML namespace for unqualified Dublin Core */
    const DC_NAMESPACE_URI = 'http://purl.org/dc/elements/1.1/';

    /** DC metadata namespace **/
    const DC_METADATA_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';

    /** DC terms namespace */
    const DC_TERMS_NAMESPACE = 'http://purl.org/dc/terms/';

    /** The view object
     * @access protected
     * @var \Zend_View
     */
    protected $_view;

    /** INit the view
     * @access public
     * @return \Zend_View
     */
    public function init()
    {
        $this->_view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
    }

    /** Add meta data to the xml response in this ESE
     * @access public
     * @return void
     */
    public function appendMetadata()
    {
        $metadataElement = $this->document->createElement('metadata');
        $this->parentElement->appendChild($metadataElement);
        $europeana = $this->document->createElementNS(self::METADATA_NAMESPACE, 'ese:record');
        $metadataElement->appendChild($europeana);
        $europeana->setAttribute('xmlns:dc', self:: DC_NAMESPACE_URI);
        $europeana->setAttribute('xmlns:oai_dc', self::DC_METADATA_NAMESPACE);
        $europeana->setAttribute('xmlns:ese', self::DC_NAMESPACE_URI);
        $europeana->setAttribute('xmlns:xsi', parent::XML_SCHEMA_NAMESPACE_URI);
        $europeana->setAttribute('xsi:schemaLocation', self::METADATA_NAMESPACE . ' ' . self::METADATA_SCHEMA);
        $europeana->setAttribute('xmlns:dcterms', self::DC_TERMS_NAMESPACE);
        //Create the dublin core metadata from an array of objects
        if (!array_key_exists('0', $this->item)) {

            if (array_key_exists('objecttype', $this->item) && $this->item['objecttype'] === 'HOARD') {
                $uri = self::HOARD_URI;
            } else {
                $uri = self::RECORD_URI;
            }

            if (!array_key_exists('objecttype', $this->item)) {
                $this->item['objecttype'] = 'Unidentified object';
            }
            if (!array_key_exists('broadperiod', $this->item)) {
                $this->item['broadperiod'] = 'UNKNOWN';
            }
            if (!array_key_exists('identifier', $this->item)) {
                $this->item['identifier'] = 'Unidentified';
            }

            if (!array_key_exists('county', $this->item)) {
                $this->item['county'] = 'Not recorded';
            }

            if (!array_key_exists('description', $this->item)) {
                $this->item['description'] = 'No description available';
            }

            if (!array_key_exists('district', $this->item)) {
                $this->item['district'] = 'Not recorded';
            }

            if (!array_key_exists('county', $this->item)) {
                $this->item['county'] = 'Not recorded';
            }

            if (!array_key_exists('knownas', $this->item)) {
                $this->item['knownas'] = NULL;
            }

            if (!array_key_exists('fourFigure', $this->item)) {
                $this->item['fourFigure'] = NULL;
            }

            if (!array_key_exists('fourFigureLon', $this->item)) {
                $this->item['fourFigureLon'] = NULL;
            }

            if (!array_key_exists('fourFigureLat', $this->item)) {
                $this->item['fourFigureLat'] = NULL;
            }

            if (!array_key_exists('fromdate', $this->item)) {
                $this->item['fromdate'] = NULL;
            }

            if (!array_key_exists('todate', $this->item)) {
                $this->item['todate'] = NULL;
            }
            if (!array_key_exists('materialTerm', $this->item)) {
                $this->item['materialTerm'] = NULL;
            }

            $dc = array(
                'title' => $this->item['broadperiod'] . ' ' . $this->item['objecttype'],
                'creator' => $this->item['identifier'],
                'subject' => self::SUBJECT . ' - ' . $this->item['broadperiod'],
                'description' => strip_tags(str_replace(array("\n", "\r", '    '), array('', '', ' '), $this->item['description'])),
                'publisher' => self::RIGHTS_HOLDER,
                'contributor' => $this->institution($this->item['institution']),
                'date' => $this->item['created'],
                'type' => $this->item['objecttype'],
                'format' => self::FORMAT,
                'source' => self::SOURCE,
                'language' => self::LANGUAGE,
                'identifier' => $this->item['old_findID'],
                'coverage' => $this->item['broadperiod'],
                'rights' => self::LICENSE,
            );
            //Create the spatial arrray
            $spatial = array(
                'county' => $this->item['county'],
                'district' => $this->item['district']
            );
            $geo = array($this->item['knownas'], $this->item['fourFigure'], $this->item['fourFigureLat'], $this->item['fourFigureLon']);
            if (array_filter($geo)) {
                //Check for availability of NGR and therefore latlon conversions
                if (is_null($this->item['knownas']) && !is_null($this->item['fourFigure'])) {
                    $lat = $this->item['fourFigureLat'];
                    $lon = $this->item['fourFigureLon'];
                    $spatial['coords'] = $lat . ',' . $lon;
                }
            }
            $dcterms = array(
                'created' => date('Y-m-d', strtotime($this->item['created'])),
                'medium' => $this->item['materialTerm'],
                'isPartOf' => self::SOURCE,
                'provenance' => self::PROVENANCE
            );
            $ese = array();
            $ese['provider'] = self::RIGHTS_HOLDER;
            $ese['type'] = 'TEXT';

            $dates = array($this->item['fromdate'], $this->item['todate']);
            if (array_filter($dates)) {
                if (!is_null($this->item['todate'])) {
                    $this->item['todate'] = $this->item['fromdate'];
                }
                $temporal = array(
                    'year1' => $this->item['fromdate'],
                    'year2' => $this->item['todate'],
                );
            }

            $formats = array();

            if (array_key_exists('thumbnail', $this->item) && !is_null($this->item['thumbnail'])) {
                $ese['isShownBy'] = $this->_serverUrl . self::THUMB_PATH . $this->item['thumbnail'] . self::EXTENSION;
                $formats[] = $this->_serverUrl . '/' . $this->item['imagedir'] . $this->item['filename'];
            }
            $ese['isShownAt'] = $this->_serverUrl . $uri . $this->item['id'];
            foreach ($dc as $k => $v) {
                $this->appendNewElement($europeana, 'dc:' . $k, $v);
            }
            foreach ($dcterms as $k => $v) {
                $this->appendNewElement($europeana, 'dcterms:' . $k, $v);
            }
            foreach ($formats as $k => $v) {
                $this->appendNewElement($europeana, 'dcterms:hasFormat', $v);
            }
            if (array_filter($dates)) {
                foreach ($temporal as $k => $v) {
                    $this->appendNewElement($europeana, 'dcterms:temporal', $v);
                }
            }
            if (array_filter($geo)) {
                foreach ($spatial as $k => $v) {
                    $this->appendNewElement($europeana, 'dcterms:spatial', $v);
                }
            }
            foreach ($ese as $k => $v) {
                $this->appendNewElement($europeana, 'ese:' . $k, $v);
            }
        }
    }


    /** Returns the OAI-PMH metadata prefix for the output format.
     * @access public
     * @return string Metadata prefix
     */
    public function getMetadataPrefix()
    {
        return self::METADATA_PREFIX;
    }

    /**
     * Returns the XML schema for the output format.
     * @access public
     * @return string XML schema URI
     */
    public function getMetadataSchema()
    {
        return self::METADATA_SCHEMA;
    }

    /**
     * Returns the XML namespace for the output format.
     * @access public
     * @return string XML namespace URI
     */
    public function getMetadataNamespace()
    {
        return self::METADATA_NAMESPACE;
    }

    /** Create the correct institution
     * @access public
     * @param string $inst
     * @return string
     */
    public function institution($inst)
    {
        if (!is_null($inst)) {
            $institutions = new Institutions();
            $where = array();
            $where[] = $institutions->getAdapter()->quoteInto('institution = ?', $inst);
            $institution = $institutions->fetchRow($where);
            if (!is_null($institution)) {
                return $institution->description;
            }
        } else {
            return 'The Portable Antiquities Scheme';
        }
    }
}