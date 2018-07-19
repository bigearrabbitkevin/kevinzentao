<?php
$config->kevincalendar = new stdclass();

$config->kevincalendar->batchcreate = 8;
//设置备注框样式
$config->kevincalendar->editor = new stdclass();
$config->kevincalendar->editor->edit = array('id' => 'desc', 'tools' => 'full');
$config->kevincalendar->editor->create = array('id' => 'desc', 'tools' => 'full');