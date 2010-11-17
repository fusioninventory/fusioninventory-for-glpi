<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class
 **/
class PluginFusinvinventoryLibhook {
    /**
    * Disable instance
    * @access private
    *
    */
    private function __construct()
    {
    }

    /**
    * create a new machine in an application
    * @access public
    * @return int $externalId Id to match application data with the library
    */
    public static function createMachine() {


      $Computer = new Computer;
      $input = array();
      $input['is_deleted'] = 0;
      $input['autoupdatesystems_id'] = Dropdown::importExternal('AutoUpdateSystem', 'FusionInventory');

      $computer_id = $Computer->add($input);

      if (defined('SOURCEXML')) {
         // TODO : Write in _plugins/fusinvinventory/xxx/idmachine.xml
         $folder = substr($computer_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$computer_id, 'w');
         fwrite($fileopen, SOURCEXML);
         fclose($fileopen);
       }

       return $computer_id;
    }

    /**
    * add a new section to the machine in an application
    * @access public
    * @param int $externalId
    * @param string $sectionName
    * @param array $dataSection
    * @return int $sectionId
    */
    public static function addSections($data, $idmachine) {
       global $DB;

      $Computer = new Computer;
      $sectionsId = array();
      $Computer->getFromDB($idmachine);

      $ignore_controllers = array();
      $ignore_USB = array();

      $i = -1;
      foreach($data as $section) {
         $i++;
         $dataSection = unserialize($section['dataSection']);
         switch ($section['sectionName']) {

            case 'BIOS':
               if ((isset($dataSection['SMANUFACTURER']))
                     AND (!empty($dataSection['SMANUFACTURER']))) {

                  $Computer->fields['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                          $dataSection['SMANUFACTURER']);
               } else if ((isset($dataSection['BMANUFACTURER']))
                            AND (!empty($dataSection['BMANUFACTURER']))) {

                  $Computer->fields['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                          $dataSection['BMANUFACTURER']);
               }
               if (isset($dataSection['SMODEL'])) {
                  $ComputerModel = new ComputerModel;
                  $Computer->fields['computermodels_id'] = $ComputerModel->import(array('name'=>$dataSection['SMODEL']));
               }
               if (isset($dataSection['SSN']))
                  $Computer->fields['serial'] = $dataSection['SSN'];

               break;

            case 'HARDWARE':
               if (isset($dataSection['NAME']))
                  $Computer->fields['name'] = $dataSection['NAME'];
               if (isset($dataSection['OSNAME'])) {
                  $OperatingSystem = new OperatingSystem;
                  $Computer->fields['operatingsystems_id'] = $OperatingSystem->import(array('name'=>$dataSection['OSNAME']));
               }
               if (isset($dataSection['OSVERSION'])) {
                  $OperatingSystemVersion = new OperatingSystemVersion;
                  $Computer->fields['operatingsystemversions_id'] = $OperatingSystemVersion->import(array('name'=>$dataSection['OSVERSION']));
               }
               if (isset($dataSection['WINPRODID'])) {
                  $Computer->fields['os_licenseid'] = $dataSection['WINPRODID'];
               }
               if (isset($dataSection['WINPRODKEY'])) {
                  $Computer->fields['os_license_number'] = $dataSection['WINPRODKEY'];
               }
               if (isset($dataSection['WORKGROUP'])) {
                  $Domain = new Domain;
                  $Computer->fields['domains_id'] = $Domain->import(array('name'=>$dataSection['WORKGROUP']));
               }
               break;

            case 'USERS':
               if (isset($dataSection['LOGIN'])) {
                  $Computer->fields['contact'] = $dataSection['LOGIN'];
                  $query = "SELECT `id`
                            FROM `glpi_users`
                            WHERE `name` = '" . $dataSection['LOGIN'] . "';";
                  $result = $DB->query($query);
                  if ($DB->numrows($result) == 1) {
                     $Computer->fields["users_id"] = $DB->result($result, 0, 0);
                  }
               }
               break;

            case 'SOUNDS':
               if (isset($dataSection['NAME'])) {
                  $ignore_controllers[$dataSection['NAME']] = 1;
               }
               break;

            case 'VIDEOS':
               if (isset($dataSection['NAME'])) {
                  $ignore_controllers[$dataSection['NAME']] = 1;
               }
               break;

            case 'PRINTERS':
               if (isset($dataSection['SERIAL'])) {
                  $ignore_USB[$dataSection['SERIAL']] = 1;
               }
               break;

         }
      }

      $Computer->update($Computer->fields);
      $j = 0;

      foreach($data as $section) {
         $dataSection = unserialize($section['dataSection']);
         switch ($section['sectionName']) {

            case 'CPUS':
               $PluginFusinvinventoryImport_Processor = new PluginFusinvinventoryImport_Processor();
               $id_processor = $PluginFusinvinventoryImport_Processor->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_processor)) {
                  $id_processor = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_processor);
               break;

            case 'DRIVES':
               $PluginFusinvinventoryImport_Drive = new PluginFusinvinventoryImport_Drive();
               $id_disk = $PluginFusinvinventoryImport_Drive->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_disk)) {
                  $id_disk = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_disk);
               break;

            case 'CONTROLLERS':
               $id_controller = '';
               if ((isset($dataSection["NAME"])) AND (!isset($ignore_controllers[$dataSection["NAME"]]))) {
                  $PluginFusinvinventoryImport_Controller = new PluginFusinvinventoryImport_Controller();
                  $id_controller = $PluginFusinvinventoryImport_Controller->AddUpdateItem("add", $idmachine, $dataSection);
               }
               if (empty($id_controller)) {
                  $id_controller = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_controller);
               break;

            case 'SOUNDS':
               $PluginFusinvinventoryImport_Sound = new PluginFusinvinventoryImport_Sound();
               $id_sound = $PluginFusinvinventoryImport_Sound->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_sound)) {
                  $id_sound = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_sound);
               break;

            case 'VIDEOS':
               $PluginFusinvinventoryImport_Graphiccard = new PluginFusinvinventoryImport_Graphiccard();
               $id_graphiccard = $PluginFusinvinventoryImport_Graphiccard->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_graphiccard)) {
                  $id_graphiccard = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_graphiccard);
               break;

            case 'MEMORIES':
               $PluginFusinvinventoryImport_Memory = new PluginFusinvinventoryImport_Memory();
               $id_memory = $PluginFusinvinventoryImport_Memory->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_memory)) {
                  $id_memory = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_memory);
               break;

            case 'NETWORKS':
               $NetworkPort = new NetworkPort();
               $network = array();
               $network['items_id']=$idmachine;
               $network['itemtype'] = 'Computer';
               if (isset($dataSection["DESCRIPTION"])) {
                  $network['name'] = addslashes($dataSection["DESCRIPTION"]);
               }
               if (isset($dataSection["IPADDRESS"])) {
                  $network['ip'] = $dataSection["IPADDRESS"];
               }
               if (isset($dataSection["MACADDR"])) {
                  $network['mac'] = $dataSection["MACADDR"];
               }
               if (isset($dataSection["TYPE"])) {
                  $network["networkinterfaces_id"]
                              = Dropdown::importExternal('NetworkInterface', $dataSection["TYPE"]);
               }
               if (isset($dataSection["IPMASK"]))
                  $network['netmask'] = $dataSection["IPMASK"];
               if (isset($dataSection["IPGATEWAY"]))
                  $network['gateway'] = $dataSection["IPGATEWAY"];
               if (isset($dataSection["IPSUBNET"]))
                  $network['subnet'] = $dataSection["IPSUBNET"];

               $devID = $NetworkPort->add($network);

               array_push($sectionsId,$section['sectionName']."/".$devID);
               break;

            case 'SOFTWARES':

               // Add software name
               // Add version of software
               // link version with computer : glpi_computers_softwareversions
               $PluginFusinvinventoryImport_Software = new PluginFusinvinventoryImport_Software;
               if (isset($dataSection['VERSION'])) {
                  $Computer_SoftwareVersion_id = $PluginFusinvinventoryImport_Software->addSoftware($idmachine, array('name'=>$dataSection['NAME'],
                                                                              'version'=>$dataSection['VERSION']));
               } else {
                  $Computer_SoftwareVersion_id = $PluginFusinvinventoryImport_Software->addSoftware($idmachine, array('name'=>$dataSection['NAME'],
                                                                              'version'=>''));
               }
               array_push($sectionsId,$section['sectionName']."/".$Computer_SoftwareVersion_id);
               break;

