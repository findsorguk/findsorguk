<?php

/**  A view helper for rendering linked data retrieved from the RDF nomisma sparql endpoint for RRC types.
 *
 * This helper takes the moneyer name and injects it into a sparql query and formats the results.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->nomismaRrcTypes()->setUri('augustus');
 * ?>
 *
 * </code>
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
class Pas_View_Helper_NomismaRrcTypes extends Zend_View_Helper_Abstract
{
    /** The language to parse */
    const LANGUAGE = 'en';

    /** The nomisma endpoint */
    const NOMISMA = 'http://nomisma.org/query';

    /**  The cache object
     * @returns \Zend_Cache
     * @var $_cache
     */
    protected $_cache;

    /** The URI to parse
     * @access protected
     * @var $_uri
     */
    protected $_uri;

    /** The main class
     * @access public
     */
    public function nomismaRrcTypes()
    {
        return $this;
    }

    /** Render the html
     * @return string
     * @access public
     */

    public function __toString()
    {
        if ($this->getData()) {
            return $this->_render($this->getData());
        } else {
            return 'No RRC types available';
        }
    }

    /** Get the data for rendering
     * @access public
     * @return
     * */
    public function getData()
    {
        $nomisma = new Nomisma();
        return $nomisma->getRRCTypes($this->getUri());
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

    /** Render the data
     * @access protected
     * @param  array $data
     */
    public function _render($data)
    {
        $html = '';
        $types = array();
        foreach ($data as $rrc) {
            $types[] = array(
                'type' => $rrc->type,
                'label' => $rrc->label,
                'startDate' => $rrc->startDate,
                'endDate' => $rrc->endDate
            );
        }
        if (!empty($types)) {
            $html .= '<h3 class="lead">Types issued</h3>';
            $html .= '<ul>';
            $html .= $this->view->partialLoop('partials/numismatics/roman/rrcTypes.phtml', $types);
            $html .= '</ul>';
        }

        return $html;
    }

    /** Get the cache object
     * @return mixed
     * @access public
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }
}