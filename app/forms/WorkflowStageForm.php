<?php

class WorkflowStageForm extends Pas_Form
{

public function __construct($options = null)
{

	parent::__construct($options);

	$this->setName('workflow');
	
	$id = new Zend_Form_Element_Hidden('id');
	
	$wfstage = new Zend_Form_Element_Radio('wfstage');
	$wfstage->setRequired(false)
	->addMultiOptions(array(
		'1' => 'Quarantine',
		'2' => 'Review',
		'4' => 'Validation',
		'3' => 'Published'))
	->addFilters(array('StripTags', 'StringTrim'));
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
    
	$this->setLegend('Workflow status');
	
	$this->addElements(array($id, $wfstage, $submit, $hash));
    
	parent::init();
	}
}