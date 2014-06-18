<?php
/**
 * A view helper for looking up person details via their id string
 *
 * Example of use:
 * <code>
 * <?php
 * echo $this->personLookup()->setPerson($personID);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http://URL name
 * @copyright (c) 2014, Daniel Pett
 * @example /app/views/scripts/partials/admin/account.phtml 
 */
class Pas_View_Helper_PersonLookup extends Zend_View_Helper_Abstract
{
    /** The person's ID
     * @access protected
     * @var string
     */
    protected $_personID;

    /** Get the person ID
     * @access public
     * @return string
     */
    public function getPersonID() {
        return $this->_personID;
    }
    /** Lookup the person and attach a url
     * @access public
     * @return \Pas_View_Helper_PersonLookup
     */
    public function personLookup() {
        return $this;
    }

    /** Set a person ID
     * @access public
     * @param string $personID
     * @return \Pas_View_Helper_PersonLookup
     */
    public function setPerson($personID) {
        if (isset($personID)) {
            $this->_personID = $personID;
        }
        return $this;
    }

    /** Get the data for the person
     * @access public
     * @return object
     */
    public function getData() {
        $people = new Peoples();
        return $people->fetchRow($people->select()->where('secuid = ?',
                $this->getPerson() ));
        }

        /** Render the Html to a string
     * @access public
     * @return string
     */
    public function render() {
        $person = $this->getData();
        $html = '';
        if ($person) {
            $params = array(
                'module' => 'database',
                'controller' => 'people',
                'action' => 'person',
                'id' => $person->id
                    );
            $url = $this->view->url($params, 'default', true);
            $html .= '<a href="';
            $html .= $url;
            $html .= '">';
            $html .= $person->fullname;
            $html .= '</a>';
        } else {
            $html .= 'No personal details found';
        }
        return $html;
    }

    /** Render to string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->render();
    }
}