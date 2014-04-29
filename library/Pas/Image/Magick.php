<?php

namespace Imagecow;

use Imagecow\Image;


class Pas_Image_Magick {
	
	protected $_sizes; 
	
	protected $_imagick;
	
	protected $_userPath;
	
	protected $_extensions	= array( 'jpg', 'jpeg', 'png', 'tiff', 'tif');
	
	protected $_mimeTypes 	= array( 
		'image/jpeg',
		'image/pjpeg',
		'image/png',
		'image/tiff',
		'image/x-tiff'
	); 
	
	protected $_user;
	
	const THUMB		= 'thumbnails/';
	
	const SMALL		= 'small/';
	
	const MEDIUM	= 'medium/';

	const LARGE		= 'large/';
	
	const DISPLAY 	= 'display/';
	
	const TIFFS		= 'tiffs/';
	
	const ZOOM		= 'zoom/';
	
	const EXT		= '.jpg';
	
	const IMAGEDIR	= 'images/';
	
	public function __construct( )
	{
		$this->_sizes = array(
			array ( 'destination' => self::THUMB, 'width' => 100, 'height' => 100 ),
			array ( 'destination' => self::SMALL, 'width' => 100, 'height' => 100 ),
			array ( 'destination' => self::MEDIUM, 'width' => 100, 'height' => 100 ),
			array ( 'destination' => self::DISPLAY, 'width' => 100, 'height' => 100 )
		);
		$this->_user = new Pas_User_Details();
	}
	
	public function setOriginal(  $original ) {
		
	}

	public function getUserPath( )
	{
		$this->_userPath = $this->_user->getPerson()->username;		
	}
	
	public function resize
	
	
}