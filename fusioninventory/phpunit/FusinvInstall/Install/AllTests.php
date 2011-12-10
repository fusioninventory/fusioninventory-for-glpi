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

class Install extends PHPUnit_Framework_TestCase {

   public function testInstall() {
      global $DB;

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
      
      loadLanguage("en_GB");
      
      $FusinvInstall = new FusinvInstall();
      $FusinvInstall->testDB("fusioninventory");
      
      $FusinvInstall->testDB("fusinvinventory");
      
      $FusinvInstall->testDB("fusinvsnmp");
      
      $FusinvInstall->testDB("fusinvdeploy");      
   }
}



class Install_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Install');
      return $suite;
   }
}
?>
