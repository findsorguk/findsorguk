<?php

/** Class for creating zoomify images on the fly.
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $zoom = new Pas_Zoomify_FileProcessor();
 * $zoom->setImagePath($path)->setFileName($this->getFilename($data))->setDebug(false)->zoomTheImage();
 * ?>
 * </code>
 * @author Ported from Python to PHP by Wes Wright
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett
 * @Copyright (C) 2005  Adam Smith  asmith@agile-software.com
 * @category Pas
 * @package Pas_Zoomify
 * @example /app/modules/database/controllers/ImagesController.php
 *
 */
class Pas_Zoomify_FileProcessor
{

    /** Original width of image
     * @var int
     */
    protected $_originalWidth = 0;

    /** Original height of image
     * @var int
     */
    protected $_originalHeight = 0;

    /** Scale information
     * @var array
     * */
    protected $_scaleInfo = array();

    /** Default number of tiles
     * @var int
     */
    protected $_numberOfTiles = 0;

    /** Tile group maps
     * @var array
     */
    protected $_tileGroupMappings = array();

    /** Quality settings
     * @var int
     */
    protected $_qualitySetting = 100;

    /** Pixel size for tiles
     * @var int
     */
    protected $_tileSize = 256;

    /** File perms mode
     * @var int
     */
    protected $_fileMode = 664;

    /** Directory perms
     * @var int
     */
    protected $_dirMode = 2775;

    /** The default permissions to use
     *
     */
    const PERMS = 0777;

    /** File group for owner
     * @var string
     */
    protected $_fileGroup = 'www-data:www-data';

    /** Location for saving
     * @var string
     */
    protected $_saveLocation = '';

    /** The path to the image
     * @var string
     */
    protected $_imagePath = '';

    /** @var int */
    protected $_debug = null;

    /** Get whether to debug or not
     * @return mixed
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /** Set whether to debug or not
     * @access public
     * @param boolean $flag
     * @return Pas_Zoomify_FileProcessor
     */
    public function setDebug($flag)
    {
        $this->_debug = $flag;
        return $this;
    }

    /** The default container
     * @var string
     */
    protected $_container = '/zoom';

    /** Folder suffix to contain data */
    const SUFFIX = '_zoomify';

    /** Suffix for files */
    const EXT = '.jpg';

    /** @var string Default format string
     * */
    protected $_format = '';

    /** Filename
     * @var string
     * @return string
     */
    protected $_fileName = '';

    /** Get the tile size
     * @access public
     * @return integer
     */
    public function getTileSize()
    {
        return $this->_tileSize;
    }

    /** Set the tile size to a different size to default
     * @access public
     * @param integer
     * @return \Pas_Zoomify_FileProcessor
     */
    public function setTileSize($tileSize)
    {
        $this->_tileSize = $tileSize;
        return $this;
    }

    /** Get the file name
     * @access public
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /** Set the file name
     * @access public
     * @param $fileName
     * @return string
     */
    public function setFileName($fileName)
    {
        $this->_fileName = $fileName;
        return $this;
    }

    /** Get the image path
     * @access public
     * @return string
     */
    public function getImagePath()
    {
        return $this->_imagePath;
    }

    /** Set the image path
     * @access public
     * @param string $imagePath
     * @return \Pas_Zoomify_FileProcessor
     */
    public function setImagePath($imagePath)
    {
        $this->_imagePath = $imagePath;
        return $this;
    }

    /** Get the file mode to set the files to
     * @access public
     * @return integer
     */
    public function getFileMode()
    {
        return octdec($this->_fileMode);
    }

    /** Get the directory mode
     * @return integer
     * @access public
     */
    public function getDirMode()
    {
        return octdec($this->_dirMode);
    }

    /** Set the file mode
     * @access public
     * @param $filemode
     * @return Pas_Zoomify_FileProcessor
     */
    public function setFileMode($filemode)
    {
        $this->_fileMode = $filemode;
        return $this;
    }

