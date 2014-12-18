<?php

/** Controller for manipulating the artefacts data
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 2
 * @uses Pas_ArrayFunctions
 * @uses Finds
 * @uses Pas_Exception_NotAuthorised
 * @uses Findspots
 * @uses Coins
 * @uses CoinClassifications
 * @uses Slides
 * @uses Publications
 * @uses Comments
 * @uses Rallies
 * @uses CommentFindForm
 * @uses FindForm
 * @uses Pas_Exception_Param
 */
class Database_ArtefactsController extends Pas_Controller_Action_Admin
{

    /** The redirect uri
     *
     */
    const REDIRECT = '/database/artefacts/';

    /** The array of restricted access
     * @access protected
     * @var array restricted access roles
     */
    protected $_restricted = array(null, 'member', 'public');

    /** the higher level roles
     * @access protected
     * @var array Higher level groups
     */
    protected $_higherLevel = array('treasure', 'flos', 'admin', 'hero', 'fa');

    /** The array of numismatic terms
     * @var array coins pseudonyms
     */
    protected $_coinarray = array(
        'Coin', 'COIN', 'coin',
        'token', 'jetton', 'coin weight',
        'COIN HOARD', 'TOKEN', 'JETTON'
    );

    /** An array of Roman and Iron Age periods
     * Used for coins
     * @access protected
     * @var array Romanic periods
     */
    protected $_periodRomIA = array(
        'Roman', 'ROMAN', 'roman',
        'Iron Age', 'Iron age', 'IRON AGE',
        'Byzantine', 'BYZANTINE', 'Greek and Roman Provincial',
        'GREEK AND ROMAN PROVINCIAL', 'Unknown',
        'UNKNOWN');

    /** An array of Roman and Prehistoric periods
     * Used for objects
     * @var array
     * @access protected
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
     * @access protected
     * @var array
     */
    protected $_earlyMed = array('Early Medieval', 'EARLY MEDIEVAL');

    /** An array of Medieval periods
     * Used for coins and objects
     * @access protected
     * @var array
     */
    protected $_medieval = array('Medieval', 'MEDIEVAL');

    /** An array of Post Medieval periods
     * Used for coins and objects
     * @access protected
     * @var array
     */
    protected $_postMed = array('Post Medieval', 'POST MEDIEVAL', 'Modern', 'MODERN');

    /** Contexts for records
     * @access protected
     * @var array
     */
    protected $_contexts = array(
        'xml', 'rss', 'json',
        'atom', 'kml', 'georss',
        'ics', 'rdf', 'xcs',
        'vcf', 'csv', 'pdf',
        'geojson');

    /** The auth object
     * @access protected
     * @var null
     */
    protected $_auth = NULL;

    /** Comments model
     * @access protected
     * @var
     */
    protected $_comments;

    /** Findspots model
     * @access protected
     * @var
     */
    protected $_findspots;

    /** Finds model
     * @access protected
     * @var
     */
    protected $_finds;

    /** The find form
     * @access protected
     * @var
     */
    protected $_findForm;

    /** Get the find form
     * @access public
     * @return \FindForm
     */
    public function getFindForm()
    {
        $this->_findForm = new FindForm();
        return $this->_findForm;
    }

    /** Get the findspots model
     * @access public
     * @return \Findspots
     */
    public function getFindspots()
    {
        $this->_findspots = new Findspots();
        return $this->_findspots;
    }

    /** Get the comments model
     * @access public
     * @return \Comments
     */
    public function getComments()
    {
        $this->_comments = new Comments();
        return $this->_comments;
    }

    /** Get the finds model
     * @access public
     * @return \Finds
     */
    public function getFinds()
    {
        $this->_finds = new Finds();
        return $this->_finds;
    }

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->deny('public', array('add', 'edit'));
        $this->_helper->_acl->allow('public', array(
            'index', 'record', 'errorreport',
            'notifyflo'
        ));
        $this->_helper->_acl->allow('member', null);

