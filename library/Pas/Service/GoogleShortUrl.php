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
class Pas_Service_GoogleShortUrl {

    /** The Google short url
     *
     */
    const GOOGLE = 'https://www.googleapis.com/urlshortener/v1/url';

    /** Message for invalid url
     *
     */
    const INVALIDURL = 'Your entry is not a valid URL.';

    /** Message for short invalid url
     *
     */
    const INVALIDSHORTURL = 'That is not a valid google shortened url';

    /** The google short url
     *
     */
    const GOOGLEURL = 'goo.gl';

    /** The api object
     * @access protected
     * @var object
     */
    protected $_api;

    /** Constructor
     * @acess public
     * @param string $key The Google api key
     * @return void
     */
    public function __construct( $key ) {
        $this->_api = self::GOOGLE . '?key=' . $key;
    }

    /** Function to shorten a given url
     * @access public
     * @param string $url URL to shorten
     * @return object $reponse Shortened URL
     */
    public function shorten( $url ) {
        $uri = $this->checkUrl( (string) $url );
        $response = $this->send($uri, true);
        return $response;
    }

    /** Expand a url from goo.gl's api
     * @access public
     * @param string $url URL to expand
     * @return object $response
     */
    public function expand($url ) {
        $uri = $this->checkShortUrl( $url );
        $response = $this->send($uri, false);
        return $response;
    }

    /** Get analytics for a URL
     * @access public
     * @param string $shortUrl
     * @return object $response
     */
    public function analytics($shortUrl){
        $url = $this->checkShortUrl( $shortUrl );
        $client = new Zend_Http_Client();
        $client->setUri($this->_api);
        $client->setMethod(Zend_Http_Client::GET);
        $client->setParameterGet('shortUrl', $shortUrl);
        $client->setParameterGet('projection', 'FULL');
        $response = $client->request();

        if($response->isSuccessful()){
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
    public function getDecode($response){
        $data = $response->getBody();
        $json = json_decode($data);
        return $json;
    }

    /** Check the request status
     * @access public
     * @param object $response
     */
    public function getStatus($response) {
        $code = $response->getStatus();
        switch($code) {
            case ($code == 200):
                return true;
                break;
            case ($code == 400):
                throw new Exception('Bad request made');
                break;
            case ($code == 404):
                throw new Exception('The resource could not be found');
                break;
            case ($code == 406):
                throw new Exception('You asked for an unknown representation');
                break;
            default;
                return false;
                break;
        }
    }

    /** Check that the URL is valid
     * @access public
     * @param string $url to validate
     * @return string $url
     */
    public function checkUrl($url) {
        if (!Zend_Uri::check($url)) {
            throw new Pas_Exception_Url(self::INVALIDURL);
        }
        return $url;
    }

    /** Check the short URL is valid as a goo.gl one
     * @access private
     * @param string $url
     * @return string $url
     * @throws Exception
     */
    private function checkShortUrl($url){
        $shorturl = parse_url($url);
        if($shorturl['host'] === self::GOOGLEURL){
            return $url;
        } else {
            throw new Exception(self::INVALIDSHORTURL);
        }
    }
    /** Send a url for shortening
     * @access private
     * @param string $url
     * @param boolean $short
     */
    private function send($url, $short = true) {
        if($short){
            $options = array(
                CURLOPT_URL => $this->_api,
                CURLOPT_POST => true,
                CURLOPT_HEADER => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER =>  1,
                );
            $config = array(
                'adapter'   => 'Zend_Http_Client_Adapter_Curl',
                'curloptions' => $options
                    );
            $client = new Zend_Http_Client( $this->_api, $config );
            $client->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'application/json');
            $client->setMethod(Zend_Http_Client::POST);
            $client->setRawData(json_encode(array("longUrl"=>$url)));
        } else {
        $options = array(
            CURLOPT_URL => $this->_api . '&shortUrl=' . $url,
            CURLOPT_SSL_VERIFYPEER => 0,
            URLOPT_RETURNTRANSFER =>  1,
            );
        $config = array(
            'adapter'   => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => $options
        );
        $client = new Zend_Http_Client( $this->_api, $config );
        }
        $response = $client->request();
        if($response->isSuccessful()) {
            $code = $this->getStatus($response);
            $header = $response->getHeaders();
            if($code == true && $header != 'text/html;charset=UTF-8'){
                return $this->getDecode($response);
            } else {
                    return NULL;
            }
        } else {
                return NULL;
        }
    }

}