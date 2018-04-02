<?php
include_once('bootstrap.php');
include_once('commonfunction.php');

include_once (GLPI_ROOT . "/inc/based_config.php");
include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
include_once (GLPI_CONFIG_DIR . "/config_db.php");


abstract class Common_TestCase extends PHPUnit_Framework_TestCase {


   public function mark_incomplete($description = null) {
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
      global $CFG_GLPI, $DB;

      include(GLPI_ROOT.'/inc/includes.php');

      $DB = new DB();
      // Force profile in session to SuperAdmin
      $_SESSION['glpiprofiles'] = ['4' => ['entities' => 0]];

      $_SESSION['glpi_plugin_fusioninventory_profile']['unmanaged'] = 'w';

      $_SESSION['glpiactiveentities'] = [0, 1];

      $_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;

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
               if (filetype($dir."/".$object) != "dir") {
                  unlink($dir."/".$object);
               }
            }
         }
      }

      include_once (GLPI_ROOT . "/inc/timer.class.php");

      // Security of PHP_SELF
      $_SERVER['PHP_SELF']=Html::cleanParametersURL($_SERVER['PHP_SELF']);

      ini_set("memory_limit", "-1");
      ini_set("max_execution_time", "0");

   }


   protected function tearDown() {
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }


}
