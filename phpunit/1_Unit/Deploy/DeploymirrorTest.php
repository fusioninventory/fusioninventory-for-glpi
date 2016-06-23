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

class DeploymirrorTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function testAddMirror() {
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $input = ['name'    => 'MyMirror',
                'comment' => 'MyComment',
                'url'     => 'http://localhost:8080/mirror',
               ];
      $mirrors_id = $pfDeploymirror->add($input);
      $this->assertGreaterThan(0, $mirrors_id);
   }

   /**
    * @test
    */
   public function testUpdateMirror() {
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $tmp = $pfDeploymirror->find("`name`='MyMirror'");
      $mirror = current($tmp);
      $input = ['id' => $mirror['id'],
                'name'    => 'Mirror 1',
                'comment' => 'MyComment 2',
                'url'     => 'http://localhost:8088/mirror',
               ];
      $this->assertTrue($pfDeploymirror->update($input));
      $this->assertTrue($pfDeploymirror->getFromDB($mirror['id']));
      $this->assertEquals('Mirror 1', $pfDeploymirror->fields['name']);
      $this->assertEquals('http://localhost:8088/mirror', $pfDeploymirror->fields['url']);

   }

   /**
    * @test
    */
   public function testDeleteLocationFromMirror() {
      //Add a location
      $location = new Location();
      $this->assertGreaterThan(0, 
         $location->add(['name'         => 'MyLocation',
                         'entities_id'  => 0,
                         'is_recursive' => 1
                        ]));
      $tmp = $location->find("`name`='MyLocation'");
      $loc = current($tmp);

      //Add the location to the mirror
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $tmp = $pfDeploymirror->find("`name`='Mirror 1'");
      $mirror = current($tmp);
      $input = ['id'           => $mirror['id'],
                'locations_id' => 'Mirror 1'
               ];
      $this->assertTrue($pfDeploymirror->update($input));

      //Purge location
      $location->delete($loc, true);
      $this->assertTrue($pfDeploymirror->getFromDB($mirror['id']));
      //Check that location has been deleted from the mirror
      $this->assertEquals(0, $pfDeploymirror->fields['locations_id']);
   }

   /**
    * @test
    */
   public function testDeleteMirror() {
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $tmp = $pfDeploymirror->find("`name`='Mirror 1'");
      $mirror = current($tmp);
      $this->assertTrue($pfDeploymirror->delete($mirror));
   }

}
