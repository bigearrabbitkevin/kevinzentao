<?php
include (dirname(__FILE__) . '/../kevin.php');


$myConfig   = (dirname(__FILE__) . '/kevinmy.php');
if(file_exists($myConfig)) include $myConfig;