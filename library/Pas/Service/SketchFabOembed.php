<?php

/** A service class for shortening and expanding links with goo.gl
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $shortener = new Pas_Service_GoogleShortUrl($key);
 * $url = $shortener->expand($url);
 * $analytics = $shortener->analytics($url);
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Service
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_Service_SketchFabOembed
{

    /** The Google short url
     *
     */
    const SFAB = 'https://sketchfab.com/oembed';

    protected $_url;

    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }

    public function getUrl()
    {
        return 'https://sketchfab.com/models/' . $this->_url;
    }

    /** Get analytics for a URL
     * @access public
     * @param string $shortUrl
     * @return object $response
     */
    public function getOembed()
    {
        $client = new Zend_Http_Client();
        $client->setUri(self::SFAB);
        $client->setMethod(Zend_Http_Client::GET);
        $client->setParameterGet('url', $this->getUrl());
        $response = $client->request();
        if ($response->isSuccessful()) {
            return $this->getDecode($response);
        } else {
            return false;
        }
    }

    /** Decode the response from JSON
     * @access public
     * @param string $response
     * @return object $json
     */
    public function getDecode($response)
    {
        $data = $response->getBody();
        $json = json_decode($data);
        return $json;
    }

}