<?php
/** Form for searching for early medieval coin data
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new EarlyMedNumismaticSearchForm();
 * $this->view->earlymedform = $form;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/SearchController.php
 * @uses Institutions
 * @uses Rallies
 * @uses OsCounties
 * @uses Rulers
 * @uses Denominations
 * @uses Mints
 * @uses DieAxes
 * @uses MedievalTypes
 * @uses CategoriesCoins
 * @uses OsRegions
 * 
 */
class EarlyMedNumismaticSearchForm extends Pas_Form {

    /** An array of higher level user roles
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin', 'flos', 'fa', 'heros', 'treasure');

    /** An array of restricted access roles
     * @access protected
     * @var array
     */
    protected $_restricted = array(null,'public', 'member', 'research');

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();

	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();

	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$rulers = new Rulers();
	$ruler_options = $rulers->getEarlyMedRulers();
	
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsEarlyMedieval();

	$mints = new Mints();
	$mint_options = $mints->getEarlyMedievalMints();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	$types = new MedievalTypes();
	$type_options = $types->getMedievalTypesForm(47);
	
	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodEarlyMed();

	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();

	parent::__construct($options);

	$this->setName('earlymedsearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid number!');


	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');
        $workflow = new Zend_Form_Element_Select('workflow');
        $workflow->setLabel('Workflow stage: ')
                ->setRequired(false)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addValidator('Int');

	if(in_array($this->_role,$this->_higherlevel)) {
            $workflow->addMultiOptions(array(
                null => 'Available Workflow stages',
                'Choose Worklow stage' => array(
                    '1' => 'Quarantine',
                    '2' => 'On review',
                    '4' => 'Awaiting validation',
                    '3' => 'Published')
                ));
	}
	if(in_array($this->_role,$this->_restricted)) {
            $workflow->addMultiOptions(array(
                null => 'Available Workflow stages',
                'Choose Worklow stage' => array(
                    '4' => 'Awaiting validation',
                    '3' => 'Published')
                ));
	}

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->setRequired(false)
		->addValidator('Int')
		->addFilters(array('StringTrim','StripTags'))
		->setUncheckedValue(null);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->setRequired(false)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(null => 'Choose a rally','Available rallies' => $rally_options));


	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
		->addValidators(array('NotEmpty'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(
	            null => 'Choose county',
	            'Available counties' => $county_options));

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
		->addMultiOptions(array(null => 'Choose district after county'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StringTrim','StripTags'))
		->setDisableTranslator(true);

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
		->setRegisterInArrayValidator(false)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(null => 'Choose parish after county'))
		->setDisableTranslator(true);

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setRegisterInArrayValidator(false)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('Int')
		->addMultiOptions(array(null => 'Choose a region for a wide result',
	            'Choose region' => $region_options));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addValidators(array('NotEmpty','ValidGridRef'))
		->addFilters(array('StringTrim','StripTags'));

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addValidators(array('NotEmpty'))
		->addFilters(array('StringTrim','StripTags'));


	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
                ->setRequired(false)
                ->addFilters(array('StripTags','StringTrim'))
                        ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addValidator('Int')
                ->addMultiOptions(array(
                    null => 'Choose denomination type',
                    'Available denominations' => $denomination_options));

	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
                ->setRequired(false)
                ->addValidator(('Int'))
                ->addFilters(array('StripTags','StringTrim'))
                        ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(
                    null => 'Choose an Early Medieval category',
                    'Available categories' => $cat_options));

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Coin type: ')
                ->setRequired(false)
                ->addValidator('Int')
		->setAttrib('class', 
                        'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose type after choosing ruler', 
                    'Available types' => $type_options))
		->addValidator('InArray', false, 
                        array(array_keys($type_options)));

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
                ->setRequired(false)
                ->addValidator('Int')
                ->addFilters(array('StripTags','StringTrim'))
                        ->setAttrib('class', 
                                'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(
                    null => 'Choose primary ruler',
                    'Available options' => $ruler_options))
                ->addValidator('InArray', false, 
                        array(array_keys($ruler_options)));

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
                ->setRequired(false)
                ->addValidator('Int')
                ->addFilters(array('StripTags','StringTrim'))
                        ->setAttrib('class', 
                                'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(
                    null => 'Choose issuing mint',
                    'Available mints' => $mint_options));

	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
		->addMultiOptions(array(
                    null => 'Choose a die axis measurement',
                    'Available options' => $axis_options));

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin')->addFilter('StringToUpper');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Early Medieval')->addFilter('StringToUpper');
	
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
                ->setRequired(false)
                ->addFilters(array('StringTrim','StripTags'))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addValidator('Alpha',true)
                ->addMultiOptions(array(
                    null => 'Choose an institution',
                    'Available institutions' => $inst_options));
    
        $hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array(
        $old_findID, $type, $description,
        $workflow, $rally, $rallyID,
        $county, $regionID, $district,
        $parish, $fourFigure, $gridref,
        $denomination, $ruler, $mint,
        $axis, $obverseinsc, $obversedesc,
        $reverseinsc, $reversedesc, $objecttype,
        $broadperiod, $cat, $submit,
        $institution, $hash));

	$this->addDisplayGroup(array(
            'category', 'ruler','type',
            'denomination', 'mint','moneyer',
            'axis', 'obverseLegend', 'obverseDescription',
            'reverseLegend','reverseDescription'),
                'numismatics');
	$this->numismatics->setLegend('Numismatic details: ');
	$this->addDisplayGroup(array(
            'old_findID', 'description', 'rally',
            'rallyID', 'workflow'),
                'details');

	$this->details->setLegend('Object details: ');
	$this->addDisplayGroup(array(
            'countyID','regionID','districtID',
            'parishID','gridref','fourFigure',
            'institution'), 'spatial');
	$this->spatial->setLegend('Spatial details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');
        
	parent::init();
    }
}