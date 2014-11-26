<?php

/** Controller for manipulating the hoards data
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2014 Mary Chester-Kadwell
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Hoards
 * @uses Pas_ArrayFunctions
 * @uses Finds
 * @uses Pas_Exception_NotAuthorised
 * @uses Findspots
 * @uses Archaeology
 * @uses Publications
 * @uses HoardForm
 * @uses Pas_Exception_Param
 */
class Database_HoardsController extends Pas_Controller_Action_Admin
{

    /** The redirect uri
     *
     */
    const REDIRECT = '/database/hoards/';

    /** The array of restricted access
     * @access protected
     * @var array restricted access roles
     */
    protected $_restricted = array(null, 'member', 'public');

    /** the higher level roles
     * @access protected
     * @var array Higher level groups
     */
    protected $_higherLevel = array('treasure', 'flos', 'admin', 'hero', 'fa', 'hoard');

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

    /** @var array Context */
    protected $_contexts = array(
        'xml', 'rss', 'json',
        'atom', 'kml', 'georss',
        'ics', 'rdf', 'xcs',
        'vcf', 'csv', 'pdf',
        'geojson');

    protected $_auth;

    protected $_comments;

    protected $_findspots;

    protected $_finds;

    protected $_hoardsFinders;

    protected $_hoardForm;

    protected $_artefactLinkForm;

    /** Get the hoard form
     * @access public
     * @return \HoardForm
     */
    public function getHoardForm()
    {
        $this->_hoardForm = new HoardForm();
        return $this->_hoardForm;
    }

    /** Get the findspots model
     * @access public
     * @return \Findspost
     */
    public function getFindspots()
    {
        $this->_findspots = new Findspots();
        return $this->_findspots;
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

    /** Get the hoards to finders model
     * @access public
     * @return HoardsFinders
     */
    public function getHoardsFinders()
    {
        $this->_hoardsFinders = new HoardsFinders();
        return $this->_hoardsFinders;
    }

    public function getComments()
    {
        $this->_comments = new Comments();
        return $this->_comments;
    }

    public function getArtefactLinkForm()
    {
        $this->_artefactLinkForm = new ArtefactLinkForm();
        return $this->_artefactLinkForm;
    }


    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->deny('public', array('add', 'edit'));
        $this->_helper->_acl->allow('public', array(
            'index', 'record', 'error',
            'notifyflo'
        ));
        $this->_helper->_acl->allow('member', null);

        $this->_helper->contextSwitch()->setAutoJsonSerialization(false)
            ->setAutoDisableLayout(true)
            ->addContext('csv', array('suffix' => 'csv'))
            ->addContext('rss', array('suffix' => 'rss'))
            ->addContext('rdf', array('suffix' => 'rdf', 'headers' => array('Content-Type' => 'application/xml')))
            ->addContext('qrcode', array('suffix' => 'qrcode'))
            ->addContext('geojson', array('suffix' => 'geojson', 'headers' => array('Content-Type' => 'application/json')))
            ->addActionContext('record', array('qrcode', 'json', 'xml', 'geojson', 'rdf'))
            ->initContext();
        $this->_hoards = new Hoards();
        $this->_auth = Zend_Registry::get('auth');
        $this->_user = $this->_helper->identity->getPerson();
    }

