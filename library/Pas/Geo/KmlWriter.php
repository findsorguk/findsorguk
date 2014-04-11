<?php
/**
 * KML Main Class File
 *
 * File containing all classes to create KML or KMZ file
 *
 * @package kml
 * @version 0.3
 * @author Ken LE PRADO <ken@leprado.com>
 * @contributor Francois HETU, Alexander LÃ¼cking
 * @link http://kmlcreator.leprado.com
 */

/**#@+
 * Constants
 */

/**
 * Version of the KML Class
 */
define('KML_CLASS_VERSION', 0.3);

/**
 * Version of the KML produced
 */
define('KML_VERSION', 2.2);


/**
 * KML class to create kml file
 *
 * @package kml
 *
 */
class Pas_Geo_KmlWriter {

   private $title;
   private $visibility;
   private $open;
   private $files;

   /**
     * Feature
     */
   private $feature;

   public function __construct($title = '', $visibility = true, $open = true) {
      $this->$title       = $title;
      $this->$visibility  = $visibility;
      $this->$open        = $open;
      $this->files = array();
   }

   /**
     * return kml string
     *
     * @return string contenu du KML
     */
   public function __toString() {

      //Add header
		$string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$string .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\" xmlns:gx=\"http://www.google.com/kml/ext/2.2\">\n";

      if (!empty($this->feature)) {
         $string .= $this->feature->__toString();
      }

      //Add footer
		$string .= "</kml>\n";

      return $string;
   }

   /**
     * Add a Feature
     *
     * @param object feature Object "Feature" to add
     */
   public function setFeature ($feature) {
      $this->feature = $feature;
   }

   /**
     * Enregistrement du KML
     *
     * @param string type Type d'export : S=String F=File A=Attachement
     * @param string filename exported filename
     */

   public function output($type = 'S', $filename = '') {
      switch ($type) {
	      case 'A':
      		header('Content-type: application/vnd.google-earth.kml');
      		header('Content-Disposition:attachment; filename="' . $filename . '"');

      		echo $this->__toString();

      		exit;
      		break;

         case 'Z':
   		// compressed output
   			$fichier = new ZipArchive();
   			if($fichier->open($filename, ZIPARCHIVE::OVERWRITE) !== true) {
				   echo('Cannot create file');
				   return false;
			   }

   			$fichier->addFromString('doc.kml', $this->__toString());
   			foreach($this->files as $displayFileName => $fileName) {
   			   if (is_file($fileName)) {
   			      $fichier->addFile($fileName, $displayFileName);
   			   }
   			}

   			$fichier->close();
			   return true;
            break;


         case 'F':
            if (file_put_contents($filename, $this->__toString())) {
               return true;
            } else {
               return false;
            }

            break;

         case 'S':
            return $this->__toString();
            break;
		}

   }

   public function addFile($fileName, $displayFileName = '') {
      if (empty ($displayFileName)) {
         $displayFileName = basename($fileName);
      }
      $this->files[$displayFileName] = $fileName;
   }

}

/**
  * KML Object
  *  has an id
  *
  * @package kml
  *
  */
class KMLObject {
   private $id;
   private $type;

   public function __construct($type = '', $id = '') {
      $this->type = $type;
      $this->id   = $id;
   }

   public function headerToString() {
      if (empty($this->id)) {
         $string = "<".$this->type.">\n";
      } else {
         $string = '<'.$this->type.' id="'.$this->id."\">\n";
      }
      return $string;
   }

   public function footerToString() {
      $string = "</".$this->type.">\n";
      return $string;
   }

}

/**
  * Feature KML
  *
  * @package kml
  *
  */
class KMLFeature extends KMLObject {
   private $name;
   private $description;
   private $visibility;
   private $styles;
   private $styleUrl;
   private $features;
   private $TimePrimitive;


   public function __construct($type, $id = '', $name = '', $description = '', $visibility = true) {
      $this->name        = $name;
      $this->description = $description;
      $this->visibility  = $visibility;

      $this->features = array();
      $this->styles = array();
      parent::__construct($type, $id);
   }

