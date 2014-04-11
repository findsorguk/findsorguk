<?php
/* SCRIPT: gridcalc.class.php
* PURPOSE: Calculate derivative information on OS grid references
* MODULE: map
* ASSUMPTIONS:
* 	Requires logger.class.php; instance already declared as '$logger'
* © Oxford ArchDigital Ltd. 2001-2002
*/
class Pas_View_Helper_Gridcalc extends Zend_View_Helper_Abstract

{
private function stripgrid($string=""){
	$stripOut = array(" ","-","-",".");
	$gridRef = str_replace($stripOut,"",$string);
	$gridRef = strtoupper($gridRef);
	return $gridRef;
}

private function get_accuracy($gridref,$clean=1){

	if ($clean == 1){$gridref = $this->stripgrid($gridref);}
	$coordCount = strlen($gridref)-2; //count length and strip off fist two characters

	switch ($coordCount) {
		case 0:
			$acc = 100000;
			break;
		case 2:
			$acc = 10000;
			break;
		case 4:
			$acc = 1000;
			break; 
		case 6:
			$acc = 100;
			break;
		case 8:
			$acc = 10;
			break;
		case 10:
			$acc = 1;
			break;
		case 12:
			$acc = 0.1;
			break;
		case 14:
			$acc = 0.01;
			break;
		default:
			return false;
			break;
	}		
	
	$gridAcc = $acc;
	return $acc;	
}

private function FourFigure($gridref,$digits=4) {
//clean grid reference
	$cleangrid = $this->stripgrid($gridref);

	$letterpair = substr($cleangrid,0,2); //strips off first two characters as National grid has 2 let
	$letterpair = strtoupper($letterpair); //transform smallcase to capital
	
	$rawcount = strlen($cleangrid);//gets length of string
	$coordcount = $rawcount-2;//simple math to get coord count
	
	if ($digits > $coordcount){$digits = $coordcount;} //keeps digits sensible
		
	$gridcoords = substr($cleangrid,2,$coordcount);	//isolates the grid numbers
	
	$fromEach = round($digits/2);	//number of digits to grab from E and N each
	
	$halfcount = $coordcount/2; //get half of number of digits
	$eastcoord = (substr($gridcoords,0,$fromEach));  //get inital easting without grid square
	$northcoord = (substr($gridcoords,$halfcount,$fromEach)); //get inital northing without grid square
	
	$fourDigit = $letterpair.$eastcoord.$northcoord;
	return $fourDigit;
	}

private function countcheck($rawcount){
//called internally to see whether grid ref has correct number of digits
	
	if(is_int($rawcount/2)) 
		return true;
	else {
	echo 'Grid Reference not available';}
}

public function GridCalc($gridref, $round="") {


//Convert from OSGB to XY
//$rawgrid = grid reference
//$OSgridSystem : assumes OSGB.  Use "NI" as parameter for Northern Ireland
	//set current module for LOGGER
	
	//get grid units
	$unit = 'm';
	
	//clean grid reference
	$cleangrid = $this->stripgrid($gridref);
	
	//gets length of string
	$rawcount=strlen($cleangrid);
	
	//check count of grid refs
	$countCheck = $this->countcheck($rawcount);
	if (!$countCheck) {return FALSE;}
	
	$letterpair = substr($cleangrid,0,2); //strips off characters

	$coordcount=$rawcount-2;//simple math to get coord count
	
	$this->coordCount = $coordcount;

	$gridcoords=substr($cleangrid,2,$coordcount);	//isolates the grid numbers
	
		$squareArray["SV"] = array("x" =>0,	"y"=>0);
		$squareArray["SW"] = array("x" =>1,	"y"=>0);
		$squareArray["SX"] = array("x" =>2,	"y"=>0);
		$squareArray["SY"] = array("x" =>3,	"y"=>0);
		$squareArray["SZ"] = array("x" =>4,	"y"=>0);
		$squareArray["TV"] = array("x" =>5,	"y"=>0);
		
		$squareArray["SQ"] = array("x" =>0,	"y"=>1);
		$squareArray["SR"] = array("x" =>1,	"y"=>1);
		$squareArray["SS"] = array("x" =>2,	"y"=>1);
		$squareArray["ST"] = array("x" =>3,	"y"=>1);
		$squareArray["SU"] = array("x" =>4,	"y"=>1);
		$squareArray["TQ"] = array("x" =>5,	"y"=>1);
		$squareArray["TR"] = array("x" =>6,	"y"=>1);
			
		$squareArray["SM"] = array("x" =>1,	"y"=>2);
		$squareArray["SN"] = array("x" =>2,	"y"=>2);
		$squareArray["SO"] = array("x" =>3,	"y"=>2);
		$squareArray["SP"] = array("x" =>4,	"y"=>2);
		$squareArray["TL"] = array("x" =>5,	"y"=>2);
		$squareArray["TM"] = array("x" =>6,	"y"=>2);	
		
		$squareArray["SG"] = array("x" =>1,	"y"=>3);
		$squareArray["SH"] = array("x" =>2,	"y"=>3);
		$squareArray["SJ"] = array("x" =>3,	"y"=>3);
		$squareArray["SK"] = array("x" =>4,	"y"=>3);
		$squareArray["TF"] = array("x" =>5,	"y"=>3);
		$squareArray["TG"] = array("x" =>6,	"y"=>3);	
			
		$squareArray["SB"] = array("x" =>1,	"y"=>4);
		$squareArray["SC"] = array("x" =>2,	"y"=>4);
		$squareArray["SD"] = array("x" =>3,	"y"=>4);
		$squareArray["SE"] = array("x" =>4,	"y"=>4);
		$squareArray["TA"] = array("x" =>5,	"y"=>4);
		$squareArray["TB"] = array("x" =>6,	"y"=>4);		
					
		$squareArray["NW"] = array("x" =>1,	"y"=>5);
		$squareArray["NX"] = array("x" =>2,	"y"=>5);
		$squareArray["NY"] = array("x" =>3,	"y"=>5);
		$squareArray["NZ"] = array("x" =>4,	"y"=>5);
		$squareArray["OV"] = array("x" =>5,	"y"=>5);
		$squareArray["OW"] = array("x" =>6,	"y"=>5);	
				
		$squareArray["NQ"] = array("x" =>0,	"y"=>6);			
		$squareArray["NR"] = array("x" =>1,	"y"=>6);
		$squareArray["NS"] = array("x" =>2,	"y"=>6);
		$squareArray["NT"] = array("x" =>3,	"y"=>6);
		$squareArray["NU"] = array("x" =>4,	"y"=>6);
		$squareArray["OQ"] = array("x" =>5,	"y"=>6);
		
		$squareArray["NL"] = array("x" =>0,	"y"=>7);			
		$squareArray["NM"] = array("x" =>1,	"y"=>7);
		$squareArray["NN"] = array("x" =>2,	"y"=>7);
		$squareArray["NO"] = array("x" =>3,	"y"=>7);
		$squareArray["NP"] = array("x" =>4,	"y"=>7);
		$squareArray["OL"] = array("x" =>5,	"y"=>7);
		
		$squareArray["NF"] = array("x" =>0,	"y"=>8);			
		$squareArray["NG"] = array("x" =>1,	"y"=>8);
		$squareArray["NH"] = array("x" =>2,	"y"=>8);
		$squareArray["NJ"] = array("x" =>3,	"y"=>8);
		$squareArray["NK"] = array("x" =>4,	"y"=>8);
		$squareArray["OF"] = array("x" =>5,	"y"=>8);
		
		$squareArray["NA"] = array("x" =>0,	"y"=>9);			
		$squareArray["NB"] = array("x" =>1,	"y"=>9);
		$squareArray["NC"] = array("x" =>2,	"y"=>9);
		$squareArray["ND"] = array("x" =>3,	"y"=>9);
		$squareArray["NE"] = array("x" =>4,	"y"=>9);
		$squareArray["OA"] = array("x" =>5,	"y"=>9);
		
		$squareArray["HV"] = array("x" =>0,	"y"=>10);		
		$squareArray["HW"] = array("x" =>1,	"y"=>10);
		$squareArray["HX"] = array("x" =>2,	"y"=>10);
		$squareArray["HY"] = array("x" =>3,	"y"=>10);
		$squareArray["HZ"] = array("x" =>4,	"y"=>10);
		$squareArray["JV"] = array("x" =>5,	"y"=>10);
			
		$squareArray["HQ"] = array("x" =>0,	"y"=>11);			
		$squareArray["HR"] = array("x" =>1,	"y"=>11);
		$squareArray["HS"] = array("x" =>2,	"y"=>11);
		$squareArray["HT"] = array("x" =>3,	"y"=>11);
		$squareArray["HU"] = array("x" =>4,	"y"=>11);
		$squareArray["JQ"] = array("x" =>5,	"y"=>11);	
					
		$squareArray["HL"] = array("x" =>0,	"y"=>12);			
		$squareArray["HM"] = array("x" =>1,	"y"=>12);
		$squareArray["HN"] = array("x" =>2,	"y"=>12);
		$squareArray["HO"] = array("x" =>3,	"y"=>12);
		$squareArray["HP"] = array("x" =>4,	"y"=>12);
		$squareArray["JL"] = array("x" =>5,	"y"=>12);
						
	
	
	//determine if grid letters are valid
	if(!$squareArray[$letterpair]){
		if (is_numeric($letterpair)){echo " -- Prob. parsing x or y coordinate as grid ref.";}
				return FALSE;
				//echo "No grid reference recorded";
		exit;
	} else {
		$gridSquare = $letterpair;
		
	}

	$halfcount = $coordcount/2; //get half of number of digits
	$eastcoord = (substr($gridcoords,0,$halfcount));  //get inital easting without grid square
	$northcoord = (substr($gridcoords,$halfcount,$halfcount)); //get inital northing without grid square
	
	//Confirm numeric
	if (!is_numeric($eastcoord)){
		
		return FALSE;
	}

	if (!is_numeric($northcoord)){
		return FALSE;
	}

	//combine and cast
	$squareX = $squareArray[$letterpair]["x"];
	$squareY = $squareArray[$letterpair]["y"];
	$noteast = $squareX.$eastcoord;
	$easting = (integer) $noteast;
	$notnorth = $squareY.$northcoord;
	$northing = (integer) $notnorth;

	//get accuracy
	$factor = $this->get_accuracy($cleangrid,0);
	
	$eastresult = ($easting * $factor);
	$northresult = ($northing * $factor);
	
	//get multiplier
	if ($unit == "m"){
		$multiplier = 1;
	} else if ($unit == "km"){
		$multiplier = 0.01;
	} else {
		
		Return FALSE;
	}
	
	//Return Easting and Northing
	$gridX = ($eastresult * $multiplier);
	$gridY = ($northresult * $multiplier);

	
	
	//calculates UK map sheets
	
	//Get 1:10,000 Map Sheet 
	//coords for determining mapquater
	$e2 = substr($easting,2,1);
	$n2 = substr($northing,2,1);
	//coords for determining coord pair
	$e1 = substr($easting,1,1);
	$n1 = substr($northing,1,1);
	
	If ($e2 >= 5) {$ew = "E";} else {$ew = "W";}
	If ($n2 >= 5) {$ns = "N";} else {$ns = "S";}
	$mapQuarter = $ns.$ew;
	$tenKMap = $letterpair.$e1.$n1.$mapQuarter;
	
	//Get 1:2500 Map Sheet
	$eastcoord = substr($easting,0,2);
	$northcoord = substr($northing,0,2);
	$twoPointFiveKMap	= $letterpair.$eastcoord.$northcoord;
	
	$height = 0;
	$e = $gridX;
	$n = $gridY;
    $lat1 = $this->E_N_to_Lat ($e,$n,6377563.396,6356256.910,400000,-100000,0.999601272,49.00000,-2.00000);
    $lon1 = $this->E_N_to_Long($e,$n,6377563.396,6356256.910,400000,-100000,0.999601272,49.00000,-2.00000);
	
	$x1 = $this->Lat_Long_H_to_X($lat1,$lon1,$height,6377563.396,6356256.910);
	$y1 = $this->Lat_Long_H_to_Y($lat1,$lon1,$height,6377563.396,6356256.910);
	$z1 = $this->Lat_H_to_Z     ($lat1,$height,6377563.396,6356256.910);

	$x2 = $this->Helmert_X($x1,$y1,$z1,446.448 ,0.2470,0.8421,-20.4894);
	$y2 = $this->Helmert_Y($x1,$y1,$z1,-125.157,0.1502,0.8421,-20.4894);
	$z2 = $this->Helmert_Z($x1,$y1,$z1,542.060 ,0.1502,0.2470,-20.4894);

	$Lat = $this->XYZ_to_Lat($x2,$y2,$z2,6378137.000,6356752.313);
	$Lon = $this->XYZ_to_Long($x2,$y2);
/*
	//Calculate Lat and Long
		$height = 0;
		$round="5";
		$a = 6377563.396;
        $b = 6356256.91;
        $F0 = 0.9996012717;
        $E0 = 400000;
        $N0 = -100000;
        $PHI0 = 0.85521133347722;
        $LAM0 = -0.034906585039887;
        $aFo = $a * $F0;
        $bFo = $b * $F0;
		$aFo2 = $aFo*$aFo;
		$bFo2 = $bFo*$bFo;
        $e2 = ($aFo2 - $bFo2) / $aFo2;
        $n = ($aFo - $bFo)/($aFo + $bFo);
        $InitPHI = $this->phid($gridX, $N0, $aFo, $PHI0, $n, $bFo);
		$sinInitPHI = Sin($InitPHI);
		$sinInitPHI2 = $sinInitPHI*$sinInitPHI;
		$nuPL = $aFo / pow(1 - ($e2 * $sinInitPHI2),0.5);
		$rhoPL = ($nuPL * (1 - $e2)) / (1 - ($e2 * $sinInitPHI2));
		$eta2PL = ($nuPL / $rhoPL) - 1;
		$M = $this->Marc($bFo, $n, $PHI0, $InitPHI);
		$Et = $gridY - $E0;

	//precalculate
	$CosInitPHI = Cos($InitPHI);
	$TanInitPHI	= Tan($InitPHI);
	$CosInitPHIneg1 = pow($CosInitPHI,-1);
	$TanInitPHI2 = $TanInitPHI*$TanInitPHI;
	$TanInitPHI4 = pow($TanInitPHI,4);
	$TanInitPHI6 = pow($TanInitPHI,6);
	
	//Get LON
	$X = $CosInitPHIneg1 / $nuPL;
	$XI = ($CosInitPHIneg1 / (6 * pow($nuPL,3)) * (($nuPL / $rhoPL) + (2 * pow($TanInitPHI,2))));
	$XII = ($CosInitPHIneg1 / (120 * pow($nuPL,5)) * (5 + (28 * $TanInitPHI2) + (24 * $TanInitPHI4) ));
	$XIIA = ($CosInitPHIneg1 / (5040 * pow($nuPL,7)) * (61 + (662 * $TanInitPHI2) + (1320 * $TanInitPHI4) + (720 * $TanInitPHI6) ));
	$Lon = ($LAM0 + ($Et * $X) - (pow($Et,3) * $XI) + (pow($Et,5) * $XII) - (pow($Et,7) * $XIIA));
	$Lon = rad2deg($Lon);
	
	//Get LAT
	$VII = ($TanInitPHI) / (2 * $nuPL * $rhoPL);
	$VIII = ($TanInitPHI / (24 * $rhoPL * pow($nuPL,3))) * (5 + (3 * $TanInitPHI2) + $eta2PL - (9 * $TanInitPHI2 * $eta2PL)) ;
	$IX = ($TanInitPHI / (720 * $rhoPL * pow($nuPL,5)) * (61 + (90 * $TanInitPHI2 + (45 * $TanInitPHI4)) ));
	$Lat = ($InitPHI - ((pow($Et,2)) * $VII) + ((pow($Et,4)) * $VIII) - ((pow($Et,6)) * $IX));
	$Lat = rad2deg($Lat);
	
	if ($round){
		$Lon = round($Lon,$round);
		$Lat = round($Lat,$round);
	}
	*/	
  
	//////
	
	return ' 25k map: ' . $twoPointFiveKMap . ' 10K map: '. $tenKMap . '<br>
Easting: '. $gridX . ' Northing: ' . $gridY .'<br>Latitude: ' . $Lat . ' Longitude: ' . $Lon;

	
	}
	
/////
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
    $IX = ((tan($PHId)) / (720 * $rho * pow($nu,5))) * (61 + (90 * ((tan($PHId)) ^ 2)) + (45 * (pow(tan($PHId),4))));
    
