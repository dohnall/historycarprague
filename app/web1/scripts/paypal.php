<?php
$hash = isset($_GET['id']) ? $_GET['id'] : "";

$query = "SELECT *
		  FROM ".Config::db_table_order()."
		  WHERE MD5(variable) = '".mysql_real_escape_string($hash)."'";
$order = $this->db->select($query, true);

if($order) {
	$query = "SELECT *
			  FROM ".Config::db_table_order_accessory()."
			  WHERE order_id = ".$order['order_id'];
	$accessories = $this->db->select($query);
	$program = Section::getInstance($order['program_id']);

	$this->smarty->assign(array(
		'accessories' => $accessories,
		'program' => $program,
		'order' => $order,
		'hash' => $hash,
	));

	if(isset($_POST['ipn_track_id'])) {
		$f = fopen(LOCAL.'log/paypal.log', 'ab');
		fwrite($f, date('Y-m-d H:i:s').", POST: ".serialize($_POST)."\n");
		fclose($f);
		
		//$_POST['payment_status'] == 'Completed' &&
		if( $_POST['receiver_id'] == '7SEHBCZB5TM9U' &&
			$_POST['mc_gross'] == $order['price'].'.00' &&
			$_POST['mc_currency'] == $order['currency'] &&
			$_POST['custom'] == $order['variable']) {
			
			$query = "UPDATE ".Config::db_table_order()." SET status = 'paid'
					  WHERE order_id = ".$order['order_id'];
			$this->db->update($query);
			$order['status'] = 'paid';

			$this->smarty->assign(array(
	    		'DESIGN' => DESIGN,
				'DICTIONARY' => $this->dictionary,
				'HELPER' => $this->helper,
			));
	
			//email
			$html = $this->smarty->fetch("order-email-paid.html");
	
			$mail = new PHPMailer();
			$mail->FromName = "History Car Prague";
			$mail->From = $this->dictionary['order_email'];
			$mail->Subject = $this->dictionary['confirmation_subject'];
			$mail->Body = $html;
	
			$mail->AddEmbeddedImage(LOCAL.'app'.DS.'web1'.DS.'design'.DS.'email-header.jpg', "image_header", "", "base64", "image/jpeg");
			$mail->AddEmbeddedImage(LOCAL.'app'.DS.'web1'.DS.'design'.DS.'email-footer.jpg', "image_footer", "", "base64", "image/jpeg");
	
			//pridam eticket
			$html = $this->smarty->fetch('pdf-eticket.html');
			$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
			$mpdf->WriteHTML($html);
			$mpdf->Output(LOCALFILES."eticket.pdf", "F");
			$mail->AddAttachment(LOCALFILES."eticket.pdf", "", "base64", "application/pdf");
	
			//pridam slevove kupony
			$html = $this->smarty->fetch('pdf-vouchers.html');
			$mpdf = new Html2Pdf('','A4',0,'',0,0,0,0,0,0);
			$mpdf->WriteHTML($html);
			$mpdf->Output(LOCALFILES."vouchers.pdf", "F");
			$mail->AddAttachment(LOCALFILES."vouchers.pdf", "", "base64", "application/pdf");
	
			if($program->get('value', 'venice')) {
				//pridam prazske benatky
				$html = $this->smarty->fetch('pdf-venice.html');
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
		}
	}
}