	public function headerToString() {
		$string = parent::headerToString();

      if (!empty($this->name)) {
         $string .= "<name>\n";
         $string .= "<![CDATA[\n";
         $string .= $this->name . "\n";
         $string .= "]]>\n";
         $string .= "</name>\n";
      }
      if (!empty($this->description)) {
         $string .= "<description>\n";
         $string .= "<![CDATA[\n";
         $string .= $this->description . "\n";
         $string .= "]]>\n";
         $string .= "</description>\n";
      }

      if ($this->visibility === true) {
         $string .= "<visibility>1</visibility>\n";
      } else {
         $string .= "<visibility>0</visibility>\n";
      }

      if (!empty($this->styles)) {
         foreach($this->styles as $style) {
            $string .= $style->__toString();
         }
      }

		if (!empty($this->styleUrl)) {
			$string .= "<styleUrl>" . $this->styleUrl . "</styleUrl>\n";
		}

      if (!empty($this->TimePrimitive)) {
         $string .= $this->TimePrimitive->__toString();
      }

      foreach ($this->features as $feature) {
         $string .= $feature->__toString();
      }

      return $string;

   }

   public function footerToString() {
      $string = parent::footerToString();
      return $string;
   }

   public function addStyle($style) {
      $this->styles[] = $style;
   }

   public function setStyleUrl($styleUrl) {
      $this->styleUrl = $styleUrl;
   }

   public function __toString() {}


   public function addFeature($feature) {
      $this->features[] = $feature;
   }

   public function setTimePrimitive($TimePrimitive) {
      $this->TimePrimitive = $TimePrimitive;
   }
}

/**
  * Folder KML
  *
  * @package kml
  *
  */
class KMLFolder extends KMLFeature {

   public function __construct($id = '', $name = '', $description = '', $visibility = true) {
      parent::__construct('Folder', $id, $name, $description, $visibility);

   }

   public function __toString() {
      $string = '';

      $string .= parent::headerToString();




      $string .= parent::footerToString();

      return $string;
   }

}

/**
  * Document KML
  *
  * @package kml
  *
  */
class KMLDocument extends KMLFeature {

   private $placemarks;

   public function __construct($id = '', $name = '', $description = '', $visibility = true) {
      parent::__construct('Document', $id, $name, $description, $visibility);

      $this->placemarks = array();

   }

   public function __toString() {
      $string = '';

      $string .= parent::headerToString();


      foreach ($this->placemarks as $placemark) {
         $string .= $placemark->__toString();
      }


      $string .= parent::footerToString();

      return $string;
   }

   public function addPlaceMark($placemark) {
      $this->placemarks[] = $placemark;
   }

}


/**
  * PlaceMark KML
  *
  * @package kml
  *
  */
class KMLPlaceMark extends KMLFeature {

   private $geometry;

   public function __construct($id = '', $name = '', $description = '', $visibility = true) {
      parent::__construct('Placemark', $id, $name, $description, $visibility);

   }

   public function __toString() {
      $string = '';

      $string .= parent::headerToString();

      $string .= $this->geometry->__toString();

      $string .= parent::   footerToString();

      return $string;
   }

   public function setGeometry($geometry) {
      $this->geometry = $geometry;
   }

}

/**
  * Geometry KML
  *
  * @package kml
  *
  */
class KMLGeometry extends KMLObject {
   public function __construct($type, $id = '') {
      parent::__construct($type, $id);
   }

}


/**
  * Point KML
  *
  * @package kml
  *
  */
class KMLPoint extends KMLGeometry {
   private $longitude;
   private $latitude;
   private $altitude;
   private $extrude;
   private $altitudeMode;

   public function __construct($longitude, $latitude, $altitude = 0, $extrude = true, $altitudeMode = 'clampToGround') {
      $this->longitude    = $longitude;
      $this->latitude     = $latitude;
      $this->altitude     = $altitude;
      $this->extrude      = $extrude;
      $this->altitudeMode = $altitudeMode;
   }

   public function __toString() {
      $string = '';
      $string .= "<Point>\n";
      if (!empty($this->extrude)) {
         if ($this->extrude === true) {
            $string .= "<extrude>1</extrude>\n";
         } else {
            $string .= "<extrude>0</extrude>\n";
         }
      }

      if (!empty($this->altitudeMode)) {
         $string .= "<altitudeMode>".$this->altitudeMode."</altitudeMode>\n";
      }

      $string .= "<coordinates>";
      $string .= $this->longitude . ', ' . $this->latitude . ', ' .  $this->altitude ."\n";
      $string .= "</coordinates>\n";
      $string .= "</Point>\n";
      return $string;
   }
}

