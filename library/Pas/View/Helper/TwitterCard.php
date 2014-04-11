<?php
/** 
 * A view helper for Zend Framework projects to generate Twitter card metadata
 * This is an adaptation of work by Niall Kennedy detailed below
 * @see https://github.com/niallkennedy/twitter-cards-php for elements that create this helper
 * @version 1.0
 * @since 27 November 2012
 * @author Niall Kennedy
 * @author Daniel Pett dpett @ britishmuseum . org
 * @link https://dev.twitter.com/docs/cards See this page for Twitter Card documentation
 * @copyright Daniel Pett/ British Museum
 * @license GNU General Public License
 * @uses Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_HeadMeta
 */
class Pas_View_Helper_TwitterCard extends Zend_View_Helper_Abstract {

	/**
	 * The string to use as the Twitter prefix (twitter:) in metadata
	 *
	 * @var string
	 */
	const PREFIX = 'twitter:';
	
	/**
	 * The prefix for account (just in case it ever changes -doubtful)
	 *
	 * @var string
	 */
	const AT_PREFIX = '@';
	
	/**
	 * The Twitter API version in use
	 *
	 * @var string
	 */
	const API_VERSION = '1.1';
	
	/**
	 * The version in use
	 *
	 * @var string
	 */
	const VERSION = '1.0';
	
	/**
	 * The valid card types available to developers
	 * @access protected
	 * @var array
	 */
    public static $cardTypes = array('summary', 'photo', 'player');
    
    /**
	 * The default type of twitter card
	 *
	 * @since 1.0
	 * @var string
	 */
    const DEFAULT_CARD = 'summary';
    
