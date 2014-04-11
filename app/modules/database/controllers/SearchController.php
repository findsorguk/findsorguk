<?php
/** Controller for searching for finds on database
 * @todo finish module's functions and replace with solr functionality. Scripts suck the big one.
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_SearchController extends Pas_Controller_Action_Admin {

	protected $_searches;

	protected $_contexts = array(
	'xml', 'rss', 'json',
	'atom', 'kml', 'georss',
	'ics', 'rdf', 'xcs',
	'csv','n3', 'midas',
	'geojson');

	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_searches = new Searches();
	$this->_helper->_acl->allow('public',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addContext('qrcode',array('suffix' => 'qrcode'))
		->addContext('geojson',array('suffix' => 'geojson', 'headers'   => array('Content-Type' => 'application/json')))
		->addContext('rdf',array('suffix' => 'rdf', 'headers'   => array('Content-Type' => 'application/xml')))
		->addContext('midas',array('suffix' => 'midas', 'headers'   => array('Content-Type' => 'text/xml')))
		->addActionContext('results', array('json','xml','rdf','rss','atom', 'kml', 'geojson', 'qrcode', 'midas'))
		->setAutoJsonSerialization(false);

	$this->_helper->contextSwitch()->initContext();

	if(!in_array($this->_helper->contextSwitch()->getCurrentContext(),$this->_contexts )) {
	$this->view->googleapikey = $this->_helper->config()->webservice->googlemaps->apikey;
	}
	}


	/** Display the basic what/where/when page.
	*/
	public function indexAction() {
	$form = new SolrForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}


	private function array_cleanup( $array ) {
      $todelete = array(
        	'submit','action','controller',
        	'module','page','csrf',
        	'finder', 'idby', 'recordby', 
      		'idBy', 'recordername');
	foreach( $array as $key => $value ) {
        foreach($todelete as $match){
    	if($key == $match){
    		unset($array[$key]);
    	}
        }
        }
        return $array;
        }

	/** Generate the advanced search page
	*/
	public function advancedAction(){
	$form = new AdvancedSearchForm(array('disableLoadDefaultDecorators' => true));
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the byzantine search form
	*/
	public function byzantinenumismaticsAction() {
	$form = new ByzantineNumismaticSearchForm();
	$this->view->byzantineform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the early medieval numismatics form
	*/
	public function earlymednumismaticsAction() {
	$form = new EarlyMedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the medieval numismatics page
	*/
	public function mednumismaticsAction() {
	$form = new MedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the post medieval numismatics pages
	*/
	public function postmednumismaticsAction() {
	$form = new PostMedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}

	/** Display the roman numismatics pages
	*/
	public function romannumismaticsAction() {
	$form = new RomanNumismaticSearchForm();
	$this->view->formRoman = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the iron age numismatics pages
	*/
	public function ironagenumismaticsAction() {
	$form = new IronAgeNumismaticSearchForm();

	$this->view->form = $form;
	if($this->getRequest()->isPost()){
        $this->_helper->geoFormLoaderOptions($this->getRequest()->getPost());
        if ($form->isValid($this->getRequest()->getPost())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
        $this->_helper->geoFormLoaderOptions($form->getValues());
	$form->populate($form->getValues());
	}
        }  else {
        $this->_helper->geoFormLoaderOptions($form->getValues());
        $form->populate($form->getValues());
        }
	}
	/** Display the greek and roman provincial pages
	*/
	public function greekromanAction() {
	$form = new GreekRomanSearchForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}


	/** Remove multiple values
	 *
	 * @param array $array
	 * @param string $sub_key
	*/
	private function unique_multi_array($array, $sub_key) {
	$target = array();
	$existing_sub_key_values = array();
	foreach ($array as $key=>$sub_array) {
       if (!in_array($sub_array[$sub_key], $existing_sub_key_values)) {
           $existing_sub_key_values[] = $sub_array[$sub_key];
           $target[$key] = $sub_array;
       }
	}
	return $target;
	}


	public function saveAction() {
	$form = new SaveSearchForm();
	$form->submit->setLabel('Save search');
	$this->view->form = $form;
	$lastsearch = $this->_searches->fetchRow($this->_searches->select()->where('userid = ?',
	$this->getIdentityForForms())->order('id DESC'));
	$querystring = unserialize($lastsearch->searchString);
	$params = array();
	foreach($querystring as $key => $value) {
	$params[$key] = $value;
	}
	$this->view->params = $params;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$insertData = $form->getValues();
	$insertData['searchString'] = $lastsearch->searchString;
	$saved = new SavedSearches();
	$insert = $saved->add($insertData);
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else  {
	$this->_flashMessenger->addMessage('There are problems with your submission.');
	$form->populate($form->getValues());
	}
	}
	}


	/** Email a search result
	*/
	public function emailAction() {
	$user = $this->_helper->identity->getPerson();
        if(!$user->id){
            $userid = 3;
        } else {
            $userid = $user->id;
        }
	$lastsearch = $this->_searches->fetchRow($this->_searches->select()->where('userid = ?',
	$userid)->order('id DESC'));
	if($lastsearch) {
	$querystring = unserialize($lastsearch->searchString);
	$params = array();
	foreach($querystring as $key => $value) {
	$params[$key] = $value;
	}
	$this->view->params = $params;
	$form = new EmailSearchForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$to[] = array(
	'email' => $form->getValue('email'),
	'name' => $form->getValue('fullname')
	);
	$from[] = array(
	'email' => $user->email,
	'name' => $user->fullname
	);
	$url = array('url' => $params);
	$assignData = array_merge($form->getValues(), $from[0], $url);
	$this->_helper->mailer($assignData,'sendSearch', $to, null, $from);
	$this->_flashMessenger->addMessage('Your email has been sent to ' . $form->getValue('fullname')
	. '. Thank you for sending them some of our records.');
	$this->_helper->Redirector->gotoSimple('results','search','database',$querystring);
	}  else {
	$form->populate($form->getValues());
	}
	}
	}
	}
	/** Display saved searches
	*/
	public function savedsearchesAction() {
	$allowed = array('fa','flos','admin');
	if(in_array($this->getRole(),$allowed)) {
	$private = 1;
	} else {
	$private = NULL;
	}
	if($this->_getParam('by') === 'me'){
	$this->view->data = $this->_searches->getAllSavedSearches($this->_helper->identity->getPerson()->id,
		$this->_getParam('page'),$private);
	} else {
	$this->view->data = $this->_searches->getAllSavedSearches(NULL, $this->_getParam('page'), $private);
	}
	}

	/** Display the solr form
	*/
	public function solrAction(){
	$form = new SolrForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = $this->array_cleanup($form->getValues());
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}


	public function postcodeAction(){
        $form = new PostcodeForm();
        $form->postcode->setLabel('Postcode to search on: ');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())){
	if ($form->isValid($form->getValues())) {
        $postcode = str_replace(' ','',$form->getValue('postcode'));
        $area = new Pas_Geo_MapIt_Postcode();
        $area->setPartialPostCode($postcode);

        $xy = $area->get();
        $params = array(
        'lat' => $xy->wgs84_lat,
        'lon' => $xy->wgs84_lon,
        'd' => $form->getValue('distance'),
        'postcode' => $form->getValue('postcode')
        );

	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}

        /** Display the index page.
	*/
	public function resultsAction(){
	$params = $this->_getAllParams();
	$search = new Pas_Solr_Handler('beowulf');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$params['format'] = $context;
	$search->setFields($fields->getFields());
	$search->setFacets(array(
    'objectType','county', 'broadperiod',
    'institution', 'rulerName', 'denominationName', 
    'mintName', 'materialTerm', 'workflow', 'reeceID'));
	$search->setParams($params);
	$search->execute();
    $this->view->facets = $search->_processFacets();
	$this->view->paginator = $search->_createPagination();
	$this->view->stats = $search->_processStats();
	$this->view->results = $search->_processResults();
	$this->view->server = $search->getLoadBalancerKey();
	if(array_key_exists('submit', $params)){
    $queries = new Searches();
    $queries->insertResults(serialize($params));
	}
	}

	public function mapAction(){
	}


        public function spatialAction(){
        $form = new FindFilterForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($_POST)){
		if ($form->isValid($form->getValues())) {
        $params = array(
          'bbox' => $form->getValue('bbox'),
          'objectType' => $form->getValue('objecttype'),
          'broadperiod' => $form->getValue('broadperiod')
        );
        $params = array_filter($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
//EOS
}
