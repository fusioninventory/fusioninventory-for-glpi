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

class RemovableMediaImportTest extends TestCase {


   public function disabledDataProvider() {

      $filename = pathinfo(__FILE__);
      $json_filename = implode(
            DIRECTORY_SEPARATOR,
            [
                  $filename['dirname'],
                  $filename['filename']
            ]
            ).".json";

            $jsondata = json_decode(
                  file_get_contents( $json_filename ),
                  true
                  );

            return $jsondata['data'];
   }


   /**
    * @test
    * @dataProvider disabledDataProvider
    */
   public function importWithRemovableMedia($data) {
      global $PF_CONFIG;

      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';

      unset($PF_CONFIG['component_removablemedia']);
      $pfConfig = new PluginFusioninventoryConfig();
      $pfConfig->updateValue('component_removablemedia', '1');

      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();

      $inventory = [];
      $inventory['CONTENT'] = $data['inventory']['CONTENT'];

      // ** Add agent
      $pfAgent = new PluginFusioninventoryAgent();
      $agents_id = $pfAgent->add($data['inventory']['AGENT']);
      $_SESSION['plugin_fusioninventory_agents_id'] = $agents_id;

      // ** Add
      $pfiComputerInv->import($data['inventory']['AGENT']['device_id'], "", $inventory); // creation

      $this->countDrivesWhenEnabled($data);
   }


   /**
    * @test
    * @dataProvider disabledDataProvider
    */
   public function importWithoutRemovableMedia($data) {
      global $PF_CONFIG;

      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';

      unset($PF_CONFIG['component_removablemedia']);
      $pfConfig = new PluginFusioninventoryConfig();
      $pfConfig->updateValue('component_removablemedia', '0');

      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();

      $inventory = [];
      $inventory['CONTENT'] = $data['inventory']['CONTENT'];

      // ** Add agent
      $pfAgent = new PluginFusioninventoryAgent();
      $agents_id = $pfAgent->add($data['inventory']['AGENT']);
      $_SESSION['plugin_fusioninventory_agents_id'] = $agents_id;

      // ** Add
      $pfiComputerInv->import($data['inventory']['AGENT']['device_id'], "", $inventory); // creation

      $this->countDrivesWhenDisabled($data);
   }


   public function countDrivesWhenDisabled($data) {
      $agent_name = $data['inventory']['AGENT']['name'];
      $computer_name = $data['inventory']['CONTENT']['HARDWARE']['NAME'];
      $nb_drives_in_database = countElementsInTable("glpi_items_disks",
         ['itemtype' => 'Computer']);
      $nb_expected_drives = $data['expected_results']['nb_drives_when_disabled'];
      $this->assertEquals(
            $nb_expected_drives,
            $nb_drives_in_database,
            "The number of drives expected in database doesn't match after importing \n".
            "inventory of agent ".$agent_name." (Computer ".$computer_name.").\n".
            "The database counts ".$nb_drives_in_database." versions while there should be \n".
            $nb_expected_drives."."
            );

   }


   public function countDrivesWhenEnabled($data) {
      $agent_name = $data['inventory']['AGENT']['name'];
      $computer_name = $data['inventory']['CONTENT']['HARDWARE']['NAME'];
      $nb_drives_in_database = countElementsInTable("glpi_items_disks",
         ['itemtype' => 'Computer']);
      $nb_expected_drives = $data['expected_results']['nb_drives_when_enabled'];
      $this->assertEquals(
            $nb_expected_drives,
            $nb_drives_in_database,
            "The number of drives expected in database doesn't match after importing \n".
            "inventory of agent ".$agent_name." (Computer ".$computer_name.").\n".
            "The database counts ".$nb_drives_in_database." versions while there should be \n".
            $nb_expected_drives."."
            );

   }
}
