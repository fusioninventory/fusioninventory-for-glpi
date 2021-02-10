<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

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
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class PackageJsonTest extends TestCase {


   /**
    * @test
    */
   public function JsonCreateNewPackage() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = [
          'name'        => 'test1',
          'entities_id' => 0];
      $packages_id = $pfDeployPackage->add($input);
      $this->assertNotFalse($packages_id);

      $pfDeployPackage->getFromDB($packages_id);
      $json_structure = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $this->assertEquals($json_structure, $pfDeployPackage->fields['json'], "json structure not right");
   }


   /**
    * @test
    */
   public function AddItem() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = [
                'name'        => 'test2',
                'entities_id' => 0
               ];
      $packages_id = $pfDeployPackage->add($input);

      // Add check
      $item = [
         'id'               => $packages_id,
         'name'             => 'check winkey',
         'itemtype'         => 'PluginFusioninventoryDeployCheck',
         'checkstype'       => 'winkeyExists',
         'path'             => 'toto',
         'return'           => 'error',
         'add_item'         => 'Add'
      ];
      PluginFusioninventoryDeployPackage::alterJSON('add_item', $item);

      $pfDeployPackage->getFromDB($packages_id);
      $json_structure = '{"jobs":{"checks":[{"name":"check winkey","type":"winkeyExists","path":"toto","value":"","return":"error"}],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $this->assertEquals($json_structure, $pfDeployPackage->fields['json'], "json structure not right");

   }

   /**
    * @test
    * @depends AddItem
    */
   public function duplicate() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $packages        = $pfDeployPackage->find(['name' => 'test2']);
      $this->assertEquals(1, count($packages));
      $package = current($packages);

      $this->assertTrue($pfDeployPackage->duplicate($package['id']));

      $packages = $pfDeployPackage->find(['name' => 'Copy of test2']);
      $this->assertEquals(1, count($packages));
      $package = current($packages);

      $json_structure = '{"jobs":{"checks":[{"name":"check winkey","type":"winkeyExists","path":"toto","value":"","return":"error"}],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $this->assertEquals($json_structure, $package['json']);
      $this->assertEquals(0, $package['entities_id']);
   }

   /**
    * @test
    */
   public function Migration_to_91() {
      global $DB;

      $DB->connect();

      // create package orders used before 9.1 version
      $query = "DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_deploypackages` ";
      $DB->query($query);

      $query = "CREATE TABLE `glpi_plugin_fusioninventory_deploypackages` (
            `id` int(11) NOT NULL,
            `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `comment` text COLLATE utf8_unicode_ci,
            `entities_id` int(11) NOT NULL,
            `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
            `date_mod` timestamp NULL DEFAULT NULL,
            `uuid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query);

      $query = "INSERT INTO `glpi_plugin_fusioninventory_deploypackages` (`id`, `name`, `comment`, `entities_id`, `is_recursive`, `date_mod`, `uuid`) VALUES
        (16, 'INST VLC 2.1.5', 'Install VLC 2.1.5 unintall all VLC', 0, 0, '2014-10-17 11:11:02', NULL);";
      $DB->query($query);

       // glpi_plugin_fusioninventory_deployorders
      $query = "DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_deployorders` ";
      $DB->query($query);

      $query = "CREATE TABLE `glpi_plugin_fusioninventory_deployorders` (
        `id` int(11) NOT NULL,
        `type` int(11) NOT NULL,
        `create_date` timestamp NOT NULL,
        `plugin_fusioninventory_deploypackages_id` int(11) NOT NULL,
        `json` longtext COLLATE utf8_unicode_ci,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query);

      $query = "INSERT INTO `glpi_plugin_fusioninventory_deployorders` (`id`, `type`, `create_date`, `plugin_fusioninventory_deploypackages_id`, `json`) VALUES
        (31, 0, '2013-04-29 09:58:58', 16, '{\"jobs\":{\"checks\":[],\"actions\":[{\"mkdir\":{\"list\":[\"c:\\\\packages\\\\vlc\"]}},{\"move\":{\"from\":\"*.*\",\"to\":\"c:\\\\packages\\\\vlc\"}},{\"cmd\":{\"exec\":\"c:\\\\packages\\\\vlc\\\\vlcinstall.cmd\"}}],\"associatedFiles\":[\"1f54a4730571d165a488f7f343e49d71f7e06c639091959df7065019971d1c3080f97da6517a94173083a50625dc1c1ba11f685d0c6f15705a75d5265c708cee\"]},\"associatedFiles\":{\"1f54a4730571d165a488f7f343e49d71f7e06c639091959df7065019971d1c3080f97da6517a94173083a50625dc1c1ba11f685d0c6f15705a75d5265c708cee\":{\"name\":\"vlc.zip\",\"p2p\":1,\"p2p-retention-duration\":16,\"uncompress\":1}}}'),
        (32, 1, '2013-04-29 09:58:58', 16, '{\"jobs\":{\"checks\":[],\"actions\":[{\"cmd\":{\"exec\":\"vlcuninstall.cmd\"}}],\"associatedFiles\":[\"b16d6a078538842df7b6e572be62845b16870d5f325ec39ac4ae3d6705b2845990684c5a39206c7f23db177226781660324fab14330d98e71f2315658d13584b\"]},\"associatedFiles\":{\"b16d6a078538842df7b6e572be62845b16870d5f325ec39ac4ae3d6705b2845990684c5a39206c7f23db177226781660324fab14330d98e71f2315658d13584b\":{\"name\":\"vlcuninstall.cmd\",\"p2p\":0,\"p2p-retention-duration\":5,\"uncompress\":0}}}');";
      $DB->query($query);

       // run migration packages
       require_once (PLUGIN_FUSIONINVENTORY_DIR . "/install/update.php");
       $migration = new Migration('9.1');
       do_deploypackage_migration($migration);

       // Check order right now
       $packages = getAllDataFromTable('glpi_plugin_fusioninventory_deploypackages');
       $this->assertEquals(2, count($packages));
       $jsons = [];
       $names = [];
      foreach ($packages as $package) {
         $jsons[] = $package['json'];
         $names[] = $package['name'];
      }
       $ref = [
           "{\"jobs\":{\"checks\":[],\"actions\":[{\"mkdir\":{\"list\":[\"c:\\packages\\vlc\"]}},{\"move\":{\"from\":\"*.*\",\"to\":\"c:\\packages\\vlc\"}},{\"cmd\":{\"exec\":\"c:\\packages\\vlc\\vlcinstall.cmd\"}}],\"associatedFiles\":[\"1f54a4730571d165a488f7f343e49d71f7e06c639091959df7065019971d1c3080f97da6517a94173083a50625dc1c1ba11f685d0c6f15705a75d5265c708cee\"]},\"associatedFiles\":{\"1f54a4730571d165a488f7f343e49d71f7e06c639091959df7065019971d1c3080f97da6517a94173083a50625dc1c1ba11f685d0c6f15705a75d5265c708cee\":{\"name\":\"vlc.zip\",\"p2p\":1,\"p2p-retention-duration\":16,\"uncompress\":1}}}",
           "{\"jobs\":{\"checks\":[],\"actions\":[{\"cmd\":{\"exec\":\"vlcuninstall.cmd\"}}],\"associatedFiles\":[\"b16d6a078538842df7b6e572be62845b16870d5f325ec39ac4ae3d6705b2845990684c5a39206c7f23db177226781660324fab14330d98e71f2315658d13584b\"]},\"associatedFiles\":{\"b16d6a078538842df7b6e572be62845b16870d5f325ec39ac4ae3d6705b2845990684c5a39206c7f23db177226781660324fab14330d98e71f2315658d13584b\":{\"name\":\"vlcuninstall.cmd\",\"p2p\":0,\"p2p-retention-duration\":5,\"uncompress\":0}}}"
       ];
       $this->assertEquals($ref, $jsons);

       $ref = ['INST VLC 2.1.5', 'INST VLC 2.1.5 (uninstall)'];
       $this->assertEquals($ref, $names);
   }
}
