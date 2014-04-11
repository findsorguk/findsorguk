<?php
/** Class for creating zoomify images on the fly.
* @author Ported from Python to PHP by Wes Wright
* @license GNU General Public License
* @author Daniel Pett
* @Copyright (C) 2005  Adam Smith  asmith@agile-software.com
* @category Pas
* @package Pas_Zoomify
* @todo Sort out a version that deals with Tiffs.
* 
*/
	
	

class Pas_Zoomify_FileProcessor  {
	
 	public $_vImageFilename;
	public $_originalWidth;
	public $_originalHeight;
	public $_vScaleInfo = array();
	public $_numberOfTiles;
	public $_vTileGroupMappings = array();
	public $_qualitySetting;
	public $_tileSize;
	public $_filemode;
	public $_dirmode;
	public $_filegroup;
	public $_vSaveToLocation;
 	public $_iMagick;
 	
 	/** Construct the variables
 	 */
    public function __construct() {
	$this->_originalWidth 	= 0;
	$this->_originalHeight	= 0;
	$this->_numberOfTiles	= 0;
	$this->_qualitySetting	= 100;
	$this->_tileSize		= 256;
	$this->_filemode		= octdec('664');
	$this->_dirmode			= octdec('2775');
	$this->_filegroup		= 'www-data';
	$this->_vSaveToLocation = '';
	$this->_iMagick			= new phMagick();
	$this->_format			= '';
    }
    
	/** Open the image and sort out how to create image
	 * 
	 */
	public function _openImage() {
	$stripped = explode('.', $this->_zFilename);
	$ext = end($stripped); 
    switch (strtolower($ext)) {
    case 'jpg':
    case 'jpe':
    case 'jpeg':
	case 'JPG':
      return imagecreatefromjpeg($this->_vImageFilename);
    case 'png':
      return imagecreatefrompng($this->_vImageFilename);
    case 'gif':
      return imagecreatefromgif($this->_vImageFilename);
    case 'tiff':
      return ;
    }
    }    

    /** Get the image from
     * @param string $filename
     */
	public function getImageFromFile($filename) {
    list($root, $ext) = explode('.',$filename);
    if ( !$root ) {
    $root = $filename;
    }
    switch (strtolower($ext)) {
    case 'jpg':
    case 'jpe':
    case 'jpeg':
	case 'JPG':
      return imagecreatefromjpeg($filename);
    case 'png':
      return imagecreatefrompng($filename);
    case 'gif':
      return imagecreatefromgif($filename);
    }
  }
  
	/** Get the name of the file the tile will be saved as
	 * @param int $scaleNumber
	 * @param int $columnNumber
	 * @param int $rowNumber
	 * @return '%s-%s-%s.jpg' % (str(scaleNumber), str(columnNumber), str(rowNumber))
	 */
	public function getTileFileName($scaleNumber, $columnNumber, $rowNumber) {
	return "$scaleNumber-$columnNumber-$rowNumber.jpg";
	}

	/** This function generates the name of the next tile group container for holding images.
	 * @param int $tileGroupNumber
	 * @return string 
	 */
	public function getNewTileContainerName($tileGroupNumber = 0) {
	return "TileGroup" . $tileGroupNumber;
	}

