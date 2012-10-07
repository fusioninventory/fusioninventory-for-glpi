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
   @since     2012

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryFormatconvert {
   
   static function XMLtoArray($xml) {
      $datainventory = array();
      $datainventory = json_decode(json_encode((array)$xml), true);
      if (isset($datainventory['CONTENT']['ENVS'])) {
         unset($datainventory['CONTENT']['ENVS']);
      }
      if (isset($datainventory['CONTENT']['PROCESSES'])) {
         unset($datainventory['CONTENT']['PROCESSES']);
      }
      $datainventory = PluginFusioninventoryFormatconvert::cleanArray($datainventory);
      return $datainventory;
   }
   
   
   
   static function JSONtoArray($json) {
      $datainventory = array();
      $datainventory = json_decode($json, true);
      $datainventory = PluginFusioninventoryFormatconvert::cleanArray($datainventory);
      return $datainventory;
   }
   
   
   
   static function cleanArray($data) {
      foreach ($data as $key=>$value) {
         if (is_array($value)) {
            $value = PluginFusioninventoryFormatconvert::cleanArray($value);
         } else {
            $value = Toolbox::clean_cross_side_scripting_deep(Toolbox::addslashes_deep($value));
         }
         $data[$key] = $value;
      }
      return $data;
   }
   
   
   
   /*
    * Modify Computer inventory
    */
   static function computerInventoryTransformation($array) {
      $a_inventory = array();
      
      $pfConfig = new PluginFusioninventoryConfig();
      
      if (isset($array['ACCOUNTINFO'])) {
         $a_inventory['ACCOUNTINFO'] = $array['ACCOUNTINFO'];
      }
      if (isset($array['BIOS'])) {
         $a_inventory['BIOS'] = array();
         if ((isset($array['BIOS']['SMANUFACTURER']))
               AND (!empty($array['BIOS']['SMANUFACTURER']))) {
            $a_inventory['BIOS']['MANUFACTURER'] = $array['BIOS']['SMANUFACTURER'];
         } else if ((isset($array['BIOS']['MMANUFACTURER']))
                      AND (!empty($array['BIOS']['MMANUFACTURER']))) {
            $a_inventory['BIOS']['MANUFACTURER'] = $array['BIOS']['MMANUFACTURER'];
         } else if ((isset($array['BIOS']['BMANUFACTURER']))
                      AND (!empty($array['BIOS']['BMANUFACTURER']))) {
            $a_inventory['BIOS']['MANUFACTURER'] = $array['BIOS']['BMANUFACTURER'];
         }
         if (isset($array['BIOS']['SMODEL']) AND $array['BIOS']['SMODEL'] != '') {
            $a_inventory['BIOS']['MODEL'] = $array['BIOS']['SMODEL'];
         } else if (isset($array['BIOS']['MMODEL']) AND $array['BIOS']['MMODEL'] != '') {
            $a_inventory['BIOS']['MODEL'] = $array['BIOS']['MMODEL'];            
         }
         if (isset($array['BIOS']['SSN'])) {
            $a_inventory['BIOS']['SERIAL'] = $array['BIOS']['SSN'];
            // HP patch for serial begin with 'S'
            if ((isset($a_inventory['BIOS']['MANUFACTURER']))
                  AND (strstr($a_inventory['BIOS']['MANUFACTURER'], "ewlett"))) {

               if (preg_match("/^[sS]/", $a_inventory['BIOS']['SERIAL'])) {
                  $a_inventory['BIOS']['SERIAL'] = preg_replace("/^[sS]/", "", $a_inventory['BIOS']['SERIAL']);
               }
            }
         }
      }
      // * Type of computer
      if (isset($array['HARDWARE']['CHASSIS_TYPE'])) {
         $a_inventory['BIOS']['TYPE'] = $array['HARDWARE']['CHASSIS_TYPE'];
      } else  if (isset($array['BIOS']['TYPE'])) {
         $a_inventory['BIOS']['TYPE'] = $array['BIOS']['TYPE'];
      } else if (isset($array['BIOS']['MMODEL'])) {
         $a_inventory['BIOS']['TYPE'] = $array['BIOS']['MMODEL'];
      }
      
      if (isset($array['BIOS']['SKUNUMBER'])) {
         $a_inventory['BIOS']['PARTNUMBER'] = $array['BIOS']['SKUNUMBER'];
      }
      
      if (isset($array['BIOS']['BDATE'])) {
         $a_split = explode("/", $array['BIOS']['BDATE']);
         // 2011-06-29 13:19:48
         if (isset($a_split[0])
                 AND isset($a_split[1])
                 AND isset($a_split[2])) {
            $a_inventory['BIOS']['DATE'] = $a_split[2]."-".$a_split[0]."-".$a_split[1];
         }
      }
      if (isset($array['BIOS']['BVERSION'])) {
         $a_inventory['BIOS']['VERSION'] = $array['BIOS']['BVERSION'];
      }
      if (isset($array['BIOS']['BMANUFACTURER'])) {
         $a_inventory['BIOS']['BIOSMANUFACTURER'] = $array['BIOS']['BMANUFACTURER'];
      }

      // * CONTROLLERS
      $thisc = new self();
      foreach ($array['CONTROLLERS'] as $a_controllers) {
         $a_inventory['CONTROLLERS'][] = $thisc->addValues($a_controllers, 
                                                           array(
                                                              'NAME'          => 'designation', 
                                                              'MANUFACTURER'  => 'manufacturer', 
                                                              'TYPE'          => 'type'));
      }

      // * CPUS
      foreach ($array['CPUS'] as $a_cpus) {
         $array_tmp = $thisc->addValues($a_cpus, 
                                        array( 
                                           'SPEED' => 'frequence', 
                                           'MANUFACTURER' => 'manufacturer', 
                                           'SERIAL' => 'serial'));
         if (isset($a_cpus['NAME'])) {
            $array_tmp['designation'] = $a_cpus['NAME'];
         } else if (isset($a_cpus['TYPE'])) {
            $array_tmp['designation'] = $a_cpus['TYPE'];
         }
         $a_inventory['CPUS'][] = $array_tmp;
      }

      // * DRIVES
      foreach ($array['DRIVES'] as $a_drives) {
         if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
                 "component_drive", 'inventory') == '0'
             OR ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
                 "component_networkdrive", 'inventory') == '0'
                 AND ((isset($array['DRIVES']['TYPE'])
                    AND $array['DRIVES']['TYPE'] == 'Network Drive')
                     OR isset($array['DRIVES']['FILESYSTEM'])
                    AND $array['DRIVES']['FILESYSTEM'] == 'nfs'))
             OR ((isset($array['DRIVES']['TYPE'])) AND
                 (($array['DRIVES']['TYPE'] == "Removable Disk")
                OR ($array['DRIVES']['TYPE'] == "Compact Disc")))) {

         } else {
            $array_tmp = $thisc->addValues($a_drives, 
                                           array( 
                                              'VOLUMN' => 'device',                                               
                                              'FILESYSTEM' => 'filesystem',
                                              'TOTAL' => 'totalsize', 
                                              'FREE' => 'freesize'));
            if ((isset($a_drives['LABEL'])) AND (!empty($a_drives['LABEL']))) {
               $array_tmp['name'] = $a_drives['LABEL'];
            } else if (((!isset($a_drives['VOLUMN'])) 
                    OR (empty($a_drives['VOLUMN']))) 
                    AND (isset($a_drives['LETTER']))) {
               $array_tmp['name'] = $a_drives['LETTER'];
            } else if (isset($a_drives['TYPE'])) {
               $array_tmp['name'] = $a_drives['TYPE'];
            } else if (isset($a_drives['VOLUMN'])) {
               $array_tmp['name'] = $a_drives['VOLUMN'];
            }
            if (isset($a_drives['MOUNTPOINT'])) {
               $array_tmp['mountpoint'] = $a_drives['MOUNTPOINT'];
            } else if (isset($a_drives['LETTER'])) {
               $array_tmp['mountpoint'] = $a_drives['LETTER'];
            } else if (isset($a_drives['TYPE'])) {
               $array_tmp['mountpoint'] = $a_drives['TYPE'];
            }            
            $a_inventory['DRIVES'][] = $array_tmp;
         }
      }

      // * HARDWARE
      $array_tmp = $thisc->addValues($array, 
                                     array( 
                                        'NAME' => 'name',
                                        'OSNAME' => 'operatingsystem',
                                        'OSVERSION' => 'operatingsystemversion',
                                        'WINPRODID' => 'os_licenseid',
                                        'WINPRODKEY' => 'os_license_number',
                                        'WORKGROUP' => 'domain',
                                        'UUID' => 'uuid',
                                        'DESCRIPTION' => 'comment'));
      if (isset($array_tmp['operatingsystem_installationdate'])) {
         $array_tmp['operatingsystem_installationdate'] = date("Y-m-d", $array_tmp['operatingsystem_installationdate']);
      }
      $a_inventory['computer'] = $array_tmp;
      
      $array_tmp = $thisc->addValues($array, 
                                     array( 
                                        'OSINSTALLDATE' => 'operatingsystem_installationdate',
                                        'WINOWNER' => 'winowner',
                                        'WINCOMPANY' => 'wincompany'));
      $a_inventory['fusioninventorycomputer'] = $array_tmp;
      
      // * MEMORIES
      foreach ($array['MEMORIES'] as $a_memories) {
         if ((!isset($a_memories["CAPACITY"]))
              OR ((isset($a_memories["CAPACITY"]))
                      AND (!preg_match("/^[0-9]+$/i", $a_memories["CAPACITY"])))) {
            // Nothing
         } else {
            $array_tmp = $thisc->addValues($a_memories, 
                                           array( 
                                              'CAPACITY' => 'size', 
                                              'SPEED' => 'frequence', 
                                              'TYPE' => 'devicememorytype',
                                              'SERIALNUMBER' => 'serial'));
            $array_tmp['designation'] = "";
            if (isset($a_memories["TYPE"]) 
                    && $a_memories["TYPE"]!="Empty Slot" 
                    && $a_memories["TYPE"] != "Unknown") {
               $array_tmp["designation"] = $a_memories["TYPE"];
            }
            if (isset($a_memories["DESCRIPTION"])) {
               if (!empty($array_tmp["designation"])) {
                  $array_tmp["designation"].=" - ";
               }
               $array_tmp["designation"] .= $a_memories["DESCRIPTION"];
            }
            $a_inventory['memory'][] = $array_tmp;
         }
      }
      
      // * MONITORS
      
      
      
      // * NETWORKS
      $a_networknames = array();
      foreach ($array['NETWORKS'] as $a_networks) {
         $virtual_import = 1;
         if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
                 "component_networkcardvirtual", 'inventory') == '0') {
            if (isset($a_networks['VIRTUALDEV'])
                    AND $a_networks['VIRTUALDEV']=='1') {

               $virtual_import = 0;
            }
         }
         if ($virtual_import == 1) {
            $array_tmp = $thisc->addValues($a_networks, 
                                           array( 
                                              'DESCRIPTION' => 'name', 
                                              'MACADDR' => 'mac', 
                                              'TYPE' => 'instantiation_type',
                                              'IPADDRESS' => 'ip',
                                              'IPADDRESS6' => 'ip'));
            if (isset($a_networknames[$array_tmp['name']])) {
               $a_networknames[$array_tmp['name']]['ipaddress'][] = $array_tmp['ip'];
            } else {
               $array_tmp['ipaddress'] = array($array_tmp['ip']);
               unset($array_tmp['ip']);
               $a_networknames[$array_tmp['name']] = $array_tmp;
            }
         }
      }
      $a_inventory['networkport'] = $a_networknames;
      
      // * OPERATINGSYSTEM
      
      // * PORTS
      
      // * SLOTS
      
      // * SOFTWARES
      
      // * SOUNDS
      
      // * STORAGES
      
      // * USERS
      
      // * VIDDEOS
      
      
      // * VIRTUALMACHINES
      if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
              "import_vm", 'inventory') != '0') {
         
          foreach ($array['VIRTUALMACHINES'] as $a_virtualmachines) {
            $a_inventory['virtualmachines'][] = $thisc->addValues($a_virtualmachines, 
                                           array( 
                                              'NAME' => 'name', 
                                              'VCPU' => 'vcpu', 
                                              'MEMORY' => 'ram', 
                                              'VMTYPE' => 'virtualmachinetypes', 
                                              'SUBSYSTEM' => 'virtualmachinesystems', 
                                              'STATUS' => 'virtualmachinestates', 
                                              'UUID' => 'uuid'));
         }
      }
      
      
      
      
      
      return $a_inventory;
   }
   
   
   
   static function addValues($array, $a_key) {
      $a_return = array();
      $a_keys = array_keys($a_key);
      foreach ($array as $key=>$value) {
         if (in_array($key, $a_keys)) {
            $a_return[$a_key[$key]] = $value;
         }
      }
      return $a_return;
   }
}

?>