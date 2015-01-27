<?php
/** Advanced search form for database
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Institutions
 * @uses Materials
 * @uses DiscoMethods
 * @uses Manufactures();
 * @uses Periods
 * @uses Cultures
 * @uses SurfTreatments
 * @uses DecStyles
 * @uses DecMethods
 * @uses Findofnotereasons
 * @uses Preservations
 * @uses Rallies
 * @uses OsCounties
 * @uses OsRegions();
*/
class AdvancedSearchForm extends Pas_Form {
    
    /** The array of higher level users
     * @var array
     * @access protected  
     */
    protected $_higherlevel = array('admin', 'flos', 'fa', 'heros', 'treasure');

    /** The restricted array
     * @access protected
     * @var array
     */
    protected $_restricted = array(null, 'public', 'member', 'research');

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();

	$discs = new DiscoMethods();
	$disc_options = $discs->getOptions();

	$mans = new Manufactures();
	$man_options = $mans->getOptions();

	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();

	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();

	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();

	$cultures = new Cultures();
	$culture_options = $cultures->getCultures();

	$surfaces = new SurfaceTreatments();
	$surface_options = $surfaces->getSurfaces();

	$decorations = new DecStyles();
	$decoration_options = $decorations->getStyles();

	$decmeths = new DecMethods();
	$decmeth_options = $decmeths->getDecmethods();

	$reasons = new Findofnotereasons();
	$reason_options = $reasons->getReasons();

	$preserves = new Preservations();
	$preserve_options = $preserves->getPreserves();

	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();

	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();

	$current_year = date('Y');
	$years = range(1850, $current_year);
	$years_list = array_combine($years,$years);

	parent::__construct($options);

	$this->setName('Advanced');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addValidator('StringLength', false, array(3,20))
                ->addErrorMessage('Please enter a valid number!');