    $E_N_to_Lat = (180 / $Pi) * ($PHId - (pow($Et,2) * $VII) + (pow($Et,4) * $VIII) - (($Et ^ 6) * $IX));
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






///






private function marc($bFo, $n, $P1, $P2)
{
//Compute meridional arc - only used in other functions
		$n2 = $n*$n;
		$n3 = $n*$n*$n;
        $Marc = $bFo * (((1 + $n + ((5 / 4) * ($n2)) + ((5 / 4) * ($n3))) * ($P2 - $P1)) - (((3 * $n) + (3 * ($n2)) + ((21 / 8) * ($n3))) * (Sin($P2 - $P1)) * (Cos($P2 + $P1))) + ((((15 / 8) * ($n2)) + ((15 / 8) * ($n3))) * (Sin(2 * ($P2 - $P1))) * (Cos(2 * ($P2 + $P1)))) - (((35 / 24) * ($n3)) * (Sin(3 * ($P2 - $P1))) * (Cos(3 * ($P2 + $P1)))));
		return $Marc;
	}
	
function phid($gridX, $N0, $aFo, $PHI0, $n, $bFo)
{
//internal function used in Grid Calc
    $PHI1 = (($gridX - $N0) / $aFo) + $PHI0;
	$M = $this->marc($bFo, $n, $PHI0, $PHI1);
    $PHI2 = (($gridX - $N0 - $M) / $aFo) + $PHI1;
    While (abs($gridX - $N0 - $M) > 0.000000001){
        $PHI2 = (($gridX - $N0 - $M) / $aFo) + $PHI1;
		$M = $this->marc($bFo, $n, $PHI0, $PHI2);
        $PHI1 = $PHI2;
    }
    $PHId = $PHI2;
	return $PHId;
}


		
	




} /* class */
?>
