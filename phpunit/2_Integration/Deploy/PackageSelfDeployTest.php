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

class PackageSelfDeployTest extends RestoreDatabase_TestCase {


   protected function setUp() {

      parent::setUp();

      self::restore_database();

      $computer      = new Computer();
      $user          = new User();
      $pfAgent       = new PluginFusioninventoryAgent();
      $pfDeployGroup = new PluginFusioninventoryDeployGroup();
      $profile       = new Profile();

      $users_id = $user->add(['name' => 'David']);
      $computer->add(['name'        => 'pc01',
                      'entities_id' => 0,
                      'users_id'    => $users_id
                     ]);
      $pfAgent->add(['computers_id'=> 1, 'entities_id' => 0]);
      $pfDeployGroup->add(['name' => 'all', 'type' => 'DYNAMIC']);
      $a_profile = current($profile->find("`interface`='helpdesk'", '', 1));

      $_SESSION['glpiID']                    = $users_id;
      $_SESSION['glpiname']                  = 'David';
      $_SESSION['glpiactive_entity']         = 0;
      $_SESSION['glpiactiveentities_string'] = "'0'";
      $_SESSION['glpigroups']                = [];
      $_SESSION['glpiactiveprofile']         = $a_profile;
      $_SESSION['glpiparententities']        = [];
   }



