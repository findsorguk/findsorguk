<?php
/**
 * Creates an html link depending on Zend_Acl.
 * Automatically adds attributes and parameters.
 *
 * @author Sergeev Anton xapon91@gmail.com
 * @package View_Helper_Href
 * @copyright Copyright (c) 2010 Sergeev Anton
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3 license
 */
class Pas_View_Helper_Href extends Zend_View_Helper_Url {
	/**
	 * @var array
	 * Default options
	 */
	public function getRole() {
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$user = $auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}
	return $role;
	}


	private $options=array(
	'module' => null,
	'controller' => null,       //default controller
	'action' => 'index',        //default action
	'params' => array(),        //array of parameters
	'router' => 'default',      //default router
	'checkAcl' => true,         //check if this link allowed by Zend_Acl
	'content' => null,		     //Link content, text or what you want between <a> and </a>
	'attribs' => array(),       //Link attributes, e.g. class or id
	'resource' => null,         //ACL resource name, if not set - controller name will be used
	'resource-prefix' => 'mvc:',//If "resource" option is null, the ACL will be checked with this option connected with "controller" option
	'privilege' => null,    	 //ACL privilege name, if null - action name will be used
	'reset' => true,		 	 //reset router defaults
	'url' => null,		 	     //Url like "http://google.com", will be used if controller is null
	'acl' => 'acl',	         //Zend_Acl instance. If option is string, will try to get Zend_Acl from Zend_Registry by this string
	'role' => NULL,
	'allowed' => array() ,            //String - role name.
	'wrapper' => array('tag' => NULL,'id' => NULL, 'class' => NULL)
	);
	 /**
	  * @param array $options
	  * @return string or bool FALSE if options are incorrect or access denied
	 */
    public function href(array $options) {
    $options = array_merge($this->options, $options);

    $url = $this->setUrl($options);

    $attribs = '';
	if (count($options['attribs'])>0) {
	foreach ($options['attribs'] as $attrib => $value)
	$attribs .=$attrib . '="' . $value . '" ';
	} else {
	$attribs = NULL;
	}

	$wrapper = array();
	if (count($options['wrapper'])>0) {
	foreach ($options['wrapper'] as $key => $value)
	$wrapper[$key] = $value;
	} else {
	$wrapper = NULL;
	}

	if(!isset($wrapper)){
	$link = '<a href="' . $this->view->baseUrl() . $url . '" '
	. $attribs . '">' . $options['content'] . '</a>';
	} else {
	$link = '';
	if(isset($wrapper['tag'])) {
	$tag = $wrapper['tag'];
	$link .= '<'.$tag;
	} else {
	$tag = NULL;
	}

	if(isset($wrapper['class'])) {
	$class = $wrapper['class'];
	$link .= ' class="'.$class.'" ';
	} else {
	$class = NULL;
	}

	if(isset($wrapper['id'])) {
	$id = $wrapper['id'];
	$link .= 'id="'.$id.'" ';
	} else {
	$id = NULL;
	}

	if(isset($wrapper['tag'])) {
	$link .= '>';
	$link .= '<a href="' . $this->view->baseUrl() . $url .'" ' . $attribs . '>'
	. $options['content'] . '</a></';
	$link .= $tag . '>';
	} else {
	$link = '<a href="' . $this->view->baseUrl() . $url .'" ' . $attribs . '>'
	. $options['content']. '</a>';
	}
	}

	if ($options['checkAcl']==true) {
	if ($this->checkAcl($options)) {
	return $link;
	} else {
	return false;
    }
    } else {
    return $link;
    }
    }

    /**
      * Construct URL based on standard url helper
      * @param array $options
      * @return string
      */
    private function setUrl($options) {
	if ($options['controller']!=null) {

	$url=$this->url(array_merge(array(
	'module' => $options['module'],
	'controller' => $options['controller'],
	'action' => $options['action']),
	$options['params']),
	$options['router'],
	$options['reset']
	);

    } elseif ($options['url']!=null) {
	$url=$options['url'];
    } else {
	$url='#';
    }
    return $url;
    }

    /**
      * Check if current options are allowed by Zend_Acl
      * @param array $options
      * @return bool
      */
    private function checkAcl($options) {

    	$options['resource-prefix'] = $options['module'] . ':';
    		if ($options['resource'] == null AND $options['controller']!= null) {
    			$resource= //$options['resource-prefix'].
				$options['controller'];
    			$privilege = $options['action'];

    		} elseif ($options['resource']!= null AND $options['privilege']!= null) {
    			$resource = $options['resource'];
    			$privilege=$options['privilege'];
    		} else {
    			return false;
    		}

	    	$role = $this->getRole();

	    	if(is_null($options['module'])){
	    		return false;
	    	} else {
	    		$module = $options['module'] . ':';
	    	}
	    	if ($role == null){
	    	return false;
	    	}
                $acl = Zend_Registry::get('acl');

//			$resource = $options['resource-prefix'] . $resource;


	    	if ($acl->isAllowed($role, $resource, $privilege) == true) {
	    		return true;
			} else {
	    		return false;
			}
    }
}