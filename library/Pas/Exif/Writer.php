<?php
/** PHP Class to read, write and transfer EXIF information that most of the digital camera produces
 * Currenty it can only read JPEG file.
 * @author Originally Vinay Yadav (vinayRas) < vinay@vinayras.com > | Adapted for PAS project Daniel Pett
 * @category Pas
 * @package Pas_Exif
 * @version 1.0
 * @license http://opensource.org/licenses/lgpl-license.php GNU LGPL
 * @since September 27 2011
 */
require_once("exifReader.inc");

/**
*
*/
class Pas_Exif_Writer extends Pas_Exif_Reader {

    /** Constructor
    */
    public function phpExifWriter($image) {
         $this->phpExifReader($image);
         $this->processFile();
    }

    /** Modifies or sets value of specified Tag
     * @param   hex   Tag, whose value has to be set
     * @param   string   Tags value
     */
    function setExifData($param,$value) {
        $this->_imageInfo['$param'] = $value;
    }

    /** This function writes back the modifed exif data into the imageinfo array -
     * NOTE: This code is still INCOMPLETE and does not work.
     *
     */
    function modifyExifDetails() {

        $newData[0] = $this->sections[$this->exifSection]["data"][0];
        $newData[1] = $this->sections[$this->exifSection]["data"][1];

        $newData[2] = 'E'; $newData[3] = 'x';
        $newData[4] = 'i'; $newData[5] = 'f';
        $newData[6] = $this->sections[$this->exifSection]["data"][6];
        $newData[7] = $this->sections[$this->exifSection]["data"][7];

        if($this->MotorolaOrder == 1) {
            $newData[8] = 'M';$newData[9] = 'M';
        } else {
            $newData[8] = 'I';$newData[9] = 'I';
        }

        $newData[10] = chr(42 >> 8);
        $newData[11] = chr(42);

        $newData[12] = chr(0);
        $newData[13] = chr(0);
        $newData[14] = chr(0);
        $newData[15] = chr(8);

        $newData[16] = 1;
        $newData[17] = 1;

        $totalLength = 16; $totalElements = 0;
        $offset = 10+(15*12);
        $otherDataArr = array();
        foreach($this->ImageInfo as $tag => $val) {

          if(is_array($val)) continue;

          if(eregi("0x",$tag)) {

            // format
            $fmt = $this->getFormat($tag);
            if($fmt == -1) continue;

            $tmpTag = hexdec($tag);
            // tag
            $newData[] = chr($tmpTag >> 8);
            $newData[] = chr($tmpTag);

            $newData[] = chr($fmt >> 8);
            $newData[] = chr($fmt);

            echo "<br>TAG:$tag - Format:$fmt - Value:$val";
            //components
            $chars = preg_split('//', $val, -1, PREG_SPLIT_NO_EMPTY);
            $ByteCount = count($chars);

            $Components = ceil($ByteCount / $this->BytesPerFormat[$fmt]);

            $newData[] = chr($Components >> 24);
            $newData[] = chr($Components >> 16);
            $newData[] = chr($Components >> 8);
            $newData[] = chr($Components);

            echo "<br>ByteCount: $ByteCount";
            if($ByteCount <= 4) {
                $newData[] = chr($chars[0] >> 8);
                $newData[] = chr($chars[0]);

                $newData[] = (isset($chars[2])) ? chr($chars[2]) : '';
                $newData[] = (isset($chars[3])) ? chr($chars[3]) : '';
            } else {
                $newData[] = chr($offset >> 24);
                $newData[] = chr($offset >> 16);
                $newData[] = chr($offset >> 8);
                $newData[] = chr($offset);


                if($fmt != FMT_STRING) {
                    $arr = $this->ConvertAnyFormatBack($val,$fmt);
                    $chars = $arr;
                    $ByteCount = 8;
                }
                $offset+=$ByteCount;

                $otherDataArr = array_merge($otherDataArr,$chars);
            }
            $totalLength += 12+$ByteCount;
            $totalElements++;
          }
        }
        $newData = array_merge($newData,$otherDataArr);

        /**
         * Write the thumbnail back to the exif section
         * Dont know if this works -
         */
        /**
        if($this->thumbnail) {
            echo "Thumnail Size:".count($this->ImageInfo["ThumbnailPointer"]);

            $tmpTag = hexdec();
            // tag
            $newData[] = chr($tmpTag >> 8);
            $newData[] = chr($tmpTag);

            // format
            $fmt = $this->getFormat($tag);
            $newData[] = chr($fmt >> 8);
            $newData[] = chr($fmt);

            //components
            $chars = preg_split('//', $val, -1, PREG_SPLIT_NO_EMPTY);
            $ByteCount = count($chars);

            $Components = $ByteCount / $this->BytesPerFormat[$fmt];

            $newData[] = chr($Components >> 32);
            $newData[] = chr($Components >> 16);
            $newData[] = chr($Components >> 8);
            $newData[] = chr($Components);


            //$newData = array_merge($newData,$chars);

            $newData = array_merge($newData,$this->ImageInfo["ThumbnailPointer"]);
            $totalLength += count($this->ImageInfo["ThumbnailPointer"]);
        }
         */

        $totalLength += 2;
        $newData[0] = chr($totalLength >> 8);
        $newData[1] = chr($totalLength);

        $newData[16] = chr($totalElements >> 8);
        $newData[17] = chr($totalElements);

        $this->sections[$this->exifSection]["data"] = $newData;
        $this->sections[$this->exifSection]["size"] = $totalLength;

    }

