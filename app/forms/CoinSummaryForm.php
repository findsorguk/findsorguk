<?php
/** A form for manipulating coin summary data for hoards
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new CoinSummaryForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/NumismaticsController.php
 * @uses Periods
 */
class CoinSummaryForm extends Pas_Form
{

    /** Construct the coin summary form
     */
    public function __construct(array $options = null)
    {
        //Get periods for select menu
        $periods = new Periods();
        $periodWord = $periods->getCoinsPeriodWords();

        //End of select options construction
        $this->addElementPrefixPath('Pas_Filter', 'Pas/Filter/', 'filter');

        parent::__construct($options);

        $this->setName('coinsummary');

        $quantity = new Zend_Form_Element_Text('quantity');
        $quantity->setLabel('Quantity: ')
            ->addValidator('Int')
            ->setErrorMessages(array('That is not a number'))
            ->setValue(1)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttribs(array('class'=> 'input-small'))
            ->setOrder(1);

        $broadperiod = new Zend_Form_Element_Select('broadperiod');
        $broadperiod->setLabel('Broad period: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->setErrorMessages(array('You must enter a broad period'))
            ->addMultiOptions(array(
                null => 'Choose broadperiod' ,
                'Available periods' => $periodWord
            ))
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(2);

        $denomination = new Zend_Form_Element_Select('denomination');
        $denomination->setLabel('Denomination: ')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(3);

        $ruler_id = new Zend_Form_Element_Select('ruler_id');
        $ruler_id->setLabel('Ruler: ')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow disabled'))
            ->setOrder(4);

        $mint_id = new Zend_Form_Element_Select('mint_id');
        $mint_id->setLabel('Issuing mint: ')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(5);

        $geographyID = new Zend_Form_Element_Select('geographyID');
        $geographyID->setLabel('Iron Age geography: ')
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(6);

        $numdate1 = new Zend_Form_Element_Text('numdate1');
        $numdate1->setLabel('Date from: ')
            ->setAttribs(array('class'=> 'input-small'))
            ->setOrder(7);

        $numdate2 = new Zend_Form_Element_Text('numdate2');
        $numdate2->setLabel('Date to: ')
            ->setAttribs(array('class'=> 'input-small'))
            ->setOrder(8);

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array(
            $quantity, $broadperiod, $denomination,
            $ruler_id, $mint_id, $geographyID,
            $numdate1, $numdate2, $submit
        ));

        $this->addDisplayGroup(array(
                'quantity', 'broadperiod', 'denomination',
                'ruler_id', 'mint_id', 'geographyID',
                'numdate1', 'numdate2'
            ),
            'details'
        );
        $this->addDisplayGroup(array(
                'submit'),
            'buttons');

        parent::init();

    }
}