<?php

error_reporting(E_ALL);

define('TU_USER', '_test_user');
define('TU_PASS', 'PhpUnit_4');

define('GLPI_FORCE_NATIVE_SQL_TYPES', false);

global $CFG_GLPI;

include('./glpi/inc/includes.php');

if (!defined('FUSINV_ROOT')) {
   define('FUSINV_ROOT', GLPI_ROOT . DIRECTORY_SEPARATOR . '/plugins/fusioninventory');
   set_include_path(
      get_include_path() . PATH_SEPARATOR .
      GLPI_ROOT . PATH_SEPARATOR .
      GLPI_ROOT . "/plugins/fusioninventory/phpunit/"
   );
}

require FUSINV_ROOT . '/phpunit/vendor/autoload.php';

$_SESSION['glpiprofiles'] = ['4' => ['entities' => 0]];

$_SESSION['glpi_plugin_fusioninventory_profile']['unmanaged'] = 'w';

$_SESSION['glpiactiveentities'] = [0, 1];

$_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;
if (!isset($_SESSION['glpilanguage'])) {
   $_SESSION['glpilanguage'] = 'fr_FR';
}

include_once("Common_TestCase.php");
include_once("RestoreDatabase_TestCase.php");
include_once("LogTest.php");
include_once("commonfunction.php");
