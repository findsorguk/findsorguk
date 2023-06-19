<?php
/** A class wrapper for the Imagecow library just for PAS use
 *
 * @author        Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category      Pas
 * @package       Image
 * @since         3/2/12
 * @license       http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version       1
 * @uses          Imagecow
 * @uses          Pas_User_Details
 * @uses          Pas_Image_Exception
 * @uses          Pas_Image_Rename
 *
 */

use Imagecow\Image;

class Pas_Image_Magick
{

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
    /** The permissions for a directory */
    const PERMS = 0777;
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
    protected $_mimeTypes
        = array(
            'image/jpeg',
            'image/pjpeg',
            'image/tiff',
            'image/x-tiff',
            'image/x-tiff64'
        );
    /** User object */
    protected $_user;
    /** The mime types for tiff images */
    protected $_tiffMimes = array('image/tiff', 'image/x-tiff');

    /** Set up the image
     *
     * @access public
     *
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

    /** Check permissions for the user directories
     *
     * @access public
     * @return \Pas_Image_Magick
     * @throws Pas_Image_Exception
     */
    public function checkPermissions()
    {
        //For each directory in the list, check that the directory exists
        foreach ($this->getSizes() as $dir) {
            //Set up each directory path
            $directory = IMAGE_PATH . $this->getUserPath()
                . $dir['destination'];
            //Check if directory exists and if not create.
            if (!is_writable($directory)) {
                chmod($directory, self::PERMS);
            } else {
                throw new Pas_Image_Exception(
                    'The directory ' . $directory . ' is not writable',
                    500
                );
            }
        }
        return $this;
    }

    /** Get the sizes for creation
     *
     * @access public
     */
    public function getSizes()
    {
        $this->_sizes = array(
            array(
                'destination' => self::THUMB,
                'width'       => 100,
                'height'      => 100
            ),
            array('destination' => self::SMALL, 'width' => 40, 'height' => 0),
            array('destination' => self::MEDIUM, 'width' => 500, 'height' => 0),
            array(
                'destination' => self::DISPLAY,
                'width'       => 0,
                'height'      => 200
            ),
        );
        return $this->_sizes;
    }

    /** Get the user's path
     *
     * @access public
     * @return string
     * @throws Pas_Image_Exception
     */
    public function getUserPath()
    {
        $path = $this->getDirectoryPath();
        if (is_null($path)) {
            //Get the user's image directory from the person object
            $user = $this->getUser()->getPerson();
            //Check if exists
            if (is_null($user)) {
                //If not throw exception
                throw new Pas_Image_Exception(
                    'No upload directory for that user'
                );
            }
            $this->_userPath = '/' . $user->username;
        } else {
            $this->_userPath = $path;
        }
        return $this->_userPath;
    }

    /** Get the directory path
     *
     * @return string
     * @access public
     */
    public function getDirectoryPath()
    {
        return $this->_directoryPath;
    }

    /** Set the directory path
     *
     * @access public
     *
     * @param string $directoryPath
     *
     * @throws Zend_Exception
     */
    public function setDirectoryPath($directoryPath)
    {
        if (!is_dir($directoryPath)) {
            throw new Pas_Image_Exception(
                'That directory path does not exist',
                500
            );
        }
        if (!is_writable($directoryPath)) {
            throw new Pas_Image_Exception(
                'That directory is not writable',
                500
            );
        }
        $this->_directoryPath = $directoryPath;
        return $this;
    }

    /** Get the user
     *
     * @access public
     * @return object
     */
    public function getUser()
    {
        $this->_user = new Pas_User_Details();
        return $this->_user;
    }

    /** Basic validation before resizing image
     * @throws Pas_Image_Exception
     */
    public function validateImage(?string $image, string $mime)
    {
        // If image parameter not set, throw exception
        if (is_null($image)) {
            throw new Pas_Image_Exception(
                'You must specify an image',
                500
            );
        }

        //Check file exists and if not throw exception
        if (!file_exists($image)) {
            throw new Pas_Image_Exception(
                'That image does not exist',
                500
            );
        }

        //Check image is in accepted mime types
        if (!in_array($mime, $this->getMimeTypes())) {
            throw new Pas_Image_Exception(
                'The mime type ' . $mime . ' is not supported',
                500
            );
        }
    }