    /** Over ride the directory mode
     * @access public
     * @param $dirmode
     * @return Pas_Zoomify_FileProcessor
     */
    public function setDirMode($dirmode)
    {
        $this->_dirMode = $dirmode;
        return $this;
    }

    /** Get the file group to set
     * @access public
     * @return integer
     */
    public function getFileGroup()
    {
        return $this->_fileGroup;
    }

    /** Set the file group
     * @access public
     * @param $fileGroup
     * @return Pas_Zoomify_FileProcessor
     */
    public function setFileGroup($fileGroup)
    {
        $this->_fileGroup = $fileGroup;
        return $this;
    }

    /** Get the save location
     * @access public
     * @return string
     */
    public function getSaveLocation()
    {
        return $this->getImagePath() . $this->getContainer();
    }

    /** Set the save location
     * @param string $saveLocation
     * @return \Pas_Zoomify_FileProcessor
     */
    public function setSaveLocation($saveLocation)
    {
        $this->_saveLocation = $saveLocation;
        return $this;
    }

    /** Get the scale info
     * @access public
     * @return array
     */
    public function getScaleInfo()
    {
        return $this->_scaleInfo;
    }


    /** Get the original width
     * @access public
     * @return int
     */
    public function getOriginalWidth()
    {
        return $this->_originalWidth;
    }

    /** Set the original width
     * @param int $originalWidth
     * @return int
     *
     */
    public function setOriginalWidth($originalWidth)
    {
        $this->_originalWidth = $originalWidth;
    }

    /** Get the original height
     * @access public
     * @return int
     */
    public function getOriginalHeight()
    {
        return $this->_originalHeight;
    }

    /** Set the original height
     * @param int $originalHeight
     * @return \Pas_Zoomify_FileProcessor
     */
    public function setOriginalHeight($originalHeight)
    {
        $this->_originalHeight = $originalHeight;
        return $this;
    }

    /** Get the quality setting - default is 100%
     * @return int
     */
    public function getQualitySetting()
    {
        return $this->_qualitySetting;
    }

    /** Set the quality setting
     * @param int $qualitySetting
     * @return \Pas_Zoomify_FileProcessor
     */
    public function setQualitySetting($qualitySetting)
    {
        $this->_qualitySetting = $qualitySetting;
        return $this;
    }

    /** Get the tile group map
     * @access public
     * @return array
     */
    public function getTileGroupMappings()
    {
        return $this->_tileGroupMappings;
    }

    /** Set the tle group mappings
     * @param array $tileGroupMappings
     * @return \Pas_Zoomify_FileProcessor
     */
    public function setTileGroupMappings($tileGroupMappings)
    {
        $this->_tileGroupMappings = $tileGroupMappings;
        return $this;
    }

    /** Get the container
     * @access public
     * @return string
     */
    public function getContainer()
    {
        return $this->_container;
    }


    /** Get the name of the file the tile will be saved as
     * @param int $scaleNumber
     * @param int $columnNumber
     * @param int $rowNumber
     * @return string
     */
    public function getTileFileName($scaleNumber, $columnNumber, $rowNumber)
    {
        return $scaleNumber . '-' . $columnNumber . '-' . $rowNumber . self::EXT;
    }

    /** This function generates the name of the next tile group container
     * for holding images.
     * @param int $tileGroupNumber
     * @return string
     */
    public function getNewTileContainerName($tileGroupNumber = 0)
    {
        return 'TileGroup' . $tileGroupNumber;
    }


    /** Process the image to a zoomed version
     * @return void
     */
    public function zoomTheImage()
    {
        $this->createContainer();
        $this->createDataContainer($this->getFileName());
        $this->getImageMetadata();
        $this->processImage();
        $this->saveXMLOutput();
    }


