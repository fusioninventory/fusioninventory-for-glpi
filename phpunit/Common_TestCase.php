<?php
include_once('bootstrap.php');
include_once('commonfunction.php');

include_once (GLPI_ROOT . "/inc/based_config.php");
include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
include_once (GLPI_CONFIG_DIR . "/config_db.php");


abstract class Common_TestCase extends PHPUnit_Framework_TestCase {

   public function mark_incomplete($description=null) {
      $this->markTestIncomplete(
         is_null($description) ? 'This test is not implemented yet' : $description
      );
   }

   public static function restore_database() {

      self::drop_database();
      self::load_mysql_file('./save.sql');

   }

   public static function load_mysql_file($filename) {

      self::assertFileExists($filename, 'File '.$filename.' does not exist!');

      $DBvars = get_class_vars('DB');

      $result = load_mysql_file(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword'],
         $filename
      );

      self::assertEquals( 0, $result['returncode'],
         "Failed to restore database:\n".
         implode("\n", $result['output'])
      );
   }

   public static function drop_database() {

      $DBvars = get_class_vars('DB');

      $result = drop_database(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword']
      );

      self::assertEquals( 0, $result['returncode'],
         "Failed to drop GLPI database:\n".
         implode("\n", $result['output'])
      );

   }


   protected function setUp() {
      global $CFG_GLPI,$DB;
      $DB = new DB();
      // Force profile in session to SuperAdmin
      $_SESSION['glpiprofiles'] = array('4' => array('entities' => 0));

      $_SESSION['glpi_plugin_fusioninventory_profile']['unmanaged'] = 'w';

      $_SESSION['glpiactiveentities'] = array(0, 1);

      $_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;

      require (GLPI_ROOT . "/inc/includes.php");


      $plugin = new Plugin();
      $DB->connect();
      $plugin->getFromDBbyDir("fusioninventory");
      $plugin->activate($plugin->fields['id']);

      file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
      file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');

      $dir = GLPI_ROOT."/files/_files/_plugins/fusioninventory";
      if (file_exists($dir)) {
         $objects = scandir($dir);

         foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
               if (filetype($dir."/".$object) == "dir") {
               } else {
                  unlink($dir."/".$object);
               }
            }
         }
      }

      include_once (GLPI_ROOT . "/inc/timer.class.php");


      // Security of PHP_SELF
      $_SERVER['PHP_SELF']=Html::cleanParametersURL($_SERVER['PHP_SELF']);

      //      function glpiautoload($classname) {
      //         global $DEBUG_AUTOLOAD, $CFG_GLPI;
      //         static $notfound = array();
      //
      //         // empty classname or non concerted plugin
      //         if (empty($classname) || is_numeric($classname)) {
      //            return FALSE;
      //         }
      //
      //         $dir=GLPI_ROOT . "/inc/";
      //         //$classname="PluginExampleProfile";
      //         if ($plug=isPluginItemType($classname)) {
      //            $plugname=strtolower($plug['plugin']);
      //            $dir=GLPI_ROOT . "/plugins/$plugname/inc/";
      //            $item=strtolower($plug['class']);
      //            // Is the plugin activate ?
      //            // Command line usage of GLPI : need to do a real check plugin activation
      //            if (isCommandLine()) {
      //               $plugin = new Plugin();
      //               if (count($plugin->find("directory='$plugname' AND state=".Plugin::ACTIVATED)) == 0) {
      //                  // Plugin does not exists or not activated
      //                  return FALSE;
      //               }
      //            } else {
      //               // Standard use of GLPI
      //               if (!in_array($plugname, $_SESSION['glpi_plugins'])) {
      //                  // Plugin not activated
      //                  return FALSE;
      //               }
      //            }
      //         } else {
      //            // Is ezComponent class ?
      //            $matches = array();
      //            if (preg_match('/^ezc([A-Z][a-z]+)/', $classname, $matches)) {
      //               include_once(GLPI_EZC_BASE);
      //               ezcBase::autoload($classname);
      //               return TRUE;
      //            } else {
      //               $item=strtolower($classname);
      //            }
      //         }
      //
      //         // No errors for missing classes due to implementation
      //         if (!isset($CFG_GLPI['missingclasses'])
      //                 OR !in_array($item, $CFG_GLPI['missingclasses'])) {
      //            if (file_exists("$dir$item.class.php")) {
      //               include_once ("$dir$item.class.php");
      //               if ($_SESSION['glpi_use_mode']==Session::DEBUG_MODE) {
      //                  $DEBUG_AUTOLOAD[]=$classname;
      //               }
      //
      //            } else if (!isset($notfound["$classname"])) {
      //               // trigger an error to get a backtrace, but only once (use prefix 'x' to handle empty case)
      //               //Toolbox::logInFile('debug', "file $dir$item.class.php not founded trying to load class $classname\n");
      //               trigger_error("GLPI autoload : file $dir$item.class.php not founded trying to load class '$classname'");
      //               $notfound["$classname"] = TRUE;
      //            }
      //         }
      //      }
      //
      //      spl_autoload_register('glpiautoload');

//      restore_error_handler();

//      error_reporting(E_ALL | E_STRICT);
//      ini_set('display_errors', 'On');
      ini_set("memory_limit", "-1");
      ini_set("max_execution_time", "0");


   }


   protected function tearDown() {
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
}
