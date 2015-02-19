<?php
/** A View helper for rendering Zooming images
 * Currently only works with image/jpeg mime types.
 *
 * An example of code use:
 * <code>
 * <?php
 * echo $this->zoomify()->setData($data);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014, Daniel Pett
 * @example /app/modules/database/views/scripts/images/zoom.phtml
 */
class Pas_View_Helper_Zoomify extends Zend_View_Helper_Abstract
{

    /** The callback function
     * @access public
     * @return \Pas_View_Helper_Zoomify
     */
    public function zoomify()
    {
        return $this;
    }

    /** Create the data string for rendering
     * @return string
     */
    public function __toString()
    {
        $data = $this->getData();
        $this->createZoom($data);
        $html = '';
        if ($this->getMimeType($data) == 'image/jpeg') {
            if (array_key_exists('filename', $data)) {
                $html .= $this->view->partial('partials/database/images/zoomifyViewer.phtml', $this->getPath($data));
            }
        } else {
            $html .= 'That file type cannot be zoomed just yet. We are working on this.';
        }
        return $html;
    }

    /** The path variable
     * @var null
     */
    protected $_path = NULL;


    /** Get the path for the image
     * @param array $data
     * @return array|null
     */
    public function getPath(array $data)
    {
        $this->_path = array(
            'path' => $data['imagedir'] . 'zoom/' . basename($data['filename'], '.jpg') . '_zoomify/',

        );
        return $this->_path;
    }

    /** The data array
     * @access protected
     * @var $_data
     */
    protected $_data = array();

    /** Get the data array
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /** Set the data
     * @access public
     * @param mixed $data
     * @return \Pas_View_Helper_Zoomify
     */
    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    /** The image directory
     * @access protected
     * @var null
     */
    protected $_imageDir = NULL;

    /** The filename
     * @access protected
     * @var null
     */
    protected $_filename = NULL;


    /** The mime type
     * @access protected
     * @var null
     */
    protected $_mimeType = NULL;

    /**  Get the image directory of the image
     * @param array $data
     * @return mixed
     */
    public function getImageDir(array $data)
    {
        if (array_key_exists('username', $data)) {
            $this->_imageDir = $data['username'];
        }
        return $this->_imageDir;
    }

    /** Get the filename
     * @param array $data
     * @return mixed
     */
    public function getFilename(array $data)
    {
        if (array_key_exists('filename', $data)) {
            $this->_filename = $data['filename'];
        }
        return $this->_filename;
    }

    /** Get the mime type
     * @param array $data
     * @return null
     */
    public function getMimeType(array $data)
    {
        if (array_key_exists('mimetype', $data)) {
            $this->_mimeType = $data['mimetype'];
        }
        return $this->_mimeType;
    }


    /** Create the zooming image if it does not exist
     * @access public
     * @param array $data
     * @return void
     */
    public function createZoom(array $data)
    {
        if ($this->getMimeType($data) == 'image/jpeg') {
            $path = implode('/', array(IMAGE_PATH, $this->getImageDir($data)));
            $container = $path . '/zoom/' . basename($this->getFilename($data), '.jpg') . '_zoomify';
            if (!is_dir($container)) {
                $zoomify = new Pas_Zoomify_FileProcessor();
                $zoomify->setImagePath($path)
                    ->setFileName($this->getFilename($data))
                    ->setDebug(false)
                    ->zoomTheImage();
            }
        }
    }
}