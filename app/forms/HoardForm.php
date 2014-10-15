<?php
/** Form for adding and editing hoard information
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new HoardForm();
 * $this->view->form = $form;
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @since  15 August 2014
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/HoardsController.php
 * @uses DiscoMethods
 * @uses Materials
 * @uses Periods
 * @uses FindOfNoteReasons
 * @uses Rallies
 * @uses Periods
 * @uses SubsequentActions
 */

class HoardForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

        ## GET OPTIONS TO POPULATE MENUS ##
        //Get periods for select menu
        $periods = new Periods();
        $period_options = $periods->getPeriodFrom();
        $periodword_options = $periods->getPeriodFromWords();

        //Get terminal reasons for select menu
        $terminalreasons = new TerminalReasons();
        $terminalreason_options = $terminalreasons->getReasons();

        //Get coin data quality ratings for select menu
        $qualityrating = new DataQuality();
        $qualityrating_options = $qualityrating->getRatings();

        //Get Find of note reason options for select menu
        $reasons = new FindOfNoteReasons();
        $reason_options = $reasons->getReasons();

        //Get primary materials for multiselect
        $primarymaterials = new Materials();
        $materials_options = $primarymaterials->getPrimaries();

        //Get discovery methods for select menu
        $discs = new DiscoMethods();
        $disc_options = $discs->getOptions();

        //Get Rally data for select menu
        $rallies = new Rallies();
        $rally_options = $rallies->getRallies();

        //Get Subsequent actions for select menu
        $actions = new SubsequentActions();
        $actionsDD = $actions->getSubActionsDD();

        //Get the reece periods for inclusion
        $reece = new Reeces();
        $reeces = $reece->getReeces();
        
        //End of select options construction
        $this->addElementPrefixPath('Pas_Filter', 'Pas/Filter/', 'filter');

        parent::__construct($options);

        $this->setName('hoards');

        ## UNIQUE ID FIELDS ##
        $secuid = new Zend_Form_Element_Hidden('secuid');
        $secuid->addFilters(array('StripTags','StringTrim'))->addValidator('Alnum');

        $old_hoardID = new Zend_Form_Element_Hidden('hoardID');
        $old_hoardID->addFilters(array('StripTags','StringTrim'));

        ## HOARD DATING ##
        //Broadperiod:
        $broadperiod = new Zend_Form_Element_Select('broadperiod');
        $broadperiod->setLabel('Broad period: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->addMultiOptions(array(
                null => 'Choose broadperiod' ,
                'Available periods' => $periodword_options
            ))
            ->addErrorMessage('You must enter a broad period.')
            ->addValidator('InArray', false, array(array_keys($periodword_options)))
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(1);

        //Sub period from: Assigned via dropdown
        $hoardsubperiod1 = new Zend_Form_Element_Select('subperiod1');
        $hoardsubperiod1->setLabel('Sub period from: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(null => 'Choose a subperiod' ,
                'Valid sub periods' => array('1' => 'Early','2' => 'Middle', '3' => 'Late')))
            ->setAttribs(array('class' => 'selectpicker show-menu-arrow'))
            ->setOrder(2);

        //Period from: Assigned via dropdown
        $hoardperiod1 = new Zend_Form_Element_Select('period1');
        $hoardperiod1->setLabel('Period from: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(null => 'Choose a period from' ,
                'Available periods' => $period_options))
            ->addValidator('InArray', false, array(array_keys($period_options)))
            ->addValidator('Int')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(3);

        //Sub period to: Assigned via dropdown
        $hoardsubperiod2 = new Zend_Form_Element_Select('subperiod2');
        $hoardsubperiod2->setLabel('Sub period to: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(null => 'Choose a subperiod' ,
                'Valid sub periods' => array('1' => 'Early','2' => 'Middle', '3' => 'Late')))
            ->addValidator('Digits')
            ->setAttribs(array('class' => 'selectpicker show-menu-arrow'))
            ->setOrder(4);

        //Period to: Assigned via dropdown
        $hoardperiod2 = new Zend_Form_Element_Select('period2');
        $hoardperiod2->setLabel('Period to: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(null => 'Choose period to',
                'Available periods' => $period_options))
            ->addValidator('InArray', false, array(array_keys($period_options)))
            ->addValidator('Int')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(5);

        //Date from: Free text Integer +ve or -ve
        $numdate1 = new Zend_Form_Element_Text('numdate1');
        $numdate1->setLabel('Date from: ')
            ->setAttrib('size',10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addValidator('Int')
            ->setOrder(6);

        //Date to: Free text Integer +ve or -ve
        $numdate2 = new Zend_Form_Element_Text('numdate2');
        $numdate2->setLabel('Date to: ')
            ->setAttrib('size',10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addValidator('Int')
            ->setOrder(7);

        ## COIN DATING ##
        //Ruler of latest coins in hoard:
        $lastruler = new Zend_Form_Element_Select('lastrulerID');
        $lastruler->setLabel('Last ruler: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(
                null => 'Choose ruler after broad period'
            ))
            ->setRegisterInArrayValidator(false)
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(8);

        //Ruler of latest coins in hoard:
        $lastreeceperiod = new Zend_Form_Element_Select('reeceID');
        $lastreeceperiod->setLabel('Last Reece period: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(
                null => 'Choose Reece period', 'Available periods' => $reeces
            ))
            ->setRegisterInArrayValidator(false)
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(9);

        //Date from: Free text Integer +ve or -ve
        $terminaldate1 = new Zend_Form_Element_Text('terminalyear1');
        $terminaldate1->setLabel('Terminal date from: ')
            ->setAttrib('size',10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addValidator('Int')
            ->setOrder(10);

        //Date to: Free text Integer +ve or -ve
        $terminaldate2 = new Zend_Form_Element_Text('terminalyear2');
        $terminaldate2->setLabel('Terminal date to: ')
            ->setAttrib('size',10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addValidator('Int')
            ->setOrder(11);

        //Reason for terminal coin dating
        $terminalreason = new Zend_Form_Element_Select('terminalreason');
        $terminalreason->setLabel('Terminal reason: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(
                null => 'Choose a reasoning',
                'Available reasons' => $terminalreason_options))
            ->addValidator('InArray', false, array(array_keys($terminalreason_options)))
            ->setAttrib('class', 'input-xlarge selectpicker show-menu-arrow')
            ->addValidator('Int')
            ->setOrder(12);

        ## HOARD DETAILS ##
        //Hoard description
        $description = new Pas_Form_Element_CKEditor('description');
        $description->setLabel('Hoard description: ')
            ->setRequired(false)
            ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
            ->setOrder(13);

        //Object notes
        $notes = new Pas_Form_Element_CKEditor('notes');
        $notes->setLabel('Notes: ')
            ->setRequired(false)
            ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
            ->setOrder(14);

        //Coin data quality rating
        $coindataquality = new Zend_Form_Element_Select('qualityrating');
        $coindataquality->setLabel('Coin data quality rating: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(
                null => 'Choose a rating',
                'Available ratings' => $qualityrating_options))
            ->addValidator('InArray', false, array(array_keys($qualityrating_options)))
            ->setAttrib('class', 'input-large selectpicker show-menu-arrow')
            ->setDescription('The quality field can only be completed by project staff')
            ->addValidator('Int')
            ->setOrder(15);

        //Find of note
        $findofnote = new Zend_Form_Element_Checkbox('findofnote');
        $findofnote->setLabel('Find of Note: ')
            ->setRequired(false)
            ->setCheckedValue('1')
            ->setUncheckedValue(null)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addValidator('NotEmpty','Int')
            ->setOrder(16);

        //Reason for find of note
        $findofnotereason = new Zend_Form_Element_Select('findofnotereason');
        $findofnotereason->setLabel('Why this find is considered noteworthy: ')
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(
                null => 'Choose a reasoning',
                'Available reasons' => $reason_options))
            ->addValidator('InArray', false, array(array_keys($reason_options)))
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addValidator('Int')
            ->setOrder(17);

        //Treasure: enumerator 1/0
        $treasure = new Zend_Form_Element_Checkbox('treasure');
        $treasure->setLabel('Treasure: ')
            ->setRequired(false)
            ->setCheckedValue('1')
            ->setUncheckedValue(null)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(18);

        //Treasure ID
        $treasureID = new Zend_Form_Element_Text('treasureID');
        $treasureID->setLabel('Treasure number: ')
            ->setRequired(false)
            ->setAttribs(array('placeholder' => 'T numbers are in the format of YYYYT1234', 'class' => 'span6'))
            ->addValidator('Alnum', false, array('allowWhiteSpace' => false))
            ->addFilters(array('StripTags','StringTrim', 'StringToUpper'))
            ->setOrder(19);

        ## QUANTITIES ##
        $quantityCoins = new Zend_Form_Element_Text('quantityCoins');
        $quantityCoins->setLabel('Quantity of coins: ')
            ->addValidator('Int')
            ->setOrder(20);

        $quantityArtefacts = new Zend_Form_Element_Text('quantityArtefacts');
        $quantityArtefacts->setLabel('Quantity of artefacts: ')
            ->addValidator('Int')
            ->setOrder(21);

        $quantityContainers = new Zend_Form_Element_Text('quantityContainers');
        $quantityContainers->setLabel('Quantity of containers: ')
            ->addValidator('Int')
            ->setOrder(22);

        ## MATERIALS ##
        //Materials
        $materials = new Zend_Form_Element_Multiselect('materials');
        $materials->setLabel('Primary materials: ')
            ->addMultiOptions($materials_options)
            ->setAttrib('class', 'multiselect')
            ->setDescription('Primary materials of coins and artefacts in the hoard')
            ->addFilters(array('Null'))
            ->setOrder(23);

        ## RECORDING DETAILS ##
        //Recorder
        $recorderID = new Zend_Form_Element_Hidden('recorderID');
        $recorderID->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(24);

        $recordername = new Zend_Form_Element_Text('recordername');
        $recordername->setLabel('Recorded by: ')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(25);

        //Primary Identifier
        $idBy = new Zend_Form_Element_Text('idBy');
        $idBy->setLabel('Primary identifier: ')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(26);

        $identifier1ID = new Zend_Form_Element_Hidden('identifier1ID');
        $identifier1ID->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(27);

        $id2by = new Zend_Form_Element_Text('id2by');
        $id2by->setLabel('Secondary Identifier: ')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(28);

        //Secondary Identifier
        $identifier2ID = new Zend_Form_Element_Hidden('identifier2ID');
        $identifier2ID->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(29);

        ## DISCOVERER DETAILS ##
        //Finder
        $finder1ID = new Zend_Form_Element_Hidden('finder1ID');
        $finder1ID->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(30);

        $hiddenfield = new Zend_Form_Element_Hidden('hiddenfield');
        $hiddenfield->setValue(2)
            ->setOrder(31);

        $finder1 = new Zend_Form_Element_Text('finder1');
        $finder1->setLabel('Found by: ')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setDescription('To make a new finder/identifier appear, you '
                . 'first need to create them from the people menu on '
                . 'the left hand side')
            ->setOrder(32);

        $addFinderButton = new Zend_Form_Element_Button('addFinder');
        $addFinderButton->setLabel('Add Additional Finder')
            ->setAttribs(array('class' => 'btn btn-info'));
        $addFinderButton->setOrder(50);

        $removeFinderButton = new Zend_Form_Element_Button('removeFinder');
        $removeFinderButton->setLabel('Remove Last Finder')
            ->setAttribs(array('class' => 'btn btn-warning hidden'));
        $removeFinderButton->setOrder(51);

        ## DISCOVERY INFORMATION ##
        //Discovery method
        $discmethod = new Zend_Form_Element_Select('discmethod');
        $discmethod->setLabel('Discovery method: ')
            ->setRequired(true)
            ->setValue(1)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addValidator('Int')
            ->addValidator('inArray', true, array(array_keys($disc_options)))
            ->addMultiOptions(array(null => 'Choose method of discovery','Available methods' => $disc_options))
            ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'))
            ->setOrder(52);

        //Discovery circumstances
        $disccircum = new Zend_Form_Element_Text('disccircum');
        $disccircum->setLabel('Discovery circumstances: ')
            ->setAttrib('size',50)
            ->setAttrib('class' , 'span6')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(53);

        //Date found from
        $datefound1 = new Zend_Form_Element_Text('datefound1');
        $datefound1->setLabel('First discovery date: ')
            ->setAttrib('size',10)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(54);

        //Date found to
        $datefound2 = new Zend_Form_Element_Text('datefound2');
        $datefound2->setLabel('Second discovery date: ')
            ->setAttrib('size',10)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(55);

        //Rally details
        $rally = new Zend_Form_Element_Checkbox('rally');
        $rally->setLabel('Rally find: ')
            ->setCheckedValue('1')
            ->setUncheckedValue(null)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addValidator('Int')
            ->setOrder(56);

        $rallyID =  new Zend_Form_Element_Select('rallyID');
        $rallyID->setLabel('Found at this rally: ')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->addMultiOptions(array(null => 'Choose rally name',
                'Available rallies' => $rally_options))
            ->addValidator('InArray', false, array(array_keys($rally_options)))
            ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'))
            ->addValidator('Int')
            ->setOrder(57);

        ## OTHER REFERENCE NUMBERS ##
        //Legacy hoard ID
        $legacy_ref = new Zend_Form_Element_Text('legacyID');
        $legacy_ref->setLabel('Legacy hoard ID: ')
            ->setAttrib('size',5)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(58)
            ->disabled = true;

        //Other reference number
        $other_ref = new Zend_Form_Element_Text('other_ref');
        $other_ref->setLabel('Other reference: ')
            ->setAttrib('size',50)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(59);

        //HER reference number
        $smrrefno = new Zend_Form_Element_Text('smrrefno');
        $smrrefno->setLabel('Historic Environment Record number: ')
            ->setAttrib('size',30)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(60);

        //Museum accession number
        $musaccno = new Zend_Form_Element_Text('musaccno');
        $musaccno->setLabel('Museum accession number: ')
            ->setAttrib('size',50)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(61);

        //Current location of object
        $curr_loc = new Zend_Form_Element_Text('curr_loc');
        $curr_loc->setLabel('Current location: ')
            ->setAttrib('class','span6')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setOrder(62);

        //Current location of object
        $subs_action = new Zend_Form_Element_Select('subs_action');
        $subs_action->setLabel('Subsequent action: ')
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setAttrib('class','span6')
            ->addMultiOptions(array(null => 'Choose a subsequent action',
                'Available options' => $actionsDD))
            ->setValue(1)
            ->addValidator('InArray', false, array(array_keys($actionsDD)))
            ->addValidator('Int')
            ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'))
            ->setOrder(63);

        ## Quantities ##
        $quantityCoins = new Zend_Form_Element_Text('quantityCoins');
        $quantityCoins->setLabel('Quantity of coins: ')
            ->addValidator('Int')
            ->setValue(null);

        $quantityArtefacts = new Zend_Form_Element_Text('quantityArtefacts');
        $quantityArtefacts->setLabel('Quantity of artefacts: ')
            ->addValidator('Int')
            ->setValue(null);

        $quantityContainers = new Zend_Form_Element_Text('quantityContainers');
        $quantityContainers->setLabel('Quantity of containers: ')
            ->addValidator('Int')
            ->setValue(null);

        ## SUBMIT BUTTON ##
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setOrder(67);

        $this->addElements(array(
            $secuid, $old_hoardID, $broadperiod,
            $hoardperiod1, $hoardperiod2, $hoardsubperiod1,
            $hoardsubperiod2, $numdate1, $numdate2,
            $lastruler, $lastreeceperiod, $terminaldate1,
            $terminaldate2, $terminalreason, $description,
            $notes, $coindataquality, $findofnote,
            $findofnotereason, $treasure, $treasureID,
            $quantityArtefacts, $quantityCoins, $quantityContainers,
            $materials, $recorderID, $recordername,
            $idBy, $id2by, $identifier1ID,
            $identifier2ID, $finder1, $finder1ID,
            $hiddenfield, $addFinderButton, $removeFinderButton,
            $discmethod, $disccircum, $datefound1,
            $datefound2, $rally, $rallyID,
            $legacy_ref, $other_ref, $smrrefno,
            $musaccno, $curr_loc, $subs_action,
            $submit
            ));

        $this->addDisplayGroup(array(
            'broadperiod', 'subperiod1', 'period1',
            'subperiod2', 'period2',
            'numdate1', 'numdate2'),
            'hoarddating');
        $this->hoarddating->setLegend('Hoard dating');

        $this->addDisplayGroup(array(
            'lastrulerID', 'reeceID',
            'terminalyear1', 'terminalyear2',
            'terminalreason'),
            'coindating');
        $this->coindating->setLegend('Coin dating');

        $this->addDisplayGroup(array(
            'description', 'notes', 'qualityrating', 'findofnote', 'findofnotereason',
            'treasure', 'treasureID'),
            'hoarddetails');
        $this->hoarddetails->setLegend('Hoard details');

        $this->addDisplayGroup(array('quantityCoins', 'quantityArtefacts', 'quantityContainers'), 'quantities');
        $this->quantities->setLegend('Quantities');

        $this->addDisplayGroup(array(
            'materials'),
            'primarymaterials');
        $this->primarymaterials->setLegend('Materials');

        $this->addDisplayGroup(array(
            'recordername','recorderID','idBy',
            'identifier1ID','id2by','identifier2ID'), 'recorders');
        $this->recorders->setLegend('Recording details');

        $this->addDisplayGroup(array('finder1','finder1ID', 'hiddenfield', 'addFinder', 'removeFinder'
            ), 'discoverers');
        $this->discoverers->setLegend('Discoverer details');

        $this->addDisplayGroup(array('disccircum','discmethod','datefound1',
            'datefound2','rally','rallyID'), 'discovery');
        $this->discovery->setLegend('Discovery details');

        $this->addDisplayGroup(array('legacyID', 'other_ref','smrrefno','musaccno','curr_loc',
            'subs_action'), 'references');
        $this->references->setLegend('Reference numbers');

        $this->addDisplayGroup(array(
            'submit'),
            'buttons');

        parent::init();

        $addFinderButton->removeDecorator('Label');
        $addFinderButtonDecorator = $addFinderButton->getDecorator('HtmlTag');
        $addFinderButtonDecorator->setOption('id', 'addFinderDiv');
        $removeFinderButton->removeDecorator('Label');

        $person = new Pas_User_Details();
        $role = $person->getRole();
        $projectTeam = array('hoard', 'admin');
        if(!in_array($role, $projectTeam)){
            $coindataquality->disabled=true;
        }
    }

    /**
     * Adds new text fields to form during form submission
     *
     * @param string $name
     * @param string $value
     * @param int    $order
     */
    public function addNewTextField($name, $value, $order) {

        $this->addElement('text', $name, array(
            'label'          => 'Also found by',
            'value'          => $value,
            'order'          => $order,
        ));
        $this->getElement($name)
            ->setRequired(false)
            ->removeDecorator('BootstrapTag')
            ->addDecorators(array(
                array('HtmlTag', array('tag' => 'div', 'class' => "controls", 'id' => "$name-controls")),
                array('Label', array('class' => 'control-label', 'id' => "$name")),
                array(array('controlGroupWrapper' => 'HtmlTag'),
                    array('tag' => 'div', 'class' => "control-group", 'id' => "$name-control-group")),
            ));
    }

    /**
     * Adds new hidden fields to form during form submission
     *
     * @param string $name
     * @param string $value
     * @param int    $order
     */
    public function addNewHiddenField($name, $value, $order) {

        $this->addElement('hidden', $name, array(
            'value'          => $value,
            'order'          => $order,
        ));
        $this->getElement($name)
            ->setRequired(false)
            ->addFilters(array('StripTags','StringTrim', 'Null'))
            ->setDecorators(array('ViewHelper'));
    }

    /**
     * After form is posted, finds all new fields where name includes 'finder'.
     * Uses addNewTextField or addNewHiddenField
     * to add them to the form object
     *
     * @param array $data
     */
    public function preValidation(array $data) {

        function findFields($field) {
            // Return field names that include 'finder' but not the first finder
            if((strpos($field, 'finder') !== false) && ($field != 'finder1') && ($field != 'finder1ID'))  {
                return $field;
            }
        }

        // Search $data for dynamically added fields using findFields callback
        $newFields = array_filter(array_keys($data), 'findFields');

        // Get the order of the first finder field
        // so that new fields can be placed after it in the display group
        $orderFinder1 = $this->getElement('finder1')->getOrder();

        foreach ($newFields as $fieldName) {
            // Add either hidden or text field
            if (strpos($fieldName, 'ID') !== false){
                // Isolate the id number from the field name and use it to set new order
                $order = (2 * (trim($fieldName, 'finderID')) - 2) + $orderFinder1;
                $this->addNewHiddenField($fieldName, $data[$fieldName], $order);
            } else {
                $order = (2 * (trim($fieldName, 'finder')) - 1) + $orderFinder1;
                $this->addNewTextField($fieldName, $data[$fieldName], $order);
            }
            $discoverers = $this->getDisplayGroup('discoverers');
            $discoverers->addElement($this->getElement($fieldName));
        }
    }

}