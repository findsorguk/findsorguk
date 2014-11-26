<?php

/** Form for adding and editing archaeological context information for hoards
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new ArchaeologyForm();
 * $form->setLegend('Also try here');
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @since  20 August 2014
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/ArchaeologyController.php
 * @uses Archaeology
 */
class ArchaeologyForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     *
     */
    public function __construct(array $options = null)
    {

        ## GET OPTIONS TO POPULATE MENUS ##
        //Get site class options for select menu
        $siteclasses = new ArchaeologicalSiteClass();
        $siteclass_options = $siteclasses->getOptions();

        //Get archaeological landscape and topography options for select menu
        $landscapes = new ArchaeologicalLandscape();
        $landscape_options = $landscapes->getOptions();

        //Get periods for select menu
        $periods = new Periods();
        $period_options = $periods->getPeriodFrom();
        $periodword_options = $periods->getPeriodFromWords();

        //Get archaeological context options for select menu
        $contexts = new ArchaeologicalContexts();
        $context_options = $contexts->getOptions();

        //Get archaeological feature options for select menu
        $features = new ArchaeologicalFeatures();
        $feature_options = $features->getOptions();

        //Get recovery methods for select menu
        $recoveries = new RecoveryMethods();
        $rec_options = $recoveries->getOptions();

        //Get contextual data quality ratings for select menu
        $qualityrating = new DataQuality();
        $qualityrating_options = $qualityrating->getRatings();

        //End of select options construction
        $this->addElementPrefixPath('Pas_Filter', 'Pas/Filter/', 'filter');

        parent::__construct($options);

        $this->setName('archaeology');

        ## SITE INFORMATION ##
        //Known site checkbox
        $knownsite = new Zend_Form_Element_Checkbox('knownsite');
        $knownsite->setLabel('Known site: ')
            ->setRequired(false)
            ->setCheckedValue('1')
            ->setUncheckedValue(null)
            ->addFilters(array('StripTags', 'StringTrim'));

        //Excavated checkbox
        $excavated = new Zend_Form_Element_Checkbox('excavated');
        $excavated->setLabel('Excavated site: ')
            ->setRequired(false)
            ->setCheckedValue('1')
            ->setUncheckedValue(null)
            ->addFilters(array('StripTags', 'StringTrim'));

        //Site class menu: Assigned via dropdown
        $siteclass = new Zend_Form_Element_Select('sitecontext');
        $siteclass->setLabel('Site class: ')
            ->addMultioptions(array(
                null => 'Choose class of site',
                'Available classes' => $siteclass_options
            ))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('InArray', false, array(array_keys($siteclass_options)))
            ->addValidator('Int')
            ->setAttrib('class', 'input-xlarge selectpicker show-menu-arrow');

        //Landscape and topography menu: Assigned via dropdown
        $landscape_topography = new Zend_Form_Element_Select('landscapetopography');
        $landscape_topography->setLabel('Landscape and topography: ')
            ->addMultioptions(array(
                null => 'Choose landscape term',
                'Available terms' => $landscape_options
            ))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('InArray', false, array(array_keys($landscape_options)))
            ->addValidator('Int')
            ->setAttrib('class', 'input-xlarge selectpicker show-menu-arrow');


        ## SITE DATING ##
        //Broad period
        $broadperiod = new Zend_Form_Element_Select('broadperiod');
        $broadperiod->setLabel('Broad period: ')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(
                null => 'Choose broadperiod',
                'Available periods' => $periodword_options
            ))
            ->addErrorMessage('You must enter a broad period.')
            ->addValidator('InArray', false, array(array_keys($periodword_options)))
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'));

        //Sub period from: Assigned via dropdown
        $sitesubperiod1 = new Zend_Form_Element_Select('subperiod1');
        $sitesubperiod1->setLabel('Sub period from: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(null => 'Choose a subperiod',
                'Valid sub periods' => array('1' => 'Early', '2' => 'Middle', '3' => 'Late')))
            ->setAttribs(array('class' => 'selectpicker show-menu-arrow'));

        //Period from: Assigned via dropdown
        $siteperiod1 = new Zend_Form_Element_Select('period1');
        $siteperiod1->setLabel('Period from: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(null => 'Choose a period from',
                'Available periods' => $period_options))
            ->addValidator('InArray', false, array(array_keys($period_options)))
            ->addValidator('Int')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'));

        //Sub period to: Assigned via dropdown
        $sitesubperiod2 = new Zend_Form_Element_Select('subperiod2');
        $sitesubperiod2->setLabel('Sub period to: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(null => 'Choose a subperiod',
                'Valid sub periods' => array('1' => 'Early', '2' => 'Middle', '3' => 'Late')))
            ->addValidator('Digits')
            ->setAttribs(array('class' => 'selectpicker show-menu-arrow'));

        //Period to: Assigned via dropdown
        $siteperiod2 = new Zend_Form_Element_Select('period2');
        $siteperiod2->setLabel('Period to: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(null => 'Choose period to',
                'Available periods' => $period_options))
            ->addValidator('InArray', false, array(array_keys($period_options)))
            ->addValidator('Int')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'));

        //Date from: Free text Integer +ve or -ve
        $numdate1 = new Zend_Form_Element_Text('sitedateyear1');
        $numdate1->setLabel('Date from: ')
            ->setAttrib('size', 10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('Int');

        //Date to: Free text Integer +ve or -ve
        $numdate2 = new Zend_Form_Element_Text('sitedateyear2');
        $numdate2->setLabel('Date to: ')
            ->setAttrib('size', 10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('Int');

        ## CONTEXT DETAILS ##
        //Archaeological context: Assigned via dropdown
        $arch_context = new Zend_Form_Element_Select('sitetype');
        $arch_context->setLabel('Context: ')
            ->addMultioptions(array(
                null => 'Choose a context',
                'Available contexts' => $context_options
            ))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('InArray', false, array(array_keys($context_options)))
            ->addValidator('Int')
            ->setAttrib('class', 'input-xlarge selectpicker show-menu-arrow');

        //Archaeological feature: Assigned via dropdown
        $arch_feature = new Zend_Form_Element_Select('feature');
        $arch_feature->setLabel('Feature: ')
            ->addMultioptions(array(
                null => 'Choose a feature',
                'Available features' => $feature_options
            ))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('InArray', false, array(array_keys($feature_options)))
            ->addValidator('Int')
            ->setAttrib('class', 'input-xlarge selectpicker show-menu-arrow');

        //Feature date from: Free text Integer +ve or -ve
        $featuredate1 = new Zend_Form_Element_Text('featuredateyear1');
        $featuredate1->setLabel('Feature date from: ')
            ->setAttrib('size', 10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('Int');

        //Feature date to: Free text Integer +ve or -ve
        $featuredate2 = new Zend_Form_Element_Text('featuredateyear2');
        $featuredate2->setLabel('Feature date to: ')
            ->setAttrib('size', 10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('Int');

        ## RECOVERY INFORMATION ##
        //Recovery method: Assigned via dropdown
        $recmethod = new Zend_Form_Element_Select('recmethod');
        $recmethod->setLabel('Recovery method: ')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('Int')
            ->addValidator('inArray', true, array(array_keys($rec_options)))
            ->addMultiOptions(array(null => 'Choose method of discovery', 'Available methods' => $rec_options))
            ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'));

        //First excavation year: Free text Integer +ve or -ve
        $excavationyear1 = new Zend_Form_Element_Text('yearexc1');
        $excavationyear1->setLabel('First excavation year: ')
            ->setAttrib('size', 10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY', 'class' => 'input-large'))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('Int');

        //Second excavation year: Free text Integer +ve or -ve
        $excavationyear2 = new Zend_Form_Element_Text('yearexc2');
        $excavationyear2->setLabel('Second excavation year: ')
            ->setAttrib('size', 10)
            ->setAttribs(array('placeholder' => 'Year in format YYYY'))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('Int');

        ## ARCHAEOLOGICAL DETAILS ##
        //Archaeology description: free text field
        $description = new Pas_Form_Element_CKEditor('description');
        $description->setLabel('Archaeology description: ')
            ->setRequired(false)
            ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

        //Contextual data quality rating: Assigned via dropdown
        $contextualdataquality = new Zend_Form_Element_Select('contextualrating');
        $contextualdataquality->setLabel('Context data quality rating: ')
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(
                null => 'Choose a rating',
                'Available ratings' => $qualityrating_options))
            ->addValidator('InArray', false, array(array_keys($qualityrating_options)))
            ->setAttrib('class', 'input-large selectpicker show-menu-arrow')
            ->setDescription('This data quality field can only be completed by hoards project staff')
            ->addValidator('Int');

        ## ARCHIVE LOCATION ##
        //Archive location: free text field
        $archive_loc = new Zend_Form_Element_Text('archiveloc');
        $archive_loc->setLabel('Archive location: ')
            ->setAttrib('class', 'span6')
            ->addFilters(array('StripTags', 'StringTrim'));

        ## SUBMIT BUTTON ##
        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array(
            $knownsite, $excavated, $siteclass, $landscape_topography, $broadperiod, $sitesubperiod1, $siteperiod1,
            $sitesubperiod2, $siteperiod2, $numdate1, $numdate2, $arch_context, $arch_feature, $featuredate1,
            $featuredate2, $recmethod, $excavationyear1, $excavationyear2, $description, $contextualdataquality,
            $archive_loc, $submit
        ));

        $this->addDisplayGroup(array(
                'knownsite', 'excavated', 'sitecontext', 'landscapetopography'),
            'siteinfo');
        $this->siteinfo->setLegend('Site information');

        $this->addDisplayGroup(array(
                'broadperiod', 'subperiod1', 'period1', 'subperiod2', 'period2', 'sitedateyear1', 'sitedateyear2'),
            'sitedating');
        $this->sitedating->setLegend('Site dating');

        $this->addDisplayGroup(array(
                'sitetype', 'feature', 'featuredateyear1', 'featuredateyear2'),
            'contextdetails');
        $this->contextdetails->setLegend('Context details');

        $this->addDisplayGroup(array(
                'recmethod', 'yearexc1', 'yearexc2'),
            'recoveryinfo');
        $this->recoveryinfo->setLegend('Recovery information');

        $this->addDisplayGroup(array(
                'description', 'contextualrating'),
            'archaeologicaldetails');
        $this->archaeologicaldetails->setLegend('Archaeological details');

        $this->addDisplayGroup(array(
                'archiveloc'),
            'archivelocation');
        $this->archivelocation->setLegend('Archive location');

        $this->addDisplayGroup(array(
                'submit'),
            'buttons');

        $person = new Pas_User_Details();
        $role = $person->getRole();
        $projectTeam = array('hoard', 'admin');
        if (!in_array($role, $projectTeam)) {
            $contextualdataquality->disabled = true;
        }

        parent::init();

    }
}