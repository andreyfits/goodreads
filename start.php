<?php
mb_internal_encoding("UTF-8"); //кодировка
error_reporting(0); //выводим все ошибки
ini_set("display_errors", 1);

set_include_path(get_include_path() . PATH_SEPARATOR . "core" . PATH_SEPARATOR . "lib" . PATH_SEPARATOR . "objects" . PATH_SEPARATOR . "validator" . PATH_SEPARATOR . "controllers" . PATH_SEPARATOR . "modules");
spl_autoload_extensions(".php");
spl_autoload_register();

define("MAINMENU", 1);
define("TOPMENU", 2);
define("KB_B", 1024);
define("PAY_RECOMMENDED", 1);
define("FREE_RECOMMENDED", 2);
define("ONLINE_RECOMMENDED", 3);

AbstractObjectDB::setDB(DataBase::getDBO());

?>