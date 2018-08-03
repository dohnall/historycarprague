<?php
$program_id = isset($_GET['program']) && is_numeric($_GET['program']) ? $_GET['program'] : 0;
$program = Section::getInstance($program_id);
if($program->get('section', 'template') != 'program') {
	Common::redirect(ROOT);
}

$this->session->order_date = isset($this->session->order_date) ? $this->session->order_date : date('Y-m-d');

if(isset($_POST['submit']) && isset($_POST['antispam']) && $_POST['antispam'] == 'nospam') {
//d($_POST);
	//validace
	if($program->get('value', 'type') == 2) {
		$_POST['time'] = !is_array($program->get('value', 'times')) ? $program->get('value', 'times') : '2000';
		$_POST['cars'] = '0';
	}
	$v = new Validator($_POST);
	$v->addRule("time", "required");
	$v->addRule("location", "required");
	$v->addRule("fullname", "required");
	$v->addRule("email", "email");
	$v->addRule("phone", "required");
	$v->addRule("payment", "required");
	$error = $v->validate();

	if($error) {
		$this->session->alert = implode('<br />', $error);
		$this->session->data = $_POST;
		Common::redirect();
	} else {
		$data = $_POST;

		$h = substr($data['time'], 0, -2);
		$h = $h < 10 ? '0'.$h : $h;
		$m = substr($data['time'], 2);
		$time = $this->session->order_date.' '.$h.':'.$m.':00';

		$data['hours'] = isset($data['hours']) && is_numeric($data['hours']) && $data['hours'] > 0 ? $data['hours'] : 0;

		do {
			$variable = rand(10000000, 99999999);
			$query = "SELECT COUNT(*) AS found FROM ".Config::db_table_order()." WHERE variable = ".$variable;
			$found = $this->db->select($query, true, 'found');
		} while($found);

		$price = $price2 = 0;
		if($program->get('value', 'price_type') == 1) {
			$price = $program->get('value', 'price') * $data['cars'];
		} elseif($program->get('value', 'price_type') == 2) {
			$price = $program->get('value', 'price') * $data['people'];
		} elseif($program->get('value', 'price_type') == 3) {
			$price = $program->get('value', 'price') * $data['cars'] * $data['hours'];
		} else {
			$price = $program->get('value', 'price');
		}

		foreach($data['accessory'] as $k => $v) {
			if($v > 0) {
				$price2+= $v * $this->helper->codebook('accessories', $k)->get('value', 'price');
			}
		}

		if($program->get('value', 'sale')) {
			$price = $price - ($price * $program->get('value', 'sale') / 100);
		}

		$price = $price + $price2;

		//ulozeni
		$query = "INSERT INTO ".Config::db_table_order()."
				  (program_id, time, time_code, people, cars, hours,
				   location, name, email, phone, note,
				   price, currency, payment, variable, status, inserted)
				  VALUES
				  (".$data['program'].", '".$time."', '".$data['time']."', ".$data['people'].", ".$data['cars'].", ".$data['hours'].",
				   '".mysql_real_escape_string($data['location'])."', '".mysql_real_escape_string($data['fullname'])."', '".$data['email']."', '".mysql_real_escape_string($data['phone_cc'].$data['phone'])."', '".mysql_real_escape_string($data['note'])."',
				   ".$price.", '".$this->dictionary['currency']."', '".$data['payment']."', ".$variable.", 'new', NOW())";
		$order_id = $this->db->insert($query);

		foreach($data['accessory'] as $k => $v) {
			if($v > 0) {
				$query = "INSERT INTO ".Config::db_table_order_accessory()."
						  (order_id, accessory_id, amount, price)
						  VALUES
						  (".$order_id.", ".$k.", ".$v.", ".$this->helper->codebook('accessories', $k)->get('value', 'price').")";
				$this->db->insert($query);
			}
		}

		$this->smarty->assign(array(
			'ROOT' => ROOT,
			'DICTIONARY' => $this->dictionary,
			'data' => $data,
			'time' => $time,
			'price' => $price,
			'program' => $program,
			'order_id' => $order_id,
			'HELPER' => $this->helper,
			'variable' => $variable,
			'hash' => md5($variable),
		));

		//email
		if($data['payment'] == 'paypal') {
			$html = $this->smarty->fetch("order-email-paypal.html");
		} elseif($data['payment'] == 'cash') {
			$html = $this->smarty->fetch("order-email-cash.html");
		} else {
			$html = $this->smarty->fetch("order-email-transfer.html");
		}
	
		$mail = new PHPMailer();
		$mail->FromName = "History Car Prague";
		$mail->From = $this->dictionary['order_email'];
		$mail->Subject = $this->dictionary['order_subject'];
		$mail->Body = $html;

		$mail->AddEmbeddedImage(LOCAL.'app'.DS.'web1'.DS.'design'.DS.'email-header.jpg', "image_header", "", "base64", "image/jpeg");
		$mail->AddEmbeddedImage(LOCAL.'app'.DS.'web1'.DS.'design'.DS.'email-footer.jpg', "image_footer", "", "base64", "image/jpeg");

	   	$mail->ClearAddresses();
		$mail->AddAddress($data['email']);
	   	$mail->Send();

	   	$mail->ClearAddresses();
		$mail->AddAddress('tours@historycarprague.com');
	   	$mail->Send();

		unset($this->session->data);
	   	Common::redirect($this->helper->section(896)->get('url'));
	}
}

$total = $total2 = 0;
$data = $this->session->data;
if($data) {
	if($program->get('value', 'price_type') == 1) {
		$total = $program->get('value', 'price') * $data['cars'];
	} elseif($program->get('value', 'price_type') == 2) {
		$total = $program->get('value', 'price') * $data['people'];
	} elseif($program->get('value', 'price_type') == 3) {
		$total = $program->get('value', 'price') * $data['cars'] * $data['hours'];
	} else {
		$total = $program->get('value', 'price');
	}
} else {
	if($program->get('value', 'min') > 1) {
		$total = $program->get('value', 'price') * $program->get('value', 'min');
	} else {
		$total = $program->get('value', 'price');
	}
}

$allowedToday = true;
if($program->get('value', 'reservation_hours_before_start') === 0 || $program->get('value', 'reservation_hours_before_start') > 0) {
	$times = $program->get('value', 'times');
	if(!is_array($times)) {
		$times = array($times);
	}
	sort($times);

	$last = end($times);
	$currTime = date('G', strtotime('+1 hour')).'00';
	$timeDiff = $program->get('value', 'reservation_hours_before_start').'00';
	if(($last - $currTime) < (int)$timeDiff || !$last) {
		$allowedToday = false;
	}
	//d($allowedToday, $last, $currTime, $timeDiff);
}

if(isset($data['accessory'])) {
	foreach($data['accessory'] as $k => $v) {
		if($v > 0) {
			$total2+= $v * $this->helper->codebook('accessories', $k)->get('value', 'price');
		}
	}
}

$this->smarty->assign(array(
	'program' => $program,
	'order_date' => $this->session->order_date,
	'total' => $total,
	'total2' => $total2,
	'data' => $data,
	'allowedToday' => $allowedToday,
));
