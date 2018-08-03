<?php
$actions = $this->section->get('children', true);

if(is_numeric($this->section->get('value', 'perpage')) && $this->section->get('value', 'perpage') > 0) {
	$perpage = $this->section->get('value', 'perpage');
} elseif(isset($this->dictionary['perpage']) && is_numeric($this->dictionary['perpage']) && $this->dictionary['perpage'] > 0) {
	$perpage = $this->dictionary['perpage'];
} else {
	$perpage = 5;
}

$count = count($actions);
$pager = new Pager($count, $perpage, $this->page);
$pager->process();
$pagerResult = $pager->getPager();

$actions = array_slice($actions, $pagerResult['from'], $pagerResult['perpage']);

$this->smarty->assign(array(
	'actions' => $actions,
	'pager' => $pagerResult,
));