/**
  * LineString KML
  *
  * @package kml
  *
  */
class KMLLineString extends KMLGeometry {
   private $points;
   private $extrude;
   private $altitudeMode;
   private $tessellate;

   public function __construct($points, $extrude = true, $altitudeMode = 'clampToGround', $tessellate = true) {
      $this->points  = $points;
      $this->extrude      = $extrude;
      $this->altitudeMode = $altitudeMode;
      $this->tessellate   = $tessellate;
   }

   public function __toString() {
      $string = '';
      $string .= "<LineString>\n";

      if (!empty($this->extrude)) {
         if ($this->extrude === true) {
            $string .= "<extrude>1</extrude>\n";
         } else {
            $string .= "<extrude>0</extrude>\n";
         }
      }

      if (!empty($this->tessellate)) {
         if ($this->tessellate === true) {
            $string .= "<tessellate>1</tessellate>\n";
         } else {
            $string .= "<tessellate>0</tessellate>\n";
         }
      }

      if (!empty($this->altitudeMode)) {
         $string .= "<altitudeMode>".$this->altitudeMode."</altitudeMode>\n";
      }

      $string .= "<coordinates>\n";
      foreach ($this->points as $point) {
         $string .= $point[0].','.$point[1].','.$point[2]." \n";
      }
      $string .= "</coordinates>\n";
      $string .= "</LineString>\n";

      return $string;
   }
}

/**
  * Polygon KML
  *
  * @package kml
  *
  */
class KMLPolygon extends KMLGeometry {
   private $outerBoundary;
   private $extrude;
   private $altitudeMode;
   private $tessellate;

   public function __construct($outerBoundary, $extrude = true, $altitudeMode = 'clampToGround', $tessellate = true) {
      $this->outerBoundary  = $outerBoundary;
      $this->extrude      = $extrude;
      $this->altitudeMode = $altitudeMode;
      $this->tessellate   = $tessellate;
   }

   public function __toString() {
      $string = '';
      $string .= "<Polygon>\n";

      if (!empty($this->extrude)) {
         if ($this->extrude === true) {
            $string .= "<extrude>1</extrude>\n";
         } else {
            $string .= "<extrude>0</extrude>\n";
         }
      }

      if (!empty($this->tessellate)) {
         if ($this->tessellate === true) {
            $string .= "<tessellate>1</tessellate>\n";
         } else {
            $string .= "<tessellate>0</tessellate>\n";
         }
      }

      if (!empty($this->altitudeMode)) {
         $string .= "<altitudeMode>".$this->altitudeMode."</altitudeMode>\n";
      }



      $string .= "<outerBoundaryIs>\n";
      $string .= "<LinearRing>\n";
      $string .= "<coordinates>\n";

      foreach ($this->outerBoundary as $point) {
         $string .= $point[0].','.$point[1].','.$point[2]." \n";
      }

      $string .= "</coordinates>\n";
      $string .= "</LinearRing>\n";
      $string .= "</outerBoundaryIs>\n";
      $string .= "</Polygon>\n";

      return $string;
   }
}



/**
  * Polygon KML
  *
  * @package kml
  *
  */
class KMLMultiGeometry extends KMLGeometry {
   private $geometries;

   public function __construct() {
      $this->geometries   = array();
   }

   public function __toString() {
      $string = '';

      $string .= "<MultiGeometry>\n";

      foreach ($this->geometries as $geometry) {
         $string .= $geometry->__toString();
      }

      $string .= "</MultiGeometry>\n";

      return $string;
   }

   public function addGeometry($geometry) {
      $this->geometries[] = $geometry;
   }
}



/**
  * StyleSelector KML
  *
  * @package kml
  *
  */
class KMLStyleSelector extends KMLObject {
   public function __construct($type, $id = '') {
      parent::__construct($type, $id);
   }
}

/**
  * Style KML
  *
  * @package kml
  *
  */
class KMLStyle extends KMLStyleSelector {
   private $IconStyle;
   private $LabelStyle;
   private $LineStyle;
   private $PolyStyle;
   private $BalloonStyle;


   public function __construct($id = '') {

      parent::__construct('Style', $id);
   }

