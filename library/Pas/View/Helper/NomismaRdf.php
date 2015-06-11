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
    /**  The cache object
     * @returns \Zend_Cache
     * @var $_cache
     */
    protected $_cache;

    /** The URI to parse
     * @var $_uri
     */
    protected $_uri;

    /** The language to parse
     *
     */
    const LANGUAGE = 'en';

    /**
     * @return mixed
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }


    /** The main class
     * @access public
     */
    public function nomismaRdf()
    {
        return $this;
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

    /** Get the uri
     * @access public
     * */
    public function getUri()
    {
        return $this->_uri;
    }

    /** Get the data for rendering
     * @access public
     * @return
     * */
    public function getData()
    {

        $key = md5($this->getUri());
        if (!($this->getCache()->test($key))) {
            $request = new EasyRdf_Http_Client();
            $request->setUri($this->getUri());
            $response = $request->request()->getStatus();
            if ($response == 200) {
                $graph = new EasyRdf_Graph($this->_uri);
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

    /** Render the data
     * @access protected
     * @param  EasyRdf_Resource $dat
     */
    protected function _render(EasyRdf_Resource $data)
    {
        $html = '';
        if (is_object($data)) {
            $html .= '<img src="https://maps.google.com/maps/api/staticmap?center=' . $data->get('geo:lat')
                . ',' . $data->get('geo:long') . '&zoom=5&size=200x200&maptype=hybrid&markers=color:green|label:G|'
                . $data->get('geo:lat') . ',' . $data->get('geo:long') . '&sensor=false" class="stelae"/>';
            $html .= '<ul>';
            foreach ($data->all('skos:prefLabel') as $labels) {
                $html .= '<li>Preferred label: ' . $labels->getValue() . ' (' . $labels->getLang() . ')</li>';
            }
            $html .= '<li>Geo coords: ' . $data->get('geo:lat');
            $html .= ',';
            $html .= $data->get('geo:long');
            $html .= '</li>';
            $html .= '<li>Definition: ' . $data->get('skos:definition');
            $html .= '</li></ul>';
        }
        return $html;
    }

    /** Rebder tge html */
    public function __toString()
    {
        if($this->getData()){
            return $this->_render($this->getData());
        } else {
            return 'Nothing returned from Nomisma at this time';
        }
    }
}