//              case 'INPUTS':
//                 $Peripheral = new Peripheral;
//
//
//                 break;

//            case 'VERSIONCLIENT':
//               // Verify agent is created
//               $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
//               $a_agent = $PluginFusioninventoryAgent->InfosByKey($section['sectionName']);
//               if (count($a_agent) == '0') {
//                  // TODO : Create agent
//
//               }
//               $PluginFusioninventoryAgent->getFromDB($a_agent['id']);
//               $PluginFusioninventoryAgent->fields['items_id'] = $idmachine;
//               $PluginFusioninventoryAgent->fields['itemtype'] = 'Computer';
//               $PluginFusioninventoryAgent->update($PluginFusioninventoryAgent->fields);
//               break;

            case 'BIOS':
               array_push($sectionsId,$section['sectionName']."/".$idmachine);
               break;


            case 'HARDWARE':
               array_push($sectionsId,$section['sectionName']."/".$idmachine);
               break;

            case 'USBDEVICES':
               if ((isset($dataSection['SERIAL'])) AND (isset($ignore_USB[$dataSection['SERIAL']]))) {
                  // Ignore
               } else {
                  $PluginFusinvinventoryImport_Peripheral =  new PluginFusinvinventoryImport_Peripheral();
                  $id_peripheral = $PluginFusinvinventoryImport_Peripheral->AddUpdateItem("add", $idmachine, $dataSection);
               }
               
               if (!isset($id_peripheral)) {
                  $id_peripheral = $j;
                  $j++;
               }

               array_push($sectionsId,$section['sectionName']."/".$id_peripheral);
               break;

            case 'PRINTERS':
               $PluginFusinvinventoryImport_Printer =  new PluginFusinvinventoryImport_Printer();
               $id_printer = $PluginFusinvinventoryImport_Printer->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_printer)) {
                  $id_printer = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_printer);
               break;

            case 'MONITORS':
               $PluginFusinvinventoryImport_Monitor =  new PluginFusinvinventoryImport_Monitor();
               $id_monitor = $PluginFusinvinventoryImport_Monitor->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_monitor)) {
                  $id_monitor = $j;
                  $j++;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_monitor);

               break;

            default:
               array_push($sectionsId,$section['sectionName']."/".$j);
               $j++;
               break;

         }
      }

      return $sectionsId;
    }

    /**
    * remove a machine's section in an application
    * @access public
    * @param int $externalId
    * @param string $sectionName
    * @param array $dataSection
    */
    public static function removeSections($idsections, $idmachine)
    {
        echo "section removed";

        // case 'CPUS':

        logInFile("removesection", "[".$idmachine."] ".print_r($idsections, true));


        foreach ($idsections as $section) {
            $split = explode("/", $section);
            $sectionName = $split[0];
            $items_id = $split[1];

            switch ($sectionName) {

               case 'SOFTWARES':
                  $PluginFusinvinventoryImport_Software = new PluginFusinvinventoryImport_Software();
                  $PluginFusinvinventoryImport_Software->removeSoftware($items_id);
                  break;
            }
           
        }

        $sectionsId = array();
        return $sectionsId;
    }



    public static function updateSections($data, $idmachine) {

      foreach($data as $section) {
         $dataSection = unserialize($section['dataSection']);
         $array = explode("/", $section['sectionId']);
         $items_id = $array[1];
         $sectionName = $array[0];

         switch ($sectionName) {

            case 'DRIVES':
               $PluginFusinvinventoryImport_Drive = new PluginFusinvinventoryImport_Drive();
               $PluginFusinvinventoryImport_Drive->AddUpdateItem("update", $items_id, $dataSection);
               break;

           case 'SOUNDS':
               $PluginFusinvinventoryImport_Sound = new PluginFusinvinventoryImport_Sound();
               $PluginFusinvinventoryImport_Sound->AddUpdateItem("update", $items_id, $dataSection);
               break;

            case 'VIDEOS':
               $PluginFusinvinventoryImport_Graphiccard = new PluginFusinvinventoryImport_Graphiccard();
               $PluginFusinvinventoryImport_Graphiccard->AddUpdateItem("update", $items_id, $dataSection);
               break;

            case 'CONTROLLERS':
               $id_controller = '';
               $PluginFusinvinventoryImport_Controller = new PluginFusinvinventoryImport_Controller();
               $id_controller = $PluginFusinvinventoryImport_Controller->AddUpdateItem("update", $items_id, $dataSection);
               break;

         }
      }



       logInFile("updatesection", "[".$idmachine."] ".print_r($data, true));
    }
}

?>