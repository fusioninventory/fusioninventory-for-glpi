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

class PackageSelfDeployTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all packages
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $items = $pfDeployPackage->find();
      foreach ($items as $item) {
         $pfDeployPackage->delete(['id' => $item['id']], true);
      }

      // Delete all groups
      $group = new Group();
      $items = $group->find();
      foreach ($items as $item) {
         $group->delete(['id' => $item['id']], true);
      }

      // Delete all deploygroups
      $pfDeployGroup = new PluginFusioninventoryDeployGroup();
      $items = $pfDeployGroup->find();
      foreach ($items as $item) {
         $pfDeployGroup->delete(['id' => $item['id']], true);
      }

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      $computer      = new Computer();
      $user          = new User();
      $pfAgent       = new PluginFusioninventoryAgent();
      $pfDeployGroup = new PluginFusioninventoryDeployGroup();
      $profile       = new Profile();

      $user->getFromDBByCrit(['name' => 'David']);
      if (isset($user->fields['id'])) {
         $userId = $user->fields['id'];
      } else {
         $userId = $user->add(['name' => 'David']);
      }
      $computerId = $computer->add([
         'name'        => 'pc01',
         'entities_id' => 0,
         'users_id'    => $userId
      ]);
      $pfAgent->add([
         'computers_id'=> $computerId,
         'entities_id' => 0
      ]);
      $pfDeployGroup->add([
         'name' => 'all',
         'type' => 'DYNAMIC'
      ]);
      $a_profile = current($profile->find(['interface' => 'helpdesk'], [], 1));

      $_SESSION['glpiID']                    = $userId;
      $_SESSION['glpiname']                  = 'David';
      $_SESSION['glpiactive_entity']         = 0;
      $_SESSION['glpiactiveentities_string'] = "'0'";
      $_SESSION['glpigroups']                = [];
      $_SESSION['glpiactiveprofile']         = $a_profile;
      $_SESSION['glpiparententities']        = [];
   }


   public static function tearDownAfterClass(): void {
      $auth = new Auth();
      $user = new User();
      $auth->auth_succeded = true;
      $user->getFromDB(2);
      $auth->user = $user;
      Session::init($auth);
      Session::initEntityProfiles(2);
      Session::changeProfile(4);
      plugin_init_fusioninventory();
   }


   protected function setUp(): void {

      // Delete all package entity
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();
      $items = $pfDeployPackage_Entity->find();
      foreach ($items as $item) {
         $pfDeployPackage_Entity->delete(['id' => $item['id']], true);
      }

      // Delete all package group
      $pfDeployPackage_Group = new PluginFusioninventoryDeployPackage_Group();
      $items = $pfDeployPackage_Group->find();
      foreach ($items as $item) {
         $pfDeployPackage_Group->delete(['id' => $item['id']], true);
      }

      // Delete all package user
      $pfDeployPackage_User = new PluginFusioninventoryDeployPackage_User();
      $items = $pfDeployPackage_User->find();
      foreach ($items as $item) {
         $pfDeployPackage_User->delete(['id' => $item['id']], true);
      }

      // Delete all package profile
      $pfDeployPackage_Profile = new PluginFusioninventoryDeployPackage_Profile();
      $items = $pfDeployPackage_Profile->find();
      foreach ($items as $item) {
         $pfDeployPackage_Profile->delete(['id' => $item['id']], true);
      }

      // Delete all tasks
      $pfTask = new PluginFusioninventoryTask();
      $items = $pfTask->find();
      foreach ($items as $item) {
         $pfTask->delete(['id' => $item['id']], true);
      }

   }


   /**
    * @test
    */
   public function PackageNoTarget() {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = [
         'name'        => 'test1',
         'entities_id' => 0,
         'plugin_fusioninventory_deploygroups_id' => 0
      ];
      $packagesId = $pfDeployPackage->add($input);
      $this->assertNotFalse($packagesId);
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');
   }


   /**
    * @test
    */
   public function PackageTargetEntity() {

      $pfDeployPackage        = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();
      $pfDeployGroup          = new PluginFusioninventoryDeployGroup();

      $pfDeployGroup->getFromDBByCrit(['name' => 'all']);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test1']);
      $pfDeployPackage->update([
         'id' => $pfDeployPackage->fields['id'],
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ]);
      $this->assertArrayHasKey('id', $pfDeployPackage->fields);

      $pfDeployPackage_Entity->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id']
      ]);

      $packages = $pfDeployPackage->canUserDeploySelf();
      $reference = [$pfDeployPackage->fields['id'] => $pfDeployPackage->fields];
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }


   /**
    * @test
    */
   public function PackageTargetgroup() {

      $pfDeployPackage       = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Group = new PluginFusioninventoryDeployPackage_Group();
      $group                 = new Group();
      $pfDeployGroup         = new PluginFusioninventoryDeployGroup();

      $pfDeployGroup->getFromDBByCrit(['name' => 'all']);

      $groupId = $group->add([
         'name' => 'self-deploy',
         'entities_id' => 0
      ]);
      $this->assertNotFalse($groupId);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test1']);
      $pfDeployPackage->update([
         'id' => $pfDeployPackage->fields['id'],
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ]);

      $pfDeployPackage_Group->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id'],
         'groups_id'   => $groupId,
         'entities_id' => 0
      ]);
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');

      $_SESSION['glpigroups'] = [0 => $groupId];

      $packages = $pfDeployPackage->canUserDeploySelf();
      $reference = [$pfDeployPackage->fields['id'] => $pfDeployPackage->fields];
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }


   /**
    * @test
    */
   public function PackageTargetUser() {
      $pfDeployPackage      = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_User = new PluginFusioninventoryDeployPackage_User();
      $pfDeployGroup         = new PluginFusioninventoryDeployGroup();

      $pfDeployGroup->getFromDBByCrit(['name' => 'all']);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test1']);
      $pfDeployPackage->update([
         'id' => $pfDeployPackage->fields['id'],
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ]);

      $pfDeployPackage_User->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id'],
         'users_id' => 1
      ]);
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');

      $pfDeployPackage_User->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id'],
         'users_id' => $_SESSION['glpiID']
      ]);

      $packages = $pfDeployPackage->canUserDeploySelf();
      $reference = [$pfDeployPackage->fields['id'] => $pfDeployPackage->fields];
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }


   /**
    * @test
    */
   public function PackageTargetProfile() {
      $pfDeployPackage         = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Profile = new PluginFusioninventoryDeployPackage_Profile();
      $pfDeployGroup         = new PluginFusioninventoryDeployGroup();

      $pfDeployGroup->getFromDBByCrit(['name' => 'all']);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test1']);
      $pfDeployPackage->update([
         'id' => $pfDeployPackage->fields['id'],
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ]);

      $pfDeployPackage_Profile->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id'],
         'profiles_id' => 4
      ]);
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');

      $pfDeployPackage_Profile->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id'],
         'profiles_id' => $_SESSION['glpiactiveprofile']['id']
      ]);

      $packages = $pfDeployPackage->canUserDeploySelf();
      $reference = [
          $pfDeployPackage->fields['id'] => $pfDeployPackage->fields
      ];
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }


   /**
    * @test
    */
   public function ReportMyPackage() {

      // Enable deploy feature for all agents
      $module = new PluginFusioninventoryAgentmodule();
      $module->getFromDBByCrit(['modulename' => 'DEPLOY']);
      $module->update([
         'id'        => $module->fields['id'],
         'is_active' => 1
      ]);

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $computer        = new Computer();
      $pfAgent         = new PluginFusioninventoryAgent();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();
      $pfDeployGroup         = new PluginFusioninventoryDeployGroup();
      $user = new User();

      $pfDeployGroup->getFromDBByCrit(['name' => 'all']);

      $computer->getFromDBByCrit(['name' => 'pc01']);
      $computerId1 = $computer->fields['id'];

      $user->getFromDBByCrit(['name' => 'David']);

      $computerId2 = $computer->add([
         'name'        => 'pc02',
         'entities_id' => 1,
         'users_id'    => $user->fields['id']
      ]);
      $this->assertNotFalse($computerId2);

      $agentId = $pfAgent->add([
         'computers_id'=> $computerId2,
         'entities_id' => 0
      ]);
      $this->assertNotFalse($agentId);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test1']);
      $pfDeployPackage->update([
         'id' => $pfDeployPackage->fields['id'],
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ]);
      $packages_id_1 = $pfDeployPackage->fields['id'];
      $packageEntityId = $pfDeployPackage_Entity->add([
         'plugin_fusioninventory_deploypackages_id' => $packages_id_1,
         'entities_id' => 0
      ]);
      $this->assertNotFalse($packageEntityId);

      // The second package, test2, is not in the same entity, and is not recursive
      // It should not be visible when requesting the list of packages the the user
      // can deploy
      $input = [
         'name'        => 'test2',
         'entities_id' => 1,
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ];
      $packages_id_2 = $pfDeployPackage->add($input);
      $this->assertNotFalse($packages_id_2);
      $pfDeployPackage_Entity->add([
         'plugin_fusioninventory_deploypackages_id' => $packages_id_2,
         'entities_id' => 1
      ]);

      // Create task
      $pfDeployPackage->deployToComputer($computerId1, $packages_id_1, $_SESSION['glpiID']);
      $userId = $_SESSION['glpiID'];
      $_SESSION['glpiID'] = 2; // glpi user account
      $pfDeployPackage->deployToComputer($computerId2, $packages_id_1, $_SESSION['glpiID']);
      $_SESSION['glpiID'] = $userId;
      // Prepare task
      PluginFusioninventoryTask::cronTaskscheduler();

      $packages = $pfDeployPackage->getPackageForMe($userId);
      $packages_deploy = [];
      foreach ($packages as $data) {
         foreach ($data as $package_info) {
            if (isset($package_info['taskjobs_id'])) {
               $packages_deploy[] = $package_info['last_taskjobstate']['state'];
            }
         }
      }
      $reference = [
         'agents_prepared'
      ];
      $this->assertEquals($reference, $packages_deploy);
   }


   /**
    * @test
    */
   public function ReportComputerPackages() {

      $pfDeployPackage        = new PluginFusioninventoryDeployPackage();
      $computer               = new Computer();
      $pfAgent                = new PluginFusioninventoryAgent();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();
      $pfDeployGroup         = new PluginFusioninventoryDeployGroup();

      $pfDeployGroup->getFromDBByCrit(['name' => 'all']);

      $computer->getFromDBByCrit(['name' => 'pc01']);
      $computerId1 = $computer->fields['id'];

      $computerId3 = $computer->add([
         'name' => 'pc03',
         'entities_id' => 0
      ]);
      $this->assertNotFalse($computerId3);
      $pfAgent->add([
         'computers_id'=> $computerId3,
         'entities_id' => 0
      ]);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test1']);
      $pfDeployPackage->update([
         'id'                                     => $pfDeployPackage->fields['id'],
         'entities_id'                            => 0,
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ]);
      $packages_id = $pfDeployPackage->fields['id'];
      $pfDeployPackage_Entity->add([
         'plugin_fusioninventory_deploypackages_id' => $packages_id
      ]);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test2']);
      $pfDeployPackage->update([
         'id'                                     => $pfDeployPackage->fields['id'],
         'entities_id'                            => 0,
         'plugin_fusioninventory_deploygroups_id' => $pfDeployGroup->fields['id']
      ]);

      $pfDeployPackage_Entity->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id']
      ]);

      $input = [
         'name'                                   => 'test3',
         'entities_id'                            => 0,
         'plugin_fusioninventory_deploygroups_id' => 0
      ];
      $packages_id = $pfDeployPackage->add($input);
      $this->assertNotFalse($packages_id);

      $packages = $pfDeployPackage->getPackageForMe(false, $computerId1);
      $names    = [];

      foreach ($packages as $data) {
         foreach ($data as $packages_id => $package_info) {
            $names[] = $package_info['name'];
         }
      }

      $expected = ['test1', 'test2'];
      $this->assertEquals($names, $expected);

   }


   /**
    * @test
    */
   public function ReportComputerPackagesDeployDisabled() {

      // Disable deploy feature for all agents
      $module = new PluginFusioninventoryAgentmodule();
      $module->getFromDBByCrit(['modulename' => 'DEPLOY']);
      $module->update([
         'id'        => $module->fields['id'],
         'is_active' => 0
      ]);

      $pfDeployPackage        = new PluginFusioninventoryDeployPackage();
      $computer               = new Computer();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();
      $pfDeployGroup         = new PluginFusioninventoryDeployGroup();

      $pfDeployGroup->getFromDBByCrit(['name' => 'all']);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test1']);
      $pfDeployPackage_Entity->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id']
      ]);

      $pfDeployPackage->getFromDBByCrit(['name' => 'test2']);
      $pfDeployPackage_Entity->add([
         'plugin_fusioninventory_deploypackages_id' => $pfDeployPackage->fields['id']
      ]);

      $computer->getFromDBByCrit(['name' => 'pc03']);

      $packages = $pfDeployPackage->getPackageForMe(false, $computer->fields['id']);
      $names    = [];

      foreach ($packages as $data) {
         foreach ($data as $package_info) {
            $names[] = $package_info['name'];
         }
      }

      $expected = [];
      $this->assertEquals($names, $expected);
   }
}
