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

/*
 * bootstrop.php needs to be loaded since tests are run in separate process
 */

include_once('0_Install/FusinvDB.php');

class UpdateTest extends RestoreDatabase_TestCase {


   /**
    * @dataProvider provider
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    * @test
    */
   function update($version = '', $verify = false, $nbrules = 0) {
      self::restore_database();
      global $DB;
      $DB->connect();

      if ($version == '') {
         return;
      }

      // uninstall the plugin FusionInventory
      $plugin = new Plugin();
      $plugin->getFromDBByCrit(['directory' => 'fusioninventory']);
      $plugin->uninstall($plugin->fields['id']);

      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetchArray($result)) {
         if (strstr($data[0], "tracker")
                 OR strstr($data[0], "fusi")) {
            $DB->query("DROP TABLE ".$data[0]);
         }
      }
      $query = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype` LIKE 'PluginFus%'";
      $DB->query($query);

      $sqlfile = PLUGIN_FUSIONINVENTORY_DIR ."/phpunit/0_Install/mysql/i-".$version.".sql";
      // Load specific FusionInventory version in database
      $result = load_mysql_file(
         $DB->dbuser,
         $DB->dbhost,
         $DB->dbdefault,
         $DB->dbpassword,
         $sqlfile
      );
      $this->assertEquals( 0, $result['returncode'],
         "Failed to install Fusioninventory ".$sqlfile.":\n".
         implode("\n", $result['output'])
      );
      $output = [];
      $returncode = 0;
      exec(
         "php -f ".FUSINV_ROOT."/scripts/cli_install.php -- --as-user 'glpi'",
         $output,
         $returncode
      );
      $this->assertEquals(0, $returncode, implode("\n", $output));

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $FusinvDB = new FusinvDB();
      $FusinvDB->checkInstall("fusioninventory", "upgrade from ".$version);

      $this->verifyEntityRules($nbrules);
      $this->checkDeployMirrors();

      if ($verify) {
         $this->verifyConfig();
      }

   }


   public function provider() {
      // version, verifyConfig, nb entity rules
      return [
         ["2.3.3", false, 0],
         //array("2.1.3", FALSE, 0),
         ["0.83+2.1", true, 1],
      ];
   }


   private function verifyEntityRules($nbrules = 0) {
      global $DB;

      $DB->connect();

      if ($nbrules == 0) {
         return;
      }

      $cnt_old = countElementsInTable("glpi_rules",
         ['sub_type' => 'PluginFusinvinventoryRuleEntity']);

      $this->assertEquals(0, $cnt_old, "May not have entity rules with old itemtype name");

      $cnt_new = countElementsInTable("glpi_rules",
         ['sub_type' => 'PluginFusioninventoryInventoryRuleEntity']);

      $this->assertEquals($nbrules, $cnt_new, "May have ".$nbrules." entity rules");

   }


   private function verifyConfig() {
      global $DB;
      $DB->connect();

      $a_configs = getAllDataFromTable('glpi_plugin_fusioninventory_configs',
         ['type' => 'states_id_default']);

      $this->assertEquals(1, count($a_configs), "May have conf states_id_default");

      $a_config = current($a_configs);
      $this->assertEquals(1, $a_config['value'], "May keep states_id_default to 1");
   }


   private function checkDeployMirrors() {
      global $DB;

      //check is the field is_active has correctly been added to mirror servers
      $this->assertTrue($DB->fieldExists('glpi_plugin_fusioninventory_deploymirrors',
                                    'is_active'));

   }


}

