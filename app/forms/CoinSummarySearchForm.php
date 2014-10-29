<?php

/** A form for searching the indexes specifically tailored for the retrieval of
 * the limited amount of Byzantine coins we have recorded.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new CoinSummarySearchForm();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Pas_Form
 */
class CoinSummarySearchForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null)
    {

        $institutions = new Institutions();
        $inst_options = $institutions->getInsts();

        $rulers = new Rulers();
        $ruler_options = $rulers->getRulersByzantine();

        $denominations = new Denominations();
        $denomination_options = $denominations->getDenomsByzantine();

        $mints = new Mints();
        $mint_options = $mints->getMintsByzantine();

        $periods = new Periods();
        $periodword_options = $periods->getPeriodFromWords();


        parent::__construct($options);

        $this->setName('coinsummary-search');

        $broadperiod = new Zend_Form_Element_Select('broadperiod');
        $broadperiod->setLabel('Broad period: ')
            ->addFilters(array('StringTrim','StripTags'))
            ->addMultiOptions(array(
                null => 'Choose period from',
                'Available periods' => $periodword_options
            ))
            ->setAttribs(array(
                'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(1);

        ###
        ##Numismatic data
        ###
        //Denomination
        $denomination = new Zend_Form_Element_Select('denomination');
        $denomination->setLabel('Denomination: ')
            ->setRegisterInArrayValidator(false)
            ->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttribs(array(
                'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->addMultiOptions(array(
                null => 'Choose denomination type',
                'Available denominations' => $denomination_options))
            ->setOrder(2);

        //Primary ruler
        $ruler = new Zend_Form_Element_Select('ruler');
        $ruler->setLabel('Ruler / issuer: ')
            ->setRegisterInArrayValidator(false)
            ->setAttribs(array(
                'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(
                null => 'Choose primary ruler',
                'Available rulers' => $ruler_options
            ))
            ->setOrder(3);

        //Mint
        $mint = new Zend_Form_Element_Select('mint');
        $mint->setLabel('Issuing mint: ')
            ->setAttribs(array(
                'class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setRegisterInArrayValidator(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(
                null => 'Choose denomination type',
                'Available mints' => $mint_options))
            ->setOrder(4);

        $institution = new Zend_Form_Element_Select('institution');
        $institution->setLabel('Recording institution: ')
            ->setRequired(false)
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addMultiOptions(array(
                null => 'Choose institution',
                'Choose institution' => $inst_options))
            ->setOrder(5);

        $quantity = new ZendX_JQuery_Form_Element_Spinner('quantity');
        $quantity->setLabel('Quantity in hoard')
            ->setJQueryParams(array('defaultValue' => 1, 'min' => 1, 'max' => 50000, ))
            ->setAttribs(array('class' => 'input-large'))
            ->addValidators(array('Int'))
            ->setOrder(6);

        $fromDate = new Zend_Form_Element_Text('fromDate');
        $fromDate->setLabel('Date from: ')
            ->setValidators(array('Int'))
            ->setAttribs(array('placeholder' => 'YYYY', 'class' => 'input-small'))
            ->setOrder(7);

        $toDate = new Zend_Form_Element_Text('toDate');
        $toDate->setLabel('Date to: ')
            ->setValidators(array('Int'))
            ->setAttribs(array('placeholder' => 'YYYY', 'class' => 'input-small'))
            ->setOrder(8);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Search summaries');

        $this->addElements(array(
            $broadperiod, $denomination, $mint, $institution,
            $ruler, $quantity, $fromDate, $toDate,
            $submit
        ));

        $this->addDisplayGroup(array(
                'broadperiod', 'denomination', 'ruler',
                'mint', 'fromDate', 'toDate',
                'quantity', 'institution', ),
            'numismatics');


        $this->numismatics->setLegend('Summary details');

        $this->addDisplayGroup(array('submit'), 'buttons');

        ZendX_JQuery::enableForm($this);
        parent::init();
    }
}