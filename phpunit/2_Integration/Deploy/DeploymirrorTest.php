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
   @author    Walid Nouh <wnouh@teclib.com>
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
   public function testGestList() {
      global $PF_CONFIG, $DB;

      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`, `tag`)
         VALUES (1, 'entity A', 0, 'Root entity > entity A', 2, 'entA')");

      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`, `tag`)
         VALUES (2, 'entity B', 0, 'Root entity > entity B', 2, 'entB')");

      $DB->query("INSERT INTO `glpi_plugin_fusioninventory_entities`
         (`id`, `entities_id`, `transfers_id_auto`, `agent_base_url`)
         VALUES (NULL, 0, 0, 'http://localhost:8080/glpi')");
      $DB->query("INSERT INTO `glpi_plugin_fusioninventory_entities`
         (`id`, `entities_id`, `transfers_id_auto`, `agent_base_url`)
         VALUES (NULL, 1, 0, 'http://localhost:8080/glpi')");
      $DB->query("INSERT INTO `glpi_plugin_fusioninventory_entities`
         (`id`, `entities_id`, `transfers_id_auto`, `agent_base_url`)
         VALUES (NULL, 2, 0, 'http://localhost:8080/glpi')");

      //Set root entity with child entities
      $_SESSION['glpiactive_entity']           = 0;
      $_SESSION['glpiactiveentities_string']   = "'0', '1', '2', '3'";
      $_SESSION['glpiparententities']          = [];
      $_SESSION['glpiactive_entity_recursive'] = 1;
      $_SESSION['glpishowallentities']         = 1;

      $location     = new Location();
      $locations_id = $location->add(['name'         => 'MyLocation',
                                      'entities_id'  => 0,
                                      'is_recursive' => 1
                                     ]);

      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $input = ['name'         => 'Mirror Location',
                'comment'      => 'MyComment',
                'url'          => 'http://localhost:8085/mirror',
                'entities_id'  => 0,
                'locations_id' => 1,
                'is_active'    => 0,
                'is_recursive' => 1
               ];
      $mirrors_locations_id = $pfDeploymirror->add($input);

      $pfDeploymirror = new PluginFusioninventoryDeployMirror();
      $input = ['name'        => 'Mirror Entity A',
                'comment'     => 'MyComment',
                'url'         => 'http://localhost:8087/mirror',
                'entities_id' => 0,
                'is_active'   => 1,
                'is_recursive'=> 1
               ];
      $mirrors1_id = $pfDeploymirror->add($input);

      $input = ['name'         => 'Mirror Entity B',
                'comment'      => 'MyComment',
                'url'          => 'http://localhost:8088/mirror',
                'entities_id'  => 1,
                'is_active'    => 1
               ];
      $mirrors2_id = $pfDeploymirror->add($input);

      $input = ['name'        => 'Mirror Entity C',
                'comment'     => 'MyComment',
                'url'         => 'http://localhost:8089/mirror',
                'entities_id' => 2,
                'is_active'   => 1
               ];
      $mirrors3_id = $pfDeploymirror->add($input);

      $computer = new Computer();
      $agent    = new PluginFusioninventoryAgent();

      $computers_id = $computer->add(['name'         => 'computer1',
                                      'serial'       => 'abcd',
                                      'entities_id'  => 0,
                                      'is_recursive' => 0,
                                     ]);
      $agents_id = $agent->add(['name'         => 'computer1-agent',
                                'computers_id' => $computers_id,
                                'entities_id'  => 0
                               ]);

      $computers2_id = $computer->add(['name'         => 'computer2',
                                       'serial'       => 'abcd',
                                       'entities_id'  => 2,
                                       'is_recursive' => 0,
                                       'locations_id' => 1
                                     ]);
      $agents2_id = $agent->add(['name'         => 'computer2-agent',
                                 'computers_id' => $computers2_id,
                                 'entities_id'  => 2
                                ]);

      $server_download_url = "http://localhost:8080/glpi/plugins/fusioninventory/b/deploy/?action=getFilePart&file=";

      //Add the server's url at the end of the mirrors list
      $PF_CONFIG['server_as_mirror'] = true;

      //-------------------------------------------------------------//
      //--- First set configuration to match mirror to locations
      //-------------------------------------------------------------//

      $PF_CONFIG['mirror_match'] = PluginFusioninventoryDeployMirror::MATCH_LOCATION;

      //The location mirror is disabled, so return the server's download url
      $mirrors = PluginFusioninventoryDeployMirror::getList($agents_id);
      $result = [ 0 => $server_download_url ];
      $this->assertEquals($result, $mirrors);

      //We enable the mirror
      $input = ['id' => $mirrors_locations_id, 'is_active' => 1];
      $pfDeploymirror->update($input);

      //We run the method again
      //In this case, the method must return the server's download location
      //because the computer has no location
      $mirrors = PluginFusioninventoryDeployMirror::getList($agents_id);
      $result  = [
                  0 => $server_download_url
                 ];
      $this->assertEquals($result, $mirrors);

      //We run the method again
      //But first we set a location for the computer
      $computer->update(['id' => $computers_id, 'locations_id' => 1]);

      //In this case, the method must return the mirror location url
      $mirrors = PluginFusioninventoryDeployMirror::getList($agents_id);
      $result  = [
                  0 => "http://localhost:8085/mirror",
                  1 => $server_download_url
                 ];
      $this->assertEquals($result, $mirrors);

      //We test the second computer which is in a child entity
      //As the location is visible in child entities, it should match
      $mirrors = PluginFusioninventoryDeployMirror::getList($agents2_id);
      $result  = [
                  0 => "http://localhost:8085/mirror",
                  1 => $server_download_url
                 ];
      $this->assertEquals($result, $mirrors);

      //-------------------------------------------------------------//
      //--- Second step : set configuration to match mirror to entities
      //-------------------------------------------------------------//

      $PF_CONFIG['mirror_match'] = PluginFusioninventoryDeployMirror::MATCH_ENTITY;

      $mirrors = PluginFusioninventoryDeployMirror::getList($agents_id);
      $result  = [
                  0 => "http://localhost:8087/mirror",
                  1 => $server_download_url
                 ];
      $this->assertEquals($result, $mirrors);

      //-------------------------------------------------------------//
      //--- Third step : set configuration to match mirror to entities
      //-------------------------------------------------------------//

      $PF_CONFIG['mirror_match'] = PluginFusioninventoryDeployMirror::MATCH_BOTH;

      $mirrors = PluginFusioninventoryDeployMirror::getList($agents_id);
      $result  = [
                  0 => "http://localhost:8085/mirror",
                  1 => "http://localhost:8087/mirror",
                  2 => $server_download_url
                 ];
      $this->assertEquals($result, $mirrors);

      $mirrors = PluginFusioninventoryDeployMirror::getList($agents_id);
      $result  = [
                  0 => "http://localhost:8085/mirror",
                  1 => "http://localhost:8087/mirror",
                  2 => $server_download_url
                 ];
      $this->assertEquals($result, $mirrors);

      //Set root entity with child entities
      $_SESSION['glpiactive_entity']           = 1;
      $_SESSION['glpiactiveentities_string']   = "'1'";
      $_SESSION['glpiparententities']          = [ 0 => 0 ];
      $_SESSION['glpiparententities']          = '0';
      $_SESSION['glpiactive_entity_recursive'] = 0;
      $_SESSION['glpishowallentities']         = 0;

      $mirrors = PluginFusioninventoryDeployMirror::getList($agents2_id);
      $result  = [
                  0 => "http://localhost:8089/mirror",
                  1 => "http://localhost:8085/mirror",
                  2 => "http://localhost:8087/mirror",
                  3 => $server_download_url
                 ];
      $this->assertEquals($result, $mirrors);

      $PF_CONFIG['server_as_mirror'] = false;

      $mirrors = PluginFusioninventoryDeployMirror::getList($agents2_id);
      $result  = [
                  0 => "http://localhost:8089/mirror",
                  1 => "http://localhost:8085/mirror",
                  2 => "http://localhost:8087/mirror"
                 ];
      $this->assertEquals($result, $mirrors);

      $PF_CONFIG['mirror_match'] = PluginFusioninventoryDeployMirror::MATCH_LOCATION;

      //We run the method again
      //In this case, the method must return the server's download location
      //because the computer has no location
      $mirrors = PluginFusioninventoryDeployMirror::getList($agents_id);
      $result  = [0 => 'http://localhost:8085/mirror'];
      $this->assertEquals($result, $mirrors);

   }

}
