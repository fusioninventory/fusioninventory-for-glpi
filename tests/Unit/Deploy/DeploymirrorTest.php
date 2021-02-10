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
   @author    Walid Nouh <wnouh@teclib.com>
   @co-author David Durieux
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class DeploymirrorTest extends TestCase {


   public static function setUpBeforeClass(): void {

      // Delete all mirrors
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $items = $pfDeploymirror->find();
      foreach ($items as $item) {
         $pfDeploymirror->delete(['id' => $item['id']], true);
      }

      // Delete all locations
      $location = new Location();
      $items = $location->find();
      foreach ($items as $item) {
         $location->delete(['id' => $item['id']], true);
      }

   }

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
      $this->assertTrue($pfDeploymirror->getFromDB($mirrors_id));
   }


   /**
    * @test
    * @depends testAddMirror
    */
   public function testUpdateMirror() {
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $pfDeploymirror->getFromDBByCrit(['name' => 'MyMirror']);
      $this->assertNotNull($pfDeploymirror->fields['id']);
      $input  = ['id'      => $pfDeploymirror->fields['id'],
                 'name'    => 'Mirror 1',
                 'comment' => 'MyComment 2',
                 'url'     => 'http://localhost:8088/mirror',
                ];
      $this->assertTrue($pfDeploymirror->update($input));
      $this->assertTrue($pfDeploymirror->getFromDB($input['id']));
      $this->assertEquals('Mirror 1', $pfDeploymirror->fields['name']);
      $this->assertEquals('http://localhost:8088/mirror', $pfDeploymirror->fields['url']);

   }


   /**
    * @test
    * @depends testUpdateMirror
    */
   public function testDeleteLocationFromMirror() {
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $location       = new Location();
      $locations_id = $location->add(['name'         => 'MyLocation',
                                      'entities_id'  => 0,
                                      'is_recursive' => 1
                                     ]);
      //Add the location to the mirror
      $pfDeploymirror->getFromDBByCrit(['name' => 'Mirror 1']);
      $this->assertNotNull($pfDeploymirror->fields['id']);
      $input          = ['id'           => $pfDeploymirror->fields['id'],
                         'locations_id' => $locations_id
                        ];
      $this->assertTrue($pfDeploymirror->update($input));

      //Purge location
      $location->delete(['id' => $locations_id], true);
      $this->assertTrue($pfDeploymirror->getFromDB($input['id']));
      //Check that location has been deleted from the mirror
      $this->assertEquals(0, $pfDeploymirror->fields['locations_id']);
   }


   /**
    * @test
    * @depends testDeleteLocationFromMirror
    */
   public function testDeleteMirror() {
      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $pfDeploymirror->getFromDBByCrit(['name' => 'Mirror 1']);
      $this->assertNotNull($pfDeploymirror->fields['id']);
      $this->assertTrue($pfDeploymirror->delete(['id' => $pfDeploymirror->fields['id']]));
   }
}
