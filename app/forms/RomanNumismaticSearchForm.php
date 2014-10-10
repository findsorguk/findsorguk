<?php
/** Form for searching for Roman numismatics
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new RomanNumismaticSearchForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/SearchController.php
 */
class RomanNumismaticSearchForm extends Pas_Form {

    /** The protected array of higher level roles
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin','flos','fa','heros');

    /** The restricted array of roles
     *
     * @var type 
     */
    protected $_restricted = array(null, 'public','member','research');


    public function __construct(array $options = null) {
        
        //Get Rally data
        $rallies = new Rallies();
        $rally_options = $rallies->getRallies();

        $counties = new OsCounties();
        $county_options = $counties->getCountiesID();

        $denominations = new Denominations();
        $denom_options = $denominations->getOptionsRoman();

        $rulers = new Rulers();
        $ruler_options = $rulers->getRomanRulers();

        $mints = new Mints();
        $mint_options = $mints->getRomanMints();

        $axes = new DieAxes();
        $axis_options = $axes->getAxes();

        $reeces = new Reeces();
        $reece_options = $reeces->getReeces();

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
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');
        if(in_array($this->_role,$this->_higherlevel)) {
            $workflow->addMultiOptions(array(
                null => 'Choose workflow',
                'Available worklow stage' => array(
                    '1'=> 'Quarantine',
                    '2' => 'On review',
                    '4' => 'Awaiting validation',
                    '3' => 'Published')
                ));
        }
        if(in_array($this->_role,$this->_restricted)) {
            $workflow->addMultiOptions(array(
                null => 'Choose a workflow stage',
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
                ->setUncheckedValue(null);

        $rallyID =  new Zend_Form_Element_Select('rallyID');
        $rallyID->setLabel('Found at this rally: ')
                    ->addFilters(array('StripTags', 'StringTrim'))
                    ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                    ->addMultiOptions(array( 
                        null => 'Choose rally name',
                        'Available rallies' => $rally_options
                ))
                    ->addValidator('InArray', false, array(array_keys($rally_options)));


        $county = new Zend_Form_Element_Select('countyID');
        $county->setLabel('County: ')
                ->addValidators(array('NotEmpty'))
                ->addMultiOptions(array( 
                    null => 'Choose county first', 
                    'Available counties' => $county_options
                ))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addValidator('InArray', false, array(array_keys($county_options)));

        $district = new Zend_Form_Element_Select('districtID');
        $district->setLabel('District: ')
                ->addMultiOptions(array(null => 'Choose district after county'))
                ->setRegisterInArrayValidator(false)
                ->addValidator('District')
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->disabled = true;

        $parish = new Zend_Form_Element_Select('parishID');
        $parish->setLabel('Parish: ')
                ->addMultiOptions(array(null => 'Choose parish after county'))
                ->setRegisterInArrayValidator(false)
                ->addValidator('Parish')
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->disabled = true;

        $regionID = new Zend_Form_Element_Select('regionID');
        $regionID->setLabel('European region: ')
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(
                    null => 'Choose a region for a wide result',
                    'Choose region' => $region_options
                ))
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
                ->addMultiOptions(array(
                    null => 'Choose denomination type',
                    'Available denominations' => $denom_options))
                ->addValidator('InArray', false, array(array_keys($denom_options)))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');

        //Primary ruler
        $ruler = new Zend_Form_Element_Select('ruler');
        $ruler->setLabel('Ruler / issuer: ')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addMultiOptions(array(
                    null => 'Choose primary ruler',
                    'Available rulers'=> $ruler_options
                ))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addValidator('InArray', false, array(array_keys($ruler_options)));

                    //Mint
        $mint = new Zend_Form_Element_Select('mint');
        $mint->setLabel('Issuing mint: ')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addMultiOptions(array(
                    null => 'Choose issuing mint', 
                    'Available mints' => $mint_options
                ))
                ->addValidator('InArray', false, array(array_keys($mint_options)))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');

        //Reece
        $reece = new Zend_Form_Element_Select('reeceID');
        $reece->setLabel('Reece period: ')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addMultiOptions(array(
                    null => 'Choose Reece period', 
                    'Available Reece periods' => $reece_options
                ))
                ->addValidator('InArray', false, array(array_keys($reece_options)))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');

        //Reverse type
        $reverse = new Zend_Form_Element_Select('revtypeID');
        $reverse->setLabel('Fourth Century reverse type: ')
                ->setDescription('This field is only applicable for fourth century AD coins.')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addMultiOptions(array(null => 'Only available after choosing a 4th century issuer'))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');

        $moneyer = new Zend_Form_Element_Select('moneyer');
        $moneyer->setLabel('Republican moneyers: ')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addMultiOptions(array(null => 'Only available after choosing a Republican issuer'))
                ->setDescription('This field is only applicable for Republican coins.')
                ->addValidator('InArray', false, array(array_keys($money)))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');
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
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addMultiOptions(array(
                    null => 'Choose die axis',
                    'Available axes' => $axis_options
                ))
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
                ->addMultiOptions(array(
                    null => 'Choose institution', 
                    'Available institution' => $inst_options
                ))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');

        $this->addElements(array(
            $old_findID, $description, $workflow,
            $rally, $rallyID, $county,
            $regionID, $district, $parish,
            $fourFigure, $gridref, $denomination,
            $ruler, $mint, $axis,
            $reece, $reverse, $obverseinsc,
            $obversedesc, $reverseinsc, $reversedesc,
            $moneyer, $objecttype, $broadperiod,
            $submit, $hash, $institution));

        $this->addDisplayGroup(array(
            'denomination','ruler', 'mint',
            'moneyer','axis','reeceID',
            'revtypeID','obverseLegend','obverseDescription',
            'reverseLegend','reverseDescription'),
                    'numismatics');


        $this->addDisplayGroup(array(
            'old_findID','description','rally',
            'rallyID','workflow'),
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