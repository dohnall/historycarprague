<?php
define("MODE", 'CMS');
require_once dirname(dirname(__FILE__))."/config/config.php";

$filename = $_GET['size']."_".$_GET['file'];

if(!file_exists(LOCALFILES.'thumbs/'.$filename)) {
	//ochrana proti utoku zvenku
	if(!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ROOT) !== 0) {
		Common::redirect(PAGE404, 404);
	}

    if(is_numeric($_GET['size'])) {
        Common::resize(LOCALFILES.$_GET['file'], $_GET['size'], $_GET['size'], LOCALFILES.'thumbs/'.$filename);
    } elseif(substr($_GET['size'], 0, 1) == 'w') {
        Common::resize(LOCALFILES.$_GET['file'], substr($_GET['size'], 1), 0, LOCALFILES.'thumbs/'.$filename);
    } elseif(substr($_GET['size'], 0, 1) == 'h') {
        Common::resize(LOCALFILES.$_GET['file'], 0, substr($_GET['size'], 1), LOCALFILES.'thumbs/'.$filename);
    } elseif(strpos($_GET['size'], "x") !== false) {
    	list($width, $height) = explode("x", $_GET['size']);
    	Common::resize(LOCALFILES.$_GET['file'], $width, $height, LOCALFILES.'thumbs/'.$filename);
    }
}

$image_properties = getimagesize(LOCALFILES.'thumbs/'.$filename);

header("Content-Type:".$image_properties['mime']."; charset=utf-8");
header("Content-Disposition: inline; filename=".$_GET['file']);
echo file_get_contents(LOCALFILES.'thumbs/'.$filename);
