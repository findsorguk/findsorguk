<?php
/**
 * A view helper for displaying links for choosing number of results required.
 *
 * This helper is configurable for the number of results you want to display
 * at one time from the solr response.
 *
 * Example use:
 *
 * <code>
 * <?php
 * echo $this->resultsQuantityChooser()->setResults($results);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_View_Helper_ResultsQuantityChooser extends Zend_View_Helper_Abstract
{
   /**
    * The default quantities to return
    * @access public
    * @var array
    */
    protected $_quantities = array(10, 20, 40, 100);

    /** The results object
     * @access protected
     * @var type
     */
    protected $_results;

    /** Get the quantites
     * @access public
     * @return array
     */
    public function getQuantities() {
        return $this->_quantities;
    }

    /** Get the results
     * @access public
     * @return array
     */
    public function getResults() {
        return $this->_results;
    }

    /** Set the quantities if desired
     * @access public
     * @param array $quantities
     * @return \Pas_View_Helper_ResultsQuantityChooser
     */
    public function setQuantities(array $quantities) {
        $this->_quantities = $quantities;
        return $this;
    }

    /** Set the results
     * @access public
     * @param \ Zend_Paginator $results
     * @return \Pas_View_Helper_ResultsQuantityChooser
     */
    public function setResults(  Zend_Paginator $results) {
        $this->_results = $results;
        return $this;
    }

    /** The request object
     * @access public
     * @var object
     */
    protected $_request;

    /** Get the request
     * @access public
     * @return object
     */
    public function getRequest() {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        return $this->_request;
    }

    /** Generate the html url array
     * @access public
     * @param  Zend_Paginator $results
     * @return type
     */
    public function generate( Zend_Paginator $results){
        $html = '';
        if ($results) {
            $urls = array();
            $request = $this->getRequest();
            foreach ($this->getQuantities() as $quantity) {
                $request['show'] = $quantity;
                $urls[$quantity] = $this->view->url($request, 'default', true);
            }
            $html .= $this->_buildHtml($urls);
        }
        return $html;
    }

    /** Build final html
     * @access public
     * @param array $urls
     * @return string
     */
    protected function _buildHtml(array $urls) {
        $html = '<p>Records per page: ';
        $request = $this->getRequest();
        foreach ($urls as $k => $v) {
            $html .= '<a href="' . $v . '" title="show ' . $k . ' records" ';
            if (!array_key_exists('show', $request) &&  $k == 20) {
                $html .= ' class="label" ';
            }
            if (array_key_exists('show', $request) && $request['show'] == $k) {
                $html .= ' class="label-danger label" ';
            }
            $html .= '>' . $k . '</a> ';
        }
        $html .= '</p>';

        return $html;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ResultsQuantityChooser
     */
    public function resultsQuantityChooser() {
        return $this;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->generate($this->getResults());
    }

}