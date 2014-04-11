<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Katiebear
 */
class Pas_Controller_Action_Helper_SolrUpdater
    extends Zend_Controller_Action_Helper_Abstract {

    protected $_cores = array(
    'beowulf', 'beopeople', 'beoimages',
    'beopublications','beobiblio','beocontent');

    protected $_solr;

    protected $_config;

    public function __construct(){
    $this->_config = Zend_Registry::get('config')->solr->toArray();
    }

    public function getSolrConfig($core){
    if(in_array($core, $this->_cores)){
    $solrAdapter = $this->_config;
    $solrAdapter['core'] = $core;
    $solr = new Solarium_Client(array(
    'adapteroptions' =>
    $solrAdapter
    ));
    return $solr;
    } else {
    	throw new Exception('That core does not exist', 500);
    }
    }

    public function update($core, $id, $type = NULL){
    $data = $this->getUpdateData($core, $id, $type);

    $this->_solr = $this->getSolrConfig($core);
    $update = $this->_solr->createUpdate();
    $doc = $update->createDocument();
    foreach($data as $k => $v){
    $doc->$k = $v;
    }
    $update->addDocument($doc);
    $update->addCommit();
    return $this->_solr->update($update);
    }

    public function deleteById($core,$id){
    $this->_solr = $this->getSolrConfig($core);
    $update = $this->_solr->createUpdate();
    $update->addDeleteByID( $this->_getIdentifier($core) . $id);
    $update->addCommit();
  
    return  $this->_solr->update($update);
    }

    protected function _getIdentifier($core){
	if(in_array($core, $this->_cores)){
		switch($core) {
			case 'beowulf':
                $identifier = 'finds-';
                break;
            case 'beopeople':
            	$identifier = 'people-';
                break;
            case 'beocontent':
            	$identifier = 'content-';
                break;
            case 'beobiblio':
            	$identifier = 'biblio-';
                break;
            case 'beoimages':
            	$identifier = 'images-';
            	break;
            case 'beopublications':
            	$identifier = 'publications-';
            	break;
            default:
                throw new Exception('Your core does not exist',500);
                break;
		}
		return $identifier;
	} else {
		throw new Exception('That core does not exist', 500);
	}
    }

    public function getUpdateData($core, $id, $type = NULL){
	if(in_array($core, $this->_cores)){
    	switch($core){
            case 'beowulf':
                $model = new Finds();
                break;
            case 'beopeople':
            	$model = new Peoples();
                break;
            case 'beocontent':
            	$type = ucfirst($type);
            	$model = new $type;
            	break;
            case 'beobiblio':
            	$model = new Bibliography();
                break;
            case 'beoimages':
            	$model = new Slides();
            	break;
            case 'beopublications':
            	$model = new Publications();
            	break;
            default:
                throw new Exception('Your core does not exist',500);
                break;
        }
        $data = $model->getSolrData($id);
        $cleanData = $this->cleanData($data[0]);

        return $cleanData;

	} else {
		throw new Exception('That core does not exist',500);
	}
    }

    public function cleanData($data){
	if(array_key_exists('datefound1',$data)){
		if(!is_null($data['datefound1'])) {
		$df1 = $data['datefound1'] . 'T00:00:00Z';
		$data['datefound1'] = $df1;
		} else {
		$data['datefound1'] = NULL;
		}
	}
	if(array_key_exists('datefound2',$data)){
		if(!is_null($data['datefound2'])) {
		$df2 = $data['datefound2'] . 'T00:00:00Z';
		$data['datefound2'] = $df2;
		} else {
		$data['datefound2'] = NULL;
		}
	}
	if(array_key_exists('created',$data)){
		if(!is_null($data['created'])) {
		$created = $this->todatestamp($data['created']);
		$data['created'] = $created;
		} else {
		$data['created'] = NULL;
		}
	}
	if(array_key_exists('updated',$data)){
		if(!is_null($data['updated'])) {
		$updated = $this->todatestamp($data['updated']);
		$data['updated'] = $updated;
		} else {
		$data['updated'] = NULL;
		}
	}
	foreach($data as $k => $v){
		$data[$k] = strip_tags($v);
		if (is_null($v) || $v === "") {
			unset($data[$k]);
		  }
	}
	return $data;

    }


    /** Format the date and return as unix stamp
	*
	* @param string $date_string
	*/
	public function todatestamp($date) {
	$st = strtotime($date);
	$date = new Zend_Date();
	$date->set($st);
	return substr($date->get(Zend_Date::W3C),0,-6) . 'Z';
	}
}

