<?php

class Database_OrganisationsController extends Pas_Controller_Action_Admin {

	protected $_gmapskey,$_config,$_geocoder, $_organisations;

	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_geocoder = new Pas_Service_Geo_Coder();
	$this->_organisations = new Organisations();
    }

	const REDIRECT = 'database/organisations/';
    /** Index page, listing all organisations
	*/
	public function indexAction() {
	$organisations = new Organisations();
	$paginator = $organisations->getOrganisations((array)$this->_getAllParams());
	$this->view->paginator = $paginator;
	$form = new OrganisationFilterForm();
	$this->view->form = $form;
	$form->organisation->setValue($this->_getParam('organisation'));
	$form->contact->setValue($this->_getParam('contact'));
	$form->contactpersonID->setValue($this->_getParam('contactpersonID'));
	$form->county->setValue($this->_getParam('county'));

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
	$this->_redirect(self::REDIRECT.'index/'.$query.'/');
	} else {
	$form->populate($formData);
	}
	}
	}
    /** Details page for an organisation.
	*/
	public function organisationAction() {
	if($this->_getParam('id',false)){
	$orgs = new Organisations();
	$this->view->orgs = $orgs->getOrgDetails($this->_getParam('id'));
	$members = new Organisations();
	$this->view->members = $members->getMembers($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

    /** Edit an organisation's details
	*/
	public function editAction() {
	$form = new OrganisationForm();
	$form->submit->setLabel('Update organisation');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('address') . ',' . $form->getValue('town_city') . ','
	. $form->getValue('county') . ',' . $form->getValue('postcode') . ','
	. $form->getValue('country');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$lon = $coords['lon'];
		$pm = new Pas_Service_Geo_Geoplanet();
		$place = $pm->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}

	$updateData = array();
	$updateData['name'] = $form->getValue('name');
	$updateData['website'] = $form->getValue('website');
	$updateData['address1'] = $form->getValue('address1');
	$updateData['address2'] = $form->getValue('address2');
	$updateData['address3'] = $form->getValue('address3');
	$updateData['address'] = $form->getValue('address');
	$updateData['postcode'] = $form->getValue('postcode');
	$updateData['town_city'] = $form->getValue('town_city');
	$updateData['county'] = $form->getValue('county');
	$updateData['country'] = $form->getValue('country');
	$updateData['contactpersonID'] = $form->getValue('contactpersonID');
	$updateData['lat'] = $lat;
	$updateData['lon'] = $lon;
	$updateData['woeid'] = $woeid;
	$updateData['updated'] = $this->getTimeForForms();
	$updateData['updatedBy'] = $this->getIdentityForForms();
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
        $updateData[$key] = NULL;
      }
	 }

	$auditData = $updateData;
	$audit = $this->_organisations->fetchRow('id=' . $this->_getParam('id'));
	$oldarray = $audit->toArray();


	$where = array();
	$where =  $this->_organisations->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_organisations->update($updateData,$where);

	if (!empty($auditData)) {
        // look for new fields with empty/null values
        foreach ($auditData as $item => $value) {
            if (empty($value)) {
                if (!array_key_exists($item, $oldarray)) {
                    // value does not exist in $oldarray, so remove from $newarray
                    unset ($updateData[$item]);
                } // if
            } else {
                // remove slashes (escape characters) from $newarray
                $auditData[$item] = stripslashes($auditData[$item]);
            } // if
        } // foreach
        // remove entry from $oldarray which does not exist in $newarray
        foreach ($oldarray as $item => $value) {
            if (!array_key_exists($item, $auditData)) {
                unset ($oldarray[$item]);
            } // if
        } // foreach
    } //

	$fieldarray   = array();
    $ix           = 0;
	$editID = md5($this->getTimeForForms());
    foreach ($oldarray as $field_id => $old_value) {
        $ix++;
		$fieldarray[$ix]['orgID']     = $this->_getParam('id');
		$fieldarray[$ix]['editID']     = $editID;
        $fieldarray[$ix]['created']     = $this->getTimeForForms();
		$fieldarray[$ix]['createdBy']     = $this->getIdentityForForms();
        $fieldarray[$ix]['fieldName']     = $field_id;
        $fieldarray[$ix]['beforeValue']    = $old_value;
        if (isset($auditData[$field_id])) {
            $fieldarray[$ix]['afterValue'] = $auditData[$field_id];
            // remove matched entry from $newarray
            unset($auditData[$field_id]);
        } else {
            $fieldarray[$ix]['afterValue'] = '';
        } // if
    } // foreach

    // process any unmatched details remaining in $newarray
    foreach ($auditData as $field_id => $new_value) {
        $ix++;
		$fieldarray[$ix]['orgID']     = $this->_getParam('id');
		$fieldarray[$ix]['editID']     = $editID;
        $fieldarray[$ix]['created']     = $this->getTimeForForms();
		$fieldarray[$ix]['createdBy']     = $this->getIdentityForForms();
        $fieldarray[$ix]['fieldName']     = $field_id;
        $fieldarray[$ix]['afterValue']    = $new_value;

    }
	function filteraudit($fieldarray)
	{
	if ($fieldarray['afterValue'] != $fieldarray['beforeValue'])
	  {
	return true;
	  }
	}

	$fieldarray = array_filter($fieldarray,'filteraudit');

	foreach($fieldarray as $f){
	foreach ($f as $key => $value) {
      if (is_null($value) || $value=="") {
       $f[$key] = NULL;
      }
    }

	$audit = new OrganisationsAudit();
	$auditBaby = $audit->insert($f);
	}
	$this->_flashMessenger->addMessage('Organisation information updated!');
	$this->_redirect(self::REDIRECT.'organisation/id/' . $this->_getParam('id'));
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$organisations = new Organisations();
	$organisation = $organisations->fetchRow('id='.$id);
	$form->populate($organisation->toArray());
	}
	}
	}
   /** Add an organisation
	*/
	public function addAction() {
	$form = new OrganisationForm();
	$form->submit->setLabel('Add a new organisation');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('address') . ',' . $form->getValue('town_city')
	. ',' . $form->getValue('county') . ',' . $form->getValue('postcode') . ','
	. $form->getValue('country');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$lon = $coords['lon'];
		$pm = new Pas_Service_Geo_Geoplanet();
		$place = $pm->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}
	$insertData['secuid'] = $this->secuid();
	$insertData['name'] = $form->getValue('name');
	$insertData['website'] = $form->getValue('website');
	$insertData['address1'] = $form->getValue('address1');
	$insertData['address2'] = $form->getValue('address2');
	$insertData['address3'] = $form->getValue('address3');
	$insertData['address'] = $form->getValue('address');
	$insertData['postcode'] = $form->getValue('postcode');
	$insertData['town_city'] = $form->getValue('town_city');
	$insertData['county'] = $form->getValue('county');
	$insertData['country'] = $form->getValue('country');
	$insertData['contactpersonID'] = $form->getValue('contactpersonID');
	$insertData['lat'] = $lat;
	$insertData['lon'] = $lon;
	$insertData['woeid'] = $woeid;
	$insertData['updated'] = $this->getTimeForForms();
	$insertData['updatedBy'] = $this->getIdentityForForms();
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
	 }
	$insert = $this->_organisations->insert($insertData);
	$this->_redirect(self::REDIRECT . 'organisation/id/' . $insert);
	$this->_flashMessenger->addMessage('Record created!');
	} else {
	$form->populate($formData);
	}
	}
	}

   /** Delete an organisation
	*/
	public function deleteAction() {
	if($this->_getParam('id',false)) {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$organisations = new Organisations();
	$where = 'id = ' . $id;
	$organisations->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->organisation = $this->_organisations->fetchRow('id='.$id);
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}