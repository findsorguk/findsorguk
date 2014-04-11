<?php
/** Form for searching for Roman numismatics
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanNumismaticSearchForm extends Pas_Form
{


    protected $_higherlevel = array('admin','flos','fa','heros');

    protected $_restricted = array(null, 'public','member','research');


    public function __construct($options = null) {
    //Get Rally data
    $rallies = new Rallies();
    $rally_options = $rallies->getRallies();

    //Get Hoard data
    $hoards = new Hoards();
    $hoard_options = $hoards->getHoards();

    $counties = new OsCounties();
    $county_options = $counties->getCountiesID();

    $denominations = new Denominations();
    $denom_options = $denominations->getOptionsRoman();

    $rulers = new Rulers();
    $ruler_options = $rulers->getRomanRulers();

    $mints = new Mints();
    $mint_options = $mints->getRomanMints();

    $axis = new Dieaxes();
    $axis_options = $axis->getAxes();

    $reece = new Reeces();
    $reece_options = $reece->getReeces();

    $regions = new OsRegions();
    $region_options = $regions->getRegionsID();
    
    $moneyers = new Moneyers();
    $money = $moneyers->getRepublicMoneyers();


    $institutions = new Institutions();
    $inst_options = $institutions->getInsts();

    parent::__construct($options);



    $this->setName('search-roman-coins');

    $old_findID = new Zend_Form_Element_Text('old_findID');
    $old_findID->setLabel('Find number: ')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addErrorMessage('Please enter a valid number!');

    $description = new Zend_Form_Element_Text('description');
    $description->setLabel('Object description contains: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',90)
		->addErrorMessage('Please enter a valid term');


    $workflow = new Zend_Form_Element_Select('workflow');
    $workflow->setLabel('Workflow stage: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');
    if(in_array($this->_role,$this->_higherlevel)) {
    $workflow->addMultiOptions(array(
        NULL => 'Choose workflow',
        'Available worklow stage' => array(
            '1'=> 'Quarantine',
            '2' => 'On review',
            '4' => 'Awaiting validation',
            '3' => 'Published')
        ));
    }
    if(in_array($this->_role,$this->_restricted)) {
    $workflow->addMultiOptions(array(
        NULL => 'Choose a workflow stage',
        'Available worklow stage' => array(
            '4' => 'Awaiting validation',
            '3' => 'Published')
        ));
    }

    $hash = new Zend_Form_Element_Hash('csrf');
    $hash->setValue($this->_salt)
            ->setTimeout(4800);
    $this->addElement($hash);

    //Rally details
    $rally = new Zend_Form_Element_Checkbox('rally');
    $rally->setLabel('Rally find: ')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setUncheckedValue(NULL);

    $rallyID =  new Zend_Form_Element_Select('rallyID');
    $rallyID->setLabel('Found at this rally: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array( NULL => 'Choose rally name','Available rallies' => $rally_options))
		->addValidator('InArray', false, array(array_keys($rally_options)));

    $hoard = new Zend_Form_Element_Checkbox('hoard');
    $hoard->setLabel('Hoard find: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL);

    $hoardID =  new Zend_Form_Element_Select('hID');
    $hoardID->setLabel('Part of this hoard: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose hoard name', 'Available hoards' => $hoard_options))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($hoard_options)));

    $county = new Zend_Form_Element_Select('countyID');
    $county->setLabel('County: ')
		->addValidators(array('NotEmpty'))
		->addMultiOptions(array( NULL => 'Choose county first', 'Available counties' => $county_options))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($county_options)));

    $district = new Zend_Form_Element_Select('districtID');
    $district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->setRegisterInArrayValidator(false)
		->addValidator('District')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->disabled = true;

    $parish = new Zend_Form_Element_Select('parishID');
    $parish->setLabel('Parish: ')
		->addMultiOptions(array(NULL => 'Choose parish after county'))
	    ->setRegisterInArrayValidator(false)
		->addValidator('Parish')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->disabled = true;

    $regionID = new Zend_Form_Element_Select('regionID');
    $regionID->setLabel('European region: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a region for a wide result','Choose region' => $region_options))
		->addValidator('InArray', false, array(array_keys($region_options)))
		->addFilters(array('StripTags', 'StringTrim'));

    $gridref = new Zend_Form_Element_Text('gridref');
    $gridref->setLabel('Grid reference: ')
		->addValidators(array('ValidGridRef'))
		->addFilters(array('StripTags', 'StringTrim'));

    $fourFigure = new Zend_Form_Element_Text('fourFigure');
    $fourFigure->setLabel('Four figure grid reference: ')
            ->addValidators(array('ValidGridRef'))
            ->addFilters(array('StripTags', 'StringTrim'));
    ###
    ##Numismatic data
    ###
    //Denomination
    $denomination = new Zend_Form_Element_Select('denomination');
    $denomination->setLabel('Denomination: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination type','Available denominations' => $denom_options))
		->addValidator('InArray', false, array(array_keys($denom_options)))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');
		
    //Primary ruler
    $ruler = new Zend_Form_Element_Select('ruler');
    $ruler->setLabel('Ruler / issuer: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose primary ruler','Available rulers'=> $ruler_options))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($ruler_options)));

		//Mint
    $mint = new Zend_Form_Element_Select('mint');
    $mint->setLabel('Issuing mint: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose issuing mint', 'Available mints' => $mint_options))
		->addValidator('InArray', false, array(array_keys($mint_options)))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');
		
    //Reece
    $reece = new Zend_Form_Element_Select('reeceID');
    $reece->setLabel('Reece period: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array( NULL => 'Choose Reece period', 'Available Reece periods' => $reece_options))
		->addValidator('InArray', false, array(array_keys($reece_options)))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');

    //Reverse type
    $reverse = new Zend_Form_Element_Select('revtypeID');
    $reverse->setLabel('Fourth Century reverse type: ')
		->setDescription('This field is only applicable for fourth century AD coins.')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Only available after choosing a 4th century issuer'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');
    //Moneyer
    $moneyer = new Zend_Form_Element_Select('moneyer');
    $moneyer->setLabel('Republican moneyers: ')
		->setDescription('This field is only applicable for Republican coins.')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Only available after choosing a Republican issuer'))
		->addValidator('InArray', false, array(array_keys($money)))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');
    //Obverse inscription
    $obverseinsc = new Zend_Form_Element_Text('obverseLegend');
    $obverseinsc->setLabel('Obverse inscription contains: ')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('size',60)
            ->addErrorMessage('Please enter a valid term');

    //Obverse description
    $obversedesc = new Zend_Form_Element_Text('obverseDescription');
    $obversedesc->setLabel('Obverse description contains: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('size',60)
            ->addErrorMessage('Please enter a valid term');

    //reverse inscription
    $reverseinsc = new Zend_Form_Element_Text('reverseLegend');
    $reverseinsc->setLabel('Reverse inscription contains: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addErrorMessage('Please enter a valid term')
            ->setAttrib('size',60);

    //reverse description
    $reversedesc = new Zend_Form_Element_Text('reverseDescription');
    $reversedesc->setLabel('Reverse description contains: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addErrorMessage('Please enter a valid term')
            ->setAttrib('size',60);

    //Die axis
    $axis = new Zend_Form_Element_Select('axis');
    $axis->setLabel('Die axis measurement: ')
		->setRequired(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose die axis','Available axes' => $axis_options))
		->addValidator('InArray', false, array(array_keys($axis_options)));

    $objecttype = new Zend_Form_Element_Hidden('objecttype');
    $objecttype->setValue('coin')
             ->addFilter('StringToUpper');

    $broadperiod = new Zend_Form_Element_Hidden('broadperiod');
    $broadperiod->setValue('Roman')
            ->setAttrib('class', 'none')
            ->addFilters(array('StringToUpper', 'StripTags', 'StringTrim'))
            ->addValidator('Alpha');
    //Submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Search');

    $institution = new Zend_Form_Element_Select('institution');
    $institution->setLabel('Recording institution: ')
		->setRequired(false)
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(NULL => 'Choose institution', 'Available institution' => $inst_options))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');

    $this->addElements(array(
    $old_findID,$description,
    $workflow,$rally,$rallyID,
    $hoard,$hoardID,$county,
    $regionID,$district,$parish,
    $fourFigure,$gridref,$denomination,
    $ruler,$mint,$axis,
    $reece,$reverse,$obverseinsc,
    $obversedesc,$reverseinsc,
    $reversedesc,$moneyer,$objecttype,
    $broadperiod, $submit, $hash,
    $institution));

    $this->addDisplayGroup(array(
    'denomination','ruler', 'mint',
    'moneyer','axis','reeceID',
    'revtypeID','obverseLegend','obverseDescription',
    'reverseLegend','reverseDescription'),
            'numismatics');


    $this->addDisplayGroup(array(
        'old_findID','description','rally',
        'rallyID','hoard','hID','workflow'),
            'details');

    $this->addDisplayGroup(array(
        'countyID','regionID','districtID',
        'parishID','gridref','fourFigure',
        'institution'),
    'spatial');

    $this->addDisplayGroup(array('submit'), 'buttons');
    $this->numismatics->setLegend('Numismatic details');
    $this->details->setLegend('Artefact details');
    $this->spatial->setLegend('Spatial details');

    parent::init();
    }
}