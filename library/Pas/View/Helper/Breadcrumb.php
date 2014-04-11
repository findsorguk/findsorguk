<?php

/**
 * This class is to display the breadcrumbs
 * Load of rubbish, needs a rewrite
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2008
 * @todo change the class to use zend_navigation
*/
class Pas_View_Helper_Breadcrumb
	extends Zend_View_Helper_Abstract {


	/** Build the breadcrumbs
	 * @access public
	 * @return string $html
	 */
	public function breadCrumb() {
	$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
	$l_m = $module;

	switch ($module) {
		case 'getinvolved':
			$nicemodule = 'Getting involved';
			break;
		case 'admin':
			$nicemodule = 'Administration centre';
			break;
		case 'conservation':
			$nicemodule = 'Conservation advice';
			break;
		case 'research':
			$nicemodule = 'research';
			break;
		case 'treasure':
			$nicemodule = 'Treasure Act';
			break;
		case 'news':
			$nicemodule = 'news &amp; reports';
			break;
		case 'events':
			$nicemodule = 'events';
			break;
		case 'info':
			$nicemodule = 'Site information';
			break;
		case 'romancoins':
			$nicemodule = 'Roman Numismatic guide';
			break;
		case 'greekromancoins':
			$nicemodule = 'Greek and Roman Provincial Numismatic guide';
		 	break;
		case 'api':
			$nicemodule = 'Application programming interface';
			break;
		case 'bronzeage':
			$nicemodule = 'Bronze Age object guide';
			break;
		case 'staffshoardsymposium':
			$nicemodule  = 'Staffordshire Hoard Symposium';
			break;
		case 'romancoins':
			$nicemodule = 'Roman coin guide';
			break;
		case 'database':
			$nicemodule = 'Finds database';
			break;
		case 'medievalcoins':
			$nicemodule = 'Medieval coin guide';
			break;
		case 'ironagecoins':
			$nicemodule = 'Iron Age coin guide';
			break;
		case 'earlymedievalcoins':
			$nicemodule = 'Early Medieval coin guide';
			break;
		case 'greekandromancoins':
			$nicemodule = 'Greek &amp; Roman Provincial coin guide';
			break;
		case 'byzantinecoins':
			$nicemodule = 'Byzantine coin guide';
			break;
		case 'postmedievalcoins':
			$nicemodule = 'Post Medieval coin guide';
			break;
		case 'getinvolved':
			$nicemodule = 'Get involved';
			break;
		case 'contacts':
			$nicemodule = 'Scheme contacts';
			break;
		case 'events':
			$nicemodule = 'Scheme events';
			break;
		case 'secrettreasures':
			$nicemodule = 'Britain\'s Secret Treasures';
			break;
		 default:
		 	$nicemodule = $module;
		 break;
	}

	$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
	$l_c = strtolower($controller);

	switch ($controller) {
		case 'error':
			$nicename = 'Error manager';
			break;
		case 'users':
			$nicename = 'Users\' section';
			break;
		case 'admin':
			$nicename = 'Site Administration';
			break;
		case 'britishmuseum':
			$nicename = 'British Museum events';
			break;
		case 'datatransfer':
			$nicename = 'Data transfer';
			break;
		case 'info':
			$nicename = 'Event information';
			break;
		case 'foi':
			$nicename = 'Freedom of Information Act';
			break;
		case 'her':
			$nicename = 'Historic Enviroment Signatories';
			break;
		case 'myscheme':
			$nicename = 'My scheme';
			break;
		case 'vanarsdelltypes':
			$nicename = 'Van Arsdell Types';
			break;
		case 'smr':
			$nicename = 'Scheduled Monuments';
			break;
		case 'osdata':
			$nicename = 'Ordnance Survery Open Data';
			break;
		case 'theyworkforyou':
			$nicename = 'Data from TheyWorkForYou';
			break;
		default:
			$nicename = $controller;
			break;
	}


	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	$l_a = strtolower($action);

	switch ($action) {
		case 'mapsearchresults':
			$nicenameaction = 'Map search results';
			break;

		case 'countystats':
			$nicenameaction = 'County statistics';
			break;
		case 'regionalstats':
			$nicenameaction = 'Regional statistics';
			break;
		case 'institutionstats':
			$nicenameaction = 'Institutional statistics';
			break;
		case 'numismaticsearch':
			$nicenameaction = 'Numismatic search';
			break;
		case 'profile':
			$nicenameaction = 'Profile details';
			break;
		case 'add':
			$nicenameaction = 'Create new';
			break;
		case 'myresearch':
			$nicenameaction = 'My research agendas';
			break;
		case 'myinstitution':
			$nicenameaction = 'My institution\'s finds';
			break;
		case 'forgot':
			$nicenameaction = 'Reset forgotten password';
			break;
		case 'login':
			$nicenameaction = 'Login to Beowulf';
			break;
		case 'advanced':
			$nicenameaction = 'Advanced search interface';
			break;
		case 'basicsearch':
			$nicenameaction = 'Basic what/where/when search interface';
			break;
		case 'searchresults':
			$nicenameaction = 'Search results';
			break;
		case 'organisations':
			$nicenameaction = 'Registered Organisations';
			break;
		case 'addfindspot':
			$nicenameaction = 'Add a findspot';
			break;
		case 'editfindspot':
			$nicenameaction = 'Edit findspot';
			break;
		case 'editpublication':
			$nicenameaction = 'Edit a publication\'s details';
			break;
		case 'publication':
			$nicenameaction = 'Publication\'s details';
			break;
			case 'addromancoin':
			$nicenameaction = 'Add Roman numismatic data';
			break;
			case 'romannumismatics':
			$nicenameaction = 'Roman numismatic search';
			break;
			case 'record':
			$nicenameaction = 'Object/coin record';
			break;
			case 'emperorbios':
			$nicenameaction = 'Emperor biographies';
			break;
			case 'postmednumismatics':
			$nicenameaction ='Post Medieval numismatic search';
			break;
			case 'project':
			$nicenameaction = 'Project details';
			break;
			case 'hers':
			$nicenameaction = 'HER offices signed up';
			break;
			case 'ruler':
			$nicenameaction = 'Ruler details';
			break;
			case 'error':
			$nicenameaction = 'Error details';
			break;
			case 'errorreport':
			$nicenameaction = 'Submit an error';
			break;
			case 'oneto50k':
			$nicenameaction = 'One to 50K entry';
			break;
			case 'myfinds':
			$nicenameaction = 'Finds I have recorded';
			break;
			case 'myimages':
			$nicenameaction = 'Images I have added';
			break;
			case 'mp':
			$nicenameaction = 'Member of Parliament';
			break;
			case 'recordedbyflos':
			$nicenameaction = 'Recorded by an FLO';
			break;
			case 'accountproblem':
			$nicenameaction = 'Problem with your account';
			break;
			case 'inaset':
			$nicenameaction = 'In a set';
			break;
			default:
			$nicenameaction = $action;
			break;
	}

	// HomePage = No Breadcrumb
	if($l_m == 'default' && $l_c == 'index' && $l_a == 'index'){
	return;
	}

	// Get our url and create a home crumb
	$url = $this->view->baseUrl();
	$homeLink = "<a href='{$url}/' title='Scheme website home page'>Home</a>";
	// Start crumbs
	$crumbs = $homeLink . " &raquo; ";

	// If our module is default
	if($l_m == 'default') {

	if($l_a == 'index'){
	$crumbs .= ucfirst($nicename);
	} else {
	$crumbs .= " <a href='{$url}/{$controller}/' title='Return to {$nicename} section'>$nicename</a> &raquo; $nicenameaction ";


	}
	} else {
	// Non Default Module
	if($l_c == 'index' && $l_a == 'index') {
	$crumbs .= ucfirst($nicemodule);
	} else {
	$crumbs .= "<a href='{$url}/{$module}/' title='Return to $nicemodule home'>" . ucfirst($nicemodule) . "</a> &raquo; ";

	if($l_a == 'index') {
	$crumbs .= ucfirst($nicename);
	} else {
	$crumbs .= " <a href='{$url}/{$module}/{$controller}/' title='Return to $nicename home'> " . ucfirst($nicename) . "</a> &raquo; " . ucfirst($nicenameaction);
	}
	}

	}
	return $crumbs;
	}

}
