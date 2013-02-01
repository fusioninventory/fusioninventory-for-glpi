<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

class Install extends PHPUnit_Framework_TestCase {

   public function testInstall($verify=1) {
      global $DB;
      $DB->connect();
      
      if (file_exists("save.sql") AND $verify == '0') {

         $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $DB->query("DROP VIEW ".$data[0]);
         }

         $query = "SHOW TABLES";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $DB->query("DROP TABLE ".$data[0]);
         }

         $res = $DB->runFile("save.sql");
         $this->assertTrue($res, "Fail: SQL Error during import saved GLPI DB");

         echo "======= Import save.sql file =======\n";

         $FusinvInstall = new FusinvInstall();
         $FusinvInstall->testDB("fusioninventory", "install new version");
      } else {

         // Delete if Table of FusionInventory or Tracker yet in DB
         $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            if (strstr($data[0], "fusi")) {
               $DB->query("DROP VIEW ".$data[0]);
            }
         }

         $query = "SHOW TABLES";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            if (strstr($data[0], "tracker")
                    OR strstr($data[0], "fusi")) {
               $DB->query("DROP TABLE ".$data[0]);
            }
         }

         passthru("cd ../tools && /usr/local/bin/php -f cli_install.php");

         Session::loadLanguage("en_GB");

         $FusinvInstall = new FusinvInstall();
         $FusinvInstall->testDB("fusioninventory", "install new version");

         passthru("mysqldump -h ".$DB->dbhost." -u ".$DB->dbuser." -p".$DB->dbpassword." ".$DB->dbdefault." > save.sql");
         
         $this->assertFileExists("../../../files/_plugins/fusioninventory/discovery.xml", 
                 'Discovery file (SNMP MODELS) not created');
         $file = file_get_contents("../../../files/_plugins/fusioninventory/discovery.xml");
         $a_lines = explode("\n", $file);
         $this->assertGreaterThan(20, count($a_lines), 'Discovery.xml file not right generated (nb lines)');

      }

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
}



class Install_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Install');
      return $suite;
   }
}

?>