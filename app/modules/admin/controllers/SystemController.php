<?php

/** Controller for adminstering various system tasks
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Zend_Registry
 * @uses Roles
 * @uses Users
 * @uses Zend_Service_Amazon_S3
 * @uses SystemRoleForm
 * @uses Zend_Mail
 *
 */
class Admin_SystemController extends Pas_Controller_Action_Admin
{

    /** The aws key
     * @access protected
     * @var string
     */
    protected $_awsKey;

    /** The aws secret
     * @access protected
     * @var string
     */
    protected $_awsSecret;

    /** Get the aws key from config
     * @access public
     * @return string
     */
    public function getAwsKey()
    {
        $this->_awsKey = $this->_helper->config()->webservice->amazonS3->accesskey;
        return $this->_awsKey;
    }

    /** Get the aws secret from config
     * @access public
     * @return string
     */
    public function getAwsSecret()
    {
        $this->_awsSecret = $this->_helper->config()->webservice->amazonS3->secretkey;
        return $this->_awsSecret;
    }

    /** The S3 class
     * @access protected
     * @var \Zend_Service_Amazon_S3
     */
    protected $_s3;

    /** Get the S3 Class
     * @access public
     * @return \Zend_Service_Amazon_S3
     */
    public function getS3()
    {
        $this->_s3 = new Zend_Service_Amazon_S3(
            $this->getAwsKey(), $this->getAwsSecret()
        );
        return $this->_s3;
    }

    /** The mysql path
     * @access protected
     * @var string
     */
    protected $_mysql = '/usr/bin/mysql';

    /** Path to mysqldump
     * @access protected
     * @var string
     */
    protected $_mysqldump = '/usr/bin/mysqldump';

    /** Path to tar
     * @access protected
     * @var string
     */
    protected $_tar = '/bin/tar';

    /** Number of days to retain
     * @access protected
     * @var integer
     */
    protected $_days = 5;

    /** The suffix
     * @access protected
     * @var string
     */
    protected $_suffix;

    /** The tar dump path
     * @access protected
     * @var string
     */
    protected $_tarDump;

    /** The sql dump path
     * @access protected
     * @var string
     */
    protected $_sqlDump;

    /** Path to backups
     * @access protected
     * @var string
     */
    protected $_path;

    /** The bucket name
     *
     */
    const BUCKET = 'dbback';

    /** The name
     *
     */
    const NAME = 'antiquities';

    /** Back up name
     *
     */
    const BACKUPNAME = 'ant';

    /** Get the suffix
     * @access public
     * @return string
     */
    public function getSuffix()
    {
        $this->_suffix = date("Ymd");
        return $this->_suffix;
    }

    /** The roles model
     * @access protected
     * @var \Roles
     */
    protected $_roles;

    /** Get the roles model
     * @access public
     * @return \Roles
     */
    public function getRoles()
    {
        $this->_roles = new Roles();
        return $this->_roles;
    }


    /** Get the tar dump path
     * @access public
     * @return string
     */
    public function getTarDump()
    {
        $this->_tarDump = $this->getSqlDump() . '.tar.gz';
        return $this->_tarDump;
    }

    /** Get the sql dump path
     * @access public
     * @return string
     */
    public function getSqlDump()
    {
        $this->_sqlDump = $this->getPath()
            . self::BACKUPNAME
            . '_'
            . $this->getSuffix()
            . '.sql';
        return $this->_sqlDump;
    }

    /** Get the path for backups
     * @access public
     * @return string
     */
    public function getPath()
    {
        $this->_path = ROOT_PATH . '/backups/';
        return $this->_path;
    }

    /** Set up the ACL and contexts, assign variables
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('admin', null);

    }

    /** List available actions via view helpers
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //Magic in view
    }

    /** List all roles held on system
     * @access public
     * @return void
     */
    public function rolesAction()
    {
        $this->view->roles = $this->getRoles()->getAllRoles();
    }

    /** View a system role
     * @access public
     * @return void
     */
    public function roleAction()
    {
        $this->view->roles = $this->getRoles()->getRole($this->getParam('id'));
        $users = new Users();
        $this->view->members = $users->getRolesMembers(
            $this->getParam('id'), $this->getParam('page'));
    }