   /**
    * @test
    */
   public function PackageNoTarget() {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'plugin_fusioninventory_deploygroups_id' => 0
               ];
      $pfDeployPackage->add($input);
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');
   }



   /**
    * @test
    */
   public function PackageTargetEntity() {

      $pfDeployPackage        = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();

      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'plugin_fusioninventory_deploygroups_id' => 1
               ];
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id));

      $packages = $pfDeployPackage->canUserDeploySelf();
      $pfDeployPackage->getFromDB($packages_id);
      $reference = [$packages_id => $pfDeployPackage->fields];
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }



   /**
    * @test
    */
   public function PackageTargetgroup() {

      $pfDeployPackage       = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Group = new PluginFusioninventoryDeployPackage_Group();
      $group                 = new Group();

      $group->add(['name' => 'self-deploy', 'entities_id' => 0]);

      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'plugin_fusioninventory_deploygroups_id' => 1
               ];
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Group->add(['plugin_fusioninventory_deploypackages_id' => $packages_id,
                                    'groups_id'   => 1,
                                    'entities_id' => 0
                                  ]);
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');

      $_SESSION['glpigroups'] = [0 => 1];

      $packages = $pfDeployPackage->canUserDeploySelf();
      $pfDeployPackage->getFromDB($packages_id);
      $reference = [$packages_id => $pfDeployPackage->fields];
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }



   /**
    * @test
    */
   public function PackageTargetUser() {

      $pfDeployPackage      = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_User = new PluginFusioninventoryDeployPackage_User();

      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'plugin_fusioninventory_deploygroups_id' => 1
               ];
      $packages_id = $pfDeployPackage->add($input);

      $pfDeployPackage_User->add(['plugin_fusioninventory_deploypackages_id' => $packages_id,
                                  'users_id' => 1
                                 ]);
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');

      $pfDeployPackage_User->add(['plugin_fusioninventory_deploypackages_id' => $packages_id,
                                  'users_id' => $_SESSION['glpiID']
                                 ]);

      $packages = $pfDeployPackage->canUserDeploySelf();
      $pfDeployPackage->getFromDB($packages_id);
      $reference = [$packages_id => $pfDeployPackage->fields];
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }



   /**
    * @test
    */
   public function PackageTargetProfile() {

      $pfDeployPackage         = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Profile = new PluginFusioninventoryDeployPackage_Profile();

      $input = array(
          'name'        => 'test1',
          'entities_id' => 0,
          'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);

      $pfDeployPackage_Profile->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                          'profiles_id' => 4));
      $packages = $pfDeployPackage->canUserDeploySelf();
      $this->assertFalse($packages, 'May have no packages');

      $pfDeployPackage_Profile->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                        'profiles_id' => $_SESSION['glpiactiveprofile']['id']));

      $packages = $pfDeployPackage->canUserDeploySelf();
      $pfDeployPackage->getFromDB($packages_id);
      $reference = array(
          $packages_id => $pfDeployPackage->fields
      );
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }



   /**
    * @test
    */
   public function ReportMyPackage() {

      //Enable deploy feature for all agents
      $module = new PluginFusioninventoryAgentmodule();
      $module->update(['id' => 6, 'is_active' => 1]);

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $computer        = new Computer();
      $pfAgent         = new PluginFusioninventoryAgent();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();

      $computer->add(array('name' => 'pc02', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 2, 'entities_id' => 0));

      $input = array(
          'name'        => 'test1',
          'entities_id' => 0,
          'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                         'entities_id' => 0));

      //The second package, test2, is not in the same entity, and is not recursive
      //It should not be visible when requesting the list of packages the the user
      //can deploy
      $input = array(
                     'name'        => 'test2',
                     'entities_id' => 1,
                     'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id_2 = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                         'entities_id' => 1));

      // Create task
      $pfDeployPackage->deployToComputer(1, $packages_id, $_SESSION['glpiID']);
      $users_id = $_SESSION['glpiID'];
      $_SESSION['glpiID'] = 2;
      $pfDeployPackage->deployToComputer(2, $packages_id, $_SESSION['glpiID']);
      $_SESSION['glpiID'] = $users_id;
      // Prepare task
      PluginFusioninventoryTask::cronTaskscheduler();

      $packages = $pfDeployPackage->getPackageForMe($users_id);
      $packages_deploy = array();
      foreach ($packages as $computers_id=>$data) {
         foreach ($data as $packages_id => $package_info) {
            if (isset($package_info['taskjobs_id'])) {
                $packages_deploy[$package_info['taskjobs_id']] = $package_info['last_taskjobstate']['state'];
            }
         }
      }
      $reference = array(
          1 => 'agents_prepared'
      );
      $this->assertEquals($reference, $packages_deploy);
   }

   /**
    * @test
    */
   public function ReportComputerPackages() {

      //Enable deploy feature for all agents
      $module = new PluginFusioninventoryAgentmodule();
      $module->update(['id' => 6, 'is_active' => 1]);

      $pfDeployPackage        = new PluginFusioninventoryDeployPackage();
      $computer               = new Computer();
      $pfAgent                = new PluginFusioninventoryAgent();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();

      $computer->add(array('name' => 'pc03', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 3, 'entities_id' => 0));

      $input = array('name'                                   => 'test1',
                     'entities_id'                            => 0,
                     'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id));

      $input = array('name'                                   => 'test2',
                     'entities_id'                            => 0,
                     'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id));

      $input = array('name'                                   => 'test3',
                     'entities_id'                            => 0,
                     'plugin_fusioninventory_deploygroups_id' => 0);
      $packages_id = $pfDeployPackage->add($input);

      $packages = $pfDeployPackage->getPackageForMe(false, 1);
      $names    = [];

      foreach ($packages as $computers_id=>$data) {
         foreach ($data as $packages_id => $package_info) {
            $names[] = $package_info['name'];
         }
      }

      $expected = ['test1', 'test2'];
      $this->assertEquals($names, $expected);

      $packages = $pfDeployPackage->getPackageForMe(false, 3);
      $this->assertEquals($packages, []);

   }

   /**
    * @test
    */
   public function ReportComputerPackagesDeployDisabled() {

      //Enable deploy feature for all agents
      $module = new PluginFusioninventoryAgentmodule();
      $module->update(['id' => 6, 'is_active' => 0]);

      $pfDeployPackage        = new PluginFusioninventoryDeployPackage();
      $computer               = new Computer();
      $pfAgent                = new PluginFusioninventoryAgent();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();

      $computer->add(array('name' => 'pc03', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 3, 'entities_id' => 0));

      $input = array('name'                                   => 'test1',
                     'entities_id'                            => 0,
                     'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id));

      $input = array('name'                                   => 'test2',
                     'entities_id'                            => 0,
                     'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id));

      $packages = $pfDeployPackage->getPackageForMe(false, 1);
      $names    = [];

      foreach ($packages as $computers_id=>$data) {
         foreach ($data as $packages_id => $package_info) {
            $names[] = $package_info['name'];
         }
      }

      $expected = [];
      $this->assertEquals($names, $expected);
   }

}
