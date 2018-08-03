<?php
if(isset($_GET['delete']) && is_numeric($_GET['delete']) && $_GET['delete'] > 0) {
	$query = "DELETE FROM ".Config::db_table_order()."
			  WHERE order_id=".$_GET['delete'];
	$this->db->delete($query);
	$query = "DELETE FROM ".Config::db_table_order_accessory()."
			  WHERE order_id=".$_GET['delete'];
	$this->db->delete($query);

	$this->session->alert = $this->dictionary['item_deleted'];
	$this->session->alert_css_class = 'success left-icon';

	Common::redirect();
}

$order = isset($_GET['order']) && in_array($_GET['order'], array('order_id', 'name', 'email', 'price', 'status', 'inserted')) ? $_GET['order'] : "inserted";
$sort = isset($_GET['sort']) && in_array($_GET['sort'], array('asc', 'desc')) ? $_GET['sort'] : "desc";

$query = "SELECT order_id, program_id, name, email, price, currency, status, inserted
		  FROM ".Config::db_table_order()."
		  ORDER BY ".$order." ".$sort;
$items = $this->db->select($query);

$this->smarty->assign(array(
    'items' => $items,
    'order' => $order,
    'sort' => $sort,
));
