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

      $computer = new Computer();
      $user = new User();
      $pfAgent  = new PluginFusioninventoryAgent();
      $pfDeployGroup = new PluginFusioninventoryDeployGroup();
      $profile = new Profile();

      $users_id = $user->add(array('name' => 'David'));
      $computer->add(array('name' => 'pc01', 'entities_id' => 0, 'users_id' => $users_id));
      $pfAgent->add(array('computers_id'=> 1, 'entities_id' => 0));
      $pfDeployGroup->add(array('name' => 'all', 'type' => 'DYNAMIC'));
      $a_profile = current($profile->find("`interface`='helpdesk'", '', 1));

      $_SESSION['glpiID'] = $users_id;
      $_SESSION['glpiname'] = 'David';
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = "'0'";
      $_SESSION['glpigroups'] = array();
      $_SESSION['glpiactiveprofile'] = $a_profile;
      $_SESSION['glpiparententities'] = array();
   }



   /**
    * @test
    */
   public function PackageNoTarget() {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = array(
          'name'        => 'test1',
          'entities_id' => 0,
          'plugin_fusioninventory_deploygroups_id' => 0);
      $pfDeployPackage->add($input);
      $packages = $pfDeployPackage->can_user_deploy_self();
      $this->assertFalse($packages, 'May have no packages');
   }



   /**
    * @test
    */
   public function PackageTargetEntity() {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Entity = new PluginFusioninventoryDeployPackage_Entity();

      $input = array(
          'name'        => 'test1',
          'entities_id' => 0,
          'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id));

      $packages = $pfDeployPackage->can_user_deploy_self();
      $pfDeployPackage->getFromDB($packages_id);
      $reference = array(
          $packages_id => $pfDeployPackage->fields
      );
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }



   /**
    * @test
    */
   public function PackageTargetgroup() {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Group = new PluginFusioninventoryDeployPackage_Group();
      $group = new Group();

      $group->add(array('name' => 'self-deploy', 'entities_id' => 0));

      $input = array(
          'name'        => 'test1',
          'entities_id' => 0,
          'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);
      $pfDeployPackage_Group->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                        'groups_id' => 1,
                                        'entities_id' => 0));
      $packages = $pfDeployPackage->can_user_deploy_self();
      $this->assertFalse($packages, 'May have no packages');

      $_SESSION['glpigroups'] = array(0 => 1);

      $packages = $pfDeployPackage->can_user_deploy_self();
      $pfDeployPackage->getFromDB($packages_id);
      $reference = array(
          $packages_id => $pfDeployPackage->fields
      );
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }



   /**
    * @test
    */
   public function PackageTargetUser() {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_User = new PluginFusioninventoryDeployPackage_User();

      $input = array(
          'name'        => 'test1',
          'entities_id' => 0,
          'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);

      $pfDeployPackage_User->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                        'users_id' => 1));
      $packages = $pfDeployPackage->can_user_deploy_self();
      $this->assertFalse($packages, 'May have no packages');

      $pfDeployPackage_User->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                        'users_id' => $_SESSION['glpiID']));

      $packages = $pfDeployPackage->can_user_deploy_self();
      $pfDeployPackage->getFromDB($packages_id);
      $reference = array(
          $packages_id => $pfDeployPackage->fields
      );
      $this->assertEquals($reference, $packages, 'May have 1 package');
   }



   /**
    * @test
    */
   public function PackageTargetProfile() {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage_Profile = new PluginFusioninventoryDeployPackage_Profile();

      $input = array(
          'name'        => 'test1',
          'entities_id' => 0,
          'plugin_fusioninventory_deploygroups_id' => 1);
      $packages_id = $pfDeployPackage->add($input);

      $pfDeployPackage_Profile->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                          'profiles_id' => 4));
      $packages = $pfDeployPackage->can_user_deploy_self();
      $this->assertFalse($packages, 'May have no packages');

      $pfDeployPackage_Profile->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id,
                                        'profiles_id' => $_SESSION['glpiactiveprofile']['id']));

      $packages = $pfDeployPackage->can_user_deploy_self();
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
      $pfDeployPackage_Entity->add(array('plugin_fusioninventory_deploypackages_id' => $packages_id));

      // Create task
      $pfDeployPackage->deploy_to_computer(1, $packages_id, $_SESSION['glpiID']);
      $users_id = $_SESSION['glpiID'];
      $_SESSION['glpiID'] = 2;
      $pfDeployPackage->deploy_to_computer(2, $packages_id, $_SESSION['glpiID']);
      $_SESSION['glpiID'] = $users_id;
      // Prepare task
      PluginFusioninventoryTask::cronTaskscheduler();

      $packages = $pfDeployPackage->get_package_for_me($users_id);
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

}
