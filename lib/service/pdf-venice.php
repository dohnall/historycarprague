<?php
define("MODE", 'WEB');
require_once dirname(dirname(__FILE__))."/config/config.php";

$lang_id = isset($_GET['lang']) && in_array($_GET['lang'], array(1, 2)) ? $_GET['lang'] : 1;

$smarty = Smarty::getInstance();
$smarty->template_dir = TEMPLATES;
$smarty->compile_dir = TEMPLATESC;

$db = Database::connect();
$session = Session::getInstance(MODE);
$session->lang_id = $lang_id;

Config::setVar('USER_TIMEZONE', DEFAULT_TIMEZONE);
Config::setVar('CURRENT_DOMAIN_URL', ROOT);

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
    'DESIGN' => DESIGN,
    'HELPER' => new Helper(),
    'DICTIONARY' => $dictionary,
));

$html = $smarty->fetch('pdf-venice.html');
$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
$mpdf->WriteHTML($html);
$mpdf->Output("vouchers.pdf", "D");
