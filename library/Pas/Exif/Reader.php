<?php
/** PHP Class to read EXIF information that most of the digital camera produce
 * For more information on EXIF
 * @link http://www.exif.org/
 * Features:
 *   - Read Exif Information
 *   - Extract and display emdedded thumbnails
 * @todo Writing exif information to the file.
 * @todo Add EXIF audio reading methods (I think it exists!)
 * @todo Support of additional tags.
 * @todo Handling Unicode character in UserComment tag of EXif Information.
 * @author Originally Vinay Yadav (vinayRas) < vinay@sanisoft.com > | Modified by Daniel Pett
 * @version 0.5
 * @license http://opensource.org/licenses/lgpl-license.php GNU LGPL
 * 
 * 1 - File does not exists!
 * 2 -
 * 3 - Filename not provided
 * 10 - too many padding bytes
 * 11 - "invalid marker"
 * 12 - Premature end of file?
 * 51 - "Illegal subdirectory link"
 * 52 - "NOT EXIF FORMAT"
 * 53 - "Invalid Exif alignment marker.\n"
 * 54 - "Invalid Exif start (1)"
*/

class Pas_Exif_Reader {
	
	const M_SOF0 = 0xC0;

	const M_SOF1 = 0xC1;// N indicates which compression process 
	
	const M_SOF2 = 0xC2; // Only SOF0-SOF2 are now in common use 

	const M_SOF3 = 0xC3;

	const M_SOF5 = 0xC5;// NB: codes C4 and CC are NOT SOF markers 

	const M_SOF6 = 0xC6;

	const M_SOF7 = 0xC7;

	const M_SOF9 = 0xC9;

	const M_SOF10 = 0xCA;

	const M_SOF11 = 0xCB;
	
	const M_SOF13 = 0xCD;

	const M_SOF14 = 0xCE;

	const M_SOF15 = 0xCF;

	const M_SOI = 0xD8;/** * Start Of Image (beginning of datastream) */

	const M_EOI = 0xD9;/** * End Of Image (end of datastream) */

	const M_SOS =0xDA;/** * Start Of Scan (begins compressed data) */

	const M_JFIF = 0xE0;/** * Jfif marker */

	const M_EXIF = 0xE1;/** * Exif marker */

	const M_EXIF_EXT = 0xE2; // 226 - Exif Extended Data
	
	const M_QUANTA = 0xDB; // 219 - Quantisation Table Definition

	const M_HUFF = 0xC4; // (DEC=196) - Huffman Table Definition

	const M_COM = 0xFE;/** * Image Title -- */

	const NUM_FORMATS = 12;

	const FMT_BYTE = 1;/** * Tag Data Format */
	
	const FMT_STRING = 2;/** * ASCII */

	const FMT_USHORT = 3;/** * Short */

	const FMT_ULONG = 4;/** * Long */

	const FMT_URATIONAL = 5;/** * Rational */

	const FMT_SBYTE = 6;/** * Byte */

	const FMT_UNDEFINED = 7;/** * Undefined */

	const FMT_SSHORT = 8;/** * Short */

	const FMT_SLONG = 9;/** * Long */

	const FMT_SRATIONAL = 10;/** * Rational */

	const FMT_SINGLE = 11;/** * Single */

	const FMT_DOUBLE = 12;	/** * Double */

	const TAG_EXIF_OFFSET = 0x8769;/** * Exif IFD */

	const TAG_INTEROP_OFFSET = 0xa005;/** * Interoperability tag */

	const TAG_MAKE = 0x010F;/** * Image input equipment manufacturer */

	const TAG_MODEL = 0x0110;/** * Image input equipment model */

	const TAG_ORIENTATION = 0x0112;/** * Orientation of image */

	const TAG_EXPOSURETIME = 0x829;/** * Exposure Time */

	const TAG_FNUMBER = 0x829D;/** * F Number */

	const TAG_SHUTTERSPEED = 0x9201;/** * Shutter Speed */

	const TAG_APERTURE = 0x9202;/** * Aperture */

	const TAG_MAXAPERTURE = 0x9205;/** * Aperture */

	const TAG_FOCALLENGTH = 0x920A;/** * Lens Focal Length */

	const TAG_DATETIME_ORIGINAL = 0x9003;/** * The date and time when the original image data was generated. */

	const TAG_USERCOMMENT = 0x928;/** * User Comments */

	const TAG_SUBJECT_DISTANCE = 0x9206;/** * subject Location */

	const TAG_FLASH = 0x9209;/** * Flash */

	const TAG_FOCALPLANEXRES = 0xa20E;/** * Focal Plane X Resolution */

	const TAG_FOCALPLANEUNITS = 0xa210;/** * Focal Plane Resolution Units */

	const TAG_EXIF_IMAGEWIDTH = 0xA002;/** * Image Width */

	const TAG_EXIF_IMAGELENGTH = 0xA003;/** * Image Height */

	const TAG_EXPOSURE_BIAS = 0x9204;/** * Exposure Bias */

	const TAG_WHITEBALANCE = 0x9208;/** * Light Source */

	const TAG_METERING_MODE = 0x9207;/** * Metering Mode */

	const TAG_EXPOSURE_PROGRAM = 0x8822;/** * Exposure Program */

	const TAG_ISO_EQUIVALENT = 0x8827;/** * ISO Equivalent Speed Rating */

	const TAG_COMPRESSION_LEVEL = 0x9102;/** * Compressed Bits Per Pixel */

	const TAG_THUMBNAIL_OFFSET = 0x0201;/** * Thumbnail Start Offset */

	const TAG_THUMBNAIL_LENGTH = 0x0202;/** * Thumbnail Length */

	const PSEUDO_IMAGE_MARKER = 0x123;/** * Image Marker */

	const MAX_COMMENT = 2000;/** * Max Image Title Length */

	const TAG_ARTIST = 0x013B;

	const TAG_COPYRIGHT = 0x8298;

	const TAG_IMAGE_WD = 0x0100; // image width
	
	const TAG_IMAGE_HT = 0x0101; // image height

	const TAG_IMAGE_BPS = 0x0102; // Bits Per sample

	const TAG_IMAGE_PHOTO_INT = 0x0106; // photometricinterpretation
	
	const TAG_IMAGE_SOFFSET = 0x0111; // stripoffsets

	const TAG_IMAGE_SPP = 0x0115; // Samples per pixel - 277

	const TAG_IMAGE_RPS = 0x0116; // RowsPerStrip - 278
	
	const TAG_IMAGE_SBC = 0x0117; // StripByteCounts - 279
	
	const TAG_IMAGE_P_CONFIG = 0x011C; // Planar Configuration - 284

	const TAG_IMAGE_DESC = 0x010E; // image title

	const TAG_X_RESOLUTION = 0x011A; // Image resolution in width direction
	
	const TAG_Y_RESOLUTION = 0x011B; // Image resolution in height direction
	
	const TAG_RESOLUTION_UNIT = 0x0128; // Unit of X and Y resolution
	
	const TAG_SOFTWARE = 0x0131; // Software used
	
	const TAG_FILE_MODDATE = 0x0132; // DateTime File change date and time
	
	const TAG_YCBCR_POSITIONING = 0x0213; // Y and C positioning
	
	const TAG_EXIF_VERSION = 0x9000; // Exif version

	const TAG_DATE_TIME_DIGITIZED = 0x9004; // Date and time of digital data

	const TAG_COMPONENT_CONFIG = 0x9101; // Component configuration

	const TAG_MAKER_NOTE = 0x927C;

	const TAG_SUB_SEC_TIME = 0x9290;

	const TAG_SUB_SEC_TIME_ORIG = 0x9291;
		
	const TAG_SUB_SEC_TIME_DIGITIZED = 0x9292;
	
	const TAG_FLASHPIX_VER = 0xA000; //FlashPixVersion
	
	const TAG_COLOR_SPACE = 0xA001; //ColorSpace
	
	const TAG_RELATED_SOUND_FILE = 0xA004; //Related audio file
	
	const TAG_GPS_LATITUDE_REF = 0x0001; //
	
	const TAG_GPS_LATITUDE = 0x0002; //
	
	const TAG_GPS_LONGITUDE_REF = 0x0003; //
	
	const TAG_GPS_LONGITUDE = 0x0004; //
	
