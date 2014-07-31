<?php
/** Controller for administering numismatic functions
 * 
 * @category  Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Denominations
 * @uses Mints
 * @uses Rulers
 * @uses Tribes
 * @uses DieAxes
 * @uses Pas_Exception_Param
 * @uses DieAxisForm
 * @uses DenominationForm
 * @uses MintForm
 * @uses RulerFilterForm
 * @uses Pas_ArrayFunctions
 * @uses RulerForm
 * @uses Monarchs
 * @uses RulerImages
 * @uses ReecePeriodEmperors
 * @uses RevTypes
 * @uses MedievalTypes
 * @uses EmperorForm
 * @uses AddMedievalTypeForm
 * @uses Reeces
 * @uses ReverseTypeForm
 * @uses WearTypes
 * @uses AddRulerImageForm
 * @uses Zend_File_Transfer_Adapter_Http
 * @uses DegreeOfWearForm
 * @uses MonarchForm
 * @uses AddDenomToRulerForm
 * @uses DenomRulers
 * @uses IronAgeTribeForm
 * @uses Geography
 * @uses CategoriesCoins
 * @uses MedCategoryForm
 * @uses CoinClassifications
 * @uses ReeceEmperorForm
 * @uses ReecePeriodEmperors
 * @uses CoinClassForm
*/
class Admin_NumismaticsController extends Pas_Controller_Action_Admin {
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        
    }
    
    /** The array functions class
     * @access protected
     * @var \Pas_ArrayFunctions
     */
    protected $_functions;
    
    /** The array functions class
     * @access public
     * @return \Pas_ArrayFunctions
     */
    public function getFunctions() {
        $this->_functions = new Pas_ArrayFunctions();
        return $this->_functions;
    }

    /** The geography model
     * @access protected
     * @var \Geography
     */
    protected $_geography;
    
    /** Get the geography model
     * @access public
     * @return \Geography
     */
    public function getGeography() {
        $this->_geography = new Geography();
        return $this->_geography;
    }

        /** The redirect uri
     * @access protected
     * @var string Redirect
     */		
    protected $_redirectUrl = 'admin/numismatics/';
    
    /** The tribes model
     * @access protected
     * @var \Tribes
     */
    protected $_tribes;
    
    /** Get the tribes model
     * @access public
     * @return \Tribes
     */
    public function getTribes() {
        $this->_tribes = new Tribes();
        return $this->_tribes;
    }

        
    /** Display the numismatic index
     * @access public
     * @return void
     */
    public function indexAction() {
        //Magic in view
    }
    
    /** Display list of all die axes
     * @access public
     * @return void
     */
    public function dieaxesAction() {
        $dieaxes = new DieAxes();
        $this->view->dieaxes = $dieaxes->getDieListAdmin();
    }
    /** Add a die axis
     * @access public
     * @return void
     */
    public function adddieaxisAction() {
        $form = new DieAxisForm();
        $form->submit->setLabel('Add a new die axis term to the system...');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $dieaxes = new DieAxes();
                $insertData = array(
                    'die_axis_name' => $form->getValue('die_axis_name'), 
                    'valid' => $form->getValue('valid'),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                    );
                $dieaxes->insert($insertData);
                $this->getFlash()->addMessage('A new die axis term been created on the system!');
                $this->_redirect($this->_redirectUrl . 'dieaxes/');
            } else {
                $this->getFlash()->addMessage('Please correct errors!');
                $form->populate($formData);
            }
        }
    }
    /** Edit a die axis
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editdieaxisAction() {
        if($this->_getParam('id',false)) {
            $form = new DieAxisForm();
            $form->submit->setLabel('Update details on database...');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $dieaxes = new DieAxes();
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
                    $this->getFlash()->addMessage('Die axis information updated!');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
                    $form->populate($formData);
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $dieaxes = new DieAxes();
                    $dieaxis = $dieaxes->fetchRow('id=' . $id);
                    if(count($dieaxis)) {
                        $form->populate($dieaxis->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete a die axis
     * @access public
     * @return void
     */
    public function deletedieaxisAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $dieaxes = new DieAxes();
                $where = 'id = ' . $id;
                $dieaxes->delete($where);
            }	
            $this->getFlash()->addMessage('Record deleted!');
            $this->_redirect($this->_redirectUrl . 'dieaxes');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $dieaxes = new DieAxes();
                $this->view->dieaxis = $dieaxes->fetchRow('id=' . $id);
            }
        }
    }
    /** Add a denomination
     * @access public
     * @return void
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
                $this->getFlash()->addMessage('A new denomination has been created on the system!');
                $this->_redirect($this->_redirectUrl . 'denominations/period/' . $insert);
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit a denomination
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $this->getFlash()->addMessage('Denomination information updated!');
                    $this->_redirect($this->_redirectUrl . 'denominations/period/' . (int)$form->getValue('period'));
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
                    $form->populate($formData);
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $denominations = new Denominations();
                    $denomination = $denominations->fetchRow('id=' . $id);
                    if(count($denomination)) {
                        $form->populate($denomination->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete a denomination
     * @access public
     * @return void
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
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function denominationsAction() {
        if($this->_getParam('period',false)){
            $period = $this->_getParam('period');
            $this->view->period = $period;
            $denoms = new Denominations();
            $this->view->paginator = $denoms->getDenominations($period,$this->_getParam('page'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    } 
    /** Add a medieval ruler
     * @access public
     * @return void
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
                $monarchs->insert($insertData);
                $this->getFlash()->addMessage('Biography for ' . $form->getValue('name') . ' created.');
                $this->_redirect($this->_redirectUrl . 'medruler/id/' . $dbaseID);
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** List mints
     * @access public
     * @return void
     */
    public function mintsAction() {
        $mints = new Mints();
        $this->view->paginator = $mints->getMintsListAllAdmin($this->_getAllParams());
    }

    /** Add a new mint
     * @access public
     * @return void
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
                $mints->insert($insertData);
                $this->_redirect($this->_redirectUrl . 'mints');
                $this->getFlash()->addMessage('A new mint has been created on the system!');
            } else  {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($this->_request->getPost());
            }
        }
    }
    
    /** Edit a mint
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $mints->update($updateData, $where);
                    $this->getFlash()->addMessage('Active mint information updated!');
                $this->_redirect($this->_redirectUrl . 'mints/period/' 
                        . $form->getValue('period'));
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
                    $form->populate($this->_request->getPost());
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete a mint
     * @access public
     * @return void
     */
    public function deletemintAction() {
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
     * @access public
     * @return void
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
                $params = $this->_functions->array_cleanup($formData);
                $where = array();
                foreach($params as $key => $value) {
                    if(!is_null($value)){
                        $where[] = $key . '/' . urlencode(strip_tags($value));
                    }
                }
                $whereString = implode('/', $where);
                $query = $whereString;
                $this->_redirect('admin/numismatics/rulers/period/' 
                        . $this->_getParam('period') . '/' 
                        . $query . '/');
            } else  {
                $form->populate($this->_request->getPost());
            }
        }
    }
    /** Add a ruler
     * @access public
     * @return void
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
                $this->getFlash()->addMessage($form->getValue('issuer') . ' has been added to the system!');
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($this->_request->getPost());
            }
        }
    }
    /** Edit a ruler
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $this->getFlash()->addMessage($form->getValue('issuer') . '\'s information updated!');
                    $this->_redirect($this->_redirectUrl . 'rulers/period/' . $form->getValue('period'));
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a ruler
     * @access public
     * @return void
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
            $this->getFlash()->addMessage('Record deleted!');
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
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
        $reverses = new RevTypes();
        $this->view->reverses = $reverses->getTypesAdmin($id);
        $reece = new ReecePeriodEmperors();
        $this->view->reeces = $reece->fetchRow('ruler_id = '. $id );
        } else {
                throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }	
    /** Iron age ruler details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** medieval ruler details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Byzantine ruler details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function byzrulerAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }	
    /** Greek and roman prov ruler detais
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function greekrulerAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Roman emperor biographical details
     * @access public
     * @return void
     */
    public function emperorbiosAction() {
        $emperors = new Emperors();
        $this->view->paginator = $emperors->getEmperorsAdminList($this->_getParam('page'));
    }
    /** Add an emperor
     * @access public
     * @return void
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
                $this->getFlash()->addMessage('A new Emperor or issuer has been created!');
                $this->_redirect($this->_redirectUrl . 'emperorbios/');
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit an emperor
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $this->getFlash()->addMessage('Issuer details for ' . $form->getValue('name') . ' updated!');
                    $this->_redirect($this->_redirectUrl . 'emperorbios/');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
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
                throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete an emperor
     * @access public
     * @return void
     */
    public function deleteemperorAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $emperors = new Emperors();
                $where = 'id = ' . $id;
                $emperors->delete($where);
                $this->getFlash()->addMessage('Issuer or Emperor details '
                        . 'deleted! This cannot be undone.');
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
     * @access public
     * @return void
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
                $this->getFlash()->addMessage('You entered the type: <em>' . $form->getValue('type')
                . '</em> successfully. It is now available for use.');
                $this->_redirect($this->_redirectUrl . 'medruler/id/' . $form->getValue('rulerID'));
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit a medieval type
     * @access public
     * @return void
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
                $this->getFlash()->addMessage('You updated: <em>' 
                        . $form->getValue('type') 
                        . '</em> successfully. It is now available for use.');
                $this->_redirect($this->_redirectUrl);
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
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
     * @access public
     * @return void
     */
    public function reeceperiodsAction(){
        $reeces = new Reeces();
        $this->view->reeces = $reeces->getReecesAdmin();
    }

    /** Add reece period
     * @access public
     * @return void
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
                $reeces->insert($insertData);
                $this->getFlash()->addMessage('A new Reece Period has been created!');
                $this->_redirect($this->_redirectUrl . 'reeceperiods/');
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit a reece period
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $this->getFlash()->addMessage('Reece Period details updated!');
                    $this->_redirect($this->_redirectUrl . 'reeceperiods/');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a reece period
     * @access public
     * @return void
     */
    public function deletereeceperiodAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $reeces = new Reeces();
                $where = 'id = ' . $id;
                $reeces->delete($where);
            }
            $this->_redirect($this->_redirectUrl . 'reeceperiods/');
            $this->getFlash()->addMessage('Reece Period details deleted! This cannot be undone.');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $reeces = new Reeces();
                $this->view->reece = $reeces->fetchRow('id =' . $id);
            }
        }
    }
    /** List reverse types
     * @access public
     * @return void
     */
    public function reversetypesAction() {
        $reverses = new RevTypes();
        $this->view->reverses = $reverses->getReverseTypeList(1);
        $this->view->uncommonreverses = $reverses->getReverseTypeList(2);
    }
    /** Add reverse types
     * @access public
     * @return void
     */
    public function addreversetypeAction() {
        $form = new ReverseTypeForm();
        $form->submit->setLabel('Add a new reverse type');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
        $formData = $this->_request->getPost();
        if ($form->isValid($formData)) {
        $reverses = new RevTypes();
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
        $this->getFlash()->addMessage('The reverse type ' . $form->getValue('type') 
        . ' has been created.');
        $this->_redirect($this->_redirectUrl . 'reversetypes');
        } else {
        $form->populate($formData);
        $this->getFlash()->addMessage($this->_formErrors);
        }
        }
    }
    /** Edit reverse type
     * @access public
     * @return void
    */
    public function editreversetypeAction() {
        if($this->_getParam('id',false)) {
            $form = new ReverseTypeForm();
            $form->submit->setLabel('Save reverse type\'s details');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $reverses = new RevTypes();
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
                    $this->getFlash()->addMessage(
                            'Reverse type details for '
                            . $form->getValue('type') 
                            . ' updated!');
                    $this->_redirect($this->_redirectUrl.'reversetypes/');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
                    $form->populate($formData);
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $reverses = new RevTypes();
                    $reverse = $reverses->fetchRow('id=' . $id);
                    $form->populate($reverse->toArray());
                }
            }
        } else 	{
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete reverse type
     * @access public
     * @return void
     */	
    public function deletereversetypeAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $reverses = new RevTypes();
                $where = 'id = ' . $id;
                $reverses->delete($where);
            }
            $this->_redirect($this->_redirectUrl . 'reversetypes/');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $reverses = new RevTypes();
                $this->view->reverse = $reverses->fetchRow('id =' . $id);
            }
        }
    }
    /** Add an image for a ruler
     * @access public
     * @return void
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
                $upload->addValidator('NotExists', false,array('./assets/rulers/'));
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
                    $location = './assets/rulers/';
                    $largepath = './assets/rulers/resized/';
                    $smallpath = './assets/rulers/thumbnails/';
                    $name = substr($filename, 0, strrpos($filename, '.')); 

                    $ext = '.jpg';

                    $converted = $name.$ext;
                    $convertlarge  = $largepath.$converted;
                    $original = $location.$converted;
                    $convertsmall = $smallpath.$converted;
                    //create medium size
                    $phMagick = new phMagick($original, $convertlarge);
                    $phMagick->resize(300,0);
                    //create thumbnail size
                    $phMagick = new phMagick($original, $convertsmall);
                    $phMagick->resize(100,0);
                    $phMagick->convert();

                    //Zoom it baby
                    $rulers = new RulerImages();
                    $upload->receive();
                    $rulers->insert($insertData);
                    $this->getFlash()->addMessage('The image has been resized.');
                    $this->_redirect($this->_redirectUrl . 'romanruler/id/' 
                            . $this->_getParam('rulerid')); 
                } else {
                    $this->getFlash()->addMessage(
                            'There is a problem with your upload. 
                                Probably that image exists.');
                    $this->view->errors = $upload->getMessages();
                } 
            } else { 
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
            }
        }
    }
    /** List degrees of wear
     * @access public
     * @return void
     */
    public function degreesofwearAction() {
        $wears = new WearTypes();
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
                $degrees = new WearTypes();
                $insertData = array(
                    'term' =>  $form->getValue('term'),
                    'termdesc' => $form->getValue('termdesc'),
                    'valid' => $form->getValue('valid'),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                );
                $degrees->insert($insertData);
                $this->getFlash()->addMessage('New degree of wear term entered');
                $this->_redirect($this->_redirectUrl . 'degreesofwear/');
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit a degree of wear
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $this->getFlash()->addMessage('Degree of wear: ' . $form->getValue('term') . ' updated!');
                    $this->_redirect($this->_redirectUrl . 'degreesofwear');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
                    $form->populate($formData);
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $degrees = new WearTypes();
                    $degree = $degrees->fetchRow('id=' . $id);
                    if(count($degree)){
                        $form->populate($degree->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete a degree of wear
     * @access public
     * @return void
     */
    public function deletedegreeofwearAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $wears = new WearTypes();
                $where = 'id = ' . $id;
                $wears->delete($where);
            }
            $this->getFlash()->addMessage('Degree of wear deleted!');
            $this->_redirect($this->_redirectUrl.'degreesofwear/');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $wears = new WearTypes();
                $this->view->degree = $wears->fetchRow('id ='.$id);
            }
        }
    }
    /** Edit a medieval ruler
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editmedievalrulerAction() {
        if($this->_getParam('id',false)) {
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
                    $monarchs->update($updateData, $where);
                    $this->getFlash()->addMessage('Monarch data updated.');
                    $this->_redirect('admin/numismatics/medruler/id/'.$id);
                } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $monarchs = new Monarchs();
                    $monarch = $monarchs->fetchRow('dbaseID ='.(int)$id);
                    $this->view->headTitle('Edit biography for :  '. $monarch['name']);
                    if(count($monarch)) {
                        $form->populate($monarch->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    
    /** Link a ruler to a denomination
     * @access public
     * @return void
     * 
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
                $denoms->insert($insertData);
                $this->getFlash()->addMessage('A new denomination has been added.');
                if($period == 21){
                    $this->_redirect($this->_redirectUrl . 'romanruler/id/' . $rulerid);
                } else {
                    $this->_redirect($this->_redirectUrl . 'medruler/id/' . $rulerid);
                }
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Link a ruler to a mint
     * @access public
     * @return void
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
                $mintrulers->insert($insertData);
                $this->getFlash()->addMessage('A new mint has been entered.');
                if($period == 21){
                    $this->_redirect($this->_redirectUrl . 'romanruler/id/' . $rulerid);
                } else {
                    $this->_redirect($this->_redirectUrl . 'medruler/id/' . $rulerid);	
                }
            } else {
            $this->getFlash()->addMessage($this->_formErrors);
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
        $reversetypes = new RevTypes();
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
                $reverses->insert($insertData);
                $this->getFlash()->addMessage('A new mint has been entered.');
                $this->_redirect($this->_redirectUrl . 'romanruler/id/' . $rulerid);
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }

    /** List Iron Age tribes
     * @access public
     * @return void
     */
    public function tribesAction(){
        $this->view->tribes = $this->getTribes()->getTribesListAdmin($this->_getParam('page'));
    }
    /** Edit iron age tribe details
     * @access public
     * @return void
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
                $where = array();
                $where[] = $this->getTribes()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                $updateData = array(
                    'tribe' =>  $form->getValue('tribe'),
                    'description' => $form->getValue('description'),
                    'updated' => $this->getTimeForForms(),
                    'updatedBy' => $this->getIdentityForForms()
                );
                $this->getTribes()->update($updateData,$where);
                $this->getFlash()->addMessage('Details for ' . $form->getValue('tribe') . ' updated!');
                $this->_redirect($this->_redirectUrl . 'tribes/');
            } else {
                $form->populate($formData);
                $this->getFlash()->addMessage($this->_formErrors);
            }
        } else {
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $tribe = $this->getTribes()->fetchRow('id=' . $id);
                if(count($tribe)){
                    $form->populate($tribe->toArray());
                } else {
                    throw new Pas_Exception_Param($this->_nothingFound);
                }
            }
        }
    } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
    }
    }
    
    /** Add a tribe
     * @access public
     * @return void
    */	
    public function addtribeAction() {
        $form = new IronAgeTribeForm();
        $form->details->setLegend('Add a new tribe\'s details');
        $form->submit->setLabel('Submit tribe\'s details');
        $this->view->form =$form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $insertData = array(
                    'tribe' =>  $form->getValue('tribe'),
                    'description' => $form->getValue('description'),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                );
                $this->getTribes()->insert($insertData);
                $this->getFlash()->addMessage('You have created the iron age tribe: '
                        . $form->getValue('tribe'));
                $this->_redirect($this->_redirectUrl . 'tribes');
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Delete a tribe
     * @access public
     * @return void
     */		
    public function deletetribeAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->getTribes()->delete($where);
            }
            $this->_redirect('/admin/tribes/');
            $this->getFlash()->addMessage('Tribe deleted!');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->tribe = $this->getTribes()->fetchRow('id ='.$id);
            }
        }
    }	
    /** List iron age regions
     * @access public
     * @return void
     */
    public function regionsAction() {
        $this->view->regions = $this->getGeography()->getIronAgeRegionsAdmin();
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
                $insertData = array(
                    'area' => $form->getValue('area'),
                    'region' => $form->getValue('region'),
                    'tribe' =>  $form->getValue('tribe'),
                    'description' => $form->getValue('description'),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                );
                $this->getGeography()->insert($insertData);
                $this->getFlash()->addMessage('You have created the iron age tribe: '
                . $form->getValue('tribe'));
                $this->_redirect($this->_redirectUrl . 'regions');
            } else {
                $form->populate($formData);
                $this->getFlash()->addMessage($this->_formErrors);
            }
        }
    }
    /** Edit iron age region
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $where = array();
                    $where[] = $this->getGeography()->getAdapter()
                            ->quoteInto('id = ?', $this->_getParam('id'));
                    $updateData = array(
                    'area' => $form->getValue('area'),
                    'region' => $form->getValue('region'),
                    'tribe' =>  $form->getValue('tribe'),
                    'description' => $form->getValue('description'),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                    );
                    $update = $geog->update($updateData,$where);
                    $this->getFlash()->addMessage('Details updated!');
                    $this->_redirect('/admin/regions/');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
                    $form->populate($formData);
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $geo = $this->getGeography()->fetchRow('id=' . $id);
                    if(count($geo)){
                        $form->populate($geo->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete iron age region
     * @access public
     * @return void
     */
    public function deleteregionAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->getGeography()->delete($where);
            }
            $this->getFlash()->addMessage('Region deleted!');
            $this->_redirect($this->_redirectUrl . 'regions');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->geog = $this->getGeography()->fetchRow('id =' . $id);
            }
        }
    }
    /** List medieval categories
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function categoriesAction() {
        if($this->_getParam('period',false)) {
            $categories = new CategoriesCoins();
            $this->view->categories = $categories->getCategoriesPeriodAdmin((int)$this->_getParam('period'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Add a medieval category
     * @access public
     * @return void
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
                $cats->insert($insertData);
                $this->getFlash()->addMessage(
                        'The medieval category of ' 
                        . $form->getValue('category')
                        . ' has been created.');
                $this->_redirect($this->_redirectUrl . 'categories/period/' . $form->getValue('periodID'));
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit a medieval category
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $cats->update($updateData,$where);
                    $this->getFlash()->addMessage(
                            'Reverse type details for ' 
                            . $form->getValue('type') 
                            . ' updated!');
                    $this->_redirect($this->_redirectUrl . 'categories/period/' . $form->getValue('periodID'));
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete a medieval category
     * @access public
     * @return void
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
            $this->getFlash()->addMessage('Medieval category deleted!');
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
     * @access public
     * @return void
     */	
    public function typesAction() {
        $types = new MedievalTypes();
        $this->view->paginator = $types->getTypesByPeriodAdmin($this->_getAllParams());
    }
    /** Add a medieval type
     * @access public
     * @return void
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
                $this->getFlash()->addMessage('The medieval type '
                . $form->getValue('type') . ' has been created.');
                $this->_redirect($this->_redirectUrl . 'types/period/' . $form->getValue('periodID'));
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }

    /** Edit a medieval type
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $this->getFlash()->addMessage(
                            'Reverse type details for '
                            . $form->getValue('type') 
                            . ' updated!');
                    $this->_redirect(
                            $this->_redirectUrl 
                            . 'types/period/' 
                            . $form->getValue('periodID'));
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    
    /** Delete a medieval type
     * @access public
     * @return void
     */
    public function deletetypeAction() {
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
     * @access public
     * @return void
     */
    public function dynastiesAction() {
        $dynasties = new Dynasties();
        $this->view->dynasties = $dynasties->getDynastyListAdmin();
    }
    /** Add a roman dynasty
     * @access public
     * @return void
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
                $dynasties->insert($insertData);
                $this->getFlash()->addMessage(
                        'Dynasty ' . $form->getValue('dynasty') 
                        . ' has been created.');
                $this->_redirect($this->_redirectUrl.'dynasties');
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit a roman dynasty
     * @access public
     * @return void
     * @throws Pas_Exception_Param
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
                    $this->getFlash()->addMessage(
                            'Dynasty details for ' . $form->getValue('dynasty')
                            . ' updated!');
                    $this->_redirect($this->_redirectUrl.'dynasties');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete a roman dynasty
     * @access public
     * @return void
     */
    public function deletedynastyAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $dynasties = new Dynasties();
                $where = 'id = ' . $id;
                $dynasties->delete($where);
                $this->getFlash()->addMessage('Dynasty  deleted!');
            }
            $this->_redirect($this->_redirectUrl.'dynasties');
        }  else   {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $dynasties = new Dynasties();
                $this->view->dynasty = $dynasties->fetchRow('id =' . $id);
            }
        }
    }
    /** Add a reference type 
     * @access public
     * @return void
     */
    public function addrefAction() {
        $form = new CoinClassForm();
        $form->details->setLegend('Add a new coin reference volume');
        $form->submit->setLabel('Submit details');
        $this->view->form =$form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $classes = new CoinClassifications();
                $insertData = array(
                    'referenceName' => $form->getValue('referenceName'),
                    'valid' => $form->getValue('valid') ,
                    'period' => $form->getValue('period'),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                );
                $classes->insert($insertData);
                $this->getFlash()->addMessage('New reference volume added');
                $this->_redirect('/admin/numismatics/refs/');
            } else {
                $this->getFlash()->addMessage($this->_formErrors);
                $form->populate($formData);
            }
        }
    }
    /** Edit a reference type
     * @access public
     * @return void
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
                    $classes = new CoinClassifications();
                    $where = array();
                    $where[] = $classes->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $updateData = array(
                        'referenceName' => $form->getValue('referenceName'),
                        'valid' => $form->getValue('valid') ,
                        'period' => $form->getValue('period'),
                        'updated' => $this->getTimeForForms(),
                        'updatedBy' => $this->getIdentityForForms()
                     );
                    $classes->update($updateData,$where);
                    $this->getFlash()->addMessage('Reference volume details changed');
                    $this->_redirect('/admin/numismatics/refs/');
                } else {
                    $this->getFlash()->addMessage($this->_formErrors);
                    $form->populate($formData);
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $classes = new CoinClassifications();
                    $class = $classes->fetchRow('id=' . $id);
                    if(count($class)){
                        $form->populate($class->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** List reference types
     * @access public
     * @return void
     */
    public function refsAction() {
        $refs = new CoinClassifications();
        $this->view->refs = $refs->getRefs();
    }

    /** Add a Reece period
     * @access public
     * @return void
     */
    public function addreeceAction(){
        $form = new ReeceEmperorForm();
        $form->submit->setLabel('Submit details');
        $this->view->form =$form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $updateData = array(
                    'periodID' => 21,
                    'ruler_id' => $this->_getParam('rulerid'),
                    'reeceperiod_id' => $form->getValue('reeceperiod_id')
                );
                $periods = new ReecePeriodEmperors();
                $update = $periods->add($updateData);
                $this->_redirect('/admin/numismatics/romanruler/id/' . $this->_getParam('rulerid'));
                $this->getFlash()->addMessage('Period added');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a Reece period
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
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
                    $this->getFlash()->addMessage('Reece period updated');
                    $this->_redirect('/admin/numismatics/romanruler/id/' . $this->_getParam('rulerid'));
                    } else {
                        $form->populate($form->getValues());
                    }
            } else {
                $id = (int)$this->_request->getParam('rulerid', 0);
                if ($id > 0) {
                    $periods = new ReecePeriodEmperors();
                    $activity = $periods->fetchRow('ruler_id=' . (int)$id);
                    if(count($activity))	{
                        $form->populate($activity->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}