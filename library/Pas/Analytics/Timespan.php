<?php
/** The time spans that you might want to query the google analytics api by.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Analytics
 * @version 1
 * 
 */
class Pas_Analytics_Timespan {
	
    /** The timespan variable
     * @access protected
     * @var string
     */
    protected $_timespan;
        
    /** Get the timespan
     * @access protected
     * @return string
     */    
    public function getTimespan() {
        return $this->_timespan;
    }

    /** Set the timespan to query
     * @access public
     * @param string $timespan
     * @return \Pas_Analytics_Timespan
     */
    public function setTimespan($timespan) {
        $this->_timespan = (string)$timespan;
        return $this;
    }
    
    /** Get the dates to query
     * @access public
     * @return array
     */
    public function getDates(  ){
        $d = new Pas_Analytics_Dates();
        switch($this->_timespan){
            case 'today':
                $dates = $d->today();
                break;
            case 'yesterday':
                $dates = $d->yesterday();
                break;
            case 'thisweek':
                $dates = $d->thisWeek();
                break;
            case 'lastweek':
                $dates = $d->lastweek();
                break;
            case 'thismonth':
                $dates = $d->thisMonth();
                break;
            case 'lastmonth':
                $dates = $d->lastMonth();
                break;
            case 'thisyear':
                $dates = $d->thisYear();
                break;
            case 'lastyear':
                $dates = $d->lastYear();
                break;
            default:
                $dates = $d->thisMonth();
                break;
        }
        return $dates;
    }
}