	const TAG_GPS_TrackRef = 0x000E; //
	
	const TAG_GPS_GPSTrack = 0x000F; //
	
	const TAG_GPS_GPSImgDirectionRef = 0x0010; //
	
	const TAG_GPS_GPSImgDirection = 0x0011; //
	
	const TAG_GPS_GPSMapDatum = 0x0012; //
	
	const TAG_GPS_GPSDestLatitudeRef = 0x0013; //
	
	const TAG_GPS_GPSDestLatitude = 0x0014; //
	
	const TAG_GPS_GPSDestLongitudeRef = 0x0015; //
	
	const TAG_GPS_GPSDestLongitude = 0x0016; //
	
	const TAG_GPS_GPSDestBearingRef = 0x0017; //
	
	const TAG_GPS_GPSDestBearing = 0x0018; //
	
	const TAG_GPS_GPSDestDistanceRef = 0x0019; //
	
	const TAG_GPS_GPSDestDistance = 0x001A; //
	
	const TAG_GPS_GPSProcessingMethod = 0x001B; //
	
	const TAG_GPS_GPSAreaInformation = 0x001C; //
	
	const TAG_GPS_GPSDateStamp = 0x001D; //
	
	const TAG_GPS_GPSDifferential = 0x001E; //
	
	const TAG_AUDIO_IMA_ADPCM_DESC = 0x0028; //  IMA-ADPCM Audio File Description Example - 40
	
	const TAG_AUDIO_MU_LAW_DESC = 0x0032; //  µ-Law Audio File Description Sample - 50
	
	const TAG_AUDIO_MU_LAW = 0x0086; // (This File µ-LAW Sample) - 134
	
	const TAG_EXPOSURE_INDEX = 0xA215; // Exposure index
	
	const TAG_SENSING_METHOD = 0xA217; // SensingMethod
	
	const TAG_SOUCE_TYPE = 0xA300; // FileSource
	
	const TAG_SCENE_TYPE = 0xA301; // SceneType
	
	const TAG_CFA_PATTERN = 0xA302; // CFA Pattern
	
	/** Tags in EXIF 2.2 Only */
	const TAG_COMPRESS_SCHEME = 0x0103; //
	
	const TAG_CUSTOM_RENDERED = 0xA401; //  CustomRendered
	
	const TAG_EXPOSURE_MODE = 0xA402; // Exposure mode      ExposureMode
	
	const TAG_WHITE_BALANCE = 0xA403; // White balance      WhiteBalance
	
	const TAG_DIGITAL_ZOOM_RATIO = 0xA404; // Digital zoom ratio      DigitalZoomRatio
	
	const TAG_FLENGTH_IN35MM = 0xA405; // Focal length in 35 mm film      FocalLengthIn35mmFilm
	
	const TAG_SCREEN_CAP_TYPE = 0xA406; // Scene capture type      SceneCaptureType
	
	const TAG_GAIN_CONTROL = 0xA407; //Gain control
	
	const TAG_CONTRAST = 0xA408; // Contrast
	
	const TAG_SATURATION = 0xA409; // Saturation
	
	const TAG_SHARPNESS = 0xA40A; // Sharpness
	
	const TAG_DEVICE_SETTING_DESC = 0xA40B; // SDevice settings description      DeviceSettingDescription
	
	const TAG_DIST_RANGE = 0xA40C; //Subject distance range SubjectDistanceRange
	
	const TAG_FOCALPLANE_YRESOL = 0xA20F; //FocalPlaneYResolution
	
	const TAG_BRIGHTNESS = 0x9203; //Brightness
	
	/** Array containg all Exif and JPEG image attributes
    * into regular expressions for themselves.
    * $ImageInfo[TAG] = TAG_VALUE;
    *
    * @var       array
    * @access    private
    *
    */
    protected $_imageInfo = array();

    protected $_motorolaOrder = 0;
    protected $_exifImageWidth = 0; //
    protected $_focalplaneXRes = 0; //
    protected $_focalplaneUnits = 0; //
//    protected $_sections = array();
    protected $_currSection = 0;  /** Stores total number for Sections */

    protected $_bytesPerFormat = array(0,1,1,2,4,8,1,1,2,4,8,4,8);

    protected $_readMode = array(
	'READ_EXIF' => 1,
	'READ_IMAGE' => 2,
	'READ_ALL' => 3
	);

    protected $_imageReadMode = 3; /** related to $RealMode arrays values */
    protected $_file ;     /** JPEG file to parse for EXIF data */
    protected $_newFile = 1;   /** flag to check if the current file has been parsed or not. */

    protected $_thumbnail = ''; /* Name of thumbnail */
    protected $_thumbnailURL = ''; /* */

    protected $_exifSection = -1;   // market the exif section index oout of all sections

    protected $_errno = 0;
    protected $_errstr = '';
	protected $_timeStart;
    protected $_debugIt = false;

    // Caching ralated protectediables
    protected $_cacheDir = ""; /* Checkout constructor for default path. */

