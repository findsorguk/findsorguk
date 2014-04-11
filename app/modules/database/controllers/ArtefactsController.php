<?php
/** Controller for manipulating the artefacts data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_ArtefactsController extends Pas_Controller_Action_Admin {

    const REDIRECT = '/database/artefacts/';

    protected $_user;
    /**
     * @var array restricted access roles
     */
    protected $_restricted = array(null, 'member','public');

    protected $_higherLevel = array('treasure', 'flos', 'admin', 'hero', 'fa' );

    /**
    * @var array coins pseudonyms
    */
    protected $_coinarray = array(
        'Coin','COIN','coin',
        'token','jetton','coin weight',
        'COIN HOARD', 'TOKEN', 'JETTON'
        );

    /** An array of Roman and Iron Age periods
     * Used for coins
    * @var array Romanic periods
    */
    protected $_periodRomIA = array(
	'Roman','ROMAN','roman',
	'Iron Age','Iron age','IRON AGE',
	'Byzantine','BYZANTINE','Greek and Roman Provincial',
	'GREEK AND ROMAN PROVINCIAL','Unknown',
	'UNKNOWN');
    /** An array of Roman and Prehistoric periods
     * Used for objects
     * @var array
     */
    protected $_periodRomPrehist = array(
	'Roman', 'ROMAN', 'roman',
	'Iron Age', 'Iron age', 'IRON AGE',
	'Byzantine', 'BYZANTINE', 'Greek and Roman Provincial',
	'GREEK AND ROMAN PROVINCIAL', 'Unknown', 'UNKNOWN',
	'Mesolithic', 'MESOLITHIC', 'PREHISTORIC',
	'NEOLITHIC', 'Neolithic', 'Palaeolithic',
	'PALAEOLITHIC', 'Bronze Age', 'BRONZE AGE');

    /** An array of Early medieval periods
     * Used for objects and coins
     * @var array
     */
    protected $_earlyMed = array('Early Medieval','EARLY MEDIEVAL');

    /** An array of Medieval periods
     * Used for coins and objects
     * @var array
     */
    protected $_medieval = array('Medieval','MEDIEVAL');

    /** An array of Post Medieval periods
     * Used for coins and objects
     * @var array
     */
    protected $_postMed = array('Post Medieval','POST MEDIEVAL','Modern', 'MODERN');

    protected $_config, $_finds, $_cs, $_auth;

    /** Setup the contexts by action and the ACL.
     *
     */
    public function init()  {
    $this->_config = $this->_helper->config();
    $this->_helper->_acl->deny('public',array('add','edit'));
    $this->_helper->_acl->allow('public',array(
        'index','record','errorreport',
        'notifyflo'
        ));
    $this->_helper->_acl->allow('member',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_cs = $this->_helper->contextSwitch();
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_cs->setAutoDisableLayout(true)
            ->addContext('csv',array('suffix' => 'csv'))
            ->addContext('kml',array('suffix' => 'kml'))
            ->addContext('rss',array('suffix' => 'rss'))
            ->addContext('atom',array('suffix' => 'atom'))
            ->addContext('rdf',array('suffix' => 'rdf','headers' => array('Content-Type' => 'application/xml')))
            ->addContext('pdf',array('suffix' => 'pdf'))
            ->addContext('midas', array('suffix' => 'midas', 'headers' => array('Content-Type' => 'application/xml')))
            ->addContext('qrcode',array('suffix' => 'qrcode'))
            ->addContext('geojson',array('suffix' => 'geojson', 'headers' => array('Content-Type' => 'application/json')))
            ->addActionContext('record', array('qrcode', 'json', 'xml', 'geojson', 'rdf'))
            ->initContext();
    $this->_finds = new Finds();
    $this->_auth = Zend_Registry::get('auth');
    $this->_user = $this->_helper->identity->getPerson();
    }


    private function array_cleanup( $array ) {
    $todelete = array('submit','action','controller','module','csrf');
    foreach( $array as $key => $value ) {
    foreach($todelete as $match){
    if($key == $match){
            unset($array[$key]);
    }
    }
    }
    }
    /** Display a list of objects recorded with pagination
    */
    public function indexAction(){
    $this->_redirect('database/search/results/');
    }

    /** Display individual record
     * @todo move comment functionality to a model
    */
    public function recordAction() {
    if($this->_getParam('id',false)) {
    $this->view->recordID = $this->_getParam('id');
    $id = $this->_getParam('id');
    $findsdata = $this->_finds->getIndividualFind($id, $this->getRole());

    if($findsdata) {
    $this->view->finds = $findsdata;
    } else {
        throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
    }

    $contexts = array(
    'xml','rss','json',
    'atom','kml','georss',
    'ics','rdf','xcs',
    'vcf','csv','pdf',
    'geojson');

    if(!in_array($this->_cs->getCurrentContext(), $contexts)) {

    $this->view->findsdata     = $this->_finds->getFindData($id);
    $this->view->findsmaterial = $this->_finds->getFindMaterials($id);
    $this->view->temporals     = $this->_finds->getFindTemporalData($id);
    $this->view->peoples       = $this->_finds->getPersonalData($id);
    $this->view->findotherrefs = $this->_finds->getFindOtherRefs($id);

    $findspotsdata = new Findspots();
    $this->view->findspots = $findspotsdata->getFindSpotData($id);

    $rallyfind = new Rallies;
    $this->view->rallyfind = $rallyfind->getFindToRallyNames($id);

    $coins = new Coins;
    $this->view->coins = $coins->getCoinData($id);

    $coinrefs = new Coinclassifications();
    $this->view->coinrefs = $coinrefs->getAllClasses($id);

    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);

    $refs = new Publications;
    $this->view->refs = $refs->getReferences($id);

    $comments = new Comments;
    $this->view->comments = $comments->getFindComments($id);

    $response = $this->getResponse();
    if(in_array($this->getRole(),$this->_higherLevel)
            && (!in_array($this->_cs->getCurrentContext(),array(
                'xml','json','qrcode')))){

    } else {
    $findspotsdata = new Findspots();
    $this->view->findspots = $findspotsdata->getFindSpotData($id);
    }
    $form = new CommentFindForm();
    $form->submit->setLabel('Add a new comment');
//    $form->removeElement('captcha');
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $data = $this->_helper->akismet($form->getValues());
    $data['contentID'] = $this->_getParam('id');
    $data['comment_type'] = 'findComment';
    $data['comment_approved'] = 'moderation';
    $comments = new Comments();
    $comments->add($data);
    $this->_flashMessenger->addMessage('Your comment has been entered and will appear shortly!');
    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
    $this->_request->setMethod('GET');
    } else {
    $this->_flashMessenger->addMessage('There are problems with your comment submission');
    $form->populate($form->getValues());
    }
    }
    } else {
    $this->_helper->layout->disableLayout();    //disable layout
    $record = $this->_finds->getAllData($id);
    $user = $this->_user;
    if($user){
    	$role = $user->role;
    } else {
    	$role = NULL;
    }
    if(in_array($role,$this->_restricted)) {
    $record['0']['gridref'] = 'Restricted information';
    $record['0']['easting'] = 'Restricted information';
    $record['0']['northing'] = 'Restricted information';
    $record['0']['lat'] = 'Restricted information';
    $record['0']['lon'] = 'Restricted information';
    $record['0']['finder'] = 'Restricted information';
    $record['0']['address'] = 'Restricted information';
    $record['0']['postcode'] = 'Restricted information';
    $record['0']['findspotdescription'] = 'Restricted information';
    if(!is_null($record['0']['knownas'])){
    $record['0']['parish'] = 'Restricted information';
    $record['0']['fourFigure'] = 'Restricted information';
    $record['0']['fourFigureLat'] = 'Restricted information';
    $record['0']['fourFigureLon'] = 'Restricted information';
    }
    }
    $this->view->record = $record;
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Add an object
     * @todo slim down action, move logic for adding to finds.php model
    */
    public function addAction() {
    $user = $this->_helper->identity->getPerson();
    if((is_null($user->peopleID) && is_null($user->canRecord)) 
    || (!is_null($user->peopleID) && is_null($user->canRecord)) ){
    $this->_redirect('/error/accountproblem');
    }
    $last = $this->_getParam('copy');
    $this->view->secuid = $this->secuid();
    $form = new FindForm();
    $form->submit->setLabel('Save record');
    $form->old_findID->setValue($this->FindUid());
    $form->secuid->setValue($this->secuid());
    if(isset($user->peopleID)){
    $form->recorderID->setValue($user->peopleID);
    $form->recordername->setValue($user->fullname);
    $form->identifier1ID->setValue($user->peopleID);
    $form->idBy->setValue($user->fullname);
    }
    if(in_array($user->role,$this->_restricted)) {
    $form->finderID->setValue($user->peopleID);
    $form->removeDisplayGroup('discoverers');
    $form->removeElement('finder');
    $form->removeElement('secondfinder');
    $form->removeElement('idBy');
    $form->recordername->setAttrib('disabled', true);
    $form->removeElement('id2by');
    }
    $this->view->form = $form;
    if($last == 'last') {
    $finddata = $this->_finds->getLastRecord($this->getIdentityForForms());
    foreach($finddata as $finddataflat){
    $form->populate($finddataflat);
    if(isset($user->peopleID)){
    $form->recorderID->setValue($user->peopleID);
    $form->recordername->setValue($user->fullname);
    }
    }
    }
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $insertData = $form->getValues();
    $insertData['secuid'] = $this->secuid();
    $insertData['old_findID'] = $this->FindUid();
    $insertData['secwfstage'] = (int)2;
    $insertData['institution'] = $this->getInstitution();
    unset($insertData['recordername']);
    unset($insertData['finder']);
    unset($insertData['idBy']);
    unset($insertData['id2by']);
    unset($insertData['secondfinder']);
    $insert = $this->_finds->add($insertData);
    $this->_helper->solrUpdater->update('beowulf', $insert);
    $this->_redirect(self::REDIRECT . 'record/id/' . $insert);
    $this->_flashMessenger->addMessage('Record created!');
    } else  {
    $this->_flashMessenger->addMessage('Please check and correct errors!');
    $form->populate($form->getValues());
    }
    }
    }
    /** Edit a record
     *
     * @todo move update logic to model finds.php
    */
    public function editAction() {
    if($this->_getParam('id',false)){
	$user = $this->getAccount();
    $form = new FindForm();
    $form->submit->setLabel('Update record');
    $this->view->form = $form;
    if(in_array($this->getRole(),$this->_restricted)) {
    $form->removeDisplayGroup('discoverers');
    $form->removeElement('finder');
    $form->finderID->setValue($user->peopleID);
    $form->removeElement('secondfinder');
    $form->removeElement('idBy');
    $form->recordername->setAttrib('disabled', true);
    $form->removeElement('id2by');
    }
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    $data = $form->getValues();
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
//    $updateData = array_filter($updateData);
    $id2by = $form->getValue('id2by');
    if($id2by === "" || is_null($id2by)){
    $updateData['identifier2ID'] = NULL;
    }
    unset($updateData['recordername']);
    unset($updateData['finder']);
    unset($updateData['idBy']);
    unset($updateData['id2by']);

    unset($updateData['secondfinder']);
    $oldData = $this->_finds->fetchRow('id=' . $this->_getParam('id'))->toArray();
    $where = array();
    $where[] = $this->_finds->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    $this->_finds->update($updateData, $where);
    $this->_helper->audit($updateData, $oldData, 'FindsAudit',  $this->_getParam('id'),
    	$this->_getParam('id'));
    $this->_helper->solrUpdater->update('beowulf', $this->_getParam('id'));
    $this->_flashMessenger->addMessage('Artefact information updated and audited!');
    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
    } else {
    $this->view->find = $this->_finds->fetchRow('id='.$this->_getParam('id'));
    $form->populate($data);
    }
    } else {
    $id = (int)$this->_request->getParam('id', 0);
    if ($id > 0) {
    $formData = $this->_finds->getEditData($id);
    if(count($formData)){
    $form->populate($formData['0']);
    $this->view->find = $this->_finds->fetchRow('id='.$id);
    } else {
            throw new Pas_Exception_Param($this->_nothingFound);
    }
    }
    }
    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Delete a record
    */
    public function deleteAction() {
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $where = $this->_finds->getAdapter()->quoteInto('id = ?', $id);
    $this->_finds->delete($where);
    $findID = $this->_request->getPost('findID');
    $findspots = new Findspots();
    $whereFindspots = array();
    $whereFindspots[] = $this->_finds->getAdapter()->quoteInto('findID  = ?',
            $findID);
    $this->_flashMessenger->addMessage('Record deleted!');
    $findspots->delete($whereFindspots);
    $this->_helper->solrUpdater->deleteById('beowulf', $id);
    $this->_redirect(self::REDIRECT);
    }
    $this->_flashMessenger->addMessage('No changes made!');
    $this->_redirect('database/artefacts/record/id/' . $id);
    } else {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->find = $this->_finds->fetchRow('id=' . $id);
    }
    }
    }
    /** Enter an error report
     * @todo move insert logic to model
    */
    public function errorreportAction() {
    if($this->_getParam('id',false)) {
    $form = new CommentOnErrorFindForm();
    $form->submit->setLabel('Submit your error report');
    $finds = $this->_finds->getRelevantAdviserFind($this->_getParam('id',0));
    $this->view->form = $form;
    $this->view->finds = $finds;
    if ($this->getRequest()->isPost()===true) {
    if($form->isValid($_POST)===true) {
    $data = $form->getValues();
    if ($this->_helper->akismet($data)) {
    $data['comment_approved'] = 'spam';
    }  else  {
    $data['comment_approved'] =  '1';
    }
    if(array_key_exists('captcha', $data)){
    	unset($data['captcha']);
    }
	$errors = new ErrorReports();
    $errors->add($data);
	$data = array_merge($finds['0'], $data);

    $this->notify($finds['0']['objecttype'],$finds['0']['broadperiod'],$finds['0']['institution'],$finds['0']['createdBy'],$data);
    
    $this->_flashMessenger->addMessage('Your error report has been submitted. Thank you!');
    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
    } else {
    $form->populate($form->getValues());
    }
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }



    public function notifyfloAction(){
    if($this->_getParam('id',false)) {
    $form = new NotifyFloForm();
    $this->view->form = $form;
    $find = $this->_finds->fetchRow($this->_finds->select()->where('id = ?', $this->_getParam('id')));
    $this->view->find = $find->toArray();
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $contacts = new Contacts();
    $to = $contacts->getNameEmail($form->getValue('flo'));
    $cc = $this->_getAdviser($find->objecttype,$find->broadperiod);
    $from[] = array('email' => $this->_user->email, 'name' => $this->_user->fullname);
    $cc = array_merge($cc,$from);
    $assignData = array_merge($find->toArray(),$form->getValues(),$to['0']);
    
    $this->_helper->mailer($assignData, 'publicFindToFlo', $to, $cc, $from);
    $this->_flashMessenger->addMessage('Your message has been sent');
    $this->_redirect('database/artefacts/record/id/' . $find->id);
    } else {
        $form->populate($form->getValues());
    }
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

	public function workflowAction(){
   	if($this->_getParam('findID',false)){
   	$people = new Peoples();
   	$exist = $people->checkEmailOwner($this->_getParam('findID'));
   	$user = new Pas_User_Details();
   	$person = $user->getPerson();
   	$from = array('name' => $person->fullname, 'email' => $person->email);
   	$this->view->from = $exist;
   	$form = new ChangeWorkFlowForm();
	$findStatus = $this->_finds->fetchRow($this->_finds->select()->where('id = ?', $this->_getParam('findID')));
   	$this->view->find = $findStatus->old_findID;
   	$form->populate($findStatus->toArray());
   	$this->view->form = $form;
   	if(is_null($exist['0']['email'])){
   		$form->finder->setAttrib('disabled', 'disabled');
   		$form->finder->setDescription('No email associated with finder yet.');
   		$form->removeElement('content');
   	}
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
	if ($form->isValid($form->getValues())) {
    $updateData = array('secwfstage' => $form->getValue('secwfstage'));	
    if(strlen($form->getValue('finder')) > 0){
    	$assignData = array(
    	'name' => $exist['0']['name'],
    	'old_findID' => $findStatus->old_findID,
    	'id' => $this->_getParam('findID'),
    	'from' => $person->fullname,
    	'workflow' => $form->getValue('secwfstage'),
    	'content' => $form->getValue('content')
 	);
	$this->_helper->mailer($assignData, 'informFinderWorkflow', $exist, array($from), array($from),null,null);
    }
    $where = array();
	$where[] = $this->_finds->getAdapter()->quoteInto('id = ?', $this->_getParam('findID'));
	$this->_finds->update($updateData, $where);
    $this->_helper->audit($updateData, $findStatus->toArray(), 'FindsAudit',  $this->_getParam('findID'),
    	$this->_getParam('findID'));
    $this->_helper->solrUpdater->update('beowulf', $this->_getParam('findID'));	
    $this->_flashMessenger->addMessage('Workflow status changed');
	$this->_redirect('database/artefacts/record/id/' . $this->_getParam('findID'));
    $this->_request->setMethod('GET');
    } else {
    $this->_flashMessenger->addMessage('There were problems changing the workflow');
    $form->populate($form->getValues());
    }
    }
   	} else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
   }

    /** Provide a notification for an object
    */
    protected function notify($objecttype, $broadperiod, $institution, $createdBy, $data) {
    if($institution === 'PUBLIC') {
    $users = new Users();
    $responsible = $users->fetchRow('id = ' . $createdBy);
  
    $to = array(array('email' => $responsible->email, 'name' => $responsible->fullname));	
    } elseif (in_array($institution, array('PAS', 'DCMS', 'RAH'))) {
    $to = array(array('email' => 'info@finds.org.uk', 'name' => 'Central Unit'));	
    }else {
    $responsible = new Contacts();
    $to = $responsible->getOwner($data['comment_findID']);
    }
  
    $cc = $this->_getAdviser($objecttype, $broadperiod);
    if($this->_user){
    $from = array(array(
        'email' => $this->_user->email,
        'name' => $this->_user->fullname));
    } else {
    	$from = array(array(
    	'email' => $data['comment_author_email'],
    	'name' => $data['comment_author']
    	));
    }
    $assignData = array_merge($to['0'],$data);
  	$this->_helper->mailer($assignData,'errorSubmission', $to, $cc, $from);

    }
    /** Function to combine an array
    */
    private function _combine($array1,$array2) {
            return array_combine($array1,$array2);
    }
    /** Determine adviser to email
    */
    private function _getAdviser($objecttype, $broadperiod) {
    	
    $this->_romancoinsadviser = $this->_config->findsadviser->romancoins;
    $this->_romancoinsadviseremail = $this->_config->findsadviser->romcoins->email;

    $this->_medievalcoinsadviser = $this->_config->findsadviser->medievalcoins;
    $this->_medievalcoinsadviseremail = $this->_config->findsadviser->medcoins->email;

    $this->_romanobjects = $this->_config->findsadviser->romanobjects;
    $this->_romanobjectsemail = $this->_config->findsadviser->romobjects->email;

    $this->_medievalobjects = $this->_config->findsadviser->medievalobjects;
    $this->_medievalobjectsemail = $this->_config->findsadviser->medobjects->email;

    $this->_postmedievalobjects = $this->_config->findsadviser->postmedievalobjects;
    $this->_postmedievalobjectsemail = $this->_config->findsadviser->postmedobjects->email;

    $this->_earlymedievalobjects = $this->_config->findsadviser->earlymedievalobjects;
    $this->_earlymedievalobjectsemail = $this->_config->findsadviser->earlymedobjects->email;

    $this->_catchall = $this->_config->findsadviser->default;
    $this->_catchallemail = $this->_config->findsadviser->def->email;

    switch($objecttype) {
    case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomIA)):
            $adviserdetails = $this->_romancoinsadviser;
            $adviseremail = $this->_romancoinsadviseremail;
            break;
    case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
            $adviserdetails = $this->_medievalcoinsadviser;
            $adviseremail = $this->_medievalcoinsadviseremail;
            break;
    case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
            $adviserdetails = $this->_medievalcoinsadviser;
            $adviseremail = $this->_medievalcoinsadviseremail;
            break;
	case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
            $adviserdetails = $this->_medievalcoinsadviser;
            $adviseremail = $this->_medievalcoinsadviseremail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomPrehist)):
            $adviserdetails = $this->_romanobjects;
            $adviseremail = $this->_romanobjectsemail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
            $adviserdetails = $this->_postmedievalobjects;
            $adviseremail = $this->_postmedievalobjectsemail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
            $adviserdetails = $this->_medievalobjects;
            $adviseremail = $this->_medievalobjectsemail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
            $adviserdetails = $this->_earlymedievalobjects;
            $adviseremail = $this->_earlymedievalobjectsemail;
            break;
    default:
            $adviserdetails = $this->_catchall;
            $adviseremail = $this->_catchallemail;
            break;
    }

   $people = $this->_combine($adviserdetails->toArray(),$adviseremail->toArray());
   $sendto = array();
   foreach($people as $k => $v){
   $sendto[] = array ('email' => $v, 'name' => $k);
   }
   return $sendto;

   }

}