    /** Plan for the arrangement of the tile groups
     * So pre processing applied
     * @return void
     */
    public function preProcess()
    {
        $tier = 0;
        $tileGroupNumber = 0;
        $numberOfTiles = 0;
        if ($this->getDebug() == true) {
            Zend_Debug::dump($this->_scaleInfo, 'SCALEINFO');
        }
        foreach ($this->_scaleInfo as $width_height) {
            list($width, $height) = $width_height;
            #		cycle through columns, then rows
            $row = 0;
            $column = 0;
            $ul_x = 0;
            $ul_y = 0;
            $lr_x = 0;
            $lr_y = 0;
            while (!(($lr_x == $width) && ($lr_y == $height))) {

                $tileFileName = $this->getTileFileName($tier, $column, $row);
                if ($this->getDebug()) {
                    Zend_Debug::dump($tileFileName, 'tile mappings filename' . __LINE__);
                }
                $tileContainerName = $this->getNewTileContainerName($tileGroupNumber);
                if ($this->getDebug()) {
                    Zend_Debug::dump($tileContainerName, 'TILE container name');
                }
                if ($numberOfTiles == 0) {
                    $this->createTileContainer($tileContainerName);
                } elseif ($numberOfTiles % $this->getTileSize() == 0) {
                    $tileGroupNumber++;
                    $tileContainerName = $this->getNewTileContainerName($tileGroupNumber);
                    if ($this->getDebug()) {
                        Zend_Debug::dump($tileContainerName, 'TILE container name');
                    }
                    $this->createTileContainer($tileContainerName);
                }
                $this->_tileGroupMappings[$tileFileName] = $tileContainerName;
                if ($this->getDebug()) {
                    Zend_Debug::dump($this->_tileGroupMappings, 'tile mappings ' . __LINE__);
                }
                $numberOfTiles++;

                if ($this->getDebug()) {
                    Zend_Debug::dump($numberOfTiles, 'number of tiles');
                }
                # for the next tile, set lower right cropping point
                if ($ul_x + $this->getTileSize() < $width) {
                    $lr_x = $ul_x + $this->getTileSize();
                } else {
                    $lr_x = $width;
                }

                if ($ul_y + $this->getTileSize() < $height) {
                    $lr_y = $ul_y + $this->getTileSize();
                } else {
                    $lr_y = $height;
                }

                # for the next tile, set upper left cropping point
                if ($lr_x == $width) {
                    $ul_x = 0;
                    $ul_y = $lr_y;
                    $column = 0;
                    $row++;
                } else {
                    $ul_x = $lr_x;
                    $column++;
                }
            }
            $tier++;
        }
    }

