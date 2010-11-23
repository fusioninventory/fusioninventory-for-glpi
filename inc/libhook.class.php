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
    private function __construct()
    {
    }

    /**
    * create a new machine in an application
    * @access public
    * @return int $externalId Id to match application data with the library
    */
    public static function createMachine($libFilename) {

       // If import computer from GLPI DB
       if (isset($_SESSION['pluginFusinvinventoryImportMachine'])) {
          return $_SESSION['pluginFusinvinventoryImportMachine']['HARDWARE'];
       }

       // Else create computer
      $Computer = new Computer;
      $input = array();
      $input['is_deleted'] = 0;
      $input['autoupdatesystems_id'] = Dropdown::importExternal('AutoUpdateSystem', 'FusionInventory');

      $_SESSION["plugin_fusinvinventory_entity"] = "0";

      $xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);
      // ** Get entity with rules
         $input_rules = array();
         if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
            $input_rules['serialnumber'] = $xml->CONTENT->BIOS->SSN;
         }
         if ((isset($xml->CONTENT->HARDWARE->NAME)) AND (!empty($xml->CONTENT->HARDWARE->NAME))) {
            $input_rules['name'] = $xml->CONTENT->HARDWARE->NAME;
         }
         if (isset($xml->CONTENT->NETWORKS)) {
            foreach($xml->CONTENT->NETWORKS as $network) {
               if ((isset($network->IPADDRESS)) AND (!empty($network->IPADDRESS))) {
                  $input_rules['ip'][] = $network->IPADDRESS;
               }
               if ((isset($network->IPSUBNET)) AND (!empty($network->IPSUBNET))) {
                  $input_rules['subnet'][] = $network->IPADDRESS;
               }
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->USERDOMAIN)) AND (!empty($xml->CONTENT->HARDWARE->USERDOMAIN))) {
            $input_rules['domain'] = $xml->CONTENT->HARDWARE->USERDOMAIN;
         }
         if ((isset($xml->CONTENT->ACCOUNTINFO->KEYNAME)) AND ($xml->CONTENT->ACCOUNTINFO->KEYNAME == 'TAG')) {
            if (isset($xml->CONTENT->ACCOUNTINFO->KEYVALUE)) {
               $input_rules['tag'] = $xml->CONTENT->ACCOUNTINFO->KEYVALUE;
            }
         }

         $ruleEntity = new PluginFusinvinventoryRuleEntityCollection();
         $dataEntity = array ();
         $dataEntity = $ruleEntity->processAllRules($input_rules, array());
         if (isset($dataEntity['entities_id'])) {
            $input['entities_id'] = $dataEntity['entities_id'];
            $_SESSION["plugin_fusinvinventory_entity"] = $dataEntity['entities_id'];
         }

      $computer_id = $Computer->add($input);

      if (isset($_SESSION['SOURCEXML'])) {
         // TODO : Write in _plugins/fusinvinventory/xxx/idmachine.xml
         $folder = substr($computer_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$computer_id, 'w');
         fwrite($fileopen, $_SESSION['SOURCEXML']);
         fclose($fileopen);
       }
       
       $changes = array();
       $changes[0]='0';
       $changes[1]="";
       $changes[2]='Create computer by FusionInventory';
       Log::history($computer_id,'Computer',$changes, 0, HISTORY_LOG_SIMPLE_MESSAGE);

       // Link computer to agent FusionInventory
       $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
       $PluginFusioninventoryAgent->setAgentWithComputerid($computer_id, $xml->DEVICEID);

       PluginFusinvinventoryLiblink::addComputerInDB($computer_id, $libFilename);

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
               if (isset($dataSection['OSCOMMENTS'])) {
                  if (strstr($dataSection['OSCOMMENTS'], 'Service Pack')) {
                     $OperatingSystemServicePack = new OperatingSystemServicePack;
                     $Computer->fields['operatingsystemservicepacks_id'] = $OperatingSystemServicePack->import(array('name'=>$dataSection['OSCOMMENTS']));
                  }
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
      $Computer->update($Computer->fields, 0);
      $j = -1;

      foreach($data as $section) {
         $dataSection = unserialize($section['dataSection']);
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

               $network['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];

               $network['_no_history'] = true;
               $devID = $NetworkPort->add($network);

               array_push($sectionsId,$section['sectionName']."/".$devID);
               break;

            case 'SOFTWARES':

                // If import computer from GLPI DB
                if (isset($_SESSION['pluginFusinvinventoryImportMachine'])) {
                   $Computer_SoftwareVersion_id = array_shift($_SESSION['pluginFusinvinventoryImportMachine']['SOFTWARES']);
                } else {
              
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
        echo "section removed";
        
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
                     // TODO : add a class for this !!!
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

      foreach($data as $section) {
         $dataSection = unserialize($section['dataSection']);
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

            }
         }
      }

       logInFile("updatesection", "[".$idmachine."] ".print_r($data, true));
    }
}

?>