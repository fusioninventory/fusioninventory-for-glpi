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

class RestURLTest extends RestoreDatabase_TestCase {



   /**
    * @test
    */
   public function prepareDB() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $query = "INSERT INTO `glpi_entities` "
              . " (`id`, `name`, `entities_id`, `level`) "
              . " VALUES ('1', 'ent1', '0', '2')";
      $DB->query($query);
      $entities_id = 1;

      $pfAgent = new PluginFusioninventoryAgent();

      $input = array(
          'name'        => 'toto',
          'entities_id' => $entities_id,
          'device_id'   => 'toto-device'
      );
      $agents_id = $pfAgent->add($input);

      $config = new PluginFusioninventoryConfig();
      $config->loadCache();

      $pfEntity = new PluginFusioninventoryEntity();
      $input = array(
              'id'             => 1,
              'entities_id'    => 0,
              'agent_base_url' => 'http://127.0.0.1/glpi085');
      $pfEntity->update($input);
      $input = array(
              'entities_id'    => $entities_id,
              'agent_base_url' => 'http://10.0.2.2/glpi085');
      $pfEntity->add($input);

      // active all modules
      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`"
              . " SET `is_active`='1'";
      $DB->query($query);
   }



   /**
    * @test
    */
   public function getCollectURLEnt1Entity() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $entities_id = 1;
      $agents_id = 1;

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $input = array(
         'itemtype'                         => 'PluginFusioninventoryCollect',
         'plugin_fusioninventory_agents_id' => 1
      );
      $pfTaskjobstate->add($input);

      // Get answer
      $input = array(
          'action'    => 'getConfig',
          'task'      => array('COLLECT' => '1.0.0'),
          'machineid' => 'toto-device'
      );

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://10.0.2.2/glpi085/plugins/fusioninventory/b/collect/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }



   /**
    * @test
    */
   public function getDeployURLRootEntity() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $entities_id = 1;
      $agents_id = 1;

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $input = array(
         'itemtype'                         => 'PluginFusioninventoryDeployPackage',
         'plugin_fusioninventory_agents_id' => 1
      );
      $pfTaskjobstate->add($input);

      // Get answer
      $input = array(
          'action'    => 'getConfig',
          'task'      => array('Deploy' => '1.0.0'),
          'machineid' => 'toto-device'
      );

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://10.0.2.2/glpi085/plugins/fusioninventory/b/deploy/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }



   /**
    * @test
    */
   public function getESXURLRootEntity() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $entities_id = 1;
      $agents_id = 1;

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $input = array(
         'itemtype'                         => 'PluginFusioninventoryCredentialIp',
         'plugin_fusioninventory_agents_id' => 1
      );
      $pfTaskjobstate->add($input);

      // Get answer
      $input = array(
          'action'    => 'getConfig',
          'task'      => array('ESX' => '1.0.0'),
          'machineid' => 'toto-device'
      );

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://10.0.2.2/glpi085/plugins/fusioninventory/b/esx/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }



   /**
    * @test
    */
   public function getCollectURLRootEntity() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $entities_id = 1;
      $agents_id = 1;

      $config = new PluginFusioninventoryConfig();
      $config->loadCache();

      $pfEntity = new PluginFusioninventoryEntity();
      $pfEntity->delete(array('id' => 2));

      // Get answer
      $input = array(
          'action'    => 'getConfig',
          'task'      => array('COLLECT' => '1.0.0'),
          'machineid' => 'toto-device'
      );

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://127.0.0.1/glpi085/plugins/fusioninventory/b/collect/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }

}
?>
