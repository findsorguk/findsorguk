<?php
/** 
 * A view helper for rendering coin data
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Zend_View_Helper_PartialLoop
 * @uses Pas_View_Helper_AddCoinLink
 * @uses Pas_View_Helper_AddJettonLink
 * @license http://URL GNU
 * @version 1
 * @since 1
 * @copyright (c) 2014, Daniel Pett
 *
 */

class Pas_View_Helper_CoinDataDisplay extends Zend_View_Helper_Abstract
{

    protected $_numismatics = array('COIN', 'MEDALLION');

    protected $_objects = array('JETTON', 'TOKEN');

    protected $_broadperiods = array(
        'IRON AGE', 'ROMAN', 'BYZANTINE',
        'EARLY MEDIEVAL', 'GREEK AND ROMAN PROVINCIAL', 'MEDIEVAL', 
        'POST MEDIEVAL', 'MODERN', 'UNKNOWN'
        );

    protected $_objectType;
    
    protected $_broadperiod;
    
    protected $_coins;
    
    protected $_finds;
    
    protected $_types;
    
    public function getNumismatics() {
        return $this->_numismatics;
    }

    public function getObjects() {
        return $this->_objects;
    }

    public function getBroadperiods() {
        return $this->_broadperiods;
    }

    public function getObjectType() {
        return $this->_objectType;
    }

    public function getBroadperiod() {
        return $this->_broadperiod;
    }

    public function getCoins() {
        return $this->_coins;
    }

    public function getFinds() {
        return $this->_finds;
    }

    public function setNumismatics($numismatics) {
        $this->_numismatics = $numismatics;
        return $this;
    }

    public function setObjects($objects) {
        $this->_objects = $objects;
        return $this;
    }

    public function setBroadperiods($broadperiods) {
        $this->_broadperiods = $broadperiods;
        return $this;
    }

    public function setObjectType($objectType) {
        $this->_objectType = $objectType;
        return $this;
    }
    public function getTypes() {
        $this->_types = array_merge($this->getNumismatics(), 
                $this->getObjects());
        return $this->_types;
    }

    public function setTypes($types) {
        $this->_types = $types;
        return $this;
    }

        public function setBroadperiod($broadperiod) {
        $this->_broadperiod = $broadperiod;
        return $this;
    }

    public function setCoins($coins) {
        $this->_coins = $coins;
        return $this;
    }

    public function setFinds($finds) {
        $this->_finds = $finds;
        return $this;
    }

    public function coinDataDisplay() {
        return $this;
    }
    
    public function __toString() {
        return $this->buildHtml();
    }
    
    public function buildHtml() {
        
        $html = '';
        if (in_array(strtoupper($this->getObjectType()), $this->getTypes())) {
        if (sizeof($this->getCoins())>0) {
        if (in_array( strtoupper( $this->getBroadperiod() ), $this->getBroadperiods() )) {

            if (in_array(strtoupper($this->getObjectType()), $this->getNumismatics())) {
                $template = str_replace(' ','', $this->getBroadperiod());
                $html .= $this->view->partialLoop('partials/database/' . strtolower($template) . 'Data.phtml', $this->getCoins());
            } elseif (in_array(strtoupper($this->getObjectType()), $this->getObjects())) {
                $html .= $this->view->partialLoop('partials/database/jettonData.phtml', $this->getCoins());
            } else {
                $html .= '';
            }

        } else {
            $html .= '<h4>An error has been detected</h4>';
            $html .= 'You can either not have a coin of that period, or we are';
            $html .= ' not set up for that period.';
        }
        } else {
            $html .= '<div>';
            $html .= '<h4>Numismatic data</h4>';
            $html .= '<p>No numismatic data has been recorded for this coin yet.</p>';
            $html .= '<div class="noprint">';
            if (in_array(strtoupper($this->getObjectType()), $this->getNumismatics())) {
            $html .= $this->view->addCoinLink()
                            ->setFindID((int) $finds[0]['id'])
                            ->setSecUid($finds[0]['secuid'])
                            ->setCreatedBy((int) $finds[0]['createdBy'])
                            ->setBroadperiod($finds[0]['broadperiod'])
                            ->setInstitution($finds[0]['institution']);

            $html .= '</div></div>';
            } elseif (in_array(strtoupper($this->getObjectType()), $this->getObjects())) {
            $html .= $this->view->addJettonLink()
                    ->setFindID((int) $finds[0]['id'])
                    ->setSecUid($finds[0]['secuid'])
                    ->setCreatedBy((int) $finds[0]['createdBy'])
                    ->setBroadperiod($finds[0]['broadperiod'])
                    ->setInstitution($finds[0]['institution']);
            $html .= '</div></div>';
            }

        }
        }

        return $html;
    }

    }
