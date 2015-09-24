<?php

/**A service class for assigning what 3 words identifiers 
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $what3words = new Pas_Service_What3words();
 * $what3words->setApiKey($key)->setLanguage($lang)->positionToWords(array($x,$y));
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2015 Daniel Pett
 * @category   Pas
 * @package    Pas_Service
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Curl
 * @example app/models/Findspots.php
 */
class Pas_Service_What3words
{
    /** Base url to query */
    const WHAT3WORDS_URI = 'http://api.what3words.com/';

    /** The Api key to use
     * @access protected
     * @var string $_apiKey
     */
    protected $_apiKey;

    /** The default language
     * @access protected
     * @var string
     */
    protected $_language = 'en';

    /** Convert 3 words or OneWord into position
     * Takes words either as string, or array of words
     * Returns array of [lat, long]
     * @access public
     * @return array
     */
    public function wordsToPosition($words)
    {
        if (is_array($words)) {
            $words = implode('.', $words);
        } elseif (!is_string($words)) {
            throw new Pas_Exception('Invalid words passed', 500);
        }
        $data = array('string' => $words);
        return self::requestWords('w3w', $data);
    }

    /**
     * Convert a position into 3 words
     * Takes position either as string, or array of 2 values
     * Returns array of [word1, word2, word3]
     * @access public
     * @return array
     */
    public function positionToWords($position)
    {
        if (is_array($position)) {
            $position = implode(',', $position);
        } elseif (!is_string($position)) {
            throw new Pas_Exception('Invalid position passed', 500);
        }
        $data = array('position' => $position);
        return self::requestWords('position', $data);

    }

    /** Get the data with a specific method using Curl
     * @access public
     * @return string
     */
    public function requestWords($method, $data)
    {
        $data['key'] = $this->getApiKey();
        $data['lang'] = $this->getLanguage();
        $curl = new Pas_Curl();
        $curl->setUri(self::WHAT3WORDS_URI . $method . '?' . http_build_query($data));
        Zend_Debug::dump($curl->getRequest());
        $curl->getRequest();
        if($curl->getResponseCode() == 200){
            return $curl->getJson();
        } else {
            return false;
        }
    }

    /** Get the api key
     * @return mixed
     * @access public
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /** Set the api key
     * @access public
     * @param mixed $apiKey
     * @return Pas_Service_What3words
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    /** Get the language to use
     * @return string
     * @access public
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /** Set the language
     * @param string $language
     * @return Pas_Service_What3words
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
        return $this;
    }
}
