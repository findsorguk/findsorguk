<?php
/**  Form for adding and editing primary activities for people
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Findspots
 * @version 1
*/
class ConfigureFindSpotCopyForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
	$finds = new Findspots();
	$schema = $finds->info();

	$fields = array_flip($schema['cols']);
	$remove = array(
            'updated', 'created', 'updatedBy',
            'createdBy', 'institution','findID',
            'address', 'fourFigure', 'gridlen',
            'postcode', 'easting', 'northing',
            'declong', 'declat', 'fourFigureLat',
            'fourFigureLon', 'woeid', 'geonamesID',
            'osmNode', 'elevation', 'geohash', 
            'country', 'map25k', 'map10k',
            'soiltype', 'smrref', 'otherref',
            'id','accuracy', 'secuid',
            'old_occupierid', 'occupier', 'old_findspotid',
            'date'
	);
	foreach($remove as $rem){
		unset($fields[$rem]);
	}
	
	$labels = array(
            'gridrefcert' => 'Grid reference certainty',
            'gridref' => 'Grid reference',
            'knownas'  => 'Known as',
            'disccircum' => 'Discovery circumstances',
            'gridrefsrc' => 'Grid reference source',
            'landusevalue' => 'Land use value',
            'landusecode' => 'Land use code',
            'depthdiscovery'	=> 'Depth of discovery',
            'Highsensitivity'	=> 'High sensitivity',
	);
	
	parent::__construct($options);

	$this->setName('configureFindSpotCopy');
	$elements = array();
	foreach(array_keys($fields) as $field){
			$label = $field;
	$field = new Zend_Form_Element_Checkbox($field);
	if(array_key_exists($label,$labels)){
            $clean = ucfirst($labels[$label]);
	} else {
            $clean = ucfirst($label);
	}
	
	$field->setLabel($clean)
		->setRequired(false)
		->addValidator('NotEmpty','boolean');

	$elements[] = $field;
	$this->addElement($field);
	}
	
	$this->addDisplayGroup($elements, 'details');
		//Submit button
	$submit = new Zend_Form_Element_Submit('submit');;
	$this->addElement( $submit);
	$this->details->setLegend('Choose fields: ');
        parent::init();
    }
}