    /** For each image, create and save tiles for zoomify
     * @access public
     * @param int $tier
     * @param int $row
     */
    public function processRowImage($tier = 0, $row = 0, $saveFilename = '')
    {

        if ($this->getDebug()) {
            Zend_Debug::dump('Tier = ' . $tier . ' Row = ' . $row, "VARIABLES");
        }
        list($tierWidth, $tierHeight) = $this->_scaleInfo[$tier];

        if ($this->getDebug()) {
            Zend_Debug::dump($this->_scaleInfo[$tier], __LINE__ . 'SCALEINFO');
        }

        $rowsForTier = floor($tierHeight / $this->getTileSize());

        if ($this->getDebug()) {
            Zend_Debug::dump($rowsForTier, 'Rows for tier');
        }
        if ($tierHeight % $this->getTileSize() > 0) {
            $rowsForTier++;
        }

        $root = basename($this->getFileName(), '.jpg');

        if ($this->getDebug()) {
            Zend_Debug::dump(basename($this->getFileName(), '.jpg'), 'BASENAME ');
        }
        if (!$root) {
            $root = $this->getFileName();
        }
        $ext = ".jpg";

        if ($this->getDebug()) {
            Zend_Debug::dump(count($this->_scaleInfo) - 1, 'COUNT OF TIER');
        }
        if ($tier == count($this->_scaleInfo) - 1) {

            $firstTierRowFile = $saveFilename;

            if ($this->getDebug()) {
                Zend_Debug::dump($firstTierRowFile, 'PATH ONE');
            }
            if (is_file($firstTierRowFile)) {
                $imageRow = imagecreatefromjpeg($firstTierRowFile);
                if ($this->getDebug()) {
                    Zend_Debug::dump($imageRow, 'LINE TEST' . __LINE__) ;
                    print "firstTierRowFile exists<br>";
                }
            }

        } else {
            # create this row from previous tier's rows
            $imageRow = imagecreatetruecolor($tierWidth, $this->getTileSize());
            if ($this->getDebug()) {
                Zend_Debug::dump($imageRow, 'THE IMAGE ROW 0');
            }
            $t = $tier + 1;
            $r = $row + $row;
            $firstRowFile = $this->getNameOfContainer() . '/' . $root . $t . "-" . $r . $ext;
            if ($this->getDebug()) {
                Zend_Debug::dump($firstRowFile, 'THE IMAGE ROW 1');
            }
            $firstRowWidth = 0;
            $firstRowHeight = 0;
            $secondRowWidth = 0;
            $secondRowHeight = 0;
            if (is_file($firstRowFile)) {
                $firstRowImage = imagecreatefromjpeg($firstRowFile);
                $firstRowWidth = imagesx($firstRowImage);
                $firstRowHeight = imagesy($firstRowImage);
                $imageRowHalfHeight = floor($this->getTileSize() / 2);
                imagecopyresized($imageRow, $firstRowImage, 0, 0, 0, 0, $tierWidth, $imageRowHalfHeight, $firstRowWidth, $firstRowHeight);
                unlink($firstRowFile);
            }
            $r = $r + 1;
            $secondRowFile =  $this->getNameOfContainer() . '/' . $root . $t . "-" . $r . $ext;
            if ($this->getDebug()) {
                Zend_Debug::dump($secondRowFile, 'THE IMAGE ROW 2');
            }
            if (is_file($secondRowFile)) {
                $secondRowImage = imagecreatefromjpeg($secondRowFile);
                $secondRowWidth = imagesx($secondRowImage);
                $secondRowHeight = imagesy($secondRowImage);
                imagecopyresampled($imageRow, $secondRowImage, 0, $imageRowHalfHeight, 0, 0, $tierWidth, $imageRowHalfHeight, $secondRowWidth, $secondRowHeight);
                unlink($secondRowFile);
            }


            # the last row may be less than $this->tileSize...
            $rowHeight = $firstRowHeight + $secondRowHeight;
            $tileHeight = $this->getTileSize() * 2;
            if (($firstRowHeight + $secondRowHeight) < $this->getTileSize() * 2) {
                $imageRow = cropTheImage($imageRow, 0, 0, $tierWidth, $firstRowHeight + $secondRowHeight);
            }
        }
        if ($imageRow) {
            # cycle through columns, then rows
            $column = 0;
            $imageWidth = imagesx($imageRow);
            $imageHeight = imagesy($imageRow);
            $ul_x = 0;
            $ul_y = 0;
            $lr_x = 0;
            $lr_y = 0;
            while (!(($lr_x == $imageWidth) && ($lr_y == $imageHeight))) {
                if (($ul_x + $this->getTileSize()) < $imageWidth) {
                    $lr_x = $ul_x + $this->getTileSize();
                } else {
                    $lr_x = $imageWidth;
                }

                if (($ul_y + $this->getTileSize()) < $imageHeight) {
                    $lr_y = $ul_y + $this->getTileSize();
                } else {
                    $lr_y = $imageHeight;
                }

                #tierLabel = len($this->_v_scaleInfo) - tier
                if ($this->getDebug() === true) {
                    print "line 248 calling crop<br>";
                }
                $this->saveTile(cropTheImage($imageRow, $ul_x, $ul_y, $lr_x, $lr_y), $tier, $column, $row);

                $this->_numberOfTiles++;

                if ($this->getDebug()) {
                    print "created tile: numberOfTiles= $this->_numberOfTiles tier column row =($tier,$column,$row)<br>\n";
                }
                # set upper left cropping point
                if ($lr_x == $imageWidth) {
                    $ul_x = 0;
                    $ul_y = $lr_y;
                    $column = 0;
                    #row += 1
                } else {
                    $ul_x = $lr_x;
                    $column++;
                }
            }
            if ($tier > 0) {
                $halfWidth = max(1, floor($imageWidth / 2));
                $halfHeight = max(1, floor($imageHeight / 2));
                $tempImage = imagecreatetruecolor($halfWidth, $halfHeight);
                imagecopyresampled($tempImage, $imageRow, 0, 0, 0, 0, $halfWidth, $halfHeight, $imageWidth, $imageHeight);
                $rowFileName = $root . $tier . "-" . $row . $ext;
                imagejpeg($tempImage, $this->getNameOfContainer() . '/' . $rowFileName);
                imagedestroy($tempImage);
            }

            if ($tier > 0) {
                if ($this->getDebug()) print "processRowImage final checks for tier $tier row=$row rowsForTier=$rowsForTier<br>\n";
                if ($row % 2 != 0) {
                    if ($this->getDebug()) print "processRowImage final checks tier=$tier row=$row mod 2 check before<br>\n";
#				  $this->processRowImage($tier=$tier-1,$row=($row-1)/2);
                    $this->processRowImage($tier - 1, floor(($row - 1) / 2));
                    if ($this->getDebug()) print "processRowImage final checks tier=$tier row=$row mod 2 check after<br>\n";
                } elseif ($row == $rowsForTier - 1) {
                    if ($this->getDebug()) print "processRowImage final checks tier=$tier row=$row rowsForTier=$rowsForTier check before<br>\n";
#				  $this->processRowImage($tier=$tier-1, $row=$row/2);
                    $this->processRowImage($tier - 1, floor($row / 2));
                    if ($this->getDebug()) print "processRowImage final checks tier=$tier row=$row rowsForTier=$rowsForTier check after<br>\n";
                }
            }
        }
    }

