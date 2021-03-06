<?php

/** Form for adding a profile photo to a user's account (staff only)
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class AddStaffPhotoForm extends Pas_Form
{
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null)
    {

        parent::__construct($options);

        $this->setAttrib('enctype', 'multipart/form-data');

        $this->setName('AddAvatar');
        $avatar = new Zend_Form_Element_File('image');
        $avatar->setLabel('Upload staff photo: ')
            ->setRequired(true)
            ->setDestination(ASSETS_PATH . '/staffphotos/')
            ->addValidator('NotEmpty')
            ->addValidator('Size', false, 2097152)
	    ->addValidator('Extension', false, array('jpeg,jpg,png', 'messages'=>array('fileExtensionFalse'=>'Please upload an image with an extension of jpg or png')))
            ->setMaxFileSize(2097152)
            ->setAttribs(array('class' => 'textInput', 'accept' => '.jpeg,.jpg,.png'))
            ->addValidator('Count', false, array('min' => 1, 'max' => 1))
            ->setDescription('We only accept jpg or png files of 2MB or less');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(60);

        //Submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Upload a photo');

        $this->addElements(array($avatar, $submit, $hash))->setLegend('Add an avatar');

        $this->addDisplayGroup(array('image'), 'details');

        $this->details->setLegend('Add a staff photograph: ');

        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }

}
