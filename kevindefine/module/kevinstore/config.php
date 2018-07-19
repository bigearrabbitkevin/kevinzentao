<?php

$config->kevinstore              = new stdclass();

$config->kevinstore->nameRequire  = new stdclass();
$config->kevinstore->nameRequire->requiredFields  = 'name';

$config->kevinstore->itemedit  = new stdclass();
$config->kevinstore->itemedit->requiredFields  = 'name,number';
$config->kevinstore->itemcreate  = new stdclass();
$config->kevinstore->itemcreate->requiredFields  = 'name';
$config->kevinstore->groupcreate  = new stdclass();
$config->kevinstore->groupcreate->requiredFields= 'name,type';

$config->kevinstore->times        = new stdclass();
$config->kevinstore->times->begin = 0;
$config->kevinstore->times->end   = 24;
$config->kevinstore->times->delta = 10;

$config->kevinstore->confirmDelete = true;

$config->kevinstore->editor = new stdclass();
//$config->kevinstore->editor->itemcreate   = array('id' => 'desc', 'tools' => 'simpleTools');
//$config->kevinstore->editor->itemedit     = array('id' => 'desc', 'tools' => 'simpleTools');