    /** Display a list of hoards recorded with pagination
     * This redirects to just the search results as there is nothing else here.
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('database/search/results/');
    }

    /** Display individual hoard record
     * @access public
     * @return void
     */
    public function recordAction()
    {
        if ($this->_getParam('id', false)) { // Check there is a hoardID in the URL
            $id = $this->_getParam('id');
            $hoardsdata = $this->_hoards->getBasicHoardData($id);
            if (!empty($hoardsdata)) {
                $this->view->hoards = $hoardsdata;
                $this->view->multipleKnownAs = $this->_hoards->getKnownAs($id);
                $this->view->temporals = $this->_hoards->getChronology($id);
                $this->view->coinChronology = $this->_hoards->getCoinChronology($id);
                $this->view->hoardDescription = $this->_hoards->getHoardDescription($id);
                $this->view->coinDataQuality = $this->_hoards->getQualityRating($id);
                $this->view->subsequentActions = $this->_hoards->getSubsequentActions($id);
                $this->view->treasureDetails = $this->_hoards->getTreasureDetails($id);
                $this->view->hoardMaterials = $this->_hoards->getMaterials($id);
                $this->view->linkedCoins = $this->_hoards->getLinkedCoins($id);
                $this->view->linkedArtefacts = $this->_hoards->getLinkedArtefacts($id);
                $this->view->linkedContainers = $this->_hoards->getLinkedContainers($id);
                $this->view->recordersIdentifiers = $this->_hoards->getRecordersIdentifiers($id);
                $this->view->finders = $this->_hoards->getFinders($id);
                $this->view->discoverySummary = $this->_hoards->getDiscoverySummary($id);
                $this->view->referenceNumbers = $this->_hoards->getReferenceNumbers($id);
                $this->view->quantities = $this->_hoards->getQuantities($id);
                $coinsummary = new CoinSummary();
                $this->view->coinSummary = $coinsummary->getCoinSummary($id);

                $this->view->findspots = $this->getFindspots()->getFindSpotData($id, 'hoards');

                $archaeology = new Archaeology();
                $this->view->archaeologicalContext = $archaeology->getArchaeologyData($id);

                $refs = new Publications();
                $this->view->refs = $refs->getReferences($id, 'hoards');

            } else {
                throw new Pas_Exception_NotAuthorised('You are not authorised to view this record', 401);
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }

    }

    /** Add a hoard
     * @access public
     * @return void
     */
    public function addAction()
    {
        $user = $this->_user;
        if (is_null($user->peopleID) || is_null($user->canRecord)) {
            $this->redirect('/error/accountproblem');
        }
        $form = $this->getHoardForm();
        $form->submit->setLabel('Save record');
        if (isset($user->peopleID)) {
            $form->recorderID->setValue($user->peopleID);
            $form->recordername->setValue($user->fullname);
            $form->identifier1ID->setValue($user->peopleID);
            $form->idBy->setValue($user->fullname);
        }
        if (in_array($user->role, $this->_restricted)) {
            $form->finderID->setValue($user->peopleID);
            $form->removeDisplayGroup('discoverers');
            $form->removeElement('finder1');
            $form->removeElement('idBy');
            $form->recordername->setAttrib('disabled', true);
            $form->removeElement('id2by');
        }

        $this->view->form = $form;

        $last = $this->_getParam('copy');
        if ($last == 'last') {
            $data = $this->_hoards->getLastRecord($this->getIdentityForForms());
            $form->populate($data[0]);
        }
        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
            $form->preValidation($formData);
            if ($form->isValid($formData)) {
                $insertData = $form->getValues();
                $insert = $this->_hoards->addHoard($insertData);
                if ($insert != 'error') {
                    $this->redirect(self::REDIRECT . 'record/id/' . $insert);
                } else { // If there is a database error, repopulate form so users don't lose their work
                    $this->getFlash()->addMessage('Database error. Please try submitting again or contact support.');
                    $form->populate($formData);
                }
            } else {
                $this->getFlash()->addMessage('Please check and correct errors!');
                $form->populate($formData);
            }
        }
    }

