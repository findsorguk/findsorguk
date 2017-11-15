<?php
/** A class wrapper for the Imagecow library just for PAS use
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Image
 * @since 3/2/12
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Imagecow
 * @uses Pas_User_Details
 * @uses Pas_Image_Exception
 * @uses Pas_Image_Rename
 *
 */

use Imagecow\Image;


class Pas_Image_MagickDust
{

    /** Set up array of sizes */
    protected $_sizes;

    /** Create user path string */
    protected $_userPath;

    protected $_directoryPath;

    /** Create original */
    protected $_original;

    /** Allowed extensions */
    protected $_extensions = array('jpg', 'jpeg', 'tiff', 'tif');

    /** Basename of file */
    protected $_basename;

    /** The record number to create the thumbnail */
    protected $_imageNumber;

    /** Allowed mime types */
    protected $_mimeTypes = array(
        'image/jpeg',
        'image/pjpeg',
        'image/tiff',
        'image/x-tiff'
    );

    /** User object */
    protected $_user;

    /**  Thumbnail directory */
    const THUMB = '/thumbnails/';

    /** Small image directory */
    const SMALL = '/small/';

    /** Medium image directory */
    const MEDIUM = '/medium/';

    /** Large image directory */
    const LARGE = '/large/';

    /** Display image directory */
    const DISPLAY = '/display/';

    /** Create directory to store tiffs */
    const TIFFS = '/tiffs/';

    /** The default extension */
    const EXT = '.jpg';

    const TIFFEXT  = '.tif';

    /** The permissions for a directory */
    const PERMS = 0777;

    /** The mime types for tiff images */
    protected $_tiffMimes = array('image/tiff', 'image/x-tiff');

    /** Get the sizes for creation
     * @access public
     */
    public function getSizes()
    {
        $this->_sizes = array(
            array('destination' => self::THUMB, 'width' => 100, 'height' => 100),
            array('destination' => self::SMALL, 'width' => 40, 'height' => 0),
            array('destination' => self::MEDIUM, 'width' => 500, 'height' => 0),
            array('destination' => self::DISPLAY, 'width' => 0, 'height' => 200),
        );
        return $this->_sizes;
    }

    /** Get the user
     * @access public
     * @return object
     */
    public function getUser()
    {
        return $this->_user;
    }

    public function setUser($user)
    {
        $this->_user = $user;
        return $this;
    }

    /** Set up the image
     * @access public
     * @param string $image
     */
    public function setImage($image)
    {
        //Original name
        $this->_original = $image;
        //Just get the basename
        $this->_basename = basename($image);
        return $this;
    }

    /** Set the image id number
     * @access public
     * @return int
     * @throws Pas_Image_Exception
     */
    public function setImageNumber($id)
    {
        if (is_int($id)) {
            $this->_imageNumber = $id;
        } else {
            throw new Pas_Image_Exception('No file to create', 500);
        }
        return $this;
    }

    /** Get the image number
     * @access public
     * @return int
     */
    public function getImageNumber()
    {
        return $this->_imageNumber;
    }

    /** get the image
     * @access public
     * @return string Path to file
     */
    public function getImage()
    {
        //Return the original name
        return $this->_original;
    }

    /** Get the array of mime types accepted
     * @access public
     * @return array
     */
    public function getMimeTypes()
    {
        return $this->_mimeTypes;
    }

    /** Get the basename of the image minus extension
     * @access public
     * @return string
     */
    public function getBasename()
    {
        //Return the basename
        return $this->_basename;
    }

    /** Get the user's path
     * @access public
     * @return string
     * @throws Pas_Image_Exception
     */
    public function getUserPath()
    {
        $this->_userPath = '/' . $this->getUser();
        return $this->_userPath;
    }

    /** Get the directory path
     * @return string
     * @access public
     */
    public function getDirectoryPath()
    {
        return $this->_directoryPath;
    }

    /** Set the directory path
     * @access public
     * @param string $directoryPath
     * @throws Zend_Exception
     */
    public function setDirectoryPath($directoryPath)
    {
        if (!is_dir($directoryPath)) {
            throw new Pas_Image_Exception('That directory path does not exist', 500);
        }
        if (!is_writable($directoryPath)) {
            throw new Pas_Image_Exception('That directory is not writable', 500);
        }
        $this->_directoryPath = $directoryPath;
        return $this;
    }


