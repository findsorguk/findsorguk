<?php

/** Form for creating and editing help topics
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new CopyrightsForm();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/HelpController.php
 * @uses Users
 */
class CopyrightsForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */

    public function __construct(array $options = null)
    {


        parent::__construct($options);

        $this->setName('copyrights');

        $copyright = new Zend_Form_Element_Text('copyright');
        $copyright->setLabel('Copyright institution: ')
            ->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->setAttrib('size', 60)
            ->addErrorMessage('You must enter an institution');

        //Submit button
        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array($copyright, $submit));

        $this->addDisplayGroup(array('copyright', 'submit'), 'details')->removeDecorator('HtmlTag');


        parent::init();
    }
}