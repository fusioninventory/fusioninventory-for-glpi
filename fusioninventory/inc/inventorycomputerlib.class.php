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

class PluginFusioninventoryInventoryComputerLib extends CommonDBTM {

   var $table = "glpi_plugin_fusioninventory_inventorycomputerlibserialization";

   
   
   function updateComputer($a_computerinventory, $items_id) {
      global $DB;
      
      $computer                     = new Computer();
      $pfInventoryComputerComputer  = new PluginFusioninventoryInventoryComputerComputer();
      $item_DeviceProcessor         = new Item_DeviceProcessor();
      $deviceProcessor              = new DeviceProcessor();
      $item_DeviceMemory            = new Item_DeviceMemory();
      $deviceMemory                 = new DeviceMemory();
      $Software                     = new Software();
      $softwareVersion              = new SoftwareVersion();
      $computer_SoftwareVersion     = new Computer_SoftwareVersion();
      $computerVirtualmachine       = new ComputerVirtualMachine();
      $computerDisk                 = new ComputerDisk();
      $item_DeviceControl           = new Item_DeviceControl();
      $deviceControl                = new DeviceControl();
      $item_DeviceGraphicCard       = new Item_DeviceGraphicCard();
      $deviceGraphicCard            = new DeviceGraphicCard();
      $item_DeviceSoundCard         = new Item_DeviceSoundCard();
      $deviceSoundCard              = new DeviceSoundCard();
      $networkPort                  = new NetworkPort();
      $networkName                  = new NetworkName();
      $iPAddress                    = new IPAddress();
      $pfInventoryComputerAntivirus = new PluginFusioninventoryInventoryComputerAntivirus();
      $pfConfig                     = new PluginFusioninventoryConfig();
      
      $computer->getFromDB($items_id);
      
      $a_computerinventory = PluginFusioninventoryFormatconvert::computerReplaceids($a_computerinventory);
      
      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $items_id);
      
      // * Computer
         $db_computer = array();
         $a_field = array('name', 'operatingsystems_id', 'operatingsystemversions_id',  'os_licenseid',
                        'os_license_number', 'domains_id', 'uuid',  'comment', 'users_id', 'contact',
                        'manufacturers_id', 'computermodels_id', 'serial', 'computertypes_id');
         foreach ($a_field as $field) {
            $db_computer[$field] = $computer->fields[$field];
         }
         $a_ret = $this->checkLock($a_computerinventory['computer'], $db_computer, $a_lockable);
         $a_computerinventory['computer'] = $a_ret[0];
         $db_computer = $a_ret[1];
         $input = $this->diffArray($a_computerinventory['computer'], $db_computer);
         $input['id'] = $items_id;         
         if (isset($input['comment'])) {
            unset($input['comment']);
         }
         $computer->update($input);
         
         $input = $this->diffArray($a_computerinventory['computer'], $db_computer);
         if (isset($input['comment'])) {
            $inputcomment = array();
            $inputcomment['comment'] = $input['comment'];
            $inputcomment['id'] = $items_id; 
            $inputcomment['_no_history'] = true;
            $computer->update($input);
         }
      
