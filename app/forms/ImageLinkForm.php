<?php
/** Form for linking images to finds
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new ImageLinkForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/ImagesController.php
 */
class ImageLinkForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

        parent::__construct($options);

        $this->setName('imagelink');

        $old_findID = new Zend_Form_Element_Text('old_findID');
        $old_findID->setLabel('Filter by find ID #')
                ->setRequired(true)
                ->setAttrib('size', 20)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('StringLength', false, array(1,200));

        $findID = new Zend_Form_Element_Hidden('findID');
        $findID->setRequired(true)
                ->setAttrib('size', 11)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('StringLength', false, array(1,200));

        //Submit button
        $submit = new Zend_Form_Element_Submit('submit');

        $submit->setLabel('Link that image');

        $this->addElements(array($findID, $old_findID, $submit));

        $this->addDisplayGroup(array('old_findID','findID'), 'details');

        $this->details->setLegend('Link image: ');

        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}