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

        //Construct parent form
        parent::__construct($options);

        //Set the coin form name
        $this->setName('coinsummary');

        $this->setAction();

        /** Set up broadperiod form element
         * @var $broadperiod
         */
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
            ->setOrder(1);

        /** Set up ruler id form element
         * @var $ruler_id
         */
        $ruler_id = new Zend_Form_Element_Select('ruler_id');
        $ruler_id->setLabel('Ruler: ')
            ->addValidator('Int')
            ->setRegisterInArrayValidator(false)
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(2);

        /** Set up denomination element
         *
         * @var  $denomination
         */
        $denomination = new Zend_Form_Element_Select('denomination');
        $denomination->setLabel('Denomination: ')
            ->addValidator('Int')
            ->setRegisterInArrayValidator(false)
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(3);

        /** The ID of the mint
         * Default null
         * @var $mint_id
         */
        $mint_id = new Zend_Form_Element_Select('mint_id');
        $mint_id->setLabel('Issuing mint: ')
            ->addValidator('Int')
            ->setRegisterInArrayValidator(false)
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(4);

        /** Set up iron age geography element
         * @var $geographyID
         */
        $geographyID = new Zend_Form_Element_Select('geographyID');
        $geographyID->setLabel('Iron Age geography: ')
            ->addValidator('Int')
            ->setRegisterInArrayValidator(false)
            ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'))
            ->setOrder(5);

        /** Set up earliest date element
         * +ve or -ve integer
         * Default should be null
         * @var $numdate1
         */
        $numdate1 = new Zend_Form_Element_Text('numdate1');
        $numdate1->setLabel('Date from: ')
            ->addValidator('Int')
            ->addFilters(array('StripTags','StringTrim'))
            ->setAttribs(array('class'=> 'input-small', 'placeholder' => 'YYYY'))
            ->setOrder(6);

        /** Set up latest date element
         * +ve or -ve integer
         * Default should be null
         * @var $numdate2
         */
        $numdate2 = new Zend_Form_Element_Text('numdate2');
        $numdate2->setLabel('Date to: ')
            ->addValidator('Int')
            ->setAttribs(array('class'=> 'input-small', 'placeholder' => 'YYYY'))
            ->setOrder(7);

        /** Set up the quantity element
         * @var $quantity
         */
        $quantity = new Zend_Form_Element_Text('quantity');
        $quantity->setLabel('Quantity: ')
            ->addValidator('Int')
            ->setErrorMessages(array('That is not a number'))
            ->setValue(1)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttribs(array('class'=> 'input-small'))
            ->setOrder(8);

        /** Create submit form element
         * @var $submit
         */
        $submit = new Zend_Form_Element_Submit('submit');

        /** Add the elements to the form
         */
        $this->addElements(array(
            $quantity, $broadperiod, $denomination,
            $ruler_id, $mint_id, $geographyID,
            $numdate1, $numdate2, $submit
        ));

        /** Create a display group for form fields
         */
        $this->addDisplayGroup(array(
                'quantity', 'broadperiod', 'denomination',
                'ruler_id', 'mint_id', 'geographyID',
                'numdate1', 'numdate2'
            ),
            'details'
        );

        /** Create a display group for buttons
         * */
        $this->addDisplayGroup(array(
                'submit'),
            'buttons');

        /** Init the parent
         */
        parent::init();

    }
}