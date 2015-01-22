<?php
/** Form for searching for Post Medieval data
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new PostMedNumismaticSearchForm();
 * $this->view->earlymedform = $form;
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Materials
 * @uses Rallies
 * @uses OsCounties
 * @uses Rulers
 * @uses Denominations
 * @uses Mints
 * @uses DieAxes
 * @uses CategoriesCoins
 * @uses OsRegions
 * @uses Institutions
 * @uses MedievalTypes
 * @example /app/modules/database/controllers/SearchController.php
*/
class PostMedNumismaticSearchForm extends Pas_Form {

    /** The array of higher level roles
     * @access public
     * @var array
     */
    protected $_higherlevel = array('admin','flos','fa','heros','treasure','research');

    /** The array of restricted roles
     * @access public
     * @var array
     */
    protected $_restricted = array(null,'public','member');

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);
	//Get data to form select menu for primary and secondary material

	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();
	//Get data to form select menu for periods
	//Get Rally data

	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();


	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$rulers = new Rulers();
	$ruler_options = $rulers->getPostMedievalRulers();

	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsPostMedieval();

	$mints = new Mints();
	$mint_options = $mints->getPostMedievalMints();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodPostMed();

	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();

	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();
	
	$types = new MedievalTypes();
	$type_options = $types->getMedievalTypesForm(36);

	$this->setName('postmedsearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid number!');

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');
	if(in_array($this->_role,$this->_higherlevel)) {
	$workflow->addMultiOptions(array(
            null => 'Choose Worklow stage',
            'Available workflow stages' => array(
                '1'=> 'Quarantine',
		'2' => 'On review',
		'4' => 'Awaiting validation',
		'3' => 'Published')));
	}
	if(in_array($this->_role,$this->_restricted)) {
            $workflow->addMultiOptions(array(
                null => 'Choose Worklow stage',
                'Available workflow stages' => array(
                    '4' => 'Awaiting validation',
                    '3' => 'Published')));
	}

        //Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
                ->addFilters(array('StripTags','StringTrim'))
		->setUncheckedValue(null)
		->addValidators(array('Int'));

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose rally name',
                    'Available rallies' => $rally_options))
		->addValidator('InArray', false, array(array_keys($rally_options)));


	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose a county',
                    'Available counties' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)));

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose district after county'
                    ))
		->setRegisterInArrayValidator(false)
		->disabled = true;

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose parish after county'
                    ))
		->disabled = true;

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose a region for a wide result',
                    'Choose region' => $region_options));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty','ValidGridRef','Alnum'));

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty','ValidGridRef','Alnum'));
	###
	##Numismatic data
	###
	//Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose denomination type',
                    'Available denominations' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)));

	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($cat_options)))
		->addMultiOptions(array(
                    null => 'Choose category',
                    'Available categories' => $cat_options))
		->addFilters(array('StripTags','StringTrim'));

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Coin type: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose type after choosing ruler', 
                    'Available types' => $type_options
                ))
		->addValidator('InArray', false, array(array_keys($type_options)));

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null =>'Choose primary ruler',
                    'Available rulers' => $ruler_options))
		->addValidator('InArray', false, array(array_keys($ruler_options)));

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null =>'Choose active mint',
                    'Available mints' => $mint_options))
		->addValidator('InArray', false, array(array_keys($mint_options)));

	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose measurement',
                    'Available die axes' => $axis_options))
		->addValidator('InArray', false, array(array_keys($axis_options)));

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin')
		->addFilter('StringToUpper');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Post Medieval')
		->addFilters(array('StripTags','StringTrim', 'StringToUpper'))
		->addValidator('Alpha',false,array('allowWhiteSpace' => true));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(
                    null => 'Choose an institution',
                    'Available institutions' => $inst_options));

	$this->addElements(array(

        $old_findID, $type, $description,
        $workflow, $rally, $rallyID,
        $county, $regionID, $district,
        $parish, $fourFigure, $gridref,
        $denomination, $ruler, $mint,$axis,
        $obverseinsc, $obversedesc, $reverseinsc,
        $reversedesc, $objecttype, $broadperiod,
        $cat, $submit, $hash,
        $institution));

	$this->addDisplayGroup(array(
		'category','ruler','type',
		'denomination','mint','moneyer',
		'axis','obverseLegend','obverseDescription',
		'reverseLegend','reverseDescription')
	, 'numismatics');

	$this->addDisplayGroup(array(
		'old_findID','description','rally',
		'rallyID', 'workflow'),
	'details');

	$this->addDisplayGroup(array(
		'countyID','regionID','districtID',
		'parishID','gridref','fourFigure',
		'institution'),
	'spatial');

	$this->numismatics->setLegend('Numismatic details');

	$this->spatial->setLegend('Spatial details');

	$this->details->setLegend('Object specific details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
    }
}