<?php
define("MODE", 'WEB');
require_once dirname(dirname(__FILE__))."/config/config.php";

$order_id = isset($_GET['order']) && is_numeric($_GET['order']) ? $_GET['order'] : 0;

$smarty = Smarty::getInstance();
$smarty->template_dir = TEMPLATES;
$smarty->compile_dir = TEMPLATESC;

$db = Database::connect();
$session = Session::getInstance(MODE);

Config::setVar('USER_TIMEZONE', DEFAULT_TIMEZONE);
Config::setVar('CURRENT_DOMAIN_URL', ROOT);

$query = "SELECT *
		  FROM ".Config::db_table_order()."
		  WHERE order_id = ".$order_id;
$order = $db->select($query, true);

if($order) {
	$query = "SELECT *
			  FROM ".Config::db_table_order_accessory()."
			  WHERE order_id = ".$order['order_id'];
	$accessories = $db->select($query);
	$program = Section::getInstance($order['program_id']);

	if($order['currency'] == 'CZK') {
		$lang_id = 1;
	} elseif($order['currency'] == 'EUR') {
		$lang_id = 2;
	} else {
		$lang_id = 1;
	}

	$session->lang_id = $lang_id;

	$lang = new Lang($lang_id);
	$lang->load();
	$langData = $lang->get();
	$langCode = $langData['item']['code'];
	Config::setVar('CURRENT_LANG_CODE', $langCode);
	
	if(file_exists(STATOR.$langCode.".ini")) {
		$smarty->configLoad(STATOR.$langCode.".ini");
		$dictionary = $smarty->getConfigVars();
	} else {
		$dictionary = array();
	}

	$smarty->assign(array(
		'accessories' => $accessories,
		'program' => $program,
		'order' => $order,
	    'DESIGN' => DESIGN,
	    'HELPER' => new Helper(),
	    'DICTIONARY' => $dictionary,
	));

	$html = $smarty->fetch('pdf-eticket.html');
	$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
	$mpdf->WriteHTML($html);
	$mpdf->Output("eticket.pdf", "D");
}
