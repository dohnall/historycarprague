<?php
$item_id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

if(isset($_POST['save'])) {
	if(preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}$/', $_POST['time'])) {
		$time = $_POST['time'].':00';
	} else {
		$time = $_POST['time'];
	}
	$time_code = date('Hi', strtotime($time));

	$query = "UPDATE ".Config::db_table_order()." SET
				time = '".$time."',
				time_code = '".$time_code."',
				name = '".$_POST['name']."',
				email = '".$_POST['email']."',
				phone = '".$_POST['phone']."',
				location = '".$_POST['location']."',
				people = '".$_POST['people']."',
				cars = '".$_POST['cars']."',
				hours = '".$_POST['hours']."',
				note = '".$_POST['note']."',
				payment = '".$_POST['payment']."',
				price = '".$_POST['price']."',
				currency = '".$_POST['currency']."',
				variable = '".$_POST['variable']."',
				status = '".$_POST['status']."'
			  WHERE order_id = ".$item_id;
	$this->db->update($query);

    $this->session->alert = $this->dictionary['item_saved'];
    $this->session->alert_css_class = 'success left-icon';
    Common::redirect();
}

if(isset($this->session->data)) {
    $data = $this->session->data;
} else {
	$query = "SELECT *
			  FROM ".Config::db_table_order()."
			  WHERE order_id = ".$item_id;
	$data = $this->db->select($query, true);
}

$query = "SELECT * 
		  FROM ".Config::db_table_order_accessory()."
		  WHERE order_id = ".$item_id;
$data['items'] = $this->db->select($query);

$this->smarty->assign(array(
    'item_id' => $item_id,
    'data' => $data,
));
