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


class SoftwareVersionAddTest extends RestoreDatabase_TestCase {


   public function dataprovider() {

      $filename = pathinfo(__FILE__);
      $json_filename = implode(
         DIRECTORY_SEPARATOR,
         array(
            $filename['dirname'],
            $filename['filename']
         )
      ).".json";

      $jsondata = json_decode(
         file_get_contents( $json_filename ),
         TRUE
      );

      return $jsondata['data'];
   }

   /**
    * @test
    * @dataProvider dataprovider
    */
   public function AddComputer($data) {
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();

      $inventory = array();
      $inventory['CONTENT'] = $data['inventory']['CONTENT'];

      // ** Add agent
      $pfAgent = new PluginFusioninventoryAgent();
      $agent_name = $data['inventory']['AGENT']['name'];
      $computer_name = $data['inventory']['CONTENT']['HARDWARE']['NAME'];
      $agents_id = $pfAgent->add($data['inventory']['AGENT']);
      $_SESSION['plugin_fusioninventory_agents_id'] = $agents_id;

      // ** Add
      $pfiComputerInv->import($data['inventory']['AGENT']['device_id'], "", $inventory); // creation

      $this->CountSoftwares($data);
      $this->CountVersions($data);
   }

   public function CountSoftwares($data) {
      $agent_name = $data['inventory']['AGENT']['name'];
      $computer_name = $data['inventory']['CONTENT']['HARDWARE']['NAME'];
      $nb_softwares_in_database = countElementsInTable("glpi_softwares");
      $this->assertEquals(
         $data['expected_results']['nb_softwares'],
         $nb_softwares_in_database,
         "The number of softwares expected in database doesn't match after importing \n".
         "inventory of agent ".$agent_name." (Computer ".$computer_name.").\n".
         "The database counts ".$nb_softwares_in_database." versions while there should be \n".
         $data['expected_results']['nb_softwares']."."
      );

   }


   public function CountVersions($data) {
      $agent_name = $data['inventory']['AGENT']['name'];
      $computer_name = $data['inventory']['CONTENT']['HARDWARE']['NAME'];
      $nb_versions_in_database = countElementsInTable("glpi_softwareversions");
      $this->assertEquals(
         $data['expected_results']['nb_versions'],
         $nb_versions_in_database,
         "The number of versions expected in database doesn't match after importing \n".
         "inventory of agent ".$agent_name." (Computer ".$computer_name.").\n".
         "The database counts ".$nb_versions_in_database." versions while there should be ".
         $data['expected_results']['nb_versions']."."
      );
   }
}
?>
