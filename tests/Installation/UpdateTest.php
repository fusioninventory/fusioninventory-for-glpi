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

require_once("FusinvDB.php");

use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase {

   public static function setUpBeforeClass(): void {
      // clean log files
      file_put_contents("../../files/_log/php-errors.log", '');
      file_put_contents("../../files/_log/sql-errors.log", '');
   }


   public static function tearDownAfterClass(): void {
      // Creation of folders if not created in tests
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/computer')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/computer');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/printer')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/printer');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/networkequipment')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/networkequipment');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/upload')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/upload');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/repository')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/repository');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/manifests')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/manifests');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/import')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/import');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/export')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/export');
      }
   }




   /**
    * @dataProvider provider
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    * @test
    */
   function update($version = '', $verify = false, $nbrules = 0) {
      global $DB;

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

      // Delete all fusion rules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => ['like', "PluginFusion%"]]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }
      $DB->query('DELETE FROM glpi_ruleactions WHERE id > 105');
      $DB->query('DELETE FROM glpi_rulecriterias WHERE id > 108');
      $DB->query('DELETE FROM glpi_rules WHERE id > 105');

      if ($version != '') {
         $sqlfile = "tests/Installation/mysql/i-".$version.".sql";
         // Load specific FusionInventory version in database
         $result = $this->load_mysql_file(
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
      }
      $output = [];
      $returncode = 0;
      $outputActivate     = [];
      $returncodeActivate = 0;
      $command = "cd ../../ && php bin/console glpi:plugin:install -vvv -n --config-dir=tests --username=glpi fusioninventory";
      exec($command, $output, $returncode);

      $commandActivate = "cd ../../ && php bin/console glpi:plugin:activate -n --config-dir=tests fusioninventory";
      exec($commandActivate, $outputActivate, $returncodeActivate);

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

   function load_mysql_file($dbuser = '', $dbhost = '', $dbdefault = '', $dbpassword = '', $file = null) {
      if (!file_exists($file)) {
         return [
            'returncode' => 1,
            'output' => ["ERROR: File '{$file}' does not exist !"]
         ];
      }

      $result = $this->construct_mysql_options($dbuser, $dbhost, $dbpassword, 'mysql');

      if (is_array($result)) {
         return $result;
      }

      $cmd = $result . " " . $dbdefault . " < ". $file ." 2>&1";

      $returncode = 0;
      $output = [];
      exec(
         $cmd,
         $output,
         $returncode
      );
      array_unshift($output, "Output of '{$cmd}'");
      return [
         'returncode'=>$returncode,
         'output' => $output
      ];
   }


   function construct_mysql_options($dbuser = '', $dbhost = '', $dbpassword = '', $cmd_base = 'mysql') {
      $cmd = [];

      if (empty($dbuser) || empty($dbhost)) {
         return [
            'returncode' => 2,
            'output' => ["ERROR: missing mysql parameters (user='{$dbuser}', host='{$dbhost}')"]
         ];
      }
      $cmd = [$cmd_base];

      if (strpos($dbhost, ':') !== false) {
         $dbhost = explode( ':', $dbhost);
         if (!empty($dbhost[0])) {
            $cmd[] = "--host ".$dbhost[0];
         }
         if (is_numeric($dbhost[1])) {
            $cmd[] = "--port ".$dbhost[1];
         } else {
            // The dbhost's second part is assumed to be a socket file if it is not numeric.
            $cmd[] = "--socket ".$dbhost[1];
         }
      } else {
         $cmd[] = "--host ".$dbhost;
      }

      $cmd[] = "--user ".$dbuser;

      if (!empty($dbpassword)) {
         $cmd[] = "-p'".urldecode($dbpassword)."'";
      }
      return implode(' ', $cmd);
   }


   public function provider() {
      // version, verifyConfig, nb entity rules
      return [
         '0.83+2.1'     => ["0.83+2.1", true, 1],
         'empty tables' => ["", false, 0],
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
