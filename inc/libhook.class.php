<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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
    function __construct() {
       $_SESSION["plugin_fusinvinventory_history_add"] = true;
       $_SESSION["plugin_fusinvinventory_no_history_add"] = false;
    }

    /**
    * create a new machine in an application
    * @access public
    * @return int $externalId Id to match application data with the library
    */
    public static function createMachine($items_id) {

      $PluginFusinvinventoryLibhook = new PluginFusinvinventoryLibhook();

      $_SESSION["plugin_fusinvinventory_history_add"] = false;
      $_SESSION["plugin_fusinvinventory_no_history_add"] = true;
       // Else create computer
      $Computer = new Computer;
      $Computer->getFromDB($items_id);
      $input = $Computer->fields;
      $input['is_deleted'] = 0;
      $input['autoupdatesystems_id'] = Dropdown::importExternal('AutoUpdateSystem', 'FusionInventory');
      $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];

      $Computer->update($input, 0);

      $PluginFusinvinventoryLibhook->writeXMLFusion($items_id);
       
       $changes = array();
       $changes[0]='0';
       $changes[1]="";
       $changes[2]='Create computer by FusionInventory';
       Log::history($items_id,'Computer',$changes, 0, HISTORY_LOG_SIMPLE_MESSAGE);

       // Link computer to agent FusionInventory
       $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
       $PluginFusioninventoryAgent->setAgentWithComputerid($items_id, $xml->DEVICEID);

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

      logInFile("addsection", "[".$idmachine."] ".print_r($data, true));

      $Computer = new Computer;
      $PluginFusinvinventoryComputer = new PluginFusinvinventoryComputer();

      $sectionsId = array();
      $Computer->getFromDB($idmachine);

      $a_ids = $PluginFusinvinventoryComputer->find("`items_id`='".$idmachine."'");
      if (count($a_ids) > 0) {
         $a_id = current($a_ids);
         $PluginFusinvinventoryComputer->getFromDB($a_id['id']);
      } else {
         $input = array();
         $input['items_id'] = $idmachine;
         $PluginFusinvinventoryComputer->getFromDB($PluginFusinvinventoryComputer->add($input));

      }


      $_SESSION["plugin_fusinvinventory_entity"] = $Computer->fields['entities_id'];

      if (!isset($_SESSION["plugin_fusinvinventory_history_add"])) {
         $_SESSION["plugin_fusinvinventory_history_add"] = true;
      }
      if (!isset($_SESSION["plugin_fusinvinventory_no_history_add"])) {
         $_SESSION["plugin_fusinvinventory_no_history_add"] = false;
      }

      $ignore_controllers = array();
      $ignore_USB = array();

      $i = -1;
      foreach($data as $section) {
         $i++;
         $dataSection = unserialize($section['dataSection']);
         foreach($dataSection as $key=>$value) {
            $dataSection[$key] = addslashes_deep($value);
         }
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

               if (isset($dataSection['TYPE'])) {
                  $ComputerType = new ComputerType();
                  $Computer->fields['computertypes_id'] = Dropdown::importExternal('ComputerType',
                                                                          $dataSection['TYPE']);
               }
               break;

            case 'HARDWARE':
               $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

               if (isset($dataSection['NAME'])) {
                  if (!in_array('name', $a_lockable)) {
                     $Computer->fields['name'] = $dataSection['NAME'];
                  }
               }
               if (isset($dataSection['OSNAME'])) {
                  if (!in_array('operatingsystems_id', $a_lockable)) {
                     $OperatingSystem = new OperatingSystem;
                     $Computer->fields['operatingsystems_id'] = $OperatingSystem->import(array('name'=>$dataSection['OSNAME']));
                  }
               }
               if (isset($dataSection['OSVERSION'])) {
                  if (!in_array('operatingsystemversions_id', $a_lockable)) {
                     $OperatingSystemVersion = new OperatingSystemVersion;
                     $Computer->fields['operatingsystemversions_id'] = $OperatingSystemVersion->import(array('name'=>$dataSection['OSVERSION']));
                  }
               }
               if (isset($dataSection['WINPRODID'])) {
                  if (!in_array('os_licenseid', $a_lockable)) {
                     $Computer->fields['os_licenseid'] = $dataSection['WINPRODID'];
                  }
               }
               if (isset($dataSection['WINPRODKEY'])) {
                  if (!in_array('os_license_number', $a_lockable)) {
                     $Computer->fields['os_license_number'] = $dataSection['WINPRODKEY'];
                  }
               }
               if (isset($dataSection['WORKGROUP'])) {
                  if (!in_array('domains_id', $a_lockable)) {
                     $Domain = new Domain;
                     $Computer->fields['domains_id'] = $Domain->import(array('name'=>$dataSection['WORKGROUP']));
                  }
               }
               if (isset($dataSection['OSCOMMENTS'])) {
                  if (!in_array('operatingsystemservicepacks_id', $a_lockable)) {
                     if (strstr($dataSection['OSCOMMENTS'], 'Service Pack')) {
                        $OperatingSystemServicePack = new OperatingSystemServicePack;
                        $Computer->fields['operatingsystemservicepacks_id'] = $OperatingSystemServicePack->import(array('name'=>$dataSection['OSCOMMENTS']));
                     }
                  }
               }
               if (isset($dataSection['UUID'])) {
                  $PluginFusinvinventoryComputer->fields['uuid'] = $dataSection['UUID'];
               }
               break;

            case 'USERS':
               $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

               if (isset($dataSection['LOGIN'])) {
                  if (!in_array('users_id', $a_lockable)) {
                     $Computer->fields['contact'] = $dataSection['LOGIN'];
                     $query = "SELECT `id`
                               FROM `glpi_users`
                               WHERE `name` = '" . $dataSection['LOGIN'] . "';";
                     $result = $DB->query($query);
                     if ($DB->numrows($result) == 1) {
                        $Computer->fields["users_id"] = $DB->result($result, 0, 0);
                     }
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
                  $ignore_USB[preg_replace("/\/$/", "", $dataSection['SERIAL'])] = 1; // Problem on Windows
               }
               break;

         }
      }

      $Computer->update($Computer->fields, $_SESSION["plugin_fusinvinventory_history_add"]);
      $PluginFusinvinventoryComputer->update($PluginFusinvinventoryComputer->fields);
      $j = -1;

      foreach($data as $section) {
         $dataSection = unserialize($section['dataSection']);
         foreach($dataSection as $key=>$value) {
            $dataSection[$key] = addslashes_deep($value);
         }
         switch ($section['sectionName']) {

            case 'CPUS':
               $PluginFusinvinventoryImport_Processor = new PluginFusinvinventoryImport_Processor();
               $id_processor = $PluginFusinvinventoryImport_Processor->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_processor)) {
                  $id_processor = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_processor);
               break;

            case 'DRIVES':
               $PluginFusinvinventoryImport_Drive = new PluginFusinvinventoryImport_Drive();
               $id_disk = $PluginFusinvinventoryImport_Drive->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_disk)) {
                  $id_disk = $j;
                  $j--;
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
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_controller);
               break;

            case 'SOUNDS':
               $PluginFusinvinventoryImport_Sound = new PluginFusinvinventoryImport_Sound();
               $id_sound = $PluginFusinvinventoryImport_Sound->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_sound)) {
                  $id_sound = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_sound);
               break;

            case 'VIDEOS':
               $PluginFusinvinventoryImport_Graphiccard = new PluginFusinvinventoryImport_Graphiccard();
               $id_graphiccard = $PluginFusinvinventoryImport_Graphiccard->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_graphiccard)) {
                  $id_graphiccard = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_graphiccard);
               break;

            case 'MEMORIES':
               $PluginFusinvinventoryImport_Memory = new PluginFusinvinventoryImport_Memory();
               $id_memory = $PluginFusinvinventoryImport_Memory->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_memory)) {
                  $id_memory = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_memory);
               break;

            case 'NETWORKS':
               $PluginFusinvinventoryImport_Networkport = new PluginFusinvinventoryImport_Networkport();
               $id_network = $PluginFusinvinventoryImport_Networkport->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_network)) {
                  $id_network = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_network);
               break;

            case 'SOFTWARES':

                // If import computer from GLPI DB
                $Computer_SoftwareVersion_id = 0;
              
               // Add software name
               // Add version of software
               // link version with computer : glpi_computers_softwareversions
               $PluginFusinvinventoryImport_Software = new PluginFusinvinventoryImport_Software;
               if (isset($dataSection['VERSION'])) {
                  $Computer_SoftwareVersion_id = $PluginFusinvinventoryImport_Software->addSoftware($idmachine, array('name'=>$dataSection['NAME'],
                                                                              'version'=>$dataSection['VERSION']));
               } else {
                  $Computer_SoftwareVersion_id = $PluginFusinvinventoryImport_Software->addSoftware($idmachine, array('name'=>$dataSection['NAME'],
                                                                              'version'=>'0'));
               }
               if (empty($Computer_SoftwareVersion_id)) {
                  $Computer_SoftwareVersion_id = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$Computer_SoftwareVersion_id);
               break;

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
               $id_peripheral = 0;
               if ((isset($dataSection['SERIAL'])) AND (isset($ignore_USB[$dataSection['SERIAL']]))) {
                  // Ignore
               } else {
                  $PluginFusinvinventoryImport_Peripheral =  new PluginFusinvinventoryImport_Peripheral();
                  $id_peripheral = $PluginFusinvinventoryImport_Peripheral->AddUpdateItem("add", $idmachine, $dataSection);
               }
               
               if (!isset($id_peripheral)) {
                  $id_peripheral = $j;
                  $j--;
               }

               array_push($sectionsId,$section['sectionName']."/".$id_peripheral);
               break;

            case 'PRINTERS':
               $PluginFusinvinventoryImport_Printer =  new PluginFusinvinventoryImport_Printer();
               $id_printer = $PluginFusinvinventoryImport_Printer->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_printer)) {
                  $id_printer = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_printer);
               break;

            case 'MONITORS':
               $PluginFusinvinventoryImport_Monitor =  new PluginFusinvinventoryImport_Monitor();
               $id_monitor = $PluginFusinvinventoryImport_Monitor->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_monitor)) {
                  $id_monitor = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_monitor);
               break;

            case 'STORAGES':
               $PluginFusinvinventoryImport_Storage =  new PluginFusinvinventoryImport_Storage();
               $id_storage = $PluginFusinvinventoryImport_Storage->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_storage)) {
                  $id_storage = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_storage);
               break;

            case 'ANTIVIRUS':
               $PluginFusinvinventoryImport_Antivirus =  new PluginFusinvinventoryImport_Antivirus();
               $id_antivirus = $PluginFusinvinventoryImport_Antivirus->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_antivirus)) {
                  $id_antivirus = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_antivirus);
               break;

            // TODO :
            /*
             *
             * VIRTUALMACHINES
             * MODEMS
             * ENVS
             * UPDATES
             * BATTERIES
             * PROCESSES
             *
             */


            default:
               array_push($sectionsId,$section['sectionName']."/".$j);
               $j--;
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

      $Computer = new Computer;
      $Computer->getFromDB($idmachine);
      $_SESSION["plugin_fusinvinventory_entity"] = $Computer->fields['entities_id'];

        
        logInFile("removesection", "[".$idmachine."] ".print_r($idsections, true));

        foreach ($idsections as $section) {
            $split = explode("/", $section);
            $sectionName = $split[0];
            $items_id = $split[1];
            if ($items_id > 0) { // Object managed into GLPI only!
               switch ($sectionName) {

                  case 'CPUS':
                     $PluginFusinvinventoryImport_Processor = new PluginFusinvinventoryImport_Processor();
                     $PluginFusinvinventoryImport_Processor->deleteItem($items_id, $idmachine);
                     break;

                  case 'DRIVES':
                     $PluginFusinvinventoryImport_Drive = new PluginFusinvinventoryImport_Drive();
                     $PluginFusinvinventoryImport_Drive->deleteItem($items_id, $idmachine);
                     break;

                  case 'CONTROLLERS':
                     $PluginFusinvinventoryImport_Controller = new PluginFusinvinventoryImport_Controller();
                     $PluginFusinvinventoryImport_Controller->deleteItem($items_id, $idmachine);
                     break;

                  case 'SOUNDS':
                     $PluginFusinvinventoryImport_Sound = new PluginFusinvinventoryImport_Sound();
                     $PluginFusinvinventoryImport_Sound->deleteItem($items_id, $idmachine);
                     break;

                  case 'VIDEOS':
                     $PluginFusinvinventoryImport_Graphiccard = new PluginFusinvinventoryImport_Graphiccard();
                     $PluginFusinvinventoryImport_Graphiccard->deleteItem($items_id, $idmachine);
                     break;

                  case 'MEMORIES':
                     $PluginFusinvinventoryImport_Memory = new PluginFusinvinventoryImport_Memory();
                     $PluginFusinvinventoryImport_Memory->deleteItem($items_id, $idmachine);
                     break;

                  case 'NETWORKS':
                     $PluginFusinvinventoryImport_Networkport = new PluginFusinvinventoryImport_Networkport();
                     $PluginFusinvinventoryImport_Networkport->deleteItem($items_id, $idmachine);
                     break;

                  case 'SOFTWARES':
                     $PluginFusinvinventoryImport_Software = new PluginFusinvinventoryImport_Software();
                     $PluginFusinvinventoryImport_Software->deleteItem($items_id, $idmachine);
                     break;

                  case 'USBDEVICES':
                     $PluginFusinvinventoryImport_Peripheral =  new PluginFusinvinventoryImport_Peripheral();
                     $PluginFusinvinventoryImport_Peripheral->deleteItem($items_id, $idmachine);
                     break;

                  case 'PRINTERS':
                     $PluginFusinvinventoryImport_Printer =  new PluginFusinvinventoryImport_Printer();
                     $PluginFusinvinventoryImport_Printer->deleteItem($items_id, $idmachine);
                     break;

                  case 'MONITORS':
                     $PluginFusinvinventoryImport_Monitor =  new PluginFusinvinventoryImport_Monitor();
                     $PluginFusinvinventoryImport_Monitor->deleteItem($items_id, $idmachine);
                     break;

                  case 'STORAGES':
                     $PluginFusinvinventoryImport_Storage = new PluginFusinvinventoryImport_Storage();
                     $PluginFusinvinventoryImport_Storage->deleteItem($items_id, $idmachine);
                     break;

                  case 'ANTIVIRUS':
                     $PluginFusinvinventoryImport_Antivirus =  new PluginFusinvinventoryImport_Antivirus();
                     $PluginFusinvinventoryImport_Antivirus->deleteItem($items_id, $idmachine);
                     break;

               }
            }           
        }

        $sectionsId = array();
        return $sectionsId;
    }



    public static function updateSections($data, $idmachine) {
       global $DB;

      $Computer = new Computer;
      $Computer->getFromDB($idmachine);
      $_SESSION["plugin_fusinvinventory_entity"] = $Computer->fields['entities_id'];

      $_SESSION["plugin_fusinvinventory_history_add"] = true;
      $_SESSION["plugin_fusinvinventory_no_history_add"] = false;


      foreach($data as $section) {
         $dataSection = unserialize($section['dataSection']);
         foreach($dataSection as $key=>$value) {
            $dataSection[$key] = addslashes_deep($value);
         }
         $array = explode("/", $section['sectionId']);
         $items_id = $array[1];
         $sectionName = $array[0];

         if ($items_id > 0) { // Object managed into GLPI only!
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

               case 'ANTIVIRUS':
                  $PluginFusinvinventoryImport_Antivirus =  new PluginFusinvinventoryImport_Antivirus();
                  $PluginFusinvinventoryImport_Antivirus->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'NETWORKS' :
                  $PluginFusinvinventoryImport_Networkport = new PluginFusinvinventoryImport_Networkport();
                  $PluginFusinvinventoryImport_Networkport->AddUpdateItem("update", $items_id, $dataSection);
                  break;


               case 'HARDWARE':
                  $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

                  if (isset($dataSection['NAME']))
                     if (!in_array('name', $a_lockable)) {
                        $Computer->fields['name'] = $dataSection['NAME'];
                     }
                  if (isset($dataSection['OSNAME'])) {
                     if (!in_array('operatingsystems_id', $a_lockable)) {
                        $OperatingSystem = new OperatingSystem;
                        $Computer->fields['operatingsystems_id'] = $OperatingSystem->import(array('name'=>$dataSection['OSNAME']));
                     }
                  }
                  if (isset($dataSection['OSVERSION'])) {
                     if (!in_array('operatingsystemversions_id', $a_lockable)) {
                        $OperatingSystemVersion = new OperatingSystemVersion;
                        $Computer->fields['operatingsystemversions_id'] = $OperatingSystemVersion->import(array('name'=>$dataSection['OSVERSION']));
                     }
                  }
                  if (isset($dataSection['WINPRODID'])) {
                     if (!in_array('os_licenseid', $a_lockable)) {
                        $Computer->fields['os_licenseid'] = $dataSection['WINPRODID'];
                     }
                  }
                  if (isset($dataSection['WINPRODKEY'])) {
                     if (!in_array('os_license_number', $a_lockable)) {
                        $Computer->fields['os_license_number'] = $dataSection['WINPRODKEY'];
                     }
                  }
                  if (isset($dataSection['WORKGROUP'])) {
                     if (!in_array('domains_id', $a_lockable)) {
                        $Domain = new Domain;
                        $Computer->fields['domains_id'] = $Domain->import(array('name'=>$dataSection['WORKGROUP']));
                     }
                  }
                  if (isset($dataSection['OSCOMMENTS'])) {
                     if (!in_array('operatingsystemservicepacks_id', $a_lockable)) {
                        if (strstr($dataSection['OSCOMMENTS'], 'Service Pack')) {
                           $OperatingSystemServicePack = new OperatingSystemServicePack;
                           $Computer->fields['operatingsystemservicepacks_id'] = $OperatingSystemServicePack->import(array('name'=>$dataSection['OSCOMMENTS']));
                        }
                     }
                  }
                  $Computer->update($Computer->fields);
                  break;

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

                  if (isset($dataSection['TYPE'])) {
                     $ComputerType = new ComputerType();
                     $Computer->fields['computertypes_id'] = Dropdown::importExternal('ComputerType',
                                                                          $dataSection['TYPE']);
                  }

                  $Computer->update($Computer->fields);
                  break;

               case 'USERS':
                  $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

                  if (isset($dataSection['LOGIN'])) {
                     if (!in_array('users_id', $a_lockable)) {
                        $Computer->fields['contact'] = $dataSection['LOGIN'];
                        $query = "SELECT `id`
                                  FROM `glpi_users`
                                  WHERE `name` = '" . $dataSection['LOGIN'] . "';";
                        $result = $DB->query($query);
                        if ($DB->numrows($result) == 1) {
                           $Computer->fields["users_id"] = $DB->result($result, 0, 0);
                        }
                     }
                  }
                  $Computer->update($Computer->fields);
                  break;

            }
         }
      }

       logInFile("updatesection", "[".$idmachine."] ".print_r($data, true));
    }


    function writeXMLFusion($items_id) {
      if (isset($_SESSION['SOURCEXML'])) {
         // TODO : Write in _plugins/fusinvinventory/xxx/idmachine.xml
         $folder = substr($items_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$items_id, 'w');
         fwrite($fileopen, $_SESSION['SOURCEXML']);
         fclose($fileopen);
       }
    }


    static function getMapping() {
       $opt = array();

       $i = 0;

       // ** HARDWARE
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'NAME';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'name';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'OSNAME';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'operatingsystems_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'OSVERSION';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'operatingsystemversions_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'WINPRODID';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'os_licenseid';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'WINPRODKEY';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'os_license_number';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'WORKGROUP';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'domains_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'OSCOMMENTS';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'operatingsystemservicepacks_id';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'UUID';
       $opt[$i]['glpiItemtype']     = 'glpi_plugin_fusinvinventory_computers';
       $opt[$i]['glpiField']        = 'uuid';

       
       // ** USERS
       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'LOGIN';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'users_id';


       // ** BIOS
       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'SMANUFACTURER';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'manufacturers_id';
   
       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'SMODEL';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'computermodels_id';

       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'SSN';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'serial';

       return $opt;
    }
}

?>