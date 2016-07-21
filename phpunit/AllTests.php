<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */


if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', realpath('../../..'));

   include_once (GLPI_ROOT . "/inc/autoload.function.php");
   spl_autoload_register('glpi_autoload');

   include_once (GLPI_ROOT . "/inc/includes.php");

   file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
   file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');

   $dir = GLPI_ROOT."/files/_files/_plugins/fusioninventory";
   $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") {
         } else {
            unlink($dir."/".$object);
         }
       }
     }


   include_once (GLPI_ROOT . "/inc/timer.class.php");

   include_once (GLPI_ROOT . "/inc/common.function.php");

   // Security of PHP_SELF
   $_SERVER['PHP_SELF']=Html::cleanParametersURL($_SERVER['PHP_SELF']);

   function glpiautoload($classname) {
      global $DEBUG_AUTOLOAD, $CFG_GLPI;
      static $notfound = array();

      // empty classname or non concerted plugin
      if (empty($classname) || is_numeric($classname)) {
         return FALSE;
      }

      $dir=GLPI_ROOT . "/inc/";
      //$classname="PluginExampleProfile";
      if ($plug=isPluginItemType($classname)) {
         $plugname=strtolower($plug['plugin']);
         $dir=GLPI_ROOT . "/plugins/$plugname/inc/";
         $item=strtolower($plug['class']);
         // Is the plugin activate ?
         // Command line usage of GLPI : need to do a real check plugin activation
         if (isCommandLine()) {
            $plugin = new Plugin();
            if (count($plugin->find("directory='$plugname' AND state=".Plugin::ACTIVATED)) == 0) {
               // Plugin does not exists or not activated
               return FALSE;
            }
         } else {
            // Standard use of GLPI
            if (!in_array($plugname, $_SESSION['glpi_plugins'])) {
               // Plugin not activated
               return FALSE;
            }
         }
      } else {
         // Is ezComponent class ?
         $matches = array();
         if (preg_match('/^ezc([A-Z][a-z]+)/', $classname, $matches)) {
            include_once(GLPI_EZC_BASE);
            ezcBase::autoload($classname);
            return TRUE;
         } else {
            $item=strtolower($classname);
         }
      }

      // No errors for missing classes due to implementation
      if (!isset($CFG_GLPI['missingclasses'])
              OR !in_array($item, $CFG_GLPI['missingclasses'])){
         if (file_exists("$dir$item.class.php")) {
            include_once ("$dir$item.class.php");
            if ($_SESSION['glpi_use_mode']==Session::DEBUG_MODE) {
               $DEBUG_AUTOLOAD[]=$classname;
            }

         } else if (!isset($notfound["$classname"])) {
            // trigger an error to get a backtrace, but only once (use prefix 'x' to handle empty case)
            //Toolbox::logInFile('debug', "file $dir$item.class.php not founded trying to load class $classname\n");
            trigger_error("GLPI autoload : file $dir$item.class.php not founded trying to load class '$classname'");
            $notfound["$classname"] = TRUE;
         }
      }
   }

   spl_autoload_register('glpiautoload');

   include (GLPI_ROOT . "/config/based_config.php");
   include (GLPI_ROOT . "/inc/includes.php");
   restore_error_handler();

   error_reporting(E_ALL | E_STRICT);
   ini_set('display_errors', 'On');
}
ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

$_SESSION['glpiprofiles'] = array('4' => array('entities' => 0));
Session::changeProfile(4);

$_SESSION["glpi_plugin_fusioninventory_profile"]['unmanaged'] = 'w';

$_SESSION['glpiactiveentities'] = array(0, 1);

require_once 'GLPIInstall/AllTests.php';
require_once 'FusinvInstall/AllTests.php';
require_once 'GLPIlogs/AllTests.php';