    /** Constructor
     * @param string File name to be parsed.
     *
     */
	public function __construct($file, $debug = 1) {
	$this->_timeStart = $this->_getmicrotime();
	if(!empty($file)) {
	$this->_file = $file;
	} else {
		throw new Pas_Exif_Exception('No file has been passed to the reader');
	}
    $this->_exifImageLength       = 0;
	$this->_imageInfo['h']["resolutionUnit"] = 0;
	$this->_imageInfo[self::TAG_MAXAPERTURE] = 0;
	$this->_imageInfo[self::TAG_ISO_EQUIVALENT] = 0;
	$this->_imageInfo[self::TAG_ORIENTATION] = 0;
	$this->_thumbnailSize = 0;
	if(isset($this->_caching)) {
	$this->_cacheDir = dirname(__FILE__) . "/.cache_thumbs";

	/**
	* If Cache directory does not exists then attempt to create it.
	*/
	if(!is_dir($this->_cacheDir)) {
	mkdir($this->_cacheDir, 777);
	}

	// Prepare the name of thumbnail
    if(is_dir($this->_cacheDir)) {
    $this->_thumbnail = $this->_cacheDir . "/" . basename($this->_file);
	$this->_thumbnailURL = ".cache_thumbs/" . basename($this->_file);
	}
	}

	/** check if file exists! */
	if(!file_exists($this->_file)) {
	throw new Pas_Exif_Exception('File ' . $this->_file . ' does not exist!');
  	}
	$this->_currSection = 0;
	$this->processFile();
    }

/**
     * Show Debugging information
     *
     * @param   string     Debugging message to display
     * @param   int   Type of error (0 - Warning, 1 - Error)
     * @return    void
     *
     */
    function debug($str,$TYPE = 0,$file="",$line=0) {
       if($this->_debugIt) {
        echo "<br>[$file:$line:".($this->getDiffTime())."]$str";
        flush();
        if($TYPE == 1) {
           exit;
        }
       }
    }
    /**
     * Processes the whole file.
     *
     */
    public function processFile() {
	/** dont reparse the whole file. */
	if(!$this->_newFile) {
	return true;	
	}
	if(!file_exists($this->_file)) {
        throw new Pas_Exif_Exception('The file with the name '. $this->file . 'does not exist!');
	}
	$this->debug("Stating Processing of " . $this->_newFile, 0, __FILE__, __LINE__);
    $i = 0;
    $exitAll = 0;
	/** Open the JPEG in binary safe reading mode */
	$fp = fopen($this->_file,"rb");
	$this->_imageInfo["h"]["FileName"] = $this->_file;
	$this->_imageInfo["h"]["FileSize"] = filesize($this->_file); /** Size of the File */
	$this->_imageInfo["h"]["FileDateTime"] = filectime($this->_file); /** File node change time */
	/** check whether jpeg image or not */
    $a = fgetc($fp);
    if (ord($a) != 0xff || ord(fgetc($fp)) != self::M_SOI){
    throw new Pas_Exif_Exception('This file is not a jpeg. It will not produce exif data.');
	}
        $tmpTestLevel = 0;
        /** Examines each byte one-by-one */
        while(!feof($fp)) {
            $data = array();
                for ($a=0;$a<7;$a++){
                        $marker = fgetc($fp);
                        if (ord($marker) != 0xff) break;
                        if ($a >= 6){
                               throw new Pas_Exif_Exception('Too many padding bytes');
                        }
                }

                if (ord($marker) == 0xff){
                     throw new Pas_Exif_Exception('Too many padding bytes');
                }

        $marker = ord($marker);
        $this->_sections[$this->_currSection]["type"] = $marker;

        // Read the length of the section.
        $lh = ord(fgetc($fp));
        $ll = ord(fgetc($fp));

        $itemlen = ($lh << 8) | $ll;

        if ($itemlen < 2){
                 throw new Pas_Exif_Exception('Invalid marker');
        }
        $this->_sections[$this->_currSection]["size"] = $itemlen;

        $tmpDataArr = array();  /** Temporary Array */

        $tmpStr = fread($fp,$itemlen-2);
        $data = chr($lh).chr($ll).$tmpStr;

        //if(count($data) != $itemlen) {
        if(strlen($data) != $itemlen) {
            throw new Pas_Exif_Exception('Premature end of file?');
        }

        $this->currSection++; /** */

        switch($marker) {
                case self::M_SOS:
                    $this->debug("<br>Found '". self::M_SOS . "' Section, Prcessing it... <br>");;
                        // If reading entire image is requested, read the rest of the data.
                        if ($this->_imageReadMode & $this->_readMode["READ_IMAGE"]){
                        // Determine how much file is left.
                                $cp = ftell($fp);
                                fseek($fp,0, SEEK_END);
                                $ep = ftell($fp);
                                fseek($fp, $cp, SEEK_SET);

                        $size = $ep-$cp;
                        $got = fread($fp, $size);

                        $this->_sections[$this->currSection]["data"] = $got;
                        $this->_sections[$this->currSection]["size"] = $size;
                        $this->_sections[$this->currSection]["type"] = self::PSEUDO_IMAGE_MARKER;
                        $this->currSection++;
                        $HaveAll = 1;
                        $exitAll = 1;
                        }
                        $this->debug("<br>'" . self::M_SOS . "' Section, PROCESSED<br>");
                    break;
                case self::M_COM: // Comment section
                        $this->debug("<br>Found '" . self::M_COM . "'(Comment) Section, Processing<br>");
                        $this->_processCOM($data, $itemlen);
                        $this->debug("<br>'" . self::M_COM . "'(Comment) Section, PROCESSED<br>");

                        $tmpTestLevel++;
                    break;
                case self::M_SOI:
                        $this->debug(" <br> === START OF IMAGE =====<br>");
                break;
                case self::M_EOI:
                        $this->debug(" <br>=== END OF IMAGE =====<br> ");
                break;
                case self::M_JFIF:
                        // Regular jpegs always have this tag, exif images have the exif
                        // marker instead, althogh ACDsee will write images with both markers.
                        // this program will re-create this marker on absence of exif marker.
                        // hence no need to keep the copy from the file.
                        //echo " <br> === M_JFIF =====<br>";
                        $this->_sections[--$this->currSection]["data"] = "";
                        break;
                case self::M_EXIF:
                        // Seen files from some 'U-lead' software with Vivitar scanner
                        // that uses marker 31 for non exif stuff.  Thus make sure
                        // it says 'Exif' in the section before treating it as exif.
                        $this->debug("<br>Found '" . self::M_EXIF . "'(Exif) Section, Proccessing<br>");
                        $this->exifSection = $this->currSection-1;
                        if (($this->_imageReadMode & $this->_readMode["READ_EXIF"]) && ($data[2] . $data[3] 
                        . $data[4] . $data[5]) == "Exif"){
                                $this->_processEXIF($data, $itemlen);
                        }else{
                                // Discard this section.
                                $this->_sections[--$this->currSection]["data"] = "";
                        }
                        $this->debug("<br>'".self::M_EXIF."'(Exif) Section, PROCESSED<br>");
                        $tmpTestLevel++;
                break;
                case self::M_SOF0:
                case self::M_SOF1:
                case self::M_SOF2:
                case self::M_SOF3:
                case self::M_SOF5:
                case self::M_SOF6:
                case self::M_SOF7:
                case self::M_SOF9:
                case self::M_SOF10:
                case self::M_SOF11:
                case self::M_SOF13:
                case self::M_SOF14:
                case self::M_SOF15:
                        $this->debug("<br>Found M_SOFn Section, Processing<br>");
                        $this->_processSOFn($data,$marker);
                        $this->debug("<br>M_SOFn Section, PROCESSED<br>");
                break;
				case self::M_EXIF_EXT: // 226 - Exif Extended Data
				    $this->debug("<br><b>Found 'Exif Extended Data' Section, Processing</b><br>-------------------------------<br>");
                    $this->_processEXTEXIF($data, $itemlen);
					$this->debug("<br>--------------------------PROCESSED<br>");
					break;

				case self::M_QUANTA: // 219 - Quantisation Table Definition
				    $this->debug("<br><b>Found 'Quantisation Table Definition' Section, Processing</b><br>-------------------------------<br>");
					$this->debug("<br>--------------------------PROCESSED<br>");
					break;

				case self::M_HUFF: // Huffman Table
				    $this->debug("<br><b>Found 'Huffman Table' Section, Processing</b><br>-------------------------------<br>");
					$this->debug("<br>--------------------------PROCESSED<br>");
					break;

                default:
                        $this->debug("DEFAULT: Jpeg section marker 0x$marker x size $itemlen\n");
        }
        $i++;
        if($exitAll == 1)  break;
            //if($tmpTestLevel == 2)  break;
        }
        fclose($fp);
        $this->_newFile = 0;
    }

    /**
     * Changing / Assiging new file
     * @param   string    JPEG file to process
     *
     */
    public function _assign($file) {

      if(!empty($file)) {
        $this->_file = $file;
      }

      /** check for existance of file! */
      if(!file_exists($this->_file)) {
         throw new Pas_Exif_Exception("File '" . $this->_file . "' does not exist!");
      }
      $this->newFile = 1;
    }

    /**
     * Process SOFn section of Image
     * @param  array    An array containing whole section.
     * @param   hex  Marker to specify the type of section.
     *
     */
    public function _processSOFn($data,$marker) {
        $data_precision = 0;
        $num_components = 0;

        $data_precision = ord($data[2]);

        if($this->_debugIt) {
          print("Image Dimension Calculation:");
          print("((ord($data[3]) << 8) | ord($data[4]));");
        }
        $this->_imageInfo["h"]["Height"] = ((ord($data[3]) << 8) | ord($data[4]));
        $this->_imageInfo["h"]["Width"] = ((ord($data[5]) << 8) | ord($data[6]));

        $num_components = ord($data[7]);

        if ($num_components == 3){
            $this->_imageInfo["h"]["IsColor"] = 1;
        }else{
            $this->_imageInfo["h"]["IsColor"] = 0;
        }

        $this->_imageInfo["h"]["Process"] = $marker;
        $this->debug("JPEG image is " . $this->_imageInfo["h"]["Width"]." * " .$this->_imageInfo["h"]["Height"] 
        . ", $num_components color components, $data_precision bits per sample\n");
    }

