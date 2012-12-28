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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

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
   var $foreignkey_itemtype = array();
   var $manufacturer_cache = array();
   
   
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
                           'NETWORKS', 'SOFTWARE', 'USERS', 'VIRTUALMACHINES', 'ANTIVIRUS',
                           'MONITORS', 'PRINTERS', 'USBDEVICES', 'PHYSICAL_VOLUMES',
                           'VOLUME_GROUPS', 'LOGICAL_VOLUMES', 'BATTERIES');
         foreach ($a_fields as $field) {
            if (isset($datainventory['CONTENT'][$field])
                    AND !is_array($datainventory['CONTENT'][$field])) {
               $datainventory['CONTENT'][$field] = array($datainventory['CONTENT'][$field]);
            } else if (isset($datainventory['CONTENT'][$field])
                    AND !is_int(key($datainventory['CONTENT'][$field]))) {
               $datainventory['CONTENT'][$field] = array($datainventory['CONTENT'][$field]);
            }
         }
      if (isset($datainventory['CONTENT'])
              && isset($datainventory['CONTENT']['BIOS'])
              && !is_array($datainventory['CONTENT']['BIOS'])) {
         unset($datainventory['CONTENT']['BIOS']);
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
            $value = str_replace("\'", "'", $value);
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
                                        'LASTLOGGEDUSER' => 'users_id',
                                        'operatingsystemservicepacks_id' => 'operatingsystemservicepacks_id',
                                        'manufacturers_id' => 'manufacturers_id',
                                        'computermodels_id' => 'computermodels_id',
                                        'serial' => 'serial',
                                        'computertypes_id' => 'computertypes_id'));
      if ($array_tmp['operatingsystemservicepacks_id'] == ''
              && isset($array['HARDWARE']['OSCOMMENTS'])
              && $array['HARDWARE']['OSCOMMENTS'] != '') {
         $array_tmp['operatingsystemservicepacks_id'] = $array['HARDWARE']['OSCOMMENTS'];
      }
      if (isset($array_tmp['users_id'])) {
         $query = "SELECT `id`
                   FROM `glpi_users`
                   WHERE `name` = '" . $array_tmp['users_id'] . "'
                   LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $array_tmp['users_id'] = $DB->result($result, 0, 0);
         } else {
            $array_tmp['users_id'] = 0;
         }    
      }
      $array_tmp['is_ocs_import'] = 1;
      $array_tmp['is_dynamic'] = 1;
      
      $a_inventory['computer'] = $array_tmp;
      
      $array_tmp = $thisc->addValues($array['HARDWARE'], 
                                     array( 
                                        'OSINSTALLDATE' => 'operatingsystem_installationdate',
                                        'WINOWNER' => 'winowner',
                                        'WINCOMPANY' => 'wincompany'));
      $array_tmp['last_fusioninventory_update'] = date('Y-m-d H:i:s');
      $a_inventory['fusioninventorycomputer'] = $array_tmp;
      if (!empty($a_inventory['fusioninventorycomputer']['operatingsystem_installationdate'])) {
         $a_inventory['fusioninventorycomputer']['operatingsystem_installationdate'] 
                  = date("Y-m-d", $a_inventory['fusioninventorycomputer']['operatingsystem_installationdate']);
      } else {
         unset($a_inventory['fusioninventorycomputer']['operatingsystem_installationdate']);
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
         if ((isset($array['BIOS']['MMANUFACTURER']))
                      AND (!empty($array['BIOS']['MMANUFACTURER']))) {
            $a_inventory['computer']['mmanufacturer'] = $array['BIOS']['MMANUFACTURER'];
         }
         if ((isset($array['BIOS']['BMANUFACTURER']))
                      AND (!empty($array['BIOS']['BMANUFACTURER']))) {
            $a_inventory['computer']['bmanufacturer'] = $array['BIOS']['BMANUFACTURER'];
         }
         
         if (isset($array['BIOS']['SMODEL']) AND $array['BIOS']['SMODEL'] != '') {
            $a_inventory['computer']['computermodels_id'] = $array['BIOS']['SMODEL'];
         } else if (isset($array['BIOS']['MMODEL']) AND $array['BIOS']['MMODEL'] != '') {
            $a_inventory['computer']['computermodels_id'] = $array['BIOS']['MMODEL'];            
         }
         if (isset($array['BIOS']['SSN'])) {
            $a_inventory['computer']['serial'] = trim($array['BIOS']['SSN']);
            // HP patch for serial begin with 'S'
            if ((isset($a_inventory['computer']['manufacturers_id']))
                  AND (strstr($a_inventory['computer']['manufacturers_id'], "ewlett"))) {

               if (isset($a_inventory['BIOS']['SERIAL'])
                       && preg_match("/^[sS]/", $a_inventory['BIOS']['SERIAL'])) {
                  $a_inventory['computer']['serial'] = trim(preg_replace("/^[sS]/", "", $a_inventory['BIOS']['SERIAL']));
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
      
      // * OPERATINGSYSTEM
      if (isset($array['OPERATINGSYSTEM'])) {
         $array_tmp = $thisc->addValues($array['OPERATINGSYSTEM'], 
                                        array( 
                                           'FULL_NAME'      => 'operatingsystems_id',
                                           'KERNEL_VERSION' => 'operatingsystemversions_id',
                                           'SERVICE_PACK'   => 'operatingsystemservicepacks_id'));
         
         foreach ($array_tmp as $key=>$value) {
            if ($a_inventory['computer'][$key] != '') {
               $a_inventory['computer'][$key] = $value;
            }
         }
      }
      
      // * BATTERIES
//      $a_inventory['batteries'] = array();
//      if (isset($array['BATTERIES'])) {
//         foreach ($array['BATTERIES'] as $a_batteries) {
//            $a_inventory['soundcard'][] = $thisc->addValues($a_batteries, 
//               array(
//                  'NAME'          => 'name', 
//                  'MANUFACTURER'  => 'manufacturers_id', 
//                  'SERIAL'     => 'serial', 
//                  'DATE'       => 'date', 
//                  'CAPACITY'   => 'capacity', 
//                  'CHEMISTRY'  => 'plugin_fusioninventory_inventorycomputerchemistries_id', 
//                  'VOLTAGE'    => 'voltage'));
//         }
//      }
      
      
      
      // * SOUNDS
      $a_inventory['soundcard'] = array();
      if ($pfConfig->getValue('component_soundcard') == 1) {
         if (isset($array['SOUNDS'])) {
            foreach ($array['SOUNDS'] as $a_sounds) {
               $a_inventory['soundcard'][] = $thisc->addValues($a_sounds, 
                                                           array(
                                                              'NAME'          => 'designation', 
                                                              'MANUFACTURER'  => 'manufacturers_id', 
                                                              'DESCRIPTION'   => 'comment'));

               $ignorecontrollers[$a_sounds['NAME']] = 1;
            }
         }
      }
            
      // * VIDEOS
      $a_inventory['graphiccard'] = array();
      if ($pfConfig->getValue('component_graphiccard') == 1) {
         if (isset($array['VIDEOS'])) {
            foreach ($array['VIDEOS'] as $a_videos) {
               if (is_array($a_videos)
                       && isset($a_videos['NAME'])) {
                  $array_tmp = $thisc->addValues($a_videos,array(
                                                              'NAME'   => 'designation', 
                                                              'MEMORY' => 'memory'));
                  if (!isset($array_tmp['memory'])) {
                     $array_tmp['memory'] = 0;
                  }
                  $a_inventory['graphiccard'][] = $array_tmp;
                  if (isset($a_videos['NAME'])) {
                     $ignorecontrollers[$a_videos['NAME']] = 1;
                  }
                  if (isset($a_videos['CHIPSET'])) {
                     $ignorecontrollers[$a_videos['CHIPSET']] = 1;
                  }
               }
            }
         }
      }
      
      // * CONTROLLERS
      $a_inventory['controller'] = array();
      if ($pfConfig->getValue('component_control') == 1) {
         if (isset($array['CONTROLLERS'])) {
            foreach ($array['CONTROLLERS'] as $a_controllers) {
               if ((isset($a_controllers["NAME"])) 
                       AND (!isset($ignorecontrollers[$a_controllers["NAME"]]))) {
                  $a_inventory['controller'][] = $thisc->addValues($a_controllers, 
                                                                    array(
                                                                       'NAME'          => 'designation', 
                                                                       'MANUFACTURER'  => 'manufacturers_id',
                                                                       'type'          => 'interfacetypes_id'));
               }
            }
         }
      }

      // * CPUS
      $a_inventory['processor'] = array();
      if ($pfConfig->getValue('component_processor') == 1) {
         if (isset($array['CPUS'])) {
            foreach ($array['CPUS'] as $a_cpus) {
               if (is_array($a_cpus)
                       && (isset($a_cpus['NAME'])
                        || isset($a_cpus['TYPE']))) {
                  $array_tmp = $thisc->addValues($a_cpus, 
                                                 array( 
                                                    'SPEED'        => 'frequence', 
                                                    'MANUFACTURER' => 'manufacturers_id', 
                                                    'SERIAL'       => 'serial',
                                                    'NAME'         => 'designation'));
                  if ($array_tmp['designation'] == ''
                          && isset($a_cpus['TYPE'])) {
                     $array_tmp['designation'] = $a_cpus['TYPE'];
                  }
                  $array_tmp['frequency'] = $array_tmp['frequence'];
                  $a_inventory['processor'][] = $array_tmp;
               }
            }
         }
      }
      
      // * DRIVES
      $a_inventory['computerdisk'] = array();
      if (isset($array['DRIVES'])) {
         foreach ($array['DRIVES'] as $a_drives) {
            if ($pfConfig->getValue("component_drive") == '0'
                OR ($pfConfig->getValue("component_networkdrive") == '0'
                    AND ((isset($a_drives['TYPE'])
                       AND $a_drives['TYPE'] == 'Network Drive')
                        OR isset($a_drives['FILESYSTEM'])
                       AND $a_drives['FILESYSTEM'] == 'nfs'))
                OR ((isset($a_drives['TYPE'])) AND
                    (($a_drives['TYPE'] == "Removable Disk")
                   OR ($a_drives['TYPE'] == "Compact Disc")))) {

            } else {
               if ($pfConfig->getValue('import_volume') == 1) {
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
         }
      }
      
      // * MEMORIES
      $a_inventory['memory'] = array();
      if ($pfConfig->getValue('component_memory') == 1) {
         if (isset($array['MEMORIES'])) {
            foreach ($array['MEMORIES'] as $a_memories) {
               if ((!isset($a_memories["CAPACITY"]))
                    OR ((isset($a_memories["CAPACITY"]))
                            AND (!preg_match("/^[0-9]+$/i", $a_memories["CAPACITY"])))) {
                  // Nothing
               } else {
                  $array_tmp = $thisc->addValues($a_memories, 
                                                 array( 
                                                    'CAPACITY'     => 'size', 
                                                    'SPEED'        => 'frequence', 
                                                    'TYPE'         => 'devicememorytypes_id',
                                                    'SERIALNUMBER' => 'serial'));
                  if ($array_tmp['size'] > 0) {
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
            }
         }
      }
      
      // * MONITORS
      $a_inventory['monitor'] = array();
      if ($pfConfig->getValue('import_monitor') > 0) {
         if (isset($array['MONITORS'])) {
            $a_serialMonitor = array();
            foreach ($array['MONITORS'] as $a_monitors) {
               $array_tmp = $thisc->addValues($a_monitors, 
                                              array( 
                                                 'CAPTION'      => 'name', 
                                                 'MANUFACTURER' => 'manufacturers_id', 
                                                 'SERIAL'       => 'serial',
                                                 'DESCRIPTION'  => 'comment'));
               if (!($pfConfig->getValue('import_monitor') == 3
                       && $array_tmp['serial'] == '')) {
                  $add = 1;
                  if (isset($array_tmp['serial'])
                          && $array_tmp['serial'] != ''
                          && isset($a_serialMonitor[$array_tmp['serial']])) {
                     $add = 0;
                  }
                  if (!isset($array_tmp['name'])) {
                     $array_tmp['name'] = '';
                  }
                  if (!isset($array_tmp['comment'])) {
                     $array_tmp['comment'] = '';
                  }
                  if (!isset($array_tmp['serial'])) {
                     $array_tmp['serial'] = '';
                  }
                  if (!isset($array_tmp['manufacturers_id'])) {
                     $array_tmp['manufacturers_id'] = '';
                  }
                  if ($add == 1) {
                     $a_inventory['monitor'][] = $array_tmp;
                     $a_serialMonitor[$array_tmp['serial']] = 1;
                  }
               }
            }
         }
      }
 
      
      // * PRINTERS
      $a_inventory['printer'] = array();
      if ($pfConfig->getValue('import_printer') > 0) {
         if (isset($array['PRINTERS'])) {
            $rulecollection = new RuleDictionnaryPrinterCollection();
            foreach ($array['PRINTERS'] as $a_printers) {
               $array_tmp = $thisc->addValues($a_printers, 
                                              array( 
                                                 'NAME'         => 'name', 
                                                 'PORT'         => 'port', 
                                                 'SERIAL'       => 'serial'));
               if (!($pfConfig->getValue('import_printer') == 3
                       && $array_tmp['serial'] == '')) {
                 
                  if (strstr($array_tmp['port'], "USB")) {
                     $array_tmp['have_usb'] = 1;
                  } else {
                     $array_tmp['have_usb'] = 0;
                  }
                  unset($array_tmp['port']);
                  $res_rule = $rulecollection->processAllRules(array("name"=>$array_tmp['name']));

                  if (isset($res_rule['_ignore_ocs_import']) AND $res_rule['_ignore_ocs_import'] == "1") {
                     // Ignrore import printer
                  } else {
                     $a_inventory['printer'][] = $array_tmp;
                  }
               }
            }
         }
      }
      
      
      
      // * PERIPHERAL
      $a_inventory['peripheral'] = array();
      if ($pfConfig->getValue('import_monitor') > 0) {
         if (isset($array['USBDEVICES'])) {
            foreach ($array['USBDEVICES'] as $a_monitors) {
               $array_tmp = $thisc->addValues($a_monitors, 
                                              array( 
                                                 'NAME'      => 'name', 
                                                 'MANUFACTURER' => 'manufacturers_id', 
                                                 'SERIAL'       => 'serial',
                                                 'PRODUCTNAME'  => 'productname'));
               if ($array_tmp['productname'] != '') {
                  $array_tmp['name'] = $array_tmp['productname'];
               }
               unset($array_tmp['productname']);
               if (!($pfConfig->getValue('import_peripheral') == 3
                       && $array_tmp['serial'] == '')) {
                  
                  $a_inventory['peripheral'][] = $array_tmp;
               }
            }
         }
      }
      
      
      // * NETWORKS
      $a_inventory['networkport'] = array();
      if ($pfConfig->getValue('component_networkcard') == 1) {
         if (isset($array['NETWORKS'])) {
            $a_networknames = array();
            foreach ($array['NETWORKS'] as $a_networks) {
               $virtual_import = 1;
               if ($pfConfig->getValue("component_networkcardvirtual") == '0') {
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
                  if (isset($array_tmp['name'])
                       && $array_tmp['name'] != '') {
                     
                     $array_tmp['mac'] = strtolower($array_tmp['mac']);
                     if (isset($a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']])) {
                        if (isset($array_tmp['ip'])) {
                           $a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']]['ipaddress'][] = $array_tmp['ip'];
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
                        } else if ($array_tmp['mac'] != '') {
                           $array_tmp["instantiation_type"] = 'NetworkPortEthernet';
                        } else {
                           $array_tmp["instantiation_type"] = 'NetworkPortLocal';
                        }
                        $a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']] = $array_tmp;
                     }
                  }
               }
            }
            $a_inventory['networkport'] = $a_networknames;
         }
      }
      
      // * SLOTS
      
      // * SOFTWARES
      $a_inventory['SOFTWARES'] = array();
      if ($pfConfig->getValue('import_software') == 1) {
         if (isset($array['SOFTWARES'])) {
            $a_inventory['SOFTWARES'] = $array['SOFTWARES'];
         }
      }
      
      // * STORAGES/COMPUTERDISK
      $a_inventory['harddrive'] = array();
      if (isset($array['STORAGES'])) {
         foreach ($array['STORAGES']  as $a_storage) {
            $type_tmp = PluginFusioninventoryFormatconvert::getTypeDrive($a_storage);
            if ($type_tmp == "Drive") {
               // it's cd-rom / dvd
//               if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
//                    "component_drive") =! 0) {
//// TODO ***
//                }
            } else {
               // it's harddisk
//               if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
//                    "component_harddrive") != 0) {
               if (is_array($a_storage)) {
                  if ($pfConfig->getValue('component_harddrive') == 1) {
                     $array_tmp = $thisc->addValues($a_storage, 
                                                    array( 
                                                        'DISKSIZE'      => 'capacity',
                                                        'INTERFACE'     => 'interfacetypes_id',
                                                        'MANUFACTURER'  => 'manufacturers_id',
                                                        'MODEL'         => 'designation',
                                                        'SERIALNUMBER'  => 'serial'));
                     if ($array_tmp['designation'] == '') {
                        if (isset($a_storage['NAME'])) {
                           $array_tmp['designation'] = $a_storage['NAME'];
                        } else if (isset($a_storage['DESIGNATION'])) {
                           $array_tmp['designation'] = $a_storage['DESIGNATION'];
                        }
                     }
                     $a_inventory['harddrive'][] = $array_tmp;
                  }
               }
            }            
         }
      }
      
      
      
      
      // * USERS
      if (isset($array['USERS'])) {
         foreach ($array['USERS'] as $a_users) {
            $array_tmp = $thisc->addValues($a_users, 
                                           array( 
                                              'LOGIN'  => 'login', 
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
      }
     
      // * VIRTUALMACHINES
      $a_inventory['virtualmachine'] = array();
      if ($pfConfig->getValue('import_vm') == 1) {
         if (isset($array['VIRTUALMACHINES'])) {
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
      }
      
      // * ANTIVIRUS
      $a_inventory['antivirus'] = array();
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
      // * STORAGE/VOLUMES
      $a_inventory['storage'] = array();
      if (isset($array['PHYSICAL_VOLUMES'])) {
         foreach ($array['PHYSICAL_VOLUMES'] as $a_physicalvolumes) {
            $array_tmp = $thisc->addValues($a_physicalvolumes, 
                                           array( 
                                              'DEVICE'   => 'name', 
                                              'PV_UUID'  => 'uuid', 
                                              'VG_UUID'  => 'uuid_link',
                                              'SIZE'     => 'totalsize',
                                              'FREE'     => 'freesize')); 
            $array_tmp['plugin_fusioninventory_inventorycomputerstoragetypes_id'] = 
                  'partition';
            $a_inventory['storage'][] = $array_tmp;
         }
      }
      if (isset($array['STORAGES'])) {
         foreach ($array['STORAGES']  as $a_storage) {
            $type_tmp = PluginFusioninventoryFormatconvert::getTypeDrive($a_storage);
            if ($type_tmp != "Drive") {
               if (isset($a_storage['NAME'])
                       AND $a_storage['NAME'] != '') {
                  $detectsize = 0;
                  $array_tmp = array();
                  
                  foreach ($a_inventory['storage'] as $a_physicalvol) {
                     if (preg_match("/^\/dev\/".$a_storage['NAME']."/", $a_physicalvol['name'])) {
                        $array_tmp['name'] = $a_storage['NAME'];
                        if (isset($a_storage['SERIALNUMBER'])) {
                           $array_tmp['uuid'] = $a_storage['SERIALNUMBER'];
                        } else {
                           $array_tmp['uuid'] = $a_storage['NAME'];
                        }
                        $array_tmp['plugin_fusioninventory_inventorycomputerstoragetypes_id'] = 
                           'hard disk';
                        if (!isset($array_tmp['uuid_link'])) {
                           $array_tmp['uuid_link'] = array();
                        }
                        $array_tmp['uuid_link'][] = $a_physicalvol['uuid'];
                        $detectsize += $a_physicalvol['totalsize'];
                     }
                  }
                  if (isset($a_storage['DISKSIZE'])
                          && $a_storage['DISKSIZE'] != '') {
                     $array_tmp['totalsize'] = $a_storage['DISKSIZE'];
                     $array_tmp['size_dynamic'] = 0;
                  } else {
                     $array_tmp['totalsize'] = $detectsize;
                     $array_tmp['size_dynamic'] = 1;
                  }
                  $a_inventory['storage'][] = $array_tmp;
               }
            }
         }
      }

      if (isset($array['VOLUME_GROUPS'])) {
         foreach ($array['VOLUME_GROUPS'] as $a_volumegroups) {
            $array_tmp = $thisc->addValues($a_volumegroups, 
                                           array( 
                                              'VG_NAME'  => 'name', 
                                              'VG_UUID'  => 'uuid', 
                                              'SIZE'     => 'totalsize',
                                              'FREE'     => 'freesize')); 
            $array_tmp['plugin_fusioninventory_inventorycomputerstoragetypes_id'] = 
                  'volume groups';
            $a_inventory['storage'][] = $array_tmp;
         }
      }
      if (isset($array['LOGICAL_VOLUMES'])) {
         foreach ($array['LOGICAL_VOLUMES'] as $a_logicalvolumes) {
            $array_tmp = $thisc->addValues($a_logicalvolumes, 
                                           array( 
                                              'LV_NAME'  => 'name', 
                                              'LV_UUID'  => 'uuid', 
                                              'VG_UUID'  => 'uuid_link',
                                              'SIZE'     => 'totalsize')); 
            $array_tmp['plugin_fusioninventory_inventorycomputerstoragetypes_id'] = 
                  'logical volumes';
            $a_inventory['storage'][] = $array_tmp;
         }
      }
      
      if (isset($array['DRIVES'])) {
         foreach ($array['DRIVES'] as $a_drives) {
            if ((((isset($a_drives['TYPE'])
                       AND $a_drives['TYPE'] == 'Network Drive')
                        OR isset($a_drives['FILESYSTEM'])
                       AND $a_drives['FILESYSTEM'] == 'nfs'))
                OR ((isset($a_drives['TYPE'])) AND
                    (($a_drives['TYPE'] == "Removable Disk")
                   OR ($a_drives['TYPE'] == "Compact Disc")))) {

            } else if (strstr($a_drives['VOLUMN'], "/dev/mapper")){
               // LVM 
               $a_split = explode("-", $a_drives['VOLUMN']);
               $volumn = end($a_split);
               $detectsize = 0;
               $array_tmp = array();
               foreach ($a_inventory['storage'] as $num=>$a_physicalvol) {
                  if ($a_physicalvol['plugin_fusioninventory_inventorycomputerstoragetypes_id']
                          == 'logical volumes') {
                     if ($volumn == $a_physicalvol['name']) {
                        $array_tmp['name'] = $a_drives['TYPE'];
                        if (isset($a_drives['SERIAL'])) {
                           $array_tmp['uuid'] = $a_drives['SERIAL'];
                        } else {
                           $array_tmp['uuid'] = $a_drives['TYPE'];
                        }
                        $array_tmp['plugin_fusioninventory_inventorycomputerstoragetypes_id'] = 
                           'mount';
                        if (!isset($array_tmp['uuid_link'])) {
                           $array_tmp['uuid_link'] = array();
                        }
                        $array_tmp['uuid_link'][] = $a_physicalvol['uuid'];
                        $detectsize += $a_physicalvol['totalsize'];
                     }
                  }
               }      
               if (isset($array_tmp['name'])) {
                  $array_tmp['totalsize'] = $a_drives['TOTAL'];
                  $a_inventory['storage'][] = $array_tmp;
               }
               
            } else if (strstr($a_drives['VOLUMN'], "/dev/")){
               $detectsize = 0;
               $array_tmp = array();
               foreach ($a_inventory['storage'] as $num=>$a_physicalvol) {
                  $volumn = $a_drives['VOLUMN'];
                  $volumn = substr_replace($volumn ,"",-1);
                  $volumn = str_replace("/dev/", "", $volumn);
                  if ($volumn == $a_physicalvol['name']) {
                     $array_tmp['name'] = $a_drives['VOLUMN'];
                     if (isset($a_drives['SERIAL'])) {
                        $array_tmp['uuid'] = $a_drives['SERIAL'];
                     } else {
                        $array_tmp['uuid'] = $a_drives['TYPE'];
                     }
                     $array_tmp['plugin_fusioninventory_inventorycomputerstoragetypes_id'] = 
                        'partition';
                     if (!isset($array_tmp['uuid_link'])) {
                        $array_tmp['uuid_link'] = array();
                     }
                     $array_tmp['uuid_link'][] = $a_physicalvol['uuid'];
                     $detectsize += $a_physicalvol['totalsize'];
                     if ($a_physicalvol['size_dynamic'] == 1) {
                        $a_inventory['storage'][$num]['totalsize'] += $a_drives['TOTAL'];
                     }
                  }
               }
               $array_tmp['totalsize'] = $a_drives['TOTAL'];
               $a_inventory['storage'][] = $array_tmp;
               
               $array_tmp['plugin_fusioninventory_inventorycomputerstoragetypes_id'] = 
                        'mount';
               $array_tmp['name'] = $a_drives['TYPE'];
               $array_tmp['uuid_link'] = array();
               $array_tmp['uuid_link'][] = $array_tmp['uuid'];
               $array_tmp['uuid'] = $array_tmp['uuid']."-mount";
               $a_inventory['storage'][] = $array_tmp;
            }
         }
      }               
      
      return $a_inventory;
   }
   
   
   
   function computerSoftwareTransformation($a_inventory, $entities_id) {
      
      $entities_id_software = Entity::getUsedConfig('entities_id_software', $_SESSION["plugin_fusinvinventory_entity"], '', true);
      if ($entities_id_software < 0) {
         $entities_id_software = $_SESSION["plugin_fusinvinventory_entity"];
      }
      
      $rulecollection = new RuleDictionnarySoftwareCollection();
      foreach ($a_inventory['SOFTWARES'] as $a_softwares) {
         $array_tmp = $this->addValues($a_softwares, 
                                        array( 
                                           'PUBLISHER'   => 'manufacturers_id', 
                                           'NAME'        => 'name', 
                                           'VERSION'     => 'version'));
         if (!isset($array_tmp['name'])
                 || $array_tmp['name'] == '') {
            if (isset($a_softwares['GUID'])
                    && $a_softwares['GUID'] != '') {
               $array_tmp['name'] = $a_softwares['GUID'];
            }
         }
         
         if (!(!isset($array_tmp['name'])
                 || $array_tmp['name'] == '')) {
            if (count($array_tmp) > 0) {
               if ($array_tmp['manufacturers_id'] != '') {
                  // Replace Manufacturer::processName
                  $output = array();
                  $output = $rulecollection->processAllRules(array("name" => stripslashes($array_tmp['manufacturers_id'])),
                                                                   $output, array());
                  if (isset($output["name"])) {
                     $array_tmp['manufacturers_id'] = $output["name"];
                  }
               } else {
                  $array_tmp['manufacturers_id'] = 0;
               }

               $res_rule = $rulecollection->processAllRules(array(
                                                               "name"         => $array_tmp['name'],
                                                               "manufacturer" => $array_tmp['manufacturers_id'],
                                                               "old_version"  => $array_tmp['version'],
                                                               "entities_id"  => $entities_id_software));
               if (isset($res_rule['_ignore_ocs_import']) AND $res_rule['_ignore_ocs_import'] == "1") {

               } else {
                  if (isset($res_rule["name"])) {
                     $array_tmp['name'] = $res_rule["name"];
                  }
                  if (isset($res_rule["version"]) && $res_rule["version"]!= '') {
                     $array_tmp['version'] = $res_rule["version"];
                  }
                  if (isset($res_rule["manufacturer"])) {
                     $array_tmp['manufacturers_id'] = $res_rule["manufacturer"];
                  } else if ($array_tmp['manufacturers_id'] != ''
                          && $array_tmp['manufacturers_id'] != '0') {
                     if (!isset($this->manufacturer_cache[$array_tmp['manufacturers_id']])) {
                        $new_value = Dropdown::importExternal('Manufacturer',
                                                              $array_tmp['manufacturers_id']);
                        $this->manufacturer_cache[$array_tmp['manufacturers_id']] = $new_value;
                     }
                     $array_tmp['manufacturers_id'] = $this->manufacturer_cache[$array_tmp['manufacturers_id']];
                  } else {
                     $array_tmp['manufacturers_id'] = 0;
                  }
                  if (isset($res_rule['new_entities_id'])) {
                     $array_tmp['entities_id'] = $res_rule['new_entities_id'];
                  }
                  if (!isset($array_tmp['entities_id'])
                          || $array_tmp['entities_id'] == '') {
                     $array_tmp['entities_id'] = $entities_id_software;
                  }
                  if (!isset($array_tmp['version'])) {
                     $array_tmp['version'] = "";
                  }
                  $array_tmp['is_template_computer'] = 0;
                  $array_tmp['is_deleted_computer'] = 0;
                  if (!isset($a_inventory['software'][$array_tmp['name']."$$$$".$array_tmp['version']])) {
                     $a_inventory['software'][$array_tmp['name']."$$$$".$array_tmp['version']] = $array_tmp;
                  }
               }
            }
         }
      }
      unset($a_inventory['SOFTWARES']);
      return $a_inventory;
   }
   
   
   
   static function addValues($array, $a_key) {
      $a_return = array();
      if (!is_array($array)) {
         return $a_return;
      }
      $a_keys = array_keys($a_key);
      foreach ($array as $key=>$value) {
         if (in_array($key, $a_keys)) {
            $a_return[$a_key[$key]] = $value;
         }
      }
      foreach ($a_key as $key=>$value) {
         if (!isset($a_return[$value])) {
            $int = 0;
            switch ($value) {
               
               case 'capacity':
               case 'freesize':
               case 'totalsize':
               case 'memory':
                  $int = 1;
                  break;

            } 
             
            if ($int == 1) {
               $a_return[$value] = 0;
            } else {
               $a_return[$value] = '';
            }            
         }
      }
      return $a_return;
   }
   
   
   
   function computerReplaceids($array) {
      
      foreach ($array as $key=>$value) {
         if (!is_int($key)
                 && $key == "software") {
            return $array;
         } else {
            if (is_array($value)) {
               $array[$key] = $this->computerReplaceids($value);
            } else {
               if ($key == "manufacturers_id") {
                  $manufacturer = new Manufacturer();
                  $array[$key]  = $manufacturer->processName($value);
               } 
               if (isset($this->foreignkey_itemtype[$key])) {
                  $array[$key] = Dropdown::importExternal($this->foreignkey_itemtype[$key],
                                                          $value);
               } else if (isForeignKeyField($key)
                       && $key != "users_id") {
                  
                  $this->foreignkey_itemtype[$key] = getItemTypeForTable(getTableNameForForeignKeyField($key));
                  $array[$key] = Dropdown::importExternal($this->foreignkey_itemtype[$key],
                                                          $value);
               }
            }
         }
      }
      return $array;
   }
   
   
   /*
    * Modify switch inventory
    */
   static function networkequipmentInventoryTransformation($array) {
      
      $a_inventory = array();
      $thisc = new self();
    
      // * INFO
      $array_tmp = $thisc->addValues($array['INFO'], 
                                     array( 
                                        'NAME'         => 'name',
                                        'SERIAL'       => 'serial',
                                        'OTHERSERIAL'  => 'otherserial',
                                        'ID'           => 'id',
                                        'LOCATION'     => 'locations_id',
                                        'MODEL'        => 'networkequipmentmodels_id',
                                        'UPTIME'        => 'uptime',
                                        'MANUFACTURER' => 'manufacturers_id',
                                        'FIRMWARE'     => 'networkequipmentfirmwares_id',
                                        'CPU'          => 'cpu',
                                        'RAM'          => 'ram',
                                        'MEMORY'       => 'memory',
                                        'MAC'          => 'mac'));
      
      if (strstr($array_tmp['networkequipmentfirmwares_id'], "CW_VERSION")
              OR strstr($array_tmp['networkequipmentfirmwares_id'], "CW_INTERIM_VERSION")) {
         $explode = explode("$", $array_tmp['networkequipmentfirmwares_id']);
         if (isset($explode[1])) {
            $array_tmp['networkequipmentfirmwares_id'] = $explode[1];
         }
      }

      $a_inventory['NetworkEquipment'] = $array_tmp;
      $a_inventory['itemtype'] = 'NetworkEquipment';
      
      $array_tmp = $thisc->addValues($array['INFO'], 
                                     array( 
                                        'COMMENTS' => 'sysdescr',
                                        'UPTIME'  => 'uptime',
                                        'CPU'     => 'cpu',
                                        'MEMORY'  => 'memory'));
      $array_tmp['last_fusioninventory_update'] = date('Y-m-d H:i:s');
      $a_inventory['PluginFusioninventoryNetworkEquipment'] = $array_tmp;
      
      // * Internal ports
      if (isset($array['INFO']['IPS'])) {
         foreach ($array['INFO']['IPS']['IP'] as $IP) {
            $a_inventory['internalport'][] = $IP;
         }
      }      
      $a_inventory['internalport'] = array_unique($a_inventory['internalport']);
      
      // * PORTS
      foreach ($array['PORTS']['PORT'] as $a_port) {
         $array_tmp = $thisc->addValues($a_port, 
                                        array( 
                                           'IFNAME'   => 'name',
                                           'IFNUMBER' => 'logical_number',
                                           'MAC'      => 'mac',
                                           'IFSPEED'   => 'speed',
                                           'IFDESCR'           => 'ifdescr',
                                           'IFINERRORS'        => 'ifinerrors',
                                           'IFINOCTETS'        => 'ifinoctets',
                                           'IFINTERNALSTATUS'  => 'ifinternalstatus',
                                           'IFLASTCHANGE'      => 'iflastchange',
                                           'IFMTU'             => 'ifmtu',
                                           'IFOUTERRORS'       => 'ifouterrors',
                                           'IFSTATUS'          => 'iftatus',
                                           'IFTYPE'            => 'iftype',
                                           'TRUNK'             => 'trunk'));
         if ($array_tmp['trunk'] == '') {
            $array_tmp['trunk'] = 0;
         }
         $array_tmp['ifspeed'] = $array_tmp['speed'];
         if ($array_tmp['ifdescr'] == '') {
            $array_tmp['ifdescr'] = $array_tmp['name'];
         }
         
         $a_inventory['networkport'][$a_port['IFNUMBER']] = $array_tmp;
         
         if (isset($a_port['CONNECTIONS'])) {
            if (isset($a_port['CONNECTIONS']['CDP'])
                    && $a_port['CONNECTIONS']['CDP'] == 1) {
               
               $array_tmp = $thisc->addValues($a_port['CONNECTIONS']['CONNECTION'], 
                                              array( 
                                                 'IFDESCR'  => 'ifdescr',
                                                 'IFNUMBER' => 'logical_number',
                                                 'SYSDESCR' => 'sysdescr',
                                                 'MODEL'    => 'model',
                                                 'IP'       => 'ip',
                                                 'SYSMAC'   => 'mac',
                                                 'SYSNAME'  => 'name'));
               $a_inventory['connection-lldp'][$a_port['IFNUMBER']] = $array_tmp;
            } else {
               // MAC
               foreach ($a_port['CONNECTIONS']['CONNECTION'] as $keymac=>$mac) {
                  if ($keymac == 'MAC') {
                     $a_inventory['connection-mac'][$a_port['IFNUMBER']][] = $mac;
                  }
               }
               $a_inventory['connection-mac'][$a_port['IFNUMBER']] = array_unique($a_inventory['connection-mac'][$a_port['IFNUMBER']]);
            }
         }
         
         // VLAN
         if (isset($a_port['VLANS'])) {
            foreach ($a_port['VLANS'] as $a_vlan) {
               $array_tmp = $thisc->addValues($a_vlan, 
                                              array( 
                                                 'NAME'  => 'name',
                                                 'NUMBER' => 'tag'));
               
            }
         }
         $a_inventory['vlans'][$a_port['IFNUMBER']][$array_tmp['tag']] = $array_tmp;
      }
      return $a_inventory;
   }
   

   
   /*
    * Modify switch inventory
    */
   static function printerInventoryTransformation($array) {
      
      $a_inventory = array();
      $thisc = new self();
    
      // * INFO
      $array_tmp = $thisc->addValues($array['INFO'], 
                                     array( 
                                        'NAME'         => 'name',
                                        'SERIAL'       => 'serial',
                                        'OTHERSERIAL'  => 'otherserial',
                                        'ID'           => 'id',
                                        'MANUFACTURER' => 'manufacturers_id',
                                        'LOCATION'     => 'locations_id',
                                        'MODEL'        => 'printermodels_id'));
      
      $a_inventory['Printer'] = $array_tmp;
      $a_inventory['itemtype'] = 'Printer';
      
      $array_tmp = $thisc->addValues($array['INFO'], 
                                     array( 
                                        'COMMENTS' => 'sysdescr'));
      $array_tmp['last_fusioninventory_update'] = date('Y-m-d H:i:s');
      $a_inventory['PluginFusioninventoryPrinter'] = $array_tmp;
      
      // * PORTS
      foreach ($array['PORTS']['PORT'] as $a_port) {
         $array_tmp = $thisc->addValues($a_port, 
                                        array( 
                                           'IFNAME'   => 'name',
                                           'IFNUMBER' => 'logical_number',
                                           'MAC'      => 'mac',
                                           'IP'       => 'ip',
                                           'IFTYPE'   => 'iftype'));
         
         $a_inventory['networkport'][$a_port['IFNUMBER']] = $array_tmp;
      }
      
      // TODO
      
      // CARTRIDGES
      
      // PAGESCOUNTER
      
      return $a_inventory;
   }

   
   
   /**
   * Get type of the drive
   *
   * @param $data array of the storage
   *
   * @return "Drive" or "HardDrive" 
   *
   **/
   static function getTypeDrive($data) {
      if (((isset($data['TYPE'])) AND
              ((preg_match("/rom/i", $data["TYPE"])) OR (preg_match("/dvd/i", $data["TYPE"]))
               OR (preg_match("/blue.{0,1}ray/i", $data["TYPE"]))))
            OR
         ((isset($data['MODEL'])) AND
              ((preg_match("/rom/i", $data["MODEL"])) OR (preg_match("/dvd/i", $data["MODEL"]))
               OR (preg_match("/blue.{0,1}ray/i", $data["MODEL"]))))
            OR
         ((isset($data['NAME'])) AND
              ((preg_match("/rom/i", $data["NAME"])) OR (preg_match("/dvd/i", $data["NAME"]))
               OR (preg_match("/blue.{0,1}ray/i", $data["NAME"]))))) {
         
         return "Drive";
      } else {
         return "HardDrive";
      }
   }
}

?>