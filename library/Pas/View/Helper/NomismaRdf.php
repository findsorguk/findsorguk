<?php

/**  A view helper for rendering linked data retrieved from the RDF nomisma graph
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Rdf_Client
 * @uses viewHelper Pas_View_Helper
 * @uses EasyRdfGraph
 * @uses Zend_Cache
 */
class Pas_View_Helper_NomismaRdf extends Zend_View_Helper_Abstract
{
    /** The language to parse
     *
     */
    const LANGUAGE = 'en';
    /**  The cache object
     * @returns \Zend_Cache
     * @var $_cache
     */
    protected $_cache;

    /** The URI to parse
     * @var $_uri
     */
    protected $_uri;

    /** The main class
     * @access public
     */
    public function nomismaRdf()
    {
        return $this;
    }

    /** Render the html
     * @access public
     * @return string
     */
    public function __toString()
    {
        if ($this->getData()) {
            return $this->_render($this->getData());
        } else {
            return 'Nothing returned from Nomisma at this time';
        }
    }

    /** Get the data for rendering
     * @access public
     * @return
     * */
    public function getData()
    {
        $key = md5($this->getUri());
        if (!($this->getCache()->test($key))) {
            $request = new \EasyRdf\Http\Client();
            $request->setUri($this->getUri());
            $response = $request->request()->getStatus();
            if ($response == 200) {
                $graph = new \EasyRdf\Graph($this->_uri);
                $graph->load();
                $data = $graph->resource($this->_uri);
            } else {
                $data = NULL;
            }
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }

    /** Get the uri
     * @access public
     * */
    public function getUri()
    {
        return $this->_uri;
    }

    /** Set the uri
     * @access public
     * @param string $uri
     * @todo add in validation of uri
     */
    public function setUri($uri)
    {
        if (!is_null($uri)) {
            $this->_uri = $uri;
        } else {
            return false;
        }
        return $this;
    }

    /** Get the cache object
     * @return mixed
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Render the data
     * @access protected
     * @param  EasyRdf_Resource $data
     */
    protected function _render(\EasyRdf\Resource $data)
    {
	//Get geo coordinates
	$kml = new SimpleXMLElement(file_get_contents($this->_uri . '.kml'));
	$getCoordinates = (string)$kml->Document->Placemark->Point->coordinates;
	$geoCoords  = explode(",", $getCoordinates);

	//Get API key for google map
	$config = Zend_Registry::get('config');
	$apiKey = $config->webservice->googlemaps->apikey;

        $html = '';
        if (is_object($data)) {
            $html .= '<img src="https://maps.google.com/maps/api/staticmap?key=' . $apiKey  . '&center=' . $geoCoords[1]
                . ',' . $geoCoords[0] . '&zoom=6&size=400x200&maptype=hybrid&markers=color:green|label:G|'
                . $geoCoords[1] . ',' . $geoCoords[0] . '" class="stelae"/>';
            $html .= '<ul>';

            foreach ($data->all('skos:prefLabel') as $labels) {
                $html .= '<li>Preferred label: ' . $labels->getValue() . ' (' . $labels->getLang() . ')</li>';
            }

            $html .= '<li>Geo coords: ' . $geoCoords[1];
            $html .= ',';
            $html .= $geoCoords[0];
            $html .= '</li>';
            $html .= '<li>Definition: ' . $data->get('skos:definition');
            $html .= '</li></ul>';
        }
        return $html;
    }
}
