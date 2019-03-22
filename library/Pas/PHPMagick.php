<?php

use Imagecow\Image;

class PHPMagick {
    /** Allowed mime types */
    protected $_mimeTypes = array(
        'image/jpeg',
        'image/png',
        'image/JPEG'
    );

    /** Get the array of mime types accepted
     * @access public
     * @return array
     */
    public function getMimeTypes()
    {
        return $this->_mimeTypes;
    }

    public function resize($source, $destination)
    {
        // If image parameter not set, throw exception
        if (is_null($source)) {
            throw new Pas_Image_Exception('You must specify an image', 500);
        }

        // Check file exists and if not throw exception
        if (!file_exists($source)) {
            throw new Pas_Image_Exception('That image does not exist', 500);
        }

	// Check that destination is an array (or whatever it is)
	if (!is_array($destination))
	{
	    throw new InvalidArgumentException('Please provide an array which should have image details', 500);
	}

	// Check that there is a width and height key
	if (!(array_key_exists('width', $destination) and array_key_exists('height', $destination)))
	{
            throw new InvalidArgumentException('Please provide an array with width and height for an image', 500);
	}

        // Create the image
	$newImage = $destination['destination'] . basename($source);

        // Set up the image creation class using imagick
        $surrogate = Image::fromFile($source, Image::LIB_IMAGICK);

        // Get the mime type
        $mime = $surrogate->getMimeType();

        // Check if mime type is in the accepted array of types
        if (in_array($mime, $this->getMimeTypes())) {
            $surrogate->resize($destination['width'], $destination['height'], 1);
            $surrogate->format('jpg');
            $surrogate->save($newImage);
        }
    }
}
