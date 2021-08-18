<?php
/** A view helper for rendering Sketchfab retrieved 3D models
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->sketchFab()->setModelID($this->modelID)->setWidth('100%')->setHeight(300);
 * ?>
 * </code>
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * Created by PhpStorm.
 * User: danielpett
 * Date: 12/12/14
 * Time: 10:36
 */

class Pas_View_Helper_SketchFab extends Zend_View_Helper_Abstract {

    /** Sketchfab base url
     * @access protected
     * @var null
     */
    protected $_sketchfabBaseUrl = NULL;

    /** Set the width
     * @access protected
     * @var null
     */
    protected $_width = NULL;

    /** Set the height
     * @var null
     * @access protected
     */
    protected $_height = NULL;

    /** The model id for parsing via sketchfab
     * @access protected
     * @var null
     */
    protected $_modelID = NULL;


    /** Constructor
     * @access public
     */
    function __construct() {
        $this->_sketchfabBaseUrl = Zend_Registry::get('config')->webservice->sketchfab->toArray();
    }

    /** Get the height
     * @return mixed
     * @access public
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /** Set the height
     * @access public
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    /** Get the model ID
     * @return mixed
     * @access public
     */
    public function getModelID()
    {
        return $this->_modelID;
    }

    /** Set the model ID
     * @access public
     * @param mixed $modelID
     */
    public function setModelID($modelID)
    {
        $this->_modelID = $modelID;
        return $this;
    }

    /** Get the width
     * @access public
     * @return mixed
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /** Set the width
     * @access public
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    /** Send the string to view
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml();
    }

    /** The class to sketchfab the model
     * @access public
     * @return \Pas_View_Helper_SketchFab
     */
    public function sketchFab()
    {
        return $this;
    }

    /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $options = array(
            'modelID' => $this->getModelID(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'baseurl' => $this->_sketchfabBaseUrl['baseurl']
        );
        $html = '';
        if(isset($options['modelID'])) {
            $html .= $this->view->partial('partials/database/3D/model.phtml', $options);
        }
        return $html;
    }
} 