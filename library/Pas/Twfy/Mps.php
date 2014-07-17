<?php
/** Retrieve a list of mps in England and Wales from twfy
 * Not interested in Scotland as Treasure Act does not apply
 *
 * An example of use:
 * 
 * <code>
 * <?php
 * $members = new Pas_Twfy_Mps();
 * $data = $members->get();
 * ?>
 * </code>
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Mps
 * @author Daniel Pett
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Twfy
 * @uses Pas_Twfy_Exception
 * @see http://www.theyworkforyou.com/api/docs/getMPs
 * @example /app/modules/news/controllers/TheyworkforyouController.php
 */
class Pas_Twfy_Mps extends Pas_Twfy {

    /** Method to use
    *
    */
    const METHOD = 'getMps';

    /** Get the data
    * @access public
    * @return array
    */
    public function get(){
        $params = array(
         'key' => $this->_apikey,
        );
        $data =  parent::get(self::METHOD, $params);
        return $this->_cleanUp($data);
    }

    /** Clean the array
    * @access protected
    * @param array $data
    * @return array
    */
    protected function _cleanUp($data){
        if(is_array($data)){
            if (!($this->_cache->test(md5(self::METHOD)))) {
            $data2 = array();
            foreach ($data as $a){
                if(in_array($a->constituency,$this->_remove)){
                    unset($a);
                }
                if(isset($a->constituency)){
                    $data2[] = $a;
                }
            }
            $this->_cache->save($data2);
            } else {
                $data2 = $this->_cache->load(md5(self::METHOD));
            }
            return $data2;
        } else {
         throw new Pas_Twfy_Exception('Data must be an array');
        }
    }

    /** Mp constituencies to scrap
    * @access protected
    * @var array
    */
    protected $_remove = array(
        'Airdrie and Shotts','Ayr, Carrick and Cumnock',
        'Belfast North','Belfast East','Belfast South',
        'Belfast West','Aberdeen North', 'Aberdeen South',
        'Berwick-upon-Tweed','Dundee East','Dundee West',
        'Dunfermline and West Fife', 'Berwickshire, Roxburgh and Selkirk','Banff and Buchan',
        'Caithness, Sutherland and Easter Ross','Cumbernauld,Kilsyth and Kirkintilloch East',
        'Dumfriesshire, Clydesdale and Tweeddale','Dumfries and Galloway',
        'East Kilbride, Strathaven and Lesmahagow','East Londonderry','East Antrim',
        'East Dunbartonshire','East Londonderry','East Lothian',
        'East Renfrewshire', 'Edinburgh East','Edinburgh North and Leith',
        'Edinburgh South','Edinburgh South West','Edinburgh West',
        'Falkirk','Fermanagh and South Tyrone','Foyle',
        'Glasgow Central','Glasgow East','Glasgow North',
        'Glasgow North East', 'Glasgow North West','Glasgow South',
        'Glasgow South West','Glenrothes','Inverclyde',
        'Inverness, Nairn, Badenoch and Strathspey', 'Kilmarnock and Loudoun',
        'Kirkcaldy and Cowdenbeath','Lanark and Hamilton East','Mid Ulster',
        'Midlothian', 'Na h-Eileanan an Iar','Newry and Armagh',
        'North Antrim','North Down','North East Fife',
        'Ochil and South Perthshire', 'Paisley and Renfrewshire North','Paisley and Renfrewshire South',
        'Ross, Skye and Lochaber','Rutherglen and Hamilton West','South Antrim',
        'Upper Bann','West Aberdeenshire and Kincardine',
        'West Dunbartonshire','West Tyrone','Lagan Valley',
        'Strangford', 'Angus'
    );

}