	/** plan for the arrangement of the tile groups
	 */
	public function preProcess() {
	$tier				= 0;
	$tileGroupNumber	= 0;
	$numberOfTiles		= 0;
	foreach ($this->_vScaleInfo as $width_height) {
	list($width,$height) = $width_height;
	//cycle through columns, then rows
	$row	= 0;
	$column	= 0;
	$ul_x	= 0;
	$ul_y	= 0;
	$lr_x	= 0; 
	$lr_y	= 0;
	while (! (($lr_x == $width) && ($lr_y == $height))) {
	$tileFileName = $this->getTileFileName($tier, $column,$row);
	$tileContainerName = $this->getNewTileContainerName($tileGroupNumber);
	if ($numberOfTiles === 0) {
	$this->createTileContainer($tileContainerName);
	} elseif ($numberOfTiles % $this->_tileSize === 0) {
	$tileGroupNumber++;
	$tileContainerName = $this->getNewTileContainerName($tileGroupNumber);
	$this->createTileContainer($tileContainerName);
	}
	$this->_vTileGroupMappings[$tileFileName] = $tileContainerName;
	$numberOfTiles++;
	//for the next tile, set lower right cropping point
	if ($ul_x + $this->_tileSize < $width) {
	$lr_x = $ul_x + $this->_tileSize;
	} else {
	$lr_x = $width;
	}
				 
	if ($ul_y + $this->_tileSize < $height) {
	$lr_y = $ul_y + $this->_tileSize;
	} else {
	$lr_y = $height;
	}
	//for the next tile, set upper left cropping point
	if ($lr_x == $width) {
	$ul_x	= 0;
	$ul_y	= $lr_y;
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
	 * @param int $tier
	 * @param int $row
	 */
	public	function processRowImage($tier = 0, $row = 0) {
	
	list($tierWidth, $tierHeight) = $this->_vScaleInfo[$tier];
	
	$rowsForTier = floor($tierHeight / $this->_tileSize);
	
	if ($tierHeight % $this->_tileSize > 0){
	$rowsForTier++;
	}
	list($root, $ext) = explode(".", $this->_vImageFilename);
	
	if ( !$root){ 
	$root = $this->_v_imageFilename;
	}
	
	$ext = ".jpg";
	if ($tier === count($this->_vScaleInfo) -1) {
	$firstTierRowFile = $root . $tier. '-' . $row . $ext;
	if (is_file($firstTierRowFile)) {
	$imageRow = imagecreatefromjpeg($firstTierRowFile);
	}
	}  else {
	# create this row from previous tier's rows
	$imageRow = imagecreatetruecolor($tierWidth, $this->_tileSize);
	$t = $tier + 1;
	$r = $row  + $row;
	$firstRowFile = $root . $t . '-' . $r . $ext;
	
	$firstRowWidth		= 0;
	$firstRowHeight		= 0;
	
	$secondRowWidth		= 0;
	$secondRowHeight	= 0;
	
	if (is_file($firstRowFile)) {
	$firstRowImage 		= imagecreatefromjpeg($firstRowFile);
	$firstRowWidth		= imagesx( $firstRowImage );
	$firstRowHeight 	= imagesy( $firstRowImage );
	$imageRowHalfHeight = floor( $this->_tileSize / 2 );
	imagecopyresized($imageRow, $firstRowImage, 0, 0, 0, 0, $tierWidth, $imageRowHalfHeight, 
	$firstRowWidth, $firstRowHeight);
	unlink($firstRowFile);
	}
	
	$r= $r + 1;
	$secondRowFile =  $root . $t . "-" . $r . $ext;
	if (is_file($secondRowFile)) {
	$secondRowImage 	= imagecreatefromjpeg( $secondRowFile );
	$secondRowWidth		= imagesx( $secondRowImage );
	$secondRowHeight	= imagesy( $secondRowImage );
	imagecopyresampled($imageRow, $secondRowImage, 0, $imageRowHalfHeight, 0, 0, $tierWidth, 
	$imageRowHalfHeight, $secondRowWidth, $secondRowHeight);
	unlink($secondRowFile);
	}
	
	$rowHeight=$firstRowHeight+$secondRowHeight;
	$tileHeight=$this->_tileSize*2;
	if (($firstRowHeight + $secondRowHeight) < $this->_tileSize * 2) {
	$imageRow = imageCrop($imageRow, 0, 0, $tierWidth, $firstRowHeight + $secondRowHeight);
	}
	}
	
	if ($imageRow) {
	# cycle through columns, then rows
	$column = 0;
	$imageWidth		= imagesx( $imageRow );
	$imageHeight 	= imagesy( $imageRow );
	$ul_x			= 0;
	$ul_y			= 0;
	$lr_x			= 0;
	$lr_y 			= 0;
	while  (!(($lr_x == $imageWidth) && ($lr_y == $imageHeight))){
	//set lower right cropping point
	if (($ul_x + $this->_tileSize) < $imageWidth) {
	$lr_x = $ul_x + $this->_tileSize;
	} else {
	$lr_x = $imageWidth;
	}
				  
	if (($ul_y + $this->_tileSize) < $imageHeight) {
	$lr_y = $ul_y + $this->_tileSize;
	} else {
	$lr_y = $imageHeight;
	}
					
	//tierLabel = len($this->_v_scaleInfo) - tier
	$this->saveTile(imageCrop($imageRow, $ul_x, $ul_y, $lr_x, $lr_y), $tier, $column, $row);
	$this->_numberOfTiles++;

	//set upper left cropping point
	if ($lr_x == $imageWidth) {
	$ul_x	= 0;
	$ul_y	= $lr_y;
	$column = 0;
	#row += 1
	} else {
	$ul_x 	= $lr_x;
	$column++;
	}
	}
	if ($tier > 0) {
	$halfWidth = max(1, floor($imageWidth / 2));
	$halfHeight = max(1, floor($imageHeight / 2));
	$tempImage= imagecreatetruecolor($halfWidth, $halfHeight);
	imagecopyresampled ($tempImage, $imageRow, 0, 0, 0, 0, $halfWidth, $halfHeight, $imageWidth, $imageHeight);
	$rowFileName = $root . $tier . '-' . $row . $ext;
	touch ($rowFileName);
	imagejpeg($tempImage, $rowFileName);
	chmod ($rowFileName,$this->_filemode);
	chgrp ($rowFileName,$this->_filegroup);
	imagedestroy($tempImage);
	}
			
	imagedestroy($imageRow); // http://greengaloshes.cc/2007/05/zoomifyimage-ported-to-php/#comment-451
	if ($tier > 0) {
	if ($row % 2 != 0) {
	$this->processRowImage($tier - 1,floor(($row-1) / 2));
	} elseif ($row == $rowsForTier - 1) {
	$this->processRowImage($tier-1, floor($row/2));
	}
	}
	}
	}
  
  	/** starting with the original image, start processing each row 
  	 * 
  	 */
	public function processImage() {
	$tier=(count($this->_vScaleInfo) - 1);
	$row = 0;
	list($ul_y, $lr_y) = array(0,0);
	list($root, $ext) = explode('.', $this->_vImageFilename)  ;
	if (!$root) $root = $this->_vImageFilename;
	$ext = ".jpg";
	$image = $this->_openImage();
	while ($row * $this->_tileSize < $this->_originalHeight) {
	$ul_y = $row * $this->_tileSize;
	if ($ul_y + $this->_tileSize < $this->_originalHeight) {
	$lr_y = $ul_y + $this->_tileSize;
	} else {
	$lr_y = $this->originalHeight;
	}
	$imageRow = imageCrop($image, 0, $ul_y, $this->_originalWidth, $lr_y);
	$saveFilename = $root . $tier . '-' . $row .  $ext;
	touch($saveFilename);
	chmod ($saveFilename,$this->_filemode);
	imagejpeg($imageRow,$saveFilename, $this->_qualitySetting);
	chgrp ($saveFilename, $this->_filegroup);
	imagedestroy($imageRow);
	$this->processRowImage($tier, $row);
	$row++;
	}
	imagedestroy($image);
	}
    
	/** Create the XML for zoomify to read and compose the image
	*/
	public function getXMLOutput() {
	$numberOfTiles = $this->getNumberOfTiles();
	$xml = strtoupper('<image_properties width="'
	. $this->_originalWidth . '" height="'
	. $this->_originalHeight . '" numtiles="'
	. $numberOfTiles .'" numimages="1" version="1.8" tilesize="'
	. $this->_tileSize . '" />');
	return $xml;
	}

	/** Get the tile's container name
	 * @param string $tileFileName
	 * @return string $containerName
	 */
	public function getAssignedTileContainerName($tileFileName) {
	if ($tileFileName) {
	if (isset($this->_vTileGroupMappings) && $this->_vTileGroupMappings) {
	$containerName = $this->_vTileGroupMappings[$tileFileName];
	if ($containerName) {
	return $containerName;
	}
	}
	}
	$containerName = $this->getNewTileContainerName();
	return $containerName ;
    }

    /** Retrieve the image metadata for an image
     */
  	public function getImageMetadata() {
	list($this->_originalWidth, $this->_originalHeight, $this->_format) = getimagesize($this->_vImageFilename);
	$width = $this->_originalWidth;
	$height = $this->_originalHeight;
	$width_height = array($width, $height);
	array_unshift($this->_vScaleInfo, $width_height);
	while (($width > $this->_tileSize) || ($height > $this->_tileSize)) {
	$width = floor($width / 2);
	$height = floor($height / 2);
	$width_height = array($width, $height);
	array_unshift($this->_vScaleInfo, $width_height);
	}
	$this->preProcess();
	}  

	/** create a container for the next group of tiles within the data container
	* 
	* @param $tileContainerName
	*/
	public function createTileContainer($tileContainerName = "") {
	$tileContainerPath = $this->_vSaveToLocation . '/' . $tileContainerName;
	if (!is_dir($tileContainerPath)) {
	mkdir($tileContainerPath) ;
	chmod($tileContainerPath, $this->_dirmode);
	chgrp($tileContainerPath, $this->_filegroup);
	}
	}
      
     
	/** Create the data container from the image name
	* @param string $imageName
	*/
	public function createDataContainer($imageName) {
	$directory = dirname($imageName);
	$filename = basename($imageName);
	list($root,$ext) = explode('.', basename($filename));
	$root = $root . '_zdata';
	//$this->_vSaveToLocation =  "./".$this->_dir."zoom/".$root;
	
	//If the paths already exist, an image is being re-processed, clean up for it.
	if (is_dir($this->_vSaveToLocation)) {
	$rm_err= rm($this->_vSaveToLocation);
	} 
	//Make the directory 
	mkdir($this->_vSaveToLocation);
	//Change the permissions
	chmod($this->_vSaveToLocation, $this->_dirmode);
	chgrp($this->_vSaveToLocation, $this->_filegroup);
		
	}
	
	/** get the full path of the file the tile will be saved as
	 * 
	 * @param int $scaleNumber
	 * @param int $columnNumber
	 * @param int $rowNumber
	 */
	public function getFileReference($scaleNumber, $columnNumber, $rowNumber) {
	$tileFileName = $this->getTileFileName($scaleNumber, $columnNumber, $rowNumber);
	$tileContainerName = $this->getAssignedTileContainerName($tileFileName);
	return $this->_vSaveToLocation . '/' . $tileContainerName . '/' . $tileFileName;
	}
    
	/**get the number of tiles generated
	 * 
	 */    
	public	function getNumberOfTiles() {
	return $this->_numberOfTiles;
	}
    
	/** Save xml metadata about the tiles
	 * 
	 */
	public function saveXMLOutput() {
	$xmlFile = fopen($this->_vSaveToLocation . '/ImageProperties.xml', 'w');
	fwrite(	$xmlFile,$this->getXMLOutput() );
	fclose( $xmlFile);
	chmod($this->_vSaveToLocation . '/ImageProperties.xml', $this->_filemode);
	chgrp($this->_vSaveToLocation . '/ImageProperties.xml', $this->_filegroup);
	}
    
    
  	/** save the cropped region
  	 * 
  	 * @param $image
  	 * @param $scaleNumber
  	 * @param $column
  	 * @param $row
  	 */
	public function saveTile($image, $scaleNumber, $column, $row) {
	$tile_file = $this->getFileReference($scaleNumber, $column, $row);
	touch($tile_file);
	chmod ($tile_file, $this->_filemode);
	imagejpeg($image,$tile_file, $this->_qualitySetting);
	}    
    
	/** Process the image to a zoomified version
	 * 
	 * @param string $filename
	 * @param string $path
	 */
	public function ZoomifyProcess($filename, $path) {
	$this->_zFilename = $filename;
	$this->_vImageFilename = $path . $filename;
	$this->_imageName = $path . $filename;
	$this->createDataContainer($this->_vImageFilename);
	$this->getImageMetadata();
	$this->processImage();
	$this->saveXMLOutput();
	}   

}
/** Function to remove an image
	 * 
	 * @param $fileglob
	 */

	function rm($fileglob) {
	if (is_string($fileglob)) {
       if (is_file($fileglob)) {
           return unlink($fileglob);
       } else if (is_dir($fileglob)) {
           $ok = rm("$fileglob/*");
           if (! $ok) {
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
	 * 
	 * @param $image
	 * @param $left
	 * @param $upper
	 * @param $right
	 * @param $lower
	 */
	function imageCrop($image,$left,$upper,$right,$lower) {
	$x = imagesx($image);
	$y = imagesy($image);
	$w = abs($right-$left);
	$h = abs($lower-$upper);
	$crop = imagecreatetruecolor($w,$h);
	imagecopy($crop, $image, 0, 0, $left, $upper, $w, $h);
	return $crop;
	}

