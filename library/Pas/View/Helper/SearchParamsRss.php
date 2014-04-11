<?php
 class Pas_View_Helper_SearchParamsRss extends Zend_View_Helper_Abstract
 {
 
    
	
function SearchParamsRss($params = NULL)
	{
	unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
$string = '';if($params != NULL) 
{	
	$string .=' RSS feed for search: '; 
//Zend_debug::dump($params);
//Objecttype
if(array_key_exists('objecttype',$params)) {
if($params['objecttype'] != NULL) {
$string .= 'Object type: '. $this->view->escape($params['objecttype']) ;

}
}
//Broadperiod
if(array_key_exists('broadperiod',$params)) {
if($params['broadperiod'] != NULL) {
$string .=' Broadperiod: '. $this->view->escape($params['broadperiod']) ;

}
}
if(array_key_exists('county',$params)) {
if($params['county'] != NULL) {
$string .=' County: '. $this->view->escape($params['county']) ;

}
}
if(array_key_exists('tribe',$params)) {
if($params['tribe'] != NULL) {

$tribe = $params['tribe'];

$tribes = new Tribes();
$tribe = $tribes->fetchRow($tribes->select()->where('id = ?', (int)$tribe));
$string .= 'Iron Age Tribe: ' . $tribe->tribe;
}
}
//region
if(array_key_exists('regionID',$params)) {
if($params['regionID'] != NULL) {

$region = $params['regionID'];
$regions = new Regions();
$regions = $regions->getRegion($region);
$this->regions = $regions;
foreach($this->regions as $region){
$string .=' Region: '. $this->view->escape($region['region']);
}
}

}

if(array_key_exists('material',$params)) {
if($params['material'] != NULL) {

$mat = $params['material'];
$materials = new Materials();
$materials = $materials->getMaterialName($mat);
$this->materials = $materials;
foreach($this->materials as $material){
$string .=' Primary material: '. $this->view->escape($material['term']);
}
}

}


if(array_key_exists('parish',$params)) {
if($params['parish'] != NULL) {
$string .=' Parish: '. $this->view->escape($params['parish']) ;

}
}
if(array_key_exists('district',$params)) {
if($params['district'] != NULL) {
$string .=' District: '. $this->view->escape($params['district']) ;}
}

if(array_key_exists('denomination',$params)) {
$denomname = $params['denomination'];
$denoms = new Denominations();
$denoms = $denoms->getDenomName($denomname);
$this->denoms = $denoms;
foreach($this->denoms as $denom)
{
$string .=' Denomination type: ' . $this->view->escape($denom['denomination']);

}
}

if(array_key_exists('description',$params)) {
if($params['description'] != NULL) {
$string .=' Description contained: '. $this->view->escape($params['description']) ;

}
}

if(array_key_exists('fourFigure',$params)) {
if($params['fourFigure'] != NULL) {
$string .=' Four figure grid reference: '. $this->view->escape($params['fourFigure']) ;

}
}

if(array_key_exists('old_findID',$params)) {
if($params['old_findID'] != NULL) {
$string .=' Find reference number: '. $this->view->escape($params['old_findID']) ;

}
}


if(array_key_exists('fromsubperiod',$params)) {

if ($params['fromsubperiod'] != NULL){

$sub = $params['fromsubperiod'];
if($sub == 1)
{ $string .=' Subperiod: Early';
}
else if ($sub == 2)
{$string .=' Subperiod: Middle';
}
else if ($sub == 3)
{$string .=' Subperiod: Late';
$this->view->headTitle(  ' > Subperiod: Late');
}
}
}

if(array_key_exists('tosubperiod',$params)) {

if ($params['tosubperiod'] != NULL){

$sub = $params['tosubperiod'];
if($sub == 1)
{ $string .=' Subperiod: Early';
$this->view->headTitle(  ' > Subperiod: Early');
}
else if ($sub == 2)
{$string .=' Subperiod: Middle';
}
else if ($sub == 3)
{$string .=' Subperiod: Late';
}
}
}



if(array_key_exists('periodfrom',$params)) {
if($params['periodfrom'] != NULL) {
$period = $params['periodfrom'];
$periods = new Periods();
$periods = $periods->getPeriodName($period);
$this->periods = $periods;
foreach($this->periods as $period)
{
$string .=' Period from: ' . $this->view->escape($period['term']);
}
}
}

//Period to key
if(array_key_exists('periodto',$params)) {
if($params['periodto'] != NULL) {
$period = $params['periodto'];
$periods = new Periods();
$periods = $periods->getPeriodName($period);
$this->periods = $periods;
foreach($this->periods as $period)
{
$string .=' Period to: ' . $this->view->escape($period['term']);

}
}
}
//
if(array_key_exists('surface',$params)) {
if($params['surface'] != NULL) {
$surfaceterm = $params['surface'];

$surfaces = new Surftreatments();
$surfaces = $surfaces->getSurfaceTerm($surfaceterm);
$this->surfaces = $surfaces;
foreach($this->surfaces as $surface)
{
$string .=' Surface treatment: ' . $this->view->escape($surface['term']);
}
}
}

if(array_key_exists('class',$params)) {
if($params['class'] != NULL) {
$string .=' Classification term like: ' . $this->view->escape($params['class']);

}
}

//Date from starts
if(array_key_exists('from',$params)) {
if($params['from'] != NULL) {
$from = $params['from'];
$suffix="BC";
$prefix="AD";
if ($from < 0) {
$date =  abs($from). ' ' .$suffix;
        }
		 else if ($from > 0) {
        $date =  $prefix.' '. abs($from);
		 }
$string .=' Date from greater or equal to: ' . (int)$date;

}
}

//Date from ends
if(array_key_exists('fromend',$params)) {
if($params['fromend'] != NULL) {
$from = $params['fromend'];
$suffix="BC";
$prefix="AD";
if ($from < 0) {
$date =  abs($from). ' ' .$suffix;
        }
		 else if ($from > 0) {
        $date =  $prefix.' '. abs($from);
		 }
$string .=' Date from smaller or equal to: ' . $date;

}
}


//Date to starts

//Date to ends

//Year found
if(array_key_exists('discovered',$params)) {
if($params['discovered'] != NULL) {
$string .=' Year of discovery where known: ' . $this->view->escape($params['discovered']);

}
}
//Found by
if(array_key_exists('finder',$params)) {
if($params['finder'] != NULL) {

$finder = $params['finder'];
$peoples = new Peoples();
$peoples = $peoples->getName($finder);

$this->peoples = $peoples;
foreach($this->peoples as $people)
{
$string .=' Item found by: ' . $this->view->escape($people['term']);

}

}
}
//Identified by
if(array_key_exists('idby',$params)) {
if($params['idby'] != NULL) {

$finder = $params['idby'];
$peoples = new Peoples();
$peoples = $peoples->getName($finder);

$this->peoples = $peoples;
foreach($this->peoples as $people)
{
$string .=' Identified by: ' . $this->view->escape($people['term']);

}

}
}
//Recorded by
//Identified by
if(array_key_exists('recordby',$params)) {
if($params['recordby'] != NULL) {

$finder = $params['recordby'];
$peoples = new Peoples();
$peoples = $peoples->getName($finder);

$this->peoples = $peoples;
foreach($this->peoples as $people)
{
$string .=' Recorded by: ' . $this->view->escape($people['term']);

}

}
}
//Issuer
if(array_key_exists('ruler',$params)) {
if($params['ruler'] != NULL) {

$ruler = $params['ruler'];

$rulers = new Rulers();
$rulers = $rulers->getRulersName($ruler);

$this->rulers = $rulers;
foreach($this->rulers as $ruler)
{
$string .=' Coin issued by: ' . $this->view->escape($ruler['issuer']);

}

}
}

if(array_key_exists('note',$params)) {
if ($params['note'] == (int)1){
$string .=' Object is a find of note';
}
}




if(array_key_exists('treasure',$params)) {
if ($params['treasure'] == (int)1){
$string .=' Object is Treasure or potential Treasure';
}
}

if(array_key_exists('TID',$params)) {
if ($params['TID'] != NULL){
$string .=' Treasure case number: '.$this->view->escape($params['TID']);
}
}

if(array_key_exists('created',$params)) {
if ($params['created'] != NULL){
$string .=' Finds entered on: '.$this->view->escape($params['created']);
}
}
if(array_key_exists('createdBefore',$params)) {
if ($params['createdBefore'] != NULL){
$string .=' Finds entered on or before: '.$this->view->niceShortDate($this->view->escape($params['createdBefore']));
}
}

if(array_key_exists('createdAfter',$params)) {
if ($params['createdAfter'] != NULL){
$string .=' Finds entered on or after: '.$this->view->niceShortDate($this->view->escape($params['createdAfter']));
}
}

if(array_key_exists('hoard',$params)) {
if ((int)$params['hoard'] == (int)1){
$string .=' Object is part of a hoard.';
}
}

if(array_key_exists('hID',$params)) {
if((int)$params['hID'] != NULL) {
$hID = $params['hID'];
$hIDs = new Hoards();
$hIDsList = $hIDs->getHoardDetails((int)$hID);
$this->hids = $hIDsList;
foreach($this->hids as $hid)
{
$string .=' Part of the ' . $this->view->escape($hid['term']). ' hoard.';
}
}
}
if(array_key_exists('otherref',$params)) {
if ($params['otherref'] != NULL){
$string .=' Other reference: '.$this->view->escape($params['otherref']);
}
}

//Workflow
if(array_key_exists('workflow',$params)) {
if($params['workflow'] != NULL) {

$stage = $params['workflow'];

$stages = new Workflows();
$stages = $stages->getStageName($stage);

$this->stages = $stages;
foreach($this->stages as $stage)
{
$string .=' Workflow stage: ' . $this->view->escape($stage['workflowstage']);

}

}
}

if(array_key_exists('manufacture',$params)) {
if($params['manufacture'] != NULL) {
$manufacture = $params['manufacture'];
$manufactures = new Manufactures();
$manufactures = $manufactures->getManufactureDetails((int)$manufacture);
$this->manufactures = $manufactures;
foreach($this->manufactures as $man)
{
$string .=' Manufacture type: ' . $this->view->escape($man['term']);
}
}
}
if(array_key_exists('decoration',$params)) {
if($params['decoration'] != NULL) {
$decoration = $params['decoration'];
$decorations = new Decmethods();
$decorations = $decorations->getDecorationDetails((int)$decoration);
$this->decorations = $decorations;
foreach($this->decorations as $dec)
{
$string .=' Decoration type: ' . $this->view->escape($dec['term']);
}
}
}
//Mint
if(array_key_exists('mint',$params)) {
if($params['mint'] != NULL) {

$id = $params['mint'];

$mints = new Mints();
$mints = $mints->getMintName($id);

$this->mints = $mints;
foreach($this->mints as $mint)
{
$string .=' Mint issuing coins: ' . $this->view->escape($mint['mint_name']).' ('.$mint['term']. ')';

}

}
}
//Category
if(array_key_exists('category',$params)) {
if($params['category'] != NULL) {
$id = $params['category'];

$cats = new CategoriesCoins();
$cats = $cats->getCategory($id);

$this->cats = $cats;
foreach($this->cats as $cat)
{
$string .=' Coin category: ' . $this->view->escape($cat['term']);

}

}
}


//Workflow
if(array_key_exists('createdby',$params)) {
if($params['createdby'] != NULL) {

$createdby = $params['createdby'];

$users = new Users();
$names = $users->getCreatedBy($createdby);

$this->names = $names;
foreach($this->names as $name)
{
$string .=' Records created by <span property="foaf:name">
' . $name['fullname'].'</span>';

}

}
}
	}
	return $string;

}

}