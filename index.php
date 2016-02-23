<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//define('BIND_MODULE','Mobile');
//define('BIND_MODULE','Admin');
define('APP_DEBUG',true);
define('APP_PATH','./Application/');
define('UPLOAD_PATH','Attachment/');
define('WEB_ROOT','http://localhost/~morganzhao/plife2/');
define('THINK_PATH',realpath('./Think').'/');
require THINK_PATH.'ThinkPHP.php';
