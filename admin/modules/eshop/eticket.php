<?php
$order_id = isset($_GET['order']) && is_numeric($_GET['order']) ? $_GET['order'] : 0;

$query = "SELECT *
		  FROM ".Config::db_table_order()."
		  WHERE order_id = ".$order_id;
$order = $this->db->select($query, true);

if($order) {
	$query = "SELECT *
			  FROM ".Config::db_table_order_accessory()."
			  WHERE order_id = ".$order['order_id'];
	$accessories = $this->db->select($query);
	$program = Section::getInstance($order['program_id']);

	if($order['currency'] == 'CZK') {
		$lang_id = 1;
	} elseif($order['currency'] == 'EUR') {
		$lang_id = 2;
	} else {
		$lang_id = 1;
	}

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
		'accessories' => $accessories,
		'program' => $program,
		'order' => $order,
		'hash' => $hash,
	    'DESIGN' => DESIGN,
	    'HELPER' => new Helper(),
	    'DICTIONARY' => $dictionary,
	));

	$html = $this->smarty->fetch('pdf-eticket.html');
	$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
	$mpdf->WriteHTML($html);
	$mpdf->Output("eticket.pdf", "D");
}
exit;