    /** If image is not .jpg, convert or rename the image
     *
     * @throws Pas_Image_Exception
     */
    protected function convertBaseImage(string $image, string $mime)
    {
        $fileNameNoExt = pathinfo($image, PATHINFO_FILENAME);
        if ($mime != "image/jpeg") {
            $surrogate = Image::fromFile(
                $this->getImageName(),
                Image::LIB_IMAGICK
            );

            $surrogate->format('jpg');
            $surrogate->save(
                IMAGE_PATH . $this->getUserPath() . '/' .
                $fileNameNoExt . self::EXT
            );
        } elseif (pathinfo($this->getImageName(), PATHINFO_EXTENSION) == 'jpeg') {
            rename(
                $image,
                IMAGE_PATH . $this->getUserPath() . '/' .
                $fileNameNoExt . self::EXT
            );
        }
        return  pathinfo($image, PATHINFO_DIRNAME) . '/' . $fileNameNoExt . '.jpg';
    }

    /** Create the different sizes of images
     *
     * @access public
     * @return void
     * @throws Pas_Image_Exception|ImagickException
     */
    public function resize()
    {
        $image = $this->getImageName();
        $mime = (new Imagick($this->getImageName()))->getImageMimeType();
        $this->validateImage($image, $mime);
        //Make directory check for existence
        $this->checkDirectories();

        // Make directory check for permissions
        // $this->checkPermissions();

        //If the base image isn't a .JPG, we need to convert/rename this
        $image = $this->convertBaseImage($image, $mime);

        //Loop through each size and create the image
        foreach ($this->getSizes() as $resize) {
            // Set the file name
            if ($resize['destination'] == self::THUMB) {
                // Thumbnail sets record number as thumbnail ID
                $resizedImagePath = IMAGE_PATH . $resize['destination']
                    . $this->getImageNumber() . self::EXT;
            } else {
                $resizedImagePath = IMAGE_PATH . $this->getUserPath()
                    . $resize['destination'] .
                    pathinfo($this->getImageName(), PATHINFO_FILENAME)
                    . self::EXT;
            }

            // Set up the image creation class using imagick
            $surrogate = Image::fromFile(
                $image,
                Image::LIB_IMAGICK
            );
            $surrogate->resize($resize['width'], $resize['height'], 1);
            $surrogate->format('jpg');
            $surrogate->save($resizedImagePath);
        }
    }

    /** get the image
     *
     * @access public
     * @return string Path to file
     */
    public function getImageName()
    {
        //Return the original name
        return $this->_original;
    }

    /** Check directories exist for a user
     *
     * @access public
     * @return \Pas_Image_Magick
     */
    public function checkDirectories()
    {
        //For each directory in the list, check that the directory exists
        foreach ($this->getSizes() as $dir) {
            if ($dir['destination'] != self::THUMB) {
                //Set up each directory path
                $directory = IMAGE_PATH . $this->getUserPath()
                    . $dir['destination'];
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

    /** Make directory if does not exist
     *
     * @access public
     *
     * @param $path
     */
    protected function _makeDirectory($path, $recursive = TRUE)
    {
        return mkdir($path, self::PERMS, $recursive);
    }

    /** Get the image number
     *
     * @access public
     * @return int
     */
    public function getImageNumber()
    {
        return $this->_imageNumber;
    }

    /** Set the image id number
     *
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

    /** Get the basename of the image minus extension
     *
     * @access public
     * @return string
     */
    public function getBasename()
    {
        //Return the basename
        return $this->_basename;
    }

    /** Get the array of mime types accepted
     *
     * @access public
     * @return array
     */
    public function getMimeTypes()
    {
        return $this->_mimeTypes;
    }

    /** Convert tiff to jpeg
     *
     * @access public
     *
     * @param $image
     *
     * @throws Pas_Image_Exception
     */
    public function convertTiff($image)
    {
        $tiffDir = IMAGE_PATH . $this->getUserPath() . self::TIFFS;
        //Determine path to Tiff folder
        $tiffPath = $tiffDir . $this->getBasename() . self::EXT;
        //Where we will be saving the file
        $destination = $this->getUserPath();

        //Check if directory exists, if not then make directory
        if (!is_dir($tiffDir)) {
            $this->_makeDirectory($tiffDir);
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
}
