<?php
/* Upload settings. */
$config->kevinclass = new stdclass();
$config->kevinclass->dangers = 'php,php3,php4,phtml,php5,jsp,py,rb,asp,asa,cer,cdx,aspl'; // Dangerous files.
$config->kevinclass->maxSize = 1024 * 1024;          // Max size.
$config->kevinclass->require = new stdclass();
$config->kevinclass->require->edit = 'title';
$config->kevinclass->require->book = 'title, alias';

$config->kevinclass->thumbs = array();
$config->kevinclass->thumbs['s'] = array('width' => '80',  'height' => '80');
$config->kevinclass->thumbs['m'] = array('width' => '300', 'height' => '300');
$config->kevinclass->thumbs['l'] = array('width' => '800', 'height' => '600');

$config->kevinclass->imageExtensions = array('jpeg', 'jpg', 'gif', 'png');

$config->kevinclass->mimes['default'] = 'application/octet-stream';

//下面是设置编辑器的样式，edit表示的是模块，id表示的是textarea的id，tools表示显示的样式有simpleTools和fullTools
$config->kevinclass->editor = new stdclass();
$config->kevinclass->editor->edit       = array('id' => 'content', 'tools' => 'fullTools');

//new catalog count
$config->kevinclass->newCatalogCount = 5;

$config->kevinclass->file = new stdclass();
$config->kevinclass->file->dangers = 'php,php3,php4,phtml,php5,jsp,py,rb,asp,aspx,ashx,asa,cer,cdx,aspl,shtm,shtml,html,htm'; // Dangerous file types.
$config->kevinclass->file->require = new stdclass();
$config->kevinclass->file->require->edit = 'title';
$config->kevinclass->file->thumbs = array();
$config->kevinclass->file->thumbs['s'] = array('width' => '80',  'height' => '80');
$config->kevinclass->file->thumbs['m'] = array('width' => '300', 'height' => '300');
$config->kevinclass->file->thumbs['l'] = array('width' => '800', 'height' => '600');

$config->kevinclass->file->imageExtensions = array('jpeg', 'jpg', 'gif', 'png');

$config->kevinclass->file->mimes['default'] = 'application/octet-stream';