    /** starting with the original image, start processing each row
     * @access public
     * @return void
     */
    public function processImage()
    {
        $tier = (count($this->getScaleInfo()) - 1);
        $row = 0;
        list($ul_y, $lr_y) = array(0, 0);
        list($root, $ext) = explode('.', $this->getFileName());
        if (!$root) {
            $root = $this->getFileName();
        }
        $image = $this->_openImage();
        while ($row * $this->getTileSize() < $this->getOriginalHeight()) {
            $ul_y = $row * $this->getTileSize();
            if ($ul_y + $this->getTileSize() < $this->getOriginalHeight()) {
                $lr_y = $ul_y + $this->getTileSize();
            } else {
                $lr_y = $this->getOriginalHeight();
            }
            $imageRow = cropTheImage($image, 0, $ul_y, $this->getOriginalWidth(), $lr_y);
            if ($this->getDebug()) {
                Zend_Debug::dump($imageRow, 'IMAGEROW');
                Zend_Debug::dump($tier, 'TIER');
                Zend_Debug::dump($row, 'ROW');
            }
            $firstTierRowFile = $root . $tier . "-" . $row . $ext;
            $saveFilename = $this->getNameOfContainer() . '/' . $root . $tier . '-' . $row . self::EXT;
            if ($this->getDebug()) {
                Zend_Debug::dump($saveFilename, 'Filename');
            }
//            touch($saveFilename);
//            chmod($saveFilename, $this->getFileMode());
            imagejpeg($imageRow, $saveFilename, $this->getQualitySetting());
//            chown($saveFilename, $this->getFileGroup());
            imagedestroy($imageRow);
            $this->processRowImage($tier, $row, $saveFilename);
            $row++;
        }
        imagedestroy($image);
    }

