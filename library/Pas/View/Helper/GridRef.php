<?php

 class RefEll {

    var $maj;
    var $min;
    var $ecc;


    /**
     * Create a new RefEll object to represent a reference ellipsoid
     *
     * @param maj the major axis
     * @param min the minor axis
     */
    function RefEll($maj, $min) {
      $this->maj = $maj;
      $this->min = $min;
      $this->ecc = (($maj * $maj) - ($min * $min)) / ($maj * $maj);
    }
  }

class Pas_View_Helper_GridRef extends Zend_View_Helper_Abstract
{	
    
function convert($lat,$long) {
      $airy1830 = new RefEll(6377563.396, 6356256.909);
      $OSGB_F0  = 0.9996012717;
      $N0       = -100000.0;
      $E0       = 400000.0;
      $phi0     = deg2rad(49.0);
      $lambda0  = deg2rad(-2.0);
      $a        = $airy1830->maj;
      $b        = $airy1830->min;
      $eSquared = $airy1830->ecc;
      $phi = deg2rad($lat);
      $lambda = deg2rad($long);
      $E = 0.0;
      $N = 0.0;
      $n = ($a - $b) / ($a + $b);
      $v = $a * $OSGB_F0 * pow(1.0 - $eSquared * $this->sinSquared($phi), -0.5);
      $rho =
        $a * $OSGB_F0 * (1.0 - $eSquared) * pow(1.0 - $eSquared * $this->sinSquared($phi), -1.5);
      $etaSquared = ($v / $rho) - 1.0;
      $M =
        ($b * $OSGB_F0)
          * (((1 + $n + ((5.0 / 4.0) * $n * $n) + ((5.0 / 4.0) * $n * $n * $n))
            * ($phi - $phi0))
            - (((3 * $n) + (3 * $n * $n) + ((21.0 / 8.0) * $n * $n * $n))
              * sin($phi - $phi0)
              * cos($phi + $phi0))
            + ((((15.0 / 8.0) * $n * $n) + ((15.0 / 8.0) * $n * $n * $n))
              * sin(2.0 * ($phi - $phi0))
              * cos(2.0 * ($phi + $phi0)))
            - (((35.0 / 24.0) * $n * $n * $n)
              * sin(3.0 * ($phi - $phi0))
              * cos(3.0 * ($phi + $phi0))));
      $I = $M + $N0;
      $II = ($v / 2.0) * sin($phi) * cos($phi);
      $III =
        ($v / 24.0)
          * sin($phi)
          * pow(cos($phi), 3.0)
          * (5.0 - $this->tanSquared($phi) + (9.0 * $etaSquared));
      $IIIA =
        ($v / 720.0)
          * sin($phi)
          * pow(cos($phi), 5.0)
          * (61.0 - (58.0 * $this->tanSquared($phi)) + pow(tan($phi), 4.0));
      $IV = $v * cos($phi);
      $V = ($v / 6.0) * pow(cos($phi), 3.0) * (($v / $rho) - $this->tanSquared($phi));
      $VI =
        ($v / 120.0)
          * pow(cos($phi), 5.0)
          * (5.0
            - (18.0 * $this->tanSquared($phi))
            + (pow(tan($phi), 4.0))
            + (14 * $etaSquared)
            - (58 * $this->tanSquared($phi) * $etaSquared));

      $N =
        $I
          + ($II * pow($lambda - $lambda0, 2.0))
          + ($III * pow($lambda - $lambda0, 4.0))
          + ($IIIA * pow($lambda - $lambda0, 6.0));
      $E =
        $E0
          + ($IV * ($lambda - $lambda0))
          + ($V * pow($lambda - $lambda0, 3.0))
          + ($VI * pow($lambda - $lambda0, 5.0));

      return array($E, $N);
    }
	
	 function sinSquared($x) {
    return sin($x) * sin($x);
  }

  function cosSquared($x) {
    return cos($x) * cos($x);
  }

  function tanSquared($x) {
    return tan($x) * tan($x);
  }

  function sec($x) {
    return 1.0 / cos($x);
  }
  
