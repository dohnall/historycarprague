<?php
require_once dirname(dirname(dirname(__FILE__)))."/lib/config/config.php";

if(isset($_FILES['Filedata']) && is_uploaded_file($_FILES['Filedata']['tmp_name']) && $_FILES['Filedata']['error'] == UPLOAD_ERR_OK) {
	$parts = explode('.', $_FILES['Filedata']['name']);
	$ext = array_pop($parts);
	$ext = strtolower($ext);
	$filename = Common::friendlyUrl(implode('.', $parts)).'.'.$ext;
	move_uploaded_file($_FILES['Filedata']['tmp_name'], LOCALFILES.$filename);
}
