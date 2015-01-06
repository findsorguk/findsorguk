<?php

/**
 * A view helper for retrieving the geographic boundaries of a parliamentary
 * constituency
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $thos->twfyGeo()->setData($data);
 * ?>
 * </code>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @see  http://www.theyworkforyou.com/ for documentation
 */
class Pas_View_Helper_TwfyGeo extends Zend_View_Helper_Abstract
{

    /** The data
     * @access protected
     * @var array
     */
    protected $_data;

    /** Get the data to use
     * @access public
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /** Set the data to query
     * @access public
     * @param  array $data
     * @return \Pas_View_Helper_TwfyGeo
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_TwfyGeo
     */
    public function twfyGeo()
    {
        return $this;
    }

    /** Get the data for building the map
     * @access public
     * @return method
     */
    public function getMap()
    {
        $data = $this->getData();
        $geo = new Pas_Twfy_Geometry;
        $constituency = $geo->get($data['constituency']);
        return $this->buildMap($constituency, $data);
    }

    /** Build the map
     * @access public
     * @param  type $geo
     * @param  type $data
     * @return string
     */
    public function buildMap($geo, $data)
    {
        $html = '';

        if (!array_key_exists('error', $geo)) {
            $html .= $this->view->partial('partials/news/map.phtml', get_object_vars($geo));
            $html .= $this->view->osDataToConst()->setConstituency($geo->name);
            $html .= $this->view->findsOfNoteConst()->setConstituency($geo->name);
            $html .= $this->view->findsWithinConst()->setConstituency($geo->name);
            $html .= $this->view->politicalHouse()->setHouse($data['house']);
        }
        return $html;
    }

    /** The string to return
     * @access public
     * @return type
     */
    public function __toString()
    {
        return $this->getMap();
    }
}
