<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

error_reporting(E_ALL);

define('TU_USER', '_test_user');
define('TU_PASS', 'PhpUnit_4');

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
