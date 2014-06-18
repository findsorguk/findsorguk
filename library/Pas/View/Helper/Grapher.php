<?php
/**
 * A view helper for creating pie graphs
 *
 * This is a pretty lame view helper.
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->grapher()
 * ->setType('pie')
 * ->setTitle('Finds by period')
 * ->setData($data);
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @example /app/modules/database/views/scripts/statistics/institution.phtml
 */
class Pas_View_Helper_Grapher {

    /** The default type
     * @access protected
     * @var string
     */
    protected $_type = 'pie';

    /** The default title
     * @access protected
     * @var string
     */
    protected $_title = 'A pie chart';

     /** The default data
     * @access protected
     * @var array
     */
    protected $_data = null;

    /** The default width
     * @access protected
     * @var int
     */
    protected $_width = 450;

    /** The default height
     * @access protected
     * @var int
     */
    protected $_height = 450;

    /** Get the type
     * @access public
     * @return string
     */
    public function getType() {
        return $this->_type;
    }

    /** Get the title
     * @access public
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /** get the data to graph
     * @access public
     * @return array
     */
    public function getData() {
        return $this->_data;
    }

    /** Set the type
     * @access public
     * @param string $type
     * @return \Pas_View_Helper_Grapher
     */
    public function setType($type) {
        $this->_type = $type;
        return $this;
    }

    /** Set the title
     * @access public
     * @param string $title
     * @return \Pas_View_Helper_Grapher
     */
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    /** Set the data
     * @access public
     * @param array $data
     * @return \Pas_View_Helper_Grapher
     */
    public function setData( array $data) {
        $this->_data = $data;
        return $this;
    }

    /** Get the width of the graph
     * @access public
     * @return int
     */
    public function getWidth() {
        return $this->_width;
    }

    /** Get the height of the graph
     * @access public
     * @return int
     */
    public function getHeight() {
        return $this->_height;
    }

    /** Set the width
     * @access public
     * @param int $width
     * @return \Pas_View_Helper_Grapher
     */
    public function setWidth($width) {
        $this->_width = $width;
        return $this;
    }

    /** Set the height
     * @access public
     * @param int $height
     * @return \Pas_View_Helper_Grapher
     */
    public function setHeight($height) {
        $this->_height = $height;
        return $this;
    }


    /** The base function to return
     * @access public
     * @return \Pas_View_Helper_Grapher
     */
    public function grapher(){
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildGraph(
                $this->getType(),
                $this->getTitle(),
                $this->getData(),
                $this->getWidth(),
                $this->getHeight()
                );
    }

    /** Build the graph string
     *
     * @param string $type
     * @param string $title
     * @param array $data
     * @param int $width
     * @param int $height
     * @return \Pas_GoogChart
     */
    public function buildGraph($type, $title, array $data, $width, $height) {
        $chart = new Pas_GoogChart();
        $color = array(
                '#99C754',
                '#54C7C5',
                '#999999',
            );

        $chart->setChartAttrs( array(
            'type' => 'pie',
            'data' => $data,
            'size' => array( $width, $height ),
            'color' => $color,
            'title' => $title,
            ));
        return $chart;
    }
}