    /** Edit a hoard
     * @access public
     * @return void
     * @todo move update logic to model finds.php
     */
    public function editAction()
    {
        $id = $this->_getParam('id', false);
        if (isset($id)) {
            $form = $this->getHoardForm();
            $form->submit->setLabel('Update record');
            $this->view->form = $form;

            $user = $this->getAccount();
            if (in_array($this->getRole(), $this->_restricted)) {
                $form->removeDisplayGroup('discoverers');
                $form->removeElement('finder');
                $form->finderID->setValue($user->peopleID);
                $form->removeElement('idBy');
                $form->recordername->setAttrib('disabled', true);
                $form->removeElement('id2by');
            }
            if ($this->getRequest()->isPost()) {
                $formData = $this->_request->getPost();
                $form->preValidation($formData);
                if ($form->isValid($formData)) {
                    $updateData = $form->getValues();
                    $oldData = $this->_hoards->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    $update = $this->_hoards->editHoard($updateData, $id);
                    $this->_helper->audit(
                        $updateData,
                        $oldData,
                        'HoardsAudit',
                        $this->_getParam('id'),
                        $this->_getParam('id')
                    );
                    if ($update != 'error') {
                        $this->redirect(self::REDIRECT . 'record/id/' . $id);
                    } else { // If there is a database error, repopulate form so users don't lose their work
                        $this->getFlash()->addMessage('Database error. Please try submitting again or contact support.');
                        $form->populate($formData);
                    }
                } else {
                    $form->populate($formData);
                }
            } else {
                if ($id > 0) {
                    $formData = $this->_hoards->getEditData($id);
                    $materialsData = $this->_hoards->getMaterials($id);
                    $findersData = $this->getHoardsFinders()->getFinders($formData['secuid']);
                    if (count($formData)) {
                        $form->addFinders($findersData);
                        $form->populate($formData);
                        $form->getElement('materials')->setValue($materialsData);
                        $this->view->hoard = $this->_hoards->fetchRow('id=' . $id);
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound, 404);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a hoard
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = $this->_hoards->getAdapter()->quoteInto('id = ?', $id);
                $this->_hoards->delete($where);
                $secuid = $this->_request->getPost('secuid');
                $whereFindspots = array();
                $whereFindspots[] = $this->getFindspots()->getAdapter()->quoteInto('findID  = ?',
                    $secuid);
                $whereHoardsFinders = array();
                $whereHoardsFinders[] = $this->getHoardsFinders()->getAdapter()->quoteInto('hoardID  = ?',
                    $secuid);
                $this->getFlash()->addMessage('Record deleted!');
                $this->getFindspots()->delete($whereFindspots);
                $this->getHoardsFinders()->delete($whereHoardsFinders);
                // $this->_helper->solrUpdater->deleteById('objects', $id);
                $this->redirect('database');
            }
            $this->getFlash()->addMessage('No changes made!');
            $this->redirect('database/hoards/record/id/' . $id);
        } else {
            $this->view->hoard = $this->_hoards->fetchRow('id=' . $this->_request->getParam('id'));
        }
    }

    /** Links an artefact record to a hoard record
     * @access public
     * @return void
     */
    public function linkAction()
    {
        // The secuid and id of the hoard is passed in the url
        if ($this->_getParam('hoardID', false) || $this->_getParam('id', false)) {
            $form = $this->getArtefactLinkForm();
            $this->view->form = $form;

            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();

                if ($form->isValid($formData)) {
                    // The secuid of the hoard is retrieved from the url
                    $updateData['hoardID'] = $this->_getParam('hoardID');

                    // The secuid of the artefact to link is retrieved from the form
                    $findSecuid = $form->getValue('findID');

                    // The id of the artefact to link is retrieved from the database
                    $findRow = $this->getFinds()->fetchRow($this->getFinds()->select()->where(
                        'secuid = ?', $findSecuid
                    ));
                    $findID = $findRow['id'];
                    // Update the Find table with the hoard secuid
                    $this->getFinds()->linkFind($updateData, $findID);

                    // $this->_helper->solrUpdater->update('objects', $findID);
                    $this->getFlash()->addMessage('Success! Coin, artefact or container linked to this hoard');
                    $this->redirect('/database/hoards/record/id/' . $this->_getParam('id'));
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Unlink an artefact record from a hoard record
     * @access public
     * @return void
     */
    public function unlinkAction()
    {
        if ($this->_getParam('findID', false) || $this->_getParam('hoardID', false) || $this->_getParam('secuid', false)) {
            // Pass the hoard secuid and id into the view from the url
            $this->view->hoardSecuid = $this->_getParam('secuid');
            $this->view->hoardID = $this->_getParam('hoardID');

            if ($this->_request->isPost()) {
                // The find id is retrieved from the form
                $findID = (int)$this->_request->getPost('findID');

                // If POST and 'Yes' to confirm unlinking
                $delete = $this->_request->getPost('del');
                if ($delete == 'Yes' && $findID > 0) {

                    // The artefact is unlinked
                    $this->getFinds()->unlinkFind($findID);

                    //	$this->_helper->solrUpdater->update('finds', $findID);

                    $this->getFlash()->addMessage('Link deleted!');
                    $this->redirect('/database/hoards/record/id/' . $this->_getParam('hoardID'));

                } else { // If 'No' to cancel unlinking
                    $this->redirect('/database/hoards/record/id/' . $this->_getParam('hoardID'));
                }
            } else { // If not POST view the confirmation
                $findID = (int)$this->_request->getParam('findID');
                if ((int)$findID > 0) {
                    $this->view->find = $this->getFinds()->fetchRow($this->getFinds()->select()->where(
                        'id = ?', $findID
                    ));
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Change workflow status of a hoard
     * @access public
     * @return void
     */
    public function workflowAction()
    {
        if ($this->_getParam('id', false)) {
            $people = new People();
            $exist = $people->checkEmailOwner($this->_getParam('id'));
            $person = $this->getAccount();
            $from = array('name' => $person->fullname, 'email' => $person->email);
            $this->view->from = $exist;
            $form = new ChangeWorkFlowForm();
            $findStatus = $this->_hoards->fetchRow($this->_hoards->select()->where('id = ?', $this->_getParam('id')));
            $this->view->find = $findStatus->hoardID;
            $form->populate($findStatus->toArray());
            $this->view->form = $form;
            if (is_null($exist['0']['email'])) {
                $form->finder->setAttrib('disabled', 'disabled');
                $form->finder->setDescription('No email associated with finder yet.');
                $form->removeElement('content');
            }
            if ($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())
            ) {
                if ($form->isValid($form->getValues())) {
                    $updateData = array('secwfstage' => $form->getValue('secwfstage'));
                    if (strlen($form->getValue('finder')) > 0) {
                        $assignData = array(
                            'name' => $exist['0']['name'],
                            'hoardID' => $findStatus->hoardID,
                            'id' => $this->_getParam('id'),
                            'from' => $person->fullname,
                            'workflow' => $form->getValue('secwfstage'),
                            'content' => $form->getValue('content')
                        );
                        $this->_helper->mailer($assignData, 'informFinderWorkflow', $exist, array($from), array($from), null, null);
                    }
                    $where = array();
                    $where[] = $this->_hoards->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $this->_hoards->update($updateData, $where);
                    $this->_helper->audit(
                        $updateData,
                        $findStatus->toArray(),
                        'FindsAudit',
                        $this->_getParam('id'),
                        $this->_getParam('id'));
                    // $this->_helper->solrUpdater->update('objects', $this->_getParam('findID'));
                    $this->getFlash()->addMessage('Workflow status changed');
                    $this->redirect('database/hoards/record/id/' . $this->_getParam('id'));
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


    /** Enter an error report
     * @access public
     * @return void
     */
    public function errorAction()
    {
        if ($this->_getParam('id', false)) {
            $form = new CommentOnErrorFindForm();
            $form->submit->setLabel('Submit your error report');
            $finds = $this->_hoards->fetchRow($this->_hoards->select()->where('id = ?', $this->_getParam('id')))->toArray();
            $this->view->finds = $finds;
            $this->view->form = $form;
            if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                $data = $form->getValues();

                if ($this->_helper->akismet($data)) {
                    $data['comment_approved'] = 'spam';
                } else {
                    $data['comment_approved'] = 1;
                }
                $errors = new ErrorReports();
                $errors->add($data);
                $data = array_merge($finds, $data);
                $this->notify(
                    $finds['institution'],
                    $finds['createdBy'],
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

    /** Provide a notification for an object
     * @param string $institution
     * @param integer $createdBy
     * @param array $data
     * @return void
     */
    protected function notify($institution, $createdBy, $data)
    {
        $to = array();
        if ($institution === 'PUBLIC') {
            $users = new Users();
            $responsible = $users->fetchRow('id = ' . $createdBy);
            $to[] = array(
                'email' => $responsible->email,
                'name' => $responsible->fullname
            );
        } elseif (in_array($institution, array('PAS', 'DCMS', 'RAH'))) {
            $to = array('email' => 'info@finds.org.uk', 'name' => 'Central Unit');
        } else {
            $responsible = new Contacts();
            $to = $responsible->getOwnerHoard($data['comment_findID']);
        }

        $cc = array();

        $team = new Users();
        $advisers = $team->getHoardsTeam();

        foreach ($advisers as $adviser) {
            $cc[] = array('email' => $adviser['email'], 'name' => $adviser['fullname']);
        }

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

        $this->_helper->mailer($assignData, 'errorHoard', $to, $cc, $from);
    }
}