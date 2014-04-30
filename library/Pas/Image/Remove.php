<?php




class Pas_Image_Remove {
	
	//Set up array of sizes
	protected $_sizes; 
	
	//Create user path string
	protected $_userPath;
	
	//Create original
	protected $_original;
	
	//User object
	protected $_user;
	
	//Thumbnail directory
	const THUMB	= 'thumbnails/';
	
	//Small image directory
	const SMALL		= 'small/';
	
	//Medium image directory
	const MEDIUM	= 'medium/';

	//Large image directory
	const LARGE	= 'large/';
	
	//Display image directory
	const DISPLAY 	= 'display/';
	
	//Create directory to store tiffs
	const TIFFS	= 'tiffs/';
	
	const EXT	= '.jpg';
	
	public function __construct( )
	{
		$this->_sizes = array(
			array ( 'destination' => self::THUMB, 'width' => 100, 'height' => 100 ),
			array ( 'destination' => self::SMALL, 'width' => 40, 'height' => 0 ),
			array ( 'destination' => self::MEDIUM, 'width' => 500, 'height' => 0 ),
			array ( 'destination' => self::DISPLAY, 'width' => 0, 'height' => 200 ),
		);
		$this->_user = new Pas_User_Details();
	}

	/** Set up the image
	 * 
	 * @param unknown_type $image
	 */
	public function setImage(  $image ) {
		//Original name
		$this->_original = $image;
		
		//Just get the basename
		$this->_basename = basename( $image );
		return $this;	
	}

	/** get the image
	 * 
	 */
	public function getImage()
	{
		//Return the original name
		return $this->_original;
	}
	
	/** Get the user's path
	 * 
	 */
	public function getUserPath( )
	{
		//Get the user's imagedir from the person object
		$userDirectory = $this->_user->getPerson()->imagedir;
		//Check if exists
		if(is_null($userDirectory)){
			//If not throw exception
			throw new Zend_Exception('No upload directory for that user');
		} else {
		//Return the path
		$this->_userPath = '.' . $userDirectory;
		} 		
	}
	
	
	/** Create the different sizes of images
	 * 
	 * @param $image
	 */
	
	public function resize( ) 
	{
		$image = $this->getImage();
		if(is_null( $image )) {
			throw new Zend_Exception('You must specify an image', 500); 
		}
		//Original image path. We leave this as is.
		$destination =  $this->getUserPath() . $image ;
		//Check file exists
		if( !file_exists( $destination ) ) {
			throw new Zend_Exception('That image does not exist', 500); 
		}
		//Make directory check
		$this->checkDirectories();
		
		//Loop through each size and create the image
		foreach( $this->_sizes as $resize )
		{
			$newImage = $this->getUserPath() . $resize['destination'] 
			. $this->getBasename() . self::EXT;
			$image = Image::create( $destination, 'Imagick' );
			$mime = $image->getMimeType();
			if( in_array( $mime, $this->_mimeTypes ) )
			{
				$image->resize( $resize['width'], $resize['height'], 1);
				$image->format('jpg');
				$image->save( $newImage );
			}
			if( in_array( $mime, $this->_tiffMimes ) ) {
				//Convert tiff to JPG and repeat above, replace original and save tiff in tiffs folder
				$this->convertTiff( $image );
			} 
		}
		//Create the zooming images
		$this->zoomifyImage( $image );
	}
	
	
	
	
}