    /** Edit a system role
     * @access public
     * @return void
     */
    public function editroleAction()
    {
        $form = new SystemRoleForm();
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $updateData = array(
                    'role' => $form->getValue('role'),
                    'description' => $form->getValue('description'),
                    'updated' => $this->getTimeForForms(),
                    'updatedBy' => $this->getIdentityForForms()
                );
                $where = array();
                $where[] = $this->getRoles()->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $this->getRoles()->update($updateData, $where);
                $this->getFlash()->addMessage($form->getValue('role') . '\'s details updated.');
                $this->redirect('/admin/systemroles/');
            } else {
                $form->populate($formData);
            }
        } else {
            // find id is expected in $params['id']
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $roles = $this->getRoles()->fetchRow('id=' . $id);
                $form->populate($roles->toArray());
            }
        }
    }

    /** Clean the entire cache
     */
    public function cleancacheAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
        $this->getFlash()->addMessage('Cache cleaned');
        $this->redirect('/admin/system/');
    }

    /** Clean just the old cached data
     * @access public
     * @return void
     */
    public function cleanoldcacheAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getCache()->clean(Zend_Cache::CLEANING_MODE_OLD);
        $this->getFlash()->addMessage('Cache cleaned');
        $this->redirect('/admin/system/');
    }

    /** Display the server's configuration
     * @access public
     * @return void
     */
    public function serverconfigAction()
    {
        //Magic in view
    }

    /** List all s3 objects stored
     * @access public
     * @return void
     */
    public function s3Action()
    {
        $s3 = $this->getS3();
        $backupslist = $s3->getObjectsByBucket('dbback');
        $data = array();
        foreach ($backupslist as $name) {
            $amazon = array(
                'name' => 'dbback/' . $name);
            $details = $s3->getInfo('dbback/' . $name);
            foreach ($details as $k => $v) {
                $amazon[$k] = $v;
            }
            $data[] = $amazon;
        }
        $this->view->data = $data;
    }

    /** Send email notifications
     * @access public
     * @return void
     * @param string $message
     */
    public function notify($message)
    {
        $mail = new Zend_Mail();
        $mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
        $mail->setBodyText($message);
        $mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
        $mail->addTo($this->_config->admin->email);
        $mail->setSubject('Backup process status on main server');
        $mail->send();
    }

    /** Clean up the old backup files
     * @access public
     * @return void
     */
    public function cleanUp()
    {
        $s3 = $this->getS3();
        $aS3Files = $s3->getObjectsByBucket(self::BUCKET);
        foreach ($aS3Files as $as3) {
            $data = $s3->getInfo('dbback/' . $as3);
            if ($data['mtime'] + ($this->_days * 24 * 60 * 60) < time('void')) {
                $deleted = $s3->removeObject('dbback/' . $as3);
            }
        }
    }

    /** Back up the databases
     * @access public
     * @return void
     *
     */
    public function backup()
    {
        set_time_limit(0);
        $pw = $this->_helper->config()->db->params->password;
        $username = $this->_helper->config()->db->params->username;
        // Dump them
        $name = self::NAME;
        $backupdb = exec("$this->_mysqldump -u$username -p$pw $name > $this->getSqlDump()");
        // Tar them up
        $tardbsql = exec("$this->_tar -cpzf $this->getTarDump() $this->getSqlDump())");
        if (file_exists($this->getSqlDump())) {
            unlink($this->getSqlDump());
        }
    }

    /** Kick off a backup process
     * @access public
     * @return void
     */
    public function s3backupAction()
    {
        $available = $this->getS3()->isObjectAvailable('dbback/' . $this->_suffix . '.sql.tar.gz');
        if ($available == false) {
            if (!file_exists($this->_tarDump)) {
                $backup = $this->backup();
                $ret = $this->transfer($backup);
            } else {
                $backup = $this->_tarDump;
                $ret = $this->transfer($backup);
            }
            echo "Success: " . ($ret ? 'Yes' : 'No');
            if ($ret == true) {
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
     * @access public
     * @param string $backup
     * @return void
     */
    public function transfer($backup)
    {
        $s3 = $this->_s3;
        $bucketName = 'dbback';
        $perms = array(
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