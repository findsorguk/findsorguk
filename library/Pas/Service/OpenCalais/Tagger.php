<?php

/**
 * Open Calais Tags
 * Last updated 1/16/2012
 * Copyright (c) 2012 Dan Grossman
 * http://www.dangrossman.info
 *
 * Please see http://www.dangrossman.info/open-calais-tags
 * for documentation and license information.
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 */
class Pas_Service_OpenCalais_Tagger
{

    protected $_apiUrl = 'http://api.opencalais.com/enlighten/rest/';

    protected $_apiKey;

    protected $_outputFormat = 'text/simple';

    protected $_contentType = 'text/html';

    protected $_getGenericRelations = true;

    protected $_getSocialTags = true;

    protected $_docRDFaccessible = false;

    protected $_allowDistribution = false;

    protected $_allowSearch = false;

    protected $_externalID = '';

    protected $_submitter = '';

    protected $_document = '';

    protected $_entities = array();

    /**
     * @return boolean
     */
    public function isAllowDistribution()
    {
        return $this->_allowDistribution;
    }

    /**
     * @param boolean $allowDistribution
     */
    public function setAllowDistribution($allowDistribution)
    {
        $this->_allowDistribution = $allowDistribution;
    }

    /**
     * @return boolean
     */
    public function isAllowSearch()
    {
        return $this->_allowSearch;
    }

    /**
     * @param boolean $allowSearch
     */
    public function setAllowSearch($allowSearch)
    {
        $this->_allowSearch = $allowSearch;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->_apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->_apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->_contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->_contentType = $contentType;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDocRDFaccessible()
    {
        return $this->_docRDFaccessible;
    }

    /**
     * @param boolean $docRDFaccessible
     */
    public function setDocRDFaccessible($docRDFaccessible)
    {
        $this->_docRDFaccessible = $docRDFaccessible;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocument()
    {
        return $this->_document;
    }

    /**
     * @param string $document
     */
    public function setDocument($document)
    {
        $this->_document = $document;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalID()
    {
        return $this->_externalID;
    }

    /**
     * @param string $externalID
     */
    public function setExternalID($externalID)
    {
        $this->_externalID = $externalID;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isGetGenericRelations()
    {
        return $this->_getGenericRelations;
    }

    /**
     * @param boolean $getGenericRelations
     */
    public function setGetGenericRelations($getGenericRelations)
    {
        $this->_getGenericRelations = $getGenericRelations;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isGetSocialTags()
    {
        return $this->_getSocialTags;
    }

    /**
     * @param boolean $getSocialTags
     */
    public function setGetSocialTags($getSocialTags)
    {
        $this->_getSocialTags = $getSocialTags;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFormat()
    {
        return $this->_outputFormat;
    }

    /**
     * @param string $outputFormat
     */
    public function setOutputFormat($outputFormat)
    {
        $this->_outputFormat = $outputFormat;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubmitter()
    {
        return $this->_submitter;
    }

    /**
     * @param string $submitter
     */
    public function setSubmitter($submitter)
    {
        $this->_submitter = $submitter;
        return $this;
    }


    protected function checkParameters()
    {
        if(empty($this->getApiKey())){
            throw new Pas_Exception_Param('No api key has been provided', 500);
        }
        if(empty($this->getDocument())){
            throw new Pas_Exception_Param('No document has been provided', 500);
        }
        if(empty($this->getContentType())){
            throw new Pas_Exception_Param('No content type has been provided', 500);
        }
    }

    public function getEntities()
    {
        $this->checkParameters();
        return $this->callAPI();

    }

    public function getParamsXML()
    {

        $types = array();
        if ($this->getGenericRelations)
            $types[] = 'GenericRelations';
        if ($this->getSocialTags)
            $types[] = 'SocialTags';

        $xml = '<c:params xmlns:c="http://s.opencalais.com/1/pred/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">';
        $xml .= '<c:processingDirectives ';
        $xml .= 'c:contentType="' . $this->contentType . '" ';
        $xml .= 'c:enableMetadataType="' . implode(',', $types) . '" ';
        $xml .= 'c:outputFormat="' . $this->outputFormat . '" ';
        $xml .= 'c:docRDFaccessible="' . ($this->docRDFaccessible ? 'true' : 'false') . '" ';
        $xml .= '></c:processingDirectives>';
        $xml .= '<c:userDirectives ';
        $xml .= 'c:allowDistribution="' . ($this->allowDistribution ? 'true' : 'false') . '" ';
        $xml .= 'c:allowSearch="' . ($this->allowSearch ? 'true' : 'false') . '" ';

        if (!empty($this->externalID))
            $xml .= 'c:externalID="' . htmlspecialchars($this->externalID) . '" ';

        if (!empty($this->submitter))
            $xml .= 'c:submitter="' . htmlspecialchars($this->submitter) . '" ';

        $xml .= '></c:userDirectives>';
        $xml .= '<c:externalMetadata></c:externalMetadata>';
        $xml .= '</c:params>';

        return $xml;

    }

    private function callAPI()
    {

        $data = 'licenseID=' . urlencode($this->api_key);
        $data .= '&paramsXML=' . urlencode($this->getParamsXML());
        $data .= '&content=' . urlencode($this->document);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);

        if (strpos($response, "<Exception>") !== false) {
            $text = preg_match("/<Exception\>(.*)<\/Exception>/mu", $response, $matches);
            throw new Pas_Service_OpenCalais_Exception($matches[1]);
        }

        //Parse out the entities
        $lines = explode("\n", $response);
        $start = false;
        foreach ($lines as $line) {
            if (strpos($line, '-->') === 0) {
                break;
            } elseif (strpos($line, '<!--') !== 0) {
                $parts = explode(':', $line);
                $type = $parts[0];
                $entities = explode(',', $parts[1]);
                foreach ($entities as $entity) {
                    if (strlen(trim($entity)) > 0)
                        $this->entities[$type][] = trim($entity);
                }
            }
        }

        //Parse out the social tags
        if (strpos($response, '<SocialTag ') !== false) {
            preg_match_all('/<SocialTag [^>]*>([^<]*)<originalValue>/', $response, $matches);
            if (is_array($matches) && is_array($matches[1]) && count($matches[1]) > 0) {
                foreach ($matches[1] as $tag) {
                    $this->entities['SocialTag'][] = trim($tag);
                }
            }
        }

    }

}