<?php
class ErrorController extends Pas_Controller_Action_Admin {

	protected $_log;

	public function init() {
		$this->_log = Zend_Registry::get('log');
		$this->_helper->_acl->allow(NULL);
        $this->_helper->layout()->setLayout('database');
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
//		$this->_cs = $this->_helper->contextSwitch();
//    	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
//    	$this->_cs->setAutoDisableLayout(true)
//            ->addActionContext('error', array('json', 'xml'))
//            ->initContext();
		}
		
	private static function addPadding ($number) {
		$formattedNumber = str_pad($number, 5, '_', STR_PAD_RIGHT);
		return str_replace('_', '&nbsp;', $formattedNumber );
	}	
	
	public function whois() {
	$user = new Pas_User_Details();
	if(is_null($user->getPerson()->username)) {
	$string = 'Public user';
	} else {
	$name = $user->getPerson()->fullname;
	$account = $user->getPerson()->username;
	$string = $name . ' with the account username of ' . $account;
	}	
	return $string; 
	}
	
	protected function _mailData() {
	$details = array();
	$details['username'] = $this->whois();
	$errors = $this->_getParam('error_handler');
	$details['file'] = get_class($errors['exception']);
	$details['ip'] = $_SERVER['REMOTE_ADDR'];
	$details['method'] = $_SERVER['REQUEST_METHOD'];
	$details['agent'] = $_SERVER['HTTP_USER_AGENT'];
	$details['referrer'] = $_SERVER['HTTP_REFERER'];
	$details['url'] = $this->view->CurUrl();
	if($errors){
	$details['exception'] = $errors->exception->getMessage();
	$details['type'] = $errors['type'];
	$details['code'] = $errors['exception']->getCode();
	$details['exceptionDetails']= $errors->exception;
	}
	
	return $details;
	}
	
	
	public function sendEmail() {
	$to[] = array('name' => 'Daniel Pett', 'email' => 'dpett@britishmuseum.org');
	$cc[] = array('name' => 'Daniel Pett', 'email' => 'danielpett@gmail.com');
	$from[] = array('name' => 'The Portable Antiquities Server', 'email' => 'info@finds.org.uk');
	$assignData = array_merge($this->_mailData(),$to['0']);
	return $this->_helper->mailer($assignData, 'serverError', $to, $cc, $from, null,null);
	}
	
	private static function formatArgValues ($args) {
		$values = array();
		foreach($args as $arg) {
			if (is_object($arg)) {
				$values[] = get_class($arg);
			} elseif (is_null($arg)) {
				$values[] = 'NULL';
			} elseif (is_array($arg)) {
				$values[] = 'Array('.count($arg).')';
			} elseif (is_string($arg)) {
				$values[] = "'$arg'";
			} else {
				$values[] = (string) $arg;
			}
		}
		return implode(', ', $values);
	}
	
	private static function generateCodeBlock ($errorLine, $filePath) {
		$lines = explode( '<br />', highlight_file($filePath, true) );
		$errorID = '';
		for($n = 0; $n < count($lines); $n++) {
			$lineNumber = $n+1;
			$paddedNumber =  self::addPadding( $lineNumber );
			$errorClass = '';
			list($errorClass, $errorID) = ($lineNumber == $errorLine) ? array('errorLine', md5( $errorLine.$filePath ) ) : array('',$errorID);
			$lines[ $n ] = "<span class=\"lineNumbers $errorClass\" id=\"$errorID\">$paddedNumber</span>".$lines[ $n ];
		}
		return "<div class=\"codeFile\" errorid=\"$errorID\">".implode("<br />\n", $lines).'</div>';
	}	
	
	public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
	
