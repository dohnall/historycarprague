<?php

$news_list = Section::getInstance(709);
$news_list->setParams(array(
	'limit' => 5,
	'from' => 0,
));
$news = $news_list->get('children', true);

$this->smarty->assign(array(
	'news' => $news,
));
