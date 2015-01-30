<?php

/**
 * An action helper for sending updates to our solr instances via the controller
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $this->_helper->solrUpdater->update($model, $insertData, $type);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 *
 */
class Pas_Controller_Action_Helper_SolrUpdater extends Zend_Controller_Action_Helper_Abstract
{

    /** The list of cores available
     * @access protected
     * @var array
     */
    protected $_cores = array(
        'objects', 'people', 'images',
        'publications', 'bibliography', 'content',
        'geodata', 'coinsummary', 'tags'
    );

    /** The solr object
     * @access protected
     * @var
     */
    protected $_solr;

    /** The config object
     * @access protected
     * @var \Zend_Config
     */
    protected $_config;

    /** The constructor
     * @access public
     */
    public function __construct()
    {
        $this->_config = Zend_Registry::get('config')->solr->toArray();
    }

    /** Get the solr configuration for a core
     * @access public
     * @param string $core
     * @return \Solarium_Client
     * @throws Exception
     */
    public function getSolrConfig($core)
    {
        if (in_array($core, $this->_cores)) {
            $solrAdapter = $this->_config;
            $solrAdapter['core'] = $core;
            $solr = new Solarium_Client(
                array(
                    'adapteroptions' =>
                        $solrAdapter
                ));
            return $solr;
        } else {
            throw new Exception('That core does not exist', 500);
        }
    }

    /** get the cores array
     * @return array
     */
    public function getCores()
    {
        return $this->_cores;
    }

    /** Update a core
     * @access public
     * @param string $core
     * @param integer $id
     * @param string $type
     * @return integer
     */
    public function update($core, $id, $type = null)
    {
        $data = $this->getUpdateData($core, $id, $type);
        $solr = $this->getSolrConfig($core);
        $update = $solr->createUpdate();
        $doc = $update->createDocument();

        foreach ($data as $k => $v) {
            $doc->$k = $v;
        }

        $update->addDocument($doc);
        $update->addCommit();
        return $solr->update($update);
    }

    /** Delete by an id number
     * @access public
     * @param string $core
     * @param integer $id
     * @return integer
     */
    public function deleteById($core, $id)
    {
        $this->_solr = $this->getSolrConfig($core);
        $update = $this->_solr->createUpdate();
        $update->addDeleteByID($this->_getIdentifier($core) . $id);
        $update->addCommit();
        return $this->_solr->update($update);
    }

    /** Get the preferred identifier by core
     * @access protected
     * @param string $core
     * @param string type
     * @return string
     * @throws Exception
     */
    protected function _getIdentifier($core, $type = null)
    {
        if (is_null($type)) {
            if (in_array($core, $this->getCores())) {
                switch ($core) {
                    case 'objects':
                        $identifier = 'finds-';
                        break;
                    case 'people':
                        $identifier = 'people-';
                        break;
                    case 'content':
                        $identifier = 'content-';
                        break;
                    case 'bibliography':
                        $identifier = 'biblio-';
                        break;
                    case 'images':
                        $identifier = 'images-';
                        break;
                    case 'publications':
                        $identifier = 'publications-';
                        break;
                    case 'tags':
                        $identifier = 'tags';
                        break;
                    case 'coinsummary':
                        $identifier = 'coinsummary-';
                        break;
                }
            }
        } elseif (in_array($type, array('hoards', 'artefacts', 'news'))) {
            switch ($type) {
                case 'artefacts':
                    $identifier = 'finds-';
                    break;
                case 'hoards':
                    $identifier = 'hoards-';
                    break;
                case 'news':
                    $identifier = 'news-';
                    break;
            }
        } else {
            throw new Exception('That core does not exist', 500);
        }
        return $identifier;
    }

    /** Get update data for a core
     * @access public
     * @param string $core
     * @param integer $id
     * @param boolean $type
     * @return array
     * @throws Exception
     */
    public function getUpdateData($core, $id, $type = null)
    {
        if (in_array($core, $this->getCores())) {
            if (in_array($type, array('artefacts'))) {
                switch ($core) {
                    case 'objects':
                        $model = new Finds();
                        break;
                    case 'images':
                        $model = new Slides();
                        break;
                    case 'coinsummary':
                        $model = new CoinSummary();
                        break;
                    default:
                        throw new Exception('Your core does not exist', 500);
                }
            } elseif(in_array($core, array('images', 'coinsummary'))){
                switch ($core) {
                    case 'images':
                        $model = new Slides();
                        break;
                    case 'coinsummary':
                        $model = new CoinSummary();
                        break;
                    default:
                        throw new Exception('Your core does not exist', 500);
                }
              }
            } elseif ($type == 'hoards') {
                switch ($core) {
                    case 'objects':
                        $model = new Hoards();
                        break;
                }
            } elseif($type == 'news') {
                $model = new News();
            } elseif($type == 'content') {
                $model == new Content();
            } elseif($type == 'events'){
                $model = new Events();
            } else if(is_null($type))  {
                switch ($core) {
                    case 'people':
                        $model = new People();
                        break;
                    case 'bibliography':
                        $model = new Bibliography();
                        break;
                    case 'publications':
                        $model = new Publications();
                        break;
                    case 'tags':
                        $model = new SemanticTags();
                        break;
                    case 'slides':
                        $model = new Slides();
                        break;
                }
            }
            $data = $model->getSolrData($id);
            $cleanData = $this->cleanData($data[0]);
            return $cleanData;
        } else {
            throw new Exception('That core does not exist', 500);
        }
    }

    /** Clean the data up
     * @access public
     * @param array $data
     * @return array
     */
    public function cleanData(array $data)
    {
        if (array_key_exists('datefound1', $data)) {
            if (!is_null($data['datefound1'])) {
                $df1 = $data['datefound1'] . 'T00:00:00Z';
                $data['datefound1'] = $df1;
            } else {
                $data['datefound1'] = null;
            }
        }
        if (array_key_exists('datefound2', $data)) {
            if (!is_null($data['datefound2'])) {
                $df2 = $data['datefound2'] . 'T00:00:00Z';
                $data['datefound2'] = $df2;
            } else {
                $data['datefound2'] = null;
            }
        }
        if (array_key_exists('created', $data)) {
            if (!is_null($data['created'])) {
                $created = $this->todatestamp($data['created']);
                $data['created'] = $created;
            } else {
                $data['created'] = null;
            }
        }
        if (array_key_exists('updated', $data)) {
            if (!is_null($data['updated'])) {
                $updated = $this->todatestamp($data['updated']);
                $data['updated'] = $updated;
            } else {
                $data['updated'] = null;
            }
        }
        foreach ($data as $k => $v) {
            $data[$k] = strip_tags($v);
            if (is_null($v) || $v === "") {
                unset($data[$k]);
            }
        }
        return $data;
    }


    /** Format the date and return as unix stamp
     * @access public
     * @param Zend_Date $date
     * @return string
     */
    public function todatestamp($date)
    {
        $st = strtotime($date);
        $zendDate = new Zend_Date();
        $zendDate->set($st);
        return substr($zendDate->get(Zend_Date::W3C), 0, -6) . 'Z';
    }
}