   public function __toString() {
      $string = '';
      $string .= KMLObject::headerToString();

      if (!empty($this->IconStyle)) {
         $string .= "<IconStyle>\n";
		    if ($this->IconStyle['hotspot']) {
				$string .= "<hotSpot x=\"".$this->IconStyle['x']."\" y=\"".$this->IconStyle['y']."\" xunits=\"".$this->IconStyle['xunits']."\" yunits=\"".$this->IconStyle['yunits']."\" />"."\n";
			}
        if (!empty($this->IconStyle['color'])) {
            $string .= "<color>".$this->IconStyle['color']."</color>\n";
         }

         if (!empty($this->IconStyle['colorMode'])) {
            $string .= "<colorMode>".$this->IconStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->IconStyle['scale'])) {
            $string .= "<scale>".$this->IconStyle['scale']."</scale>\n";
         }

         if (!empty($this->IconStyle['icon'])) {
            $string .= "<Icon>\n";
            $string .= "<href>".$this->IconStyle['icon']."</href>\n";
            $string .= "</Icon>\n";
         }

         $string .= "</IconStyle>\n";
      }



      if (!empty($this->LabelStyle)) {
         $string .= "<LabelStyle>\n";

         if (!empty($this->LabelStyle['color'])) {
            $string .= "<color>".$this->LabelStyle['color']."</color>\n";
         }

         if (!empty($this->LabelStyle['colorMode'])) {
            $string .= "<colorMode>".$this->LabelStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->LabelStyle['scale'])) {
            $string .= "<scale>".$this->LabelStyle['scale']."</scale>\n";
         }

         $string .= "</LabelStyle>\n";
      }


      if (!empty($this->PolyStyle)) {
         $string .= "<PolyStyle>\n";

         if (!empty($this->PolyStyle['color'])) {
            $string .= "<color>".$this->PolyStyle['color']."</color>\n";
         }

         if (!empty($this->PolyStyle['colorMode'])) {
            $string .= "<colorMode>".$this->PolyStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->PolyStyle['fill'])) {
            if ($this->PolyStyle['fill'] === true) {
               $string .= "<fill>1</fill>\n";
            } else {
               $string .= "<fill>0</fill>\n";
            }
         }

         if (!empty($this->PolyStyle['outline'])) {
            if ($this->PolyStyle['outline'] === true) {
               $string .= "<outline>1</outline>\n";
            } else {
               $string .= "<outline>0</outline>\n";
            }
         }

         $string .= "</PolyStyle>\n";
      }



      if (!empty($this->LineStyle)) {
         $string .= "<LineStyle>\n";

         if (!empty($this->LineStyle['color'])) {
            $string .= "<color>".$this->LineStyle['color']."</color>\n";
         }

         if (!empty($this->LineStyle['colorMode'])) {
            $string .= "<colorMode>".$this->LineStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->LineStyle['width'])) {
            $string .= "<width>".$this->LineStyle['width']."</width>\n";
         }

         $string .= "</LineStyle>\n";
      }

      if (!empty($this->BalloonStyle)) {
         $string .= "<BalloonStyle>\n";

         if (!empty($this->BalloonStyle['bgColor'])) {
            $string .= "<bgColor>".$this->BalloonStyle['bgColor']."</bgColor>\n";
         }

         if (!empty($this->BalloonStyle['textColor'])) {
            $string .= "<textColor>".$this->BalloonStyle['textColor']."</textColor>\n";
         }

         if (!empty($this->BalloonStyle['text'])) {
            $string .= "<text>\n";
            $string .= "<![CDATA[\n";
            $string .= $this->BalloonStyle['text'] . "\n";
            $string .= "]]>\n";
            $string .= "</text>\n";
         }

         if (!empty($this->BalloonStyle['displayMode'])) {
            $string .= "<displayMode>\n";
            $string .= "<![CDATA[\n";
            $string .= utf8_encode($this->BalloonStyle['displayMode']) . "\n";
            $string .= "]]>\n";
            $string .= "</displayMode>\n";
         }


         $string .= "</BalloonStyle>\n";
      }

      $string .= KMLObject::footerToString();
      return $string;

   }


   public function setIconStyle($icon = '', $color = '', $colorMode = 'normal', $scale = 1, $hotspot = true, $x = 19, $y = 0, $xunits = 'pixels', $yunits = 'pixels') {
      $this->IconStyle = Array(
                              'icon'       => $icon,
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'scale'      => $scale,
							  'hotspot'	   => $hotspot,
							  'x'          => $x,
							  'y'          => $y,
							  'xunits'     => $xunits,
							  'yunits'     => $yunits

		);
   }

   public function setLabelStyle ($color = '', $colorMode = 'normal', $scale = 1) {
      $this->LabelStyle = Array(
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'scale'      => $scale,
                           );

   }

   public function setPolyStyle ($color = '', $colorMode = 'normal', $fill = true, $outline = true) {
      $this->PolyStyle = Array(
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'fill'       => $fill,
                              'outline'    => $outline
                           );

   }

   public function setLineStyle ($color = '', $colorMode = 'normal', $width = 1) {
      $this->LineStyle = Array(
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'width'    => $width
                           );

   }

   public function setBalloonStyle ($text = '', $textColor = '', $bgColor = '', $displayMode = 'default') {
      $this->BalloonStyle = Array(
                              'text'        => $text,
                              'textColor'   => $textColor,
                              'bgColor'     => $bgColor,
                              'displayMode' => $displayMode
                           );

   }

}