	public function indexAction()
	{

	}	
	public function errorAction($extended =false) 
    { 
    	
        // Ensure the default view suffix is used so we always return good 
        // content
        $this->_helper->viewRenderer->setViewSuffix('phtml');
        // Grab the error object from the request
        $errors = $this->_getParam('error_handler'); 
//        Zend_Debug::dump(get_class($errors['exception']));
		if($errors) {
		$data = array();
		$data['errorMessage'] = $errors['exception']->getMessage();
		$data['errorType'] = $errors['type'];
		$data['errorCode'] = $errors['exception']->getCode();
		$data['errorFilePath'] = $errors['exception']->getFile();
		$data['errorLineNumber'] = $errors['exception']->getLine();
		$data['errorLineNumberFormatted'] = self::addPadding( $errors['exception']->getLine() );
		$data['traceStack'] = array();
		foreach( $errors['exception']->getTrace() as $trace) {
			if ($extended) {
				$trace['lineNumberFormatted'] = self::addPadding( $trace['line'] );
				$trace['codeBlock'] = self::generateCodeBlock( $trace['line'], $trace['file'] );
			}
			$data['traceStack'][] = $trace;
		}
		
			$compiledTrace = '';
		foreach($data['traceStack'] as $trace) {
			$compiledTrace .= '<li class="codeBlock">'."\n";
			if(!array_key_exists('line', $trace)){
			if ($extended) {
				$compiledTrace .= '<div class="filePath"><a class="openLink" href="javascript://">open</a>'.$trace['file'].'</div>'."\n";
			} else {
				$compiledTrace .= '<div class="filePath"><span class="openLink">'.$trace['line'].'</span> '.$trace['file'].'</div>'."\n";
			}
			$compiledTrace .= '<div class="functionCall">'.$trace['class'].'->'.$trace['function'].'('.self::formatArgValues($trace['args']).')</div>'."\n";
			if ($extended) {
				$compiledTrace .= $trace['codeBlock']."\n";
			}
			}
			$compiledTrace .= '</li>'."\n";
		}
		
		//Zend_debug::dump($compiledTrace);
     
      switch ($errors->type) {
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        // 404 error -- controller or action not found
        $this->getResponse()->setHttpResponseCode(404);
        $this->renderScript('error/notfound.phtml');
        $this->view->message = 'Page not found';
        $this->view->code  = 404;
        if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER) {
		$this->view->info = sprintf(
                      'Unable to find controller "%s" in module "%s"',
                      $errors->request->getControllerName(),
                      $errors->request->getModuleName()
                    );
        }
        if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION) {
		$this->renderScript('error/notfound.phtml');
		$this->view->code  = 404;
		$this->view->message = sprintf(
                      'Unable to find action "%s" in controller "%s" in module "%s"',
                      $errors->request->getActionName(),
                      $errors->request->getControllerName(),
                      $errors->request->getModuleName()
                    );
		$priority = Zend_Log::NOTICE;
		$log = $this->getLog();
        if ($log) {
            $log->log($this->view->message . ' ' . $errors->exception, $priority, $errors->exception);
            $log->log('Request Parameters' . ' ' . $errors->request->getParams(), $priority, $errors->request->getParams());
        }        
		$this->view->compiled = $compiledTrace;

        }
		break;
	  
		case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
	  	switch (get_class($errors['exception'])) {
                    
	  				case 'Pas_Exception_NotAuthorised' :
						$this->getResponse()->setHttpResponseCode(401);
						$this->view->message = 'This record falls outside your access levels. If you contact us, 
					        we can let you know when you can see it. This normally means the record is not on public view.';
						$this->view->info  = $errors->exception;
						$this->view->code  = 403;
//						$this->sendEmail();
						$this->view->headTitle('Not authorised.');
                    break;
                    
					case 'Pas_Exception_BadJuJu':
					        $this->getResponse()->setHttpResponseCode(500);
							$this->view->message = 'There has been an internal server error!';
							$this->view->info  = $errors->exception;
							$this->view->code  = 500;
							$this->view->compiled = $compiledTrace;
							$this->view->headTitle('bad juju.');
	
                    break;
					
                    case 'Pas_Exception_Param':
					        $this->getResponse()->setHttpResponseCode(500);
							$this->view->message = 'The url you used is missing a parameter';
							$this->view->info  = $errors->exception;
							$this->view->code  = 500;
							$this->view->compiled = $compiledTrace;
							$this->view->headTitle('A parameter is missing from your url.');
                    break;
                    
					case 'Zend_Db_Statement_Exception' :
				        $this->getResponse()->setHttpResponseCode(503);
						$this->view->info  = $errors->exception;
                        $this->view->code = 503;
						$this->view->message = 'There has been an error with our SQL (that is the code that 
						powers database queries). Our fault entirely. This has been logged and sent to admin.' ;
						$this->view->compiled = $compiledTrace;
						$this->sendEmail();
						$this->view->headTitle('SQL error returned');	
                    break;
					
                    case 'Zend_Db_Adapter_Exception':
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->code = 500;
						$this->view->message = 'Server has gone away (usually being restarted or processes killed.)';
						$this->sendEmail();
						$this->view->headTitle('The database is down.');
					break;
					
					case 'Zend_Db_Table_Exception':
						if(preg_match("/primary/i",$errors->exception->getMessage())){
						$cache = Zend_Registry::get('cache');
						$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->message = 'Cache file needs a clean! Please try again.';
						$this->view->code = 500;
						$this->sendEmail();	
						$this->view->headTitle('Cache file needs cleaning - retry.');
						}
					break;
					
					case 'Zend_Db_Statement_Mysqli_Exception':
					 	$this->getResponse()->setHttpResponseCode(500);
					 	$this->view->code = 500;
						$this->view->message = 'Server has gone away';
						$this->sendEmail();
						$this->view->headTitle('The server adapter has gone away.');
					break;
					
					case 'PDOException':
					 	$this->getResponse()->setHttpResponseCode(500);
					 	$this->view->code = 500;
						$this->view->message = 'PDO exception has been caught';
						$this->sendEmail();
						$this->view->headTitle('PDO data error.');
					break;
					
					case 'Pas_Solr_Exception':
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->code = 500;
						$this->view->message = 'The search handler has an error';
						$this->sendEmail();
						$this->view->headTitle('The search engine has an error.');
					break;
					
					case 'Solarium_Client_HttpException':
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->code = 500;
						$this->view->message = 'Search engine error: The server is not responding, but will be back shortly';
						$this->sendEmail();
						$this->view->headTitle('Problem with search engine');
					break;
					
					case 'Zend_Loader_PluginLoader_Exception':
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->code = 500;
						$this->view->message = 'Plugin not found';
						$this->sendEmail();
						$this->view->headTitle('Plugin not found');
						break;
					
					case 'Zend_View_Exception' :
				        $this->getResponse()->setHttpResponseCode(500);
                        $this->view->code =500;
						$this->view->message = 'Rendering of view error.';
						$this->view->compiled = $compiledTrace;
						$this->sendEmail();
						$this->view->headTitle('View cannot be displayed.');
                    break;
			}
			break;
	     	default:
	        // application error
	        $this->getResponse()->setHttpResponseCode(500);
	        $this->view->message = 'Application error';
	        $this->view->code  = 500;
	        $this->sendEmail();
	        $this->view->info  = $errors->exception;
	        $this->view->headTitle('A generic application error has been made');
			break;      
    }

        
        // pass the actual exception object to the view
        $this->view->exception = $errors->exception; 
        
        // pass the request to the view
        $this->view->request   = $errors->request; 
		//Zend_Debug::dump($errors->type);
		} else {
		$this->_redirect('/error/notauthorised');
		}
    } 

	public function notauthorisedAction() {
        $this->getResponse()->setHttpResponseCode(401);
		$this->_helper->layout()->setLayout('database');
		$this->view->headTitle('None shall pass');
		$this->view->message = 'You are not authorised to view this resource';
        $this->view->code  = 401;
        
	}				
	
	public function accountproblemAction(){
	$this->sendEmail();	
	}
	
	public function databasedownAction(){
		
	}
	
	public function accountconnectionAction(){
	$this->sendEmail();	
	}
	
	public function downtimeAction() {
	}
	
}
