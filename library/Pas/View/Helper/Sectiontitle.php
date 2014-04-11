<?php 
/** View helper for expanding section titles in admin backend
 * 
 * @author dpett
 *
 */
class Pas_View_Helper_Sectiontitle 
	extends Zend_View_Helper_Abstract {

public function sectiontitle($string = NULL) {
	switch ($string) {
		case 'ironagecoins':
			$sec = 'Iron Age coin guide';
			break;
		case 'api':
			$sec = 'API documentation';
			break;
		case 'medievalcoins':
			$sec = 'Medieval coin guide';
			break;
		case 'earlymedievalcoins':
			$sec = 'Early Medieval coin guide';
			break;
		case 'postmedievalcoins':
			$sec = 'Post Medieval coin guide';
			break;
		case 'news':
			$sec = 'News';
			break;
		case 'events':
			$sec = 'Events';
			break;
		case 'treasure':
			$sec = 'Treasure Act';
			break;
		case 'conservation':
			$sec = 'Conservation guide';
			break;
		case 'romancoins':
			$sec = 'Roman coin guide';
			break;
		case 'getinvolved':
			$sec = 'Get involved';
			break;
		case 'byzantinecoins':
			$sec = 'Byzantine coin guide';
			break;
		case 'greekromancoins':
			$sec = 'Greek and Roman coin guide';
			break;
		case 'info':
			$sec = 'Site information';
			break;
		case 'reviews':
			$sec = 'Scheme reviews';
			break;
		case 'reports':
			$sec = 'Annual reports';
			break;
		case 'research':
			$sec = 'Research';
			break;
		case 'datatransfer':
			$sec = 'Data transfer';
			break;
		case 'help':
			$sec = 'Site help';
			break;
		case 'databasehelp':
			$sec = 'Database help';
			break;
		case 'publications':
			$sec = 'Scheme publications';
			break;
		case 'staffs':
			$sec = 'Staffs Hoard symposium';
			break;
		case 'bronzeage':
			$sec = 'Bronze Age object guide';
			break;
		case 'treasure':
			$sec = 'Treasure';
			break;
		case 'treports':
			$sec = 'Treasure reports';
			break;
		case 'frg':
			$sec = 'Finds Recording Guide';
			break;
		case 'secret':
			$sec = 'Britain\'s Secret Treasures';
			break;
		default:
			$sec = 'Index';
			break;
	}		
	
	return $sec;
 
}

}