/**
  * StyleSelector KML
  *
  * @package kml
  *
  */
class KMLTimePrimitive extends KMLObject {
   public function __construct($type, $id = '') {
      parent::__construct($type, $id);
   }
}

/**
  * TimeStamp KML
  *
  * @package kml
  *
  */
class KMLTimeStamp extends KMLTimePrimitive {
   private $timestamp;

   public function __construct($id = '', $timestamp) {
      $this->timestamp = $timestamp;
      parent::__construct('TimeStamp', $id);
   }

   public function __toString() {
      $string = '';
      $string .= KMLObject::headerToString();

      $string .= "<when>".$this->timestamp."</when>\n";

      $string .= KMLObject::footerToString();
      return $string;

   }

   /**
     * Test date format
     *
     * @param string date Date to test
     * @result boolean Result of the test (boolean => isDate)
     */
   public function isDate ($date) {
      //A
      if (ereg("^([0-9]{4})(-[0-9]{2}(.*))?", $date)) {
         return true;
      } else {
         return false;
      }
   }
}


/**
  * TimeSpan KML
  *
  * @package kml
  *
  */
class KMLTimeSpan extends KMLTimePrimitive {
   private $begintime;
   private $endtime;

   public function __construct($id = '', $begintime = '', $endtime = '') {
      $this->begintime = $begintime;
      $this->endtime   = $endtime;
      parent::__construct('TimeStamp', $id);
   }

   public function __toString() {
      $string = '';
      $string .= KMLObject::headerToString();

      if (!empty($this->$begintime)) {
         $string .= "<begin>".$this->begintime."</begin>\n";
      }
      if (!empty($this->$endtime)) {
         $string .= "<begin>".$this->endtime."</begin>\n";
      }

      $string .= KMLObject::footerToString();
      return $string;

   }
}

class KMLAnimation extends KMLObject {
	private $extension = "gx:";
	private $flyTos = null;
	private $tourPrimitives = null;
	private $name;
	private $description;

	public function __construct($id = "", $name = "", $description = ""){
		parent::__construct($this->extension."Tour", $id);
		$this->name = $name;
		$this->description = $description;
		$this->flyTos = array();
		$this->tourPrimitives = array();
	}
	public function __toString(){
		$string = parent::headerToString();
		//$string .= "<name>".utf8_encode($this->name)."</name>\n";
		if (!empty($this->name)) {
			$string .= "<name>\n";
			$string .= "<![CDATA[\n";
			$string .= $this->name . "\n";
			$string .= "]]>\n";
			$string .= "</name>\n";
		}
		if (!empty($this->description)) {
			$string .= "<description>\n";
			$string .= "<![CDATA[\n";
			$string .= $this->description . "\n";
			$string .= "]]>\n";
			$string .= "</description>\n";
		}
		$string .= "<".$this->extension."Playlist>\n";
		foreach($this->flyTos as $flyTo){
			$string .= $flyTo->__toString();
		}
		foreach($this->tourPrimitives as $tourPrimitive){
			$string .= $tourPrimitive->__toString();
		}
		$string .= "</".$this->extension."Playlist>\n";
		$string .= parent::footerToString();
		return $string;
	}
	public function addFlyTo($KMLFlyTo, $order = 0){
		$this->flyTos[] = $KMLFlyTo;
	}
	public function addTourPrimitive($tourPrimitive){
		$this->tourPrimitives[] = $tourPrimitive;
	}

}
class KMLTourPrimitive extends KMLObject {
	protected $extension = "gx:";
	public function __construct($type){
		parent::__construct($this->extension.$type);
	}
	public function headerToString(){
		return parent::headerToString();
	}
	public function footerToString(){
		return parent::footerToString();
	}
}
class KMLFlyTo extends KMLTourPrimitive {
	private $duration = 5.0;
	private $abstractView = null;
	private $flyToMode = "bounce";//smooth
	private $latitude = 0.000;
	private $longitude = 0.000;
	private $altitude = 0;
	private $heading = 0.000; //degrees, 0:360
	private $range = 0;
	private $tilt = 0;//degrees, 0:90
	private $roll = 0;//degrees, 0:180
	private $altitudeMode = "relativeToGround";//absolute, clampToGround
	private $beforeWaits;
	private $afterWaits;