require_once '1_Unit/FormatConvertData.php';
require_once '1_Unit/SoftwareUpdate.php';
require_once '1_Unit/ComputerTransformation.php';
require_once '1_Unit/ComputerUpdate.php';
require_once '1_Unit/PrinterTransformation.php';
require_once '1_Unit/PrinterUpdate.php';
require_once '1_Unit/NetworkEquipmentTransformation.php';
require_once '1_Unit/NetworkEquipmentUpdate.php';
require_once '1_Unit/NetworkEquipmentUpdateDiscovery.php';
require_once '1_Unit/ComputerLog.php';
require_once '1_Unit/AgentChangeDeviceid.php';
//require_once '1_Unit/Tasks/Task.php';
//require_once '1_Unit/Tasks/Job.php';
//require_once '1_Unit/Tasks/Tasks_Jobs.php';
//require_once '1_Unit/Tasks/Tasks_Jobs_Run.php';



require_once '2_Integration/ComputerEntity.php';
require_once '2_Integration/RuleIgnoredImport.php';
require_once '2_Integration/RuleImport.php';
require_once '2_Integration/SoftwareEntityCreation.php';
require_once '2_Integration/SoftwareVersionAdd.php';
require_once '2_Integration/ComputerDynamic.php';
require_once '2_Integration/UnmanagedManaged.php';
require_once '2_Integration/UnmanagedImport.php';
require_once '2_Integration/TaskDeployDynamicGroup.php';
require_once '2_Integration/ComputerPrinter.php';
require_once '2_Integration/ComputerLicense.php';
require_once '2_Integration/NetworkEquipmentLLDP.php';
require_once '2_Integration/ComputerMonitor.php';
require_once '2_Integration/ComputerPeripheral.php';
require_once '2_Integration/CollectsTest.php';

require_once 'emulatoragent.php';

class AllTests {
   public static function suite() {
      $suite = new PHPUnit_Framework_TestSuite('FusionInventory');
      if (file_exists("save.sql")) {
         unlink("save.sql");
      }

      $suite->addTest(GLPIInstall_AllTests::suite());
      $suite->addTest(FusinvInstall_AllTests::suite());

      Plugin::loadLang('fusioninventory');

      if (isset($_SERVER['argv'])
              && isset($_SERVER['argv'][2])
              && !isset($_SERVER['argv'][3])) {
         $class = $_SERVER['argv'][2]."_AllTests";
         $suite->addTest($class::suite());
      } else {
         $suite->addTest(FormatConvertData_AllTests::suite());
         $suite->addTest(SoftwareUpdate_AllTests::suite());
         $suite->addTest(AgentChangeDeviceid_AllTests::suite());
         $suite->addTest(ComputerTransformation_AllTests::suite());
         $suite->addTest(ComputerUpdate_AllTests::suite());
         $suite->addTest(PrinterTransformation_AllTests::suite());
         $suite->addTest(PrinterUpdate_AllTests::suite());
         $suite->addTest(NetworkEquipmentTransformation_AllTests::suite());
         $suite->addTest(NetworkEquipmentUpdate_AllTests::suite());
         $suite->addTest(NetworkEquipmentUpdateDiscovery_AllTests::suite());
         $suite->addTest(ComputerLog_AllTests::suite());

         $suite->addTest(ComputerEntity_AllTests::suite());
         $suite->addTest(RuleIgnoredImport_AllTests::suite());
         $suite->addTest(RuleImport_AllTests::suite());
         $suite->addTest(SoftwareEntityCreation_AllTests::suite());
         $suite->addTest(SoftwareVersionAdd_AllTests::suite());
         $suite->addTest(ComputerDynamic_AllTests::suite());
         $suite->addTest(UnmanagedManaged_AllTests::suite());
         $suite->addTest(UnmanagedImport_AllTests::suite());
         //$suite->addTest(TaskDeployDynamicGroup_AllTests::suite());
         $suite->addTest(ComputerPrinter_AllTests::suite());
         $suite->addTest(ComputerLicense_AllTests::suite());
         $suite->addTest(NetworkEquipmentLLDP_AllTests::suite());
         $suite->addTest(ComputerMonitor_AllTests::suite());
         $suite->addTest(ComputerPeripheral_AllTests::suite());
      }

      # For travis-CI
      file_put_contents ( "result.stamp", "test ok" );

      return $suite;
   }
}

?>