    /**
     * Searched for the tag specified in the sections list
     *
     * @param   hex  Tag to search for
     *
     * @return  int
            -1 - Tag not found
     */
    function findMarker($marker) {
            for($i=0;$i<$this->currSection;$i++) {
                if($this->sections[$i]["type"] == $marker) {
                    return $i;
                }
            }
            return -1;
    }

    /**
     * Adds comment to the image.
     * NOTE: Will have to call writeExif for the comments and
     *       other data to be written back to image.
     * @param   string Commnent as string
     *
     */
    function addComment($comment) {

        /** check if comments already exists! */
        $commentSection = $this->findMarker(M_COM);
        if($commentSection == -1) {
            // make 3rd element as comment section - Push-up all elements
            for($i=$this->currSection;$i>2;$i--) {
                $this->sections[$i]["type"] = $this->sections[$i-1]["type"];
                $this->sections[$i]["data"] = $this->sections[$i-1]["data"];
                $this->sections[$i]["size"] = $this->sections[$i-1]["size"];
            }
            $this->currSection++;
            $commentSection = 2;
        }

        $data[0] = 0;  // dummy data
        $data[1] = 0;  // dummy data

        $chars = preg_split('//', $comment, -1, PREG_SPLIT_NO_EMPTY);
        $data = array_merge($data,$chars);

        $this->sections[$commentSection]["size"] = count($data);

        $data[0] = chr($this->sections[$commentSection]["size"] >> 8);
        $data[1] = chr($this->sections[$commentSection]["size"]);

        $this->sections[$commentSection]["type"] = M_COM;
        $this->sections[$commentSection]["data"] = $data;
    }

    /**
     * Return the format of data of any tag.
     *
     * @param   hex Tag whose format has to looked for
     *
     * @return int Return the format as int
     *
     */
    function getFormat($tag) {

        switch($tag) {
            //$FMT_BYTE_ARRAY


            //$FMT_STRING_ARRAY
            case TAG_MAKE:
            case TAG_MODEL:
            case TAG_SOFTWARE:
            case TAG_ARTIST:
            case TAG_COPYRIGHT:
            case TAG_DATETIME_ORIGINAL:
            case TAG_IMAGE_DESC:
                return FMT_STRING;
                break;

            //$FMT_USHORT_ARRAY
            case TAG_ORIENTATION:
            case TAG_EXPOSURE_PROGRAM:
            case TAG_METERING_MODE:
            case TAG_FLASH:
            case TAG_EXIF_IMAGEWIDTH:
            case TAG_EXIF_IMAGELENGTH:
                return FMT_USHORT;
                break;

            //$FMT_ULONG_ARRAY
            case TAG_THUMBNAIL_LENGTH:
                return FMT_ULONG;
                break;

            //$FMT_URATIONAL_ARRAY
            case TAG_EXPOSURETIME:
            case TAG_FNUMBER:
            case TAG_COMPRESSION_LEVEL:
            case TAG_APERTURE:
            case TAG_MAXAPERTURE:
            case TAG_FOCALLENGTH:
                return FMT_URATIONAL;
                break;
            //$FMT_SBYTE_ARRAY
            //$FMT_UNDEFINED_ARRAY
            //$FMT_SSHORT_ARRAY
            //$FMT_SLONG_ARRAY

            //$FMT_SRATIONAL_ARRAY
            case TAG_SHUTTERSPEED:
            case TAG_EXPOSURE_BIAS:
                return FMT_SRATIONAL;
                break;

            //$FMT_SINGLE_ARRAY
            //$FMT_DOUBLE_ARRAY

            default:
                $this->debug("UNDEFINED TAG:",$tag);
                return -1;
        }
    }