	public function __construct($duration = 5.0, $flyToMode = "B"){
		parent::__construct("FlyTo");
		$this->duration = $duration;
		switch($flyToMode){
			case "B":
				$this->flyToMode = "bounce";
				break;
			case "S":
				$this->flyToMode = "smooth";
				break;
			default:
				$this->flyToMode = "bounce";
				break;
		}
		$this->beforeWaits = array();
		$this->afterWaits = array();
	}
	public function setAbstractView($KMLAbstractView){
		$this->abstractView = $KMLAbstractView;
	}
	public function addWait($duration = 3, $after = true){
		$wait = new KMLWait($duration);
		if($after){
			$this->afterWaits[] = $wait;
		}else{
			$this->beforeWaits[] = $wait;
		}
	}
	public function __toString(){
		$string = parent::headerToString();
		$string .= "<".$this->extension."duration>".$this->duration."</".$this->extension."duration>\n";
		$string .= "<".$this->extension."flyToMode>".$this->flyToMode."</".$this->extension."flyToMode>\n";
		$string .= $this->abstractView->__toString();
		$string .= parent::footerToString();

		$beforeWaitsString = "";
		$afterWaitsString = "";
		foreach($this->beforeWaits as $wait){
			$beforeWaitsString .= $wait->__toString();
		}
		foreach($this->afterWaits as $wait){
			$afterWaitsString .= $wait->__toString();
		}
		$string = $beforeWaitsString.$string.$afterWaitsString;

		return $string;

	}

}
class KMLWait extends KMLTourPrimitive{
	private $duration = 3;
	public function __construct($duration){
		$this->duration = $duration;
		parent::__construct('Wait');
	}
	public function __toString(){
		$string = parent::headerToString();
		$string .= "<".$this->extension."duration>".$this->duration."</".$this->extension."duration>\n";
		$string .= parent::footerToString();
		return $string;
	}

}
class KMLAnimatedUpdate extends KMLTourPrimitive {
	private $targetPlacemarkId = '';
	private $wait = null;
	private $closeBalloon = true;
	public function __construct($duration = 4){
		parent::__construct('AnimatedUpdate');
		$this->wait = new KMLWait($duration);
	}
	public function __toString(){
		$string = parent::headerToString();
		$string .= "<Update>\n";
		$string .= "<targetHref/>\n";
		$string .= "<Change>\n";
		$string .= "<Placemark targetId=\"".$this->targetPlacemarkId."\">\n";
		$string .= "<gx:balloonVisibility>1</gx:balloonVisibility>\n";
		$string .= "</Placemark>\n";
		$string .= "</Change>\n";
		$string .= "</Update>\n";
		$string .= parent::footerToString();
		if($this->closeBalloon){
			$string .= $this->wait->__toString();
			$string .= parent::headerToString();
			$string .= "<Update>\n";
			$string .= "<targetHref/>\n";
			$string .= "<Change>\n";
			$string .= "<Placemark targetId=\"".$this->targetPlacemarkId."\">\n";
			$string .= "<gx:balloonVisibility>0</gx:balloonVisibility>\n";
			$string .= "</Placemark>\n";
			$string .= "</Change>\n";
			$string .= "</Update>\n";
			$string .= parent::footerToString();
		}
		return $string;
	}
	public function setTargetPlacemarkId($id){
		$this->targetPlacemarkId = $id;
	}
	public function keepBalloonOpenOnEnd(){
		$this->closeBalloon = false;
	}
}
class KMLAbstractView extends KMLObject{
	private $typeOfView = "LookAt";
	private $latitude = 0.000;
	private $longitude = 0.000;
	private $altitude = 0;
	private $heading = 0.000; //degrees, 0:360
	private $range = 0;
	private $tilt = 0;//degrees, 0:90
	private $roll = 0;//degrees, 0:180
	private $altitudeMode = "relativeToGround";//absolute, clampToGround
	public function __construct($typeOfView = "L", $id=""){
		switch($typeOfView){
			case "L":
				$this->typeOfView = "LookAt";
				break;
			case "C":
				$this->typeOfView = "Camera";
				break;
			default:
				$this->typeOfView = "LookAt";
				break;
		}
		parent::__construct($this->typeOfView, $id);
	}
	public function setPosition($latitude = 0, $longitude = 0, $altitude = 0, $altitudeMode = "R"){
		switch($altitudeMode){
			case "R":
				$this->altitudeMode = "relativeToGround";
				break;
			case "A":
				$this->altitudeMode = "absolute";
				break;
			case "C":
				$this->altitudeMode = "clampToGround";
				break;
			default:
				$this->altitudeMode = "relativeToGround";
				break;
		}
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->altitude = $altitude;
	}
	public function setOrientation($heading = 0, $range = 0, $tilt = 0, $roll = 0){
		$this->heading = $heading;
		$this->range = $range;
		$this->tilt = $tilt;
		$this->roll = $roll;
	}
	public function __toString(){
		$string = "";
		$string .= "<".$this->typeOfView.">\n";
		$string .= "<longitude>".$this->longitude."</longitude>\n";
		$string .= "<latitude>".$this->latitude."</latitude>\n";
		$string .= "<altitude>".$this->altitude."</altitude>\n";
		$string .= "<heading>".$this->heading."</heading>\n";
		$string .= "<tilt>".$this->tilt."</tilt>\n";
		if($this->typeOfView == "LookAt"){
			$string .= "<range>".$this->range."</range>\n";
		}
		if($this->typeOfView == "Camera"){
			$string .= "<roll>".$this->roll."</roll>\n";
		}
		$string .= "<altitudeMode>".$this->altitudeMode."</altitudeMode>\n";
		$string .= "</".$this->typeOfView.">\n";
		return $string;
	}
}

