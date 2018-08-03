<?php
define("MODE", 'WEB');
require_once dirname(dirname(__FILE__))."/config/config.php";

$db = Database::connect();

$smarty = Smarty::getInstance();
$smarty->template_dir = TEMPLATES;
$smarty->compile_dir = TEMPLATESC;
$session = Session::getInstance(MODE);

Config::setVar('CURRENT_DOMAIN_URL', ROOT);


/*
email: transfer@historycarprague.com
heslo: ivftY1Ty
*/

$imap   = imap_open('{imap.ebola.cz:993/imap/ssl}INBOX', 'transfer@historycarprague.com', 'ivftY1Ty');
$emails = imap_search($imap, 'ALL');
//d($imap, $emails);
if(is_array($emails)) {
	foreach($emails as $n) {
		$overview = imap_fetch_overview($imap, $n, 0);
		$message = imap_fetchbody($imap, $n, 1);
		$message = iconv('CP1250', 'UTF-8', base64_decode($message));
		imap_delete($imap, $n);
		preg_match('/symbol platby ([0-9]{8})/', $message, $arr);
		$vs = $arr[1];
		preg_match('/částka ([0-9]+),00 ([A-Z]{3})/', $message, $arr);
		$price = $arr[1];
		$currency = $arr[2];

		if(!$vs) {
			continue;
		}

		$f = fopen(LOCAL.'log/transfer.log', 'ab');
		fwrite($f, date('Y-m-d H:i:s').", vs: ".$vs.", amount: ".$price." ".$currency."\n");
		fclose($f);

		$query = "SELECT *
				  FROM ".Config::db_table_order()."
				  WHERE variable = ".$vs." AND
				  		status = 'new'";
		$order = $db->select($query, true);
		if($order) {
			switch($order['currency']) {
				case 'CZK': $lang_id = 1; break;
				case 'EUR': $lang_id = 2; break;
				default: $lang_id = 2; break;
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

			$query = "SELECT *
					  FROM ".Config::db_table_order_accessory()."
					  WHERE order_id = ".$order['order_id'];
			$accessories = $db->select($query);

			$program = Section::getInstance($order['program_id']);

			$smarty->assign(array(
			    'DESIGN' => DESIGN,
			    'DICTIONARY' => $dictionary,
			    'accessories' => $accessories,
				'program' => $program,
				'order' => $order,
				'HELPER' => new Helper(),
			));

			if($order['price'] == $price && $order['currency'] == $currency) {
				$query = "UPDATE ".Config::db_table_order()." SET status = 'paid'
					  	  WHERE order_id = ".$order['order_id'];
				$db->update($query);

				//email
				$html = $smarty->fetch("order-email-paid.html");
		
				$mail = new PHPMailer();
				$mail->FromName = "History Car Prague";
				$mail->From = $dictionary['order_email'];
				$mail->Subject = $dictionary['confirmation_subject'];
				$mail->Body = $html;
		
				$mail->AddEmbeddedImage(LOCAL.'app'.DS.'web1'.DS.'design'.DS.'email-header.jpg', "image_header", "", "base64", "image/jpeg");
				$mail->AddEmbeddedImage(LOCAL.'app'.DS.'web1'.DS.'design'.DS.'email-footer.jpg', "image_footer", "", "base64", "image/jpeg");
		
				//pridam eticket
				$html = $smarty->fetch('pdf-eticket.html');
				$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
				$mpdf->WriteHTML($html);
				$mpdf->Output(LOCALFILES."eticket.pdf", "F");
				$mail->AddAttachment(LOCALFILES."eticket.pdf", "", "base64", "application/pdf");
		
				//pridam slevove kupony
				$html = $smarty->fetch('pdf-vouchers.html');
				$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
				$mpdf->WriteHTML($html);
				$mpdf->Output(LOCALFILES."vouchers.pdf", "F");
				$mail->AddAttachment(LOCALFILES."vouchers.pdf", "", "base64", "application/pdf");
		
				if($program->get('value', 'venice')) {
					//pridam prazske benatky
					$html = $smarty->fetch('pdf-venice.html');
					$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
					$mpdf->WriteHTML($html);
					$mpdf->Output(LOCALFILES."venice.pdf", "F");
					$mail->AddAttachment(LOCALFILES."venice.pdf", "", "base64", "application/pdf");		
				}
	
			   	$mail->ClearAddresses();
				$mail->AddAddress($order['email']);
			   	$mail->Send();
/*
			   	$mail->ClearAddresses();
				$mail->AddAddress('dohnal@gramonet.com');
			   	$mail->Send();
*/
			//spatna castka
			} else {
				$mail = new PHPMailer();
				$mail->FromName = "History Car Prague";
				$mail->From = $dictionary['order_email'];
				$mail->Subject = $dictionary['confirmation_subject'];
				$mail->Body = $dictionary['order_status_badamount'];

			   	$mail->ClearAddresses();
				$mail->AddAddress($order['email']);
			   	$mail->Send();

			   	$mail->ClearAddresses();
				$mail->AddAddress('tours@historycarprague.com');
			   	$mail->Send();
			}
		}
		//d($overview, $message, $vs, $price, $currency, $order);
	}
}

imap_expunge($imap);
imap_close($imap);

//d($imap, $emails);
//d($imap, $emails, imap_mime_header_decode(imap_utf8($overview[0]->from)));
