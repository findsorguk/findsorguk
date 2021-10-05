<?php
/** Get submitted messages on the system
 * 
 * An example of use:
 * <code>
 * <?php
 * $messages = new Messages();
 * $messages->addComplaint($insertData);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @uses Zend_Service_Akismet
 * @uses Zend_Paginator
 * @uses Zend_Http_UserAgent
 * @example /app/modules/about/controllers/ContactusController.php
 */
class Messages extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'messages';
	
    /**
     * Default value for replied field
     */
    const REPLIED_DEFAULT_VALUE = 0;

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The akismet key
     * @access protected
     * @var string 
     */
    protected $_akismetkey;

    /** Site url
     * @access protected
     * @var string
     */
    protected $_baseUrl;

    /** Whether spam
     * 
     */
    const SPAM = '{SPAM: Akismet checked}';

    /** Not spam
     * 
     */
    const NOTSPAM = 'Akismet checked  - clean';

    /** Akismet class
     * @access protected
     * @var \Zend_Service_Akismet 
     */
    protected $_akismet;

    /** Initialise 
     * 
     */
    public function init(){
        $this->_baseUrl = Zend_Registry::get('siteurl');
	$this->_akismetkey = $this->_config->webservice->akismet->apikey;
	$this->_akismet = new Zend_Service_Akismet($this->_akismetkey, $this->_baseUrl);
    }

    /** get a count of messages
     * @access public
     * @return array
     */
    public function getCount(){
        $messages = $this->getAdapter();
        $select = $messages->select()
                ->from($this->_name,array('total' => 'COUNT(id)'))
                ->where('replied != ?',(int)1);
        return $messages->fetchAll($select);
    }

    /** Get a paginated list of messages
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getMessages(array $params){
        $messages = $this->getAdapter();
        $select = $messages->select()
                ->from($this->_name)
                ->order($this->_primary.' DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)
                ->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;
    }

    /** Add a new help request message and send email to Scheme in box.
     * @access public
     * @param array $data
     * @return integer
     */
    public function addRequest(array $data){
        if(!empty($data['csrf'])){
            unset($data['csrf']);
        }
        if(empty($data['comment_date'])){
            $data['comment_date'] = $this->timeCreation();
        }
        if(empty($data['updatedBy'])){
            $data['updatedBy'] = $this->getUserNumber();
        }
        if ($this->_akismet->isSpam($data)) {
            $data['comment_approved'] = self::SPAM;
        } else  {
            $data['comment_approved'] = self::NOTSPAM;
        }
        $mail = new Zend_Mail();
        $mail->setBodyText('You submitted this comment/ query: ' 
                . strip_tags($data['comment_content']));
        $mail->setFrom($data['comment_author_email'], $data['comment_author']);
        $mail->addTo('past@britishmuseum.org', 'The Portable Antiquities Scheme');
        $mail->addCC($data['comment_author_email'], $data['comment_author']);
        $mail->setSubject('Contact us submission');
        $mail->send();
        return parent::insert($data);
    }

    /** Add the complaint
     * @access public
     * @param array $data
     * @return integer
     */
    public function addComplaint(array $data){
        if(!empty($data['csrf'])){
            unset($data['csrf']);
        }

	unset($data['captcha']);

        if(empty($data['replied'])){
            $data['replied'] = self::REPLIED_DEFAULT_VALUE;
        }
        if(empty($data['comment_date'])){
            $data['comment_date'] = $this->timeCreation();
            $data['created'] = $this->timeCreation();
            $data['updated'] = $this->timeCreation();   
        }
        if(empty($data['createdBy'])){
            $data['createdBy'] = $this->getUserNumber();
        }
        if(empty($data['updatedBy'])){
            $data['updatedBy'] = $this->getUserNumber();
        }
        if(empty($data['user_ip'])){
            $data['user_ip'] = Zend_Controller_Front::getInstance()->getRequest()->getClientIp();
        }
        if(empty($data['user_agent'])){
            $useragent = new Zend_Http_UserAgent();
            $data['user_agent'] = substr($useragent->getUserAgent(), 0, 255);
        }
        if ($this->_akismet->isSpam($data)) {
            $data['comment_approved'] = self::SPAM;
        } else  {
            $data['comment_approved'] = self::NOTSPAM;
        }
        return parent::insert($data);
    }
}
