<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryLibhook {


    function __construct() {
       if (!isset($_SESSION["plugin_fusinvinventory_history_add"])) {
         $_SESSION["plugin_fusinvinventory_history_add"] = true;
       }
       if (!isset($_SESSION["plugin_fusinvinventory_no_history_add"])) {
         $_SESSION["plugin_fusinvinventory_no_history_add"] = false;
       }
       $_SESSION["plugin_fusinvinventory_userdefined"] = 0;
    }

    
    
    /**
    * create a new computer in GLPI (computer create before but populate it here)
    *
    * @param $items_id integer id of the computer
    *
    * @return nothing
    *
    **/
    public static function createMachine($items_id) {

      $_SESSION["plugin_fusinvinventory_history_add"] = false;
      $_SESSION["plugin_fusinvinventory_no_history_add"] = true;
       // Else create computer
      $Computer = new Computer();
      $Computer->getFromDB($items_id);
      $input = array();
      $input['id'] = $Computer->fields['id'];
      $input['is_deleted'] = 0;
      $input['autoupdatesystems_id'] = Dropdown::importExternal('AutoUpdateSystem', 
                                                                'FusionInventory',
                                                                $_SESSION["plugin_fusinvinventory_entity"]);
      $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];

      $Computer->update($input, 0);
    }



    /**
    * add a new section to the machine in an application
    * 
    * @param $data array with section to add
    * @param $idmachine integer id of the GLPI Computer
    *
    * @return $sectionId integer id of the section
    *
    **/
    public static function addSections($data, $idmachine) {

      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusinvinventory-addsection",
         "[".$idmachine."] ".print_r($data, true)
      );
       
      $Computer = new Computer();

      $sectionsId = array();
      $Computer->getFromDB($idmachine);
      $inputC = array();
      $inputC['id'] = $Computer->fields['id'];

      $input = array();
      $input['items_id'] = $idmachine;
 
      $_SESSION["plugin_fusinvinventory_entity"] = $Computer->fields['entities_id'];

      if (!isset($_SESSION["plugin_fusinvinventory_history_add"])) {
         $_SESSION["plugin_fusinvinventory_history_add"] = true;
      }
      if (!isset($_SESSION["plugin_fusinvinventory_no_history_add"])) {
         $_SESSION["plugin_fusinvinventory_no_history_add"] = false;
      }

      $ignore_USB = array();

      $i = -1;
      // Pre-get HARDWARE/CHASSIS_TYPE (type of computer
      $computer_type = '';
      foreach($data as $section) {
         if ($section['sectionName'] == 'HARDWARE') {
            $dataSection = unserialize($section['dataSection']);
            if (isset($dataSection['CHASSIS_TYPE'])) {
               $computer_type = $dataSection['CHASSIS_TYPE'];
            }
         }
      }
      $pFusinvinventoryComputer = new PluginFusinvinventoryComputer();
      $a_computerextend = current($pFusinvinventoryComputer->find("`computers_id`='".$idmachine."'", 
                                                                  "", 1));
      $inputCext = array();
      if (!empty($a_computerextend)) {         
         $inputCext['id'] = $a_computerextend['id'];
      } else {
         $inputCext['computers_id'] = $idmachine;
      }

      foreach($data as $section) {
         $i++;
         $dataSection = unserialize($section['dataSection']);
         switch ($section['sectionName']) {

            case 'BIOS':
               $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

               if ((isset($dataSection['SMANUFACTURER']))
                     AND (!empty($dataSection['SMANUFACTURER']))) {

                  if (!in_array('manufacturers_id', $a_lockable)) {
                     $inputC['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                            $dataSection['SMANUFACTURER'],
                                                                            $_SESSION["plugin_fusinvinventory_entity"]);
                  }
               } else if ((isset($dataSection['MMANUFACTURER']))
                            AND (!empty($dataSection['MMANUFACTURER']))) {

                  if (!in_array('manufacturers_id', $a_lockable)) {
                     $inputC['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                            $dataSection['MMANUFACTURER'],
                                                                            $_SESSION["plugin_fusinvinventory_entity"]);
               
                  }
               } else if ((isset($dataSection['BMANUFACTURER']))
                            AND (!empty($dataSection['BMANUFACTURER']))) {

                  if (!in_array('manufacturers_id', $a_lockable)) {
                     $inputC['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                            $dataSection['BMANUFACTURER'],
                                                                            $_SESSION["plugin_fusinvinventory_entity"]);
               
                  }
               }
               if (isset($dataSection['SMODEL']) AND $dataSection['SMODEL'] != '') {
                  if (!in_array('computermodels_id', $a_lockable)) {
                     $ComputerModel = new ComputerModel();
                     $inputC['computermodels_id'] = $ComputerModel->importExternal($dataSection['SMODEL'], $_SESSION["plugin_fusinvinventory_entity"]);
                  }
               } else if (isset($dataSection['MMODEL']) AND $dataSection['MMODEL'] != '') {
                  if (!in_array('computermodels_id', $a_lockable)) {
                     $ComputerModel = new ComputerModel();
                     $inputC['computermodels_id'] = $ComputerModel->importExternal($dataSection['MMODEL'], $_SESSION["plugin_fusinvinventory_entity"]);
                  }
               }
               if (isset($dataSection['SSN']))
                  if (!in_array('serial', $a_lockable)) {
                     if (isset($_SESSION["plugin_fusioninventory_serialHP"])) {
                        $inputC['serial'] = $_SESSION["plugin_fusioninventory_serialHP"];
                     } else {
                        $inputC['serial'] = $dataSection['SSN'];
                     }
                  }
               // * Type of computer
               $computerType = new ComputerType();
               if ($computer_type != '') {
                  if (!in_array('computertypes_id', $a_lockable)) {
                     $inputC['computertypes_id'] = $computerType->importExternal($computer_type, $_SESSION["plugin_fusinvinventory_entity"]);
                  } 
               } else  if (isset($dataSection['TYPE'])) {
                  if (!in_array('computertypes_id', $a_lockable)) {
                     $inputC['computertypes_id'] = $computerType->importExternal($dataSection['TYPE'], $_SESSION["plugin_fusinvinventory_entity"]);
                  }
               } else if (isset($dataSection['MMODEL'])) {
                  if (!in_array('computertypes_id', $a_lockable)) {
                     $inputC['computertypes_id'] = $computerType->importExternal($dataSection['MMODEL'], $_SESSION["plugin_fusinvinventory_entity"]);
                  }
               }
               
               if (isset($dataSection['SKUNUMBER'])) {
                  $pfLibhook = new PluginFusinvinventoryLibhook();
                  $pfLibhook->Suppliertag($idmachine, $dataSection['SKUNUMBER']);
               }
               if (isset($dataSection['BDATE'])) {
                  $a_split = explode("/", $dataSection['BDATE']);
                  // 2011-06-29 13:19:48
                  if (isset($a_split[0])
                          AND isset($a_split[1])
                          AND isset($a_split[2])) {
                     $inputCext['bios_date'] = $a_split[2]."-".$a_split[0]."-".$a_split[1];
                  }
               }
               if (isset($dataSection['BVERSION'])) {
                  $inputCext['bios_version'] = $dataSection['BVERSION'];
               }
               if (isset($dataSection['ASSETTAG'])) {
                  $inputCext['bios_assettag'] = $dataSection['ASSETTAG'];
               }
               
               if (isset($dataSection['BMANUFACTURER'])) {
                  $inputCext['bios_manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                                 $dataSection['BMANUFACTURER'],
                                                                                 $_SESSION["plugin_fusinvinventory_entity"]);
               }
               break;

            case 'ACCOUNTINFO':
               if (isset($dataSection['KEYVALUE'])
                       AND isset($dataSection['KEYNAME'])
                       AND $dataSection['KEYNAME'] == 'TAG') {

                  $config = new PluginFusioninventoryConfig();
                  if ($config->getValue($_SESSION["plugin_fusinvinventory_moduleid"], 'location') == '1') {
                     $inputC['locations_id'] = Dropdown::importExternal('Location',
                                                                                  $dataSection['KEYVALUE'],
                                                                                  $_SESSION["plugin_fusinvinventory_entity"]);
                  }
                  if ($config->getValue($_SESSION["plugin_fusinvinventory_moduleid"], 'group') == '1') {
                     $inputC['groups_id'] = PluginFusinvinventoryLibhook::importGroup($dataSection['KEYVALUE'], $_SESSION["plugin_fusinvinventory_entity"]);
                  }
               }
               break;

            case 'HARDWARE':
               $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

               if (isset($dataSection['NAME'])) {
                  if (!in_array('name', $a_lockable)) {
                     $inputC['name'] = $dataSection['NAME'];
                  }
               }
               if (isset($dataSection['OSNAME'])) {
                  if (!in_array('operatingsystems_id', $a_lockable)) {
                     $OperatingSystem = new OperatingSystem();
                     $inputC['operatingsystems_id'] = $OperatingSystem->importExternal($dataSection['OSNAME'], $_SESSION["plugin_fusinvinventory_entity"]);
                  }
               }
               if (!in_array('operatingsystemversions_id', $a_lockable)) {
                  $addfield = 0;
                  if (isset($dataSection['OSVERSION'])) {
                     $OperatingSystemVersion = new OperatingSystemVersion();
                     $inputC['operatingsystemversions_id'] = $OperatingSystemVersion->importExternal($dataSection['OSVERSION'], $_SESSION["plugin_fusinvinventory_entity"]);
                     $addfield = 1;
                  }
                  if ($addfield == '0') {
                     $inputC['operatingsystemversions_id'] = 0;
                  }
               }
               if (!in_array('os_licenseid', $a_lockable)) {
                  $addfield = 0;
                  if (isset($dataSection['WINPRODID'])) {
                     $inputC['os_licenseid'] = $dataSection['WINPRODID'];
                     $addfield = 1;
                  }
                  if ($addfield == '0') {
                     $inputC['os_licenseid'] = '';
                  }
               }
               if (!in_array('os_license_number', $a_lockable)) {
                  $addfield = 0;
                  if (isset($dataSection['WINPRODKEY'])) {
                     $inputC['os_license_number'] = $dataSection['WINPRODKEY'];
                     $addfield = 1;
                  }
                  if ($addfield == '0') {
                     $inputC['os_license_number'] = '';
                  }
               }
               if (!in_array('domains_id', $a_lockable)) {
                  $addfield = 0;
                  if (isset($dataSection['WORKGROUP'])) {
                     $Domain = new Domain();
                     $inputC['domains_id'] = $Domain->import(array('name'=>$dataSection['WORKGROUP']));
                     $addfield = 1;
                  }
                  if ($addfield == '0') {
                     $inputC['domains_id'] = 0;
                  }
               }
               if (isset($dataSection['OSINSTALLDATE'])) {
                  $inputCext['operatingsystem_installationdate'] = date("Y-m-d", $dataSection['OSINSTALLDATE']);
               }
               if (!in_array('operatingsystemservicepacks_id', $a_lockable)) {
                  $addfield = 0;
                  if (isset($dataSection['OSCOMMENTS'])) {
                     if (strstr($dataSection['OSCOMMENTS'], 'Service Pack')) {
                        $OperatingSystemServicePack = new OperatingSystemServicePack();
                        $inputC['operatingsystemservicepacks_id'] = $OperatingSystemServicePack->importExternal($dataSection['OSCOMMENTS'], $_SESSION["plugin_fusinvinventory_entity"]);
                        $addfield = 1;
                     }
                  }
                  if ($addfield == '0') {
                     $inputC['operatingsystemservicepacks_id'] = 0;
                  }
               }
               if (isset($dataSection['UUID'])) {
                  if (!in_array('uuid', $a_lockable)) {
                     $inputC['uuid'] = $dataSection['UUID'];
                  }
               }
               if (isset($dataSection['DESCRIPTION'])) {
                  if (!in_array('comment', $a_lockable)) {
                     $inputC['comment'] = $dataSection['DESCRIPTION'];
                  }
               }
               if (isset($dataSection['WINOWNER'])) {
                  $inputCext['winowner'] = $dataSection['WINOWNER'];
               }
               if (isset($dataSection['WINCOMPANY'])) {
                  $inputCext['wincompany'] = $dataSection['WINCOMPANY'];
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

      $Computer->update($inputC, $_SESSION["plugin_fusinvinventory_history_add"]);
      if (isset($inputCext['id'])) {
         $pFusinvinventoryComputer->update($inputCext, $_SESSION["plugin_fusinvinventory_history_add"]);
      } else {
         $pFusinvinventoryComputer->add($inputCext, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
      $j = -1;

      foreach($data as $section) {
         $dataSection = unserialize($section['dataSection']);
         switch ($section['sectionName']) {

            case 'CPUS':
               $pfImport_Processor = new PluginFusinvinventoryImport_Processor();
               $id_processor = $pfImport_Processor->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_processor)) {
                  $id_processor = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_processor);
               break;

            case 'DRIVES':
               $pfImport_Drive = new PluginFusinvinventoryImport_Drive();
               $id_disk = $pfImport_Drive->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_disk)) {
                  $id_disk = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_disk);
               break;

            case 'CONTROLLERS':
               $id_controller = '';

               if ((isset($dataSection["NAME"])) AND (!isset($_SESSION["plugin_fusinvinventory_ignorecontrollers"][$dataSection["NAME"]]))) {
                  $pfImport_Controller = new PluginFusinvinventoryImport_Controller();
                  $id_controller = $pfImport_Controller->AddUpdateItem("add", $idmachine, $dataSection);
               }
               if (empty($id_controller)) {
                  $id_controller = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_controller);
               break;

            case 'SOUNDS':
               $pfImport_Sound = new PluginFusinvinventoryImport_Sound();
               $id_sound = $pfImport_Sound->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_sound)) {
                  $id_sound = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_sound);
               break;

            case 'VIDEOS':
               $pfImport_Graphiccard = new PluginFusinvinventoryImport_Graphiccard();
               $id_graphiccard = $pfImport_Graphiccard->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_graphiccard)) {
                  $id_graphiccard = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_graphiccard);
               break;

            case 'MEMORIES':
               $pfImport_Memory = new PluginFusinvinventoryImport_Memory();
               $id_memory = $pfImport_Memory->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_memory)) {
                  $id_memory = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_memory);
               break;

            case 'NETWORKS':
               $pfImport_Networkport = new PluginFusinvinventoryImport_Networkport();
               $id_network = $pfImport_Networkport->AddUpdateItem("add", $idmachine, $dataSection);
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
               $pfImport_Software = new PluginFusinvinventoryImport_Software();
               if (!isset($dataSection['PUBLISHER'])) {
                  $dataSection['PUBLISHER'] = NULL;
               }
               $name = '';
               if (isset($dataSection['NAME'])) {
                  $name = $dataSection['NAME'];
               } else if (isset($dataSection['GUID'])) {
                  $name = $dataSection['GUID'];
               }
               $Computer_SoftwareVersion_id = '';
               if (isset($dataSection['VERSION'])
                       AND $name != '') {
                  $Computer_SoftwareVersion_id = $pfImport_Software->addSoftware($idmachine, array('name'=>$name,
                                                                              'version'=>$dataSection['VERSION'],
                                                                              'PUBLISHER'=>$dataSection['PUBLISHER']));
               } else if ($name != '') {
                  $Computer_SoftwareVersion_id = $pfImport_Software->addSoftware($idmachine, array('name'=>$name,
                                                                              'version'=>NOT_AVAILABLE,
                                                                              'PUBLISHER'=>$dataSection['PUBLISHER']));
               }
               if (empty($Computer_SoftwareVersion_id)) {
                  $Computer_SoftwareVersion_id = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$Computer_SoftwareVersion_id);
               break;

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
                  $pfImport_Peripheral =  new PluginFusinvinventoryImport_Peripheral();
                  $id_peripheral = $pfImport_Peripheral->AddUpdateItem("add", $idmachine, $dataSection);
               }
               
               if (!isset($id_peripheral)) {
                  $id_peripheral = $j;
                  $j--;
               }

               array_push($sectionsId,$section['sectionName']."/".$id_peripheral);
               break;

            case 'PRINTERS':
               $pfImport_Printer =  new PluginFusinvinventoryImport_Printer();
               $id_printer = $pfImport_Printer->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_printer)) {
                  $id_printer = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_printer);
               break;

            case 'MONITORS':
               $pfImport_Monitor =  new PluginFusinvinventoryImport_Monitor();
               $id_monitor = $pfImport_Monitor->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_monitor)) {
                  $id_monitor = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_monitor);
               break;

            case 'STORAGES':
               $pfImport_Storage =  new PluginFusinvinventoryImport_Storage();
               $id_storage = $pfImport_Storage->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_storage)) {
                  $id_storage = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_storage);
               break;

            case 'ANTIVIRUS':
               $pfImport_Antivirus =  new PluginFusinvinventoryImport_Antivirus();
               $id_antivirus = $pfImport_Antivirus->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_antivirus)) {
                  $id_antivirus = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_antivirus);
               break;

            case 'VIRTUALMACHINES':
               $pfImport_Virtualmachine = new PluginFusinvinventoryImport_Virtualmachine();
               $id_vm = $pfImport_Virtualmachine->addUpdateItem("add",$idmachine,$dataSection);
               if (empty($id_vm)) {
                  $id_vm = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_vm);
               break;

            case 'USERS':
               $pfImport_User = new PluginFusinvinventoryImport_User();
               $id_user = $pfImport_User->AddUpdateItem("add", $idmachine, $dataSection);
               if (empty($id_user)) {
                  $id_user = $j;
                  $j--;
               }
               array_push($sectionsId,$section['sectionName']."/".$id_user);
               break;

            // TODO :
            /*
             *
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
    * 
    * @param $idsections array sections with id
    * @param $idmachine integer id of the GLPI Computer
    *
    * @return emtpy array
    *
    **/
    public static function removeSections($idsections, $idmachine, $sectiondetail) {

      $Computer = new Computer();
      $Computer->getFromDB($idmachine);
      $_SESSION["plugin_fusinvinventory_entity"] = $Computer->fields['entities_id'];

      $_SESSION["plugin_fusinvinventory_history_add"] = true;
      $_SESSION["plugin_fusinvinventory_no_history_add"] = false;
      
      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusinvinventory-removesection",
         "[".$idmachine."] ".print_r($idsections, true)
      );
        
        foreach ($idsections as $section) {
            $split = explode("/", $section);
            $sectionName = $split[0];
            $items_id = $split[1];
            if (($items_id > 0) OR (strstr($items_id, 'd'))) { // Object managed into GLPI only!
               switch ($sectionName) {

                  case 'CPUS':
                     $pfImport_Processor = new PluginFusinvinventoryImport_Processor();
                     $pfImport_Processor->deleteItem($items_id, $idmachine);
                     break;

                  case 'DRIVES':
                     $pfImport_Drive = new PluginFusinvinventoryImport_Drive();
                     $pfImport_Drive->deleteItem($items_id, $idmachine);
                     break;

                  case 'CONTROLLERS':
                     $pfImport_Controller = new PluginFusinvinventoryImport_Controller();
                     $pfImport_Controller->deleteItem($items_id, $idmachine);
                     break;

                  case 'SOUNDS':
                     $pfImport_Sound = new PluginFusinvinventoryImport_Sound();
                     $pfImport_Sound->deleteItem($items_id, $idmachine);
                     break;

                  case 'VIDEOS':
                     $pfImport_Graphiccard = new PluginFusinvinventoryImport_Graphiccard();
                     $pfImport_Graphiccard->deleteItem($items_id, $idmachine);
                     break;

                  case 'MEMORIES':
                     $pfImport_Memory = new PluginFusinvinventoryImport_Memory();
                     $pfImport_Memory->deleteItem($items_id, $idmachine);
                     break;

                  case 'NETWORKS':
                     $pfImport_Networkport = new PluginFusinvinventoryImport_Networkport();
                     $pfImport_Networkport->deleteItem($items_id, $idmachine);
                     break;

                  case 'SOFTWARES':
                     $pfImport_Software = new PluginFusinvinventoryImport_Software();
                     $pfImport_Software->deleteItem($items_id, $idmachine);
                     break;

                  case 'USBDEVICES':
                     $pfImport_Peripheral =  new PluginFusinvinventoryImport_Peripheral();
                     $pfImport_Peripheral->deleteItem($items_id, $idmachine);
                     break;

                  case 'PRINTERS':
                     $pfImport_Printer =  new PluginFusinvinventoryImport_Printer();
                     $pfImport_Printer->deleteItem($items_id, $idmachine);
                     break;

                  case 'MONITORS':
                     $pfImport_Monitor =  new PluginFusinvinventoryImport_Monitor();
                     $pfImport_Monitor->deleteItem($items_id, $idmachine);
                     break;

                  case 'STORAGES':
                     $pfImport_Storage = new PluginFusinvinventoryImport_Storage();
                     $pfImport_Storage->deleteItem($items_id, $idmachine, $sectiondetail[$section]);
                     break;

                  case 'ANTIVIRUS':
                     $pfImport_Antivirus =  new PluginFusinvinventoryImport_Antivirus();
                     $pfImport_Antivirus->deleteItem($items_id, $idmachine);
                     break;

                  case 'VIRTUALMACHINES':
                     $virtualmachine =  new PluginFusinvinventoryImport_Virtualmachine();
                     $virtualmachine->deleteItem($items_id, $idmachine);
                     break;

                  case 'USERS':
                     $pfImport_User = new PluginFusinvinventoryImport_User();
                     $pfImport_User->deleteItem($items_id, $idmachine);
                     break;

               }
            }           
        }

        $sectionsId = array();
        return $sectionsId;
    }



   /**
   * Update glpi for each section
   *
   * @param $data array of each sections
   * @param $idmachine integer id of the GLPI Computer
   *
   * @return nothing
   *
   **/
    public static function updateSections($data, $idmachine) {
       global $DB;

      $Computer = new Computer();
      $Computer->getFromDB($idmachine);
      $inputC = array();
      $inputC['id'] = $Computer->fields['id'];
      $_SESSION["plugin_fusinvinventory_entity"] = $Computer->fields['entities_id'];

      $_SESSION["plugin_fusinvinventory_history_add"] = true;
      $_SESSION["plugin_fusinvinventory_no_history_add"] = false;

      // Pre-get HARDWARE/CHASSIS_TYPE (type of computer)
      $computer_type = '';
      foreach($data as $section) {
         $array = explode("/", $section['sectionId']);
         $sectionName = $array[0];
         if ($sectionName == 'HARDWARE') {
            $dataSection = $section['dataSection'];
            if (isset($dataSection['CHASSIS_TYPE'])) {
               $computer_type = $dataSection['CHASSIS_TYPE'];
            }
         }
      }
      $pFusinvinventoryComputer = new PluginFusinvinventoryComputer();
      $a_computerextend = current($pFusinvinventoryComputer->find("`computers_id`='".$idmachine."'", 
                                                                  "", 1));
      $inputCext = array();
      if (!empty($a_computerextend)) {         
         $inputCext['id'] = $a_computerextend['id'];
      } else {
         $inputCext['computers_id'] = $idmachine;
      }
      foreach($data as $section) {
         $dataSection = $section['dataSection'];
         $array = explode("/", $section['sectionId']);
         $items_id = $array[1];
         $sectionName = $array[0];

         if (($items_id > 0) OR (strstr($items_id, 'd'))) { // Object managed into GLPI only!
            switch ($sectionName) {

               case 'CPUS':
                  $pfImport_Processor = new PluginFusinvinventoryImport_Processor();
                  $pfImport_Processor->AddUpdateItem("update", $items_id, $dataSection);
                  break;
               
               case 'DRIVES':
                  $pfImport_Drive = new PluginFusinvinventoryImport_Drive();
                  $pfImport_Drive->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'CONTROLLERS':
                  $id_controller = '';
                  $pfImport_Controller = new PluginFusinvinventoryImport_Controller();
                  $id_controller = $pfImport_Controller->AddUpdateItem("update", $items_id, $dataSection);
                  break;

              case 'SOUNDS':
                  $pfImport_Sound = new PluginFusinvinventoryImport_Sound();
                  $pfImport_Sound->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'VIDEOS':
                  $pfImport_Graphiccard = new PluginFusinvinventoryImport_Graphiccard();
                  $pfImport_Graphiccard->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'MEMORIES':
                  $pfImport_Memory = new PluginFusinvinventoryImport_Memory();
                  $pfImport_Memory->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'NETWORKS' :
                  $pfImport_Networkport = new PluginFusinvinventoryImport_Networkport();
                  $pfImport_Networkport->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'SOFTWARES':
                  // May never require update
                  break;

               case 'BIOS':
                  $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

                  if ((isset($dataSection['SMANUFACTURER']))
                        AND (!empty($dataSection['SMANUFACTURER']))) {

                     if (!in_array('manufacturers_id', $a_lockable)) {
                        $inputC['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                               $dataSection['SMANUFACTURER'], 
                                                                               $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  } else if ((isset($dataSection['MMANUFACTURER']))
                               AND (!empty($dataSection['MMANUFACTURER']))) {

                     if (!in_array('manufacturers_id', $a_lockable)) {
                        $inputC['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                               $dataSection['MMANUFACTURER'],
                                                                               $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  } else if ((isset($dataSection['BMANUFACTURER']))
                               AND (!empty($dataSection['BMANUFACTURER']))) {

                     if (!in_array('manufacturers_id', $a_lockable)) {
                        $inputC['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                               $dataSection['BMANUFACTURER'],
                                                                               $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  }
                  if (isset($dataSection['SMODEL']) AND $dataSection['SMODEL'] != '') {
                     if (!in_array('computermodels_id', $a_lockable)) {
                        $ComputerModel = new ComputerModel();
                        $inputC['computermodels_id'] = $ComputerModel->importExternal($dataSection['SMODEL'], $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  } else if (isset($dataSection['MMODEL']) AND $dataSection['MMODEL'] != '') {
                     if (!in_array('computermodels_id', $a_lockable)) {
                        $ComputerModel = new ComputerModel();
                        $inputC['computermodels_id'] = $ComputerModel->importExternal($dataSection['MMODEL'], $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  }
                  if (isset($dataSection['SSN']))
                     if (!in_array('serial', $a_lockable)) {
                        if (isset($_SESSION["plugin_fusioninventory_serialHP"])) {
                           $inputC['serial'] = $_SESSION["plugin_fusioninventory_serialHP"];
                        } else {
                           $inputC['serial'] = $dataSection['SSN'];
                        }
                     }
                  // Update type of computer
                  $computerType = new ComputerType();
                  if ($computer_type != '') {
                     if (!in_array('computertypes_id', $a_lockable)) {
                        $inputC['computertypes_id'] = $computerType->importExternal($computer_type, $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  } else if (isset($dataSection['TYPE'])) {
                     if (!in_array('computertypes_id', $a_lockable)) {
                        $inputC['computertypes_id'] = $computerType->importExternal($dataSection['TYPE'], $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  } else if (isset($dataSection['MMODEL'])) {
                     if (!in_array('computertypes_id', $a_lockable)) {
                        $inputC['computertypes_id'] = $computerType->importExternal($dataSection['MMODEL'], $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  }
                  if (isset($dataSection['SKUNUMBER'])) {
                     $pfLibhook = new PluginFusinvinventoryLibhook();
                     $pfLibhook->Suppliertag($idmachine, $dataSection['SKUNUMBER']);
                  }
                  $Computer->update($inputC);
                  
                  if (isset($dataSection['BDATE'])) {
                     $a_split = explode("/", $dataSection['BDATE']);
                     // 2011-06-29 13:19:48
                     if (isset($a_split[0])
                          AND isset($a_split[1])
                          AND isset($a_split[2])) {
                        $inputCext['bios_date'] = $a_split[2]."-".$a_split[0]."-".$a_split[1];
                      }
                  }
                  if (isset($dataSection['BVERSION'])) {
                     $inputCext['bios_version'] = $dataSection['BVERSION'];
                  }
                  if (isset($dataSection['ASSETTAG'])) {
                     $inputCext['bios_assettag'] = $dataSection['ASSETTAG'];
                  }

                  if (isset($dataSection['BMANUFACTURER'])) {
                     $inputCext['bios_manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                                    $dataSection['BMANUFACTURER'],
                                                                                    $_SESSION["plugin_fusinvinventory_entity"]);
                  }
                  if (isset($inputCext['id'])) {
                     $pFusinvinventoryComputer->update($inputCext, $_SESSION["plugin_fusinvinventory_history_add"]);
                  } else {
                     $pFusinvinventoryComputer->add($inputCext, $_SESSION["plugin_fusinvinventory_history_add"]);
                  }
                  break;

               case 'ACCOUNTINFO':
                  if (isset($dataSection['KEYVALUE'])
                          AND isset($dataSection['KEYNAME'])
                          AND $dataSection['KEYNAME'] == 'TAG') {
                          
                     $config = new PluginFusioninventoryConfig();
                     if ($config->getValue($_SESSION["plugin_fusinvinventory_moduleid"], 'location') == 1) {
                        $inputC['locations_id'] = Dropdown::importExternal('Location',
                                                                                     $dataSection['KEYVALUE'],
                                                                                     $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                     if ($config->getValue($_SESSION["plugin_fusinvinventory_moduleid"], 'group') == 1) {
                        $inputC['groups_id'] = PluginFusinvinventoryLibhook::importGroup($dataSection['KEYVALUE'], $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  }
                  break;

               case 'HARDWARE':
                  $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

                  if (isset($dataSection['NAME']))
                     if (!in_array('name', $a_lockable)) {
                        $inputC['name'] = $dataSection['NAME'];
                     }
                  if (isset($dataSection['OSNAME'])) {
                     if (!in_array('operatingsystems_id', $a_lockable)) {
                        $OperatingSystem = new OperatingSystem();
                        $inputC['operatingsystems_id'] = $OperatingSystem->importExternal($dataSection['OSNAME'], $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  }
                  if (isset($dataSection['OSVERSION'])) {
                     if (!in_array('operatingsystemversions_id', $a_lockable)) {
                        $OperatingSystemVersion = new OperatingSystemVersion();
                        $inputC['operatingsystemversions_id'] = $OperatingSystemVersion->importExternal($dataSection['OSVERSION'], $_SESSION["plugin_fusinvinventory_entity"]);
                     }
                  }
                  if (isset($dataSection['WINPRODID'])) {
                     if (!in_array('os_licenseid', $a_lockable)) {
                        $inputC['os_licenseid'] = $dataSection['WINPRODID'];
                     }
                  }
                  if (isset($dataSection['WINPRODKEY'])) {
                     if (!in_array('os_license_number', $a_lockable)) {
                        $inputC['os_license_number'] = $dataSection['WINPRODKEY'];
                     }
                  }
                  if (isset($dataSection['WORKGROUP'])) {
                     if (!in_array('domains_id', $a_lockable)) {
                        $Domain = new Domain();
                        $inputC['domains_id'] = $Domain->import(array('name'=>$dataSection['WORKGROUP']));
                     }
                  }
                  if (isset($dataSection['OSCOMMENTS'])) {
                     if (!in_array('operatingsystemservicepacks_id', $a_lockable)) {
                        if (strstr($dataSection['OSCOMMENTS'], 'Service Pack')) {
                           $OperatingSystemServicePack = new OperatingSystemServicePack();
                           $inputC['operatingsystemservicepacks_id'] = $OperatingSystemServicePack->importExternal($dataSection['OSCOMMENTS'], $_SESSION["plugin_fusinvinventory_entity"]);
                        }
                     }
                  }
                  if (isset($dataSection['DESCRIPTION'])) {
                     if (!in_array('comment', $a_lockable)) {
                        $inputC['comment'] = $dataSection['DESCRIPTION'];
                     }
                  }
                  $Computer->update($inputC);
                  
                  if (isset($dataSection['OSINSTALLDATE'])) {
                     $inputCext['operatingsystem_installationdate'] = $dataSection['OSINSTALLDATE'];
                  }
                  if (isset($dataSection['WINOWNER'])) {
                     $inputCext['winowner'] = $dataSection['WINOWNER'];
                  }
                  if (isset($dataSection['WINCOMPANY'])) {
                     $inputCext['wincompany'] = $dataSection['WINCOMPANY'];
                  }
                  
                  if (isset($inputCext['id'])) {
                     $pFusinvinventoryComputer->update($inputCext, $_SESSION["plugin_fusinvinventory_history_add"]);
                  } else {
                     $pFusinvinventoryComputer->add($inputCext, $_SESSION["plugin_fusinvinventory_history_add"]);
                  }
                  break;

               case 'USBDEVICES':
                  break;

               case 'PRINTERS':
                  break;

               case 'MONITORS':
                  break;

               case 'STORAGES':
                  $pfImport_Storage = new PluginFusinvinventoryImport_Storage();
                  $pfImport_Storage->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'ANTIVIRUS':
                  $pfImport_Antivirus =  new PluginFusinvinventoryImport_Antivirus();
                  $pfImport_Antivirus->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'VIRTUALMACHINES':
                  $pfImport_Virtualmachine =  new PluginFusinvinventoryImport_Virtualmachine();
                  $pfImport_Virtualmachine->AddUpdateItem("update", $items_id, $dataSection);
                  break;

               case 'USERS':
                  $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $idmachine);

                  if (isset($dataSection['LOGIN'])) {
                     if (!in_array('users_id', $a_lockable)) {
                        $inputC['contact'] = $dataSection['LOGIN'];
                        $query = "SELECT `id`
                                  FROM `glpi_users`
                                  WHERE `name` = '" . $dataSection['LOGIN'] . "';";
                        $result = $DB->query($query);
                        if ($DB->numrows($result) == 1) {
                           $inputC["users_id"] = $DB->result($result, 0, 0);
                        }
                     }
                  }
                  $Computer->update($inputC);
                  break;

            }
         }
      }
      $Computer->update($inputC);
      PluginFusioninventoryLogger::logIfExtradebug("pluginFusinvinventory-updatesection", 
                                                   "[".$idmachine."] ".print_r($data, true));
    }



   /**
   * Write XML file into files/_plugins/fusinvinventory
   *
   * @param $items_id integer id of the computer
   *
   * @return nothing
   *
   **/
    function writeXMLFusion($items_id,$xml='') {
      if ($xml != '') {
         // TODO : Write in _plugins/fusinvinventory/xxx/idmachine.xml
         $folder = substr($items_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$items_id, 'w');
         fwrite($fileopen, $xml);
         fclose($fileopen);
       }
    }



   /**
   * Define Mapping for unlock fields
   *
   * @return array of the mapping
   *
   **/
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
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'uuid';

       $i++;
       $opt[$i]['xmlSection']       = 'HARDWARE';
       $opt[$i]['xmlSectionChild']  = 'DESCRIPTION';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'comment';

       
       // ** USERS
       $i++;
       $opt[$i]['xmlSection']       = 'USERS';
       $opt[$i]['xmlSectionChild']  = 'LOGIN';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'users_id';

       $i++;
       $opt[$i]['xmlSection']       = 'USERS';
       $opt[$i]['xmlSectionChild']  = 'LOGIN';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'contact';


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

       $i++;
       $opt[$i]['xmlSection']       = 'BIOS';
       $opt[$i]['xmlSectionChild']  = 'TYPE';
       $opt[$i]['glpiItemtype']     = 'Computer';
       $opt[$i]['glpiField']        = 'computertypes_id';

       return $opt;
    }



    /**
    * Update model for HP for suppliertag plugin
    *
    * @param $items_id integer id of the computer
    * @param $partnumber value HP partnumber
    *
    * @return nothing
    *
    **/
    function Suppliertag($items_id, $partnumber) {
      if ($partnumber != 'Not Specified') {
         $a_partnumber = explode("#", $partnumber);
         $Plugin = new Plugin();
         if ($Plugin->isActivated('manufacturersimports')) {
            if (class_exists("PluginManufacturersimportsModel")) {
               $PluginManufacturersimportsModel = new PluginManufacturersimportsModel();
               $PluginManufacturersimportsModel->addModel($items_id, 'Computer', $a_partnumber[0]);
            }
         }
      }
    }
    
    
    
   static function importGroup($value, $entities_id) {
      global $DB;

      if (empty ($value)) {
         return 0;
      }

      $query2 = "SELECT `id`
                 FROM `glpi_groups`
                 WHERE `name` = '$value'
                       AND `entities_id` = '$entities_id'";
      $result2 = $DB->query($query2);

      if ($DB->numrows($result2) == 0) {
         $group                = new Group();
         $input = array();
         $input["name"]        = $value;
         $input["entities_id"] = $entities_id;
         return $group->add($input);
      }
      $line2 = $DB->fetch_array($result2);
      return $line2["id"];
   }    
}

?>