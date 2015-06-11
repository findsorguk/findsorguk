<?php

/**
 * LatestRecords helper
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses viewHelper Pas_View_Helper
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @version 1
 */
class Pas_View_Helper_LatestRecordsPublications extends Zend_View_Helper_Abstract
{
    /** The Solarium client
     * @access protected
     * @var Solarium_Client
     */
    protected $_solr;

    /** The solr config
     * @var array
     * @access protected
     */
    protected $_solrConfig;

    /** The config object
     * @access protected
     * @var mixed
     * @return \Zend_Config
     */
    protected $_config;

    /** The cache object
     * @access protected
     * @var mixed
     * @return \Zend_Cache
     */
    protected $_cache;

    /** The allowed array
     * @var array
     * @access protected
     */
    protected $_allowed = array(
        'fa', 'flos', 'admin',
        'treasure', 'hoard'
    );

    /** Construct the class
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = $this->_config->solr->toArray();
        $this->_solrConfig['core'] = 'bibliography';
        $this->_solr = new Solarium_Client(array('adapteroptions' => ($this->_solrConfig)));
    }

    /** Get the user role
     * @access public
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        if ($user->getPerson()) {
            $role = $user->getPerson()->role;
        } else {
            $role = NULL;
        }

        return $role;
    }

    /** Get the latest records back from solr
     *
     */
    public function latestRecordsPublications($q = '*:*', $fields = 'id,old_findID,objecttype,imagedir,filename,thumbnail,broadperiod,workflow', $start = 0, $limit = 4,
                                              $sort = 'id', $direction = 'desc')
    {
        $select = array(
            'query' => $q,
            'start' => $start,
            'rows' => $limit,
            'fields' => array($fields),
            'sort' => array($sort => $direction),
            'filterquery' => array(),
        );
        if (!in_array($this->getRole(), $this->_allowed)) {
            $select['filterquery']['workflow'] = array(
                'query' => 'workflow:[3 TO 4]'
            );
        }
        $select['filterquery']['images'] = array(
            'query' => 'thumbnail:[1 TO *]'
        );
        $cachekey = md5($q . $this->getRole() . 'biblio');
        if (!($this->_cache->test($cachekey))) {
            $query = $this->_solr->createSelect($select);
            $resultset = $this->_solr->select($query);
            $data = array();
            $data['numberFound'] = $resultset->getNumFound();
            foreach ($resultset as $doc) {
                $fields = array();
                foreach ($doc as $key => $value) {
                    $fields[$key] = $value;
                }
                $data['images'][] = $fields;
            }
            $this->_cache->save($data);
        } else {
            $data = $this->_cache->load($cachekey);
        }

        return $this->buildHtml($data);
    }

    /** Build html
     * @access public
     * @return string
     */
    public function buildHtml($data)
    {
        if (array_key_exists('images', $data)) {
            $html = '<h3 class="lead">Referenced finds recorded with images</h3>';
            $html .= '<p>We have recorded ' . $data['numberFound'] . ' examples.</p>';
            $html .= '<div id="latest">';
            $html .= $this->view->partialLoop('partials/database/images/imagesPaged.phtml', $data['images']);
            $html .= '</div>';

            return $html;
        } else {
            return false;
        }
    }

}
