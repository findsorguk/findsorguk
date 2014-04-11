<?php
/** Controller for administering numismatic functions
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_NumismaticsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** 
	 * 
	 * @var string Redirect
	 */		
	protected $_redirectUrl = 'admin/numismatics/';
	/** Display the numismatic index
	 * 
	 */
	public function indexAction() {
	}
	/** Display list of all die axes
	 * 
	 */
	public function dieaxesAction() {
	$dieaxes = new Dieaxes();
	$this->view->dieaxes = $dieaxes->getDieListAdmin();
	}
	/** Add a die axis
	 * 
	 */
	public function adddieaxisAction() {
	$form = new DieAxisForm();
	$form->submit->setLabel('Add a new die axis term to the system...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$dieaxes = new Dieaxes();
	$insertData = array(
	'die_axis_name' => $form->getValue('die_axis_name'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$dieaxes->insert($insertData);
	$this->_flashMessenger->addMessage('A new die axis term been created on the system!');
	$this->_redirect($this->_redirectUrl . 'dieaxes/');
	} else {
	$this->_flashMessenger->addMessage('Please correct errors!');
	$form->populate($formData);
	}
	}
	}
	/** Edit a die axis
	 * 
	 */
	public function editdieaxisAction() {
	if($this->_getParam('id',false)) {
	$form = new DieAxisForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$dieaxes = new Dieaxes();
	$updateData = array(
	'die_axis_name' => $form->getValue('die_axis_name'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] = $dieaxes->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$update = $dieaxes->update($updateData, $where);
	$this->_redirect($this->_redirectUrl . 'dieaxes');
	$this->_flashMessenger->addMessage('Die axis information updated!');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$dieaxes = new Dieaxes();
	$dieaxis = $dieaxes->fetchRow('id=' . $id);
	if(count($dieaxis)) {
	$form->populate($dieaxis->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a die axis
	 * 
	 */
	public function deletedieaxisAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$dieaxes = new Dieaxes();
	$where = 'id = ' . $id;
	$dieaxes->delete($where);
	}	
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'dieaxes');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$dieaxes = new Dieaxes();
	$this->view->dieaxis = $dieaxes->fetchRow('id='.$id);
	}
	}
	}
	/** Add a denomination
	 * 
	 */
	public function adddenominationAction() {
	$form = new DenominationForm();
	$form->submit->setLabel('Add a new denomination to the system...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$denominations = new Denominations();
	$insertData = array(
	'denomination' => $form->getValue('denomination'),
	'period' => $form->getValue('period'),
	'material' => $form->getValue('material'), 
	'valid' => $form->getValue('valid'),
	'description' => $form->getValue('description'), 
	'rarity' => $form->getValue('rarity'), 
	'thickness' => $form->getValue('thickness'),
	'diameter' => $form->getValue('diameter'), 
	'weight' => $form->getValue('weight'), 
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $denominations->insert($insertData);
	$this->_flashMessenger->addMessage('A new denomination has been created on the system!');
	$this->_redirect($this->_redirectUrl . 'denominations/period/' . $insert);
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a denomination
	*/		
	public function editdenominationAction() {
	if($this->_getParam('id',false)) {
	$form = new DenominationForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$id = (int)$this->_getParam('id');
	$denominations = new Denominations();
	$updateData = array(
	'denomination' => $form->getValue('denomination'),
	'period' => $form->getValue('period'),
	'material' => $form->getValue('material'), 
	'valid' => $form->getValue('valid'),
	'description' => $form->getValue('description'), 
	'rarity' => $form->getValue('rarity'), 
	'thickness' => $form->getValue('thickness'),
	'diameter' => $form->getValue('diameter'), 
	'weight' => $form->getValue('weight'), 
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] = $denominations->getAdapter()->quoteInto('id = ?', (int)$id);
	$update = $denominations->update($updateData, $where);
	$this->_flashMessenger->addMessage('Denomination information updated!');
	$this->_redirect($this->_redirectUrl . 'denominations/period/' . (int)$form->getValue('period'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$denominations = new Denominations();
	$denomination = $denominations->fetchRow('id=' . $id);
	if(count($denomination))
	{
	$form->populate($denomination->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a denomination
	*/		
	public function deletedenominationAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$denominations = new Denominations();
	$where = 'id = ' . $id;
	$denominations->delete($where);
	}
	$this->_redirect($this->_redirectUrl . 'denominations');
	} else  {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$denominations = new Denominations();
	$this->view->denomination = $denominations->fetchRow('id=' . $id);
	}
	}
	}
	/** List all denominations
	*/	
	public function denominationsAction() {
	if($this->_getParam('period',false)){
	$period = $this->_getParam('period');
	$this->view->period = $period;
	$denoms = new Denominations();
	$this->view->paginator = $denoms->getDenominations($period,$this->_getParam('page'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	} 
	/** Add a medieval ruler
	*/	
	public function addmedievalrulerAction() {
	$dbaseID = $this->_getParam('id');
	$form = new MonarchForm();
	$form->submit->setLabel('Add biography to system');
	if(!is_null($dbaseID)){
	$form->dbaseID->setValue($dbaseID);
	}
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertData = array(
	'name' => $form->getValue('name'),
	'styled' => $form->getValue('styled'), 
	'dbaseID' => $form->getValue('dbaseID'), 
	'alias' => $form->getValue('alias'),
	'biography' => $form->getValue('biography'), 
	'born' => $form->getValue('born'), 
	'died' => $form->getValue('died'), 
	'date_from' => $form->getValue('date_from'), 
	'date_to' => $form->getValue('date_to'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms(), 
	'publishState' => $form->getValue('publishState')
	);
	$monarchs = new Monarchs();
	$update = $monarchs->insert($insertData);
	$this->_flashMessenger->addMessage('Biography for ' . $form->getValue('name') . ' created.');
	$this->_redirect($this->_redirectUrl . 'medruler/id/' . $dbaseID);
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** List mints
	*/			
	public function mintsAction() {
	$mints = new Mints();
	$this->view->paginator = $mints->getMintsListAllAdmin($this->_getAllParams());
	}
		
	/** Add a new mint
	 * 
	 */
	public function addmintAction() {
	$form = new MintForm();
	$form->submit->setLabel('Add a new mint to the system...');
	$form->valid->setValue(1);
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$mints = new Mints();
	$insertData = array(
	'mint_name' => $form->getValue('mint_name'),
	'period' => $form->getValue('period'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $mints->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'mints');
	$this->_flashMessenger->addMessage('A new mint has been created on the system!');
	} else  {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a mint
	*/		
	public function editmintAction() {
	if($this->_getParam('id',false)) {
	$form = new MintForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$mints = new Mints();
	$where = array();
	$where[] = $mints->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'mint_name' => $form->getValue('mint_name'),
	'period' => $form->getValue('period'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $mints->update($updateData, $where);
	$this->_flashMessenger->addMessage('Active mint information updated!');
	$this->_redirect($this->_redirectUrl . 'mints/period/' . $form->getValue('period'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$mints = new Mints();
	$mint = $mints->fetchRow('id='.$id);
	if(count($mint)) {
	$form->populate($mint->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a mint
	 * 
	 */
	public function deletemintAction() {
	$this->_flashMessenger->addMessage('Record deleted!');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$mints = new Mints();
	$where = 'id = ' . $id;
	$mints->delete($where);
	}
	$this->_redirect($this->_redirectUrl.'mints');
	} else  {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$mints = new Mints();
	$this->view->mint = $mints->fetchRow('id=' . $id);
	}
	}
	}
	/** List rulers
	 * 
	 */
	public function rulersAction() {
	$form = new RulerFilterForm();
	$ruler = $this->_getParam('ruler');
	$form->ruler->setValue($ruler);
	$this->view->form = $form;
	$rulers = new Rulers();
	$this->view->paginator = $rulers->getRulerListAdmin($this->_getAllParams()); 
	if ($this->_request->isPost() && !is_null($this->_getParam('submit'))) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
		$params = array_filter($formData);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if(!is_null($value)){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect('admin/numismatics/rulers/period/' . $this->_getParam('period') . '/' 
	. $query . '/');
	} else  {
	$form->populate($formData);
	}
	}
	}
	/** Add a ruler
	*/	
	public function addrulerAction(){
	$form = new RulerForm();
	$form->submit->setLabel('Add a new ruler or issuer to the system...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$rulers = new Rulers();
	$updateData = array(	
	'issuer' => $form->getValue('issuer'),
	'date1' => $form->getValue('date1'),
	'date2' => $form->getValue('date2'), 
	'valid' => $form->getValue('valid'),	
	'period' => $form->getValue('period'),	
	'created' => $this->getTimeForForms(),	
	'createdBy' => $this->getIdentityForForms()	
	);
	$update = $rulers->insert($updateData);
	$rulers = new Rulers();
	$this->_redirect($this->_redirectUrl . 'rulers/' . $form->getValue('period'));
	$this->_flashMessenger->addMessage($form->getValue('issuer') . ' has been added to the system!');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a ruler
	*/	
	public function editrulerAction() {
	if($this->_getParam('id',false)) {
	$form = new RulerForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$rulers = new Rulers();
	$updateData = array(
	'issuer' => $form->getValue('issuer'),
	'date1' => $form->getValue('date1'),
	'date2' => $form->getValue('date2'), 
	'valid' => $form->getValue('valid'),
	'period' => $form->getValue('period'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] = $rulers->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$update = $rulers->update($updateData, $where);
	$this->_flashMessenger->addMessage($form->getValue('issuer') . '\'s information updated!');
	$this->_redirect($this->_redirectUrl . 'rulers/period/' . $form->getValue('period'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$rulers = new Rulers();
	$ruler = $rulers->fetchRow('id=' . $id);
	if(count($ruler)) {
	$form->populate($ruler->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	/** Delete a ruler
	*/	
	public function deleterulerAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$rulers = new Rulers();
	$where = 'id = ' . $id;
	$rulers->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'rulers/period/' . $rulers['period']);
	}  else  {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$rulers = new Rulers();
	$this->view->ruler = $rulers->fetchRow('id=' . $id);
	}
	}
	}
	/** Roman ruler details = is this pointless?
	*/		
	public function romanrulerAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$rulers = new Rulers();
	$this->view->details = $rulers->getRulerProfileAdmin($id);
	$images = new RulerImages();
	$this->view->images = $images->getImages($id);
	$mints = new Mints();
	$this->view->mints = $mints->getRomanMintRulerAdmin($id);
	$denominations = new Denominations();
	$this->view->denoms = $denominations->getRomanRulerDenomAdmin($id);
	$reverses = new Revtypes();
	$this->view->reverses = $reverses->getTypesAdmin($id);
	$reece = new ReecePeriodEmperors();
	$this->view->reeces = $reece->fetchRow('ruler_id = '. $id );
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}	
	/** Iron age ruler details
	*/		
	public function ironagerulerAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$rulers = new Rulers();
	$this->view->details = $rulers->getRulerProfileAdmin($id);
	$images = new RulerImages();
	$this->view->images = $images->getImages($id);
	$mints = new Mints();
	$this->view->mints = $mints->getRomanMintRulerAdmin($id);
	$denominations = new Denominations();
	$this->view->denoms = $denominations->getRomanRulerDenomAdmin($id);	
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** medieval ruler details
	*/	
	public function medrulerAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$rulers = new Rulers();
	$this->view->details = $rulers->getRulerProfileAdmin($id);
	$images = new RulerImages();
	$this->view->images = $images->getImages($id);
	$mints = new Mints();
	$this->view->mints = $mints->getRomanMintRulerAdmin($id);
	$denominations = new Denominations();
	$this->view->denoms = $denominations->getRomanRulerDenomAdmin($id);
	$types = new MedievalTypes();
	$this->view->types = $types->getEarlyMedTypeRulerAdmin($id);
	$bios = new Monarchs();
	$this->view->bios = $bios->getBiography($id);
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Byzantine ruler details
	*/		
	public function byzrulerAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
 	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}	
	/** Greek and roman prov ruler detais
	*/	
	public function greekrulerAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
 	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Roman emperor biographical details
	*/	
	public function emperorbiosAction() {
	$emperors = new Emperors();
	$this->view->paginator = $emperors->getEmperorsAdminList($this->_getParam('page'));
	}
	/** Add an emperor
	*/	
	public function addemperorAction() {
	$form = new EmperorForm();
	$form->submit->setLabel('Add Emperor\'s details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$emperors = new Emperors();
	$insertData = array(
	'name' => $form->getValue('name'),
	'reeceID' => $form->getValue('reeceID'),
	'pasID' => $form->getValue('pasID'),
	'date_from' => $form->getValue('date_from'),
	'date_to' => $form->getValue('date_to'),
	'biography' => $form->getValue('biography'),
	'dynasty' => $form->getValue('dynasty'),
	'created' => $this->getTimeForForms(),	
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $emperors->insert($insertData);
	$this->_flashMessenger->addMessage('A new Emperor or issuer has been created!');
	$this->_redirect($this->_redirectUrl . 'emperorbios/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit an emperor
	*/	
	public function editemperorAction() {
	if($this->_getParam('id',false)) {
	$form = new EmperorForm();
	$form->submit->setLabel('Save Emperor\'s details');
	$form->details->setLegend('Biographical details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$emperors = new Emperors();
	$where = array();
	$where[] = $emperors->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'name' => $form->getValue('name'),
	'reeceID' => $form->getValue('reeceID'),
	'pasID' => $form->getValue('pasID'),
	'date_from' => $form->getValue('date_from'),
	'date_to' => $form->getValue('date_to'),
	'biography' => $form->getValue('biography'),
	'dynasty' => $form->getValue('dynasty'),
	'updated' => $this->getTimeForForms(),	
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $emperors->update($updateData,$where);
	$this->_flashMessenger->addMessage('Issuer details for ' . $form->getValue('name') . ' updated!');
	$this->_redirect($this->_redirectUrl . 'emperorbios/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$emperors = new Emperors();
	$emperor = $emperors->fetchRow('id=' . $id);
	if(count($emperor) > 0) {
	$form->populate($emperor->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	/** Delete an emperor
	*/	
	public function deleteemperorAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$emperors = new Emperors();
	$where = 'id = ' . $id;
	$emperors->delete($where);
    $this->_flashMessenger->addMessage('Issuer or Emperor details deleted! This cannot be undone.');
	}
	$this->_redirect($this->_redirectUrl . 'emperorbios/');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$emperors = new Emperors();
	$this->view->emperor = $emperors->fetchRow('id =' . $id);
	}
	}
	}
	/** Add a medieval type
	*/	
	public function addmedievaltypeAction() {
	$form = new AddMedievalTypeForm();
	$r = $this->_getParam('rulerid');
	$form->rulerID->setValue($r);
	$p = $this->_getParam('period');
	$form->periodID->setValue($p);
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertdata = array(
	'type' => $form->getValue('type'),
	'periodID' => $form->getValue('periodID'),
	'datefrom' => $form->getValue('datefrom'),
	'dateto' => $form->getValue('dateto'),
	'rulerID' => $form->getValue('rulerID'),
	'categoryID' => $form->getValue('categoryID'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$types = new MedievalTypes();
	$types->insert($insertdata);
	$this->_flashMessenger->addMessage('You entered the type: <em>' . $form->getValue('type')
	. '</em> successfully. It is now available for use.');
	$this->_redirect($this->_redirectUrl . 'medruler/id/' . $form->getValue('rulerID'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a medieval type
	*/	
	public function editmedievaltypeAction() {
	$form = new AddMedievalTypeForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$id = $this->_getParam('id'); 
	$insertdata = array(
	'type' => $form->getValue('type'),
	'periodID' => $form->getValue('periodID'),
	'datefrom' => $form->getValue('datefrom'),
	'dateto' => $form->getValue('dateto'),
	'rulerID' => $form->getValue('rulerID'),
	'categoryID' => $form->getValue('categoryID'),
	'updated' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm')
	);
	$types = new MedievalTypes();
	$where = array();
	$where[] = $types->getAdapter()->quoteInto('id = ?', $id);
	$types->update($insertdata,$where);
	$this->_flashMessenger->addMessage('You updated: <em>' . $form->getValue('type') 
	. '</em> successfully. It is now available for use.');
	$this->_redirect($this->_redirectUrl);
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$types = new MedievalTypes();
	$type = $types->fetchRow('id=' . (int)$id);
	$form->populate($type->toArray());
	}
	}
	}
 	/** List reece periods
	*/
	public function reeceperiodsAction(){
	$reeces = new Reeces();
	$this->view->reeces = $reeces->getReecesAdmin();
	}

	/** Add reece period
	*/
	public function addreeceperiodAction() {
	$form = new ReecePeriodForm();
	$form->submit->setLabel('Add a new Reece Period');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$reeces = new Reeces();
	$insertData = array(
	'period_name' => $form->getValue('period_name'),
	'description' =>$form->getValue('description'),
	'date_range' => $form->getValue('date_range'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $reeces->insert($insertData);
	$this->_flashMessenger->addMessage('A new Reece Period has been created!');
	$this->_redirect($this->_redirectUrl . 'reeceperiods/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a reece period
	*/
	public function editreeceperiodAction() {
	if($this->_getParam('id',false)){
	$form = new ReecePeriodForm();
	$form->submit->setLabel('Save Reece period details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$reeces = new Reeces();
	$where = array();
	$where[] = $reeces->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'period_name' => $form->getValue('period_name'),
	'description' =>$form->getValue('description'),
	'date_range' => $form->getValue('date_range'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $reeces->update($updateData,$where);
	$this->_flashMessenger->addMessage('Reece Period details updated!');
	$this->_redirect($this->_redirectUrl . 'reeceperiods/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$reeces = new Reeces();
	$reece = $reeces->fetchRow('id=' . $id);
	if(count($reece)) {
	$form->populate($reece->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	/** Delete a reece period
	*/
	function deletereeceperiodAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$reeces = new Reeces();
	$where = 'id = ' . $id;
	$reeces->delete($where);
	}
	$this->_redirect($this->_redirectUrl . 'reeceperiods/');
	$this->_flashMessenger->addMessage('Reece Period details deleted! This cannot be undone.');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$reeces = new Reeces();
	$this->view->reece = $reeces->fetchRow('id =' . $id);
	}
	}
	}
	/** List reverse types
	*/	
	public function reversetypesAction() {
	$reverses = new Revtypes();
	$this->view->reverses = $reverses->getReverseTypeList(1);
	$uncommonreverses = new Revtypes();
	$this->view->uncommonreverses = $uncommonreverses->getReverseTypeList(2);
	}
	/** Add reverse types
	*/	
	public function addreversetypeAction() {
	$form = new ReverseTypeForm();
	$form->submit->setLabel('Add a new reverse type');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$reverses = new Revtypes();
	$insertData = array(
	'type' =>  $form->getValue('type'),
	'description' => $form->getValue('description'),
	'translation' => $form->getValue('translation'),
	'gendate' => $form->getValue('gendate'),
	'reeceID' => $form->getValue('reeceID'),
	'common' => $form->getValue('common'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$insert = $reverses->insert($insertData);
	$this->_flashMessenger->addMessage('The reverse type ' . $form->getValue('type') 
	. ' has been created.');
	$this->_redirect($this->_redirectUrl . 'reversetypes');
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage($this->_formErrors);
	}
	}
	}
	/** Edit reverse type
	*/
	public function editreversetypeAction() {
	if($this->_getParam('id',false)) {
	$form = new ReverseTypeForm();
	$form->submit->setLabel('Save reverse type\'s details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$reverses = new Revtypes();
	$where = array();
	$where[] = $reverses->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'type' => $form->getValue('type'),
	'description' => $form->getValue('description'),
	'translation' => $form->getValue('translation'),
	'gendate' => $form->getValue('gendate'),
	'reeceID' => $form->getValue('reeceID'),
	'common' => $form->getValue('common'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $reverses->update($updateData,$where);
	$this->_flashMessenger->addMessage('Reverse type details for '
	. $form->getValue('type') . ' updated!');
	$this->_redirect($this->_redirectUrl.'reversetypes/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$reverses = new Revtypes();
	$reverse = $reverses->fetchRow('id=' . $id);
	$form->populate($reverse->toArray());
	}
	}
	} else 	{
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete reverse type
	*/	
	public function deletereversetypeAction() {
	$this->_flashMessenger->addMessage('Reverse type  deleted!');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$reverses = new Revtypes();
	$where = 'id = ' . $id;
	$reverses->delete($where);
	}
	$this->_redirect($this->_redirectUrl . 'reversetypes/');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$reverses = new Revtypes();
	$this->view->reverse = $reverses->fetchRow('id =' . $id);
	}
	}
	}
	/** Add an image for a ruler
	*/
	public function addrulerimageAction() {
	$form = new AddRulerImageForm();	
	$form->rulerID->setValue($this->_getParam('rulerid'));
	$form->submit->setLabel('Add an image for a ruler');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();	{
	if ($form->isValid($formData)) {
	$upload = new Zend_File_Transfer_Adapter_Http();
	$upload->addValidator('NotExists', false,array('./images/rulers/'));
	$filesize = $upload->getFileSize();
	if($upload->isValid()) {
	$filename = $form->getValue('image');
	$rulerID = $formData['rulerID'];
	$caption = $formData['caption'];
	$insertData = array();
	$insertData['filename'] = $filename;
	$insertData['caption'] = $caption;
	$insertData['rulerID'] = $rulerID;
	$insertData['created'] = $this->getTimeForForms();
	$insertData['createdBy'] = $this->getIdentityForForms();
	$insertData['filesize'] = $filesize;
	foreach ($insertData as $key => $value) {
	if (is_null($value) || $value=="") {
		unset($insertData[$key]);
		}
	}	
	$location = './images/rulers/';
	$largepath = './images/rulers/resized/';
	$smallpath = './images/rulers/thumbnails/';
	$name = substr($filename, 0, strrpos($filename, '.')); 

	$ext = '.jpg';
	
	$converted = $name.$ext;
	$convertlarge  = $largepath.$converted;
	$original = $location.$converted;
	$convertsmall = $smallpath.$converted;
	//create medium size
	$phMagick = new phMagick($original, $convertlarge);
	$phMagick->resize(300,0);
//	Zend_Debug::dump($phMagick->convert());
//exit;
	//create thumbnail size
	$phMagick = new phMagick($original, $convertsmall);
	$phMagick->resize(100,0);
	$phMagick->convert();

	//Zoom it baby
	$rulers = new RulerImages();
	$upload->receive();
	$rulers->insert($insertData);
 	$this->_flashMessenger->addMessage('The image has been resized.');
	$this->_redirect($this->_redirectUrl . 'romanruler/id/' . $this->_getParam('rulerid')); 
	} else {
	$this->_flashMessenger->addMessage('There is a problem with your upload. Probably that 
	image exists.');
	$this->view->errors = $upload->getMessages();
	} 
	} else { 
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	}
	/** List degrees of wear
	*/	
	public function degreesofwearAction() {
	$wears = new Weartypes();
	$this->view->degrees = $wears->getWearTypesAdmin();
	}
	/** Add degree of wear details
	*/
	public function adddegreeofwearAction() {
	$form = new DegreeOfWearForm();
	$form->details->setLegend('Add a new degree of wear term');
	$form->submit->setLabel('Submit term\'s details');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$degrees = new Weartypes();
	$insertData = array(
	'term' =>  $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $degrees->insert($insertData);
	$this->_flashMessenger->addMessage('New degree of wear term entered');
	$this->_redirect($this->_redirectUrl . 'degreesofwear/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a degree of wear
	*/
	public function editdegreeofwearAction() {
	if($this->_getParam('id',false)) {
	$form = new DegreeOfWearForm();
	$form->details->setLegend('Edit degree of wear details');
	$form->submit->setLabel('Submit term detail changes');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$degrees = new WearTypes();
	$where = array();
	$where[] = $degrees->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $degrees->update($updateData,$where);
	$this->_flashMessenger->addMessage('Degree of wear: ' . $form->getValue('term') . ' updated!');
	$this->_redirect($this->_redirectUrl . 'degreesofwear');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$degrees = new Weartypes();
	$degree = $degrees->fetchRow('id=' . $id);
	if(count($degree)){
	$form->populate($degree->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a degree of wear
	*/	
	public function deletedegreeofwearAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$wears = new Weartypes();
	$where = 'id = ' . $id;
	$wears->delete($where);
	}
	$this->_flashMessenger->addMessage('Degree of wear deleted!');
	$this->_redirect($this->_redirectUrl.'degreesofwear/');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$wears = new Weartypes();
	$this->view->degree = $wears->fetchRow('id ='.$id);
	}
	}
	}
	/** Edit a medieval ruler
	*/
	public function editmedievalrulerAction() {
	if($this->_getParam('id',false)) {
	$this->view->headTitle('Edit ruler biography');
	$form = new MonarchForm();
	$form->submit->setLabel('Edit a biography.');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$id = $this->_getParam('id'); 
	$updateData = array(
	'name' => $form->getValue('name'),
	'styled' => $form->getValue('styled'), 
	'dbaseID' => $form->getValue('dbaseID'),
	'alias' => $form->getValue('alias'),
	'biography' => $form->getValue('biography'), 
	'born' => $form->getValue('born'), 
	'died' => $form->getValue('died'), 
	'date_from' => $form->getValue('date_from'), 
	'date_to' => $form->getValue('date_to'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms(), 
	'publishState' => $form->getValue('publishState')
	);
	$monarchs = new Monarchs();
	$where = array();
	$where[] = $monarchs->getAdapter()->quoteInto('dbaseID = ?', $id);
	$update = $monarchs->update($updateData, $where);
	$this->_flashMessenger->addMessage('Monarch data updated.');
	$this->_redirect('admin/numismatics/medruler/id/'.$id);
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$monarchs = new Monarchs();
	$monarch = $monarchs->fetchRow('dbaseID ='.(int)$id);
	$this->view->headTitle('Edit biography for :  '. $monarch['name']);
	if(count($monarch))
	{
	$form->populate($monarch->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}	
	/** Link a ruler to a denomination
	*/	
	public function rulertodenominationAction() {
	$form = new AddDenomToRulerForm();
	$rulerid = $this->_getParam('rulerid');
	$period = $this->_getParam('period');
	$denoms = new Denominations();
	$denomsList = $denoms->getDenomsAdd($period);
	$form->ruler_id->setValue($rulerid);
	$form->period_id->setValue($period);
	$form->denomination_id->addMultiOptions($denomsList);
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$denoms = new DenomRulers();
	$insertData = array(
	'ruler_id' => $form->getValue('ruler_id'),
	'denomination_id' => $form->getValue('denomination_id'),
	'period_id' => $form->getValue('period_id'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$denoms->insert($insertData);
	$this->_flashMessenger->addMessage('A new denomination has been added.');
	if($period == 21){
	$this->_redirect($this->_redirectUrl.'romanruler/id/'.$rulerid);
	} else {
		$this->_redirect($this->_redirectUrl.'medruler/id/'.$rulerid);
	}
	} else {
	
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	
	}
	}
	}
	/** Link a ruler to a mint
	*/	
	public function rulertomintAction() {
	$form = new AddMintToRulerForm();
	$rulerid = $this->_getParam('rulerid');
	$period = $this->_getParam('period');
	$mints = new Mints();
	$mintsList = $mints->getMints($period);
	$form->ruler_id->setValue($rulerid);
	$form->mint_id->addMultiOptions($mintsList);
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$mintrulers = new MintsRulers();
	$insertData = array(
	'ruler_id'=> $form->getValue('ruler_id'),
	'mint_id' => $form->getValue('mint_id'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$mintrulers->insert($insertData);
	$this->_flashMessenger->addMessage('A new mint has been entered.');
	if($period == 21){
	$this->_redirect($this->_redirectUrl . 'romanruler/id/' . $rulerid);
	} else {
	$this->_redirect($this->_redirectUrl . 'medruler/id/' . $rulerid);	
	}
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Link a ruler to a reverse
	*/	
	public function rulertoreversetypeAction() {
	$form = new AddReverseToRulerForm();
	$rulerid = $this->_getParam('rulerid');
	$form->rulerID->setValue($rulerid);
	$reversetypes = new Revtypes();
	$reversetypesList = $reversetypes->getRevTypes();
	$form->reverseID->addMultiOptions($reversetypesList);
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$reverses = new RulerRevType();
	$insertData = array(
	'rulerID' => $form->getValue('rulerID'),
	'reverseID' => $form->getValue('reverseID'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$reverses->insert($insertData);
	$this->_flashMessenger->addMessage('A new mint has been entered.');
	$this->_redirect($this->_redirectUrl . 'romanruler/id/' . $rulerid);
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	
	/** List Iron Age tribes
	*/
	public function tribesAction(){
	$tribes = new Tribes();
	$this->view->tribes = $tribes->getTribesListAdmin($this->_getParam('page'));
	}
	/** Edit iron age tribe details
	 * 
	 */
	public function edittribeAction() {
	if($this->_getParam('id',false)) {
	$form = new IronAgeTribeForm();
	$form->details->setLegend('Edit tribe\'s details');
	$form->submit->setLabel('Submit tribe detail changes');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$tribes = new Tribes();
	$where = array();
	$where[] = $tribes->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'tribe' =>  $form->getValue('tribe'),
	'description' => $form->getValue('description'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $tribes->update($updateData,$where);
	$this->_flashMessenger->addMessage('Details for ' . $form->getValue('tribe') . ' updated!');
	$this->_redirect($this->_redirectUrl . 'tribes/');
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage($this->_formErrors);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$tribes = new Tribes();
	$tribe = $tribes->fetchRow('id=' . $id);
	if(count($tribe)){
	$form->populate($tribe->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Add a tribe
	*/	
	public function addtribeAction() {
	$form = new IronAgeTribeForm();
	$form->details->setLegend('Add a new tribe\'s details');
	$form->submit->setLabel('Submit tribe\'s details');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$tribes= new Tribes();
	$insertData = array(
	'tribe' =>  $form->getValue('tribe'),
	'description' => $form->getValue('description'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $tribes->insert($insertData);
	$this->_flashMessenger->addMessage('You have created the iron age tribe: '
	. $form->getValue('tribe'));
	$this->_redirect($this->_redirectUrl . 'tribes');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Delete a tribe
	*/		
	public function deletetribeAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$tribes = new Tribes();
	$where = 'id = ' . $id;
	$tribes->delete($where);
	}
	$this->_redirect('/admin/tribes/');
	$this->_flashMessenger->addMessage('Tribe deleted!');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$tribes = new Tribes();
	$this->view->tribe = $tribes->fetchRow('id ='.$id);
	}
	}
	}	
	/** List iron age regions
	*/	
	public function regionsAction() {
	$regions = new Geography();
	$this->view->regions = $regions->getIronAgeRegionsAdmin();
	}
	/** Add iron age region
	*/
	public function addregionAction() {
	$form = new IronAgeRegionForm();
	$form->details->setLegend('Add a new region\'s details');
	$form->submit->setLabel('Submit region\'s details');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$geog = new Geography();
	$insertData = array(
	'area' => $form->getValue('area'),
	'region' => $form->getValue('region'),
	'tribe' =>  $form->getValue('tribe'),
	'description' => $form->getValue('description'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $geog->insert($insertData);
	$this->_flashMessenger->addMessage('You have created the iron age tribe: '
	. $form->getValue('tribe'));
	$this->_redirect($this->_redirectUrl . 'regions');
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage($this->_formErrors);
	}
	}
	}
	/** Edit iron age region
	*/	
	public function editregionAction() {
	if($this->_getParam('id',false)) {
	$form = new IronAgeRegionForm();
	$form->details->setLegend('Edit region\'s details');
	$form->submit->setLabel('Submit region detail changes');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$geog = new Geography();
	$where = array();
	$where[] = $geog->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'area' => $form->getValue('area'),
	'region' => $form->getValue('region'),
	'tribe' =>  $form->getValue('tribe'),
	'description' => $form->getValue('description'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $geog->update($updateData,$where);
	$this->_flashMessenger->addMessage('Details updated!');
	$this->_redirect('/admin/regions/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$geog = new Geography();
	$geo = $geog->fetchRow('id=' . $id);
	if(count($geo)){
	$form->populate($geo->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete iron age region
	*/
	public function deleteregionAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$geogs = new Geography();
	$where = 'id = ' . $id;
	$geogs->delete($where);
	}
	$this->_flashMessenger->addMessage('Region deleted!');
	$this->_redirect($this->_redirectUrl . 'regions');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$geogs = new Geography();
	$this->view->geog = $geogs->fetchRow('id =' . $id);
	}
	}
	}
	/** List medieval categories
	*/			
	public function categoriesAction() {
	if($this->_getParam('period',false)) {
	$categories = new CategoriesCoins();
	$this->view->categories = $categories->getCategoriesPeriodAdmin((int)$this->_getParam('period'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Add a medieval category
	*/	
	public function addcategoryAction() {
	$form = new MedCategoryForm();
	$form->details->setLegend('Add a new category\'s details');
	$form->submit->setLabel('Submit category details');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$cats = new CategoriesCoins();
	$insertData = array(
	'category' =>  $form->getValue('category'),
	'periodID' => $form->getValue('periodID'),
	'description' => $form->getValue('description'), 
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $cats->insert($insertData);
	$this->_flashMessenger->addMessage('The medieval category of ' . $form->getValue('category')
	. ' has been created.');
	$this->_redirect($this->_redirectUrl . 'categories/period/' . $form->getValue('periodID'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a medieval category
	*/
	public function editcategoryAction() {
	if($this->_getParam('id',false)) {
	$form = new MedCategoryForm();
	$form->details->setLegend('Edit category details');
	$form->submit->setLabel('Submit category detail changes');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$cats = new CategoriesCoins();
	$where = array();
	$where[] = $cats->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'category' =>  $form->getValue('category'),
	'periodID' => $form->getValue('periodID'),
	'description' => $form->getValue('description'),
	 'updated' => $this->getTimeForForms(),
	 'updatedBy' => $this->getIdentityForForms()
	 );
	$update = $cats->update($updateData,$where);
	$this->_flashMessenger->addMessage('Reverse type details for ' . $form->getValue('type') 
	. ' updated!');
	$this->_redirect($this->_redirectUrl . 'categories/period/' . $form->getValue('periodID'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$cats = new CategoriesCoins();
	$cat = $cats->fetchRow('id=' . $id);
	if(count($cat)) {
	$form->populate($cat->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a medieval category
	*/	
	public function deletecategoryAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$cats = new CategoriesCoins();
	$where = 'id = ' . $id;
	$cats->delete($where);
	}
	$this->_flashMessenger->addMessage('Medieval category deleted!');
	$this->_redirect($this->_redirecturl.'categories');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$cats = new CategoriesCoins();
	$this->view->cats = $cats->fetchRow('id =' . $id);
	}
	}
	}
	/** Medieval type list
	*/	
	public function typesAction() {
	$types = new MedievalTypes();
	$this->view->paginator = $types->getTypesByPeriodAdmin($this->_getAllParams());
	}
	/** Add a medieval type
	*/	
	public function addtypeAction() {
	$form = new MedTypeForm();
	$form->details->setLegend('Add a new type\'s details');
	$form->submit->setLabel('Submit type details');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$medtypes = new MedievalTypes();
	$insertData = array(
	'type' =>  $form->getValue('type'),
	'rulerID' => $form->getValue('rulerID'),
	'periodID' => $form->getValue('periodID'),
	'categoryID' => $form->getValue('categoryID'),
	'datefrom' => $form->getValue('datefrom'),
	'dateto' => $form->getValue('dateto'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $medtypes->insert($insertData);
	$this->_flashMessenger->addMessage('The medieval type '
	. $form->getValue('type') . ' has been created.');
	$this->_redirect($this->_redirectUrl . 'types/period/' . $form->getValue('periodID'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}

	/** Edit a medieval type
	*/
	public function edittypeAction() {
	if($this->_getParam('id',false)) {
	$form = new MedTypeForm();
	$form->details->setLegend('Edit type details');
	$form->submit->setLabel('Submit type detail changes');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$medtypes = new MedievalTypes();
	$where = array();
	$where[] = $medtypes->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'type' => $form->getValue('type'),
	'rulerID' => $form->getValue('rulerID') ,
	'periodID' => $form->getValue('periodID'),
	'categoryID' => $form->getValue('categoryID'),
	'datefrom' => $form->getValue('datefrom'),
	'dateto' => $form->getValue('dateto'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	 );
	$update = $medtypes->update($updateData,$where);
	$this->_flashMessenger->addMessage('Reverse type details for '
	. $form->getValue('type') . ' updated!');
	$this->_redirect($this->_redirectUrl . 'types/period/' . $form->getValue('periodID'));
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$medtypes = new MedievalTypes();
	$medtype = $medtypes->fetchRow('id='.$id);
	if(count($medtype)){
	$form->populate($medtype->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a medieval type
	*/	
	public function deletetypeAction() {
	$this->_flashMessenger->addMessage('Medieval type  deleted!');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$medtypes = new MedievalTypes();
	$where = 'id = ' . $id;
	$medtypes->delete($where);
	}
	$this->_redirect($this->_redirectUrl.'types');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$medtypes = new MedievalTypes();
	$this->view->medtype = $medtypes->fetchRow('id ='.$id);
	}
	}
	}
	/** List roman dynasties
	*/	
	public function dynastiesAction() {
	$dynasties = new Dynasties();
	$this->view->dynasties = $dynasties->getDynastyListAdmin();
	}
	/** Add a roman dynasty
	*/	
	public function adddynastyAction() {
	$form = new DynastyForm();
	$form->details->setLegend('Add a new dynasty\'s details');
	$form->submit->setLabel('Submit dynasty\'s details');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$dynasties = new Dynasties();
	$insertData = array(
	'dynasty' => $form->getValue('dynasty'),
	'valid' => $form->getValue('valid') ,
	'description' => $form->getValue('description'),
	'date_from' => $form->getValue('date_from'),
	'date_to' => $form->getValue('date_to'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $dynasties->insert($insertData);
	$this->_flashMessenger->addMessage('Dynasty ' . $form->getValue('dynasty') 
	. ' has been created.');
	$this->_redirect($this->_redirectUrl.'dynasties');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a roman dynasty
	*/	
	public function editdynastyAction() {
	if($this->_getParam('id',false)) {
	$form = new DynastyForm();
	$form->details->setLegend('Edit dynasty details');
	$form->submit->setLabel('Submit dynastic detail changes');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$dynasties = new Dynasties();
	$where = array();
	$where[] = $dynasties->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'dynasty' => $form->getValue('dynasty'),
	'valid' => $form->getValue('valid') ,
	'description' => $form->getValue('description'),
	'date_from' => $form->getValue('date_from'),
	'date_to' => $form->getValue('date_to'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	 );
	$update = $dynasties->update($updateData,$where);
	$this->_flashMessenger->addMessage('Dynasty details for ' . $form->getValue('dynasty')
	. ' updated!');
	$this->_redirect($this->_redirectUrl.'dynasties');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$dynasties = new Dynasties();
	$dynasty = $dynasties->fetchRow('id=' . $id);
	if(count($dynasty)){
	$form->populate($dynasty->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a roman dynasty
	*/	
	public function deletedynastyAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$dynasties = new Dynasties();
	$where = 'id = ' . $id;
	$dynasties->delete($where);
	$this->_flashMessenger->addMessage('Dynasty  deleted!');
	}
	$this->_redirect($this->_redirectUrl.'dynasties');
	} 
	else 
	{
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$dynasties = new Dynasties();
	$this->view->dynasty = $dynasties->fetchRow('id ='.$id);
	}
	}
	}
	/** Add a reference type 
	*/	
	public function addrefAction() {
	$form = new CoinClassForm();
	$form->details->setLegend('Add a new coin reference volume');
	$form->submit->setLabel('Submit details');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$classes = new CoinClass();
	$insertData = array(
	'referenceName' => $form->getValue('referenceName'),
	'valid' => $form->getValue('valid') ,
	'period' => $form->getValue('period'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $classes->insert($insertData);
	$this->_flashMessenger->addMessage('New reference volume added');
	$this->_redirect('/admin/numismatics/refs/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	/** Edit a reference type
	*/	
	public function editrefAction() {
	if($this->_getParam('id',false)) {
	$form = new CoinClassForm();
	$form->details->setLegend('Edit reference volume details');
	$form->submit->setLabel('Submit changes');
	$this->view->form =$form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$classes = new CoinClass();
	$where = array();
	$where[] = $classes->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'referenceName' => $form->getValue('referenceName'),
	'valid' => $form->getValue('valid') ,
	'period' => $form->getValue('period'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	 );
	$update = $classes->update($updateData,$where);
	$this->_flashMessenger->addMessage('Reference volume details changed');
	$this->_redirect('/admin/numismatics/refs/');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$classes = new CoinClass();
	$class = $classes->fetchRow('id=' . $id);
	if(count($class)){
	$form->populate($class->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** List reference types
	*/	
	public function refsAction() {
	$refs = new CoinClass();
	$this->view->refs = $refs->getRefs();
	}
	
	public function addreeceAction(){
	$form = new ReeceEmperorForm();
	$form->submit->setLabel('Submit details');
	$this->view->form =$form;
		$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
    $updateData = array(
    'periodID' => '21',
    'ruler_id' => $this->_getParam('rulerid'),
    'reeceperiod_id' => $form->getValue('reeceperiod_id')
    );
	$periods = new ReecePeriodEmperors();
	$update = $periods->add($updateData);
	$this->_redirect('/admin/numismatics/romanruler/id/' . $this->_getParam('rulerid'));
	$this->_flashMessenger->addMessage('Period added');
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	
	public function editreeceAction(){
	if($this->_getparam('id',false)) {
	$form = new ReeceEmperorForm();
	$form->submit->setLabel('Update details' );
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
    $updateData = array(
    'periodID' => 21,
    'ruler_id' => $this->_getParam('rulerid'),
    'reeceperiod_id' => $form->getValue('reeceperiod_id')
    );
	$periods = new ReecePeriodEmperors();
	$where = array();
	$where[] = $periods->getAdapter()->quoteInto('ruler_id = ?', (int)$this->_getParam('rulerid'));
	$update = $periods->update($updateData, $where);
	$this->_flashMessenger->addMessage('Reece period updated');
	$this->_redirect('/admin/numismatics/romanruler/id/' . $this->_getParam('rulerid'));
	} else {
	$form->populate($form->getValues());
	}
	} else {
	$id = (int)$this->_request->getParam('rulerid', 0);
	if ($id > 0) {
	$periods = new ReecePeriodEmperors();
	$activity = $periods->fetchRow('ruler_id='.(int)$id);
	if(count($activity))	{
	$form->populate($activity->toArray());
	} else {
	throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
