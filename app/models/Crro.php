<?php

/** A class for interacting with Coins of the Roman Republic online
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $crro = new Crro();
 * $data = $crro->getInfo('cassius');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett / Trustees of the British Museum
 * @category Pas
 * @package Crro
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Crro
{

    const CRRO = 'http://numismatics.org/crro/id/';
    /** The cache object
     * @var  $_cache
     * @access protected
     */
    protected $_cache;

    /** Get the data for reuse based off sparql endpoint
     * @access public
     * @return array $data
     * */
    public function getInfo($identifier)
    {
        $key = md5($identifier . 'crro');
        $uri = self::CRRO . $identifier;
        if (!($this->getCache()->test($key))) {
            // use the CURL based HTTP client adaptor
            \EasyRdf\RdfNamespace::set('nm', 'http://nomisma.org/id/');
            \EasyRdf\RdfNamespace::set('nmo', 'http://nomisma.org/ontology#');
            \EasyRdf\RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
            \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
            $request = new \EasyRdf\Http\Client($uri);
            $response = $request->request()->getStatus();

            if ($response == 200) {
                $graph = new \EasyRdf\Graph($uri . '.rdf');
                try {
                    $graph->load();
                    $data = $graph->resource($uri);
                    $this->getCache()->save($data);
                }
                catch (\EasyRdf\Exception $e)
                {
                    echo 'Problem accessing Nomisma: ' . $e->getMessage() . ' - ';
                    $data = NULL;
                }
            } else {
                $data = NULL;
            }
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }

    /** Get the cache object
     * @access public
     * @return mixed
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }
}