    /**
	 * Only allow URLS with the HTTP and HTTPs schemas
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
    public static $allowedSchemes = array('http', 'https');
    
    
	/**
	 * Return the Twitter Card
	 * @access public
	 * @param string $card_type The card type. one of "summary", "photo", "player"
	 */
	public function twitterCard( $type = '' ){
		//Check if the card type is a string and in the array of available types
		if ( is_string( $type ) && in_array( $type, self::$cardTypes ) ) {
			$this->card = $type;
		} else {
			$this->card = self::DEFAULT_CARD;
		}
		return $this;	
	}
	
	
	/**
	 * Test an inputted Twitter username for validity
	 * @access public
	 * @param string $username Twitter username
	 * @return bool true if valid else false
	 */
	public static function isValidUsername( $username ) {
		//Check if the username provided is a string and return true if condition met
		if ( is_string( $username ) && $username ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Test to check for numerical Twitter ID
	 * @access public
	 * @param string $id Twitter user ID string
	 * @return bool true if the string contains only digits. else false
	 */
	public static function isValidId( $id ) {
		// Check for integer value 
		if ( is_int( $id ) ) {
			return true;
		}
		// Check if the string contains only digits
		if ( is_string( $id ) && ctype_digit( $id ) ) {
			return true;
		}
		//If the above cases are not met, return false
		return false;
	}
	
	/**
	 * Test if given URL is valid and matches allowed schemes
	 * 
	 * @param string $url URL to test
	 * @param array $allowedSchemes one or both of http, https
	 * @return bool true if URL can be parsed and scheme allowed, else false
	 */
	public static function isValidUrl( $url, $allowedSchemes = null ) {
		if ( ! ( is_string( $url ) && $url ) ) {
			return false;
		}
		if ( ! is_array( $allowedSchemes ) || empty( $allowedSchemes ) ) {
			$schemes = self::$allowedSchemes;
		} else {
			$schemes = array();
			foreach ( $allowedSchemes as $scheme ) {
				if ( in_array( $scheme, self::$allowedSchemes, true ) ) {
					$schemes[] = $scheme;
				}
			}

			if ( empty( $schemes ) ) {
				$schemes = self::$allowedSchemes;
			}
		}

		// parse_url will test scheme + full URL validity vs. just checking if string begins with "https://"
		try {
			$scheme = parse_url( $url, PHP_URL_SCHEME );
			if ( is_string( $scheme ) && in_array( strtolower( $scheme ), $schemes, true ) ) {
				return true;
			}
		} catch( Exception $e ) {
			// E_WARNING in PHP < 5.3.3
		} 

		return false;
	}

	
	/**
	 * Set the twitter card title. 
	 * The card documentation states that it will be truncated at 70 characters ergo 
	 * you don't need to worry about truncating the title you provide
	 * @access public
	 * @param string $title The page title
	 * @return Twitter_Card object for chaining
	 */
	public function setTitle( $title ) {
		//Check if the title is a string
		if ( is_string( $title ) ) {
			//Trim the title string for excess white space		
			$title = trim( $title );
			// From the documentation, you can have an empty string for photo cards
			if ( ! $title && $this->card !== 'photo' ){
				return;
			}
			$this->title = $title;
			
		}
		return $this;
	}
    
	/**
	 * Set up the Canonical URL. Check for validity and return string
	 * @access public
	 * @since 1.0
	 * @param string $url canonical URL
	 * @return Twitter_Card object for chaining
	 */
	public function setURL( $url ) {
		//Check if supplied url is valid
		if ( self::isValidUrl( $url ) ){
			//if valid set the url
			$this->url = $url;
		}
		//return the twitter card object
		return $this;
	}
	
	/**
	 * A description of the content.
	 * Descriptions over 200 characters in length will be auto truncated by Twitter 
	 *
	 * @since 1.0
	 * @param string $description description of page content
	 * @return Twitter_Card for chaining
	 */
	public function setDescription( $description ) {
		//Check if the description is a string
		if ( is_string( $description ) ) {
			//Trim whitespace
			$description = trim( $description );
			//If description exists, set description
			if ( $description )
				$this->description = $description;
		}
		//return twitter card object
		return $this;
	}
	
	/**
	 * URL of an image representing the post, with optional dimensions to help preserve aspect 
	 * ratios on Twitter resizing
	 * Minimum size for photo cards: 280x150px
	 * Minimum size for all others: 60x60px
	 * For summary cards, by default twitter will resize and crop images larger than 120x120px 
	 *
	 * @since 1.0
	 * @param string $url URL of an image representing content
	 * @param int $width width of the specified image in pixels
	 * @param int $height height of the specified image in pixels
	 * @return Twitter_Card for chaining
	 */
	public function setImage( $url, $width = 0, $height = 0 ) {
		//Check the validity of the image
		if ( ! self::isValidUrl( $url ) ) {
			//If invalid, just return the twitter card object
			return $this;
		}
		//Create a new image object to attach attributes to
		$image = new stdClass();
		//Set the url for the image
		$image->url = $url;
		//Check for +ve integers for width and height;
		if ( is_int( $width ) && is_int( $height ) && $width > 0 && $height > 0 ) {
						// minimum dimensions for all card types
			if ( $width < 60 || $height < 60 ) {
				return $this;
			}
			// minimum dimensions for photo cards - width greater than 280, height greater than 150
			if ( in_array( $this->card, array( 'photo', 'player' ), true ) 
				&& ( $width < 280 || $height < 150 ) ) {
				return $this;
			}
			//Set the width of the image
			$image->width = $width;
			//Set the height of the image
			$image->height = $height;
		}
		//Set the $image object
		$this->image = $image;
		//return the twitter card object
		return $this;
	}
	
	/**
	 * An HTTPS URL of an HTML suitable for display in an iframe
	 * Expected width and height of the iframe are required
	 * If the iframe width is greater than 435 pixels Twitter will resize to 
	 * fit a 435 pixel width column
	 *
	 * @since 1.0
	 * @param string $url HTTPS URL to iframe player
	 * @param int $width width in pixels preferred by iframe URL
	 * @param int $height height in pixels preferred by iframe URL
	 * @return Twitter_Card for chaining
	 */
	public function setVideo( $url, $width, $height ) {
		//Check the url submitted is valid and +ve integers for dimensions
		if ( ! ( self::isValidUrl( $url, array( 'https' ) ) && is_int( $width ) 
			&& is_int( $height ) && $width > 0 && $height > 0 ) ) {
			//if failed just return the twitter card object
			return;
		}
		//Create a new video object
		$video = new stdClass();
		//Set the url for the video
		$video->url = $url;
		//Set the video width
		$video->width = $width;
		//Set the video height
		$video->height = $height;
		//Create the video object
		$this->video = $video;
		//return the twitter card object
		return $this;
	}

	/**
	 * Link to a direct MP4 file with H.264 Baseline Level 3 video and AAC LC audio tracks
	 * Videos up to 640x480 pixels supported
	 * @access public
	 * @param string $url URL
	 * @return Twitter_Card for chaining
	 */
	public function setVideoStream( $url ) {
		//Check if a video element is set up and if the URL is valid
		if ( ! ( isset( $this->video ) && self::isValidUrl( $url ) ) ) {
			return $this;
		}
		//Create the video stream object
		$stream = new stdClass();
		//Add the url to the stream object
		$stream->url = $url;
		//Create the type
		$stream->type = 'video/mp4; codecs=&quot;avc1.42E01E1, mpa.40.2&quot;';
		//Add the stream to the video object
		$this->video->stream = $stream;
		//Return the twitter card for chaining
		return $this;
	}

	/**
	 * Build a user object based on username and id inputs
	 *
	 * @since 1.0
	 * @param string $username Twitter username. no need to include the "@" prefix
	 * @param string $id Twitter numerical ID
	 * @return array associative array with username key and optional id key
	 */
	public static function filterAccountInfo( $username, $id = '' ) {
		//Check if the username is a string if not return null
		if ( ! is_string( $username ) ) {
			return null;
		}
		//Trim the @ off the username if provided
		$username = ltrim( trim( $username ), self::AT_PREFIX );
		
		//Check if username is valid and if not return null
		if ( ! ( $username && self::isValidUsername( $username ) ) ){
			return null;
		}
		
		//Create a new user object
		$user = new stdClass();
		
		//Set the username
		$user->username = $username;
		if ( $id && self::isValidId( $id ) ){
		//Set the user id for the user object
			$user->id = (string) $id;
		}
		
		//return the user object
		return $user;
	}

	
	
	/**
	 * Twitter account for the site: Twitter username and optional account ID
	 * A user may change his username but his numeric ID will stay the same
	 *
	 * @since 1.0
	 * @param string $username Twitter username. no need to include the "@" prefix
	 * @param string|int $id Twitter numerical ID. This is passed as a string to better 
	 * handle large numbers
	 * @return Twitter_Card for chaining
	 */
	public function setSiteAccount( $username, $id = '' ) {
		//Use the static function to filter username and id data
		$user = self::filterAccountInfo( $username, $id );
		//Check if the username has been set
		if ( $user && isset( $user->username ) ){
			$this->site = $user;
		}
		//return the Twitter card for chaining
		return $this;
	}

	/**
	 * Content creator / author
	 *
 	 * @since 1.0
	 * @param string $username Twitter username. You don't need to include the "@" prefix
	 * @param string|int $id Twitter numerical ID. This is passed as a string to better handle large numbers
	 * @return Twitter_Card for chaining
	 */
	public function setCreatorAccount( $username, $id = '' ) {
		//Use the static function to filter username and id data
		$user = self::filterAccountInfo( $username, $id );
		//Check if the username has been set
		if ( $user && isset( $user->username ) ) {
			$this->creator = $user;
		}
		//return the Twitter card for chaining
		return $this;
	}
	
	
	/**
	 * Translate object properties into an associative array of Twitter property 
	 * names as keys mapped to their value
	 * @access public
	 * @return array associative array with Twitter card properties as a key with their respective values
	 */
	public function toArray() {
		//Check if all the required properties have been set for the card metadata you are building
		if ( !$this->requiredPropertiesExist() ) {
			return array();
		}

		// Initialize with required properties (type, url and title)
		$metaData = array(
			'card' => $this->card,
			'url' => $this->url,
			'title' => $this->title
		);
		
		//Add the description to the array
		if ( isset( $this->description ) ) {
			$metaData['description'] = $this->description;
		}

		// Add an image to the array with dimensions if set
		if ( isset( $this->image ) && isset( $this->image->url ) ) {
			$metaData['image'] = $this->image->url;
			if ( isset( $this->image->width ) && isset( $this->image->height ) ) {
				$metaData['image:width'] = $this->image->width;
				$metaData['image:height'] = $this->image->height;
			}
		}

		// Check that video has not been set for a photo
		if ( $this->card !== 'photo' && isset( $this->video ) && isset( $this->video->url ) ) {
			$metaData['player'] = $this->video->url;
			if ( isset( $this->video->width ) && isset( $this->video->height ) ) {
				$metaData['player:width'] = $this->video->width;
				$metaData['player:height'] = $this->video->height;
			}

			// no video stream without a main video player. content type required.
			if ( isset( $this->video->stream ) && isset( $this->video->stream->url ) && isset( $this->video->stream->type ) ) {
				$metaData['player:stream'] = $this->video->stream->url;
				$metaData['player:stream:content_type'] = $this->video->stream->type;
			}
		}

		// Add the site acccount and if set the optional twitter ID
		if ( isset( $this->site ) && isset( $this->site->username ) ) {
			$metaData['site'] = self::AT_PREFIX . $this->site->username;
			if ( isset( $this->site->id ) )
				$metaData['site:id'] = $this->site->id;
		}

		// Add the creator metadata and if set the optional twitter ID (this never changes, username can)
		if ( isset( $this->creator ) && isset( $this->creator->username ) ) {
			$metaData['creator'] = self::AT_PREFIX . $this->creator->username;
			if ( isset( $this->creator->id ) )
				$metaData['creator:id'] = $this->creator->id;
		}
		return $metaData;
	}
	
	
	/** 
	 * Create the headMeta elements for returning Zend Head Meta elements
	 * @uses Zend_View_Helper_HeadMeta
	 * @access public
	 * @since 1.0
	 * @return Twitter_Card
	 */
	public function create()
    {
    	//Get the twitter metadata associative array 
    	$twitterMeta = $this->toArray();
    	//Check if not empty and if it is return false
		if ( empty( $twitterMeta ) ) {
			return false;
		}
		//If it does exist, create the Zend_View_Helper_HeadMeta elements using the key value pairs
		foreach ( $twitterMeta as $name => $value ) {
		$this->view->headMeta()->setProperty( self::PREFIX . $name, $value );
		}
		//Return the twitter card
		return $this;
    }
 
	/**
	 * Check if all required properties have been set
	 * The required properties vary by card type
	 * @access private
	 * @return bool true if all required properties exist for the specified type, else false
	 */
	private function requiredPropertiesExist() {
		
		//If the url is not set set and a title is set, return false. Url is required.
		if ( ! ( isset( $this->url ) && isset( $this->title ) ) ) {
			return false;
		}

		// The description is required for summary & video but not photo. Therefore if not set and the card
		// type is summary or video return false
		if ( ! isset( $this->description ) && $this->card !== 'photo' ) {
			return false;
		}
		
		// image optional for summary
		if ( in_array( $this->card, array( 'photo', 'player' ), true ) && ! ( isset( $this->image ) 
			&& isset( $this->image->url ) ) ) {
			return false;
		}
		
		// if the card type is set to player, then check that a video has been set
		if ( $this->card === 'player' && ! ( isset( $this->video ) && isset( $this->video->url ) 
			&& isset( $this->video->width ) && isset( $this->video->height ) ) ) {
			return false;
		}
		
		//If none of the above conditions are met then return test positive
		return true;
	}
	
}