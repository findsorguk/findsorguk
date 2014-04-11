<?php

/**
* Create a KML file
*
* Class for creating a KML file from a data source
* and outputing it to either a file or string
*
* PHP version 5
*
* @category  XML
* @package   Create_KML
* @author    Robert McLeod <hamstar@telescum.co.nz>
* @copyright 2009 Robert McLeod
* @license   http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version   SVN: 1.0
* @link      ??
*
*/

require_once 'Pas/KML/Exception.php';
require_once 'Pas/KML/Common.php';

/**
* Class to define a place to be added to the KML class
*
* @category XML
* @package  Create_KML
* @author   Robert McLeod <hamstar@telescum.co.nz>
* @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @link     ??
*/
class Pas_KML_Place extends Pas_KML_Common
{
    public $type = 'place';
    public  $folder = '**[root]**';
    public $id, $name, $desc, $style, $coords;

    /**
    * Encloses a string in CDATA escaping if it
    * contains html tags
    *
    * @param string $data Data to escape
    *
    * @return string
    */
    private function cdataEscape($data)
    {
        if (strlen($data) != strlen($this->sanitize($data))) {
            $data = str_replace( ']]>', '', $data );
            return '<![CDATA['.$data.']]>';
        }

        return $data;
    }

    /**
    * Sets the id, removing any tags from it
    *
    * @param string $id Id of the placemark
    *
    * @return Pas_KML_Place this object
    */
    public function setId($id)
    {
        $this->id = $this->sanitize($id);
        return $this;
    }

    /**
    * Returns the ID
    *
    * @return integer the id of this place
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Sets the name escaping tags with CDATA
    *
    * @param string $name Name of the placemark
    *
    * @return Pas_KML_Place this object
    */
    public function setName($name)
    {
        $this->name = $this->cdataEscape($name);
        return $this;
    }

    /**
    * Return the name of this place
    *
    * @return string the name
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Sets the description escaping tags with CDATA
    *
    * @param string $desc Description of the placemark
    *
    * @return Pas_KML_Place this object
    */
    public function setDesc($desc)
    {
        $this->desc = $this->cdataEscape($desc);
        return $this;
    }

    /**
    * Returns the description of this place
    *
    * @return string the description
    */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
    * Sets the style stripping any html and adding
    * a hash sign if not present for the style
    *
    * @param string $style Style of the placemark
    *
    * @return Pas_KML_Place this object
    */
    public function setStyle($style)
    {

        $style = $this->sanitize($style);

        // Add a hash for the style
        if (substr($style, 0, 1) != '#') {
            $style = '#' . $style;
        }

        $this->style = $style;

        return $this;
    }

    /**
    * Returns the style of this place
    *
    * @return string the style
    */
    public function getStyle()
    {
        return $this->style;
    }

    /**
    * Sets the coordinates, checking that they are floats
    *
    * @param float $lat Latitude coordinate
    * @param float $lng Longitude coordinate
    *
    * @return Pas_KML_Place this objectz
    * @throws Pas_KML_Exception
    */
    public function setCoords($lat, $lng)
    {
        // Convert to floats if they are in a string
        $lat = floatval($lat);
        $lng = floatval($lng);

        // Check that they are floats
        if (is_float($lat) && is_float($lng)) {
            // Set coords
            $this->coords = $lat . ',' . $lng;
            return $this;
        }

        // Not a valid set of coordinates
        throw new Pas_KML_Exception("Invalid set of coordinates.");
    }

    /**
    * Return the coordinates of this place
    *
    * @return string the coordinates
    */
    public function getCoords()
    {
        return $this->coords;
    }

    /**
    * Sets the folder name or empty argument sets the folder to root
    *
    * @param string $folder Folder which the placemark goes in
    *
    * @return Pas_KML_Place this object
    */
    public function setFolder($folder = false)
    {
        if ($folder === false) {
            $this->folder = '**[root]**';
        } else {
            $this->folder = $folder;
        }
        return $this;
    }

    /**
    * Return the folder which this place resides
    *
    * @return string the folder
    */
    public function getFolder()
    {
        return $this->folder;
    }

     public function getType()
    {
        return $this->type;
    }
}
?>