        $this->_helper->contextSwitch()->setAutoJsonSerialization(false)
            ->setAutoDisableLayout(true)
            ->addContext('csv', array('suffix' => 'csv'))
            ->addContext('kml', array('suffix' => 'kml'))
            ->addContext('rss', array('suffix' => 'rss'))
            ->addContext('atom', array('suffix' => 'atom'))
            ->addContext('rdf', array('suffix' => 'rdf', 'headers' => array('Content-Type' => 'application/xml')))
            ->addContext('pdf', array('suffix' => 'pdf'))
            ->addContext('midas', array('suffix' => 'midas', 'headers' => array('Content-Type' => 'application/xml')))
            ->addContext('qrcode', array('suffix' => 'qrcode'))
            ->addContext('geojson', array('suffix' => 'geojson', 'headers' => array('Content-Type' => 'application/json')))
            ->addActionContext('record', array('qrcode', 'json', 'xml', 'geojson', 'rdf'))
            ->initContext();
        $this->_auth = Zend_Registry::get('auth');
    }

    /** Display a list of objects recorded with pagination
     * This redirects to just the search results as there is nothing else here.
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('You cannot access the root page for artefacts');
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('database/search/results/');
    }

    /** Display individual record
     * @access public
     * @return void
     * @todo move comment functionality to a model
     */
    public function recordAction()
    {

        if ($this->_getParam('id', false)) {
            $this->view->recordID = $this->_getParam('id');
            $id = $this->_getParam('id');
            $this->view->finds = $this->getFinds()->getAllData($id);
            $coinrefs = new CoinClassifications();
            $this->view->coinrefs = $coinrefs->getAllClasses($id);
            $thumbs = new Slides;
            $this->view->thumbs = $thumbs->getThumbnails($id);
            $refs = new Publications;
            $this->view->refs = $refs->getReferences($id);
            $this->view->comments = $this->getComments()->getFindComments($id);
            $form = new CommentFindForm();
            $form->submit->setLabel('Add a new comment');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $data = $this->_helper->akismet($form->getValues());
                    $data['contentID'] = $this->_getParam('id');
                    $data['comment_type'] = 'findComment';
                    $data['comment_approved'] = 'moderation';
                    $this->getComments()->add($data);
                    $this->getFlash()->addMessage('Your comment has been entered and will appear shortly!');
                    $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
                    $this->_request->setMethod('GET');
                } else {
                    $this->getFlash()->addMessage('There are problems with your comment submission');
                    $form->populate($this->_request->getPost());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Add an object
     * @access public
     * @return void
     * @todo slim down action, move logic for adding to finds.php model
     */
    public function addAction()
    {
        $user = $this->_helper->identity->getPerson();
        if ((is_null($user->peopleID) && is_null($user->canRecord))
            || (!is_null($user->peopleID) && is_null($user->canRecord))
        ) {
            $this->redirect('/error/accountproblem');
        }
        $last = $this->_getParam('copy');
        $this->view->secuid = $this->secuid();
        $form = $this->getFindForm();
        $form->submit->setLabel('Save record');
        $form->old_findID->setValue($this->FindUid());
        $form->secuid->setValue($this->secuid());
        if (isset($user->peopleID)) {
            $form->recorderID->setValue($user->peopleID);
            $form->recordername->setValue($user->fullname);
            $form->identifier1ID->setValue($user->peopleID);
            $form->idBy->setValue($user->fullname);
        }
        if (in_array($user->role, $this->_restricted)) {
            $form->finderID->setValue($user->peopleID);
            $form->removeDisplayGroup('discoverers');
            $form->removeElement('finder');
            $form->removeElement('secondfinder');
            $form->removeElement('idBy');
            $form->recordername->setAttrib('disabled', true);
            $form->removeElement('id2by');
        }
        $this->view->form = $form;
        if ($last == 'last') {
            $finddata = $this->getFinds()->getLastRecord($this->getIdentityForForms());
            foreach ($finddata as $finddataflat) {
                $form->populate($finddataflat);
                if (isset($user->peopleID)) {
                    $form->recorderID->setValue($user->peopleID);
                    $form->recordername->setValue($user->fullname);
                }
            }
        }
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
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
                $insert = $this->getFinds()->add($insertData);
                $this->_helper->solrUpdater->update('objects', $insert);
                $this->redirect(self::REDIRECT . 'record/id/' . $insert);
                $this->getFlash()->addMessage('Record created!');
            } else {
                $this->getFlash()->addMessage('Please check and correct errors!');
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Edit a record
     * @access public
     * @return void
     * @todo move update logic to model finds.php
     */
    public function editAction()
    {
        if ($this->_getParam('id', false)) {
            $user = $this->getAccount();
            $form = $this->getFindForm();
            $form->submit->setLabel('Update record');
            $this->view->form = $form;
            if (in_array($this->getRole(), $this->_restricted)) {
                $form->removeDisplayGroup('discoverers');
                $form->removeElement('finder');
                $form->finderID->setValue($user->peopleID);
                $form->removeElement('secondfinder');
                $form->removeElement('idBy');
                $form->recordername->setAttrib('disabled', true);
                $form->removeElement('id2by');
            }
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = $form->getValues();
                    $id2by = $form->getValue('id2by');
                    if ($id2by === "" || is_null($id2by)) {
                        $updateData['identifier2ID'] = null;
                    }
                    unset($updateData['recordername']);
                    unset($updateData['finder']);
                    unset($updateData['idBy']);
                    unset($updateData['id2by']);
                    unset($updateData['secondfinder']);
                    $oldData = $this->getFinds()->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    $where = array();
                    $where[] = $this->getFinds()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $this->getFinds()->update($updateData, $where);
                    $this->_helper->audit(
                        $updateData,
                        $oldData,
                        'FindsAudit',
                        $this->_getParam('id'),
                        $this->_getParam('id')
                    );
                    $this->_helper->solrUpdater->update('objects', $this->_getParam('id'));
                    $this->getFlash()->addMessage('Artefact information updated and audited!');
                    $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
                } else {
                    $this->view->find = $this->getFinds()->fetchRow('id=' . $this->_getParam('id'));
                    $form->populate($this->_request->getPost());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $formData = $this->getFinds()->getEditData($id);
                    if (count($formData)) {
                        $form->populate($formData['0']);
                        $this->view->find = $this->getFinds()->fetchRow('id=' . $id);
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound, 404);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a record
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = $this->getFinds()->getAdapter()->quoteInto('id = ?', $id);
                $this->getFinds()->delete($where);
                $findID = $this->_request->getPost('findID');
                $whereFindspots = array();
                $whereFindspots[] = $this->getFindspots()->getAdapter()->quoteInto('findID  = ?', $findID);
                $this->getFlash()->addMessage('Record deleted!');
                $this->getFindspots()->delete($whereFindspots);
                $this->_helper->solrUpdater->deleteById('objects', $id);
                $this->redirect(self::REDIRECT);
            }
            $this->getFlash()->addMessage('No changes made!');
            $this->redirect('database/artefacts/record/id/' . $id);
        } else {
            $this->view->find = $this->getFinds()->fetchRow('id=' . $this->_request->getParam('id'));
        }
    }

    /** Enter an error report
     * @access public
     * @return void
     * @todo move insert logic to model
     */
    public function errorreportAction()
    {
        if ($this->_getParam('id', false)) {
            $form = new CommentOnErrorFindForm();
            $form->submit->setLabel('Submit your error report');
            $finds = $this->getFinds()->getRelevantAdviserFind($this->_getParam('id', 0));
            $this->view->form = $form;
            $this->view->finds = $finds;
            if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                if ($this->_helper->akismet($data)) {
                    $data['comment_approved'] = 'spam';
                } else {
                    $data['comment_approved'] = 1;
                }
                if (array_key_exists('captcha', $data)) {
                    unset($data['captcha']);
                }

                $errors = new ErrorReports();
                $errors->add($data);
                $data = array_merge($finds['0'], $data);

                $this->notify(
                    $finds['0']['objecttype'],
                    $finds['0']['broadperiod'],
                    $finds['0']['institution'],
                    $finds['0']['createdBy'],
                    $data
                );
                $this->getFlash()->addMessage('Your error report has been submitted. Thank you!');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
            } else {
                $form->populate($this->_request->getPost());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Notify an FLO
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function notifyfloAction()
    {
        if ($this->_getParam('id', false)) {
            $form = new NotifyFloForm();
            $this->view->form = $form;
            $find = $this->getFinds()->fetchRow($this->getFinds()->select()->where('id = ?', $this->_getParam('id')));
            $this->view->find = $find->toArray();
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $contacts = new Contacts();
                    $to = $contacts->getNameEmail($form->getValue('flo'));
                    $cc = $this->_getAdviser($find->objecttype, $find->broadperiod);
                    $from[] = array('email' => $this->_user->email, 'name' => $this->_user->fullname);
                    $cc = array_merge($cc, $from);
                    $assignData = array_merge($find->toArray(), $form->getValues(), $to['0']);
                    $this->_helper->mailer($assignData, 'publicFindToFlo', $to, $cc, $from);
                    $this->getFlash()->addMessage('Your message has been sent');
                    $this->redirect('database/artefacts/record/id/' . $find->id);
                } else {
                    $form->populate($form->getValues());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    public function workflowAction()
    {
        if ($this->_getParam('id', false)) {
            $people = new People();
            $exist = $people->checkEmailOwner($this->_getParam('id'));
            $person = $this->getAccount();
            $from = array('name' => $person->fullname, 'email' => $person->email);
            $this->view->from = $exist;
            $form = new ChangeWorkFlowForm();
            $findStatus = $this->getFinds()->fetchRow($this->getFinds()->select()->where('id = ?', $this->_getParam('id')));
            $this->view->find = $findStatus->old_findID;
            $form->populate($findStatus->toArray());
            $this->view->form = $form;
            if (is_null($exist['0']['email'])) {
                $form->finder->setAttrib('disabled', 'disabled');
                $form->finder->setDescription('No email associated with finder yet.');
                $form->removeElement('content');
            }
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = array('secwfstage' => $form->getValue('secwfstage'));
                    if (strlen($form->getValue('finder')) > 0) {
                        $assignData = array(
                            'name' => $exist['0']['name'],
                            'old_findID' => $findStatus->old_findID,
                            'id' => $this->_getParam('id'),
                            'from' => $person->fullname,
                            'workflow' => $form->getValue('secwfstage'),
                            'content' => $form->getValue('content')
                        );
                        $this->_helper->mailer($assignData, 'informFinderWorkflow', $exist, array($from), array($from), null, null);
                    }
                    $where = array();
                    $where[] = $this->getFinds()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $this->getFinds()->update($updateData, $where);
                    $this->_helper->audit(
                        $updateData,
                        $findStatus->toArray(),
                        'FindsAudit',
                        $this->_getParam('findID'),
                        $this->_getParam('findID'));
                    $this->_helper->solrUpdater->update('objects', $this->_getParam('id'));
                    $this->getFlash()->addMessage('Workflow status changed');
                    $this->redirect('database/artefacts/record/id/' . $this->_getParam('id'));
                    $this->_request->setMethod('GET');
                } else {
                    $this->getFlash()->addMessage('There were problems changing the workflow');
                    $form->populate($this->_request->getPost());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Provide a notification for an object
     */
    protected function notify($objecttype, $broadperiod, $institution, $createdBy, $data)
    {
        if ($institution === 'PUBLIC') {
            $users = new Users();
            $responsible = $users->fetchRow('id = ' . $createdBy);
            $to = array(array(
                'email' => $responsible->email,
                'name' => $responsible->fullname
            ));
        } elseif (in_array($institution, array('PAS', 'DCMS', 'RAH'))) {
            $to = array(array('email' => 'info@finds.org.uk', 'name' => 'Central Unit'));
        } else {
            $responsible = new Contacts();
            $to = $responsible->getOwner($data['comment_findID']);
        }
        $cc = $this->_getAdviser($objecttype, $broadperiod);

        if ($this->_user) {

            $from = array(array(
                'email' => $this->_user->email,
                'name' => $this->_user->fullname
            ));
        } else {
            $from = array(array(
                'email' => $data['comment_author_email'],
                'name' => $data['comment_author']
            ));
        }
        $assignData = array_merge($to['0'], $data);
        $this->_helper->mailer($assignData, 'errorSubmission', $to, $cc, $from);
    }

    /** Determine adviser to email
     * @access public
     * @param string $objecttype
     * @param type $broadperiod
     * @return array
     */
    private function _getAdviser($objecttype, $broadperiod)
    {
        $config = $this->_helper->config();
        $this->_romancoinsadviser = $config->findsadviser->romancoins;
        $this->_romancoinsadviseremail = $config->findsadviser->romcoins->email;

        $this->_medievalcoinsadviser = $config->findsadviser->medievalcoins;
        $this->_medievalcoinsadviseremail = $config->findsadviser->medcoins->email;

        $this->_romanobjects = $config->findsadviser->romanobjects;
        $this->_romanobjectsemail = $config->findsadviser->romobjects->email;

        $this->_medievalobjects = $config->findsadviser->medievalobjects;
        $this->_medievalobjectsemail = $config->findsadviser->medobjects->email;

        $this->_postmedievalobjects = $config->findsadviser->postmedievalobjects;
        $this->_postmedievalobjectsemail = $config->findsadviser->postmedobjects->email;

        $this->_earlymedievalobjects = $config->findsadviser->earlymedievalobjects;
        $this->_earlymedievalobjectsemail = $config->findsadviser->earlymedobjects->email;

        $this->_catchall = $config->findsadviser->default;
        $this->_catchallemail = $config->findsadviser->def->email;

        switch ($objecttype) {

            case (in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_periodRomIA)):
                $adviserdetails = $this->_romancoinsadviser;
                $adviseremail = $this->_romancoinsadviseremail;
                break;

            case (in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_earlyMed)):
                $adviserdetails = $this->_medievalcoinsadviser;
                $adviseremail = $this->_medievalcoinsadviseremail;
                break;

            case (in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_medieval)):
                $adviserdetails = $this->_medievalcoinsadviser;
                $adviseremail = $this->_medievalcoinsadviseremail;
                break;

            case (in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_postMed)):
                $adviserdetails = $this->_medievalcoinsadviser;
                $adviseremail = $this->_medievalcoinsadviseremail;
                break;

            case (!in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_periodRomPrehist)):
                $adviserdetails = $this->_romanobjects;
                $adviseremail = $this->_romanobjectsemail;
                break;

            case (!in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_postMed)):
                $adviserdetails = $this->_postmedievalobjects;
                $adviseremail = $this->_postmedievalobjectsemail;
                break;

            case (!in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_medieval)):
                $adviserdetails = $this->_medievalobjects;
                $adviseremail = $this->_medievalobjectsemail;
                break;

            case (!in_array($objecttype, $this->_coinarray)
                && in_array($broadperiod, $this->_earlyMed)):
                $adviserdetails = $this->_earlymedievalobjects;
                $adviseremail = $this->_earlymedievalobjectsemail;
                break;

            default:
                $adviserdetails = $this->_catchall;
                $adviseremail = $this->_catchallemail;
                break;
        }

        $people = $this->_combine($adviserdetails->toArray(), $adviseremail->toArray());
        $sendto = array();
        foreach ($people as $k => $v) {
            $sendto[] = array('email' => $v, 'name' => $k);
        }
        return $sendto;
    }
}