<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
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
   var $softList = array();
   var $softVersionList = array();
   var $log_add = array();


   function __construct() {
      $this->software                  = new Software();
      $this->softwareVersion           = new SoftwareVersion();
      $this->computer_SoftwareVersion  = new Computer_SoftwareVersion();
      $this->softcatrule               = new RuleSoftwareCategoryCollection();
      $this->computer                  = new Computer();
   }



   /**
    * Update computer data
    *
    * @global type $DB
    *
    * @param php array $a_computerinventory all data from the agent
    * @param integer $computers_id id of the computer
    * @param boolean $no_history set tru if not want history
    *
    * @return nothing
    */
   function updateComputer($a_computerinventory, $computers_id, $no_history, $setdynamic=0) {
      global $DB, $CFG_GLPI;

      $computer                     = new Computer();
      $pfInventoryComputerComputer  = new PluginFusioninventoryInventoryComputerComputer();
      $item_DeviceProcessor         = new Item_DeviceProcessor();
      $deviceProcessor              = new DeviceProcessor();
      $item_DeviceMemory            = new Item_DeviceMemory();
      $deviceMemory                 = new DeviceMemory();
      $computerVirtualmachine       = new ComputerVirtualMachine();
      $computerDisk                 = new ComputerDisk();
      $item_DeviceControl           = new Item_DeviceControl();
      $item_DeviceHardDrive         = new Item_DeviceHardDrive();
      $item_DeviceGraphicCard       = new Item_DeviceGraphicCard();
      $item_DeviceSoundCard         = new Item_DeviceSoundCard();
      $networkPort                  = new NetworkPort();
      $networkName                  = new NetworkName();
      $iPAddress                    = new IPAddress();
      $ipnetwork                    = new IPNetwork();
      $pfInventoryComputerAntivirus = new PluginFusioninventoryInventoryComputerAntivirus();
      $pfConfig                     = new PluginFusioninventoryConfig();
      $pfComputerLicenseInfo        = new PluginFusioninventoryComputerLicenseInfo();

//      $pfInventoryComputerStorage   = new PluginFusioninventoryInventoryComputerStorage();
//      $pfInventoryComputerStorage_Storage =
//             new PluginFusioninventoryInventoryComputerStorage_Storage();

      $computer->getFromDB($computers_id);

      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $computers_id);

      // * Computer
         $db_computer = array();
         $db_computer = $computer->fields;
         $computerName = $a_computerinventory['Computer']['name'];
         $a_ret = PluginFusioninventoryToolbox::checkLock($a_computerinventory['Computer'],
                                                          $db_computer, $a_lockable);
         $a_computerinventory['Computer'] = $a_ret[0];

         $input = $a_computerinventory['Computer'];

         $input['id'] = $computers_id;
         $history = TRUE;
         if ($no_history) {
            $history = FALSE;
         }

         $computer->update($input, $history);

      $this->computer = $computer;

      // * Computer fusion (ext)
         $db_computer = array();
         if ($no_history === FALSE) {
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputercomputers`
                WHERE `computers_id` = '$computers_id'
                LIMIT 1";
            $result = $DB->query($query);
            while ($data = $DB->fetch_assoc($result)) {
               foreach($data as $key=>$value) {
                  $data[$key] = Toolbox::addslashes_deep($value);
               }
               $db_computer = $data;
            }
         }
         if (count($db_computer) == '0') { // Add
            $a_computerinventory['fusioninventorycomputer']['computers_id'] = $computers_id;
            $pfInventoryComputerComputer->add($a_computerinventory['fusioninventorycomputer'],
                                              array(), FALSE);
         } else { // Update
            if (!empty($db_computer['serialized_inventory'])) {
               $setdynamic = 0;
            }
            $idtmp = $db_computer['id'];
            unset($db_computer['id']);
            unset($db_computer['computers_id']);
            $a_ret = PluginFusioninventoryToolbox::checkLock(
                                          $a_computerinventory['fusioninventorycomputer'],
                                          $db_computer);
            $a_computerinventory['fusioninventorycomputer'] = $a_ret[0];
            $db_computer = $a_ret[1];
            $input = $a_computerinventory['fusioninventorycomputer'];
            $input['id'] = $idtmp;
            $input['_no_history'] = $no_history;
            $pfInventoryComputerComputer->update($input);
         }

      // Put all link item dynamic (in case of update computer not yet inventoried with fusion)
         if ($setdynamic == 1) {
            $this->setDynamicLinkItems($computers_id);
         }

      // * Processors
         if ($pfConfig->getValue("component_processor") != 0) {
            $db_processors = array();
            if ($no_history === FALSE) {
               $query = "SELECT `glpi_items_deviceprocessors`.`id`, `designation`,
                     `frequency`, `frequence`, `frequency_default`,
                     `serial`, `manufacturers_id`
                  FROM `glpi_items_deviceprocessors`
                  LEFT JOIN `glpi_deviceprocessors`
                     ON `deviceprocessors_id`=`glpi_deviceprocessors`.`id`
                  WHERE `items_id` = '$computers_id'
                     AND `itemtype`='Computer'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while (($data = $DB->fetch_assoc($result))) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  $db_processors[$idtmp] = Toolbox::addslashes_deep($data);
               }
            }
            if (count($db_processors) == 0) {
               foreach ($a_computerinventory['processor'] as $a_processor) {
                  $this->addProcessor($a_processor, $computers_id, $no_history);
               }
            } else {

               // Check all fields from source: 'designation', 'serial', 'manufacturers_id',
               // 'frequence'
               foreach ($a_computerinventory['processor'] as $key => $arrays) {
                  $frequence = $arrays['frequence'];
                  unset($arrays['frequence']);
                  unset($arrays['frequency']);
                  unset($arrays['frequency_default']);
                  foreach ($db_processors as $keydb => $arraydb) {
                     $frequencedb = $arraydb['frequence'];
                     unset($arraydb['frequence']);
                     unset($arraydb['frequency']);
                     unset($arraydb['frequency_default']);
                     if ($arrays == $arraydb) {
                        $a_criteria = $deviceProcessor->getImportCriteria();
                        $criteriafrequence = $a_criteria['frequence'];
                        $compare = explode(':', $criteriafrequence);
                        if ($frequence > ($frequencedb - $compare[1])
                                && $frequence < ($frequencedb + $compare[1])) {
                           unset($a_computerinventory['processor'][$key]);
                           unset($db_processors[$keydb]);
                           break;
                        }
                     }
                  }
               }

               if (count($a_computerinventory['processor']) == 0
                  AND count($db_processors) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_processors) != 0) {
                     // Delete processor in DB
                     foreach ($db_processors as $idtmp => $data) {
                        $item_DeviceProcessor->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['processor']) != 0) {
                     foreach($a_computerinventory['processor'] as $a_processor) {
                        $this->addProcessor($a_processor, $computers_id, $no_history);
                     }
                  }
               }
            }
         }

      // * Memories
         if ($pfConfig->getValue("component_memory") != 0) {
            $db_memories = array();
            if ($no_history === FALSE) {
               $query = "SELECT `glpi_items_devicememories`.`id`, `designation`, `size`,
                     `frequence`, `serial`, `devicememorytypes_id` FROM `glpi_items_devicememories`
                  LEFT JOIN `glpi_devicememories` ON `devicememories_id`=`glpi_devicememories`.`id`
                  WHERE `items_id` = '$computers_id'
                     AND `itemtype`='Computer'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  $data1 = Toolbox::addslashes_deep($data);
                  $db_memories[$idtmp] = $data1;
               }
            }

            if (count($db_memories) == 0) {
               foreach ($a_computerinventory['memory'] as $a_memory) {
                  $this->addMemory($a_memory, $computers_id, $no_history);
               }
            } else {
               // Check all fields from source: 'designation', 'serial', 'size',
               // 'devicememorytypes_id', 'frequence'
               foreach ($a_computerinventory['memory'] as $key => $arrays) {
                  $frequence = $arrays['frequence'];
                  unset($arrays['frequence']);
                  foreach ($db_memories as $keydb => $arraydb) {
                     $frequencedb = $arraydb['frequence'];
                     unset($arraydb['frequence']);
                     if ($arrays == $arraydb) {
                        $a_criteria = $deviceMemory->getImportCriteria();
                        $criteriafrequence = $a_criteria['frequence'];
                        $compare = explode(':', $criteriafrequence);
                        if ($frequence > ($frequencedb - $compare[1])
                                && $frequence < ($frequencedb + $compare[1])) {
                           unset($a_computerinventory['memory'][$key]);
                           unset($db_memories[$keydb]);
                           break;
                        }
                     }
                  }
               }

               if (count($a_computerinventory['memory']) == 0
                  AND count($db_memories) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_memories) != 0) {
                     // Delete memory in DB
                     foreach ($db_memories as $idtmp => $data) {
                        $item_DeviceMemory->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['memory']) != 0) {
                     foreach($a_computerinventory['memory'] as $a_memory) {
                        $this->addMemory($a_memory, $computers_id, $no_history);
                     }
                  }
               }
            }
         }

      // * Hard drive
         if ($pfConfig->getValue("component_harddrive") != 0) {
            $db_harddrives = array();
            if ($no_history === FALSE) {
               $query = "SELECT `glpi_items_deviceharddrives`.`id`, `serial`
                     FROM `glpi_items_deviceharddrives`
                  WHERE `items_id` = '$computers_id'
                     AND `itemtype`='Computer'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  $data1 = Toolbox::addslashes_deep($data);
                  $data2 = array_map('strtolower', $data1);
                  $db_harddrives[$idtmp] = $data2;
               }
            }

            if (count($db_harddrives) == 0) {
               foreach ($a_computerinventory['harddrive'] as $a_harddrive) {
                  $this->addHardDisk($a_harddrive, $computers_id, $no_history);
               }
            } else {
               foreach ($a_computerinventory['harddrive'] as $key => $arrays) {
                  $arrayslower = array_map('strtolower', $arrays);
                  foreach ($db_harddrives as $keydb => $arraydb) {
                     if ($arrayslower['serial'] == $arraydb['serial']) {
                        unset($a_computerinventory['harddrive'][$key]);
                        unset($db_harddrives[$keydb]);
                        break;
                     }
                  }
               }

               if (count($a_computerinventory['harddrive']) == 0
                  AND count($db_harddrives) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_harddrives) != 0) {
                     // Delete hard drive in DB
                     foreach ($db_harddrives as $idtmp => $data) {
                        $item_DeviceHardDrive->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['harddrive']) != 0) {
                     foreach($a_computerinventory['harddrive'] as $a_harddrive) {
                        $this->addHardDisk($a_harddrive, $computers_id, $no_history);
                     }
                  }
               }
            }
         }


      // * Graphiccard
         if ($pfConfig->getValue("component_graphiccard") != 0) {
            $db_graphiccards = array();
            if ($no_history === FALSE) {
               $query = "SELECT `glpi_items_devicegraphiccards`.`id`, `designation`, `memory`
                     FROM `glpi_items_devicegraphiccards`
                  LEFT JOIN `glpi_devicegraphiccards`
                     ON `devicegraphiccards_id`=`glpi_devicegraphiccards`.`id`
                  WHERE `items_id` = '$computers_id'
                     AND `itemtype`='Computer'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $data['designation'])) {
                     $data['designation'] = Toolbox::addslashes_deep($data['designation']);
                  }
                  $data['designation'] = trim(strtolower($data['designation']));
                  $db_graphiccards[$idtmp] = $data;
               }
            }

            if (count($db_graphiccards) == 0) {
               foreach ($a_computerinventory['graphiccard'] as $a_graphiccard) {
                  $this->addGraphicCard($a_graphiccard, $computers_id, $no_history);
               }
            } else {
               // Check all fields from source: 'designation', 'memory'
               foreach ($a_computerinventory['graphiccard'] as $key => $arrays) {
                  $arrays['designation'] = strtolower($arrays['designation']);
                  foreach ($db_graphiccards as $keydb => $arraydb) {
                     if ($arrays == $arraydb) {
                        unset($a_computerinventory['graphiccard'][$key]);
                        unset($db_graphiccards[$keydb]);
                        break;
                     }
                  }
               }

               if (count($a_computerinventory['graphiccard']) == 0
                  AND count($db_graphiccards) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_graphiccards) != 0) {
                     // Delete graphiccard in DB
                     foreach ($db_graphiccards as $idtmp => $data) {
                        $item_DeviceGraphicCard->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['graphiccard']) != 0) {
                     foreach($a_computerinventory['graphiccard'] as $a_graphiccard) {
                        $this->addGraphicCard($a_graphiccard, $computers_id, $no_history);
                     }
                  }
               }
            }
         }


      // * Sound
         if ($pfConfig->getValue("component_soundcard") != 0) {
            $db_soundcards = array();
            if ($no_history === FALSE) {
               $query = "SELECT `glpi_items_devicesoundcards`.`id`, `designation`, `comment`,
                     `manufacturers_id` FROM `glpi_items_devicesoundcards`
                  LEFT JOIN `glpi_devicesoundcards`
                     ON `devicesoundcards_id`=`glpi_devicesoundcards`.`id`
                  WHERE `items_id` = '$computers_id'
                     AND `itemtype`='Computer'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  $data1 = Toolbox::addslashes_deep($data);
                  $db_soundcards[$idtmp] = $data1;
               }
            }

            if (count($db_soundcards) == 0) {
               foreach ($a_computerinventory['soundcard'] as $a_soundcard) {
                  $this->addSoundCard($a_soundcard, $computers_id, $no_history);
               }
            } else {
               // Check all fields from source: 'designation', 'memory', 'manufacturers_id'
               foreach ($a_computerinventory['soundcard'] as $key => $arrays) {
   //               $arrayslower = array_map('strtolower', $arrays);
                  $arrayslower = $arrays;
                  foreach ($db_soundcards as $keydb => $arraydb) {
                     if ($arrayslower == $arraydb) {
                        unset($a_computerinventory['soundcard'][$key]);
                        unset($db_soundcards[$keydb]);
                        break;
                     }
                  }
               }

               if (count($a_computerinventory['soundcard']) == 0
                  AND count($db_soundcards) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_soundcards) != 0) {
                     // Delete soundcard in DB
                     foreach ($db_soundcards as $idtmp => $data) {
                        $item_DeviceSoundCard->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['soundcard']) != 0) {
                     foreach($a_computerinventory['soundcard'] as $a_soundcard) {
                        $this->addSoundCard($a_soundcard, $computers_id, $no_history);
                     }
                  }
               }
            }
         }

      // * Controllers
         if ($pfConfig->getValue("component_control") != 0) {
            $db_controls = array();
            if ($no_history === FALSE) {
               $query = "SELECT `glpi_items_devicecontrols`.`id`, `interfacetypes_id`,
                     `manufacturers_id`, `designation` FROM `glpi_items_devicecontrols`
                  LEFT JOIN `glpi_devicecontrols` ON `devicecontrols_id`=`glpi_devicecontrols`.`id`
                  WHERE `items_id` = '$computers_id'
                     AND `itemtype`='Computer'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  $data1 = Toolbox::addslashes_deep($data);
                  $data2 = array_map('strtolower', $data1);
                  $db_controls[$idtmp] = $data2;
               }
            }

            if (count($db_controls) == 0) {
               foreach ($a_computerinventory['controller'] as $a_control) {
                  $this->addControl($a_control, $computers_id, $no_history);
               }
            } else {
               // Check all fields from source:
               foreach ($a_computerinventory['controller'] as $key => $arrays) {
                  $arrayslower = array_map('strtolower', $arrays);
                  foreach ($db_controls as $keydb => $arraydb) {
                     if ($arrayslower == $arraydb) {
                        unset($a_computerinventory['controller'][$key]);
                        unset($db_controls[$keydb]);
                        break;
                     }
                  }
               }

               if (count($a_computerinventory['controller']) == 0
                  AND count($db_controls) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_controls) != 0) {
                     // Delete controller in DB
                     foreach ($db_controls as $idtmp => $data) {
                        $item_DeviceControl->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['controller']) != 0) {
                     foreach($a_computerinventory['controller'] as $a_control) {
                        $this->addControl($a_control, $computers_id, $no_history);
                     }
                  }
               }
            }
         }

      // * Software
         if ($pfConfig->getValue("import_software") != 0) {

            $entities_id = 0;
            if (count($a_computerinventory['software']) > 0) {
               $a_softfirst = current($a_computerinventory['software']);
               if (isset($a_softfirst['entities_id'])) {
                  $entities_id = $a_softfirst['entities_id'];
               }
            }
            $db_software = array();
            if ($no_history === FALSE) {
               $query = "SELECT `glpi_computers_softwareversions`.`id` as sid,
                          `glpi_softwares`.`name`,
                          `glpi_softwareversions`.`name` AS version,
                          `glpi_softwares`.`manufacturers_id`,
                          `glpi_softwareversions`.`entities_id`,
                          `glpi_computers_softwareversions`.`is_template_computer`,
                          `glpi_computers_softwareversions`.`is_deleted_computer`
                   FROM `glpi_computers_softwareversions`
                   LEFT JOIN `glpi_softwareversions`
                        ON (`glpi_computers_softwareversions`.`softwareversions_id`
                              = `glpi_softwareversions`.`id`)
                   LEFT JOIN `glpi_softwares`
                        ON (`glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`)
                   WHERE `glpi_computers_softwareversions`.`computers_id` = '$computers_id'
                     AND `glpi_computers_softwareversions`.`is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['sid'];
                  unset($data['sid']);
                  if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $data['name'])) {
                     $data['name'] = Toolbox::addslashes_deep($data['name']);
                  }
                  if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $data['version'])) {
                     $data['version'] = Toolbox::addslashes_deep($data['version']);
                  }
                  $comp_key = strtolower($data['name']).
                               "$$$$".strtolower($data['version']).
                               "$$$$".$data['manufacturers_id'].
                               "$$$$".$data['entities_id'];
                  $db_software[$comp_key] = $idtmp;
               }
            }

            $lastSoftwareid = 0;
            $lastSoftwareVid = 0;

            /*
             * Schema
             *
             * LOCK software
             * 1/ Add all software
             * RELEASE software
             *
             * LOCK softwareversion
             * 2/ Add all software versions
             * RELEASE softwareversion
             *
             * 3/ add version to computer
             *
             */

            if (count($db_software) == 0) { // there are no software associated with computer
               $nb_unicity = count(FieldUnicity::getUnicityFieldsConfig("Software", $entities_id));
               $options = array();
               if ($nb_unicity == 0) {
                  $options['disable_unicity_check'] = TRUE;
               }
               $a_softwareInventory = array();
               $a_softwareVersionInventory = array();

               $lastSoftwareid = $this->loadSoftwares($entities_id, $a_computerinventory['software'], $lastSoftwareid);
               $queryDBLOCK = "INSERT INTO `glpi_plugin_fusioninventory_dblocksoftwares`
                     SET `value`='1'";
               $CFG_GLPI["use_log_in_files"] = FALSE;
               while(!$DB->query($queryDBLOCK)) {
                  usleep(100000);
               }
               $CFG_GLPI["use_log_in_files"] = TRUE;
               $this->loadSoftwares($entities_id, $a_computerinventory['software'], $lastSoftwareid);
               foreach ($a_computerinventory['software'] as $a_software) {
                  if (!isset($this->softList[$a_software['name']."$$$$".
                           $a_software['manufacturers_id']])) {
                     $this->addSoftware($a_software,
                                        $options);
                  }
               }
               $queryDBLOCK = "DELETE FROM `glpi_plugin_fusioninventory_dblocksoftwares`
                     WHERE `value`='1'";
               $DB->query($queryDBLOCK);
               $lastSoftwareVid = $this->loadSoftwareVersions($entities_id,
                                              $a_computerinventory['software'],
                                              $lastSoftwareVid);
               $queryDBLOCK = "INSERT INTO `glpi_plugin_fusioninventory_dblocksoftwareversions`
                     SET `value`='1'";
               $CFG_GLPI["use_log_in_files"] = FALSE;
               while(!$DB->query($queryDBLOCK)) {
                  usleep(100000);
               }
               $CFG_GLPI["use_log_in_files"] = TRUE;
               $this->loadSoftwareVersions($entities_id,
                                           $a_computerinventory['software'],
                                           $lastSoftwareVid);
               foreach ($a_computerinventory['software'] as $a_software) {
                  $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                  if (!isset($this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id])) {
                     $this->addSoftwareVersion($a_software, $softwares_id);
                  }
               }
               $queryDBLOCK = "DELETE FROM `glpi_plugin_fusioninventory_dblocksoftwareversions`
                     WHERE `value`='1'";
               $DB->query($queryDBLOCK);
               $a_toinsert = array();
               foreach ($a_computerinventory['software'] as $a_software) {
                  $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                  $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id];
                  $a_tmp = array(
                      'computers_id'        => $computers_id,
                      'softwareversions_id' => $softwareversions_id,
                      'is_dynamic'          => 1,
                      'entities_id'         => $a_software['entities_id']
                      );
                  $a_toinsert[] = "('".implode("','", $a_tmp)."')";
               }
               if (count($a_toinsert) > 0) {
                  $this->addSoftwareVersionsComputer($a_toinsert);

                  if (!$no_history) {
                     foreach ($a_computerinventory['software'] as $a_software) {
                        $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                        $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id];

                        $changes[0] = '0';
                        $changes[1] = "";
                        $changes[2] = $a_software['name']." - ".
                                sprintf(__('%1$s (%2$s)'), $a_software['version'], $softwareversions_id);
                        $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                                     Log::HISTORY_INSTALL_SOFTWARE);

                        $changes[0] = '0';
                        $changes[1] = "";
                        $changes[2] = sprintf(__('%1$s (%2$s)'), $computerName, $computers_id);
                        $this->addPrepareLog($softwareversions_id, 'SoftwareVersion', 'Computer', $changes,
                                     Log::HISTORY_INSTALL_SOFTWARE);
                     }
                  }
               }

            } else {
               foreach ($a_computerinventory['software'] as $key => $arrayslower) {
                  if (isset($db_software[$key])) {
                     unset($a_computerinventory['software'][$key]);
                     unset($db_software[$key]);
                  }
               }

               if (count($a_computerinventory['software']) == 0
                  && count($db_software) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_software) > 0) {
                     // Delete softwares in DB
                     $a_delete = array();
                     foreach ($db_software as $idtmp) {
                        $this->computer_SoftwareVersion->getFromDB($idtmp);
                        $this->softwareVersion->getFromDB($this->computer_SoftwareVersion->fields['softwareversions_id']);
//                        $this->computer_SoftwareVersion->delete(array('id'=>$idtmp, '_no_history'=> TRUE), FALSE);

                        if (!$no_history) {
                           $changes[0] = '0';
                           $changes[1] = addslashes($this->computer_SoftwareVersion->getHistoryNameForItem1($this->softwareVersion, 'delete'));
                           $changes[2] = "";
                           $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                                        Log::HISTORY_UNINSTALL_SOFTWARE);

                           $changes[0] = '0';
                           $changes[1] = sprintf(__('%1$s (%2$s)'), $computerName, $computers_id);
                           $changes[2] = "";
                           $this->addPrepareLog($idtmp, 'SoftwareVersion', 'Computer', $changes,
                                        Log::HISTORY_UNINSTALL_SOFTWARE);
                        }
                     }
                     $query = "DELETE FROM `glpi_computers_softwareversions` "
                             ."WHERE `id` IN ('".implode("', '", $db_software)."')";
                     $DB->query($query);
                  }
                  if (count($a_computerinventory['software']) > 0) {
                     $nb_unicity = count(FieldUnicity::getUnicityFieldsConfig("Software",
                                                                              $entities_id));
                     $options = array();
                     if ($nb_unicity == 0) {
                        $options['disable_unicity_check'] = TRUE;
                     }
                     $lastSoftwareid = $this->loadSoftwares($entities_id, $a_computerinventory['software'], $lastSoftwareid);
                     $queryDBLOCK = "INSERT INTO `glpi_plugin_fusioninventory_dblocksoftwares`
                           SET `value`='1'";
                     $CFG_GLPI["use_log_in_files"] = FALSE;
                     while(!$DB->query($queryDBLOCK)) {
                        usleep(100000);
                     }
                     $CFG_GLPI["use_log_in_files"] = TRUE;
                     $this->loadSoftwares($entities_id, $a_computerinventory['software'], $lastSoftwareid);
                     foreach ($a_computerinventory['software'] as $a_software) {
                        if (!isset($this->softList[$a_software['name']."$$$$".
                                 $a_software['manufacturers_id']])) {
                           $this->addSoftware($a_software,
                                              $options);
                        }
                     }
                     $queryDBLOCK = "DELETE FROM `glpi_plugin_fusioninventory_dblocksoftwares`
                           WHERE `value`='1'";
                     $DB->query($queryDBLOCK);

                     $lastSoftwareVid = $this->loadSoftwareVersions($entities_id,
                                                    $a_computerinventory['software'],
                                                    $lastSoftwareVid);
                     $queryDBLOCK = "INSERT INTO `glpi_plugin_fusioninventory_dblocksoftwareversions`
                           SET `value`='1'";
                     $CFG_GLPI["use_log_in_files"] = FALSE;
                     while(!$DB->query($queryDBLOCK)) {
                        usleep(100000);
                     }
                     $CFG_GLPI["use_log_in_files"] = TRUE;
                     $this->loadSoftwareVersions($entities_id,
                                                 $a_computerinventory['software'],
                                                 $lastSoftwareVid);
                     foreach ($a_computerinventory['software'] as $a_software) {
                        $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                        if (!isset($this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id])) {
                           $this->addSoftwareVersion($a_software, $softwares_id);
                        }
                     }
                     $queryDBLOCK = "DELETE FROM `glpi_plugin_fusioninventory_dblocksoftwareversions`
                           WHERE `value`='1'";
                     $DB->query($queryDBLOCK);
                     $a_toinsert = array();
                     foreach ($a_computerinventory['software'] as $a_software) {
                        $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                        $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id];
                        $a_tmp = array(
                            'computers_id'        => $computers_id,
                            'softwareversions_id' => $softwareversions_id,
                            'is_dynamic'          => 1,
                            'entities_id'         => $a_software['entities_id']
                            );
                        $a_toinsert[] = "('".implode("','", $a_tmp)."')";
                     }
                     $this->addSoftwareVersionsComputer($a_toinsert);

                     if (!$no_history) {
                        foreach ($a_computerinventory['software'] as $a_software) {
                           $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                           $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id];

                           $changes[0] = '0';
                           $changes[1] = "";
                           $changes[2] = $a_software['name']." - ".
                                 sprintf(__('%1$s (%2$s)'), $a_software['version'], $softwareversions_id);
                           $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                                        Log::HISTORY_INSTALL_SOFTWARE);

                           $changes[0] = '0';
                           $changes[1] = "";
                           $changes[2] = sprintf(__('%1$s (%2$s)'), $computerName, $computers_id);
                           $this->addPrepareLog($softwareversions_id, 'SoftwareVersion', 'Computer', $changes,
                                        Log::HISTORY_INSTALL_SOFTWARE);
                        }
                     }
                  }
               }
            }
         }

      // * Virtualmachines
         if ($pfConfig->getValue("import_vm") == 1) {
            $db_computervirtualmachine = array();
            if ($no_history === FALSE) {
               $query = "SELECT `id`, `name`, `uuid`, `virtualmachinesystems_id`
                     FROM `glpi_computervirtualmachines`
                  WHERE `computers_id` = '$computers_id'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  $data1 = Toolbox::addslashes_deep($data);
                  $db_computervirtualmachine[$idtmp] = $data1;
               }
            }

            $simplecomputervirtualmachine = array();
            if (isset($a_computerinventory['virtualmachine'])) {
               foreach ($a_computerinventory['virtualmachine'] as $key=>$a_computervirtualmachine) {
                  $a_field = array('name', 'uuid', 'virtualmachinesystems_id');
                  foreach ($a_field as $field) {
                     if (isset($a_computervirtualmachine[$field])) {
                        $simplecomputervirtualmachine[$key][$field] =
                                    $a_computervirtualmachine[$field];
                     }
                  }
               }
            }
            foreach ($simplecomputervirtualmachine as $key => $arrays) {
               foreach ($db_computervirtualmachine as $keydb => $arraydb) {
                  if ($arrays == $arraydb) {
                     $input = array();
                     $input['id'] = $keydb;
                     if (isset($a_computerinventory['virtualmachine'][$key]['vcpu'])) {
                        $input['vcpu'] = $a_computerinventory['virtualmachine'][$key]['vcpu'];
                     }
                     if (isset($a_computerinventory['virtualmachine'][$key]['ram'])) {
                        $input['ram'] = $a_computerinventory['virtualmachine'][$key]['ram'];
                     }
                     if (isset($a_computerinventory['virtualmachine'][$key]['virtualmachinetypes_id'])) {
                        $input['virtualmachinetypes_id'] =
                             $a_computerinventory['virtualmachine'][$key]['virtualmachinetypes_id'];
                     }
                     if (isset($a_computerinventory['virtualmachine'][$key]['virtualmachinestates_id'])) {
                        $input['virtualmachinestates_id'] =
                            $a_computerinventory['virtualmachine'][$key]['virtualmachinestates_id'];
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
               && count($db_computervirtualmachine) == 0) {
               // Nothing to do
            } else {
               if (count($db_computervirtualmachine) != 0) {
                  // Delete virtualmachine in DB
                  foreach ($db_computervirtualmachine as $idtmp => $data) {
                     $computerVirtualmachine->delete(array('id'=>$idtmp), 1);
                  }
               }
               if (count($a_computerinventory['virtualmachine']) != 0) {
                  foreach($a_computerinventory['virtualmachine'] as $a_virtualmachine) {
                     $a_virtualmachine['computers_id'] = $computers_id;
                     $computerVirtualmachine->add($a_virtualmachine, array(), FALSE);
                  }
               }
            }
         }
         if ($pfConfig->getValue("create_vm") == 1) {
            // Create VM based on information of section VIRTUALMACHINE
            $pfAgent = new PluginFusioninventoryAgent();

            // Use ComputerVirtualMachine::getUUIDRestrictRequest to get existant
            // vm in computer list
            $computervm = new Computer();
            foreach ($a_computerinventory['virtualmachine_creation'] as $a_vm) {
               // Define location of physical computer (host)
               $a_vm['locations_id'] = $computer->fields['locations_id'];

               if (isset($a_vm['uuid'])
                       && $a_vm['uuid'] != '') {
                  $query = "SELECT * FROM `glpi_computers`
                     WHERE `uuid` ".ComputerVirtualMachine::getUUIDRestrictRequest($a_vm['uuid'])."
                     LIMIT 1"; // TODO: Add entity search
                  $result = $DB->query($query);
                  $computers_vm_id = 0;
                  while ($data = $DB->fetch_assoc($result)) {
                     $computers_vm_id = $data['id'];
                  }
                  if ($computers_vm_id == 0) {
                     // Add computer
                     $a_vm['entities_id'] = $computer->fields['entities_id'];
                     $computers_vm_id = $computervm->add($a_vm);
                     // Manage networks
                     $this->manageNetworkPort($a_vm['networkport'], $computers_vm_id, FALSE);
                  } else {
                     if ($pfAgent->getAgentWithComputerid($computers_vm_id) === FALSE) {
                        // Update computer
                        $a_vm['id'] = $computers_vm_id;
                        $computervm->update($a_vm);
                        // Manage networks
                        $this->manageNetworkPort($a_vm['networkport'], $computers_vm_id, FALSE);
                     }
                  }
               }
            }
         }

      // * ComputerDisk
         if ($pfConfig->getValue("import_volume") != 0) {
            $db_computerdisk = array();
            if ($no_history === FALSE) {
               $query = "SELECT `id`, `name`, `device`, `mountpoint`
                   FROM `glpi_computerdisks`
                   WHERE `computers_id` = '".$computers_id."'
                     AND `is_dynamic`='1'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $idtmp = $data['id'];
                  unset($data['id']);
                  $data1 = Toolbox::addslashes_deep($data);
                  $data2 = array_map('strtolower', $data1);
                  $db_computerdisk[$idtmp] = $data2;
               }
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
                        $input['filesystems_id'] =
                                 $a_computerinventory['computerdisk'][$key]['filesystems_id'];
                     }
                     $input['totalsize'] = $a_computerinventory['computerdisk'][$key]['totalsize'];
                     $input['freesize'] = $a_computerinventory['computerdisk'][$key]['freesize'];

                     $input['_no_history'] = TRUE;
                     $computerDisk->update($input, FALSE);
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
                  // Delete computerdisk in DB
                  foreach ($db_computerdisk as $idtmp => $data) {
                     $computerDisk->delete(array('id'=>$idtmp), 1);
                  }
               }
               if (count($a_computerinventory['computerdisk']) != 0) {
                  foreach($a_computerinventory['computerdisk'] as $a_computerdisk) {
                     $a_computerdisk['computers_id']  = $computers_id;
                     $a_computerdisk['is_dynamic']    = 1;
                     $a_computerdisk['_no_history']   = $no_history;
                     $computerDisk->add($a_computerdisk, array(), FALSE);
                  }
               }
            }
         }


      // * Networkports
         if ($pfConfig->getValue("component_networkcard") != 0) {
            // Get port from unknown device if exist
            $this->manageNetworkPort($a_computerinventory['networkport'], $computers_id, $no_history);
         }


      // * Antivirus
         $db_antivirus = array();
         if ($no_history === FALSE) {
            $query = "SELECT `id`, `name`, `version`
                  FROM `glpi_plugin_fusioninventory_inventorycomputerantiviruses`
               WHERE `computers_id` = '$computers_id'";
            $result = $DB->query($query);
            while ($data = $DB->fetch_assoc($result)) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data1 = Toolbox::addslashes_deep($data);
               $data2 = array_map('strtolower', $data1);
               $db_antivirus[$idtmp] = $data2;
            }
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
                  $input = $a_computerinventory['antivirus'][$key];
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
                  $pfInventoryComputerAntivirus->delete(array('id'=>$idtmp), 1);
               }
            }
            if (count($a_computerinventory['antivirus']) != 0) {
               foreach($a_computerinventory['antivirus'] as $a_antivirus) {
                  $a_antivirus['computers_id'] = $computers_id;
                  $pfInventoryComputerAntivirus->add($a_antivirus, array(), FALSE);
               }
            }
         }



      // * Licenseinfo
         $db_licenseinfo = array();
         if ($no_history === FALSE) {
            $query = "SELECT `id`, `name`, `fullname`, `serial`
                  FROM `glpi_plugin_fusioninventory_computerlicenseinfos`
               WHERE `computers_id` = '$computers_id'";
            $result = $DB->query($query);
            while ($data = $DB->fetch_assoc($result)) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data1 = Toolbox::addslashes_deep($data);
               $data2 = array_map('strtolower', $data1);
               $db_licenseinfo[$idtmp] = $data2;
            }
         }
         foreach ($a_computerinventory['licenseinfo'] as $key => $arrays) {
            $arrayslower = array_map('strtolower', $arrays);
            foreach ($db_licenseinfo as $keydb => $arraydb) {
               if ($arrayslower == $arraydb) {
                  unset($a_computerinventory['licenseinfo'][$key]);
                  unset($db_licenseinfo[$keydb]);
                  break;
               }
            }
         }
         if (count($a_computerinventory['licenseinfo']) == 0
            AND count($db_licenseinfo) == 0) {
            // Nothing to do
         } else {
            if (count($db_licenseinfo) != 0) {
               foreach ($db_licenseinfo as $idtmp => $data) {
                  $pfComputerLicenseInfo->delete(array('id'=>$idtmp), 1);
               }
            }
            if (count($a_computerinventory['licenseinfo']) != 0) {
               foreach($a_computerinventory['licenseinfo'] as $a_licenseinfo) {
                  $a_licenseinfo['computers_id'] = $computers_id;
                  $pfComputerLicenseInfo->add($a_licenseinfo, array(), FALSE);
               }
            }
         }



      // * Batteries
         /* Standby, see ticket http://forge.fusioninventory.org/issues/1907
         $db_batteries = array();
         if ($no_history === FALSE) {
            $query = "SELECT `id`, `name`, `serial`
                  FROM `glpi_plugin_fusioninventory_inventorycomputerbatteries`
               WHERE `computers_id` = '$computers_id'";
            $result = $DB->query($query);
            while ($data = $DB->fetch_assoc($result)) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data = Toolbox::addslashes_deep($data);
               $data = array_map('strtolower', $data);
               $db_batteries[$idtmp] = $data;
            }
         }
         $simplebatteries = array();
         foreach ($a_computerinventory['batteries'] as $key=>$a_batteries) {
            $a_field = array('name', 'serial');
            foreach ($a_field as $field) {
               if (isset($a_batteries[$field])) {
                  $simplebatteries[$key][$field] = $a_batteries[$field];
               }
            }
         }
         foreach ($simplebatteries as $key => $arrays) {
            $arrayslower = array_map('strtolower', $arrays);
            foreach ($db_batteries as $keydb => $arraydb) {
               if ($arrayslower == $arraydb) {
                  $input = array();
                  $input = $a_computerinventory['batteries'][$key];
                  $input['id'] = $keydb;
                  $pfInventoryComputerBatteries->update($input);
                  unset($simplebatteries[$key]);
                  unset($a_computerinventory['batteries'][$key]);
                  unset($db_batteries[$keydb]);
                  break;
               }
            }
         }
         if (count($a_computerinventory['batteries']) == 0
            AND count($db_batteries) == 0) {
            // Nothing to do
         } else {
            if (count($db_batteries) != 0) {
               foreach ($db_batteries as $idtmp => $data) {
                  $pfInventoryComputerBatteries->delete(array('id'=>$idtmp), 1);
               }
            }
            if (count($a_computerinventory['batteries']) != 0) {
               foreach($a_computerinventory['batteries'] as $a_batteries) {
                  $a_batteries['computers_id'] = $computers_id;
                  $pfInventoryComputerBatteries->add($a_batteries, array(), FALSE);
               }
            }
         }
*/


      $entities_id = $_SESSION["plugin_fusioninventory_entity"];
      // * Monitors
         if ($pfConfig->getValue("import_monitor") != 0) {
            $db_monitors = array();
            $computer_Item = new Computer_Item();
            if ($no_history === FALSE) {
               if ($pfConfig->getValue('import_monitor') == 1) {
                  // Global import
                  $query = "SELECT `glpi_monitors`.`name`, `glpi_monitors`.`manufacturers_id`,
                        `glpi_monitors`.`serial`,
                        `glpi_monitors`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_monitors` ON `items_id`=`glpi_monitors`.`id`
                     WHERE `itemtype`='Monitor'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['is_global'] == 0) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_monitors[$idtmp] = $data2;
                     }
                  }
               } else if ($pfConfig->getValue('import_monitor') == 2) {
                  // Unique import
                  $query = "SELECT `glpi_monitors`.`name`, `glpi_monitors`.`manufacturers_id`,
                        `glpi_monitors`.`serial`,
                        `glpi_monitors`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_monitors` ON `items_id`=`glpi_monitors`.`id`
                     WHERE `itemtype`='Monitor'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['is_global'] == 1) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_monitors[$idtmp] = $data2;
                     }
                  }
               } else if ($pfConfig->getValue('import_monitor') == 3) {
                  // Unique import on serial number
                  $query = "SELECT `glpi_monitors`.`name`, `glpi_monitors`.`manufacturers_id`,
                        `glpi_monitors`.`serial`,
                        `glpi_monitors`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_monitors` ON `items_id`=`glpi_monitors`.`id`
                     WHERE `itemtype`='Monitor'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['serial'] == ''
                             || $data['is_global'] == 1) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_monitors[$idtmp] = $data2;
                     }
                  }
               }
            }

            if (count($db_monitors) == 0) {
               foreach ($a_computerinventory['monitor'] as $a_monitor) {
                  $a_monitor['entities_id'] = $entities_id;
                  $this->addMonitor($a_monitor, $computers_id, $no_history);
               }
            } else {
               // Check all fields from source:
               foreach ($a_computerinventory['monitor'] as $key => $arrays) {
                  $arrayslower = array_map('strtolower', $arrays);
                  foreach ($db_monitors as $keydb => $arraydb) {
                     if ($arrayslower == $arraydb) {
                        unset($a_computerinventory['monitor'][$key]);
                        unset($db_monitors[$keydb]);
                        break;
                     }
                  }
               }
               if ($pfConfig->getValue('import_monitor') == 1) {
                  foreach ($a_computerinventory['monitor'] as $key => $arrays) {
                     unset($arrays['serial']);
                     $arrayslower = array_map('strtolower', $arrays);
                     foreach ($db_monitors as $keydb => $arraydb) {
                        unset($arraydb['serial']);
                        if ($arrayslower == $arraydb) {
                           unset($a_computerinventory['monitor'][$key]);
                           unset($db_monitors[$keydb]);
                           break;
                        }
                     }
                  }
               }

               if (count($a_computerinventory['monitor']) == 0
                  AND count($db_monitors) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_monitors) != 0) {
                     // Delete monitors links in DB
                     foreach ($db_monitors as $idtmp => $data) {
                        $computer_Item->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['monitor']) != 0) {
                     foreach($a_computerinventory['monitor'] as $a_monitor) {
                        $a_monitor['entities_id'] = $entities_id;
                        $this->addMonitor($a_monitor, $computers_id, $no_history);
                     }
                  }
               }
            }
         }


      // * Printers
         if ($pfConfig->getValue("import_printer") != 0) {
            $db_printers = array();
            $computer_Item = new Computer_Item();
            if ($no_history === FALSE) {
               if ($pfConfig->getValue('import_printer') == 1) {
                  // Global import
                  $query = "SELECT `glpi_printers`.`name`, `glpi_printers`.`serial`,
                        `glpi_printers`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_printers` ON `items_id`=`glpi_printers`.`id`
                     WHERE `itemtype`='Printer'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['is_global'] == '0') {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_printers[$idtmp] = $data2;
                     }
                  }
               } else if ($pfConfig->getValue('import_printer') == 2) {
                  // Unique import
                  $query = "SELECT `glpi_printers`.`name`, `glpi_printers`.`serial`,
                        `glpi_printers`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_printers` ON `items_id`=`glpi_printers`.`id`
                     WHERE `itemtype`='Printer'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['is_global'] == 1) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_printers[$idtmp] = $data2;
                     }
                  }
               } else if ($pfConfig->getValue('import_printer') == 3) {
                  // Unique import on serial number
                  $query = "SELECT `glpi_printers`.`name`, `glpi_printers`.`serial`,
                        `glpi_printers`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_printers` ON `items_id`=`glpi_printers`.`id`
                     WHERE `itemtype`='Printer'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['serial'] == ''
                             || $data['is_global'] == 1) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data = Toolbox::addslashes_deep($data);
                        $data = array_map('strtolower', $data);
                        $db_printers[$idtmp] = $data;
                     }
                  }
               }
            }

            if (count($db_printers) == 0) {
               foreach ($a_computerinventory['printer'] as $a_printer) {
                  $a_printer['entities_id'] = $entities_id;
                  $this->addPrinter($a_printer, $computers_id, $no_history);
               }
            } else {
               // Check all fields from source:
               foreach ($a_computerinventory['printer'] as $key => $arrays) {
                  unset($arrays['have_usb']);
                  $arrayslower = array_map('strtolower', $arrays);
                  foreach ($db_printers as $keydb => $arraydb) {
                     if ($arrayslower == $arraydb) {
                        unset($a_computerinventory['printer'][$key]);
                        unset($db_printers[$keydb]);
                        break;
                     }
                  }
               }
               if ($pfConfig->getValue('import_printer') == 1) {
                  foreach ($a_computerinventory['printer'] as $key => $arrays) {
                     unset($arrays['have_usb']);
                     unset($arrays['serial']);
                     $arrayslower = array_map('strtolower', $arrays);
                     foreach ($db_printers as $keydb => $arraydb) {
                        unset($arraydb['serial']);
                        if ($arrayslower == $arraydb) {
                           unset($a_computerinventory['printer'][$key]);
                           unset($db_printers[$keydb]);
                           break;
                        }
                     }
                  }
               }

               if (count($a_computerinventory['printer']) == 0
                  AND count($db_printers) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_printers) != 0) {
                     // Delete printers links in DB
                     foreach ($db_printers as $idtmp => $data) {
                        $computer_Item->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['printer']) != 0) {
                     foreach($a_computerinventory['printer'] as $a_printer) {
                        $a_printer['entities_id'] = $entities_id;
                        $this->addPrinter($a_printer, $computers_id, $no_history);
                     }
                  }
               }
            }
         }

      // * Peripheral
         if ($pfConfig->getValue("import_peripheral") != 0) {
            $db_peripherals = array();
            $computer_Item = new Computer_Item();
            if ($no_history === FALSE) {
               if ($pfConfig->getValue('import_peripheral') == 1) {
                  // Global import
                  $query = "SELECT `glpi_peripherals`.`name`, `glpi_peripherals`.`manufacturers_id`,
                        `glpi_peripherals`.`serial`,
                        `glpi_peripherals`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_peripherals` ON `items_id`=`glpi_peripherals`.`id`
                     WHERE `itemtype`='Peripheral'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['is_global'] == 0) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_peripherals[$idtmp] = $data2;
                     }
                  }
               } else if ($pfConfig->getValue('import_peripheral') == 2) {
                  // Unique import
                  $query = "SELECT `glpi_peripherals`.`name`, `glpi_peripherals`.`manufacturers_id`,
                        `glpi_peripherals`.`serial`,
                        `glpi_peripherals`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_peripherals` ON `items_id`=`glpi_peripherals`.`id`
                     WHERE `itemtype`='Peripheral'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['is_global'] == 1) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_peripherals[$idtmp] = $data2;
                     }
                  }
               } else if ($pfConfig->getValue('import_peripheral') == 3) {
                  // Unique import on serial number
                  $query = "SELECT `glpi_peripherals`.`name`, `glpi_peripherals`.`manufacturers_id`,
                        `glpi_peripherals`.`serial`,
                        `glpi_peripherals`.`is_global`, `glpi_computers_items`.`id` as link_id
                        FROM `glpi_computers_items`
                     LEFT JOIN `glpi_peripherals` ON `items_id`=`glpi_peripherals`.`id`
                     WHERE `itemtype`='Peripheral'
                        AND `computers_id`='".$computers_id."'
                        AND `entities_id`='".$entities_id."'
                        AND `glpi_computers_items`.`is_dynamic`='1'";
                  $result = $DB->query($query);
                  while ($data = $DB->fetch_assoc($result)) {
                     if ($data['serial'] == ''
                             || $data['is_global'] == 1) {
                        $computer_Item->delete(array('id' => $data['link_id']), 1);
                     } else {
                        $idtmp = $data['link_id'];
                        unset($data['link_id']);
                        unset($data['is_global']);
                        $data1 = Toolbox::addslashes_deep($data);
                        $data2 = array_map('strtolower', $data1);
                        $db_peripherals[$idtmp] = $data2;
                     }
                  }
               }
            }

            if (count($db_peripherals) == 0) {
               foreach ($a_computerinventory['peripheral'] as $a_peripheral) {
                  $a_peripheral['entities_id'] = $entities_id;
                  $this->addPeripheral($a_peripheral, $computers_id, $no_history);
               }
            } else {
               // Check all fields from source:
               foreach ($a_computerinventory['peripheral'] as $key => $arrays) {
                  $arrayslower = array_map('strtolower', $arrays);
                  foreach ($db_peripherals as $keydb => $arraydb) {
                     if ($arrayslower == $arraydb) {
                        unset($a_computerinventory['peripheral'][$key]);
                        unset($db_peripherals[$keydb]);
                        break;
                     }
                  }
               }
               if ($pfConfig->getValue('import_peripheral') == 1) {
                  foreach ($a_computerinventory['peripheral'] as $key => $arrays) {
                     unset($arrays['serial']);
                     $arrayslower = array_map('strtolower', $arrays);
                     foreach ($db_peripherals as $keydb => $arraydb) {
                        unset($arraydb['serial']);
                        if ($arrayslower == $arraydb) {
                           unset($a_computerinventory['peripheral'][$key]);
                           unset($db_peripherals[$keydb]);
                           break;
                        }
                     }
                  }
               }

               if (count($a_computerinventory['peripheral']) == 0
                  AND count($db_peripherals) == 0) {
                  // Nothing to do
               } else {
                  if (count($db_peripherals) != 0) {
                     // Delete peripherals links in DB
                     foreach ($db_peripherals as $idtmp => $data) {
                        $computer_Item->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory['peripheral']) != 0) {
                     foreach($a_computerinventory['peripheral'] as $a_peripheral) {
                        $a_peripheral['entities_id'] = $entities_id;
                        $this->addPeripheral($a_peripheral, $computers_id, $no_history);
                     }
                  }
               }
            }
         }

      // * storage
      // Manage by uuid to correspond with GLPI data
