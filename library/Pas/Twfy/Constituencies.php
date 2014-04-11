<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** Retrieve a list of constituencies from twfy
 *
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Constituencies
 * @author Daniel Pett
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Twfy
 * @uses Pas_Twfy_Exception
 */
class Pas_Twfy_Constituencies extends Pas_Twfy {

    /** The correct method to use
     *
     */
    const METHOD = 'getConstituencies';

    /** Constituencies I don't want!!
     *
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
	'Strangford'
	);

    /** Get the response from twfy
     *
     * @param type $date
     * @return type
     */
    public function get($date){
        $params = array(
            'key' => $this->_apikey,
            'date'	=> $date
        );
        $data =  parent::get(self::METHOD, $params);
        return $this->_cleanUp($data);
    }

    /** Remove elements from array
     * @throws pas_Twfy_Exception
     * @param array $data
     * @return array
     */
    protected function _cleanUp($data){
    if(is_array($data)){
    if (!($this->_cache->test(md5(self::METHOD)))) {
    foreach ($data as $a) {
    if(in_array($a->name,$this->_remove)){
    unset($a->name);
    }
    }
    $data2 = array();
    foreach($data as $a){
    if(isset($a->name)){
    $data2[] = array('name' => $a->name);
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

}

