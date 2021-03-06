<?php 
/**
*  全局配置文件
*/


error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', '1');


define("ROOT", dirname(dirname(__FILE__)).'/');
define("ROOT_CONFIG", ROOT.'config/');
define("ROOT_CLASS", ROOT.'lib/class/');
define("ROOT_LIB", ROOT.'lib/');
define("ROOT_MODULE", ROOT.'lib/module/');
define("ROOT_API", ROOT.'api/');
define("ROOT_TEMPLATE",ROOT.'template/');
define("ROOT_USER_HEADER", ROOT.'static/header/');
define("ROOT_USERSPACE","/home/BDVTP/");
define("ROOT_USERSPACE_MYSQL","/var/lib/mysql/");
define("DATABASE_USER","BDVTP_");

define("PATH_USER_HEADER", "./admin/static/header/");
define("PATH_CSS", "./css/");
define("PATH_IMAGE", "./images/");
define("PATH_JS", "./js/");

define("MD5_SALT","B2D0V1T6P");
?>