//         $db_storage = array();
//         if ($no_history === FALSE) {
//            $query = "SELECT `id`, `uuid` FROM ".
//                "`glpi_plugin_fusioninventory_inventorycomputerstorages`
//                WHERE `computers_id` = '$computers_id'";
//            $result = $DB->query($query);
//            while ($data = $DB->fetch_assoc($result)) {
//               $idtmp = $data['id'];
//               unset($data['id']);
//               $data = Toolbox::addslashes_deep($data);
//               $data = array_map('strtolower', $data);
//               $db_storage[$idtmp] = $data;
//            }
//         }
//         if (count($db_storage) == 0) {
//            $a_links = array();
//            $a_uuid  = array();
//            foreach ($a_computerinventory['storage'] as $a_storage) {
//               $a_storage['computers_id'] = $computers_id;
//               $insert_id = $pfInventoryComputerStorage->add($a_storage);
//               if (isset($a_storage['uuid'])) {
//                  $a_uuid[$a_storage['uuid']] = $insert_id;
//                  if (isset($a_storage['uuid_link'])) {
//                     if (is_array($a_storage['uuid_link'])) {
//                        $a_links[$insert_id] = $a_storage['uuid_link'];
//                     } else {
//                        $a_links[$insert_id][] = $a_storage['uuid_link'];
//                     }
//                  }
//               }
//            }
//            foreach ($a_links as $id=>$data) {
//               foreach ($data as $num=>$uuid) {
//                  $a_links[$id][$num] = $a_uuid[$uuid];
//               }
//            }
//            foreach ($a_links as $id=>$data) {
//               foreach ($data as $id2) {
//                  $input = array();
//                  $input['plugin_fusioninventory_inventorycomputerstorages_id_1'] = $id;
//                  $input['plugin_fusioninventory_inventorycomputerstorages_id_2'] = $id2;
//                  $pfInventoryComputerStorage_Storage->add($input);
//               }
//            }
//         } else {
//            // Check only field *** from source:
//
//         }

      $this->addLog();
   }



   function manageNetworkPort($inventory_networkports, $computers_id, $no_history) {
      global $DB;

      $networkPort = new NetworkPort();
      $networkName = new NetworkName();
      $iPAddress   = new IPAddress();
      $iPNetwork   = new IPNetwork();

      foreach ($inventory_networkports as $a_networkport) {
         if ($a_networkport['mac'] != '') {
            $a_networkports = $networkPort->find("`mac`='".$a_networkport['mac']."'
               AND `itemtype`='PluginFusioninventoryUnknownDevice'", "", 1);
            if (count($a_networkports) > 0) {
               $input = current($a_networkports);
               $unknowndevices_id = $input['items_id'];
               $input['itemtype'] = 'Computer';
               $input['items_id'] = $computers_id;
               $input['is_dynamic'] = 1;
               $input['name'] = $a_networkport['name'];
               $networkPort->update($input);
               $pfUnknownDevice = new PluginFusioninventoryUnknownDevice();
               $pfUnknownDevice->delete(array('id'=>$unknowndevices_id), 1);
            }
         }
      }
      // end get port from unknwon device

      $db_networkport = array();
      if ($no_history === FALSE) {
         $query = "SELECT `id`, `name`, `mac`, `instantiation_type`, `logical_number`
             FROM `glpi_networkports`
             WHERE `items_id` = '$computers_id'
               AND `itemtype`='Computer'
               AND `is_dynamic`='1'";
         $result = $DB->query($query);
         while (($data = $DB->fetch_assoc($result))) {
            $idtmp = $data['id'];
            unset($data['id']);
            if (is_null($data['mac'])) {
               $data['mac'] = '';
            }
            if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $data['name'])) {
               $data['name'] = Toolbox::addslashes_deep($data['name']);
            }
            $db_networkport[$idtmp] = array_map('strtolower', $data);
         }
      }
      $simplenetworkport = array();
      foreach ($inventory_networkports as $key=>$a_networkport) {
         // Add ipnetwork if not exist
         if (       $a_networkport['gateway'] != ''
                 && $a_networkport['netmask'] != ''
                 && $a_networkport['subnet']  != '') {

            if (countElementsInTable('glpi_ipnetworks',
                                     "`address`='".$a_networkport['subnet']."'
                                     AND `netmask`='".$a_networkport['netmask']."'
                                     AND `gateway`='".$a_networkport['gateway']."'
                                     AND `entities_id`='".$_SESSION["plugin_fusioninventory_entity"]."'") == 0) {

               $input_ipanetwork = array(
                   'name'    => $a_networkport['subnet'].'/'.
                                $a_networkport['netmask'].' - '.
                                $a_networkport['gateway'],
                   'network' => $a_networkport['subnet'].' / '.
                                $a_networkport['netmask'],
                   'gateway' => $a_networkport['gateway'],
                   'entities_id' => $_SESSION["plugin_fusioninventory_entity"]
               );
               $iPNetwork->add($input_ipanetwork);
            }
         }


         // End add ipnetwork
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
            $logical_number = $arraydb['logical_number'];
            unset($arraydb['logical_number']);
            if ($arrayslower == $arraydb) {
               if ($inventory_networkports[$key]['logical_number'] != $logical_number) {
                  $input = array();
                  $input['id'] = $keydb;
                  $input['logical_number'] = $inventory_networkports[$key]['logical_number'];
                  $networkPort->update($input);
               }

               // Get networkname
               $a_networknames_find = current($networkName->find("`items_id`='".$keydb."'
                                                    AND `itemtype`='NetworkPort'", "", 1));
               if (!isset($a_networknames_find['id'])) {
                  $a_networkport['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
                  $a_networkport['items_id'] = $computers_id;
                  $a_networkport['itemtype'] = "Computer";
                  $a_networkport['is_dynamic'] = 1;
                  $a_networkport['_no_history'] = $no_history;
                  $a_networkport['items_id'] = $keydb;
                  unset($a_networkport['_no_history']);
                  $a_networkport['is_recursive'] = 0;
                  $a_networkport['itemtype'] = 'NetworkPort';
                  unset($a_networkport['name']);
                  $a_networkport['_no_history'] = $no_history;
                  $a_networknames_id = $networkName->add($a_networkport, array(), FALSE);
                  $a_networknames_find['id'] = $a_networknames_id;
               }

               // Same networkport, verify ipaddresses
               $db_addresses = array();
               $query = "SELECT `id`, `name` FROM `glpi_ipaddresses`
                   WHERE `items_id` = '".$a_networknames_find['id']."'
                     AND `itemtype`='NetworkName'";
               $result = $DB->query($query);
               while ($data = $DB->fetch_assoc($result)) {
                  $db_addresses[$data['id']] = $data['name'];
               }
               $a_computerinventory_ipaddress =
                           $inventory_networkports[$key]['ipaddress'];
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
                     // Delete ip address in DB
                     foreach (array_keys($db_addresses) as $idtmp) {
                        $iPAddress->delete(array('id'=>$idtmp), 1);
                     }
                  }
                  if (count($a_computerinventory_ipaddress) != 0) {
                     foreach ($a_computerinventory_ipaddress as $ip) {
                        $input = array();
                        $input['items_id']   = $a_networknames_find['id'];
                        $input['itemtype']   = 'NetworkName';
                        $input['name']       = $ip;
                        $input['is_dynamic'] = 1;
                        $iPAddress->add($input, array(), FALSE);
                     }
                  }
               }

               unset($db_networkport[$keydb]);
               unset($simplenetworkport[$key]);
               unset($inventory_networkports[$key]);
               break;
            }
         }
      }

      if (count($inventory_networkports) == 0
         AND count($db_networkport) == 0) {
         // Nothing to do
      } else {
         if (count($db_networkport) != 0) {
            // Delete networkport in DB
            foreach ($db_networkport as $idtmp => $data) {
               $networkPort->delete(array('id'=>$idtmp), 1);
            }
         }
         if (count($inventory_networkports) != 0) {
            foreach ($inventory_networkports as $a_networkport) {
               $a_networkport['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
               $a_networkport['items_id'] = $computers_id;
               $a_networkport['itemtype'] = "Computer";
               $a_networkport['is_dynamic'] = 1;
               $a_networkport['_no_history'] = $no_history;
               $a_networkport['items_id'] = $networkPort->add($a_networkport, array(), FALSE);
               unset($a_networkport['_no_history']);
               $a_networkport['is_recursive'] = 0;
               $a_networkport['itemtype'] = 'NetworkPort';
               unset($a_networkport['name']);
               $a_networkport['_no_history'] = $no_history;
               $a_networknames_id = $networkName->add($a_networkport, array(), FALSE);
               foreach ($a_networkport['ipaddress'] as $ip) {
                  $input = array();
                  $input['items_id']   = $a_networknames_id;
                  $input['itemtype']   = 'NetworkName';
                  $input['name']       = $ip;
                  $input['is_dynamic'] = 1;
                  $input['_no_history'] = $no_history;
                  $iPAddress->add($input, array(), FALSE);
               }
            }
         }
      }
   }



   /**
    * Add a new processor component
    *
    * @param type $data
    * @param type $computers_id
    * @param type $no_history
    *
    * @return nothing
    */
   function addProcessor($data, $computers_id, $no_history) {
      $item_DeviceProcessor         = new Item_DeviceProcessor();
      $deviceProcessor              = new DeviceProcessor();

      $processors_id = $deviceProcessor->import($data);
      $data['deviceprocessors_id']  = $processors_id;
      $data['itemtype']             = 'Computer';
      $data['items_id']             = $computers_id;
      $data['is_dynamic']           = 1;
      $data['_no_history']          = $no_history;
      $item_DeviceProcessor->add($data, array(), FALSE);
   }



   /**
    * Add a new memory component
    *
    * @param type $data
    * @param type $computers_id
    * @param type $no_history
    *
    * @return nothing
    */
   function addMemory($data, $computers_id, $no_history) {
      $item_DeviceMemory            = new Item_DeviceMemory();
      $deviceMemory                 = new DeviceMemory();

      $memories_id = $deviceMemory->import($data);
      $data['devicememories_id'] = $memories_id;
      $data['itemtype']          = 'Computer';
      $data['items_id']          = $computers_id;
      $data['is_dynamic']        = 1;
      $data['_no_history']       = $no_history;
      $item_DeviceMemory->add($data, array(), FALSE);
   }



   /**
    * Add a new hard disk component
    *
    * @param type $data
    * @param type $computers_id
    * @param type $no_history
    *
    * @return nothing
    */
   function addHardDisk($data, $computers_id, $no_history) {
      $item_DeviceHardDrive         = new Item_DeviceHardDrive();
      $deviceHardDrive              = new DeviceHardDrive();

      $harddrives_id = $deviceHardDrive->import($data);
      $data['deviceharddrives_id']  = $harddrives_id;
      $data['itemtype']             = 'Computer';
      $data['items_id']             = $computers_id;
      $data['is_dynamic']           = 1;
      $data['_no_history']          = $no_history;
      $item_DeviceHardDrive->add($data, array(), FALSE);
   }



   /**
    * Add a new graphic card component
    *
    * @param type $data
    * @param type $computers_id
    * @param type $no_history
    *
    * @return nothing
    */
   function addGraphicCard($data, $computers_id, $no_history) {
      $item_DeviceGraphicCard       = new Item_DeviceGraphicCard();
      $deviceGraphicCard            = new DeviceGraphicCard();

      $graphiccards_id = $deviceGraphicCard->import($data);
      $data['devicegraphiccards_id']   = $graphiccards_id;
      $data['itemtype']                = 'Computer';
      $data['items_id']                = $computers_id;
      $data['is_dynamic']              = 1;
      $data['_no_history']             = $no_history;
      $item_DeviceGraphicCard->add($data, array(), FALSE);
   }



   /**
    * Add a new sound card component
    *
    * @param type $data
    * @param type $computers_id
    * @param type $no_history
    *
    * @return nothing
    */
   function addSoundCard($data, $computers_id, $no_history) {
      $item_DeviceSoundCard         = new Item_DeviceSoundCard();
      $deviceSoundCard              = new DeviceSoundCard();

      $sounds_id = $deviceSoundCard->import($data);
      $data['devicesoundcards_id']  = $sounds_id;
      $data['itemtype']             = 'Computer';
      $data['items_id']             = $computers_id;
      $data['is_dynamic']           = 1;
      $data['_no_history']          = $no_history;
      $item_DeviceSoundCard->add($data, array(), FALSE);
   }



   /**
    * Add a new controller component
    *
    * @param type $data
    * @param type $computers_id
    * @param type $no_history
    *
    * @return nothing
    */
   function addControl($data, $computers_id, $no_history) {
      $item_DeviceControl           = new Item_DeviceControl();
      $deviceControl                = new DeviceControl();

      $controllers_id = $deviceControl->import($data);
      $data['devicecontrols_id'] = $controllers_id;
      $data['itemtype']          = 'Computer';
      $data['items_id']          = $computers_id;
      $data['is_dynamic']        = 1;
      $data['_no_history']       = $no_history;
      $item_DeviceControl->add($data, array(), FALSE);
   }



   /**
    * Load software from DB are in the incomming inventory
    *
    * @global type $DB
    *
    * @param integer $entities_id entitity id
    * @param array $a_soft list of software from the agent inventory
    * @param integer $lastid last id search to not search from beginning
    *
    * @return integer last id
    */
   function loadSoftwares($entities_id, $a_soft, $lastid = 0) {
      global $DB;

      $whereid = '';
      if ($lastid > 0) {
         $whereid .= ' AND `id` > "'.$lastid.'"';
      }
      $a_softSearch = array();
      $nbSoft = 0;
      if (count($this->softList) == 0) {
         foreach ($a_soft as $a_software) {
            $a_softSearch[] = "(`name`='".$a_software['name']."' AND `manufacturers_id`='".$a_software['manufacturers_id']."')";
            $nbSoft++;
         }
      } else {
         foreach ($a_soft as $a_software) {
            if (!isset($this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']])) {
               $a_softSearch[] = "(`name`='".$a_software['name']."' AND `manufacturers_id`='".$a_software['manufacturers_id']."')";
               $nbSoft++;
            }
         }
      }
      $whereid .= " AND (".implode(" OR ", $a_softSearch).")";

      $sql = "SELECT max( id ) AS max FROM `glpi_softwares`";
      $result = $DB->query($sql);
      $data = $DB->fetch_assoc($result);
      $lastid = $data['max'];
      $whereid .= " AND `id` <= '".$lastid."'";
      if ($nbSoft == 0) {
         return $lastid;
      }

      $sql = "SELECT `id`, `name`, `manufacturers_id` FROM `glpi_softwares`
      WHERE `entities_id`='".$entities_id."'".$whereid;
      $result = $DB->query($sql);

      while ($data = $DB->fetch_assoc($result)) {
         $this->softList[Toolbox::addslashes_deep($data['name'])."$$$$".$data['manufacturers_id']] = $data['id'];
      }
      return $lastid;
   }



   /**
    * Load software versions from DB are in the incomming inventory
    *
    * @global type $DB
    *
    * @param integer $entities_id entitity id
    * @param array $a_softVersion list of software versions from the agent inventory
    * @param integer $lastid last id search to not search from beginning
    *
    * @return type
    */
   function loadSoftwareVersions($entities_id, $a_softVersion, $lastid = 0) {
      global $DB;

      $whereid = '';
      if ($lastid > 0) {
         $whereid .= ' AND `id` > "'.$lastid.'"';
      }
      $arr = array();
      $a_versions = array();
      foreach ($a_softVersion as $a_software) {
         $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
         if (!isset($this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id])) {
            $a_versions[$a_software['version']][] = $softwares_id;
         }
      }

      $nbVersions = 0;
      foreach ($a_versions as $name=>$a_softwares_id) {
         $arr[] = " (`name`='".$name."' AND `softwares_id` IN ('".  implode("', '", $a_softwares_id)."'))";
         $nbVersions++;
      }
      $whereid .= " AND ( ";
      $whereid .= implode(' OR ', $arr);
      $whereid .= " ) ";


      $sql = "SELECT max( id ) AS max FROM `glpi_softwareversions`";
      $result = $DB->query($sql);
      $data = $DB->fetch_assoc($result);
      $lastid = $data['max'];
      $whereid .= " AND `id` <= '".$lastid."'";

      if ($nbVersions == 0) {
         return $lastid;
      }

      $sql = "SELECT `id`, `name`, `softwares_id` FROM `glpi_softwareversions`
      WHERE `entities_id`='".$entities_id."'".$whereid;
      $result = $DB->query($sql);
      while ($data = $DB->fetch_assoc($result)) {
         $this->softVersionList[strtolower($data['name'])."$$$$".$data['softwares_id']] = $data['id'];
      }
      return $lastid;
   }



   /**
    * Add a new software
    *
    * @global type $DB
    *
    * @param array $a_software
    * @param array $options
    *
    * @return nothing
    */
   function addSoftware($a_software, $options) {

      $a_softwares_id = $this->software->add($a_software, $options, FALSE);
      $this->addPrepareLog($a_softwares_id, 'Software');

      $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']] = $a_softwares_id;
   }



   function addSoftwareVersion($a_software, $softwares_id) {

      $options = array();
      $options['disable_unicity_check'] = TRUE;

      $a_software['name']         = $a_software['version'];
      $a_software['softwares_id'] = $softwares_id;
      $a_software['_no_history']  = TRUE;
      $softwareversions_id = $this->softwareVersion->add($a_software, $options, FALSE);
      $this->addPrepareLog($softwareversions_id, 'SoftwareVersion');
      $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id] = $softwareversions_id;
   }


   function addSoftwareVersionsComputer($a_input) {
      global $DB;

      $query = 'INSERT INTO `glpi_computers_softwareversions` (`computers_id`,`softwareversions_id`,`is_dynamic`,`entities_id`) ';
      $query .= ' VALUES '.implode(',', $a_input);
      $DB->query($query);
   }



   function addSoftwareVersionComputer($a_software, $computers_id, $no_history, $options) {

      $options['disable_unicity_check'] = TRUE;

      $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
      $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id];

      $this->softwareVersion->getFromDB($softwareversions_id);
      $a_software['computers_id']         = $computers_id;
      $a_software['softwareversions_id']  = $softwareversions_id;
      $a_software['is_dynamic']           = 1;
      $a_software['is_template_computer'] = FALSE;
      $a_software['is_deleted_computer']  = FALSE;
      $a_software['_no_history']          = TRUE;
      $a_software['entities_id']          = $a_software['entities_id'];

      if ($this->computer_SoftwareVersion->add($a_software, $options, FALSE)) {
         if (!$no_history) {
            $changes[0] = '0';
            $changes[1] = "";
            $changes[2] = addslashes($this->computer_SoftwareVersion->getHistoryNameForItem1($this->softwareVersion, 'add'));
            $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                         Log::HISTORY_INSTALL_SOFTWARE);

            $changes[0] = '0';
            $changes[1] = "";
            $changes[2] = addslashes($this->computer_SoftwareVersion->getHistoryNameForItem2($this->computer, 'add'));
            $this->addPrepareLog($softwareversions_id, 'SoftwareVersion', 'Computer', $changes,
                         Log::HISTORY_INSTALL_SOFTWARE);
         }
      }
   }



   function addMonitor($data, $computers_id, $no_history) {
      global $DB;

      $computer_Item = new Computer_Item();
      $monitor       = new Monitor();
      $pfConfig      = new PluginFusioninventoryConfig();
      $pfEntity      = new PluginFusioninventoryEntity();

      $monitors_id = 0;
      if ($pfConfig->getValue('import_monitor') == 1) {
         $where_serial = "AND (`serial`='".$data['serial']."'
                  OR `serial`=''
                  OR `serial` IS NULL)";
         if ($data['serial'] == '') {
            $where_serial = '';
         }
         $where_manufacturer = "AND (`manufacturers_id`='".$data['manufacturers_id']."')";
         if ($data['manufacturers_id'] == 0) {
            $where_manufacturer = '';
         }
         // Global import
         $query = "SELECT `glpi_monitors`.`id` FROM `glpi_monitors`
            WHERE `name`='".$data['name']."'
               ".$where_manufacturer."
               ".$where_serial."
               AND `is_global`='1'
               AND `entities_id`='".$data['entities_id']."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $monitors_id = $db_data['id'];
         } else {
            $data['is_global'] = 1;
            $monitors_id = $monitor->add($data);
         }
      } else if ($pfConfig->getValue('import_monitor') == 2) {
         // Unique import
         $added = 0;
         $query = "SELECT `glpi_monitors`.`id` FROM `glpi_monitors`
            LEFT JOIN `glpi_computers_items` ON `items_id`=`glpi_monitors`.`id`
               AND `glpi_computers_items`.`itemtype`='Monitor'
            WHERE `name`='".$data['name']."'
               AND `manufacturers_id`='".$data['manufacturers_id']."'
               AND `serial`='".$data['serial']."'
               AND `is_global`='0'
               AND `entities_id`='".$data['entities_id']."'
               AND `glpi_computers_items`.`id` IS NULL
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $monitors_id = $db_data['id'];
         } else {
            $data['is_global'] = 0;
            $monitors_id = $monitor->add($data);
            $added = 1;
         }
      } else if ($pfConfig->getValue('import_monitor') == 3) {
         // Unique import on serial number
         $entity = "AND `entities_id`='".$data['entities_id']."'";
         if ($pfEntity->getValue('transfers_id_auto', $data['entities_id']) > 0) {
            $entity = '';
         }
         $added = 0;
         $query = "SELECT `glpi_monitors`.`id`, `glpi_monitors`.`entities_id`
            FROM `glpi_monitors`
            WHERE `serial`='".$data['serial']."'
               AND `is_global`='0'
               ".$entity."
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $monitors_id = $db_data['id'];
            if ($db_data['entities_id'] != $data['entities_id']) {
               $transfer = new Transfer();
               $transfer->getFromDB($pfEntity->getValue('transfers_id_auto', $data['entities_id']) > 0);
               $item_to_transfer = array("Monitor" => array($db_data['id']=>$db_data['id']));
               $transfer->moveItems($item_to_transfer, $data['entities_id'], $transfer->fields);
            }
         } else {
            $data['is_global'] = 0;
            $monitors_id = $monitor->add($data);
            $added = 1;
         }
         if ($added == 0) {
            $monitor->getFromDB($monitors_id);
            $computer_Item->disconnectForItem($monitor);
         }
      }
      $data['computers_id']   = $computers_id;
      $data['itemtype']       = 'Monitor';
      $data['items_id']       = $monitors_id;
      $data['is_dynamic']     = 1;
      $data['_no_history']    = $no_history;
      $computer_Item->add($data, array(), FALSE);
   }



   function addPrinter($data, $computers_id, $no_history) {
      global $DB;

      $computer_Item = new Computer_Item();
      $printer       = new Printer();
      $pfConfig      = new PluginFusioninventoryConfig();
      $pfEntity      = new PluginFusioninventoryEntity();

      $printers_id = 0;
      if ($pfConfig->getValue('import_printer') == 1) {
         // Global import
         $where_serial = "AND (`serial`='".$data['serial']."'
                  OR `serial`=''
                  OR `serial` IS NULL)";
         if ($data['serial'] == '') {
            $where_serial = '';
         }
         $query = "SELECT `glpi_printers`.`id` FROM `glpi_printers`
            WHERE `name`='".$data['name']."'
               ".$where_serial."
               AND `is_global`='1'
               AND `entities_id`='".$data['entities_id']."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $printers_id = $db_data['id'];
         } else {
            $data['is_global'] = 1;
            $printers_id = $printer->add($data);
         }
      } else if ($pfConfig->getValue('import_printer') == 2) {
         // Unique import
         $added = 0;
         $query = "SELECT `glpi_printers`.`id` FROM `glpi_printers`
            LEFT JOIN `glpi_computers_items` ON `items_id`=`glpi_printers`.`id`
               AND `glpi_computers_items`.`itemtype`='Printer'
            WHERE `name`='".$data['name']."'
               AND `serial`='".$data['serial']."'
               AND `is_global`='0'
               AND `entities_id`='".$data['entities_id']."'
               AND `glpi_computers_items`.`id` IS NULL
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $printers_id = $db_data['id'];
         } else {
            $data['is_global'] = 0;
            $printers_id = $printer->add($data);
            $added = 1;
         }
      } else if ($pfConfig->getValue('import_printer') == 3) {
         // Unique import on serial number
         $entity = "AND `entities_id`='".$data['entities_id']."'";
         if ($pfEntity->getValue('transfers_id_auto', $data['entities_id']) > 0) {
            $entity = '';
         }
         $added = 0;
         $query = "SELECT `glpi_printers`.`id`, `glpi_printers`.`entities_id`
            FROM `glpi_printers`
            WHERE `name`='".$data['name']."'
               AND `serial`='".$data['serial']."'
               AND `is_global`='0'
               ".$entity."
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $printers_id = $db_data['id'];
            if ($db_data['entities_id'] != $data['entities_id']) {
               $transfer = new Transfer();
               $transfer->getFromDB($pfEntity->getValue('transfers_id_auto', $data['entities_id']) > 0);
               $item_to_transfer = array("Printer" => array($db_data['id']=>$db_data['id']));
               $transfer->moveItems($item_to_transfer, $data['entities_id'], $transfer->fields);
            }
         } else {
            $data['is_global'] = 0;
            $printers_id = $printer->add($data);
            $added = 1;
         }
         if ($added == 0) {
            $printer->getFromDB($printers_id);
            $computer_Item->disconnectForItem($printer);
         }
      }
      $data['computers_id']   = $computers_id;
      $data['itemtype']       = 'Printer';
      $data['items_id']       = $printers_id;
      $data['is_dynamic']     = 1;
      $data['_no_history']    = $no_history;
      $computer_Item->add($data, array(), FALSE);
   }



   function addPeripheral($data, $computers_id, $no_history) {
      global $DB;

      $computer_Item = new Computer_Item();
      $peripheral    = new Peripheral();
      $pfConfig      = new PluginFusioninventoryConfig();
      $pfEntity      = new PluginFusioninventoryEntity();

      $peripherals_id = 0;
      if ($pfConfig->getValue('import_peripheral') == 1) {
         // Global import
         $where_serial = "AND (`serial`='".$data['serial']."'
                  OR `serial`=''
                  OR `serial` IS NULL)";
         if ($data['serial'] == '') {
            $where_serial = '';
         }
         $where_manufacturer = "AND (`manufacturers_id`='".$data['manufacturers_id']."')";
         if ($data['manufacturers_id'] == 0) {
            $where_manufacturer = '';
         }
         $query = "SELECT `glpi_peripherals`.`id` FROM `glpi_peripherals`
            WHERE `name`='".$data['name']."'
               ".$where_manufacturer."
               ".$where_serial."
               AND `is_global`='1'
               AND `entities_id`='".$data['entities_id']."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $peripherals_id = $db_data['id'];
         } else {
            $data['is_global'] = 1;
            $peripherals_id = $peripheral->add($data);
         }
      } else if ($pfConfig->getValue('import_peripheral') == 2) {
         // Unique import
         $added = 0;
         $query = "SELECT `glpi_peripherals`.`id` FROM `glpi_peripherals`
            LEFT JOIN `glpi_computers_items` ON `items_id`=`glpi_peripherals`.`id`
               AND `glpi_computers_items`.`itemtype`='Peripheral'
            WHERE `name`='".$data['name']."'
               AND `manufacturers_id`='".$data['manufacturers_id']."'
               AND `serial`='".$data['serial']."'
               AND `is_global`='0'
               AND `entities_id`='".$data['entities_id']."'
               AND `glpi_computers_items`.`id` IS NULL
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $peripherals_id = $db_data['id'];
         } else {
            $data['is_global'] = 0;
            $peripherals_id = $peripheral->add($data);
            $added = 1;
         }
      } else if ($pfConfig->getValue('import_peripheral') == 3) {
         // Unique import on serial number
         $entity = "AND `entities_id`='".$data['entities_id']."'";
         if ($pfEntity->getValue('transfers_id_auto', $data['entities_id']) > 0) {
            $entity = '';
         }
         $added = 0;
         $query = "SELECT `glpi_peripherals`.`id`, `glpi_peripherals`.`entities_id`
            FROM `glpi_peripherals`
            WHERE `name`='".$data['name']."'
               AND `manufacturers_id`='".$data['manufacturers_id']."'
               AND `serial`='".$data['serial']."'
               AND `is_global`='0'
               ".$entity."
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $db_data = $DB->fetch_assoc($result);
            $peripherals_id = $db_data['id'];
            if ($db_data['entities_id'] != $data['entities_id']) {
               $transfer = new Transfer();
               $transfer->getFromDB($pfEntity->getValue('transfers_id_auto', $data['entities_id']) > 0);
               $item_to_transfer = array("Peripheral" => array($db_data['id']=>$db_data['id']));
               $transfer->moveItems($item_to_transfer, $data['entities_id'], $transfer->fields);
            }
         } else {
            $data['is_global'] = 0;
            $peripherals_id = $peripheral->add($data);
            $added = 1;
         }
         if ($added == 0) {
            $peripheral->getFromDB($peripherals_id);
            $computer_Item->disconnectForItem($peripheral);
         }
      }
      $data['computers_id']   = $computers_id;
      $data['itemtype']       = 'Peripheral';
      $data['items_id']       = $peripherals_id;
      $data['is_dynamic']     = 1;
      $data['_no_history']    = $no_history;
      $computer_Item->add($data, array(), FALSE);
   }



   function arrayDiffEmulation($arrayFrom, $arrayAgainst) {
      $arrayAgainsttmp = array();
      foreach ($arrayAgainst as $key => $data) {
         $arrayAgainsttmp[serialize($data)] = $key;
      }

      foreach ($arrayFrom as $key => $value) {
         if (isset($arrayAgainsttmp[serialize($value)])) {
            unset($arrayFrom[$key]);
         }
      }
      return $arrayFrom;
   }



   function addPrepareLog($items_id, $itemtype, $itemtype_link='', $changes=array('0', '', ''), $linked_action=Log::HISTORY_CREATE_ITEM) {
      $this->log_add[] = array($items_id, $itemtype, $itemtype_link, $_SESSION["glpi_currenttime"], $changes, $linked_action);
   }


   function addLog() {
      global $DB;

      if (count($this->log_add) > 0) {
         $username = addslashes($_SESSION["glpiname"]);

         $dataLog = array();
         foreach ($this->log_add as $data) {
            $changes = $data[4];
            unset($data[4]);
            $id_search_option = $changes[0];
            $old_value = $changes[1];
            $new_value = $changes[2];

            $dataLog[] = "('".implode("', '", $data)."', '".$id_search_option."',
                           '".$old_value."', '".$new_value."',
                           '".$username."')";
         }

         // Build query

         $query = "INSERT INTO `glpi_logs`
                          (`items_id`, `itemtype`, `itemtype_link`, `date_mod`, `linked_action`,
                          `id_search_option`, `old_value`, `new_value`,
                            `user_name`)
                   VALUES ".implode(", \n", $dataLog);

         $DB->query($query);
         $this->log_add = array();
      }
   }



   function setDynamicLinkItems($computers_id) {
      global $DB;

      $computer = new Computer();
      $input = array(
          'id' => $computers_id
      );
      PluginFusioninventoryInventoryComputerInventory::addDefaultStateIfNeeded($input);
      $computer->update($input);

      $DB->query("UPDATE `glpi_computerdisks` SET `is_dynamic`='1'
                     WHERE `computers_id`='".$computers_id."'");

      $DB->query("UPDATE `glpi_computers_items` SET `is_dynamic`='1'
                     WHERE `computers_id`='".$computers_id."'");

      $DB->query("UPDATE `glpi_computers_softwareversions` SET `is_dynamic`='1'
                     WHERE `computers_id`='".$computers_id."'");

      $DB->query("UPDATE `glpi_computervirtualmachines` SET `is_dynamic`='1'
                     WHERE `computers_id`='".$computers_id."'");

      $a_tables = array("glpi_networkports", "glpi_items_devicecases", "glpi_items_devicecontrols",
                        "glpi_items_devicedrives", "glpi_items_devicegraphiccards",
                        "glpi_items_deviceharddrives", "glpi_items_devicememories",
                        "glpi_items_devicemotherboards", "glpi_items_devicenetworkcards",
                        "glpi_items_devicepcis", "glpi_items_devicepowersupplies",
                        "glpi_items_deviceprocessors", "glpi_items_devicesoundcards");

      foreach ($a_tables as $table) {
         $DB->query("UPDATE `".$table."` SET `is_dynamic`='1'
                        WHERE `items_id`='".$computers_id."'
                           AND `itemtype`='Computer'");
      }
   }
}

?>