    /**
     * Process Comments
     * @param   array    Section data
     * @param   int  Length of the section
     *
     */
    public function _processCOM($data, $length) {
        if ($length > self::MAX_COMMENT) $length = self::MAX_COMMENT;
            /** Truncate if it won't fit in our structure. */

        $nch = 0; 
        
        $Comment = "";
        
        for ($a=2; $a<$length; $a++){
            $ch = $data[$a];
            if ($ch == '\r' && $data[$a+1] == '\n') continue; // Remove cr followed by lf.

            $Comment .= $ch;
        }
        //$this->_imageInfo[M_COM] = $Comment;
        $this->_imageInfo["h"]["imageComment"] = $this->_stringFormat($Comment);
        $this->debug("COM marker comment: $Comment\n");
    }
    /**
     * Process one of the nested EXIF directories.
     * @param   string        All directory information
     * @param   string     whole Section
     * @param   int  Length of exif section
     *
    */
    public function _processExifDir($DirStart, $OffsetBase, $ExifLength) {
        $NumDirEntries = 0;
        $ValuePtr = array();

        $NumDirEntries = $this->_get16u($DirStart[0],$DirStart[1]);

        $this->debug("<br>Directory with $NumDirEntries entries\n");

        for ($de=0;$de < $NumDirEntries;$de++){
        	 ini_set('memory_limit', '256M');
            //$DirEntry = array_slice($DirStart,2+12*$de);
            $DirEntry = substr($DirStart,2+12*$de);

            $Tag = $this->_get16u($DirEntry[0],$DirEntry[1]); 
            $Format = $this->_get16u($DirEntry[2],$DirEntry[3]);
            $Components = $this->_get32u($DirEntry[4],$DirEntry[5],$DirEntry[6],$DirEntry[7]);

            $ByteCount = $Components * $this->_bytesPerFormat[$Format];

            if ($ByteCount > 4){
                $OffsetVal = $this->_get32u($DirEntry[8],$DirEntry[9],$DirEntry[10],$DirEntry[11]);
                if ($OffsetVal+$ByteCount > $ExifLength){
                    $this->debug("Illegal value pointer($OffsetVal) for tag $Tag",1);
                }
                //$ValuePtr = array_slice($OffsetBase,$OffsetVal);
                $ValuePtr = substr($OffsetBase,$OffsetVal);
            } else {
                //$ValuePtr = array_slice($DirEntry,8);
                $ValuePtr = substr($DirEntry,8);
            }

            switch($Tag){

                case self::TAG_MAKE:
                    $this->_imageInfo["h"]["make"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                    break;

                case self::TAG_MODEL:
                    $this->_imageInfo["h"]["model"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                    break;

                case self::TAG_DATETIME_ORIGINAL:
                    $this->_imageInfo[self::TAG_DATETIME_ORIGINAL] =  $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                    $this->_imageInfo["h"]["DateTime"]  = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                    break;

                case self::TAG_USERCOMMENT:
                    // Olympus has this padded with trailing spaces.  Remove these first.
                    for ($a=$ByteCount;;){
                        $a--;
                        if ($ValuePtr[$a] == ' '){
                            //$ValuePtr[$a] = '\0';
                        } else {
                            break;
                        }
                        if ($a == 0) break;
                    }

                    // Copy the comment
                    if (($ValuePtr[0].$ValuePtr[1].$ValuePtr[2].$ValuePtr[3].$ValuePtr[4]) == "ASCII"){
                        for ($a=5;$a<10;$a++){
                            $c = $ValuePtr[$a];
                            if ($c != '\0' && $c != ' '){
                                $tmp = substr($ValuePtr,0,$ByteCount);
                                    break;
                            }
                        }
                    } else if (($ValuePtr[0].$ValuePtr[1].$ValuePtr[2].$ValuePtr[3].$ValuePtr[4].$ValuePtr[5].$ValuePtr[6]) == "Unicode"){
                        $tmp = substr($ValuePtr,0,$ByteCount);
                        //  * Handle Unicode characters here...
                    } else {
                        //$this->_imageInfo[TAG_USERCOMMENT] = implode("",array_slice($ValuePtr,0,$ByteCount));
                        $tmp = substr($ValuePtr,0,$ByteCount);
                    }
                    $this->_imageInfo['h']["exifComment"] = $this->_stringFormat($tmp);
                    break;

				case self::TAG_ARTIST:
                    $this->_imageInfo['h']["artist"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;

				case self::TAG_COPYRIGHT:
                    $this->_imageInfo['h']["copyright"] = htmlentities(substr($ValuePtr,0,$ByteCount));
					break;

                case self::TAG_FNUMBER:
                    // Simplest way of expressing aperture, so I trust it the most.
                    // (overwrite previously computd value if there is one)
                    $tmp = $this->_convertAnyFormat(substr($ValuePtr,0), $Format);
                    $this->_imageInfo['h']["fnumber"] = sprintf("f/%3.1f",(double)$tmp[0]);
                    break;

                case self::TAG_APERTURE:
                case self::TAG_MAXAPERTURE:
                    // More relevant info always comes earlier, so only use this field if we don't
                    // have appropriate aperture information yet.
                    if (!isset($this->_imageInfo['h']["aperture"])){
                        $tmpArr =  $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["aperture"] = exp($tmpArr[0]*log(2)*0.5);
                    }
                    break;

                case self::TAG_FOCALLENGTH:
                    // Nice digital cameras actually save the focal length as a function
                    // of how farthey are zoomed in.
                    $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                    $this->_imageInfo['h']["focalLength"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);
                    if (isset($this->_imageInfo['h']["CCDWidth"])){
                        $this->_imageInfo['h']["focalLength"] .= sprintf("(35mm equivalent: %dmm)",(int)($tmp[0]/$this->_imageInfo['h']["CCDWidth"]*36 + 0.5));
                    }
                    break;

                case self::TAG_SUBJECT_DISTANCE:
                    // Inidcates the distacne the autofocus camera is focused to.
                    // Tends to be less accurate as distance increases.
                    //$this->_imageInfo["h"]["Distance"] =  $this->ConvertAnyFormat($ValuePtr, $Format);
                    $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                    $this->_imageInfo['h']["Distance"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);
                    if ($this->_imageInfo['h']["Distance"] < 0){
                            $this->_imageInfo['h']["focusDistance"] = "Infinite";
                    } else {
                            $this->_imageInfo['h']["focusDistance"] = sprintf("%4.2fm",(double)$this->_imageInfo['h']["Distance"]);
                    }


                    break;

                case self::TAG_EXPOSURETIME:
                    // Simplest way of expressing exposure time, so I trust it most.
                    // (overwrite previously computd value if there is one)
                    $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                    $this->_imageInfo['h']["exposureTime"] = sprintf("%6.3f s (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);
                    if ($tmp[0] <= 0.5){
                            $this->_imageInfo['h']["exposureTime"] .= sprintf(" (1/%d)",(int)(0.5 + 1/$tmp[0]));
                    }
                    break;

                case self::TAG_SHUTTERSPEED:
                    // More complicated way of expressing exposure time, so only use
                    // this value if we don't already have it from somewhere else.
                    if ($this->_imageInfo[self::TAG_EXPOSURETIME] == 0){
                        $sp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo[self::TAG_SHUTTERSPEED] = (1/exp($sp[0]*log(2)));
                    }
                    break;

                case self::TAG_FLASH:
                    $this->_imageInfo["h"]["flashUsed"] = "No";
                    if ($this->_convertAnyFormat($ValuePtr, $Format) & 7){
                        $this->_imageInfo["h"]["flashUsed"] = "Yes";
                    }
                    break;

                case self::TAG_ORIENTATION:
                    $this->_imageInfo[self::TAG_ORIENTATION] = $this->_convertAnyFormat($ValuePtr, $Format);
                    if ($this->_imageInfo[self::TAG_ORIENTATION] < 1 || $this->_imageInfo[self::TAG_ORIENTATION] > 8){
                            $this->debug(sprintf("Undefined rotation value %d", $this->_imageInfo[self::TAG_ORIENTATION], 0),1);
                        $this->_imageInfo[self::TAG_ORIENTATION] = 0;
                    }
                    break;

                case self::TAG_EXIF_IMAGELENGTH:
                    //       * Image height
                    $a = (int) $this->_convertAnyFormat($ValuePtr, $Format);
                    if ($this->_exifImageLength < $a) $this->_exifImageLength = $a;
                    $this->_imageInfo[self::TAG_EXIF_IMAGELENGTH] = $this->_exifImageLength;
                    $this->_imageInfo["h"]["Height"] = $this->_exifImageLength;
                    break;
                case self::TAG_EXIF_IMAGEWIDTH:
                    // Use largest of height and width to deal with images that have been
                    // rotated to portrait format.
                    $a = (int) $this->_convertAnyFormat($ValuePtr, $Format);
                    if ($this->_exifImageWidth < $a) $this->_exifImageWidth = $a;
                    $this->_imageInfo[self::TAG_EXIF_IMAGEWIDTH] = $this->_exifImageWidth;
                    $this->_imageInfo["h"]["Width"] = $this->_exifImageWidth;

                    break;

                case self::TAG_FOCALPLANEXRES:
                    $this->_focalplaneXRes = $this->_convertAnyFormat($ValuePtr, $Format);
                    $this->_focalplaneXRes = $this->_focalplaneXRes[0];
                    $this->_imageInfo[self::TAG_FOCALPLANEXRES] = $this->_focalplaneXRes[0];
                    break;

                case self::TAG_FOCALPLANEUNITS:
                    switch($this->_convertAnyFormat($ValuePtr, $Format)){
                        case 1: $this->_focalplaneUnits = 25.4; break; // inch
                        case 2:
                            // According to the information I was using, 2 means meters.
                            // But looking at the Cannon powershot's files, inches is the only
                            // sensible value.
                            $this->_focalplaneUnits = 25.4;
                            break;

                        case 3: $this->_focalplaneUnits = 10;   break;  // centimeter
                        case 4: $this->_focalplaneUnits = 1;    break;  // milimeter
                        case 5: $this->_focalplaneUnits = .001; break;  // micrometer
                    }
                    $this->_imageInfo[self::TAG_FOCALPLANEUNITS] = $this->_focalplaneUnits;
                    break;

                    // Remaining cases contributed by: Volker C. Schoech (schoech@gmx.de)

                case self::TAG_EXPOSURE_BIAS:
                    $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                    $this->_imageInfo['h']["exposureBias"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);
                    break;

                case self::TAG_WHITEBALANCE:
                    $tmp = (int) $this->_convertAnyFormat($ValuePtr, $Format);
                    $tmpArr = array("1"=>"Sunny","2"=>"fluorescent","3"=>"incandescent");
                    $this->_imageInfo['h']["whiteBalance"] =
                        (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Cloudy");
                    break;

                case self::TAG_METERING_MODE:
                    $tmp = (int) $this->_convertAnyFormat($ValuePtr, $Format);

                    $tmpArr = array("2"=>"center weight","3"=>"spot","5"=>"matrix");
                    $this->_imageInfo['h']["meteringMode"] =
                        (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                    break;

                case self::TAG_EXPOSURE_PROGRAM:
                    $tmp = (int) $this->_convertAnyFormat($ValuePtr, $Format);
                    $tmpArr = array("2"=>"program (auto)","3"=>"aperture priority (semi-auto)","4"=>"shutter priority (semi-auto)");
                    $this->_imageInfo['h']["exposure"] =
                        (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");

                    break;

                case self::TAG_ISO_EQUIVALENT:
                    $tmp = (int) $this->_convertAnyFormat($ValuePtr, $Format);
                    if ( $tmp < 50 ) $tmp *= 200;
                    $this->_imageInfo['h']["isoEquiv"] = sprintf("%2d",(int)$tmp);
                    break;

                case self::TAG_COMPRESSION_LEVEL:
                    $tmp = (int) $this->_convertAnyFormat($ValuePtr, $Format);
                    $tmpArr = array("1"=>"Basic","2"=>"Normal","4"=>"Fine");
                    $this->_imageInfo['h']["jpegQuality"] =
                        (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                    break;

                case self::TAG_THUMBNAIL_OFFSET:
                    $this->ThumbnailOffset = $this->_convertAnyFormat($ValuePtr, $Format);
                    $this->DirWithThumbnailPtrs = $DirStart;
                    break;

                case self::TAG_THUMBNAIL_LENGTH:
                    $this->ThumbnailSize = $this->_convertAnyFormat($ValuePtr, $Format);
                    $this->_imageInfo[self::TAG_THUMBNAIL_LENGTH] = $this->ThumbnailSize;
                    break;

                //----------------------------------------------
                case self::TAG_IMAGE_DESC:
                        $this->_imageInfo['h']["imageDesc"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_X_RESOLUTION:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["xResolution"] = sprintf("%4.2f (%d/%d) %s",(double)$tmp[0],$tmp[1][0],$tmp[1][1],$this->_imageInfo['h']["resolutionUnit"]);
                        break;
                case self::TAG_Y_RESOLUTION:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["yResolution"] = sprintf("%4.2f (%d/%d) %s",(double)$tmp[0],$tmp[1][0],$tmp[1][1],$this->_imageInfo['h']["resolutionUnit"]);
                        break;
                case self::TAG_RESOLUTION_UNIT:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("2"=>"Inches","3"=>"Centimeters");

                        $this->_imageInfo['h']["resolutionUnit"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_SOFTWARE:
                        $this->_imageInfo['h']["software"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_FILE_MODDATE;
                        $this->_imageInfo['h']["fileModifiedDate"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_YCBCR_POSITIONING:
                        $this->_imageInfo['h']["YCbCrPositioning"] = $this->_convertAnyFormat($ValuePtr, $Format);
                        break;
                case self::TAG_EXIF_VERSION:
                        $this->_imageInfo['h']["exifVersion"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_DATE_TIME_DIGITIZED:
                        $this->_imageInfo['h']["dateTimeDigitized"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_COMPONENT_CONFIG: // need more tests for this
                        $tmp = (int)$this->_convertAnyFormat($ValuePtr, $Format);

                        $tmpArr = array("0"=>"Does Not Exists","1"=>"Y","2"=>"Cb","3"=>"Cr","4"=>"R","5"=>"G","6"=>"B");

                        if(strlen($tmp) < 4 ) {
                            $this->_imageInfo['h']["componentConfig"] = $tmpArr["0"];
                        } else {
                            for($i=0;$i<strlen($tmp);$i++) {
                                if($tmp["$i"] != 0) {
                                    $this->_imageInfo['h']["componentConfig"] .= $tmpArr[$tmp["$i"]];
                                }
                            }
                        }
                        break;
                case self::TAG_MAKER_NOTE:
                        //$this->_imageInfo['h']["makerNote"] = substr($ValuePtr,0,$ByteCount);
                        $this->_imageInfo['h']["makerNote"] = "NOT IMPLEMENTED";
                        break;
                case self::TAG_SUB_SEC_TIME:
                        $this->_imageInfo['h']["subSectionTime"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_SUB_SEC_TIME_ORIG:
                        $this->_imageInfo['h']["subSectionTimeOriginal"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_SUB_SEC_TIME_DIGITIZED:
                        $this->_imageInfo['h']["subSectionTimeDigtized"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_FLASHPIX_VER:
                        $this->_imageInfo['h']["flashpixVersion"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_COLOR_SPACE:
                        $this->_imageInfo['h']["colorSpace"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_RELATED_SOUND_FILE:
                        $this->_imageInfo['h']["relatedSoundFile"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_GPS_LATITUDE_REF:
                        $this->_imageInfo['h']["GPSLatitudeRef"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
						$this->_imageInfo['h']["GPSLatitudeRef"] = trim($this->_imageInfo['h']["GPSLatitudeRef"]);
						$tmp = substr($this->_imageInfo['h']["GPSLatitudeRef"],0,1);
						if($tmp == "S") {
							$this->_imageInfo['h']["GPSLatitudeRef"] = "South latitude";
						} else if($tmp == "N") {
							$this->_imageInfo['h']["GPSLatitudeRef"] = "North latitude";
						} else {
							$this->_imageInfo['h']["GPSLatitudeRef"] = "Reserved";
						}
                        break;
                case self::TAG_GPS_LATITUDE:
						$tmp = substr($ValuePtr,0,$ByteCount);

						$this->_imageInfo['h']["GPSLatitude"]["Degrees"] = ord(substr($tmp,0,1));
						$this->_imageInfo['h']["GPSLatitude"]["Minutes"] = ord(substr($tmp,1,1));
						$this->_imageInfo['h']["GPSLatitude"]["Seconds"] = ord(substr($tmp,2,1));
                        break;

                case self::TAG_GPS_LONGITUDE:
						$tmp = substr($ValuePtr,0,$ByteCount);

						$this->_imageInfo['h']["GPSLongitude"]["Degrees"] = ord(substr($tmp,0,1));
						$this->_imageInfo['h']["GPSLongitude"]["Minutes"] = ord(substr($tmp,1,1));
						$this->_imageInfo['h']["GPSLongitude"]["Seconds"] = ord(substr($tmp,2,1));

                        break;

                case self::TAG_GPS_LONGITUDE_REF:
                        $this->_imageInfo['h']["GPSLongitudeRef"] = substr($ValuePtr,0,$ByteCount);
						$this->_imageInfo['h']["GPSLongitudeRef"] = trim($this->_imageInfo['h']["GPSLongitudeRef"]);
						$tmp = substr($this->_imageInfo['h']["GPSLongitudeRef"],0,1);
						if($tmp == "E") {
							$this->_imageInfo['h']["GPSLongitudeRef"] = "East Longitude";
						} else if($tmp == "W") {
							$this->_imageInfo['h']["GPSLongitudeRef"] = "West Longitude";
						} else {
							$this->_imageInfo['h']["GPSLongitudeRef"] = "Reserved";
						}

                        break;


				case self::TAG_GPS_TrackRef: /* Reference for direction of movement    GPSTrackRef */
					$this->_imageInfo['h']["GPSTrackRef"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;


				case self::TAG_GPS_GPSTrack: /* Direction of movement					GPSTrack */
					$this->_imageInfo['h']["GPSTrack"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSImgDirectionRef: /* Reference for direction of image       GPSImgDirectionRef */
					$this->_imageInfo['h']["GPSImgDirectionRef"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSImgDirection: /* Direction of image                     GPSImgDirection     */
					$this->_imageInfo['h']["GPSImgDirection"] = $this->_convertAnyFormat($ValuePtr, $Format);
					break;
				case self::TAG_GPS_GPSMapDatum: /* Geodetic survey data used              GPSMapDatum         */
					$this->_imageInfo['h']["GPSMapDatum"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSDestLatitudeRef:/* Reference for latitude of destination  GPSDestLatitudeRef  */
					$this->_imageInfo['h']["GPSDestLatitudeRef"] = substr($ValuePtr,0,$ByteCount);
					$this->_imageInfo['h']["GPSDestLatitudeRef"] = trim($this->_imageInfo['h']["GPSDestLatitudeRef"]);
					$tmp = substr($this->_imageInfo['h']["GPSDestLatitudeRef"],0,1);
					if($tmp == "S") {
						$this->_imageInfo['h']["GPSDestLatitudeRef"] = "South latitude";
					} else if($tmp == "N") {
						$this->_imageInfo['h']["GPSDestLatitudeRef"] = "North latitude";
					} else {
						$this->_imageInfo['h']["GPSDestLatitudeRef"] = "Reserved";
					}

					break;
				case self::TAG_GPS_GPSDestLatitude:/* Latitude of destination                GPSDestLatitude     */
					$tmp = substr($ValuePtr,0,$ByteCount);

						$this->_imageInfo['h']["GPSDestLatitude"]["Degrees"] = ord(substr($tmp,0,1));
						$this->_imageInfo['h']["GPSDestLatitude"]["Minutes"] = ord(substr($tmp,1,1));
						$this->_imageInfo['h']["GPSDestLatitude"]["Seconds"] = ord(substr($tmp,2,1));

					break;
				case self::TAG_GPS_GPSDestLongitudeRef:/* Reference for longitude of destination GPSDestLongitudeRef 21     */
					$this->_imageInfo['h']["GPSDestLongitudeRef"] = substr($ValuePtr,0,$ByteCount);
					$this->_imageInfo['h']["GPSDestLongitudeRef"] = trim($this->_imageInfo['h']["GPSDestLongitudeRef"]);
					$tmp = substr($this->_imageInfo['h']["GPSDestLongitudeRef"],0,1);
					if($tmp == "E") {
						$this->_imageInfo['h']["GPSDestLongitudeRef"] = "East Longitude";
					} else if($tmp == "W") {
						$this->_imageInfo['h']["GPSDestLongitudeRef"] = "West Longitude";
					} else {
						$this->_imageInfo['h']["GPSDestLongitudeRef"] = "Reserved";
					}

					break;
				case self::TAG_GPS_GPSDestLongitude:/* Longitude of destination               GPSDestLongitude    22     */
					$this->_imageInfo['h']["GPSDestLongitude"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSDestBearingRef:/* Reference for bearing of destination   GPSDestBearingRef   23     */
					$this->_imageInfo['h']["GPSDestBearingRef"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSDestBearing: /* Bearing of destination                 GPSDestBearing      24     */
					$this->_imageInfo['h']["GPSDestBearing"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSDestDistanceRef:/* Reference for distance to destination  GPSDestDistanceRef  25     */
					$this->_imageInfo['h']["GPSDestDistanceRef"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSDestDistance: /* Distance to destination                GPSDestDistance     26     */
					//$this->_imageInfo['h']["GPSDestDistance"] = $this->_convertAnyFormat($ValuePtr, $Format);
					$tmp = $this->_convertAnyFormat($ValuePtr, $Format);
					$this->_imageInfo['h']["GPSDestDistance"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);;
					break;
				case self::TAG_GPS_GPSProcessingMethod: /* Name of GPS processing method          GPSProcessingMethod 27     */
					//$this->_imageInfo['h']["GPSProcessingMethod"] = $this->_convertAnyFormat($ValuePtr, $Format);
					$tmp = $this->_convertAnyFormat($ValuePtr, $Format);
					$this->_imageInfo['h']["GPSProcessingMethod"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);;
					break;
				case self::TAG_GPS_GPSAreaInformation: /* Name of GPS area                       GPSAreaInformation  28     */
					$this->_imageInfo['h']["GPSAreaInformation"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSDateStamp: /* GPS date                               GPSDateStamp        29     */
					$this->_imageInfo['h']["GPSDateStamp"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;
				case self::TAG_GPS_GPSDifferential: /* GPS differential correction            GPSDifferential     30     */
					$this->_imageInfo['h']["GPSDifferential"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
					break;

				case self::TAG_AUDIO_MU_LAW:
					$this->_imageInfo['h']["AudioMuLaw"] = $this->_convertAnyFormat($ValuePtr, $Format);
					break;
				case self::TAG_AUDIO_IMA_ADPCM_DESC: //  IMA-ADPCM Audio File Description Example - 40
					$this->_imageInfo['h']["AudioIMA-ADPCM-DESC"] = $this->_convertAnyFormat($ValuePtr, $Format);
					break;
				case self::TAG_AUDIO_MU_LAW_DESC: //  µ-Law Audio File Description Sample - 50
					$this->_imageInfo['h']["AudioMuLawDesc"] = $this->_convertAnyFormat($ValuePtr, $Format);
					break;

				case self::TAG_EXPOSURE_INDEX:
					$tmp = $this->_convertAnyFormat($ValuePtr, $Format);
					$this->_imageInfo['h']["ExposureIndex"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);;
					break;

                case self::TAG_SENSING_METHOD:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("1"=>"Not Defined","2"=>"One-chip color area sensor","3"=>"Two-chip color area sensor",
                                        "4"=>"Three -chip color area sensor","5"=>"Color sequential area sensor",
                                        "6"=>"Trilinear sensor", "7"=>"Color sequential linear sensor"
                                        );

                        $this->_imageInfo['h']["sensing"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_SOUCE_TYPE:
                        $this->_imageInfo['h']["sourceType"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_SCENE_TYPE:
                        $this->_imageInfo['h']["sceneType"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_CFA_PATTERN:
                        $this->_imageInfo['h']["CFAPattern"] = $this->_stringFormat(substr($ValuePtr,0,$ByteCount));
                        break;
                case self::TAG_CUSTOM_RENDERED:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["customRendered"] = ($tmp == 0) ? 'Normal Process' : ($tmp == 1 ? 'Custom Process' : 'Reserved');
                        break;
                case self::TAG_EXPOSURE_MODE:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array('Auto Exposure','Manual Exposure','Auto Bracket');
                        $this->_imageInfo['h']["exposureMode"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_WHITE_BALANCE:
                        $this->_imageInfo['h']["whiteBalance"] = $this->_convertAnyFormat($ValuePtr, $Format);
                        break;
                case self::TAG_DIGITAL_ZOOM_RATIO:
                        $tmp = $this->_imageInfo['h']["zoomRatio"] = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["zoomRatio"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);
                        break;
                case self::TAG_FLENGTH_IN35MM:
                        $this->_imageInfo['h']["flength35mm"] = $this->_convertAnyFormat($ValuePtr, $Format);
                        break;
                case self::TAG_SCREEN_CAP_TYPE:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("Standard","Landscape","Portrait","Night Scene");
                        $this->_imageInfo['h']["screenCaptureType"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_GAIN_CONTROL:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("None","Low Gain Up","High Gain Up","Low Gain Down","High Gain Down");
                        $this->_imageInfo['h']["gainControl"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_CONTRAST:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("Normal","Soft","Hard");
                        $this->_imageInfo['h']["contrast"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_SATURATION:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("Normal","Low Saturation","High Saturation");
                        $this->_imageInfo['h']["saturation"] =
						(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_SHARPNESS:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("Normal","Soft","Hard");
                        $this->_imageInfo['h']["sharpness"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_DIST_RANGE:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("Unknown","Macro","Close View","Distant View");
                        $this->_imageInfo['h']["distanceRange"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_DEVICE_SETTING_DESC:
                        $this->_imageInfo['h']["deviceSettingDesc"] = "NOT IMPLEMENTED";
                        break;
                case self::TAG_COMPRESS_SCHEME:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("1"=>"Uncompressed","6"=>"JPEG compression (thumbnails only)");
                        $this->_imageInfo['h']["compressScheme"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_IMAGE_WD:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegImageWidth"] = $tmp;
                        break;
                case self::TAG_IMAGE_HT:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegImageHeight"] = $tmp;
                        break;
                case self::TAG_IMAGE_BPS:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegBitsPerSample"] = $tmp;
                        break;
                case self::TAG_IMAGE_PHOTO_INT:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegPhotometricInt"] = $tmp;
                        $tmpArr = array("2"=>"RGB","6"=>"YCbCr");
                        $this->_imageInfo['h']["jpegPhotometricInt"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");

                        break;
                case self::TAG_IMAGE_SOFFSET:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegStripOffsets"] = $tmp;
                        break;
                case self::TAG_IMAGE_SPP:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegSamplesPerPixel"] = $tmp;
                        break;
                case self::TAG_IMAGE_RPS:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegRowsPerStrip"] = $tmp;
                        break;
                case self::TAG_IMAGE_SBC:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["jpegStripByteCounts"] = $tmp;
                        break;
                case self::TAG_IMAGE_P_CONFIG:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $tmpArr = array("1"=>"Chunky Format","2"=>"Planar Format");
                        $this->_imageInfo['h']["jpegPlanarConfig"] =
                            (isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
                        break;
                case self::TAG_FOCALPLANE_YRESOL:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["focalPlaneYResolution"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);
                        break;
                case self::TAG_BRIGHTNESS:
                        $tmp = $this->_convertAnyFormat($ValuePtr, $Format);
                        $this->_imageInfo['h']["brightness"] = sprintf("%4.2f (%d/%d)",(double)$tmp[0],$tmp[1][0],$tmp[1][1]);
                        break;
                //---------------------------------------------
                case self::TAG_EXIF_OFFSET:
                case self::TAG_INTEROP_OFFSET:
                    {

                        $SubdirStart = substr($OffsetBase,$this->_get32u($ValuePtr[0],$ValuePtr[1],$ValuePtr[2],$ValuePtr[3]));
                            $this->_processExifDir($SubdirStart, $OffsetBase, $ExifLength);
                        continue;
                    }
                default: {
                    $this->debug("UNKNOWN TAG: $Tag");
                    }
            }
        }

        {
        // In addition to linking to subdirectories via exif tags,
        // there's also a potential link to another directory at the end of each
        // directory.  this has got to be the result of a comitee!
        $tmpDirStart = substr($DirStart,2+12*$NumDirEntries);
        if (strlen($tmpDirStart) + 4 <= strlen($OffsetBase)+$ExifLength){
            $Offset = $this->_get32u($tmpDirStart[0],$tmpDirStart[1],$tmpDirStart[2],$tmpDirStart[3]);
            if ($Offset){
                $SubdirStart = substr($OffsetBase,$Offset);
                if (strlen($SubdirStart) > strlen($OffsetBase)+$ExifLength){
                    if (strlen($SubdirStart) < strlen($OffsetBase)+$ExifLength+20){
                        // Jhead 1.3 or earlier would crop the whole directory!
                        // As Jhead produces this form of format incorrectness,
                        // I'll just let it pass silently
                    } else {
                        $this->errno = 51;
                        $this->errstr = "Illegal subdirectory link";
                        $this->debug($this->errstr,1);
                    }
                }else{
                    if (strlen($SubdirStart) <= strlen($OffsetBase)+$ExifLength){
                        $this->_processExifDir($SubdirStart, $OffsetBase, $ExifLength);
                    }
                }
            }
        } else {
            // The exif header ends before the last next directory pointer.
        }
    }

    /**
    * Check if thumbnail has been cached or not.
    * If yes! then read the file.
    */
    if(file_exists($this->_thumbnail) && $this->caching && (filemtime($this->_thumbnail) == filemtime($this->_file) )) {
        $this->_imageInfo["h"]["Thumbnail"] = $this->_thumbnail;
        $this->_imageInfo["h"]["ThumbnailSize"] =  sprintf("%d bytes",filesize($this->_thumbnail));
    } else{
        if ($this->ThumbnailSize && $this->_thumbnailOffset){
            if ($this->ThumbnailSize + $this->_thumbnailOffset <= $ExifLength){
                // The thumbnail pointer appears to be valid.  Store it.
                $this->_imageInfo["h"]["Thumbnail"] = substr($OffsetBase,$this->_thumbnailOffset);

                // Save the thumbnail /
                if($this->caching && is_dir($this->_cacheDir)) {
                    $this->_saveThumbnail($this->_thumbnail);
                    $this->_imageInfo["h"]["Thumbnail"] = $this->_thumbnail;
                }
                $this->_imageInfo["h"]["ThumbnailSize"] =  sprintf("%d bytes",strlen($this->_imageInfo["h"]["Thumbnail"]));
            }
        }
    }
    }

    /**
     * Process Exif data
     * @param   array    Section data as an array
     * @param   int  Length of the section (length of data array)
     *
     */
	public function _processEXTEXIF($data,$length) {
			//print_r($data);
	}

    /**
     * Process Exif data
     * @param   array    Section data as an array
     * @param   int  Length of the section (length of data array)
     *
     */
    public function _processEXIF($data,$length) {

        $this->debug("Exif header $length bytes long\n");
        if(($data[2].$data[3].$data[4].$data[5]) != "Exif") {
            $this->errno = 52;
            $this->errstr = "NOT EXIF FORMAT";
            $this->debug($this->errstr,1);
        }

        $this->_imageInfo["h"]["FlashUsed"] = 0;
            /** If it s from a digicam, and it used flash, it says so. */

        $this->_focalplaneXRes = 0;
        $this->_focalplaneUnits = 0;
        $this->_exifImageWidth = 0;

        if(($data[8].$data[9]) == "II") {
            $this->debug("Exif section in Intel order\n");
            $this->_motorolaOrder = 0;
        } else if(($data[8].$data[9]) == "MM") {
            $this->debug("Exif section in Motorola order\n");
            $this->_motorolaOrder = 1;
        } else {
            $this->errno = 53;
            $this->errstr = "Invalid Exif alignment marker.\n";
            $this->debug($this->errstr,1);
            return;
        }

        if($this->_get16u($data[10],$data[11]) != 0x2A || $this->_get32s($data[12],$data[13],$data[14],$data[15]) != 0x08) {
            $this->errno = 54;
            $this->errstr = "Invalid Exif start (1)";
            $this->debug($this->errstr,1);
        }

        $DirWithThumbnailPtrs = NULL;

        //$this->ProcessExifDir(array_slice($data,16),array_slice($data,8),$length);
        $this->_processExifDir(substr($data,16),substr($data,8),$length);

        // Compute the CCD width, in milimeters.                      2
        if ($this->_focalplaneXRes != 0){
            $this->_imageInfo["h"]["CCDWidth"] = sprintf("%4.2fmm",(float)($this->_exifImageWidth * $this->_focalplaneUnits / $this->_focalplaneXRes));
        }

        $this->debug("Non settings part of Exif header: ".$length." bytes\n");
    } // end of function process_EXIF

    /** Converts two byte number into its equivalent int integer
     * @param   int $val
     * @param   int $by
     *
     */
    protected function _get16u($val, $by) {
        if($this->_motorolaOrder){
            return ((ord($val) << 8) | ord($by));
        } else {
            return ((ord($by) << 8) | ord($val));
        }
    }

    /**
     * Converts 4-byte number into its equivalent integer
     *
     * @param   int $val1
     * @param   int $val2
     * @param   int $val3
     * @param   int $val4
     * @return int
     */
    protected function _get32s($val1,$val2,$val3,$val4)
    {
        $val1 = ord($val1);
        $val2 = ord($val2);
        $val3 = ord($val3);
        $val4 = ord($val4);

        if ($this->_motorolaOrder){
            return (($val1 << 24) | ($val2 << 16) | ($val3 << 8 ) | ($val4 << 0 ));
        }else{
            return  (($val4 << 24) | ($val3 << 16) | ($val2 << 8 ) | ($val1 << 0 ));
        }
    }
    /**
     * Converts 4-byte number into its equivalent integer with the help of Get32s
     *
     * @param   int
     * @param   int
     * @param   int
     * @param   int
     *
     * @return int
     *
     */
    public function _get32u($val1,$val2,$val3,$val4) {
        return ($this->_get32s($val1,$val2,$val3,$val4) & 0xffffffff);
    }

    //--------------------------------------------------------------------------
    // Evaluate number, be it int, rational, or float from directory.
    //--------------------------------------------------------------------------
   public function _convertAnyFormat($ValuePtr, $Format) {
	$Value = 0;

	switch($Format){
		case self::FMT_SBYTE:     
			$Value = $ValuePtr[0];  
			break;
		case self::FMT_BYTE:      
			$Value = $ValuePtr[0];        
			break;
		case self::FMT_USHORT:    
			$Value = $this->_get16u($ValuePtr[0], $ValuePtr[1]);          
			break;
		case self::FMT_ULONG:     
			$Value = $this->_get32u($ValuePtr[0], $ValuePtr[1], $ValuePtr[2], $ValuePtr[3]);          
			break;
		case self::FMT_URATIONAL:
		case self::FMT_SRATIONAL: {
			$Num = $this->_get32s($ValuePtr[0], $ValuePtr[1], $ValuePtr[2], $ValuePtr[3]);
			$Den = $this->_get32s($ValuePtr[4], $ValuePtr[5], $ValuePtr[6], $ValuePtr[7]);
			if ($Den === 0){
			$Value = 0;
			} else {
			$Value = (double) ($Num / $Den);
			}
			return array($Value,array($Num,$Den));
			break;
			}
		case self::FMT_SSHORT:    
			$Value = $this->_get16u($ValuePtr[0], $ValuePtr[1]);  
			break;
		case self::FMT_SLONG:     
			$Value = $this->_get32s($ValuePtr[0],$ValuePtr[1],$ValuePtr[2],$ValuePtr[3]);                
			break;
		// Not sure if this is correct (never seen float used in Exif format)
		case self::FMT_SINGLE:    
			$Value = $ValuePtr[0];      
			break;
		case self::FMT_DOUBLE:    
			$Value = $ValuePtr[0];             
			break;
        }
        return $Value;
    }

    /**
     * Function to extract thumbnail from Exif data of the image.
     * and store it in a filename given by $ThumbFile
     * @todo rewrite this to work the way I want it to. Thumbnails are generated already by another 
     * script.
     * @param   String   Files name to store the thumbnail
     *
     */
    public function _saveThumbnail($ThumbFile) {
         $ThumbFile = trim($ThumbFile);
         $file = basename($this->_file);

         if(empty($ThumbFile)) $ThumbFile = "th_$file";

         if (!empty($this->_imageInfo["h"]["Thumbnail"])){
            $tp = fopen($ThumbFile,"wb");
            if(!$tp) {
                $this->errno = 2;
                $this->errstr = "Cannot Open file '$ThumbFile'";
            }
            fwrite($tp,$this->_imageInfo["h"]["Thumbnail"]);
            fclose($tp);
            touch($ThumbFile,filemtime($this->_file));
         }
         //$this->thumbnailURL = $ThumbFile;
         $this->_imageInfo["h"]["Thumbnail"] = $ThumbFile;
    }

    /**
     * Returns thumbnail url along with parameter supplied.
     * Should be called in src attribute of image
     *
     * @return  string  File URL
     *
     */
	public function _showThumbnail() {
	return "showThumbnail.php?file=".$this->_file;
	//$this->_imageInfo["h"]["Thumbnail"]
    }

    /**
     * Function to give back thumbail image
     * @return string   full image
     *
     */
    public function getThumbnail() {
	return $this->_imageInfo["h"]["Thumbnail"];
    }

    /**
    *
    */
    public function getImageInfo() {
	$imgInfo = $this->_imageInfo["h"];
	$retArr = $imgInfo;
	$retArr["FileName"] = $imgInfo["FileName"];
	$retArr["FileSize"] = $imgInfo["FileSize"]." bytes";
	$retArr["FileDateTime"] = date("d-M-Y H:i:s",$imgInfo["FileDateTime"]);
	$retArr["resolution"] = $imgInfo["Width"]."x".$imgInfo["Height"];
    if ($this->_imageInfo[self::TAG_ORIENTATION] > 1){
	// Only print orientation if one was supplied, and if its not 1 (normal orientation)
	// 1 - "The 0th row is at the visual top of the image, and the 0th column is the visual left-hand side."
	// 2 - "The 0th row is at the visual top of the image, and the 0th column is the visual right-hand side."
	// 3 - "The 0th row is at the visual bottom of the image, and the 0th column is the visual right-hand side."
	// 4 - "The 0th row is at the visual bottom of the image, and the 0th column is the visual left-hand side."
	// 5 - "The 0th row is the visual left-hand side of of the image, and the 0th column is the visual top."
	// 6 - "The 0th row is the visual right-hand side of of the image, and the 0th column is the visual top."
	// 7 - "The 0th row is the visual right-hand side of of the image, and the 0th column is the visual bottom."
	// 8 - "The 0th row is the visual left-hand side of of the image, and the 0th column is the visual bottom."
	// Note: The descriptions here are the same as the name of the command line
	// option to pass to jpegtran to right the image
	$OrientTab = array(
	'Undefined',
	'Normal',           // 1
	'flip horizontal',  // left right reversed mirror
	'rotate 180',       // 3
	'flip vertical',    // upside down mirror
	'transpose',        // Flipped about top-left <--> bottom-right axis.
	'rotate 90',        // rotate 90 cw to right it.
	'transverse',       // flipped about top-right <--> bottom-left axis
	'rotate 270',       // rotate 270 to right it.
	);
	$retArr["orientation"] = $OrientTab[$this->_imageInfo[self::TAG_ORIENTATION]];
    }

	$retArr["color"] = ($imgInfo["IsColor"] == 0) ? "Black and white" : "Color";
	if(isset($imgInfo["Process"])) {
	switch($imgInfo["Process"]) {
		case self::M_SOF0: 
			$process = "Baseline";
			break;
		case self::M_SOF1: 
			$process = "Extended sequential";
			break;
		case self::M_SOF2: 
			$process = "Progressive";
			break;
		case self::M_SOF3: 
			$process = "Lossless";
			break;
		case self::M_SOF5: 
			$process = "Differential sequential";
			break;
		case self::M_SOF6: 
			$process = "Differential progressive";
			break;
		case self::M_SOF7: 
			$process = "Differential lossless";
			break;
		case self::M_SOF9: 
			$process = "Extended sequential, arithmetic coding";
			break;
		case self::M_SOF10: 
			$process = "Progressive, arithmetic coding";
			break;
		case self::M_SOF11: 
			$process = "Lossless, arithmetic coding";
			break;
		case self::M_SOF13: 
			$process = "Differential sequential, arithmetic coding";
			break;
		case self::M_SOF14: 
			$process = "Differential progressive, arithmetic coding";
			break;
		case M_SOF15: 
			$process =   "Differential lossless, arithmetic coding";
			break;
		default: 
			$process = "Unknown";
		}
	$retArr["jpegProcess"] = $process;
	}

	if(file_exists($this->_thumbnailURL)) {
	$retArr["Thumbnail"] =  $this->_thumbnailURL;
	}
	return $retArr;
    }

	/**
	*
	*/
	public function _stringFormat($str) {
	$tmpStr = "";
		for($i=0;$i < strlen($str); $i++) {
			if(ord($str[$i]) !=0) {
				$tmpStr .= $str[$i];
			}
	}
	return $tmpStr;
	}
    /**
    * Returns time in microseconds
    */
    public function _getmicrotime(){
	list($usec, $sec) = explode(' ',microtime());
	return ((float)$usec + (float)$sec);
    }

    /**
    *  Get the time difference
    */
    public function _getDiffTime() {
	return ($this->_getmicrotime() - $this->_timeStart);
    }

} 