    /**
     * @return resource
     */
    public function _openImage()
    {
        return imagecreatefromjpeg(implode('/', array($this->getImagePath(), $this->getFileName())));
    }

    /** Create the XML for zoomify to read and compose the image
     * @access public
     * @return string
     */
    public function getXMLOutput()
    {
        $xml = new DomDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        $xml->xmlStandalone = true;
        $node = $xml->createElement('IMAGE_PROPERTIES');
        $newnode = $xml->appendChild($node);
        $newnode->setAttribute('WIDTH', $this->getOriginalWidth());
        $newnode->setAttribute('HEIGHT', $this->getOriginalHeight());
        $newnode->setAttribute('NUMTILES', $this->_numberOfTiles);
        $newnode->setAttribute('NUMIMAGES', '1');
        $newnode->setAttribute('VERSION', '1.8');
        $newnode->setAttribute('TILESIZE', $this->getTileSize());
        return $xml->saveXML();
    }

    /** Get the tile's container name
     * @param string $tileFileName
     * @return string $containerName
     */
    public function getAssignedTileContainerName($tileFileName)
    {
        if ($tileFileName) {
            if (!is_null($this->_tileGroupMappings) && $this->_tileGroupMappings) {
                $containerName = $this->_tileGroupMappings[$tileFileName];
                if ($containerName) {
                    if ($this->getDebug()) {
                        Zend_Debug::dump($containerName, 'Assigned container');
                    }
                    return $containerName;
                }
            }
        }
        return $this->getNewTileContainerName();
    }

    /** Retrieve the image metadata for an image
     */
    public function getImageMetadata()
    {
        $file = implode('/', array($this->getImagePath(), $this->getFileName()));
        list($this->_originalWidth, $this->_originalHeight, $this->_format) = getimagesize($file);
        $width = $this->getOriginalWidth();
        $height = $this->getOriginalHeight();
        $width_height = array($width, $height);
        array_unshift($this->_scaleInfo, $width_height);
        if ($this->getDebug()) {
            Zend_Debug::dump(array_unshift($this->_scaleInfo, $width_height), "UNSHIFT");
        }
        while (($width > $this->getTileSize()) || ($height > $this->getTileSize())) {
            $width = floor($width / 2);
            $height = floor($height / 2);
            $width_height = array($width, $height);
            array_unshift($this->_scaleInfo, $width_height);
            if ($this->getDebug()) {
                Zend_Debug::dump("getImageMetadata newWidth=$width newHeight=$height", 'NEW DIMS');
            }
        }
        if ($this->getDebug()) {
            Zend_Debug::dump($width_height, 'WIDTH_HEIGHT');
        }
        // Process the image
        $this->preProcess();
    }

    /** create a container for the next group of tiles within the data container
     * @access public
     * @param $tileContainerName
     */
    public function createTileContainer($tileContainerName = "")
    {
        $tileContainerPath = implode('/', array($this->getNameOfContainer(), $tileContainerName));
        if ($this->getDebug()) {
            Zend_Debug::dump('Making container: ' . $tileContainerPath);
        }
        if (!is_dir($tileContainerPath)) {
            $this->_makeDirectory($tileContainerPath, self::PERMS, true);
        }
    }

    /** Make directory if does not exist
     * @access public
     * @param $path
     */
    protected function _makeDirectory($path, $recursive = TRUE)
    {
        if (!is_dir($path)) {
            return mkdir($path, self::PERMS, $recursive);
        }
    }

