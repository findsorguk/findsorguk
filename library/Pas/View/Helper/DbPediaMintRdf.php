<?php

/**
 * A view helper to render ruler information
 *
 * A single use view helper for getting data from dbpedia.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->dbPediaMintRdf()->setUri($uri);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses Zend_Cache
 * @uses EasyRdf_Grap
 * @version 1
 * @since 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_View_Helper_DbPediaMintRdf extends Zend_View_Helper_Abstract
{
    /** The RDF client
     * @access protected
     * @var
     */
    protected $_client;

    /** The cache object
     * @access protected
     */
    protected $_cache;

    /** The uri to parse
     * @access protected
     */
    protected $_uri;

    /** The language to parse
     */
    const LANGUAGE = 'en';

    /** The class to return
     * @access public
     * @returns \DbPediaMintRdf
     */
    public function DbPediaMintRdf()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this;
    }

    /** Set the uri
     * @access public
     * @returns string
     */
    public function setUri($uri)
    {
        if (isset($uri)) {
            $this->_uri = $uri;
        } else {
            throw new Pas_Exception_Url('No uri set');
        }
        return $this;
    }

    /** Get the graph to parse
     * @access protected
     * @returns object
     */
    protected function getData()
    {
        $key = md5($this->_uri);
        if (!($this->_cache->test($key))) {
            $graph = new \EasyRdf\Graph($this->_uri);
            $graph->load();
            $data = $graph->resource($this->_uri);
            $this->_cache->save($data);
        } else {
            $data = $this->_cache->load($key);
        }

        return $data;
    }

    /** Render the html
     * @access protected
     * @returns string
     */
    protected function _render()
    {
        $d = $this->getData();
        $html = '';
        if ($d->get('dbpediaowl:thumbnail')) {
            $html .= '<a href="' . $d->get('foaf:depiction') . '" rel="lightbox"><img src="';
            $html .= $d->get('dbpediaowl:thumbnail');
            $html .= '" class="pull-right"/></a>';
        }

        $html .= '<ul>';
        $html .= '<li>Preferred label: ' . $d->label(self::LANGUAGE) . '</li>';

	if (!empty($d->get('geo:lat')))
	{
	   $html .= '<li>Geo coords: ' . $d->get('geo:lat') . ',' . $d->get('geo:long') . '</li>';
	}

        if ($d->get('dbpediaowl:elevation')) {
            $html .= '<li>Elevation: ' . $d->get('dbpediaowl:elevation') . ' (Max: ' . $d->get('dbpediaowl:maximumElevation') . ' Min: ' . $d->get('dbpediaowl:minimumElevation') . ')</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /** The to string function
     * @access public
     * @returns string
     */
    public function __toString()
    {
        return $this->_render();
    }
}
