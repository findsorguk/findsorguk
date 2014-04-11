<?php
/**
* Form for creating and editing coin classification data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class CoinClassForm extends Pas_Form
{

public function __construct($options = null)
{

	$periods = new Periods();
	$period_actives = $periods->getCoinsPeriod();

	parent::__construct($options);

	$this->setName('coinsclass');

	$referenceName = new Zend_Form_Element_Text('referenceName');
	$referenceName->setLabel('Reference volume title: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',60);


	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->SetLabel('Is this volume currently valid: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'));

	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('inArray', false, array(array_keys($period_actives)))
		->addMultiOptions(array(NULL=> NULL,'Choose period:' => $period_actives))
		->addErrorMessage('You must enter a period for this mint');

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($referenceName, $valid, $period, $submit));

	$this->addDisplayGroup(array('referenceName','period','valid'), 'details');

	$this->details->setLegend('Mint details: ');

	$this->addDisplayGroup(array('submit'),'buttons');

	parent::init();
	}

}