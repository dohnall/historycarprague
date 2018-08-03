<?php
require_once dirname(dirname(dirname(dirname(__FILE__))))."/lib/config/config.php";

$smarty = Smarty::getInstance();
$smarty->template_dir = TEMPLATES;
$smarty->compile_dir = TEMPLATESC;

$db = Database::connect();

Config::setVar('USER_TIMEZONE', DEFAULT_TIMEZONE);
Config::setVar('CURRENT_DOMAIN_URL', ROOT);

$langCode = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$langList = new LangList();
$lang_id = $langList->getLangByCode($langCode);
$session->lang_id = $lang_id;

$lang = new Lang($lang_id);
$lang->load();

Config::setVar('CURRENT_LANG_CODE', $langCode);

if(file_exists(STATOR.$langCode.".ini")) {
	$smarty->configLoad(STATOR.$langCode.".ini");
	$dictionary = $smarty->getConfigVars();
} else {
	$dictionary = array();
}

$smarty->assign(array(
    'ROOT' => ROOT,
    'DESIGN' => DESIGN,
    'FILES' => FILES,
    'HELPER' => new Helper(),
    'DICTIONARY' => $dictionary,
));

$return = "";
$action = isset($_GET['action']) ? $_GET['action'] : "";

if($action == "selectTime") {
	$date = isset($_POST['date']) && preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $_POST['date']) ? $_POST['date'] : "";
	$program_id = isset($_POST['program']) && is_numeric($_POST['program']) ? $_POST['program'] : 0;

	if($date && $program_id) {
	
		$program = Section::getInstance($program_id);
		$times = $program->get('value', 'times');
		if(!is_array($times)) {
			$times = array($times);
		}
		sort($times);

		if($date == date('Y-m-d')) {
			$currTime = date('G', strtotime('+1 hour')).'00';
			$allowedSince = $currTime + $program->get('value', 'reservation_hours_before_start') * 100;
		} else {
			$allowedSince = 0;
		}

		$cars = array();
		foreach($times as $k => $v) {
			if((int)$v < $allowedSince) {
				unset($times[$k]);
				continue;
			}
			$cars[$v] = $dictionary['settings_cars'];
		}

		$people = 0;
		if($program->get('value', 'type') == 2) {
			$query = "SELECT program_id, people, cars, time_code, hours
					  FROM ".Config::db_table_order()."
					  WHERE DATE(time) = '".$date."' AND
					  		program_id = ".$program_id;
			$result = $db->select($query);
			foreach($result as $row) {
				$people+= $row['people'];
			}
		} else {
			$query = "SELECT program_id, people, cars, time_code, hours
					  FROM ".Config::db_table_order()."
					  WHERE DATE(time) = '".$date."'";
			$result = $db->select($query);
			foreach($result as $row) {
				$people+= $row['people'];
				$prog = Section::getInstance($row['program_id']);
				if(in_array($prog->get('section', 'parent_id'), array(905))) {
					continue;
				}
				$hours = $row['hours'] ? $row['hours'] : $prog->get('value', 'length');
				for($i = -1 * ($hours - 1); $i < $hours; $i++) {
					if(isset($cars[$row['time_code'] + $i * 100])) {
						$cars[$row['time_code'] + $i * 100] -= $row['cars'];
					}
				}
			}		
		}
		
		$free = array();
		foreach($times as $row) {
			if($cars[$row] > 0) {
				$free[$row] = $cars[$row];
			}
		}
		$is_free = array_sum($free);

		$capacity = $program->get('value', 'max') - $people;
//d($program->get('value', 'max'), $people, $capacity);
		$smarty->assign(array(
			'date' => $date,
			'udate' => date('N', strtotime($date)),
			'program' => $program,
			'times' => $times,
			'driveAllowedOrder' => $date == date('Y-m-d') && $program->get('value', 'reservation_hours_before_start') === "0" ? date('G', strtotime('now +30 minute')).'00' : '0',
			'cars' => $cars,
			'people' => $people,
			'capacity' => $capacity,
			'data' => $session->data,
			'is_free' => $is_free,
		));
//d(date('N', strtotime($date)));
		$return = $smarty->fetch('ajax_order_time.html');
		$session->order_date = $date;
	}
}

echo $return;
