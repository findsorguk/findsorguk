<?php
/** A class wrapper for the Imagecow library just for PAS use
 *
 */

use Imagecow\Image;


class Pas_Image_Magick
{

    /** Set up array of sizes */
    protected $_sizes;

    /** Create user path string */
    protected $_userPath;

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
     * @
     */
    public function getUser()
    {
        $this->_user = new Pas_User_Details();
        return $this->_user;
    }

    /** Set up the image
     * @access public
     * @param unknown_type $image
     */
    public function setImage($image)
    {
        //Original name
        $this->_original = $image;
		//Just get the basename
		$this->_basename = basename($image);
		return $this;	
	}


    public function setImageNumber($id)
    {
        if (is_int($id)) {
            $this->_imageNumber = $id;
        } else {
            throw new Zend_Exception ('No file to create', 500);
        }
        return $this;
    }

    public function getImageNumber()
    {
        return $this->_imageNumber;
    }

    /** get the image
     *
     */
    public function getImage()
    {
        //Return the original name
        return $this->_original;
    }

    public function getMimeTypes()
    {
        return $this->_mimeTypes;
    }

    /** get the basename of the image minus extension
     *
     */
    public function getBasename()
    {
        //Return the basename
        return $this->_basename;
    }

    /** Get the user's path
     *
     */
    public function getUserPath()
    {
        //Get the user's imagedir from the person object
        $user = $this->getUser()->getPerson();
        //Check if exists
        if (is_null($user)) {
            //If not throw exception
            throw new Zend_Exception('No upload directory for that user');
        }
        $this->_userPath = '/' . $user->username;
        return $this->_userPath;
    }

    /** Check directories exist for a user
     *
     */
    public function checkDirectories()
    {
        //For each directory in the list, check that the directory exists
        foreach ($this->getSizes() as $dir) {
            //Set up each directory path
            $directory = IMAGE_PATH . $this->getUserPath() . $dir['destination'];
            //Check if directory exists and if not create.
            if (!is_dir($directory)) {
                $this->_makeDirectory($directory);
            }
        }
        return $this;
    }

    public function checkPermissions()
    {
        //For each directory in the list, check that the directory exists
        foreach ($this->getSizes() as $dir) {
            //Set up each directory path
            $directory = IMAGE_PATH . $this->getUserPath() . $dir['destination'];
            //Check if directory exists and if not create.
            if (!is_writable($directory)) {
                chmod($directory, self::PERMS);
            }
        }
        return $this;
    }

    /** Create the different sizes of images
     *
     * @param $image
     */

    public function resize()
    {
        // Get the image
        $image = $this->getImage();
        // If image parameter not set, throw exception
        if (is_null($image)) {
            throw new Zend_Exception('You must specify an image', 500);
        }

        //Check file exists and if not throw exception
        if (!file_exists($image)) {
            throw new Zend_Exception('That image does not exist', 500);
        }
        //Make directory check for existence
        $this->checkDirectories();
        // Make directory check for permissions
        $this->checkPermissions();

        //Loop through each size and create the image
        foreach ($this->getSizes() as $resize) {
            // Set the file name
            if ($resize['destination'] == self::THUMB) {
                // Thumbnail sets record number as thumbnail ID
                $newImage = IMAGE_PATH . $this->getUserPath() . $resize['destination'] . $this->getImageNumber() . self::EXT;
            } else {
                // Normal base name otherwise
                $newImage = IMAGE_PATH. $this->getUserPath() . $resize['destination'] . $this->getBasename();
            }
            // Set up the image creation class using imagick
            $surrogate = Image::create($this->getImage(), 'Imagick');
            // Get the mime type
            $mime = $surrogate->getMimeType();
            // Check if mime type is in the accepted array of types
            if (in_array($mime, $this->getMimeTypes())) {
                $surrogate->resize($resize['width'], $resize['height'], 1);
                $surrogate->format('jpg');
                $surrogate->save($newImage);
            }
            // If the mime type is a tiff do this
            if (in_array($mime, $this->_tiffMimes)) {
                //Convert tiff to JPG and repeat above, replace original and save tiff in tiffs folder
                $this->convertTiff($image);
            }
        }
    }


    /** Convert tiff to jpeg
     * @access public
     * @param $image
     */
    public function convertTiff()
    {
        //Determine path to Tiff folder
        $tiffPath = IMAGE_PATH . $this->getUserPath() . self::TIFFS . $this->getBasename() . self::EXT;
        //Where we will be saving the file
        $destination = $this->getUserPath();

        //Check if directory exists, if not then make directory
        if (!is_dir($tiffPath)) {
            $this->_makeDirectory($tiffPath);
        }

        //Create an instance of the image to save
        $surrogate = Image::create($tiffPath, 'Imagick');
        //Set the image to save as jpeg file
        $surrogat->format('jpg');
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