  function RefEll($maj, $min) {
      $this->maj = $maj;
      $this->min = $min;
      $this->ecc = (($maj * $maj) - ($min * $min)) / ($maj * $maj);
    }
	

public function GridRef($lat,$long) {

$xandy = $this->convert($lat,$long);
$e = $xandy['0'];
$n = $xandy['1'];
$ref = str_replace(' ','',$this->osgb36_to_gridref($e,$n));

	$letterpair = substr($ref,0,2); //strips off first two characters as National grid has 2 let
	$letterpair = strtoupper($letterpair); //transform smallcase to capital
	
	$rawcount = strlen($ref);//gets length of string
	$coordcount = $rawcount-2;//simple math to get coord count
	$digits = 4;
	if ($digits > $coordcount){$digits = $coordcount;} //keeps digits sensible
		
	$gridcoords = substr($ref,2,$coordcount);	//isolates the grid numbers
	
	$fromEach = round($digits/2);	//number of digits to grab from E and N each
	
	$halfcount = $coordcount/2; //get half of number of digits
	$eastcoord = (substr($gridcoords,0,$fromEach));  //get inital easting without grid square
	$northcoord = (substr($gridcoords,$halfcount,$fromEach)); //get inital northing without grid square
	
	$fourDigit = $letterpair.$eastcoord.$northcoord;
	//return array($ref,$fourdigit,$e,$n);
	$allowed = array('hero','research','flos','admin','fa');
	$auth = Zend_Registry::get('auth');
	if($auth->hasIdentity()) {
	$user = $auth->getIdentity();
	if(in_array($user->role,$allowed)) {
    
	return 'Grid ref: '.$ref.'<br />Restricted 4 figure grid reference: <span class="knownas">'.$fourDigit.'</span><br />Easting: '. round($e).'<br />Northing: '. round($n);
	
	} else {
    return 'Restricted 4 figure grid reference: <span class="knownas">'.$fourDigit.'</span>';
	}
	} else {
    return 'Restricted 4 figure grid reference: <span class="knownas">'.$fourDigit.'</span>';
	}

}
function wgs84_to_internal($lat,$long) {
	list($e,$n,$reference_index) = $this->wgs84_to_national($lat,$long);
	return $this->national_to_internal($e,$n,$reference_index);
}


// see solution 1 at http://astronomy.swin.edu.au/~pbourke/geometry/insidepoly/
function pointInside($p,&$points) {
	$c = 0;
	$p1 = $points[0];
	$n = count($points);
	for ($i=1; $i<=$n; $i++) {
		$p2 = $points[$i % $n];
		if ($p[1] > min($p1[1], $p2[1]) 
				&& $p[1] <= max($p1[1], $p2[1]) 
				&& $p[0] <= max($p1[0], $p2[0]) 
				&& $p1[1] != $p2[1]) {
			$xinters = ($p[1] - $p1[1]) * ($p2[0] - $p1[0]) / ($p2[1] - $p1[1]) + $p1[0];
			if ($p1[0] == $p2[0] || $p[0] <= $xinters)
				$c++;
		}
		$p1 = $p2;
	}
	// if the number of edges we passed through is even, then it’s not in the poly.
	return $c%2!=0;
}
		

//use:	list($e,$n,$reference_index) = wgs84_to_national($lat,$long);
		//with reference_index deduced from the location and the approraite conversion used
function wgs84_to_national($lat,$long,$usehermert = true) {
	$conv = new ConversionsLatLong;
	$ire = ($lat > 51.2 && $lat < 55.73 && $long > -12.2 && $long < -4.8);
	$uk = ($lat > 49 && $lat < 62 && $long > -9.5 && $long < 2.3);
	
	if ($uk && $ire) {
		//rough border for ireland
		$ireland = array(
			array(-12.19,50.38),
			array( -6.39,50.94),
			array( -5.07,53.71),
			array( -5.25,54.71),
			array( -6.13,55.42),
			array(-10.65,56.15),
			array(-12.19,50.38) );
		$ire = $this->pointInside(array($long,$lat),$ireland);
		$uk = 1 - $ire;
	} 
	
	if ($ire) {
		return array_merge($conv->wgs84_to_irish($lat,$long,$usehermert),array(2));
	} else if ($uk) {
		return array_merge($conv->wgs84_to_osgb36($lat,$long),array(1));
	}
}


//use:	list($lat,$long) = internal_to_wgs84($x,$y,$reference_index = 0);
		//reference_index is optional as we can duduce this (but if known then can pass it in to save having to recaluate)
			//will probably just call national_to_wgs84 once converted

function internal_to_wgs84($x,$y,$reference_index = 0) {
	list ($e,$n,$reference_index) = $this->internal_to_national($x,$y,$reference_index);
	return $this->national_to_wgs84($e,$n,$reference_index);
}


//use:	list($lat,$long) = national_to_wgs84($e,$n,$reference_index);

function national_to_wgs84($e,$n,$reference_index,$usehermert = true) {
	$conv = new ConversionsLatLong;
	$latlong = array();
	if ($reference_index == 1) {
		$latlong = $conv->osgb36_to_wgs84($e,$n);
	} else if ($reference_index == 2) {
		$latlong = $conv->irish_to_wgs84($e,$n,$usehermert);
	}
	return $latlong;
}


//use:	list($lat,$long) = gridsquare_to_wgs84(&$gridsquare);
			//will contain nateastings/natnorthings  or can call getNationalEastings to get them

function gridsquare_to_wgs84(&$gridsquare) {
	if (!$gridsquare->nateastings)
		$gridsquare->getNatEastings();
	return $this->national_to_wgs84($gridsquare->nateastings,$gridsquare->natnorthings,$gridsquare->reference_index);
}

//--------------------------------------------------------------------------------
// convenence functions

//use:    $gr = internal_to_gridref($x,$y,$gr_length,$reference_index = 0);
         //reference_index is optional as we can duduce this

function internal_to_gridref($x,$y,$gr_length,$reference_index = 0) {
	list($e,$n,$reference_index) = $this->internal_to_national($x,$y,$reference_index);

	return $this->national_to_gridref($e-500,$n-500,$gr_length,$reference_index);
}


//use:    list($gr,$len) = national_to_gridref($e,$n,$gr_length,$reference_index);

function national_to_gridref($e,$n,$gr_length,$reference_index,$spaced = false) {
	if (!$reference_index) {
		return array("",0);
	}
	list($x,$y) = $this->national_to_internal($e,$n,$reference_index );

	$db = $this->_getDB();

	$x_lim=$x-100;
	$y_lim=$y-100;
	$sql="select prefix from gridprefix ".
		"where CONTAINS(geometry_boundary, GeomFromText('POINT($x $y)')) ".
		"and (origin_x > $x_lim) and (origin_y > $y_lim) ".
		"and reference_index=$reference_index";
	$prefix=$db->GetOne($sql);
	#$sql="select prefix from gridprefix ".
	#	"where $x between origin_x and (origin_x+width-1) and ".
	#	"$y between origin_y and (origin_y+height-1) and reference_index=$reference_index";
	#$prefix=$db->GetOne($sql);

	$eastings = sprintf("%05d",($e+ 500000) % 100000); //cope with negative! (for Rockall...)
	$northings = sprintf("%05d",$n % 100000);
	

	if ($gr_length) {
		$len = intval($gr_length/2);
	} else {
		//try to work out the shortest grid ref length
		$east = preg_replace("/^(\d+?)0*$/",'$1',$eastings);
		$north = preg_replace("/^(\d+?)0*$/",'$1',$northings);
		$len = max(strlen($east),strlen($north),2);
	}
	
	$eastings = substr($eastings,0,$len);
	$northings = substr($northings,0,$len);
	if ($spaced) {
		return array("$prefix $eastings $northings",$len);
	} else {
		return array($prefix.$eastings.$northings,$len);
	}
}

//use:    list($x,$y) = national_to_internal($e,$n,$reference_index );

function national_to_internal($e,$n,$reference_index ) {
	global $CONF;
	$x = intval($e / 1000);
	$y = intval($n / 1000);
	
	//add the internal origin
	$x += $CONF['origins'][$reference_index][0];
	$y += $CONF['origins'][$reference_index][1];
	return array($x,$y);
}


//use:    list($e,$n,$reference_index) = internal_to_national($x,$y,$reference_index = 0);
// note gridsquare has its own version that takes into account the userspecified easting/northing
function internal_to_national($x,$y,$reference_index = 0) {
	global $CONF;
	if (!$reference_index) {
		$db = $this->_getDB();
		
		$reference_index=$db->GetOne("select reference_index from gridsquare where CONTAINS( GeomFromText('POINT($x $y)'),point_xy )");
		
		//But what to do when the square is not on land??
		
		if (!$reference_index) {
			//when not on land just try any square!
			// but favour the _smaller_ grid - works better, but still not quite right where the two grids almost overlap
			$where_crit =  "order by reference_index desc";
			$x_lim=$x-100;
			$y_lim=$y-100;
		
			#$sql="select reference_index from gridprefix ".
			#	"where $x between origin_x and (origin_x+width-1) and ".
			#	"$y between origin_y and (origin_y+height-1) $where_crit";
			$sql="select reference_index from gridprefix ".
				"where CONTAINS(geometry_boundary, GeomFromText('POINT($x $y)')) and (origin_x > $x_lim) and (origin_y > $y_lim) ".
				$where_crit;
			$reference_index=$db->GetOne($sql);
		}
	}

	if ($reference_index) {
		//remove the internal origin
		$x -= $CONF['origins'][$reference_index][0];
		$y -= $CONF['origins'][$reference_index][1];

		//lets position the national coords in the center of the square!
		$e = intval($x * 1000 + 500);
		$n = intval($y * 1000 + 500);
		return array($e,$n,$reference_index);
	} else {
		return array();
	}
}


//use:    list($x,$y,$reference_index) = gridref_to_internal($gr);

//use:    list($e,$n,$reference_index) = gridref_to_national($gr);

//use:	list($x,$y) = alignInternalToNationalLines($x,$y,$reference_index = 0);
	 //reference_index is optional as we can duduce this
	 // for mosaic->setAlignedOrigin to handle the hardcoded alignments

//use:	list($e,$n) = osgb36_to_irish($e,$n);
			// this is used when we have a dataset in osgb and need to convert it to irish national (eg loc_placenames etc)

function wgs84_to_friendly($lat,$long) {
	$el = ($long > 0)?'E':'W';
	$nl = ($lat > 0)?'N':'S';
	
	$xd = intval(abs($long));
	$xm = intval((abs($long)-$xd)*60);
	$xs = (abs($long)*3600)-($xm*60)-($xd*3600);

	$yd = intval(abs($lat));
	$ym = intval((abs($lat)-$yd)*60);
	$ys = (abs($lat)*3600)-($ym*60)-($yd*3600);

	$ymd = sprintf("%.4f",$ym+($ys/60));
	$xmd = sprintf("%.4f",$xm+($xs/60));
	
	return array("$yd:$ymd$nl","$xd:$xmd$el");
}

function wgs84_to_friendly_smarty_parts($lat,$long,&$smarty) {
	$el = ($long > 0)?'E':'W';
	$nl = ($lat > 0)?'N':'S';
	
	$along = abs($long);
	$alat = abs($lat);
	
	$xd = intval($along);
	$xm = intval(($along-$xd)*60);
	$xs = ($along*3600)-($xm*60)-($xd*3600);

	$yd = intval($alat);
	$ym = intval(($alat-$yd)*60);
	$ys = ($alat*3600)-($ym*60)-($yd*3600);

	$ymd = sprintf("%.4f",$ym+($ys/60));
	$xmd = sprintf("%.4f",$xm+($xs/60));
	
	$xs = sprintf("%.5f",$xs);
	$ys = sprintf("%.5f",$ys);
	
	foreach (array('el','nl','along','alat','xd','xm','xs','yd','ym','ys','ymd','xmd') as $name) {
		$smarty->assign($name, $$name);
	}
	$smarty->assign('latdm', "$yd:$ymd$nl");
	$smarty->assign('longdm', "$xd:$xmd$el");
}

# great circle distance in m
function distance ($lat1, $lon1, $lat2, $lon2) {
	return (6371000*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180);
}

/**************************
* Irish Functions
***************************/

#source http://www.osni.gov.uk/downloads/Making%20maps%20GPS%20compatible.pdf 
#Translations Rotations
#?X (m) +482.530 ?x (”) +1.042
#?Y (m) -130.596 ?y (”) +0.214
#?Z (m) +564.557 ?z (”) +0.631
#Scale (ppm) +8.150

#source of ellipsoid axis dimensions a,b : http://www.osni.gov.uk/technical/grid.doc


function wgs84_to_irish($lat,$long,$uselevel2 = true) {
    $height = 0;

	if ($uselevel2) {
		//Level 2 Transformation - 95% of points should fall within 40 cm
		$x1 = $this->Lat_Long_H_to_X($lat,$long,$height,6378137.00,6356752.313);
		$y1 = $this->Lat_Long_H_to_Y($lat,$long,$height,6378137.00,6356752.313);
		$z1 = $this->Lat_H_to_Z     ($lat,      $height,6378137.00,6356752.313);

		$x2 = $this->Helmert_X($x1,$y1,$z1,-482.53 ,-0.214,-0.631,-8.15);
		$y2 = $this->Helmert_Y($x1,$y1,$z1, 130.596,-1.042,-0.631,-8.15);
		$z2 = $this->Helmert_Z($x1,$y1,$z1,-564.557,-1.042,-0.214,-8.15);

		$lat  = $this->XYZ_to_Lat ($x2,$y2,$z2,6377340.189,6356034.447);
		$long = $this->XYZ_to_Long($x2,$y2);
	} 

    $e = $this->Lat_Long_to_East ($lat,$long,6377340.189,6356034.447, 200000,1.000035,53.50000,-8.00000);
    $n = $this->Lat_Long_to_North($lat,$long,6377340.189,6356034.447, 200000,250000,1.000035,53.50000,-8.00000);

	if (!$uselevel2) {
		//Level 1 Transformation - 95% of points within 2 metres
		#fixed datum shift correction (instead of fancy hermert translation above!)
		##source http://www.osni.gov.uk/downloads/Making%20maps%20GPS%20compatible.pdf
		$e=$e+49;
		$n=$n-23.4;
	}

    return array($e,$n);
}




function irish_to_wgs84($e,$n,$uselevel2 = true) {
    $height = 0;

	if (!$uselevel2) {
		#fixed datum shift correction (instead of fancy hermert translation below!)
		$e = $e-49;
		$n = $n+23.4;
	}

    $lat = $this->E_N_to_Lat ($e,$n,6377340.189,6356034.447,200000,250000,1.000035,53.50000,-8.00000);
    $lon = $this->E_N_to_Long($e,$n,6377340.189,6356034.447,200000,250000,1.000035,53.50000,-8.00000);

	if ($uselevel2) {
		$x1 = $this->Lat_Long_H_to_X($lat,$lon,$height,6377340.189,6356034.447);
		$y1 = $this->Lat_Long_H_to_Y($lat,$lon,$height,6377340.189,6356034.447);
		$z1 = $this->Lat_H_to_Z     ($lat,     $height,6377340.189,6356034.447);

		$x2 = $this->Helmert_X($x1,$y1,$z1, 482.53 ,0.214,0.631,8.15);
		$y2 = $this->Helmert_Y($x1,$y1,$z1,-130.596,1.042,0.631,8.15);
		$z2 = $this->Helmert_Z($x1,$y1,$z1, 564.557,1.042,0.214,8.15);

		$lat  = $this->XYZ_to_Lat ($x2,$y2,$z2,6378137.000,6356752.313);
		$lon  = $this->XYZ_to_Long($x2,$y2);
	} 

    return array($lat,$lon);
}

/**************************
* OSGB Functions
***************************/