	$objecttype = new Zend_Form_Element_Text('objecttype');
	$objecttype->setLabel('Object type: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addErrorMessage('Please enter a valid object type!');

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addErrorMessage('Please enter a valid term');

	//Find of note
	$findofnote = new Zend_Form_Element_Checkbox('note');
	$findofnote->setLabel('Find of Note: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->setUncheckedValue(null);

	//Reason for find of note
	$findofnotereason = new Zend_Form_Element_Select('reason');
	$findofnotereason->setLabel('Reason for noteworthy status: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose reason',
                    'Available reasons'  => $reason_options
                ))
                ->setAttribs(array(
                    'class' => 'input-xxlarge selectpicker show-menu-arrow'));

	//Institution
	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose institution',
                    'Available institutions' => $inst_options
                ))
                ->setAttribs(array(
                    'class' => 'input-medium selectpicker show-menu-arrow'));

	$notes = new Zend_Form_Element_Text('notes');
	$notes->setLabel('Notes: ')
                ->addFilters(array('StringTrim','StripTags'));

	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('Broad period: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose period from',
                    'Available periods' => $periodword_options
                ))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	$objdate1subperiod = new Zend_Form_Element_Select('fromsubperiod');
	$objdate1subperiod->setLabel('Sub period from: ')
                ->addMultiOptions(array(
                    null => 'Choose sub-period from',
                    'Available sub period from' => array('1' => 'Early',
                '2' => 'Middle','3' => 'Late')))
                ->addFilters(array('StringTrim','StripTags'))
                ->setOptions(array('separator' => ''))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));
	
	//Period from: Assigned via dropdown
	$objdate1period = new Zend_Form_Element_Select('periodFrom');
	$objdate1period->setLabel('Period from: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose period from' ,
                    'Available periods' => $period_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	$objdate2subperiod = new Zend_Form_Element_Select('tosubperiod');
	$objdate2subperiod->setLabel('Sub period to: ')
                ->addMultiOptions(array(
                    null => 'Choose sub-period from',
                    'Available subperiods' => array(
                        '1' => 'Early',
                        '2' => 'Middle',
                        '3' => 'Late')))
                ->setDisableTranslator(true)
                ->addFilters(array('StringTrim','StripTags'))
                ->setOptions(array('separator' => ''))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));
	
	//Period to: Assigned via dropdown
	$objdate2period = new Zend_Form_Element_Select('periodTo');
	$objdate2period->setLabel('Period to: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose period to',
                    'Available periods' => $period_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));
        $culture = new Zend_Form_Element_Select('culture');
	$culture->setLabel('Ascribed culture: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose ascribed culture',
                    'Available cultures' => $culture_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	$from = new Zend_Form_Element_Text('fromdate');
	$from->setLabel('Start date: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addValidators(array('NotEmpty','Int'))
                ->addErrorMessage('Please enter a valid date')
                ->setAttribs(array(
                    'placeholder' => 'Positive for AD, negative for BC'))
                ->setDescription('If you want to search for a date range, '
                        . 'enter a start date in this box and and end in the '
                        . 'box below. You do not need to add AD or BC');

	$to = new Zend_Form_Element_Text('todate');
	$to->setLabel('End date: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addValidators(array('NotEmpty','Int'))
                ->addErrorMessage('Please enter a valid date')
                ->setAttribs(array(
                    'placeholder' => 'Positive for AD, negative for BC'));

	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addValidator('Int')
                ->setAttribs(array(
                    'class' => 'input-medium selectpicker show-menu-arrow'));

	if(in_array($this->_role,$this->_higherlevel)) {
	$workflow->addMultiOptions(array(null => 'Available Workflow stages',
            'Choose Worklow stage' => array(
                '1' => 'Quarantine',
                '2' => 'On review',
                '4' => 'Awaiting validation',
                '3' => 'Published')));
	}
	if(in_array($this->_role,$this->_restricted)) {
            $workflow->addMultiOptions(array(
                null => 'Available Workflow stages',
                'Choose Worklow stage' => array(
                    '4' => 'Awaiting validation',
                    '3' => 'Published')
                ));
	}

	$treasure = new Zend_Form_Element_Checkbox('treasure');
	$treasure->setLabel('Treasure find: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->setUncheckedValue(null);

	$treasureID =  new Zend_Form_Element_Text('TID');
	$treasureID->setLabel('Treasure ID number: ')
                ->addFilters(array('StringTrim','StripTags'));

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
                ->addValidator('Int')
                ->addFilters(array('StringTrim','StripTags'))
                ->setUncheckedValue(null);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose a rally',
                    'Available rallies' => $rally_options
                ))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));


	$other_ref = new Zend_Form_Element_Text('otherRef');
	$other_ref->setLabel('Other reference: ')
                ->addFilters(array('StringTrim','StripTags'));
	
	$smrRef = new Zend_Form_Element_Text('smrRef');
	$smrRef->setLabel('SMR reference: ')
                ->addFilters(array('StringTrim','StripTags'));
		
	//Manufacture method
	$manmethod = new Zend_Form_Element_Select('manufacture');
	$manmethod->setLabel('Manufacture method: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addValidator('Int')
                ->addMultiOptions(array(
                    null => 'Choose method of manufacture',
                    'Available methods' => $man_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));
            
	//Decoration method
	$decmethod = new Zend_Form_Element_Select('decoration');
	$decmethod->setLabel('Decoration method: ')
                ->addValidator('Int')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose decoration method',
                    'Available decorative methods' => $decmeth_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	//Surface treatment
	$surftreat = new Zend_Form_Element_Select('surface');
	$surftreat->setLabel('Surface Treatment: ')
                ->addValidator('Int')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose surface treatment',
                    'Available surface treatments' => $surface_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	//decoration style
	$decstyle = new Zend_Form_Element_Select('decstyle');
	$decstyle->setLabel('Decorative style: ')
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose decorative style',
                    'Available decorative options' => $decoration_options))
                ->addValidator('Int')
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	//Preservation of object
	$preservation = new Zend_Form_Element_Select('preservation');
	$preservation->setLabel('Preservation: ')
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
                ->addMultiOptions(array(
                    null => 'Choose level of preservation',
                    'Available options' => $preserve_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
                ->addValidators(array('NotEmpty'))
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(
                    null => 'Choose county',
                    'Available counties' => $county_options))
                ->setAttribs(array(
                    'class' => 'input-xxlarge selectpicker show-menu-arrow'));
            

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
                ->addMultiOptions(array(null => 'Choose district after county'))
                ->setRegisterInArrayValidator(false)
                ->addFilters(array('StringTrim','StripTags'))
                ->setAttribs(array(
                    'class' => 'input-xxlarge selectpicker show-menu-arrow'));
	
	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
                ->setRegisterInArrayValidator(false)
                ->addFilters(array('StringTrim','StripTags'))
                ->addMultiOptions(array(null => 'Choose parish after county'))
            ->setAttribs(array(
                'class' => 'input-xxlarge selectpicker show-menu-arrow'));

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
                ->setRegisterInArrayValidator(false)
                ->addValidator('Int')
                ->addMultiOptions(array(null => 'Choose a region for a wide result',
                    'Choose region' => $region_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
                ->addValidators(array('NotEmpty','ValidGridRef'))
                ->addFilters(array('StringTrim','StripTags'));

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
                ->addValidators(array('NotEmpty'))
                ->addFilters(array('StringTrim','StripTags'));

	$idBy = new Zend_Form_Element_Text('idBy');
	$idBy->setLabel('Primary identifier: ')
                ->addValidators(array('NotEmpty'))
                ->addFilters(array('StringTrim','StripTags'));

	$identifierID = new Zend_Form_Element_Hidden('identifierID');
	$identifierID->addFilters(array('StringTrim','StripTags'));


	$created = new Zend_Form_Element_Text('createdBefore');
        $created->setLabel('Date record created on or before: ')
                ->addFilters(array('StringTrim','StripTags'));

	$created2 = new Zend_Form_Element_Text('createdAfter');
	$created2->setLabel('Date record created on or after: ')
                ->addFilters(array('StringTrim','StripTags'));

	$updated = new Zend_Form_Element_Text('updatedBefore');
	$updated->setLabel('Date record updated on or before: ')
                ->addFilters(array('StringTrim','StripTags'));

	$updated2 = new Zend_Form_Element_Text('updatedAfter');
	$updated2->setLabel('Date record updated on or after: ')
                ->addFilters(array('StringTrim','StripTags'));
	
	$finder = new Zend_Form_Element_Text('finder');
	$finder->setLabel('Found by: ')->addFilters(array('StringTrim','StripTags'));

	$finderID = new Zend_Form_Element_Hidden('finderID');
	$finderID->addFilters(array('StringTrim','StripTags'));

	$recordby = new Zend_Form_Element_Text('recordername');
	$recordby->setLabel('Recorded by: ')
                ->addValidators(array('NotEmpty'))
                ->addFilters(array('StringTrim','StripTags'))
                ->setAttrib('autoComplete', 'true');

	$recorderID = new Zend_Form_Element_Hidden('recorderID');
	$recorderID->addFilters(array('StringTrim','StripTags'));

	$discoverydate = new Zend_Form_Element_Select('discovered');
	$discoverydate->setLabel('Year of discovery')
                ->setMultiOptions(array(
                    null => 'Choose a year of discovery',
                    'Date range' => $years_list))
                ->addValidator('Int')
                ->addFilters(array('StringTrim','StripTags'))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Submit your search');

	$material1 = new Zend_Form_Element_Select('material');
	$material1->setLabel('Primary material: ')
                ->addFilters(array('StripTags','StringTrim'))
                ->addMultiOptions(array(
                    null => 'Choose primary material',
                    'Available options' => $primary_options))
                ->setAttribs(array(
                    'class' => 'input-xlarge selectpicker show-menu-arrow'));
            
	$woeid = new Zend_Form_Element_Text('woeid');
	$woeid->setLabel('Where on earth ID: ')
                ->addValidator('Int')
                ->addFilters(array('StripTags','StringTrim'));

	$elevation  = new Zend_Form_Element_Text('elevation');
	$elevation->setLabel('Elevation: ')
                ->addValidator('Int')
                ->addFilters(array('StripTags','StringTrim'));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	if(in_array($this->_role,$this->_restricted)) {
            $this->addElements(array(
                $old_findID, $objecttype, $broadperiod,
                $description, $from, $to,
                $workflow, $findofnote, $findofnotereason,
                $rally, $rallyID, $other_ref,
                $manmethod, $notes, $objdate1period,
                $objdate2period,  $county, $regionID,
                $district, $parish, $fourFigure,
                $objdate1subperiod, $objdate2subperiod, $treasure,
                $treasureID, $discoverydate, $created,
                $created2, $updated, $updated2,
                $culture, $surftreat, $submit,
                $material1, $elevation, $woeid,
                $institution, $hash, $smrRef
                ));
        } else {
            $this->addElements(array(
                $old_findID, $objecttype, $broadperiod,
                $description, $from, $to,
                $workflow, $findofnote, $findofnotereason,
                $rally, $rallyID, $other_ref,
                $manmethod, $notes, $objdate1period,
                $objdate2period, $county, $regionID,
                $district, $parish, $fourFigure, $elevation, $woeid,
                $objdate1subperiod, $objdate2subperiod, $treasure,
                $treasureID, $discoverydate, $created,
                $created2, $updated, $updated2, $idBy, $finder,
                $finderID, $recordby, $recorderID,
                $identifierID, $culture, $surftreat,
                $submit, $material1, $institution, 
                $smrRef, $hash
                ));
	}

	$this->addDisplayGroup(array(
            'old_findID', 'objecttype', 'description',
            'notes', 'note', 'reason',
            'treasure', 'TID', 'rally',
            'rallyID', 'workflow', 'otherRef',
            'smrRef', 'material', 'manufacture',
            'surface'),
            'details');
	$this->details->setLegend('Main details: ');

	$this->addDisplayGroup(array(
            'broadperiod', 'fromsubperiod', 'periodFrom',
            'tosubperiod', 'periodTo', 'culture',
            'fromdate', 'todate')
                , 'Temporaldetails');
	$this->Temporaldetails->setLegend('Dates and periods: ');

	$this->addDisplayGroup(array(
            'countyID', 'regionID', 'districtID',
            'parishID', 'fourFigure', 'elevation',
            'woeid'), 'Spatial');
	$this->Spatial->setLegend('Spatial details: ');
	if(in_array($this->_role,$this->_restricted)) {
            $this->addDisplayGroup(array(
                'institution', 'createdAfter', 'createdBefore',
                'updatedAfter', 'updatedBefore', 'discovered'), 'Discovery');
            } else {
                $this->addDisplayGroup(array(
                'institution', 'finder', 'idBy', 
                'identifierID', 'recordername', 'recorderID', 
                'createdAfter', 'createdBefore','updatedAfter', 
                'updatedBefore', 'discovered'), 'Discovery');
            }

	$this->Discovery->setLegend('Discovery details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}