    /** Check directories exist for a user
     * @access public
     * @return \Pas_Image_MagickDust
     */
    public function checkDirectories()
    {
        //For each directory in the list, check that the directory exists
        foreach ($this->getSizes() as $dir) {
            if ($dir['destination'] != self::THUMB) {
                //Set up each directory path
                $directory = IMAGE_PATH . $this->getUserPath() . $dir['destination'];
                //Check if directory exists and if not create.
                if (!is_dir($directory)) {
                    $this->_makeDirectory($directory);
                }
            } else {
                $directory = IMAGE_PATH . self::THUMB;
                if (!is_dir($directory)) {
                    $this->_makeDirectory($directory);
                }
            }
        }
        return $this;
    }

    /** Check permissions for the user directories
     * @access public
     * @return \Pas_Image_MagickDust
     * @throws Pas_Image_Exception
     */
    public function checkPermissions()
    {
        //For each directory in the list, check that the directory exists
        foreach ($this->getSizes() as $dir) {
            //Set up each directory path
            $directory = IMAGE_PATH . $this->getUserPath() . $dir['destination'];
            //Check if directory exists and if not create.
//            if (!is_writable($directory)) {
//                chmod($directory, self::PERMS);
//            } else {
//                throw new Pas_Image_Exception('The directory ' . $directory . ' is not writable', 500);
//            }
        }
        return $this;
    }

    /** Create the different sizes of images
     * @access public
     * @throws Pas_Image_Exception
     * @return void
     */

    public function resize()
    {
        // Get the image
        $image = $this->getImage();
        // If image parameter not set, throw exception
        if (is_null($image)) {
            throw new Pas_Image_Exception('You must specify an image', 500);
        }

        //Check file exists and if not throw exception
        if (!file_exists($image)) {
            throw new Pas_Image_Exception('That image does not exist', 500);
        }
        //Make directory check for existence
        $this->checkDirectories();
        // Make directory check for permissions
//        $this->checkPermissions();
        // If the mime type is a tiff do this
        if (in_array($mime, $this->_tiffMimes)) {
            //Convert tiff to JPG and repeat above, replace original and save tiff in tiffs folder
            $this->convertTiff();
        }

        //Loop through each size and create the image
        foreach ($this->getSizes() as $resize) {
            // Set the file name
            if ($resize['destination'] == self::THUMB) {
                // Thumbnail sets record number as thumbnail ID
                $newImage = IMAGE_PATH . $resize['destination'] . $this->getImageNumber() . self::EXT;
            } else {
                // Normal base name otherwise
                $newImage = IMAGE_PATH . $this->getUserPath() . $resize['destination'] . $this->getBasename();
            }
            // Set up the image creation class using imagick
            $surrogate = Image::fromFile($this->getImage(), Image::LIB_IMAGICK);
            // Get the mime type
            $mime = $surrogate->getMimeType();
            // Check if mime type is in the accepted array of types
            if (in_array($mime, $this->getMimeTypes())) {
                $surrogate->resize($resize['width'], $resize['height'], 1);
                $surrogate->format('jpg');
                $surrogate->save($newImage);
            }

        }
    }


    /** Convert tiff to jpeg
     * @access public
     */
    public function convertTiff()
    {
        //Determine path to Tiff folder
        $tiffPath = IMAGE_PATH . $this->getUserPath() . self::TIFFS . $this->getBasename() . self::TIFFEXT;
//        print($tiffPath);
//exit;
        //Where we will be saving the file
        $destination = $this->getUserPath();

        //Check if directory exists, if not then make directory
        if (!is_dir($tiffPath)) {
            $this->_makeDirectory($tiffPath);
        }

        //Create an instance of the image to save
        $surrogate = Image::fromFile($tiffPath, Image::LIB_IMAGICK);
        //Set the image to save as jpeg file
        $surrogate->format('jpg');
        //Save to original folder as jpeg
        $surrogate->save($destination);
        //return to the resize function
        $this->resize();
    }

    /** Make directory if does not exist
     * @access public
     * @param $path
     */
    protected function _makeDirectory($path, $recursive = TRUE)
    {
        return mkdir($path, self::PERMS, $recursive);
    }
}