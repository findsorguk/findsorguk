<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** An interface to the Edina SpatialFeatureSearch api call
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage ClosestMatchSearch
 * @license GNU Public
 * @since 3/2/12
 * @version 1
 * @copyright Daniel Pett, The British Museum
 * @author Daniel Pett
 * @uses Pas_Geo_Edina_Exception
 * @see http://unlock.edina.ac.uk/places/queries/
 * Usage:
 * $edina = new Pas_Geo_Edina_SpatialFeatureSearch();
 * $edina->setType('Farm');
 * $edina->setBoundingBox(array(
 * '-3.35081720352173', //minx
 * '55.87272644042972', //miny
 * '-2.01274991035461', //maxx
 * '55.9947509765625',  //maxy
 * ));
 * $edina->get();
 * Then process the object returned in your script
 */
class Pas_Geo_Edina_SpatialFeatureSearch extends Pas_Geo_Edina {

    /** The method to call
     *
     */
    const METHOD = 'spatialFeatureSearch?';

    /** The number of vertices to call
     *
     */
    const CORNERS = 4;

    /** The operators
     * @access protected
     * @var array
     */
    protected $_operators = array('within','intersect');

    /** The feature type to search
     *
     * @var string
     */
    protected $_type;

    /** The minimum latitude
     * @access protected
     * @var float
     */
    protected $_minx;

    /** The maximum latitude
     * @access protected
     * @var float
     */
    protected $_maxx;

    /** The minimum longitude
     * @access protected
     * @var float
     */
    protected $_miny;

    /** The maximum longitude
     * @access protected
     * @var float
     */
    protected $_maxy;

    /** Set the type to query
     * @access public
     * @param array $types
     * @return type
     * @throws Pas_Geo_Edina_Exception
     */
    public function setType( $type){
        $featureTypes = new Pas_Geo_Edina_FeatureTypes();
        $types = $featureTypes->getTypesList();

        if(!in_array($type, $types)){
            throw new Pas_Geo_Edina_Exception('That type is not supported');
        } else {
        return $this->_type = $type;
        }
    }

    /** Get the operators available
     * @access public
     * @return type
     */
    public function getOperators() {
        return $this->_operators;
    }

    /** Get the type of feature called
     * @access public
     * @return type
     */
    public function getType() {
        return $this->_type;
    }

    /** Get the min lat
     * @access public
     * @return type
     */
    public function getMinx() {
        return $this->_minx;
    }

    /** Get the max lat
     * @access public
     * @return type
     */
    public function getMaxx() {
        return $this->_maxx;
    }

    /** Get the min long
     * @access public
     * @return type
     */
    public function getMiny() {
        return $this->_miny;
    }

    /** Get the max lat
     * @access public
     * @return type
     */
    public function getMaxy() {
        return $this->_maxy;
    }

    /** Get the operator used
     * @access public
     * @return type
     */
    public function getOperator() {
        return $this->_operator;
    }

    /** The default operator
    * @access protected
    * @var type
    */
    protected $_operator = 'within';

    /** set the operator to use
     * @access public
     * @param type $operator
     * @throws Pas_Geo_Edina_Exception
     */
    public function setOperator($operator){
        if(!in_array($operator, $this->_operators)){
            throw new Pas_Geo_Edina_Exception('The operator you provided is not in scope');
        } else {
            $this->_operator = $operator;
        }
    }

    /** Set the bounding box
     * @access public
     * @param array $bbox
     */
    public function setBoundingBox( array $bbox){
       if(is_array($bbox)){
           $this->_bboxCheck($bbox);
       }
       $this->_minx = $bbox['0'];
       $this->_miny = $bbox['1'];
       $this->_maxx = $bbox['2'];
       $this->_maxy = $bbox['3'];
    }

    /** Check that bounding box coordinates are valid
     * @access protected
     * @param array $bbox
     * @throws Pas_Geo_Edina_Exception
     */
    protected function _bboxCheck($bbox){
      if(count($bbox) === self::CORNERS){
      //Validate the points
      foreach($bbox as $corner){
          if(!is_numeric($corner)){
              throw new Pas_Geo_Edina_Exception('Coordinate provided not numeric');
          } elseif((abs($corner) > 180)){
              throw new Pas_Geo_Edina_Exception('Coordinate greater than 180 &deg;');
          }
      }
      //Check mathematics
      if($bbox['0']  > $bbox['2']){
        //This checks that the minimum latitude is smaller than maximum
        //latitude, if not throw exception
        throw new Pas_Geo_Edina_Exception('Minimum latitude greater than maximum');
       }

      if($bbox['1'] > $bbox['3'] ){
        //This checks that the minimum latitude is smaller than maximum
        ////latitude, if not throw exception
        throw new Pas_Geo_Edina_Exception('Minimum longitude greater than maximum');
      }
    }
    }

    /** Get the data from the api
     * @access public
     */
    public function get() {
        $params = array(
            'featureType' => $this->_type,
            'minx' => $this->_minx,
            'miny' => $this->_miny,
            'maxx' => $this->_maxx,
            'maxy' => $this->_maxy,
            'operator' => $this->_operator
        );

    return parent::get(self::METHOD, $params);
    }

}