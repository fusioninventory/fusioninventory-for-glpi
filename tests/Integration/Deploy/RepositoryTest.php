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
   @author    Alexandre Delaunay
   @co-author David Durieux
   @copyright Copyright (c) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase {
   private $packages_1_id   = 0;
   private $packages_2_id   = 0;
   private $filename        = "";
   private $sha512          = "";

   public static function setUpBeforeClass(): void {

      // Delete all packages
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $items = $pfDeployPackage->find();
      foreach ($items as $item) {
         $pfDeployPackage->delete(['id' => $item['id']], true);
      }
   }

   protected function setUp(): void {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      // create a package
      $this->packages_1_id = $pfDeployPackage->add([
         'name' => 'test package 1',
      ]);
      $this->assertNotFalse($this->packages_1_id);

      // create a second package
      $this->packages_2_id = $pfDeployPackage->add([
         'name' => 'test package 2',
      ]);
      $this->assertNotFalse($this->packages_2_id);

      // get Fusion config
      $config = new PluginFusioninventoryConfig;
      $server_upload_path = $config->getValue("server_upload_path");

      // create a file in fusion upload folder
      $this->filename = $server_upload_path."/file1";
      $file_created = file_put_contents($this->filename, "test repository");
      $this->assertNotFalse($file_created);
      $this->sha512 = hash_file('sha512', $this->filename);
   }


   /**
    * @test
    */
   public function cleanFiles() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployFile    = new PluginFusioninventoryDeployFile();

      // create a file for this package
      $data_file = [
         'id'        => $this->packages_1_id,
         'itemtype'  => 'PluginFusioninventoryDeployFile',
         'filestype' => 'Server',
         'filename'  => $this->filename,
      ];
      $ret = PluginFusioninventoryDeployPackage::alterJSON('add_item', $data_file);
      $this->assertTrue($ret, 'File not right added');

      // check json of the package
      $pfDeployPackage->getFromDB($this->packages_1_id);
      $json = json_decode($pfDeployPackage->fields['json'], true);
      $this->assertTrue(isset($json['associatedFiles'][$this->sha512]));

      // retrieve the sha512 of the single part
      $sha512_part = trim(file_get_contents(PLUGIN_FUSIONINVENTORY_MANIFESTS_DIR.$this->sha512), "\n");
      $fulldir  = PLUGIN_FUSIONINVENTORY_REPOSITORY_DIR.
                     $pfDeployFile->getDirBySha512($sha512_part);
      $firstdir = PLUGIN_FUSIONINVENTORY_REPOSITORY_DIR.substr($sha512_part, 0, 1);

      // check the directories are created
      $this->asserttrue(is_dir($fulldir));
      $this->asserttrue(is_dir($firstdir));

      // check presence of file in repo
      $this->assertTrue($pfDeployFile->checkPresenceFile($this->sha512));

      // add the same file to the second package
      $data_file['id'] = $this->packages_2_id;
      PluginFusioninventoryDeployPackage::alterJSON('add_item', $data_file);

      // remove file from the first package
      $data_file = [
         'packages_id'     => $this->packages_1_id,
         'itemtype'        => 'PluginFusioninventoryDeployFile',
         'file_entries'    => [0 => 1]
      ];
      PluginFusioninventoryDeployPackage::alterJSON('remove_item', $data_file);

      // check json of the package
      $pfDeployPackage->getFromDB($this->packages_1_id);
      $json = json_decode($pfDeployPackage->fields['json'], true);
      $this->assertFalse(isset($json['associatedFiles'][$this->sha512]));

      // check presence of file in repo
      // it must be still here, the file is used in package 2
      $this->assertTrue($pfDeployFile->checkPresenceFile($this->sha512));

      // remove file from the second package
      $data_file = [
         'packages_id'     => $this->packages_2_id,
         'itemtype'        => 'PluginFusioninventoryDeployFile',
         'file_entries'    => [0 => 1]
      ];
      PluginFusioninventoryDeployPackage::alterJSON('remove_item', $data_file);

      // check json of the package
      $pfDeployPackage->getFromDB($this->packages_2_id);
      $json = json_decode($pfDeployPackage->fields['json'], true);
      $this->assertFalse(isset($json['associatedFiles'][$this->sha512]));

      // check presence of file in repo
      // Now, we removed it from both package, it must be removed from repository
      $this->assertFalse($pfDeployFile->checkPresenceFile($this->sha512));

      // check the previous directories created in repository are cleaned
      $this->assertFalse(is_dir($fulldir));
      $this->assertFalse(is_dir($firstdir));
   }


   /**
    * @test
    */
   public function cleanPackage() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployFile    = new PluginFusioninventoryDeployFile();

      // create a file and it to both packages
      $data_file = [
         'id'        => $this->packages_1_id,
         'itemtype'  => 'PluginFusioninventoryDeployFile',
         'filestype' => 'Server',
         'filename'  => $this->filename,
      ];
      PluginFusioninventoryDeployPackage::alterJSON('add_item', $data_file);
      $data_file['id'] = $this->packages_2_id;
      PluginFusioninventoryDeployPackage::alterJSON('add_item', $data_file);

      // remove a package and check presence of file
      $pfDeployPackage->delete([
         'id' => $this->packages_1_id
      ], true);
      $this->assertTrue($pfDeployFile->checkPresenceFile($this->sha512));

      // remove a package and check absence of file
      $pfDeployPackage->delete([
         'id' => $this->packages_2_id
      ], true);
      $this->assertfalse($pfDeployFile->checkPresenceFile($this->sha512));
   }
}
