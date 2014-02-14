<?php
xdebug_enable();

define('GLPI_ROOT', realpath('../../..'));
set_include_path(get_include_path() . PATH_SEPARATOR . GLPI_ROOT);

require_once (GLPI_ROOT . "/inc/autoload.function.php");

include_once("BaseTestCase.php");
include_once("LogTest.php");
include_once("commonfunction.php");
