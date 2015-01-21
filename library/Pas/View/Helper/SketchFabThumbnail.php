<?php

/**
 * DomesdayNear helper
 *
 * A helper for finding out which entries in the Domesday book are near the
 * point of recording a PAS object.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->domesdayNear()->setLon(51.2)->setLat(-2.3)->setRadius(2);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses viewHelper Pas_View_Helper
 * @uses Zend_Cache
 * @uses Pas_Service_Domesday_Place
 * @version 1
 * @since 18/5/2014
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Pas_View_Helper
 * @example /app/views/scripts/partials/database/findSpot.phtml
 */
class Pas_View_Helper_SketchFabThumbnail extends Zend_View_Helper_Abstract
{

    protected $_modelID;

    /**
     * @return mixed
     */
    public function getModelID()
    {
        return $this->_modelID;
    }

    /**
     * @param mixed $modelID
     */
    public function setModelID($modelID)
    {
        $this->_modelID = $modelID;
        return $this;
    }


    /** the function to call
     * @access public
     * @return \Pas_View_Helper_DomesdayNear
     */
    public function sketchFabThumbnail()
    {
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $oembed = get_object_vars($this->getData());
        $modelID = array('modelID' => $this->getModelID());
        $data = array_merge($oembed, $modelID);
        $html = $this->view->partial('partials/database/3D/small.phtml', $data);
        return $html;
    }


    public function getData()
    {
        $service = new Pas_Service_SketchFabOembed();
        $service->setUrl($this->getModelID());
        return $service->getOembed();
    }

}