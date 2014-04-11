<?php
/** A view helper for generating a list of the parameters and search results
 * @category Pas
 * @package  Pas_View_Helper
 * @author   Daniel Pett
 * @version  1
 * @since    19/12/2011
 * @license  GNU Public
 * @todo     Clean up the code and make it write as one html block remove echoes.
 */
class Pas_View_Helper_SearchParamsUsers 
	extends Zend_View_Helper_Abstract {

	public function SearchParamsUsers($params = NULL) {
	unset($params['submit']);
	unset($params['action']);
	unset($params['controller']);
	unset($params['module']);
	if(!is_null($params)) {	
	echo 'You searched for: '; 
	
	$data = array();
	
	//Objecttype
	if(array_key_exists('objecttype',$params)) {
	if(!is_null($params['objecttype'])) {
	$data[] = 'Object type: '. $this->view->escape($params['objecttype']) ;
	}
	}
	//Broadperiod
	if(array_key_exists('broadperiod',$params)) {
	if(!is_null($params['broadperiod'])) {
	$data[] = 'Broadperiod: '. $this->view->escape($params['broadperiod']) ;
	}
	}

	//VA type
	if(array_key_exists('vaType',$params)) {
	if(!is_null($params['vaType'])) {
	$va = $params['vaType'];
	$data[] = 'Van Arsdell Type: '.$va;
	}
	}


	if(array_key_exists('woeid',$params)) {
	if(!is_null($params['woeid'])) {
	$woeid = $params['woeid'];
	$data[] = 'Where on Earth ID: '.$woeid;
	}
	}

	if(array_key_exists('recorderID',$params)) {
	if(!is_null($params['recorderID'])) {
	$rid = $params['recorderID'];
	$peoples = new Peoples();
	$people = $peoples->fetchRow($peoples->select()->where('secuid = ?', $rid));
	$data[] = 'Recorded by: ' . $people->fullname;
	}
	}
	//County
	if(array_key_exists('county',$params)) {
	if(!is_null($params['county'])) {
	$data[] = 'County: '. $this->view->escape($params['county']) ;
	}
	}
	//Tribe for IA coins
	if(array_key_exists('tribe',$params)) {
	if(!is_null($params['tribe'])) {
	$tribe = $params['tribe'];
	$tribes = new Tribes();
	$tribe = $tribes->fetchRow($tribes->select()->where('id = ?', (int)$tribe));
	$data[] = 'Iron Age Tribe: ' . $tribe->tribe;
	}
	}
	//region
	if(array_key_exists('regionID',$params)) {
	if(!is_null($params['regionID'])) {
	
	$region = $params['regionID'];
	$regions = new Regions();
	$regions = $regions->getRegion($region);
	$this->regions = $regions;
	foreach($this->regions as $region){
	$data[] =  'Region: '. $this->view->escape($region['region']);
	}
	}
	}

    if(array_key_exists('material',$params)) {
    if(!is_null($params['material'])) {

    $mat = $params['material'];
    $materials = new Materials();
    $materials = $materials->getMaterialName($mat);
    $this->materials = $materials;
    foreach($this->materials as $material){
    $data[] =  'Primary material: ' . $this->view->escape($material['term']) ;
    }
    }
    }


    if(array_key_exists('parish',$params)) {
    if(!is_null($params['parish'])) {
    $data[] =  'Parish: '. $this->view->escape($params['parish']) ;
    }
    }

    if(array_key_exists('district',$params)) {
    if(!is_null($params['district'])) {
    $data[] =  'District: '. $this->view->escape($params['district']) ;
    }
    }

    if(array_key_exists('denomination',$params)) {
    $denomname = $params['denomination'];
    $denoms = new Denominations();
    $denoms = $denoms->getDenomName($denomname);
    $this->denoms = $denoms;
    foreach($this->denoms as $denom)
    {
    $data[] =  'Denomination type: ' . $this->view->escape($denom['denomination']);
    }
    }

    if(array_key_exists('description',$params)) {
    if(!is_null($params['description'])) {
    $data[] =  'Description contained: '. $this->view->escape($params['description']) ;
    }
    }

    if(array_key_exists('fourFigure',$params)) {
    if(!is_null($params['fourFigure'])) {
    $data[] =  'Four figure grid reference: '. $this->view->escape($params['fourFigure']) ;
    }
    }

    if(array_key_exists('old_findID',$params)) {
    if(!is_null($params['old_findID'])) {
    $data[] =  'Find reference number: '. $this->view->escape($params['old_findID']) ;
    }
    }


    if(array_key_exists('fromsubperiod',$params)) {

    if (!is_null($params['fromsubperiod'])){

    $sub = $params['fromsubperiod'];
    if($sub == 1)
    { $data[] =  'Subperiod: Early';
    }
    else if ($sub == 2)
    {$data[] =  'Subperiod: Middle';
    }
    else if ($sub == 3)
    {$data[] =  'Subperiod: Late';
    }
    }
    }

    if(array_key_exists('tosubperiod',$params)) {

    if (!is_null($params['tosubperiod'])){

    $sub = $params['tosubperiod'];
    if($sub == 1)
    { $data[] =  'Subperiod: Early';
    }
    else if ($sub == 2)
    {$data[] =  'Subperiod: Middle';
    }
    else if ($sub == 3)
    {$data[] =  'Subperiod: Late';
    }
    }
    }



    if(array_key_exists('periodfrom',$params)) {
    if(!is_null($params['periodfrom'])) {
    $period = $params['periodfrom'];
    $periods = new Periods();
    $periods = $periods->getPeriodName($period);
    $this->periods = $periods;
    foreach($this->periods as $period)
    {
    $data[] =  'Period from: ' . $this->view->escape($period['term']);

    }
    }
    }

    //Period to key
    if(array_key_exists('periodto',$params)) {
    if(!is_null($params['periodto'])) {
    $period = $params['periodto'];
    $periods = new Periods();
    $periods = $periods->getPeriodName($period);
    $this->periods = $periods;
    foreach($this->periods as $period)
    {
    $data[] =  'Period to: ' . $this->view->escape($period['term']);

    }
    }
    }
    //
    if(array_key_exists('surface',$params)) {
    if(!is_null($params['surface'])) {
    $surfaceterm = $params['surface'];

    $surfaces = new Surftreatments();
    $surfaces = $surfaces->getSurfaceTerm($surfaceterm);
    $this->surfaces = $surfaces;
    foreach($this->surfaces as $surface)
    {
    $data[] =  'Surface treatment: ' . $this->view->escape($surface['term']);
    }
    }
    }

    if(array_key_exists('class',$params)) {
    if(!is_null($params['class'])) {
    $data[] =  'Classification term like: ' . $this->view->escape($params['class']);

    }
    }

    //Date from starts
    if(array_key_exists('from',$params)) {
    if(!is_null($params['from'])) {
    $from = $params['from'];
    $suffix="BC";
    $prefix="AD";
    if ($from < 0) {
    $date =  abs($from). ' ' .$suffix;
            }
                     else if ($from > 0) {
            $date =  $prefix.' '. abs($from);
                     }
    $data[] =  'Date from greater or equal to: ' . (int)$date;

    }
    }

    //Date from ends
    if(array_key_exists('fromend',$params)) {
    if(!is_null($params['fromend'])) {
    $from = $params['fromend'];
    $suffix="BC";
    $prefix="AD";
    if ($from < 0) {
    $date =  abs($from). ' ' .$suffix;
            }
                     else if ($from > 0) {
            $date =  $prefix.' '. abs($from);
                     }
    $data[] =  'Date from smaller or equal to: ' . $date;

    }
    }


    //Date to starts

    //Date to ends

    //Year found
    if(array_key_exists('discovered',$params)) {
    if(!is_null($params['discovered'])) {
    $data[] =  'Year of discovery where known: ' . $this->view->escape($params['discovered']);

    }
    }
    //Found by
    if(array_key_exists('finder',$params)) {
    if(!is_null($params['finder'])) {

    $finder = $params['finder'];
    $peoples = new Peoples();
    $peoples = $peoples->getName($finder);

    $this->peoples = $peoples;
    foreach($this->peoples as $people)
    {
    $data[] =  'Item found by: ' . $this->view->escape($people['term']);

    }

    }
    }
    //Identified by
    if(array_key_exists('idby',$params)) {
    if(!is_null($params['idby'])) {

    $finder = $params['idby'];
    $peoples = new Peoples();
    $peoples = $peoples->getName($finder);

    $this->peoples = $peoples;
    foreach($this->peoples as $people)
    {
    $data[] =  'Identified by: ' . $this->view->escape($people['term']);

    }

    }
    }
    //Recorded by
    //Identified by
    if(array_key_exists('recordby',$params)) {
    if(!is_null($params['recordby'])) {
    $finder = $params['recordby'];
    $peoples = new Peoples();
    $peoples = $peoples->getName($finder);
    $this->peoples = $peoples;
    foreach($this->peoples as $people) {
    $data[] =  'Recorded by: ' . $this->view->escape($people['term']) ;
    }
    }
    }
    //Issuer
    if(array_key_exists('ruler',$params)) {
    if(!is_null($params['ruler'])) {
    $ruler = $params['ruler'];
    $rulers = new Rulers();
    $rulers = $rulers->getRulersName($ruler);
    $this->rulers = $rulers;
    foreach($this->rulers as $ruler){
    $data[] =  'Coin issued by: ' . $this->view->escape($ruler['issuer']) ;
    }
    }
    }

    if(array_key_exists('note',$params)) {
    if ($params['note'] == (int)1){
    $data[] =  'Object is a find of note';
    }
    }

    if(array_key_exists('treasure',$params)) {
    if ($params['treasure'] == (int)1){
    $data[] =  'Object is Treasure or potential Treasure';
    }
    }

    if(array_key_exists('TID',$params)) {
    if (!is_null($params['TID'])){
    $data[] =  'Treasure case number: '.$this->view->escape($params['TID']);
    }
    }

    if(array_key_exists('created',$params)) {
    if (!is_null($params['created'])){
    $data[] =  'Finds entered on: '.$this->view->escape($params['created']);
    }
    }
    if(array_key_exists('createdBefore',$params)) {
    if (!is_null($params['createdBefore'])){
    $data[] =  'Finds entered on or before: '.$this->view->niceshortdate($this->view->escape($params['createdBefore']));
    }
    }

    if(array_key_exists('createdAfter',$params)) {
    if (!is_null($params['createdAfter'])){
    $data[] =  'Finds entered on or after: '.$this->view->niceshortdate($this->view->escape($params['createdAfter']));
    }
    }

    if(array_key_exists('hoard',$params)) {
    if ((int)$params['hoard'] == (int)1){
    $data[] =  'Object is part of a hoard.';
    }
    }

    if(array_key_exists('hID',$params)) {
    if((int)$params['hID']) {
    $hID = $params['hID'];
    $hIDs = new Hoards();
    $hIDsList = $hIDs->getHoardDetails((int)$hID);
    $this->hids = $hIDsList;
    foreach($this->hids as $hid)
    {
    $data[] =  'Part of the ' . $this->view->escape($hid['term']). ' hoard.';
    }
    }
    }
    if(array_key_exists('otherref',$params)) {
    if (!is_null($params['otherref'])){
    $data[] =  'Other reference: '.$this->view->escape($params['otherref']);
    }
    }

    //Workflow
    if(array_key_exists('workflow',$params)) {
    if(!is_null($params['workflow'])) {

    $stage = $params['workflow'];

    $stages = new Workflows();
    $stages = $stages->getStageName($stage);

    $this->stages = $stages;
    foreach($this->stages as $stage)
    {
    $data[] =  'Workflow stage: ' . $this->view->escape($stage['workflowstage']);

    }

    }
    }

    if(array_key_exists('manufacture',$params)) {
    if(!is_null($params['manufacture'])) {
    $manufacture = $params['manufacture'];
    $manufactures = new Manufactures();
    $manufactures = $manufactures->getManufactureDetails((int)$manufacture);
    $this->manufactures = $manufactures;
    foreach($this->manufactures as $man)
    {
    $data[] =  'Manufacture type: ' . $this->view->escape($man['term']);
    }
    }
    }
    if(array_key_exists('decoration',$params)) {
    if(!is_null($params['decoration'])) {
    $decoration = $params['decoration'];
    $decorations = new Decmethods();
    $decorations = $decorations->getDecorationDetails((int)$decoration);
    $this->decorations = $decorations;
    foreach($this->decorations as $dec)
    {
    $data[] =  'Decoration type: ' . $this->view->escape($dec['term']);
    }
    }
    }
    //Mint
    if(array_key_exists('mint',$params)) {
    if(!is_null($params['mint'])) {

    $id = $params['mint'];

    $mints = new Mints();
    $mints = $mints->getMintName($id);

    $this->mints = $mints;
    foreach($this->mints as $mint)
    {
    $data[] =  'Mint issuing coins: ' . $this->view->escape($mint['mint_name']).' ('.$mint['term']. ')';
    }

    }
    }
    //Category
    if(array_key_exists('category',$params)) {
    if(!is_null($params['category'])) {
    $id = $params['category'];

    $cats = new CategoriesCoins();
    $cats = $cats->getCategory($id);

    $this->cats = $cats;
    foreach($this->cats as $cat)
    {
    $data[] =  'Coin category: ' . $this->view->escape($cat['term']);
    }

    }
    }

    if(array_key_exists('reeceID',$params)) {
    if(!is_null($params['reeceID'])) {
    $id = $params['reeceID'];

    $reeces = new Reeces();
    $rs = $reeces->getReecePeriodDetail($id);

    foreach($rs as $r)
    {
    $data[] =  'Reece Period: ' . $this->view->escape($r['period_name']).' '.$r['date_range'];

    }

    }
    }


 	echo implode($data, ' &raquo;');

            }
    }

}