class KMLOverlay extends KMLFeature{
	private $color=""; //aabbggrr, with opacity aa:00=>full transparency
	private $drawOrder = -1; //Highest has priority
	private $iconHref= ""; //If href="" a rectangle is drawn.

	public function __construct($type = '', $id = '', $name = '', $description = '', $visibility = true){
		parent::__construct($type, $id, $name, $description, $visibility);
	}
	public function setColor($color = "00FFFFFF"){
		$this->color = $color;
	}
	public function setDrawOrder($drawOrder = 0){
		$this->drawOrder = $drawOrder;
	}
	public function setIconHref($iconHref){
		$this->iconHref = $iconHref;
	}
	public function headerToString(){
		$string = parent::headerToString();
		if(!empty($this->iconHref)){
			$string .= "<Icon><href>".$this->iconHref."</href></Icon>\n";
		}
		if(!empty($this->color)){
			$string .= "<color>".$this->iconHref."</color>\n";
		}
		if($this->drawOrder >= 0){
			$string .= "<drawOrder>".$this->drawOrder."</drawOrder>\n";
		}
		return $string;
	}

}

class KMLPhotoOverlay extends KMLOverlay{
	private $rotation = 0;				// <!-- kml:angle180 -->
	/*ViewVolume*/
	private $near = 100;				// Meters
	private $leftFov = -45;				// <!-- kml:angle180 -->
	private $rightFov = 45;				// <!-- kml:angle180 -->
	private $bottomFov = -45;			// <!-- kml:angle180 -->
	private $topFov = 45;				// <!-- kml:angle180 -->
	/*ImagePyramid*/
	private $hasImagePyramid = false;
	private $tileSize = 256;			// int, px
	private $maxWidth = 1000;				// int, px
	private $maxHeight = 1000;				// int, px
	private $gridOrigin = "lowerLeft";	// upperLeft

	private $point = null;
	private $shape = "rectangle"; //rectangle, cylinder, sphere

