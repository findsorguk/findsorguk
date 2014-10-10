<?php
/** A form for searching the indexes specifically tailored for the retrieval of
 * the limited amount of Byzantine coins we have recorded.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new ByzantineNumismaticSearchForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Pas_Form
 * @example /app/modules/database/controllers/SearchController.php
 * @uses Institutions
 * @uses Rallies
 * @uses Hoards
 * @uses OsCounties
 * @uses Rulers
 * @uses Denominations
 * @uses Mints
 * @uses DieAxes
 * @uses OsRegions
 */
class ByzantineNumismaticSearchForm extends Pas_Form {
    
    /** An array of roles that grant higher level access
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin', 'flos', 'fa', 'heros', 'treasure');
	
    /** An array of roles with restricted access
     * @access protected
     * @var array
     */
    protected $_restricted = array(null, 'public', 'member', 'research');
    
    /** The constructor
     * @access public
     * @param type $options
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
    	$ruler_options = $rulers->getRulersByzantine();

    	$denominations = new Denominations();
	    $denomination_options = $denominations->getDenomsByzantine();

        $mints = new Mints();
	    $mint_options = $mints->getMintsByzantine();

        $axis = new DieAxes();
	    $axis_options = $axis->getAxes();

	    $regions = new OsRegions();
	    $region_options = $regions->getRegionsID();

	    parent::__construct($options);

	    $this->setName('byzantine-search');

	    $old_findID = new Zend_Form_Element_Text('old_findID');
	    $old_findID->setLabel('Find number: ')
                ->setRequired(false)
                ->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid number!')
		->setDisableTranslator(true);

        $description = new Zend_Form_Element_Text('description');
        $description->setLabel('Object description contains: ')
            ->setRequired(false)
                    ->addFilters(array('StripTags','StringTrim'))
            ->addValidator('NotEmpty')
            ->addErrorMessage('Please enter a valid term')
            ->setDisableTranslator(true);


        $workflow = new Zend_Form_Element_Select('workflow');
        $workflow->setLabel('Workflow stage: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'));
            if(in_array($this->_role,$this->_higherlevel)) {
                $workflow->addMultiOptions(
                        array(
                            null => 'Available Workflow stages',
                            'Choose Worklow stage' => array(
                                '1' => 'Quarantine',
                                '2' => 'On review',
                                '4' => 'Awaiting validation',
                                '3' => 'Published')));
            }
        if(in_array($this->_role,$this->_restricted)) {
                $workflow->addMultiOptions(array(null => 'Available Workflow stages',
                    'Choose Worklow stage' => array(
                        '4' => 'Awaiting validation',
                        '3' => 'Published')));
            }

        //Rally details
        $rally = new Zend_Form_Element_Checkbox('rally');
        $rally->setLabel('Rally find: ')
                    ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setUncheckedValue(null);

        $rallyID =  new Zend_Form_Element_Select('rallyID');
        $rallyID->setLabel('Found at this rally: ')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttribs(array(
                        'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->addMultiOptions(array(
                        null => 'Choose rally name',
                        'Available rallies' => $rally_options));


        $county = new Zend_Form_Element_Select('countyID');
        $county->setLabel('County: ')
            ->addFilters(array('StripTags','StringTrim'))
            ->addValidators(array('NotEmpty'))
            ->setAttribs(array(
                        'class' => 'input-xxlarge selectpicker show-menu-arrow'))
            ->addMultiOptions(array(
                        null => 'Choose county',
                        'Available counties' => $county_options
                    ));

        $district = new Zend_Form_Element_Select('districtID');
        $district->setLabel('District: ')
            ->addMultiOptions(array(null => 'Choose district after county'))
            ->setRegisterInArrayValidator(false)
            ->setAttribs(array(
                        'class' => 'input-xxlarge selectpicker show-menu-arrow'))
            ->disabled = true;

        $parish = new Zend_Form_Element_Select('parishID');
        $parish->setLabel('Parish: ')
            ->setRegisterInArrayValidator(false)
            ->addFilters(array('StripTags','StringTrim'))
            ->addMultiOptions(array(null => 'Choose parish after county'))
            ->setAttribs(array(
                        'class' => 'input-xxlarge selectpicker show-menu-arrow'))
            ->disabled = true;

        $regionID = new Zend_Form_Element_Select('regionID');
        $regionID->setLabel('European region: ')
            ->setRegisterInArrayValidator(false)
            ->addFilters(array('StripTags','StringTrim'))
            ->addMultiOptions(array(
                        null => 'Choose a region for a wide result',
                        'Choose region' => $region_options))
            ->setAttribs(array(
                        'class' => 'input-xxlarge selectpicker show-menu-arrow'));

        $gridref = new Zend_Form_Element_Text('gridref');
        $gridref->setLabel('Grid reference: ')
            ->addFilters(array('StripTags','StringTrim'))
            ->addValidators(array('NotEmpty','ValidGridRef'));

        $fourFigure = new Zend_Form_Element_Text('fourFigure');
        $fourFigure->setLabel('Four figure grid reference: ')
            ->addFilters(array('StripTags','StringTrim'))
            ->addValidators(array('NotEmpty','ValidGridRef'));
        ###
        ##Numismatic data
        ###
        //Denomination
        $denomination = new Zend_Form_Element_Select('denomination');
        $denomination->setLabel('Denomination: ')
            ->setRegisterInArrayValidator(false)
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttribs(array(
                        'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->addMultiOptions(array(
                        null => 'Choose denomination type',
                        'Available denominations' => $denomination_options));

        //Primary ruler
        $ruler = new Zend_Form_Element_Select('ruler');
        $ruler->setLabel('Ruler / issuer: ')
            ->setRegisterInArrayValidator(false)
            ->setAttribs(array(
                        'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->addFilters(array('StripTags','StringTrim'))
            ->addMultiOptions(array(
                        null => 'Choose primary ruler',
                        'Available rulers' => $ruler_options
                    ));

        //Mint
        $mint = new Zend_Form_Element_Select('mint');
        $mint->setLabel('Issuing mint: ')
            ->setAttribs(array(
                        'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setRegisterInArrayValidator(false)
            ->addFilters(array('StripTags','StringTrim'))
            ->addMultiOptions(array(
                        null => 'Choose denomination type',
                        'Available mints' => $mint_options));

        //Obverse inscription
        $obverseinsc = new Zend_Form_Element_Text('obverseLegend');
        $obverseinsc->setLabel('Obverse inscription contains: ')
            ->setAttrib('size',60)
            ->addFilters(array('StripTags','StringTrim'))
            ->addErrorMessage('Please enter a valid term');

        //Obverse description
        $obversedesc = new Zend_Form_Element_Text('obverseDescription');
        $obversedesc->setLabel('Obverse description contains: ')
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttrib('size',60)
            ->addErrorMessage('Please enter a valid term');

        //reverse inscription
        $reverseinsc = new Zend_Form_Element_Text('reverseLegend');
        $reverseinsc->setLabel('Reverse inscription contains: ')
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttrib('size',60)
            ->addErrorMessage('Please enter a valid term');

        //reverse description
        $reversedesc = new Zend_Form_Element_Text('reverseDescription');
        $reversedesc->setLabel('Reverse description contains: ')
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttrib('size',60)
            ->addErrorMessage('Please enter a valid term');

        //Die axis
        $axis = new Zend_Form_Element_Select('axis');
        $axis->setLabel('Die axis measurement: ')
            ->setRegisterInArrayValidator(false)
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttribs(array(
                        'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->addMultiOptions(array(
                        null => 'Choose measurement',
                        'Available axes' => $axis_options));

        $institution = new Zend_Form_Element_Select('institution');
        $institution->setLabel('Recording institution: ')
        ->setRequired(false)
        ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
        ->addFilters(array('StringTrim','StripTags'))
        ->addMultiOptions(array(
                null => 'Choose institution',
                'Choose institution' => $inst_options));

        $objecttype = new Zend_Form_Element_Hidden('objecttype');
        $objecttype->setValue('coin');
        $objecttype->addFilters(array('StripTags','StringTrim'));

        $broadperiod = new Zend_Form_Element_Hidden('broadperiod');
        $broadperiod->setValue('Byzantine')
            ->addFilters(array('StripTags','StringTrim','StringToUpper'));

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array(
                $old_findID, $description, $workflow,
                $rally, $rallyID, $county, $regionID,
                $district, $parish, $fourFigure,
                $gridref, $denomination, $ruler,
                $mint, $axis, $obverseinsc,
                $obversedesc, $reverseinsc, $reversedesc,
                $objecttype, $broadperiod, $institution,
                $submit
                    ));

        $this->addDisplayGroup(array(
                'denomination', 'ruler','mint',
                'moneyer', 'axis', 'obverseLegend',
                'obverseDescription','reverseLegend','reverseDescription'),
                'numismatics');

        $this->addDisplayGroup(array(
                'old_findID', 'description', 'rally',
                'rallyID', 'hoard', 'hID',
                'workflow'), 'details');
        $this->addDisplayGroup(array(
                'countyID','regionID','districtID',
                'parishID','gridref','fourFigure',
                'institution'), 'spatial');

        $this->numismatics->setLegend('Numismatic details');

        $this->details->setLegend('Artefact details');

        $this->spatial->setLegend('Spatial details');
        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}