		#-===-
		#ETRS89 (WGS84) to OSGB36/ODN Helmert transformation  
		#  X(m)     Y(m)     Z(m)     s(PPM)  X(sec)  Y(sec)  X(sec)  
		#-446.448 +125.157 -542.060 +20.4894 -0.1502 -0.2470 -0.8421 

function wgs84_to_osgb36($lat,$long) {
    $height = 0;

    $x1 = $this->Lat_Long_H_to_X($lat,$long,$height,6378137.00,6356752.313);
    $y1 = $this->Lat_Long_H_to_Y($lat,$long,$height,6378137.00,6356752.313);
    $z1 = $this->Lat_H_to_Z     ($lat,      $height,6378137.00,6356752.313);
    
    $x2 = $this->Helmert_X($x1,$y1,$z1,-446.448,-0.2470,-0.8421,20.4894);
    $y2 = $this->Helmert_Y($x1,$y1,$z1, 125.157,-0.1502,-0.8421,20.4894);
    $z2 = $this->Helmert_Z($x1,$y1,$z1,-542.060,-0.1502,-0.2470,20.4894);
    
    $lat2  = $this->XYZ_to_Lat ($x2,$y2,$z2,6377563.396,6356256.910);
    $long2 = $this->XYZ_to_Long($x2,$y2); 
    
    $e = $this->Lat_Long_to_East ($lat,$long,6377563.396,6356256.910,400000,0.999601272,49.00000,-2.00000);
    $n = $this->Lat_Long_to_North($lat,$long,6377563.396,6356256.910,400000,-100000,0.999601272,49.00000,-2.00000);
    
    return array($e,$n);
}

function osgb36_to_wgs84($e,$n) {
    $height = 0;

    $lat1 = $this->E_N_to_Lat ($e,$n,6377563.396,6356256.910,400000,-100000,0.999601272,49.00000,-2.00000);
    $lon1 = $this->E_N_to_Long($e,$n,6377563.396,6356256.910,400000,-100000,0.999601272,49.00000,-2.00000);
	
	$x1 = $this->Lat_Long_H_to_X($lat1,$lon1,$height,6377563.396,6356256.910);
	$y1 = $this->Lat_Long_H_to_Y($lat1,$lon1,$height,6377563.396,6356256.910);
	$z1 = $this->Lat_H_to_Z     ($lat1,      $height,6377563.396,6356256.910);

	$x2 = $this->Helmert_X($x1,$y1,$z1,446.448 ,0.2470,0.8421,-20.4894);
	$y2 = $this->Helmert_Y($x1,$y1,$z1,-125.157,0.1502,0.8421,-20.4894);
	$z2 = $this->Helmert_Z($x1,$y1,$z1,542.060 ,0.1502,0.2470,-20.4894);

	$lat = $this->XYZ_to_Lat($x2,$y2,$z2,6378137.000,6356752.313);
	$lon = $this->XYZ_to_Long($x2,$y2);

    return array($lat,$lon);
}


function osgb36_to_gridref($e,$n) {
    $codes = array(
		array ('SV','SW','SX','SY','SZ','TV','TW'), 
        array ('SQ','SR','SS','ST','SU','TQ','TR'),
        array ('SL','SM','SN','SO','SP','TL','TM'),
        array ('SF','SG','SH','SJ','SK','TF','TG'),
        array ('SA','SB','SC','SD','SE','TA','TB'),
        array ('NV','NW','NX','NY','NZ','OV','OW'),
        array ('NQ','NR','NS','NT','NU','OQ','OR'),
        array ('NL','NM','NN','NO','NP','OL','OM'),
        array ('NF','NG','NH','NJ','NK','OF','OG'),
        array ('NA','NB','NC','ND','NE','OA','OB'),
        array ('HV','HW','HX','HY','HZ','JV','JW'),
        array ('HQ','HR','HS','HT','HU','JQ','JR'),
        array ('HL','HM','HN','HO','HP','JL','JM'),
               );

    $ref = sprintf ("%s %05d %05d", $codes[intval($n/100000)][intval($e/100000)],fmod($e,100000), fmod($n,100000)) ;
	return $ref;
}

/**************************
* General Functions
***************************/

function E_N_to_Lat($East, $North, $a, $b, $e0, $n0, $f0, $PHI0, $LAM0) {
	#Un-project Transverse Mercator eastings and northings back to latitude.
	#Input: - _
	#eastings (East) and northings (North) in meters; _
	#ellipsoid axis dimensions (a & b) in meters; _
	#eastings (e0) and northings (n0) of false origin in meters; _
	#central meridian scale factor (f0) and _
	#latitude (PHI0) and longitude (LAM0) of false origin in decimal degrees.

	#'REQUIRES THE "Marc" AND "InitialLat" FUNCTIONS

	#Convert angle measures to radians
    $Pi = 3.14159265358979;
    $RadPHI0 = $PHI0 * ($Pi / 180);
    $RadLAM0 = $LAM0 * ($Pi / 180);

	#Compute af0, bf0, e squared (e2), n and Et
    $af0 = $a * $f0;
    $bf0 = $b * $f0;
    $e2 = (pow($af0,2) - pow($bf0,2)) / pow($af0,2);
    $n = ($af0 - $bf0) / ($af0 + $bf0);
    $Et = $East - $e0;

	#Compute initial value for latitude (PHI) in radians
    $PHId = $this->InitialLat($North, $n0, $af0, $RadPHI0, $n, $bf0);
    
	#Compute nu, rho and eta2 using value for PHId
    $nu = $af0 / (sqrt(1 - ($e2 * ( pow(Sin($PHId),2)))));
    $rho = ($nu * (1 - $e2)) / (1 - ($e2 * pow(Sin($PHId),2)));
    $eta2 = ($nu / $rho) - 1;
    
	#Compute Latitude
    $VII = (tan($PHId)) / (2 * $rho * $nu);
    $VIII = ((tan($PHId)) / (24 * $rho * pow($nu,3))) * (5 + (3 * (pow(tan($PHId),2))) + $eta2 - (9 * $eta2 * (pow(tan($PHId),2))));
    $IX = ((tan($PHId)) / (720 * $rho * pow($nu,5))) * (61 + (90 * (pow(tan($PHId),2))) + (45 * (pow(tan($PHId),4))));
    
    $E_N_to_Lat = (180 / $Pi) * ($PHId - (pow($Et,2) * $VII) + (pow($Et,4) * $VIII) - (pow($Et,6) * $IX));
	return ($E_N_to_Lat);
}

function E_N_to_Long($East, $North, $a, $b, $e0, $n0, $f0, $PHI0, $LAM0) {
	#Un-project Transverse Mercator eastings and northings back to longitude.
	#Input: - _
	#eastings (East) and northings (North) in meters; _
	#ellipsoid axis dimensions (a & b) in meters; _
	#eastings (e0) and northings (n0) of false origin in meters; _
	#central meridian scale factor (f0) and _
	#latitude (PHI0) and longitude (LAM0) of false origin in decimal degrees.

	#REQUIRES THE "Marc" AND "InitialLat" FUNCTIONS

	#Convert angle measures to radians
    $Pi = 3.14159265358979;
    $RadPHI0 = $PHI0 * ($Pi / 180);
    $RadLAM0 = $LAM0 * ($Pi / 180);

	#Compute af0, bf0, e squared (e2), n and Et
    $af0 = $a * $f0;
    $bf0 = $b * $f0;
    $e2 = (pow($af0,2) - pow($bf0,2)) / pow($af0,2);
    $n = ($af0 - $bf0) / ($af0 + $bf0);
    $Et = $East - $e0;

	#Compute initial value for latitude (PHI) in radians
    $PHId = $this->InitialLat($North, $n0, $af0, $RadPHI0, $n, $bf0);
    
	#Compute nu, rho and eta2 using value for PHId
    $nu = $af0 / (sqrt(1 - ($e2 * (pow(sin($PHId),2)))));
    $rho = ($nu * (1 - $e2)) / (1 - ($e2 * pow(Sin($PHId),2)));
    $eta2 = ($nu / $rho) - 1;

	#Compute Longitude
    $X = (pow(cos($PHId),-1)) / $nu;
    $XI = ((pow(cos($PHId),-1)) / (6 * pow($nu,3))) * (($nu / $rho) + (2 * (pow(tan($PHId),2))));
    $XII = ((pow(cos($PHId),-1)) / (120 * pow($nu,5))) * (5 + (28 * (pow(tan($PHId),2))) + (24 * (pow(tan($PHId),4))));
    $XIIA = ((pow(Cos($PHId),-1)) / (5040 * pow($nu,7))) * (61 + (662 * (pow(tan($PHId),2))) + (1320 * (pow(Tan($PHId),4))) + (720 * (pow(tan($PHId),6))));

    $E_N_to_Long = (180 / $Pi) * ($RadLAM0 + ($Et * $X) - (pow($Et,3) * $XI) + (pow($Et,5) * $XII) - (pow($Et,7) * $XIIA));
	return $E_N_to_Long;
}

function InitialLat($North, $n0, $afo, $PHI0, $n, $bfo) {
	#Compute initial value for Latitude (PHI) IN RADIANS.
	#Input: - _
	#northing of point (North) and northing of false origin (n0) in meters; _
	#semi major axis multiplied by central meridian scale factor (af0) in meters; _
	#latitude of false origin (PHI0) IN RADIANS; _
	#n (computed from a, b and f0) and _
	#ellipsoid semi major axis multiplied by central meridian scale factor (bf0) in meters.
 
	#REQUIRES THE "Marc" FUNCTION
	#THIS FUNCTION IS CALLED BY THE "E_N_to_Lat", "E_N_to_Long" and "E_N_to_C" FUNCTIONS
	#THIS FUNCTION IS ALSO USED ON IT'S OWN IN THE  "Projection and Transformation Calculations.xls" SPREADSHEET

	#First PHI value (PHI1)
    $PHI1 = (($North - $n0) / $afo) + $PHI0;
    
	#Calculate M
    $M = $this->Marc($bfo, $n, $PHI0, $PHI1);
    
	#Calculate new PHI value (PHI2)
    $PHI2 = (($North - $n0 - $M) / $afo) + $PHI1;
    
	#Iterate to get final value for InitialLat
	While (abs($North - $n0 - $M) > 0.00001) {
        $PHI2 = (($North - $n0 - $M) / $afo) + $PHI1;
        $M = $this->Marc($bfo, $n, $PHI0, $PHI2);
        $PHI1 = $PHI2;
	}    
    return $PHI2;
}



function Lat_Long_H_to_X ($PHI, $LAM, $H, $a, $b) {
# Convert geodetic coords lat (PHI), long (LAM) and height (H) to cartesian X coordinate.
# Input: - _
#    Latitude (PHI)& Longitude (LAM) both in decimal degrees; _
#  Ellipsoidal height (H) and ellipsoid axis dimensions (a & b) all in meters.
    
# Convert angle measures to radians
    $Pi = 3.14159265358979;
    $RadPHI = $PHI * ($Pi / 180);
    $RadLAM = $LAM * ($Pi / 180);

# Compute eccentricity squared and nu
    $e2 = (pow($a,2) - pow($b,2)) / pow($a,2);
    $V = $a / (sqrt(1 - ($e2 * (  pow(sin($RadPHI),2)))));

# Compute X
    return ($V + $H) * (cos($RadPHI)) * (cos($RadLAM));
}


function Lat_Long_H_to_Y ($PHI, $LAM, $H, $a, $b) {
# Convert geodetic coords lat (PHI), long (LAM) and height (H) to cartesian Y coordinate.
# Input: - _
# Latitude (PHI)& Longitude (LAM) both in decimal degrees; _
# Ellipsoidal height (H) and ellipsoid axis dimensions (a & b) all in meters.

# Convert angle measures to radians
    $Pi = 3.14159265358979;
    $RadPHI = $PHI * ($Pi / 180);
    $RadLAM = $LAM * ($Pi / 180);

# Compute eccentricity squared and nu
    $e2 = (pow($a,2) - pow($b,2)) / pow($a,2);
    $V = $a / (sqrt(1 - ($e2 * (  pow(sin($RadPHI),2))) ));

# Compute Y
    return ($V + $H) * (cos($RadPHI)) * (sin($RadLAM));
}


function Lat_H_to_Z ($PHI, $H, $a, $b) {
# Convert geodetic coord components latitude (PHI) and height (H) to cartesian Z coordinate.
# Input: - _
#    Latitude (PHI) decimal degrees; _
# Ellipsoidal height (H) and ellipsoid axis dimensions (a & b) all in meters.

# Convert angle measures to radians
    $Pi = 3.14159265358979;
    $RadPHI = $PHI * ($Pi / 180);

# Compute eccentricity squared and nu
    $e2 = (pow($a,2) - pow($b,2)) / pow($a,2);
    $V = $a / (sqrt(1 - ($e2 * (  pow(sin($RadPHI),2)) )));

# Compute X
    return (($V * (1 - $e2)) + $H) * (sin($RadPHI));
}


function Helmert_X ($X,$Y,$Z,$DX,$Y_Rot,$Z_Rot,$s) {

# (X, Y, Z, DX, Y_Rot, Z_Rot, s)
# Computed Helmert transformed X coordinate.
# Input: - _
#    cartesian XYZ coords (X,Y,Z), X translation (DX) all in meters ; _
# Y and Z rotations in seconds of arc (Y_Rot, Z_Rot) and scale in ppm (s).

# Convert rotations to radians and ppm scale to a factor
$Pi = 3.14159265358979;
$sfactor = $s * 0.000001;

$RadY_Rot = ($Y_Rot / 3600) * ($Pi / 180);

$RadZ_Rot = ($Z_Rot / 3600) * ($Pi / 180);
    
#Compute transformed X coord
    return  ($X + ($X * $sfactor) - ($Y * $RadZ_Rot) + ($Z * $RadY_Rot) + $DX);
}


function Helmert_Y ($X,$Y,$Z,$DY,$X_Rot,$Z_Rot,$s) {
# (X, Y, Z, DY, X_Rot, Z_Rot, s)
# Computed Helmert transformed Y coordinate.
# Input: - _
#    cartesian XYZ coords (X,Y,Z), Y translation (DY) all in meters ; _
#  X and Z rotations in seconds of arc (X_Rot, Z_Rot) and scale in ppm (s).
 
# Convert rotations to radians and ppm scale to a factor
$Pi = 3.14159265358979;
$sfactor = $s * 0.000001;
$RadX_Rot = ($X_Rot / 3600) * ($Pi / 180);
$RadZ_Rot = ($Z_Rot / 3600) * ($Pi / 180);
    
# Compute transformed Y coord
return ($X * $RadZ_Rot) + $Y + ($Y * $sfactor) - ($Z * $RadX_Rot) + $DY;

}


function Helmert_Z ($X, $Y, $Z, $DZ, $X_Rot, $Y_Rot, $s) {

# (X, Y, Z, DZ, X_Rot, Y_Rot, s)
# Computed Helmert transformed Z coordinate.
# Input: - _
#    cartesian XYZ coords (X,Y,Z), Z translation (DZ) all in meters ; _
# X and Y rotations in seconds of arc (X_Rot, Y_Rot) and scale in ppm (s).
# 
# Convert rotations to radians and ppm scale to a factor
$Pi = 3.14159265358979;
$sfactor = $s * 0.000001;
$RadX_Rot = ($X_Rot / 3600) * ($Pi / 180);
$RadY_Rot = ($Y_Rot / 3600) * ($Pi / 180);
    
# Compute transformed Z coord
return (-1 * $X * $RadY_Rot) + ($Y * $RadX_Rot) + $Z + ($Z * $sfactor) + $DZ;
} 





function XYZ_to_Lat ($X, $Y, $Z, $a, $b) {
# Convert XYZ to Latitude (PHI) in Dec Degrees.
# Input: - _
# XYZ cartesian coords (X,Y,Z) and ellipsoid axis dimensions (a & b), all in meters.

# THIS FUNCTION REQUIRES THE "Iterate_XYZ_to_Lat" FUNCTION
# THIS FUNCTION IS CALLED BY THE "XYZ_to_H" FUNCTION

    $RootXYSqr = sqrt(pow($X,2) + pow($Y,2));
    $e2 = (pow($a,2) - pow($b,2)) / pow($a,2);
    $PHI1 = atan2 ($Z , ($RootXYSqr * (1 - $e2)) );
    
    $PHI = $this->Iterate_XYZ_to_Lat($a, $e2, $PHI1, $Z, $RootXYSqr);
    
    $Pi = 3.14159265358979;
    
    return $PHI * (180 / $Pi);
    }


function Iterate_XYZ_to_Lat ($a, $e2, $PHI1, $Z, $RootXYSqr) {
# Iteratively computes Latitude (PHI).
# Input: - _
#    ellipsoid semi major axis (a) in meters; _
#    eta squared (e2); _
#    estimated value for latitude (PHI1) in radians; _
#    cartesian Z coordinate (Z) in meters; _
# RootXYSqr computed from X & Y in meters.

# THIS FUNCTION IS CALLED BY THE "XYZ_to_PHI" FUNCTION
# THIS FUNCTION IS ALSO USED ON IT'S OWN IN THE _
# "Projection and Transformation Calculations.xls" SPREADSHEET


    $V = $a / (sqrt(1 - ($e2 * pow(sin($PHI1),2))));
    $PHI2 = atan2(($Z + ($e2 * $V * (sin($PHI1)))) , $RootXYSqr);
    
    while (abs($PHI1 - $PHI2) > 0.000000001) {
        $PHI1 = $PHI2;
        $V = $a / (sqrt(1 - ($e2 * pow(sin($PHI1),2))));
        $PHI2 = atan2(($Z + ($e2 * $V * (sin($PHI1)))) , $RootXYSqr);
    }

    return $PHI2;
}


function XYZ_to_Long ($X, $Y) {
# Convert XYZ to Longitude (LAM) in Dec Degrees.
# Input: - _
# X and Y cartesian coords in meters.

    $Pi = 3.14159265358979;
    return atan2($Y , $X) * (180 / $Pi);
}


function XYZ_to_H ($X, $Y, $Z, $a, $b) {
# Convert XYZ to Ellipsoidal Height.
# Input: - _
# XYZ cartesian coords (X,Y,Z) and ellipsoid axis dimensions (a & b), all in meters.

# REQUIRES THE "XYZ_to_Lat" FUNCTION

# Compute PHI (Dec Degrees) first
    $PHI = $this->XYZ_to_Lat($X, $Y, $Z, $a, $b);

#Convert PHI radians
    $Pi = 3.14159265358979;
    $RadPHI = $PHI * ($Pi / 180);
    
# Compute H
    $RootXYSqr = sqrt(pow($X,2) + pow($Y,2));
    $e2 = (pow($a,2) - pow($b,2)) / pow($a,2);
    $V = $a / (sqrt(1 - ($e2 * pow(sin($RadPHI),2))));
    $H = ($RootXYSqr / cos($RadPHI)) - $V;
    
    return $H;
}



function Lat_Long_to_East ($PHI, $LAM, $a, $b, $e0, $f0, $PHI0, $LAM0) {
#Project Latitude and longitude to Transverse Mercator eastings.
#Input: - _
#    Latitude (PHI) and Longitude (LAM) in decimal degrees; _
#    ellipsoid axis dimensions (a & b) in meters; _
#    eastings of false origin (e0) in meters; _
#    central meridian scale factor (f0); _
# latitude (PHI0) and longitude (LAM0) of false origin in decimal degrees.

# Convert angle measures to radians
    $Pi = 3.14159265358979;
    $RadPHI = $PHI * ($Pi / 180);
    $RadLAM = $LAM * ($Pi / 180);
    $RadPHI0 = $PHI0 * ($Pi / 180);
    $RadLAM0 = $LAM0 * ($Pi / 180);

    $af0 = $a * $f0;
    $bf0 = $b * $f0;
    $e2 = (pow($af0,2) - pow($bf0,2)) / pow($af0,2);
    $n = ($af0 - $bf0) / ($af0 + $bf0);
    $nu = $af0 / (sqrt(1 - ($e2 * pow(sin($RadPHI),2) )));
    $rho = ($nu * (1 - $e2)) / (1 - ($e2 * pow(sin($RadPHI),2) ));
    $eta2 = ($nu / $rho) - 1;
    $p = $RadLAM - $RadLAM0;
    
    $IV = $nu * (cos($RadPHI));
    $V = ($nu / 6) * ( pow(cos($RadPHI),3)) * (($nu / $rho) - (pow(tan($RadPHI),2)));
    $VI = ($nu / 120) * (pow(cos($RadPHI),5)) * (5 - (18 * (pow(tan($RadPHI),2))) + (pow(tan($RadPHI),4)) + (14 * $eta2) - (58 * (pow(tan($RadPHI),2)) * $eta2));
    
    return $e0 + ($p * $IV) + (pow($p,3) * $V) + (pow($p,5) * $VI);
}


function Lat_Long_to_North ($PHI, $LAM, $a, $b, $e0, $n0, $f0, $PHI0, $LAM0) {
# Project Latitude and longitude to Transverse Mercator northings
# Input: - _
# Latitude (PHI) and Longitude (LAM) in decimal degrees; _
# ellipsoid axis dimensions (a & b) in meters; _
# eastings (e0) and northings (n0) of false origin in meters; _
# central meridian scale factor (f0); _
# latitude (PHI0) and longitude (LAM0) of false origin in decimal degrees.

# REQUIRES THE "Marc" FUNCTION

# Convert angle measures to radians
    $Pi = 3.14159265358979;
    $RadPHI = $PHI * ($Pi / 180);
    $RadLAM = $LAM * ($Pi / 180);
    $RadPHI0 = $PHI0 * ($Pi / 180);
    $RadLAM0 = $LAM0 * ($Pi / 180);
    
    $af0 = $a * $f0;
    $bf0 = $b * $f0;
    $e2 = (pow($af0,2) - pow($bf0,2)) / pow($af0,2);
    $n = ($af0 - $bf0) / ($af0 + $bf0);
    $nu = $af0 / (sqrt(1 - ($e2 * pow(sin($RadPHI),2))));
    $rho = ($nu * (1 - $e2)) / (1 - ($e2 * pow(sin($RadPHI),2)));
    $eta2 = ($nu / $rho) - 1;
    $p = $RadLAM - $RadLAM0;
    $M = $this->Marc($bf0, $n, $RadPHI0, $RadPHI);
    
    $I = $M + $n0;
    $II = ($nu / 2) * (sin($RadPHI)) * (cos($RadPHI));
    $III = (($nu / 24) * (sin($RadPHI)) * (pow(cos($RadPHI),3))) * (5 - (pow(tan($RadPHI),2)) + (9 * $eta2));
    $IIIA = (($nu / 720) * (sin($RadPHI)) * (pow(cos($RadPHI),5))) * (61 - (58 * (pow(tan($RadPHI),2))) + (pow(tan($RadPHI),4)));
    
    return $I + (pow($p,2) * $II) + (pow($p,4) * $III) + (pow($p,6) * $IIIA);
}
   




function Marc ($bf0, $n, $PHI0, $PHI) {
#Compute meridional arc.
#Input: - _
# ellipsoid semi major axis multiplied by central meridian scale factor (bf0) in meters; _
# n (computed from a, b and f0); _
# lat of false origin (PHI0) and initial or final latitude of point (PHI) IN RADIANS.

#THIS FUNCTION IS CALLED BY THE - _
# "Lat_Long_to_North" and "InitialLat" FUNCTIONS
# THIS FUNCTION IS ALSO USED ON IT'S OWN IN THE "Projection and Transformation Calculations.xls" SPREADSHEET

    return $bf0 * (((1 + $n + ((5 / 4) * pow($n,2)) + ((5 / 4) * pow($n,3))) * ($PHI - $PHI0)) - (((3 * $n) + (3 * pow($n,2)) + ((21 / 8) * pow($n,3))) * (sin($PHI - $PHI0)) * (cos($PHI + $PHI0))) + ((((15 / 8
) * pow($n,2)) + ((15 / 8) * pow($n,3))) * (sin(2 * ($PHI - $PHI0))) * (cos(2 * ($PHI + $PHI0)))) - (((35 / 24) * pow($n,3)) * (sin(3 * ($PHI - $PHI0))) * (cos(3 * ($PHI + $PHI0)))));
}

}