      // * Computer fusion (ext)
         $db_computer = array();
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputercomputers`
             WHERE `computers_id` = '$items_id'";
         $result = $DB->query($query);         
         while ($data = $DB->fetch_assoc($result)) {            
            foreach($data as $key=>$value) {
               $data[$key] = Toolbox::addslashes_deep($value);
            }
            $db_computer = $data;
         }
         if (count($db_computer) == '0') { // Add
            $a_computerinventory['fusioninventorycomputer']['computers_id'] = $items_id;
            $pfInventoryComputerComputer->add($a_computerinventory['fusioninventorycomputer']);
         } else { // Update
            $idtmp = $db_computer['id'];
            unset($db_computer['id']);
            unset($db_computer['computers_id']);
            $a_ret = $this->checkLock($a_computerinventory['fusioninventorycomputer'], $db_computer);
            $a_computerinventory['fusioninventorycomputer'] = $a_ret[0];
            $db_computer = $a_ret[1];
            $input = $this->diffArray($a_computerinventory['fusioninventorycomputer'], $db_computer);
            $input['id'] = $idtmp;
            $pfInventoryComputerComputer->update($input);
         }
         
         
      // * Processors
         
      // * Memories
         
      // * Graphiccard
         
      // * Sound
         
      // * Controllers
         
      // * Software
         if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
              "import_software", 'inventory') != 0) {
            $a_softwares = array();
            $query = "SELECT `glpi_computers_softwareversions`.`id` as sid,
                       `glpi_softwares`.`name`,
                       `glpi_softwareversions`.`name` AS version,
                       `glpi_softwareversions`.`entities_id`,
                       `glpi_softwares`.`manufacturers_id`
                FROM `glpi_computers_softwareversions`
                LEFT JOIN `glpi_softwareversions`
                     ON (`glpi_computers_softwareversions`.`softwareversions_id`
                           = `glpi_softwareversions`.`id`)
                LEFT JOIN `glpi_softwares`
                     ON (`glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`)
                WHERE `glpi_computers_softwareversions`.`computers_id` = '$items_id'";
            $result = $DB->query($query);
            while ($db_software = $DB->fetch_assoc($result)) {
               $idtmp = $db_software['sid'];
               unset($db_software['sid']);
               if ($db_software['version'] == '') {
                  unset($db_software['version']);
               } 
               $db_software = Toolbox::addslashes_deep($db_software);
               $db_software = array_map('strtolower', $db_software);
               $a_softwares[$idtmp] = $db_software;
            }
            foreach ($a_computerinventory['software'] as $key => $arrays) {
               unset($arrays['manufacturer']);
               $arrayslower = array_map('strtolower', $arrays);
               foreach ($a_softwares as $keydb => $arraydb) {
                  if ($arrayslower == $arraydb) {
                     unset($a_computerinventory['software'][$key]);
                     unset($a_softwares[$keydb]);
                     break;
                  }
               }
            }
            if (count($a_computerinventory['software']) == 0
               AND count($a_softwares) == 0) {
               // Nothing to do
            } else {
               if (count($a_softwares) != 0) {
                  // Delete softwares in DB
                  foreach ($a_softwares as $idtmp => $data) {
                     $computer_SoftwareVersion->delete(array('id'=>$idtmp));
                  }
               }
               if (count($a_computerinventory['software']) != 0) {
                  foreach($a_computerinventory['software'] as $a_software) {
                     $softwares_id = $Software->addOrRestoreFromTrash($a_software['name'],
                                                                     $a_software['manufacturer'],
                                                                     $a_software['entities_id']);
                     $a_software['softwares_id'] = $softwares_id;
                     $a_software['name'] = $a_software['version'];
                     $softwareversions_id = $softwareVersion->add($a_software);
                     $a_software['computers_id'] = $items_id;
                     $a_software['softwareversions_id'] = $softwareversions_id;
                     $computer_SoftwareVersion->add($a_software);
                  }
               }
            }
         }
         
      // * Virtualmachines
         $db_computervirtualmachine = array();
         $query = "SELECT `id`, `name`, `uuid`, `virtualmachinesystems_id` FROM `glpi_computervirtualmachines`
             WHERE `computers_id` = '$items_id'";
         $result = $DB->query($query);         
         while ($data = $DB->fetch_assoc($result)) {
            $idtmp = $data['id'];
            unset($data['id']);            
            $data = Toolbox::addslashes_deep($data);
            $data = array_map('strtolower', $data);
            $db_computervirtualmachine[$idtmp] = $data;
         }
         $simplecomputervirtualmachine = array();
         foreach ($a_computerinventory['virtualmachine'] as $key=>$a_computervirtualmachine) {
            $a_field = array('name', 'uuid', 'virtualmachinesystems_id');
            foreach ($a_field as $field) {
               if (isset($a_computervirtualmachine[$field])) {
                  $simplecomputervirtualmachine[$key][$field] = $a_computervirtualmachine[$field];
               }
            }            
         }
         foreach ($simplecomputervirtualmachine as $key => $arrays) {
            $arrayslower = array_map('strtolower', $arrays);
            foreach ($db_computervirtualmachine as $keydb => $arraydb) {
               if ($arrayslower == $arraydb) {
                  $input = array();
                  $input['id'] = $keydb;
                  if (isset($a_computerinventory['virtualmachine'][$key]['vcpu'])) {
                     $input['vcpu'] = $a_computerinventory['virtualmachine'][$key]['vcpu'];
                  }
                  if (isset($a_computerinventory['virtualmachine'][$key]['ram'])) {
                     $input['ram'] = $a_computerinventory['virtualmachine'][$key]['ram'];
                  }
                  if (isset($a_computerinventory['virtualmachine'][$key]['virtualmachinetypes_id'])) {
                     $input['virtualmachinetypes_id'] = $a_computerinventory['virtualmachine'][$key]['virtualmachinetypes_id'];
                  }
                  if (isset($a_computerinventory['virtualmachine'][$key]['virtualmachinestates_id'])) {
                     $input['virtualmachinestates_id'] = $a_computerinventory['virtualmachine'][$key]['virtualmachinestates_id'];
                  }
                  $computerVirtualmachine->update($input);
                  unset($simplecomputervirtualmachine[$key]);
                  unset($a_computerinventory['virtualmachine'][$key]);
                  unset($db_computervirtualmachine[$keydb]);
                  break;
               }
            }
         }
         if (count($a_computerinventory['virtualmachine']) == 0
            AND count($db_computervirtualmachine) == 0) {
            // Nothing to do
         } else {
            if (count($db_computervirtualmachine) != 0) {
               // Delete softwares in DB
               foreach ($db_computervirtualmachine as $idtmp => $data) {
                  $computerVirtualmachine->delete(array('id'=>$idtmp));
               }
            }
            if (count($a_computerinventory['virtualmachine']) != 0) {
               foreach($a_computerinventory['virtualmachine'] as $a_virtualmachine) {
                  $a_virtualmachine['computers_id'] = $items_id;
                  $computerVirtualmachine->add($a_virtualmachine);
               }
            }
         }
         
      // * ComputerDisk
         $db_computerdisk = array();
         $query = "SELECT `id`, `name`, `device`, `mountpoint` FROM `glpi_computerdisks`
             WHERE `computers_id` = '$items_id'";
         $result = $DB->query($query);         
         while ($data = $DB->fetch_assoc($result)) {
            $idtmp = $data['id'];
            unset($data['id']);            
            $data = Toolbox::addslashes_deep($data);
            $data = array_map('strtolower', $data);
            $db_computerdisk[$idtmp] = $data;
         }
         $simplecomputerdisk = array();
         foreach ($a_computerinventory['computerdisk'] as $key=>$a_computerdisk) {
            $a_field = array('name', 'device', 'mountpoint');
            foreach ($a_field as $field) {
               if (isset($a_computerdisk[$field])) {
                  $simplecomputerdisk[$key][$field] = $a_computerdisk[$field];
               }
            }            
         }
         foreach ($simplecomputerdisk as $key => $arrays) {
            $arrayslower = array_map('strtolower', $arrays);
            foreach ($db_computerdisk as $keydb => $arraydb) {
               if ($arrayslower == $arraydb) {
                  $input = array();
                  $input['id'] = $keydb;
                  if (isset($a_computerinventory['computerdisk'][$key]['filesystems_id'])) {
                     $input['filesystems_id'] = $a_computerinventory['computerdisk'][$key]['filesystems_id'];
                  }
                  $input['totalsize'] = $a_computerinventory['computerdisk'][$key]['totalsize'];                  
                  $input['freesize'] = $a_computerinventory['computerdisk'][$key]['freesize'];
                  $computerDisk->update($input);
                  unset($simplecomputerdisk[$key]);
                  unset($a_computerinventory['computerdisk'][$key]);
                  unset($db_computerdisk[$keydb]);
                  break;
               }
            }
         }
         if (count($a_computerinventory['computerdisk']) == 0
            AND count($db_computerdisk) == 0) {
            // Nothing to do
         } else {
            if (count($db_computerdisk) != 0) {
               // Delete softwares in DB
               foreach ($db_computerdisk as $idtmp => $data) {
                  $computerDisk->delete(array('id'=>$idtmp));
               }
            }
            if (count($a_computerinventory['computerdisk']) != 0) {
               foreach($a_computerinventory['computerdisk'] as $a_computerdisk) {
                  $a_computerdisk['computers_id'] = $items_id;
                  $computerDisk->add($a_computerdisk);
               }
            }
         }
         
     
      // * Networkports
         $db_networkport = array();
         $query = "SELECT `id`, `name`, `mac`, `instantiation_type` FROM `glpi_networkports`
             WHERE `items_id` = '$items_id'
               AND `itemtype`='Computer'";
         $result = $DB->query($query);         
         while ($data = $DB->fetch_assoc($result)) {
            $idtmp = $data['id'];
            unset($data['id']);
            if (is_null($data['mac'])) {
               unset($data['mac']);
            }
            $data = Toolbox::addslashes_deep($data);
            $data = array_map('strtolower', $data);
            $db_networkport[$idtmp] = $data;
         }
         $simplenetworkport = array();
         foreach ($a_computerinventory['networkport'] as $key=>$a_networkport) {
            $a_field = array('name', 'mac', 'instantiation_type');
            foreach ($a_field as $field) {
               if (isset($a_networkport[$field])) {
                  $simplenetworkport[$key][$field] = $a_networkport[$field];
               }
            }            
         }
         foreach ($simplenetworkport as $key => $arrays) {
            $arrayslower = array_map('strtolower', $arrays);
            foreach ($db_networkport as $keydb => $arraydb) {
               if ($arrayslower == $arraydb) {
                  // Get networkname
                  $a_networknames_find = current($networkName->find("`items_id`='".$keydb."'
                                                             AND `itemtype`='NetworkPort'", "", 1));
                  
                  // Same networkport, verify ipaddresses
                  $db_addresses = array();
                  $query = "SELECT `id`, `name` FROM `glpi_ipaddresses`
                      WHERE `items_id` = '".$a_networknames_find['id']."'
                        AND `itemtype`='NetworkName'";
                  $result = $DB->query($query);         
                  while ($data = $DB->fetch_assoc($result)) {
                     $db_addresses[$data['id']] = $data['name'];
                  }
                  $a_computerinventory_ipaddress = $a_computerinventory['networkport'][$key]['ipaddress'];
                  foreach ($a_computerinventory_ipaddress as $key2 => $arrays2) {
                     foreach ($db_addresses as $keydb2 => $arraydb2) {
                        if ($arrays2 == $arraydb2) {
                           unset($a_computerinventory_ipaddress[$key2]);
                           unset($db_addresses[$keydb2]);
                           break;
                        }
                     }
                  }
                  if (count($a_computerinventory_ipaddress) == 0
                     AND count($db_addresses) == 0) {
                     // Nothing to do
                  } else {
                     if (count($db_addresses) != 0) {
                        // Delete softwares in DB                     
                        foreach ($db_addresses as $idtmp => $name) {
                           $iPAddress->delete(array('id'=>$idtmp));
                        }
                     }
                     if (count($a_computerinventory_ipaddress) != 0) {
                        foreach ($a_computerinventory_ipaddress as $ip) {
                           $input = array();
                           $input['items_id'] = $a_networknames_find['id'];
                           $input['itemtype'] = 'NetworkName';
                           $input['name'] = $ip;
                           $iPAddress->add($input);
                        }
                     }
                  }
                  
                  unset($db_networkport[$keydb]);
                  unset($simplenetworkport[$key]);
                  unset($a_computerinventory['networkport'][$key]);
                  break;
               }
            }
         }
         
         if (count($a_computerinventory['networkport']) == 0
            AND count($db_networkport) == 0) {
            // Nothing to do
         } else {
            if (count($db_networkport) != 0) {
               // Delete softwares in DB
               foreach ($db_networkport as $idtmp => $data) {
                  $networkPort->delete(array('id'=>$idtmp));
               }
            }
            if (count($a_computerinventory['networkport']) != 0) {
               foreach ($a_computerinventory['networkport'] as $a_networkport) {
                  $a_networkport['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
                  $a_networkport['items_id'] = $items_id;
                  $a_networkport['itemtype'] = "Computer";
                  $a_networkport['items_id'] = $networkPort->add($a_networkport);
                  $a_networkport['is_recursive'] = 0;
                  $a_networkport['itemtype'] = 'NetworkPort';
                  $a_networknames_id = $networkName->add($a_networkport);
                  foreach ($a_networkport['ipaddress'] as $ip) {
                     $input = array();
                     $input['items_id'] = $a_networknames_id;
                     $input['itemtype'] = 'NetworkName';
                     $input['name'] = $ip;
                     $iPAddress->add($input);
                  }
               }
            }
         }   
         
         
      // * Antivirus
         $db_antivirus = array();
         $query = "SELECT `id`, `name`, `version` FROM `glpi_plugin_fusioninventory_inventorycomputerantiviruses`
             WHERE `computers_id` = '$items_id'";
         $result = $DB->query($query);         
         while ($data = $DB->fetch_assoc($result)) {
            $idtmp = $data['id'];
            unset($data['id']);            
            $data = Toolbox::addslashes_deep($data);
            $data = array_map('strtolower', $data);
            $db_antivirus[$idtmp] = $data;
         }
         $simpleantivirus = array();
         foreach ($a_computerinventory['antivirus'] as $key=>$a_antivirus) {
            $a_field = array('name', 'version');
            foreach ($a_field as $field) {
               if (isset($a_antivirus[$field])) {
                  $simpleantivirus[$key][$field] = $a_antivirus[$field];
               }
            }            
         }
         foreach ($simpleantivirus as $key => $arrays) {
            $arrayslower = array_map('strtolower', $arrays);
            foreach ($db_antivirus as $keydb => $arraydb) {
               if ($arrayslower == $arraydb) {
                  $input = array();
                  $input = $a_computerinventory['virtualmachine'][$key];
                  $input['id'] = $keydb;
                  $pfInventoryComputerAntivirus->update($input);
                  unset($simpleantivirus[$key]);
                  unset($a_computerinventory['antivirus'][$key]);
                  unset($db_antivirus[$keydb]);
                  break;
               }
            }
         }
         if (count($a_computerinventory['antivirus']) == 0
            AND count($db_antivirus) == 0) {
            // Nothing to do
         } else {
            if (count($db_antivirus) != 0) {
               foreach ($db_antivirus as $idtmp => $data) {
                  $pfInventoryComputerAntivirus->delete(array('id'=>$idtmp));
               }
            }
            if (count($a_computerinventory['antivirus']) != 0) {
               foreach($a_computerinventory['antivirus'] as $a_antivirus) {
                  $a_antivirus['computers_id'] = $items_id;
                  $pfInventoryComputerAntivirus->add($a_antivirus);
               }
            }
         }
      
   }
   
   
   
   static function checkLock($datainventory, $db_computer, $a_lockable=array()) {
      foreach($a_lockable as $field) {
         if (isset($datainventory[$field])) {
            unset($datainventory[$field]);
         }
         if (isset($db_computer[$field])) {
            unset($db_computer[$field]);
         }
      }
      return array($datainventory, $db_computer);
   }
   
   
   
   function diffArray($array1, $array2) {

      $a_return = array();
      foreach ($array1 as $key=>$value) {
         $key2 = false;
         $key2 = array_search($value, $array2, true);
         if ($key2) {
            unset($array2[$key2]);
         } else {
            $a_return[$key] = $value;
         }
      }
      return $a_return;
   }
}

?>