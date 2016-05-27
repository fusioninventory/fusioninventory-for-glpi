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
   @since     2013

   ------------------------------------------------------------------------
 */

class PackageJsonTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function JSONCreateNewPackage() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = array(
          'name'        => 'test1',
          'entities_id' => 0);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage->getFromDB(1);
      $json_structure = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $this->assertEquals($json_structure, $pfDeployPackage->fields['json'], "json structure not right");
   }


   /**
    * @test
    */
   public function AddItem() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = array(
          'name'        => 'test1',
          'entities_id' => 0);
      $packages_id = $pfDeployPackage->add($input);

      // Add check
      $item = array(
         'id' => $packages_id,
         'itemtype'  => 'PluginFusioninventoryDeployCheck',
         'deploy_checktype' => 'winkeyExists',
         'path'      => 'toto',
         'return'    => 'error',
         'add_item'  => 'Add'
      );
      PluginFusioninventoryDeployPackage::alter_json('add_item', $item);

      $pfDeployPackage->getFromDB($packages_id);
      $json_structure = '{"jobs":{"checks":[{"type":"winkeyExists","path":"toto","value":"","return":"error"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $this->assertEquals($json_structure, $pfDeployPackage->fields['json'], "json structure not right");

   }
}
