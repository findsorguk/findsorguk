<?php
/**
 * A view helper for creating a url slug
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_SearchUrl extends Zend_View_Helper_Abstract  {


	public function buildUrlString($data){
	$parameters = $this->getParameters($data);
	if(!is_null($parameters['route'])){
	$route = $parameters['route'];
	} else {
	$route = 'default';
	}
	$name = $parameters['nicename'];
	unset($parameters['route']);
	unset($parameters['nicename']);
	$html = '';
	$html .= 'This is a result from the <a href="'. $this->view->url($parameters,$route,true) . '" ';
	$html .= 'title="View result">' . $name . '</a> section &raquo;';
	return $html;
	}

	public function getParameters($data){
		$section = $data['section'];
		$parameters = NULL;
		switch($section){
			case($section === 'news'):
				$parameters['module'] = 'news';
				$parameters['controller'] = 'stories';
				$parameters['action'] = 'article';
				$parameters['id'] = $data['id'];
				$parameters['nicename'] = 'news';
			break;
			case($section === 'profiles'):
				$parameters['module'] = 'contacts';
				$parameters['controller'] = 'staff';
				$parameters['action'] = 'profile';
				$parameters['id'] = $data['id'];
				$parameters['nicename'] = 'staff profiles and contact details';
			break;
			case($section === 'research'):
				$parameters['module'] = 'research';
				$parameters['controller'] = 'projects';
				$parameters['action'] = 'project';
				$parameters['id'] = $data['id'];
				$parameters['nicename'] = 'research';
			break;
			case($section === 'events'):
				$parameters['module'] = 'events';
				$parameters['controller'] = 'info';
				$parameters['action'] = 'index';
				$parameters['id'] = $data['id'];
				$parameters['nicename'] = 'events';
			break;
			case($section === 'romancoins'):
				$parameters['module'] = 'romancoins';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'page';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'romanguides';
				$parameters['nicename'] = 'Roman coin guides';
			break;
			case($section === 'postmedievalcoins'):
				$parameters['module'] = 'postmedievalcoins';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'page';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'postmedievalguides';
				$parameters['nicename'] = 'post medieval coin guide';
			break;
			case($section === 'medievalcoins'):
				$parameters['module'] = 'medievalcoins';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'page';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'medievalguides';
				$parameters['nicename'] = 'medieval coin guide';
			break;
			case($section === 'byzantinecoins'):
				$parameters['module'] = 'byzantinecoins';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'page';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'byzantineguides';
				$parameters['nicename'] = 'Byzantine coin guide';
			break;
			case($section === 'greekromancoins'):
				$parameters['module'] = 'greekromancoins';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'page';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'greekromanguides';
				$parameters['nicename'] = 'Greek and Roman provincial coin guide';
			break;
			case($section === 'earlymedievalcoins'):
				$parameters['module'] = 'earlymedievalcoins';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'page';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'earlymedievalguides';
				$parameters['nicename'] = 'early medieval coin guide';
			break;
			case($section === 'ironagecoins'):
				$parameters['module'] = 'ironagecoins';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'page';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'ironageguides';
				$parameters['nicename'] = 'Iron Age coin guide';
			break;
			case($section === 'bronzeage'):
				$parameters['module'] = 'bronzeage';
				$parameters['controller'] = 'objects';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'bronzeage';
				$parameters['nicename'] = 'Bronze Age guide';
			break;
			case($section === 'info'):
				$parameters['module'] = 'info';
				$parameters['controller'] = 'articles';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'i';
				$parameters['nicename'] = 'general information';
			break;
			case($section === 'staffs'):
				$parameters['module'] = 'staffshoardsymposium';
				$parameters['controller'] = 'papers';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'staffs';
				$parameters['nicename'] = 'Staffordshire Hoard symposium papers';
			break;
			case($section === 'conservation'):
				$parameters['module'] = 'conservation';
				$parameters['controller'] = 'advice';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'c';
				$parameters['nicename'] = 'Conservation guide';
			break;
			case($section === 'frg'):
				$parameters['module'] = 'guide';
				$parameters['controller'] = 'torecording';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'frg';
				$parameters['nicename'] = 'Volunteer recording guide';
			break;
			case($section === 'treports'):
				$parameters['module'] = 'treasure';
				$parameters['controller'] = 'reports';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'treps';
				$parameters['nicename'] = 'Treasure Annual Reports';
			break;
			case($section === 'reports'):
				$parameters['module'] = 'news';
				$parameters['controller'] = 'reports';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'reps';
				$parameters['nicename'] = 'Scheme Annual Reports';
			break;
			case($section === 'treasure'):
				$parameters['module'] = 'treasure';
				$parameters['controller'] = 'advice';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 't';
				$parameters['nicename'] = 'Treasure advice';
			break;
			case($section === 'getinvolved' ):
				$parameters['module'] = 'getinvolved';
				$parameters['controller'] = 'guides';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'guides';
				$parameters['nicename'] = 'getting involved';
			break;
			case($section === 'vacancies'):
				$parameters['module'] = 'getinvolved';
				$parameters['controller'] = 'vacancies';
				$parameters['action'] = 'vacancy';
				$parameters['id'] = $data['id'];
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'staff vacancies';
			break;
			case($section === 'publications'):
				$parameters['module'] = 'getinvolved';
				$parameters['controller'] = 'publications';
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'our publications';
			break;
			case($section === 'reviews'):
				$parameters['module'] = 'news';
				$parameters['controller'] = 'reviews';
				$parameters['action'] = 'index';
				$parameters['slug'] = $data['slug'];
				$parameters['route'] = 'r';
				$parameters['nicename'] = 'commissioned reviews';
			break;
			case($section === 'databasehelp'):
				$parameters['module'] = 'help';
				$parameters['controller'] = 'database';
				$parameters['action'] = 'topic';
				$parameters['id'] = $data['id'];
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'database specific help';
			break;
			case($section === 'help'):
				$parameters['module'] = 'help';
				$parameters['controller'] = 'site';
				$parameters['action'] = 'topic';
				$parameters['id'] = $data['id'];
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'general site help';
			break;
			case($section === 'database'):
				$parameters['module'] = 'database';
				$parameters['controller'] = 'index';
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'Database';
			break;
			case($section === 'index'):
				$parameters['module'] = 'default';
				$parameters['controller'] = 'index';
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'home page';
			break;
			case($section === 'datatransfer'):
				$parameters['module'] = 'research';
				$parameters['controller'] = 'datatransfer';
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'research and data transfer';
			break;
			case($section === 'oai'):
				$parameters['module'] = 'database';
				$parameters['controller'] = 'oai';
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'OAI documentation';
			break;
			case($section === 'api'):
				$parameters['module'] = 'api';
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'API documentation';
			break;
                        case($section === 'secret'):
				$parameters['module'] = 'secrettreasures';
				$parameters['route'] = 'default';
				$parameters['nicename'] = 'Britain\'s Secret Treasures';
			break;
			default:
				$parameters['module'] = 'contacts';
				$parameters['controller'] = 'staff';
				$parameters['action'] = 'profiles';
				$parameters['id'] = $data['id'];
				$parameters['route'] = 'default';
				$parameters['nicename'] = $data['section'];
		}
			return $parameters;
		}

	public function buildUrl($data){
	$parameters = $this->getParameters($data);
	if(!is_null($parameters['route'])){
	$route = $parameters['route'];
	} else {
	$route = 'default';
	}
	$name = $parameters['nicename'];
	unset($parameters['route']);
	unset($parameters['nicename']);
	return $this->view->url($parameters,$route,true);
	}

	public function searchUrl($data, $rich = true){
	if(is_array($data) && $rich === true){
		return $this->buildUrlString($data);
	} else if (is_array($data) && $rich === false){
		return $this->buildUrl($data);
	} else {
		return false;
	}
	}
}