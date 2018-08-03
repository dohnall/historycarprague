<?php
header("Content-Type: text/html; charset=utf-8");

function __autoload($classname) {
  	if(file_exists(CLASSES.$classname.".class.php")) {
    	require_once CLASSES.$classname.".class.php";
  	} elseif(file_exists(PROJECT.$classname.".class.php")) {
  		require_once PROJECT.$classname.".class.php";
  	} elseif(substr(strtolower($classname), 0, 6) == 'smarty') {
	    $_class = strtolower($classname);
	    $_classes = array(
	        'smarty_config_source' => true,
	        'smarty_config_compiled' => true,
	        'smarty_security' => true,
	        'smarty_cacheresource' => true,
	        'smarty_cacheresource_custom' => true,
	        'smarty_cacheresource_keyvaluestore' => true,
	        'smarty_resource' => true,
	        'smarty_resource_custom' => true,
	        'smarty_resource_uncompiled' => true,
	        'smarty_resource_recompiled' => true,
	    );
	
	    if (!strncmp($_class, 'smarty_internal_', 16) || isset($_classes[$_class])) {
	        include SMARTY_SYSPLUGINS_DIR . $_class . '.php';
	    }
	} else {
    	return false;
  	}
}

function d() {
    $arg_num = func_num_args();
    if($arg_num > 0) {
        $arg_list = func_get_args();
        foreach($arg_list as $arg) {
            echo "<xmp>"; var_dump($arg); echo "</xmp>";
        }
        exit;
    }
}

function clean(&$str) {
    Common::clean($str);
}
/*
if(strpos($_SERVER['SCRIPT_NAME'], '/subdom/rezervace/admin/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'subdom/rezervace/admin/'));
    define("MODE", 'CMS');
} elseif(strpos($_SERVER['SCRIPT_NAME'], '/subdom/rezervace/lib/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'subdom/rezervace/lib/'));
} elseif(strpos($_SERVER['SCRIPT_NAME'], '/subdom/rezervace/app/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'subdom/rezervace/app/'));
    define("MODE", 'WEB');
} elseif(strpos($_SERVER['SCRIPT_NAME'], '/subdom/rezervace/install/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'subdom/rezervace/install/'));
    define("MODE", 'WEB');
} else {
    $_slash = ($_SERVER["SERVER_NAME"]=='localhost') ? "/" : "";
    $_root = dirname(substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'subdom/rezervace/'))).$_slash;
    define("MODE", 'WEB');
}
*/

if(strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'admin/'));
    define("MODE", 'CMS');
} elseif(strpos($_SERVER['SCRIPT_NAME'], '/lib/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'lib/'));
} elseif(strpos($_SERVER['SCRIPT_NAME'], '/app/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'app/'));
    define("MODE", 'WEB');
} elseif(strpos($_SERVER['SCRIPT_NAME'], '/install/') !== false) {
    $_root = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], 'install/'));
    define("MODE", 'WEB');
} else {
    $_slash = ($_SERVER["SERVER_NAME"]=='historycarprague.loc') ? "" : "";
    $_root = dirname($_SERVER['SCRIPT_NAME']).$_slash;
    define("MODE", 'WEB');
}

mb_internal_encoding("UTF-8");
setlocale(LC_ALL, 'cs_CZ.UTF8');

//iconv_set_encoding("internal_encoding", "UTF-8");
//iconv_set_encoding("input_encoding", "UTF-8");
//iconv_set_encoding("output_encoding", "UTF-8");

define("DS", DIRECTORY_SEPARATOR);
define("ROOT", "http://".$_SERVER['HTTP_HOST'].$_root);
define("LOCAL", dirname(dirname(dirname(__FILE__))).DS);
define("APP", ROOT."app/");
define("LOCALAPP", LOCAL."app".DS);
define("LOCALLIB", LOCAL."lib".DS);
define("CONFIG", LOCALLIB."config".DS);
define("CLASSES", LOCALLIB."classes".DS);
define("INSTALL", ROOT."install/");
define("SERVICE", LOCALLIB."service".DS);
define("LOG", LOCALLIB."log".DS);

define("PAGE404", ROOT."page-404");
define("COMPANY", "gn"); //fd, gn
define("DEFAULT_CMS_LANG", "en");
define("LOG_ACTION", true);
define("SUPPORT_MAIL", "dohnal@gramonet.com");
define("DEFAULT_TIMEZONE", "Europe/Prague");
define("FORUM_TYPE", "open"); //open, moderated
define("FORUM_ORDER", "inserted"); //inserted, thread
define("FORUM_NEW_INSERT", "begin"); //begin, end
define("EFP_PROJECT_TEMPLATE", "initiative");
define("CFWI_PROJECT_TEMPLATE", "project-detail");

define("RECAPTCHA_PUBLIC_KEY", "6LcXy9kSAAAAAN0YnI_9znl_fiPY9mL1RhHW29hX");
define("RECAPTCHA_PRIVATE_KEY", "6LcXy9kSAAAAAAGaSw-NvHswqbu8tBXTslP0WiCI");

date_default_timezone_set(DEFAULT_TIMEZONE);

if(file_exists(CONFIG."config.local.php")) {
	require_once CONFIG."config.local.php";
} else {
	require_once CONFIG."config.local.conf";
}

$session = Session::getInstance(MODE);

if($session->domain_id) {
	define('DOMAINID', $session->domain_id);
} else {
	$domainList = new DomainList();
	if($domain_id = $domainList->getDomainByUrl(ROOT)) {
		$session->domain_id = $domain_id;
		define('DOMAINID', $domain_id);
	} else {
		die('Unknown domain!');
	}
}

define('WEBID', APP.'web'.DOMAINID."/");
define('LOCALWEBID', LOCALAPP.'web'.DOMAINID.DS);
define("LOCALFILES", LOCALWEBID."files".DS);
define("FILES", WEBID."files/");
define("DESIGN", WEBID."design/");
define("JS", WEBID."js/");
define("PROJECT", LOCALWEBID."project".DS);
define("SCRIPTS", LOCALWEBID."scripts".DS);
define("TEMPLATES", LOCALWEBID."templates".DS);
define("TEMPLATESC", LOCALWEBID."templates_c".DS);
define("STATOR", LOCALWEBID."static".DS);

define("CMSROOT", ROOT."admin/");
define("CMSLOCAL", LOCAL."admin".DS);
define("CMSAJAX", CMSROOT."ajax/");
define("CMSAJAXLOCAL", CMSLOCAL."ajax".DS);
define("CMSDESIGN", CMSROOT."design/");
define("CMSJS", CMSROOT."js/");
define("CMSLANG", CMSLOCAL."lang".DS);
define("CMSMODULES", CMSLOCAL."modules".DS);
define("CMSTEMPLATES", CMSLOCAL."templates".DS);
define("CMSTEMPLATESC", CMSLOCAL."templates_c".DS);
