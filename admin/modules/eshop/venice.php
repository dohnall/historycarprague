<?php
$lang_id = isset($_GET['lang']) && in_array($_GET['lang'], array(1, 2)) ? $_GET['lang'] : 1;

$this->smarty->template_dir = TEMPLATES;
$this->smarty->compile_dir = TEMPLATESC;

$lang = new Lang($lang_id);
$lang->load();
$langData = $lang->get();
$langCode = $langData['item']['code'];
Config::setVar('CURRENT_LANG_CODE', $langCode);

if(file_exists(STATOR.$langCode.".ini")) {
	$this->smarty->configLoad(STATOR.$langCode.".ini");
	$dictionary = $this->smarty->getConfigVars();
} else {
	$dictionary = array();
}

$this->smarty->assign(array(
    'DESIGN' => DESIGN,
    'HELPER' => new Helper(),
    'DICTIONARY' => $dictionary,
));

$html = $this->smarty->fetch('pdf-venice.html');
$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
$mpdf->WriteHTML($html);
$mpdf->Output("venice.pdf", "D");