    /** Create the data container from the image name
     * @param string $imageName
     * @access public
     */
    public function createDataContainer($imageName)
    {
        $directory = implode('/', array($this->getSaveLocation(), basename($imageName, '.jpg'))) . self::SUFFIX;
        //If the paths already exist, an image is being re-processed, clean up for it.
        if (is_dir($directory)) {
            if ($this->getDebug()) {
                Zend_Debug::dump('Removing directory', 'REMOVE DIRECTORY');
            }
        }
        if ($this->getDebug()) {
            Zend_Debug::dump('Line: ' . __LINE__ . ' making ' . $directory . ' Perms: ' . self::PERMS, 'CREATE DATA');
        }
        //Make the directory
        $this->_makeDirectory($directory, self::PERMS, true);
        //Change the permissions
        chmod($directory, $this->getDirMode());
    }

    /** Get the name of the container for the image
     * @access public
     * @return string
     */
    public function getNameOfContainer()
    {
        return implode('/', array($this->getSaveLocation(), basename($this->getFileName(), '.jpg'))) . self::SUFFIX;
    }

    /** Get the full path of the file the tile will be saved as
     * @param int $scaleNumber
     * @param int $columnNumber
     * @param int $rowNumber
     * @return string
     */
    public function getFileReference($scaleNumber, $columnNumber, $rowNumber)
    {
        $tileFileName = $this->getTileFileName($scaleNumber, $columnNumber, $rowNumber);
        $tileContainerName = $this->getAssignedTileContainerName($tileFileName);
        return $this->getNameOfContainer() . '/' . $tileContainerName . '/' . $tileFileName;
    }

    /** Get the number of tiles generated
     * @access public
     * @return integer
     */
    public function getNumberOfTiles()
    {
        return $this->_numberOfTiles;
    }

    /** Save xml metadata about the tiles
     * @access public
     * @return void
     */
    public function saveXMLOutput()
    {
        $xmlFile = fopen($this->getNameOfContainer() . '/ImageProperties.xml', 'w');
        fwrite($xmlFile, $this->getXMLOutput());
        fclose($xmlFile);
    }


    /** Save the cropped region
     * @access public
     * @param $image
     * @param $scaleNumber
     * @param $column
     * @param $row
     * @return void
     */
    public function saveTile($image, $scaleNumber, $column, $row)
    {
        $tile_file = $this->getFileReference($scaleNumber, $column, $row);
        imagejpeg($image, $tile_file, $this->getQualitySetting());
    }


    /** Create the zoomed image container
     * @access public
     * @return void
     */
    public function createContainer()
    {
        $directory = $this->getImagePath() . $this->getContainer();
        if (!is_dir($directory)) {
            if ($this->getDebug() === true) {
                Zend_Debug::dump('Line: ' . __LINE__ . ' Making directory ' . $directory . ' with ' . self::PERMS, 'CREATE CONTAINER');
            }
            $this->_makeDirectory($directory, self::PERMS, true);
        }
    }

}

/** Function to remove an image
 * @param $fileglob
 * @return boolean
 */

function rm($fileglob)
{
    if (is_string($fileglob)) {
        if (is_file($fileglob)) {
            return unlink($fileglob);
        } else if (is_dir($fileglob)) {
            $ok = rm("$fileglob/*");
            if (!$ok) {
                return false;
            }
            return rmdir($fileglob);
        } else {
            $matching = glob($fileglob);
            if ($matching === false) {
                trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
                return false;
            }
            $rcs = array_map('rm', $matching);
            if (in_array(false, $rcs)) {
                return false;
            }
        }
    } else if (is_array($fileglob)) {
        $rcs = array_map('rm', $fileglob);
        if (in_array(false, $rcs)) {
            return false;
        }
    } else {
        trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);
        return false;
    }
    return true;
}

/** Crop an image
 * @param $image
 * @param $left
 * @param $upper
 * @param $right
 * @param $lower
 * @return resource
 */
function cropTheImage($image, $left, $upper, $right, $lower)
{
    $x = imagesx($image);
    $y = imagesy($image);
    $w = abs($right - $left);
    $h = abs($lower - $upper);
    $crop = imagecreatetruecolor($w, $h);
    imagecopy($crop, $image, 0, 0, $left, $upper, $w, $h);
    return $crop;
}