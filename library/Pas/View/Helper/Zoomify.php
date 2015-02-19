<?php
/**
 * Created by PhpStorm.
 * User: danielpett
 * Date: 19/02/15
 * Time: 11:05
 */

class Pas_View_Helper_Zoomify extends Zend_View_Helper_Abstract {

    public function zoomify()
    {
        return $this;
    }

    public function __toString()
    {
        $data = $this->getData();
        $this->createZoom($data);
        $html = '';
        if($this->getMimeType($data) == 'image/jpeg') {
            if (array_key_exists('filename', $data)) {
                $html .= $this->view->partial('partials/database/images/zoomifyViewer.phtml', $this->getPath($data));
            } else {
                $html .= 'That file type cannot be zoomed just yet. We are working on this.';
            }
        }
        return $html;
    }

    protected $_path = NULL;

    /**
     * @return mixed
     */
    public function getPath($data)
    {
        $this->_path = array(
            'path' => $data['imagedir'] . 'zoom/' . basename($data['filename'], '.jpg') . '_zoomify/',

        );
        return $this->_path;
    }



    protected $_data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    protected $_imageDir = NULL;

    protected $_filename = NULL;

    protected $_mimeType = NULL;
    /**
     * @return mixed
     */
    public function getImageDir($data)
    {
        if(array_key_exists('username', $data)){
            $this->_imageDir = $data['username'];
        }
        return $this->_imageDir;
    }

    /**
     * @return mixed
     */
    public function getFilename($data)
    {
        if(array_key_exists('filename', $data)){
            $this->_filename = $data['filename'];
        }
        return $this->_filename;
    }

    public function getMimeType($data)
    {
        if(array_key_exists('mimetype', $data)){
            $this->_mimeType = $data['mimetype'];
        }
        return $this->_mimeType;
    }


    /** Create the zoom tiles
     *
     */
    public function createZoom($data)
    {
        if($this->getMimeType($data) == 'image/jpeg') {
            $path = implode('/', array(IMAGE_PATH, $this->getImageDir($data)));
            $container = $path . '/zoom/' . basename($this->getFilename($data), '.jpg') . '_zoomify';
            if(!is_dir($container)) {
                $zoomify = new Pas_Zoomify_FileProcessor();
                $zoomify->setImagePath($path)
                    ->setFileName($this->getFilename($data))
                    ->setDebug(false)
                    ->zoomTheImage();
            }
        }
    }
}