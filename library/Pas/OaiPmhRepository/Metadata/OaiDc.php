<?php
/** Class implmenting metadata output for the required oai_dc metadata format.
 * oai_dc is output of the 15 unqualified Dublin Core fields. Slight modifications
 * applied by Daniel Pett, 6/2/12
 *
 * @package OaiPmhRepository
 * @subpackage MetadataFormats
 * @author John Flatness, Yu-Hsun Lin
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once('Pas/OaiPmhRepository/Metadata/Abstract.php');

class Pas_OaiPmhRepository_Metadata_OaiDc extends Pas_OaiPmhRepository_Metadata_Abstract
{
    /** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'oai_dc';

    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';

    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd';

    /** XML namespace for unqualified Dublin Core */
    const DC_NAMESPACE_URI = 'http://purl.org/dc/elements/1.1/';

    /**
     * Appends Dublin Core metadata.
     *
     * Appends a metadata element, an child element with the required format,
     * and further children for each of the Dublin Core fields present in the
     * item.
     */


    public function appendMetadata() {
	$metadataElement = $this->document->createElement('metadata');
	$this->parentElement->appendChild($metadataElement);

	$oai_dc = $this->document->createElementNS( self::METADATA_NAMESPACE, 'oai_dc:dc');
	$metadataElement->appendChild($oai_dc);

	/* Must manually specify XML schema uri per spec, but DOM won't include
	* a redundant xmlns:xsi attribute, so we just set the attribute
	*/
	$oai_dc->setAttribute('xmlns:dc', self::DC_NAMESPACE_URI);
	$oai_dc->setAttribute('xmlns:xsi', parent::XML_SCHEMA_NAMESPACE_URI);
	$oai_dc->setAttribute('xsi:schemaLocation', self::METADATA_NAMESPACE
                . ' ' . self::METADATA_SCHEMA);

	if(!array_key_exists('0',$this->item)) {
	$data = array(
   	'title'			=> $this->item['broadperiod']. ' ' . $this->item['objecttype'] ,
	'creator'		=> $this->item['creator'],
	'subject'		=> self::SUBJECT,
	'description'           => strip_tags($this->item['description']),
	'publisher'             => self::RIGHTS_HOLDER,
	'contributor'           => $this->item['institution'],
	'date' 			=> $this->item['created'],
 	'type' 			=> $this->item['objecttype'],
	'format' 		=> self::FORMAT,
	'id' 			=> $this->item['id'],
	'identifier'            => $this->_serverUrl . self::RECORD_URI . $this->item['id'],
	'source' 		=> self::SOURCE,
	'language' 		=> self::LANGUAGE
        );


	if(isset($this->item['thumbnail'])){
        $relation = $this->_serverUrl . '/' . $this->item['imagedir']
               . $this->item['filename'];
	$data['relation'] = $relation;
	} else {
	$data['relation'] = '';
	}
	$data['coverage'] = $this->item['broadperiod'];
	$data['rights'] = self::LICENSE;
	unset($data['id']);
	foreach($data as $k => $v){
	$this->appendNewElement($oai_dc, 'dc:' . $k, $v);
	}
	}
    }


    /**
     * Returns the OAI-PMH metadata prefix for the output format.
     *
     * @return string Metadata prefix
     */
    public function getMetadataPrefix() {
        return self::METADATA_PREFIX;
    }

    /**
     * Returns the XML schema for the output format.
     *
     * @return string XML schema URI
     */
    public function getMetadataSchema() {
        return self::METADATA_SCHEMA;
    }

    /**
     * Returns the XML namespace for the output format.
     *
     * @return string XML namespace URI
     */
    public function getMetadataNamespace() {
        return self::METADATA_NAMESPACE;
    }
}
