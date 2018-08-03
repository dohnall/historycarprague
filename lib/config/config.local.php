<?php
if ($_SERVER["SERVER_NAME"]=='historycarprague.loc') {
	error_reporting(E_ALL&~E_DEPRECATED);
	define("DEBUGGER", false);
	define("DBHOST", "127.0.0.1");
	define("DBNAME", "historycarprague");
	define("DBUSER", "root");
	define("DBPASS", "");
	define("DBPREF", "cms_");
	define("DBTYPE", "mysql");
	define("DBCSET", "utf8");
} else {
	error_reporting(E_ALL);
	define("DEBUGGER", true);
	define("DBHOST", "mysql4.ebola.cz");
	define("DBNAME", "historycarpraguecom_historycar");
	define("DBUSER", "historycar785_ad");
	define("DBPASS", "hicap90sql");
	define("DBPREF", "cms_");
	define("DBTYPE", "mysql");
	define("DBCSET", "utf8");
}