	public function __construct($id = '', $name = '', $description = '', $visibility = true) {
		parent::__construct('PhotoOverlay', $id, $name, $description, $visibility);

	}
	public function setViewVolume($near = 100, $leftFov = -45, $rightFov = 45, $bottomFov = -45, $topFov = 45){
		$this->near = $near;
		$this->leftFov = $leftFov;
		$this->rightFov =$rightFov;
		$this->bottomFov = $bottomFov;
		$this->topFov = $topFov;
	}
	public function calculateViewVolume($imageHeight, $imageWidth, $near){
		$this->near = $near;
		$this->topFov = rad2deg(atan($imageHeight/(2*$near)));
		$this->bottomFov = -$this->topFov;
		$this->rightFov = rad2deg(atan($imageWidth/(2*$near)));
		$this->leftFov = -$this->rightFov;
	}
	public function setImagePyramid($tileSize = 256, $maxWidth = 1000, $maxHeight= 1000, $gridOrigin = "lowerLeft"){
		$this->hasImagePyramid = true;
		$this->tileSize = $tileSize;
		$this->maxWidth = $maxWidth;
		$this->maxHeight = $maxHeight;
		$this->gridOrigin = $gridOrigin;
	}
	public function setPoint($longitude, $latitude, $altitude = 0, $extrude = false, $altitudeMode = "relativeToGround"){
		$this->point = new KMLPoint($longitude, $latitude, $altitude, $extrude, $altitudeMode);
	}
	public function setShape($shape = "R"){
		switch($shape){
			case "R":
				$this->shape = "rectangle";
				break;
			case "C":
				$this->shape = "cylinder";
				break;
			case "S":
				$this->shape = "sphere";
				break;
			default:
				$this->shape = "rectangle";
				break;
		}

	}
	public function __toString(){
		$string = parent::headerToString();
		$string .= "<rotation>".$this->rotation."</rotation>\n";
		$string .=
		"<ViewVolume>\n".
		"<near>".$this->near."</near>\n".
		"<leftFov>".$this->leftFov."</leftFov>\n".
		"<rightFov>".$this->rightFov."</rightFov>\n".
		"<bottomFov>".$this->bottomFov."</bottomFov>\n".
		"<topFov>".$this->topFov."</topFov>\n".
		"</ViewVolume>\n";
		if($this->hasImagePyramid){
			$string .=
			"<ImagePyramid>\n".
			"<tileSize>".$this->tileSize."</tileSize>\n".
			"<maxWidth>".$this->maxWidth."</maxWidth>\n".
			"<maxHeight>".$this->maxHeight."</maxHeight>\n".
			"<gridOrigin>".$this->gridOrigin."</gridOrigin>\n".
			"</ImagePyramid>\n";
		}
		if(!empty($this->point)){
			$string .= $this->point->__toString();
		}
		$string .= "<shape>".$this->shape."</shape>\n";
		$string .= parent::footerToString();
		return $string;
	}

}

class KMLGroundOverlay extends KMLOverlay{
    private $latlonBox = array();
    private $rotation = 0;

    public function __construct($id = '', $name = '', $description = '', $visibility = true) {
        parent::__construct('GroundOverlay', $id, $name, $description, $visibility);
    }

    public function setLanLonBox($north, $south, $east, $west, $rotation = 0) {
        $this->latlonBox = array($north, $south, $east, $west, $rotation);
        $this->rotation = $rotation;
    }

    public function __toString(){
        $string = parent::headerToString();

        if(count($this->latlonBox) > 0)
        {
            $string .= "<LatLonBox>";
            $string .= "<north>".$this->latlonBox[0]."</north>";
            $string .= "<south>".$this->latlonBox[1]."</south>";
            $string .= "<east>".$this->latlonBox[2]."</east>";
            $string .= "<west>".$this->latlonBox[3]."</west>";
            $string .= "<rotation>".$this->rotation."</rotation>";
            $string .= "</LatLonBox>";
        }

        $string .= parent::footerToString();
        return $string;
    }
}

class KMLScreenOverlay extends KMLOverlay{
	public function __construct($id = '', $name = '', $description = '', $visibility = true) {
		parent::__construct('ScreenOverlay', $id, $name, $description, $visibility);
	}

	public function __toString(){
		$string = parent::headerToString();

		$string .= parent::footerToString();
		return $string;
	}

}
