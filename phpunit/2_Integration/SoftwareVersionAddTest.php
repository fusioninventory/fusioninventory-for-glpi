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
    * @dataProvider dataprovider
    */
   public function AddComputer($data) {
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();

      $inventory = [];
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


   /**
    * @test
    */
   public function newComputerSoftwareInstalldate() {

       $arrayinventory = [
           'CONTENT' => [
               'HARDWARE' => [],
               'SOFTWARES' => [
                   [
                       'ARCH' => 'i586',
                       'FROM' => 'registry',
                       'GUID' => 'ActiveTouchMeetingClient',
                       'HELPLINK' => 'support.webex.com/',
                       'NAME' => 'Cisco WebEx Meetings',
                       'PUBLISHER' => 'Cisco WebEx LLC',
                       'UNINSTALL_STRING' => 'C:\PROGRA~2\WebEx\atcliun.exe',
                       'URL_INFO_ABOUT' => 'www.webex.com',
                   ],
                   [
                       'ARCH' => 'i586',
                       'FROM' => 'registry',
                       'GUID' => 'Adobe AIR',
                       'NAME' => 'Adobe AIR',
                       'PUBLISHER' => 'Adobe Systems Incorporated',
                       'UNINSTALL_STRING' => 'C:\Program Files\Common Files\Adobe AIR\Versions\1.0\Resources\Adobe AIR Updater.exe -arp:uninstall',
                       'VERSION' => '4.0.0.1390',
                   ]
               ]
           ]
       ];
       $pfici = new PluginFusioninventoryInventoryComputerInventory();
       $software = new Software();
       $csv = new Computer_SoftwareVersion();

       // first create without install_date
       $pfici->sendCriteria('TESTAAAA', $arrayinventory);
       $soft_ids = array_keys($software->find());
       $csoftv_ids = array_keys($csv->find());

       // update with install_date
       $dates = [
           ['06/02/2014', '27/01/2014'],
           ['10/05/2016', '27/11/2015'],
       ];
       foreach ($dates as $data_date) {
          $arrayinventory['CONTENT']['SOFTWARES'][0]['INSTALLDATE'] = $data_date[0];
          $arrayinventory['CONTENT']['SOFTWARES'][1]['INSTALLDATE'] = $data_date[1];
          $pfici->sendCriteria('TESTAAAA', $arrayinventory);
          $this->assertEquals($soft_ids, array_keys($software->find()));
          $this->assertEquals($csoftv_ids, array_keys($csv->find()));
          foreach ($software->find() as $soft) {
             if ($soft['name'] == 'Cisco WebEx Meetings') {
                $csversion = current($csv->find(['softwareversions_id' => $soft['id']]));
                $this->assertEquals($data_date[0], $csversion['date_install']);
               } else if ($soft['name'] == 'Adobe Systems Incorporated') {
                  $csversion = current($csv->find(['softwareversions_id' => $soft['id']]));
                  $this->assertEquals($data_date[1], $csversion['date_install']);
               }
            }
         }

         // remove an installdate
         unset($arrayinventory['CONTENT']['SOFTWARES'][0]['INSTALLDATE']);
         $pfici->sendCriteria('TESTAAAA', $arrayinventory);
         $this->assertEquals($soft_ids, array_keys($software->find()));
         $this->assertEquals($csoftv_ids, array_keys($csv->find()));
         foreach ($software->find() as $soft) {
            if ($soft['name'] == 'Cisco WebEx Meetings') {
               $csversion = current($csv->find(['softwareversions_id' => $soft['id']]));
               $this->assertEquals('', $csversion['date_install']);
            }
         }
   }


   /**
    * @test
    */
   public function newComputerSoftwareOS() {

      $arrayinventory = [
          'CONTENT' => [
              'HARDWARE' => [
                  'NAME' => 'portdavid'
              ],
              'OPERATINGSYSTEM' => [
                  'ARCH'           => '32-bit',
                  'BOOT_TIME'      => '2016-04-06 11:56:40',
                  'FULL_NAME'      => 'Microsoft Windows 7',
                  'INSTALL_DATE'   => '2015-05-22 15:53:53',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.1.7601',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 1',
               ],
               'SOFTWARES' => [
                   [
                       'ARCH' => 'i586',
                       'FROM' => 'registry',
                       'GUID' => 'ActiveTouchMeetingClient',
                       'HELPLINK' => 'support.webex.com/',
                       'NAME' => 'Cisco WebEx Meetings',
                       'PUBLISHER' => 'Cisco WebEx LLC',
                       'UNINSTALL_STRING' => 'C:\PROGRA~2\WebEx\atcliun.exe',
                       'URL_INFO_ABOUT' => 'www.webex.com',
                   ],
                   [
                       'ARCH' => 'i586',
                       'FROM' => 'registry',
                       'GUID' => 'Adobe AIR',
                       'NAME' => 'Adobe AIR',
                       'PUBLISHER' => 'Adobe Systems Incorporated',
                       'UNINSTALL_STRING' => 'C:\Program Files\Common Files\Adobe AIR\Versions\1.0\Resources\Adobe AIR Updater.exe -arp:uninstall',
                       'VERSION' => '4.0.0.1390',
                   ]
               ]
           ]
      ];
      $pfici = new PluginFusioninventoryInventoryComputerInventory();
      $software = new Software();
      $softwareVersion = new SoftwareVersion();
      $csv = new Computer_SoftwareVersion();

      $soft_ids = array_keys($software->find());

      $pfici->sendCriteria('TESTAAAA', $arrayinventory);
      $softversion_ids = array_keys($softwareVersion->find());

      $arrayinventory['CONTENT']['HARDWARE']['NAME'] = 'portdavid_2';
      $pfici->sendCriteria('TESTAAAA', $arrayinventory);
      $this->assertEquals($softversion_ids, array_keys($softwareVersion->find()));

      $arrayinventory['CONTENT']['OPERATINGSYSTEM']['NAME'] = 'Windows XP';
      $pfici->sendCriteria('TESTAAAA', $arrayinventory);
      $this->assertEquals(count($softversion_ids) + 2, count(array_keys($softwareVersion->find())));

      $arrayinventory['CONTENT']['HARDWARE']['NAME'] = 'portdavid_3';
      $arrayinventory['CONTENT']['OPERATINGSYSTEM']['NAME'] = 'Windows';
      $pfici->sendCriteria('TESTAAAA', $arrayinventory);
      $this->assertEquals(count($softversion_ids) + 2, count(array_keys($softwareVersion->find())));

   }
}
