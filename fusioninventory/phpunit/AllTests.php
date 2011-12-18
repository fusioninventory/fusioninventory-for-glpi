<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */


if (!defined('GLPI_ROOT')) {   
   define('GLPI_ROOT', '../../..');
   
   include_once (GLPI_ROOT . "/inc/autoload.function.php");
   spl_autoload_register('__autoload');
   
   include_once (GLPI_ROOT . "/inc/includes.php");

   file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
   file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');
   
   include (GLPI_ROOT . "/config/based_config.php");
   include (GLPI_ROOT . "/inc/includes.php");
   restore_error_handler();

   error_reporting(E_ALL | E_STRICT);
   ini_set('display_errors','On');
}
ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

require_once 'GLPIInstall/AllTests.php';
require_once 'FusinvInstall/AllTests.php';
require_once 'InventoryComputer/AllTests.php';
require_once 'Rules/AllTests.php';
require_once 'Netinventory/AllTests.php';
require_once 'GLPIlogs/AllTests.php';
require_once 'Netdiscovery/AllTests.php';

require_once 'emulatoragent.php';

class AllTests {
   public static function suite() {
      $suite = new PHPUnit_Framework_TestSuite('FusionInventory');
      $suite->addTest(GLPIInstall_AllTests::suite());
      $suite->addTest(FusinvInstall_AllTests::suite());
      $suite->addTest(InventoryComputer_AllTests::suite());
      $suite->addTest(Rules_AllTests::suite());
      $suite->addTest(Netinventory_AllTests::suite());
      $suite->addTest(Netdiscovery_AllTests::suite());
      return $suite;
   }
}

?>