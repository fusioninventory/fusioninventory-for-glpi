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
      if (isset($datainventory['CONTENT']['PORTS'])) {
         unset($datainventory['CONTENT']['PORTS']);
      }
      $datainventory = PluginFusioninventoryFormatconvert::cleanArray($datainventory);
      // Hack for some sections
         $a_fields = array('SOUNDS', 'VIDEOS', 'CONTROLLERS', 'CPUS', 'DRIVES', 'MEMORIES',
                           'NETWORKS', 'SOFTWARE', 'USERS', 'VIRTUALMACHINES', 'ANTIVIRUS');
         foreach ($a_fields as $field) {
            if (isset($datainventory['CONTENT'][$field])
                    AND !is_int(key($datainventory['CONTENT'][$field]))) {
               $datainventory['CONTENT'][$field] = array($datainventory['CONTENT'][$field]);
            }
         }
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
            if (count($value) == 0) {
               $value = '';
            } else {
               $value = PluginFusioninventoryFormatconvert::cleanArray($value);
            }
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
      global $DB;
      
      $a_inventory = array();
      $thisc = new self();
      $pfConfig = new PluginFusioninventoryConfig();
      
      $ignorecontrollers = array();
      
      if (isset($array['ACCOUNTINFO'])) {
         $a_inventory['ACCOUNTINFO'] = $array['ACCOUNTINFO'];
      }
         
      // * HARDWARE
      $array_tmp = $thisc->addValues($array['HARDWARE'], 
                                     array( 
                                        'NAME'           => 'name',
                                        'OSNAME'         => 'operatingsystems_id',
                                        'OSVERSION'      => 'operatingsystemversions_id',
                                        'WINPRODID'      => 'os_licenseid',
                                        'WINPRODKEY'     => 'os_license_number',
                                        'WORKGROUP'      => 'domains_id',
                                        'UUID'           => 'uuid',
                                        'DESCRIPTION'    => 'comment',
                                        'LASTLOGGEDUSER' => 'users_id'));
      if (isset($array_tmp['users_id'])) {
         $query = "SELECT `id`
                   FROM `glpi_users`
                   WHERE `name` = '" . $array_tmp['users_id'] . "';";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $array_tmp['users_id'] = $DB->result($result, 0, 0);
         } else {
            $array_tmp['users_id'] = 0;
         }    
      }
      
      
      $a_inventory['computer'] = $array_tmp;
      
      $array_tmp = $thisc->addValues($array['HARDWARE'], 
                                     array( 
                                        'OSINSTALLDATE' => 'operatingsystem_installationdate',
                                        'WINOWNER' => 'winowner',
                                        'WINCOMPANY' => 'wincompany'));
      $a_inventory['fusioninventorycomputer'] = $array_tmp;
      if (isset($a_inventory['fusioninventorycomputer']['operatingsystem_installationdate'])) {
         $a_inventory['fusioninventorycomputer']['operatingsystem_installationdate'] 
                  = date("Y-m-d", $a_inventory['fusioninventorycomputer']['operatingsystem_installationdate']);
      }
      
      // * BIOS
      if (isset($array['BIOS'])) {
         $a_inventory['BIOS'] = array();
         if ((isset($array['BIOS']['SMANUFACTURER']))
               AND (!empty($array['BIOS']['SMANUFACTURER']))) {
            $a_inventory['computer']['manufacturers_id'] = $array['BIOS']['SMANUFACTURER'];
         } else if ((isset($array['BIOS']['MMANUFACTURER']))
                      AND (!empty($array['BIOS']['MMANUFACTURER']))) {
            $a_inventory['computer']['manufacturers_id'] = $array['BIOS']['MMANUFACTURER'];
         } else if ((isset($array['BIOS']['BMANUFACTURER']))
                      AND (!empty($array['BIOS']['BMANUFACTURER']))) {
            $a_inventory['computer']['manufacturers_id'] = $array['BIOS']['BMANUFACTURER'];
         }
         if (isset($array['BIOS']['SMODEL']) AND $array['BIOS']['SMODEL'] != '') {
            $a_inventory['computer']['computermodels_id'] = $array['BIOS']['SMODEL'];
         } else if (isset($array['BIOS']['MMODEL']) AND $array['BIOS']['MMODEL'] != '') {
            $a_inventory['computer']['computermodels_id'] = $array['BIOS']['MMODEL'];            
         }
         if (isset($array['BIOS']['SSN'])) {
            $a_inventory['computer']['serial'] = $array['BIOS']['SSN'];
            // HP patch for serial begin with 'S'
            if ((isset($a_inventory['computer']['manufacturers_id']))
                  AND (strstr($a_inventory['computer']['manufacturers_id'], "ewlett"))) {

               if (preg_match("/^[sS]/", $a_inventory['BIOS']['SERIAL'])) {
                  $a_inventory['computer']['serial'] = preg_replace("/^[sS]/", "", $a_inventory['BIOS']['SERIAL']);
               }
            }
         }
      }
      // * Type of computer
      if (isset($array['HARDWARE']['CHASSIS_TYPE'])) {
         $a_inventory['computer']['computertypes_id'] = $array['HARDWARE']['CHASSIS_TYPE'];
      } else  if (isset($array['BIOS']['TYPE'])) {
         $a_inventory['computer']['computertypes_id'] = $array['BIOS']['TYPE'];
      } else if (isset($array['BIOS']['MMODEL'])) {
         $a_inventory['computer']['computertypes_id'] = $array['BIOS']['MMODEL'];
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
      
      // * SOUNDS
      foreach ($array['SOUNDS'] as $a_sounds) {
         $a_inventory['sound'][] = $thisc->addValues($a_sounds, 
                                                     array(
                                                        'NAME'          => 'designation', 
                                                        'MANUFACTURER'  => 'manufacturers_id', 
                                                        'DESCRIPTION'   => 'comment'));

         $ignorecontrollers[$a_sounds['NAME']] = 1;
      }
            
      // * VIDEOS
      foreach ($array['VIDEOS'] as $a_videos) {
         $a_inventory['graphiccard'][] = $thisc->addValues($a_videos, 
                                                           array(
                                                              'NAME'          => 'designation', 
                                                              'MEMORY'        => 'memory'));

         $ignorecontrollers[$a_videos['NAME']] = 1;
         if (isset($a_videos['CHIPSET'])) {
            $ignorecontrollers[$a_videos['CHIPSET']] = 1;
         }
      }
      
      // * CONTROLLERS
      foreach ($array['CONTROLLERS'] as $a_controllers) {
         if ((isset($a_controllers["NAME"])) 
                 AND (!isset($ignorecontrollers[$a_controllers["NAME"]]))) {
            $a_inventory['controller'][] = $thisc->addValues($a_controllers, 
                                                              array(
                                                                 'NAME'          => 'designation', 
                                                                 'MANUFACTURER'  => 'manufacturers_id', 
                                                                 'TYPE'          => 'type'));
         }
      }

      // * CPUS
      foreach ($array['CPUS'] as $a_cpus) {
         $array_tmp = $thisc->addValues($a_cpus, 
                                        array( 
                                           'SPEED' => 'frequence', 
                                           'MANUFACTURER' => 'manufacturers_id', 
                                           'SERIAL' => 'serial'));
         if (isset($a_cpus['NAME'])) {
            $array_tmp['designation'] = $a_cpus['NAME'];
         } else if (isset($a_cpus['TYPE'])) {
            $array_tmp['designation'] = $a_cpus['TYPE'];
         }
         $a_inventory['processor'][] = $array_tmp;
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
                                              'FILESYSTEM' => 'filesystems_id',
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
            $a_inventory['computerdisk'][] = $array_tmp;
         }
      }
      
      // * OPERATINGSYSTEM
      $array_tmp = $thisc->addValues($array['OPERATINGSYSTEM'], 
                                     array( 
                                        'FULL_NAME'      => 'operatingsystems_id',
                                        'KERNEL_VERSION' => 'operatingsystemversions_id',
                                        'SERVICE_PACK'   => 'operatingsystemservicepacks_id'));
      foreach ($array_tmp as $key=>$value) {
         $a_inventory['fusioninventorycomputer'][$key] = $value;
      }
      
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
                                              'MACADDR'     => 'mac', 
                                              'TYPE'        => 'instantiation_type',
                                              'IPADDRESS'   => 'ip',
                                              'IPADDRESS6'  => 'ip',
                                              'VIRTUALDEV'  => 'virtualdev',
                                              'IPSUBNET'    => 'subnet'));
            if (isset($a_networknames[$array_tmp['name']])) {
               if (isset($array_tmp['ip'])) {
                  $a_networknames[$array_tmp['name']]['ipaddress'][] = $array_tmp['ip'];
               }
            } else {
               if (isset($array_tmp['ip'])) {
                  $array_tmp['ipaddress'] = array($array_tmp['ip']);
                  unset($array_tmp['ip']);
               } else {
                  $array_tmp['ipaddress'] = array();
               }
               if (isset($array_tmp["instantiation_type"])
                       AND $array_tmp["instantiation_type"] == 'Ethernet') {
                  $array_tmp["instantiation_type"] = 'NetworkPortEthernet';
               } else if (isset($array_tmp["instantiation_type"])
                       AND ($array_tmp["instantiation_type"] == 'Wifi'
                            OR $array_tmp["instantiation_type"] == 'IEEE')) {
                  $array_tmp["instantiation_type"] = 'NetworkPortWifi';
               } else {
                  $array_tmp["instantiation_type"] = 'NetworkPortLocal';
               }
               $a_networknames[$array_tmp['name']] = $array_tmp;
            }
         }
      }
      $a_inventory['networkport'] = $a_networknames;
      
      // * SLOTS
      
      // * SOFTWARES
      if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
              "import_software", 'inventory') != '0') {
         
         $rulecollection = new RuleDictionnarySoftwareCollection();
         foreach ($array['SOFTWARES'] as $a_softwares) {
            $array_tmp = $thisc->addValues($a_softwares, 
                                           array( 
                                              'PUBLISHER'   => 'manufacturer', 
                                              'NAME'        => 'name', 
                                              'VERSION'     => 'version'));
            if (isset($array_tmp['manufacturer'])) {
               $array_tmp['manufacturer'] = Manufacturer::processName($array_tmp['manufacturer']);
            } else {
               $array_tmp['manufacturer'] = '';
            }
            $res_rule = $rulecollection->processAllRules(array(
                                                            "name"         => $array_tmp['name'],
                                                            "manufacturer" => $array_tmp['manufacturer'],
                                                            "old_version"  => $array_tmp['version']));
            if (isset($res_rule['_ignore_ocs_import']) AND $res_rule['_ignore_ocs_import'] == "1") {

            } else {
               if (isset($res_rule["name"])) {
                  $array_tmp['name'] = $res_rule["name"];
               }
               if (isset($res_rule["version"]) && $res_rule["version"]!= '') {
                  $array_tmp['version'] = $res_rule["version"];
               }
               if (isset($res_rule["manufacturer"])  && $res_rule["manufacturer"]) {
                  $array_tmp['manufacturer'] = Dropdown::getDropdownName("glpi_manufacturers", $res_rule["manufacturer"]);
               }
               if (!isset($array_tmp['manufacturer'])) {
                  $array_tmp['manufacturer'] = '';
               }
               if (isset($res_rule['new_entities_id'])) {
                  $array_tmp['entities_id'] = $res_rule['new_entities_id'];
               }
               $a_inventory['software'][] = $array_tmp;
            }
         }         
      }

      
      // * STORAGES
      
      // * USERS
      foreach ($array['USERS'] as $a_users) {
         $array_tmp = $thisc->addValues($a_users, 
                                        array( 
                                           'LOGIN' => 'login', 
                                           'DOMAIN' => 'domain'));
         $user = '';
         if (isset($array_tmp['login'])) {
            $user = $array_tmp['login'];
            if (isset($array_tmp['domain'])) {
               $user .= "@".$array_tmp['domain'];
            }
         }
         if ($user != '') {
            if (isset($a_inventory['computer']['contact'])) {
               if ($a_inventory['computer']['contact'] == '') {
                  $a_inventory['computer']['contact'] = $user;
               } else {
                  $a_inventory['computer']['contact'] .= "/".$user;
               }
            } else {
               $a_inventory['computer']['contact'] = $user;
            }
         }
      }
     
      // * VIRTUALMACHINES
      if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
              "import_vm", 'inventory') != '0') {
         
          foreach ($array['VIRTUALMACHINES'] as $a_virtualmachines) {
            $a_inventory['virtualmachine'][] = $thisc->addValues($a_virtualmachines, 
                                           array( 
                                              'NAME'        => 'name', 
                                              'VCPU'        => 'vcpu', 
                                              'MEMORY'      => 'ram', 
                                              'VMTYPE'      => 'virtualmachinetypes_id', 
                                              'SUBSYSTEM'   => 'virtualmachinesystems_id', 
                                              'STATUS'      => 'virtualmachinestates_id', 
                                              'UUID'        => 'uuid'));
         }
      }
      
      // * ANTIVIRUS
      if (isset($array['ANTIVIRUS'])) {
         foreach ($array['ANTIVIRUS'] as $a_antiviruses) {
            $a_inventory['antivirus'][] = $thisc->addValues($a_antiviruses, 
                                                          array( 
                                                             'NAME'     => 'name', 
                                                             'COMPANY'  => 'manufacturers_id', 
                                                             'VERSION'  => 'version',
                                                             'ENABLED'  => 'is_active',
                                                             'UPTODATE' => 'uptodate'));
         }
      }
      if (!isset($a_inventory['antivirus'])) {
         $a_inventory['antivirus'] = array();
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
   
   
   
   static function computerReplaceids($array) {
      
      foreach ($array as $key=>$value) {
         if (is_array($value)) {
            $array[$key] = PluginFusioninventoryFormatconvert::computerReplaceids($value);
         } else {
            $itemtype = '';
            if ($key == 'manufacturers_id') {
               $itemtype = 'Manufacturer';
            } else if ($key == 'computermodels_id') {
               $itemtype = 'ComputerModel';
            } else if ($key == 'computertypes_id') {
               $itemtype = 'ComputerType';
            } else if ($key == 'domains_id') {
               $itemtype = 'Domain';
            } else if ($key == 'operatingsystems_id') {
               $itemtype = 'OperatingSystem';
            } else if ($key == 'operatingsystemversions_id') {
               $itemtype = 'OperatingSystemVersion';
            } else if ($key == 'operatingsystemservicepacks_id') {
               $itemtype = 'OperatingSystemServicePack';
            } else if ($key == 'virtualmachinetypes_id') {
               $itemtype = 'VirtualMachineType';
            } else if ($key == 'virtualmachinesystems_id') {
               $itemtype = 'VirtualMachineSystem';
            } else if ($key == 'virtualmachinestates_id') {
               $itemtype = 'VirtualMachineState';
            } else if ($key == 'filesystems_id') {
               $itemtype = 'Filesystem';
            } else if ($key == "manufacturer") {               
               $array['manufacturers_id']= Dropdown::importExternal('Manufacturer',
                                                                    $value);
            }
            if ($itemtype != '') {
               $value = Dropdown::importExternal($itemtype,
                                                 $value);
               $array[$key] = $value;  
            }
         }
      }
      return $array;
   }
}

?>