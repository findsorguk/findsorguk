<?php
/** Controller for adminstering various system tasks
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_SystemController extends Pas_Controller_Action_Admin {

	protected $_awsKey = NULL;
	protected $_awsSecret = NULL;
	protected $_config = NULL;
	protected $_s3 = NULL;
	protected $_mysql  		= '/usr/bin/mysql';
	protected $_mysqldump  	= '/usr/bin/mysqldump';
	protected $_tar  		= '/bin/tar';
	protected $_days = NULL;
	protected $_suffix = NULL;
	protected $_tarDump = NULL;
	protected $_sqlDump = NULL;
	protected $_path = '/home/backups/';
	protected $_cache;

	const BUCKET = 'dbback';
	const NAME = 'antiquities';
	const BACKUPNAME = 'ant';

	/** Set up the ACL and contexts, assign variables
	*/
	public function init() {
	$this->_helper->_acl->allow('admin',null);
	$this->_cache = Zend_Registry::get('cache');
 	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->view->messages = $this->_flashMessenger->getMessages();
	$this->_config = Zend_Registry::get('config');
	$this->_awsKey = $this->_config->webservice->amazonS3->accesskey;
	$this->_awsSecret = $this->_config->webservice->amazonS3->secretkey;
	$this->_days = 5;
	$this->_suffix = date("Ymd");
	$this->_s3 = new Zend_Service_Amazon_S3($this->_awsKey, $this->_awsSecret);
	$this->_sqlDump = $this->_path . self::BACKUPNAME . '_' . $this->_suffix . '.sql';
	$this->_tarDump = $this->_sqlDump . '.tar.gz';
	}
	/** List available actions via view helpers
	*/
	public function indexAction() {
	}

	/** List all roles held on system
	*/
	public function rolesAction() {
	$roles = new Roles();
	$this->view->roles = $roles->getAllRoles();
	}
	/** View a system role
	*/
	public function roleAction(){
	$roles = new Roles();
	$this->view->roles = $roles->getRole($this->_getParam('id'));
	$users = new Users();
	$this->view->members = $users->getRolesMembers($this->_getParam('id'),$this->_getParam('page'));
	}
	/** Edit a system role
	*/
	public function editsystemroleAction(){
	$form = new SystemRoleForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$roles = new Roles();
	$updateData = array(
	'role' => $form->getValue('role'),
	'description' => $form->getValue('description'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] =  $roles->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $roles->update($updateData,$where);
	$this->_flashMessenger->addMessage($form->getValue('role') . '\'s details updated.');
	$this->_redirect('/admin/systemroles/');
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$roles = new Roles();
	$roles = $roles->fetchRow('id=' . $id);
	$form->populate($roles->toArray());
	}
	}
	}
	/** Clean the entire cache
	*/
	public function cleancacheAction() {
	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender();
	$this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
	$this->_flashMessenger->addMessage('Cache cleaned');
	$this->_redirect('/admin/system/');
	}
	/** Clean just the old cached data
	*/
	public function cleanoldcacheAction() {
	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender();
	$this->_cache->clean(Zend_Cache::CLEANING_MODE_OLD);
	$this->_flashMessenger->addMessage('Cache cleaned');
	$this->_redirect('/admin/system/');
	}
	/** Display the server's configuration
	*/
	public function serverconfigAction() {
	}
	/** List all s3 objects stored
	*/
	public function s3Action() {
	$s3 = $this->_s3;
	$backupslist  = $s3->getObjectsByBucket('dbback');
	$data = array();
	foreach($backupslist as $name) {
	$amazon = array(
	'name' => 'dbback/' . $name);
	$details = $s3->getInfo('dbback/' . $name);
	foreach($details as $k => $v){
	$amazon[$k] = $v;
	}
	$data[] = $amazon;
	}
	$this->view->data = $data;
	}
	/** Send email notifications
	*/
	public function notify($message) {
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
	$mail->setBodyText($message);
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addTo($this->_config->admin->email);
	$mail->setSubject('Backup process status on main server');
	$mail->send();
	}
	/** Clean up the old backup files
	*/
	private function cleanUp() {
	$s3 = $this->_s3;
    $aS3Files = $s3->getObjectsByBucket(self::BUCKET);
    foreach($aS3Files as $as3){
    $data = $s3->getInfo('dbback/' . $as3);
    if ($data['mtime']+($this->_days * 24 * 60 * 60) < time( 'void' )) {
    $deleted = $s3->removeObject('dbback/'.$as3);
    }
    }
	}
	/** Back up the databases
	*/
	private function backup() {
	set_time_limit(0);
	$pw = $this->_config->db->params->password;
	$username = $this->_config->db->params->username;
	// Dump them
    $name = self::NAME;
    $backupdb = exec("$this->_mysqldump -u$username -p$pw $name > $this->_sqlDump");
    // Tar them up
    $tardbsql = exec("$this->_tar -cpzf $this->_tarDump $this->_sqlDump");
    if(file_exists($this->_sqlDump)){
    unlink($this->_sqlDump);
    }
	return $this->_tarDump;
	}
	/** Kick off a backup process
	*/
	public function s3backupAction() {
	$s3 = $this->_s3;
	$available = $s3->isObjectAvailable('dbback/' . $this->_suffix . '.sql.tar.gz');
	if($available == false){
	if(!file_exists($this->_tarDump)){
	$backup = $this->backup();
	$ret = $this->transfer($backup);
	} else {
	$backup = $this->_tarDump;
	$ret = $this->transfer($backup);
	}
	echo "Success: " . ($ret ? 'Yes' : 'No');
	if($ret == true){
	$deleted = $this->cleanUp();
	$this->notify('The backup process was successful');
	unlink($backup);
	} else {
	$this->notify('The backup process was unsuccessful');
	}
	} else {
	echo "The backup file already exists at the S3 server";
	$this->notify('The backup file already exists at the S3 server');
	}
	}
	/** Transfer data to amazon's server
	*/
	private function transfer($backup) {
	$s3 = $this->_s3;
	$bucketName = 'dbback';
    $perms      = array(
    	    Zend_Service_Amazon_S3::S3_ACL_HEADER =>
            Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ
    );
 	$backupName = $this->_suffix . '.sql.tar.gz';
    $ret = $s3->putFileStream(
        $backup,
        $bucketName . '/' . $backupName,
        $perms
    );
    return $ret;

	}


	}