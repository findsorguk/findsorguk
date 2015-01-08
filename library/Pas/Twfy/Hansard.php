<?php

/** Retrieve a list of parliamentary mentions for PAS
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $twfy = new Pas_Twfy_Hansard();
 * $arts = $twfy->get($search, $this->getPage(), 20);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Hansard
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Twfy
 * @see http://www.theyworkforyou.com/api/docs/getHansard
 * @example /app/modules/news/controllers/TheyworkforyouController.php
 */
class Pas_Twfy_Hansard extends Pas_Twfy
{

    protected $_search;

    protected $_page = 1;

    protected $_limit = 20;

    protected $_order = 'd';

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->_search;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search)
    {
        $this->_search = $search;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->_page = $page;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->_order = $order;
        return $this;
    }


    /** Basic method call
     *
     */
    const METHOD = 'getHansard';

    /** Retrieve data
     * @access public
     * @param string $search
     * @param int $page
     * @param int $limit
     * @param string $order
     * @return array
     */
    public function fetchData()
    {
        $params = array(
            'key' => $this->_apikey,
            'order' => $this->getOrder(),
            'search' => $this->getSearch(),
            'num' => $this->getLimit(),
            'page' => $this->getPage()
        );
        return parent::get(self::METHOD, $params);
    }


}

