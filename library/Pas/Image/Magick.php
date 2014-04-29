<?php

namespace Imagecow;

use Imagecow\Image;


class Pas_Image_Magick {
	
	protected $_sizes; 
	
	protected $_imagick;
	
	protected $_userPath;
	
	protected $_original;
	
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
	
	const IMAGEDIR	= './images/';
	
	public function __construct( )
	{
		$this->_sizes = array(
			array ( 'destination' => self::THUMB, 'width' => 100, 'height' => 100 ),
			array ( 'destination' => self::SMALL, 'width' => 100, 'height' => 100 ),
			array ( 'destination' => self::MEDIUM, 'width' => 100, 'height' => 100 ),
			array ( 'destination' => self::DISPLAY, 'width' => 100, 'height' => 100 ),
		);
		$this->_user = new Pas_User_Details();
	}
	
	public function setOriginal(  $original ) {
		$this->_original = $original;
		return $this;	
	}

	public function getUserPath( )
	{
		$this->_userPath = $this->_user->getPerson()->username;		
	}
	
	public function checkDirectories()
	{
		foreach( $this->_sizes as $dir )
		{
			$directory = self::IMAGEDIR . $this->getUserPath() . $dir['destination'];
			if(!is_dir( $directory ))
			{
				mkdir($directory, 755);	
			}
		}
		return $this;
	}
	
	public function resize( ) 
	{
		$path =  self::IMAGEDIR . $this->getUserPath() . $this->_original;
		$this->checkDirectories();
		$name = $this->_original;	
		foreach( $this->_sizes as $resize )
		{
			$newImage = self::IMAGEDIR . $this->getUserPath() . $resize['destination'] . self::EXT;
			$image = Image::create( $path, 'Imagick' );
			$mime = $image->getMimeType();
			if( in_array( $mime, $this->_mimeTypes ) )
			{
				$image->resize( $resize['width'], $resize['height'], 1);
				$image->save($newImage);
			}
		}
	}
	
	
}