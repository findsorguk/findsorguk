<?php
/** Form for adding an avatar to a user's account if they don't use Gravatar
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class AddAvatarForm extends Pas_Form {

    /** Construct the form
     * @access public
     * @param array $options
     * @return void 
     */
    public function __construct(array $options = null) {

	parent::__construct($options);
	
	$this->setAttrib('enctype', 'multipart/form-data');
	$this->setName('AddAvatar');

	$avatar = new Zend_Form_Element_File('avatar');
	$avatar->setLabel('Upload an avatar: ')
                ->setRequired(true)
                ->setDestination('./images/avatars/')
                ->addValidator('NotEmpty')
                ->addValidator('Size', false, 512000)
                ->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif') 
                ->setMaxFileSize(512000)
                ->setAttribs(array('class'=> 'textInput'))
                ->addValidator('Count', false, array('min' => 1, 'max' => 1));

        $submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Upload an avatar')
                ->setAttribs(array('class'=> 'large'));
	$this->addElements(array($avatar,$submit));
	parent::init();
	}
}