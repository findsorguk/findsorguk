<?php
/** Form for linking sketchfab models to finds
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new SketchFabForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class SketchFabForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

        parent::__construct($options);

        $this->setName('sketchfab');

        $model = new Zend_Form_Element_Text('modelID');
        $model->setLabel('SketchFab model ID: ')
                ->setRequired(true)
                ->setAttrib('size', 20)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('StringLength', false, array(1,200));

        //Submit button
        $submit = new Zend_Form_Element_Submit('submit');

        $submit->setLabel('Submit model');

        $this->addElements(array($model, $submit));

        $this->addDisplayGroup(array('modelID'), 'details');

        $this->details->setLegend('SketchFab model: ');

        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}