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
 * echo $this->dbPediaRulerRdf()->setUri($uri);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses Zend_Cache
 * @uses EasyRdf_Graph
 * @version 1
 * @since 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_View_Helper_DbPediaRulerRdf extends Zend_View_Helper_Abstract
{
    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The array of namespaces to use
     * @access protected
     * @var array
     */
    protected $_nameSpaces = array(
        'cat' => 'http://dbpedia.org/resource/Category:',
        'dbpedia' => 'http://dbpedia.org/resource/',
        'dbo' => 'http://dbpedia.org/ontology/',
        'dbp' => 'http://dbpedia.org/property/'
    );

    /** Set new name spaces if so desired
     * @access public
     * @param array $nameSpaces
     * @return \Pas_View_Helper_SparqlEasy
     */
    public function setNameSpaces(array $nameSpaces)
    {
        $this->_nameSpaces = $nameSpaces;
        return $this;
    }

    /** Get the namespaces
     * @access public
     * @return array
     */
    public function getNameSpaces()
    {
        return $this->_nameSpaces;
    }

    /** Register the namespaces with EasyRdf
     * @access public
     * @return \Pas_View_Helper_SparqlEasy
     */
    public function registerNameSpaces()
    {
        foreach ($this->getNameSpaces() as $k => $v) {
            \EasyRdf\RdfNamespace::set($k, $v);
        }
        return $this;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** The uri to query
     * @access protected
     * @var string
     */
    protected $_uri;

    /** The language to filter
     *
     */
    const LANGUAGE = 'en';

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_DbPediaRulerRdf
     */
    public function dbPediaRulerRdf()
    {
//        $this->registerNameSpaces();
        return $this;
    }

    /** Set the uri to query
     * @access public
     * @param string $uri
     * @return \Pas_View_Helper_DbPediaRulerRdf
     * @throws Pas_Exception_Url
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

    /** Get the uri to query
     * @access public
     * @return string
     */
    public function getUri()
    {
        return $this->_uri;
    }


    /** Get the data from rdf graph
     * @access protected
     * @return object
     */
    protected function getData()
    {
        $uri = $this->getUri();
        $key = md5($uri);
        if (!($this->getCache()->test($key))) {
            $graph = new \EasyRdf\Graph($uri);
            $graph->load();

            $data = $graph->resource($uri);
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($key);
        }
        $this->registerNameSpaces();
        return $data;
    }

    /** Clean the string for dbpedia uri
     * @access protected
     * @param string $string
     * @return type
     */
    protected function _cleaner($string)
    {
        $html = str_replace(array('http://dbpedia.org/resource/', 'Category:',
            '_'), array('', '', ' '), $string);
        return $html;
    }

    /** Clean out wikipedia link
     * @access public
     * @param string $string
     * @return string
     */
    protected function _wikiLink($string)
    {
        if(!is_array($string)) {
            $cleaned = str_replace(array('http://dbpedia.org/resource/'),
                array('http://en.wikipedia.org/wiki/'), $string);
            $html = '<a href="';
            $html .= $cleaned;
            $html .= '">';
            $html .= urldecode($this->_cleaner($string));
            $html .= '</a>';
        } else {
            $html = '';
        }
        return $html;
    }

    /** return the html
     * @access protected
     * @return string
     */
    protected function _render()
    {
        $html = '';
        $d = $this->getData();
//        echo '<pre>';
//        echo $d->dump('text');
//        echo '</pre>';
        $html .= '<h3 class="lead">Information from Wikipedia</h3>';
        if ($d->get('dbo:thumbnail')) {
            $html .= '<img src="';
            $html .= $d->get('dbo:thumbnail');
            $html .= '" class="pull-right stelae"/>';
        }
        $html .= '<ul>';
        $html .= '<li>Preferred label: ' . $d->label(self::LANGUAGE) . '</li>';
        $html .= '<li>Full names: <ul>';
        foreach ($d->all('foaf:name', 'literal') as $name) {
            $html .= '<li>';
            $html .= $name->getValue();
            $html .= '</li>';
        }
        $html .= '</li></ul>';
        $html .= '<li>Title: ' . implode(', ', $d->all('dbp:title', 'literal')) . '</li>';
        $html .= '<li>Predecessor: ' . $this->_cleaner(implode(', ', $d->all('dbo:predecessor', 'resource'))) . '</li>';
        $html .= '<li>Successor: ' . $this->_cleaner(implode(', ', $d->all('dbo:successor', 'resource'))) . '</li>';
        $html .= '<li>Definition: ' . $d->get('dbo:abstract', 'literal', self::LANGUAGE) . '</li>';

        $html .= '<li>Parents: <ul>';
        $html .= '<li>Father: ';
        $html .= $this->_wikiLink($d->get('dbp:father', 'resource'));
        $html .= '</li>';
        $html .= '<li>Mother: ';
        $html .= $this->_wikiLink($d->get('dbp:mother', 'resource'));
        $html .= '</li>';
        $html .= '</ul></li>';
        $birth = $d->all('dbp:birthPlace', 'resource');
        $newBirth = array();
        foreach ($birth as $nb) {
            $newBirth[] = $this->_wikiLink($nb);
        }
        $html .= '<li>Birth place: ' . implode(', ', $newBirth) . '</li>';
        $death = $d->all('dbp:deathPlace', 'resource');
        $reBirth = array();
        foreach ($death as $reb) {
            $reBirth[] = $this->_wikiLink($reb);
        }
        $html .= '<li>Death place: ' . implode(', ', $reBirth) . '</li>';
        $html .= '<li>Spouse: <ul>';
        foreach ($d->all('dbo:spouse', 'resource') as $name) {
            $html .= '<li>';
            $html .= $this->_wikiLink($name);
            $html .= '</li>';
        }
        $html .= '</ul></li>';
        $html .= '<li>Other title(s): <ul>';
        $titles = array();
        foreach ($d->all('dbp:title') as $name) {
            $titles[] = urldecode($this->_cleaner($name));
        }
        $new = array_unique($titles, SORT_STRING);
        foreach ($new as $n) {
            if (strlen($n) > 4) {
                $html .= '<li>';
                $html .= $n;
                $html .= '</li>';
            }
        }
        $html .= '</ul></li>';
        $html .= '<li>Came After: <ul>';
        foreach ($d->all('dbp:after') as $name) {
            if (strlen($name) > 4) {
                $html .= '<li>';
                $html .= $this->_cleaner($name);
                $html .= '</li>';
            }
        }
        $html .= '</ul></li>';
        $html .= '<li>Came before: <ul>';
        foreach ($d->all('dbp:before') as $name) {
            if (strlen($name) > 4) {
                $html .= '<li>';
                $html .= $this->_cleaner($name);
                $html .= '</li>';
            }
        }
        $html .= '</ul></li>';
        $subjects = $d->all('dcterms:subject', 'resource');
        $html .= '<li>Subjects on wikipedia: <ul>';
        foreach ($subjects as $subject) {
            $html .= '<li>';
            $html .= $this->_wikiLink($subject);
            $html .= '</li>';
        }
        $html .= '</ul></li>';
        $html .= '</ul>';
        return $html;
    }

    /** return the string
     * @access public
     */
    public function __toString()
    {
        return $this->_render();
    }

}
