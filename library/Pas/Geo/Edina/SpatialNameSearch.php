<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** An interface to the Edina SpatialNameSearch api call using bounding box
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage SpatialNameSearch
 * @license GNU Public
 * @since 3/2/12
 * @version 1
 * @copyright Daniel Pett, The British Museum
 * @author Daniel pett
 * @uses Pas_Geo_Edina_Exception
 * @see http://unlock.edina.ac.uk/places/queries/
 *
 * Usage:
 * $edina = new Pas_Geo_Edina_SpatialNameSearch();
 * $edina->setName(array('Portobello','Musselburgh'));
 * $edina->setBoundingBox(array(
 * '-3.35081720352173', //minx
 * '55.87272644042972', //miny
 * '-2.01274991035461', //maxx
 * '55.9947509765625',  //maxy
 * ));
 * $edina->get();
 * Then process the object returned in your script
 */
class Pas_Geo_Edina_SpatialNameSearch extends Pas_Geo_Edina {

    /** API Method to call
     *
     */
    const METHOD = 'spatialNameSearch?';

    /** Number of vertices to check
     *
     */
    const CORNERS = 4;

    /** Possible operators for query available
     * @access protected
     * @var array
     */
    protected $_operators = array('within','intersect');

    /** The place name you will query for
     * @access protected
     * @var string
     */
    protected $_name;

    /** The minimum latitude
     * @access protected
     * @var float
     */
    protected $_minx;

    /** The maximum latitude
     * @acces protected
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

    /** The operator default
     * @access protected
     * @var type
     */
    protected $_operator = 'within';

    /** The name or names to search for
     * @access public
     * @param array $names
     * @return string
     * @throws Pas_Geo_Edina_Exception
     */
    public function setName(array $names){
        if(!is_array($names)){
            throw new Pas_Geo_Edina_Exception('The list of names must be an array');
        } else {
            return $this->_name = implode(',',$names);
        }
    }

    /** Set the operator if you want to change default
     * @access public
     * @param string $operator
     * @throws Pas_Geo_Edina_Exception
     */
    public function setOperator($operator){
        if(!in_array($operator, $this->_operators)){
            throw new Pas_Geo_Edina_Exception('The operator you provided is not in scope');
        } else {
            $this->_operator = $operator;
        }
    }

    /** Set up the bounding box to query within or via intersection
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

    /** Using the parent class, call the api
     * @access public
     * @return object
     */
    public function get() {
        $params = array(
            'name' => $this->_name,
            'minx' => $this->_minx,
            'miny' => $this->_miny,
            'maxx' => $this->_maxx,
            'maxy' => $this->_maxy,
            'operator' => $this->_operator
        );

    return parent::get(self::METHOD, $params);
    }
}