    /**
     *
     * Reverse of ConvertAnyFormat, - Incomplete
     * TODO:
            only FMT_URATIONAL, FMT_SRATIONAL works
     *
     */
    function ConvertAnyFormatBack($Value, $Format)
    {
        //$Value = 0;
        switch($Format){
            case FMT_SBYTE:     $Value = $ValuePtr[0];  break;
            case FMT_BYTE:      $Value = $ValuePtr[0];  break;

            case FMT_USHORT:    $Value = $this->Get16u($ValuePtr[0],$ValuePtr[1]); break;
            case FMT_ULONG:     $Value = $this->Get32u($ValuePtr[0],$ValuePtr[1],$ValuePtr[2],$ValuePtr[3]); break;

            case FMT_URATIONAL:
            case FMT_SRATIONAL:
                {

                    $num = $Value[1][0];
                    $Den = $Value[1][1];

                    $ValuePtr[0] = chr($num >> 24);
                    $ValuePtr[1] = chr($num >> 16);
                    $ValuePtr[2] = chr($num >> 8);
                    $ValuePtr[3] = chr($num);

                    $ValuePtr[4] = chr($Den >> 24);
                    $ValuePtr[5] = chr($Den >> 16);
                    $ValuePtr[6] = chr($Den >> 8);
                    $ValuePtr[7] = chr($Den);

                    break;
                }

            case FMT_SSHORT:    $Value = $this->Get16u($ValuePtr[0],$ValuePtr[1]);  break;
            case FMT_SLONG:     $Value = $this->Get32s($ValuePtr[0],$ValuePtr[1],$ValuePtr[2],$ValuePtr[3]); break;

            // Not sure if this is correct (never seen float used in Exif format)
            case FMT_SINGLE:    $Value = $ValuePtr[0];      break;
            case FMT_DOUBLE:    $Value = $ValuePtr[0];             break;
            default:
                return -1;
        }
        return $ValuePtr;
    }

    /**
     * Returns the raw exif information stored
     *
     */
    function getExif() {
        if($this->exifSection > -1) {
                return $this->sections[$this->exifSection]["data"];
        }
        /** Exif data does not exists  */
        return -1;
    }

    /**
     * Addes raw exif information
     *
     * @param   string    Exif Data to be added.
     *
     * NOTE: This function will blindly replace any existing EXIF data
     */
    function addExif($exifData) {
        $exifSection = $this->findMarker(M_EXIF);
        if($exifSection == -1) {
            // make 3rd element as comment section - Push-up all elements
            for($i=$this->currSection;$i>2;$i--) {
                $this->sections[$i]["type"] = $this->sections[$i-1]["type"];
                $this->sections[$i]["data"] = $this->sections[$i-1]["data"];
                $this->sections[$i]["size"] = $this->sections[$i-1]["size"];
            }
            $exifSection = 2;
            $this->currSection++;
        }

        $this->sections[$exifSection]["type"] = M_EXIF;
        $this->sections[$exifSection]["data"] = $exifData;
        $this->sections[$exifSection]["size"] = strlen($exifData);
    }

    /**
     * Write the whole image back into a file.
     *  This function does not write back to the same file.
     *  You need to specify a filename
     *
     * @param   string    filename to save the JPEG content to
     */
    function writeImage($file) {

        $file = trim($file);
        if(empty($file)) {
            $this->errno = 3;
            $this->errstr = "File name not provided!";
            debug($this->errstr,1);
        }

        $fp = fopen($file,"wb");

        /** Initial static jpeg marker. */
        fwrite($fp,chr(0xff));
        fwrite($fp,chr(0xd8));

        if ($this->sections[0]["type"] != M_EXIF && $this->sections[0]["type"] != M_JFIF){
            $JfifHead = array(
                chr(0xff), chr(M_JFIF),
                chr(0x00), chr(0x10), 'J' , 'F' , 'I' , 'F' , chr(0x00), chr(0x01),
                chr(0x01), chr(0x01), chr(0x01), chr(0x2C), chr(0x01), chr(0x2C), chr(0x00), chr(0x00)
            );

            fwrite($fp,implode("",$JfifHead));
        }

        /** write each section back into the file */
        for($key=0;$key<$this->currSection-1;$key++) {
          if(!empty($this->sections[$key]["data"])) {
            fwrite($fp,chr(0xff));
            fwrite($fp,chr($this->sections[$key]["type"]));
            /**
              dat acan be array as well as string. Check the data-type of data.
              If it is an array then convert it to string.
            */
            if(is_array($this->sections[$key]["data"])) {
                $this->sections[$key]["data"] = implode("",$this->sections[$key]["data"]);
            }
            fwrite($fp,$this->sections[$key]["data"]);
          }
        }
        // Write the remaining image data.
            if(is_array($this->sections[$key]["data"])) {
                $this->sections[$key]["data"] = implode("",$this->sections[$key]["data"]);
            }
            fwrite($fp,$this->sections[$key]["data"]);
        fclose($fp);
    }

} // end of class
?>
