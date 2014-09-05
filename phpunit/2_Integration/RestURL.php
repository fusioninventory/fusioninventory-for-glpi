<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class RestURL extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function getCollectURLRootEntity() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfAgent = new PluginFusioninventoryAgent();

      $input = array(
          'name'        => 'toto',
          'entities_id' => 0,
          'device_id'   => 'toto-device'
      );
      $agents_id = $pfAgent->add($input);

      $config = new PluginFusioninventoryConfig();
      $config->updateValue('agent_base_url', 'http://127.0.0.1/glpi085');
      $config->loadCache();

      // Get answer
      $input = array(
          'action'    => 'getConfig',
          'task'      => array('COLLECT' => '1.0.0'),
          'machineid' => 'toto-device'
      );

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://127.0.0.1/glpi085/plugins/fusioninventory/b/collect/',
                          $response['schedule'][0]['remote'],
                          'May have only 1 computerdisk');
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

      $pfAgent = new PluginFusioninventoryAgent();

      $input = array(
          'name'        => 'toto',
          'entities_id' => 0,
          'device_id'   => 'toto-device'
      );
      $agents_id = $pfAgent->add($input);

      $config = new PluginFusioninventoryConfig();
      $config->updateValue('agent_base_url', 'http://127.0.0.1/glpi085');
      $config->loadCache();

      // Get answer
      $input = array(
          'action'    => 'getConfig',
          'task'      => array('Deploy' => '1.0.0'),
          'machineid' => 'toto-device'
      );

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://127.0.0.1/glpi085/plugins/fusioninventory/b/deploy/',
                          $response['schedule'][0]['remote'],
                          'May have only 1 computerdisk');
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

      $pfAgent = new PluginFusioninventoryAgent();

      $input = array(
          'name'        => 'toto',
          'entities_id' => 0,
          'device_id'   => 'toto-device'
      );
      $agents_id = $pfAgent->add($input);

      $config = new PluginFusioninventoryConfig();
      $config->updateValue('agent_base_url', 'http://127.0.0.1/glpi085');
      $config->loadCache();

      // Get answer
      $input = array(
          'action'    => 'getConfig',
          'task'      => array('ESX' => '1.0.0'),
          'machineid' => 'toto-device'
      );

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://127.0.0.1/glpi085/plugins/fusioninventory/b/esx/',
                          $response['schedule'][0]['remote'],
                          'May have only 1 computerdisk');
   }

}
?>
