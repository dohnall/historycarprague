<?php
define("MODE", 'CMS');
require_once dirname(dirname(__FILE__))."/config/config.php";

$db = Database::connect();

$filename = $_GET['file'];

if(file_exists(LOCALFILES.$filename)) {
	$query = "UPDATE ".Config::db_table_section_file()." SET
			  	download = download + 1
			  WHERE file = '".$filename."'";
	$db->update($query);
	$file_properties = getimagesize(LOCALFILES.$filename);
	header("Content-Type:".$image_properties['mime']."; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$filename);
	echo file_get_contents(LOCALFILES.$filename);
}
