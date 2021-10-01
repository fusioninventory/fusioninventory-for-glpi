<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the update / add information of computer
 * inventory into GLPI database.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the update / add information of computer inventory into GLPI database.
 */
class PluginFusioninventoryInventoryComputerLib extends PluginFusioninventoryInventoryCommon {

   /**
    * Define the name of the table
    *
    * @var string
    */
   var $table = "glpi_plugin_fusioninventory_inventorycomputerlibserialization";

   /**
    * Initialize the list of software
    *
    * @var array
    */
   var $softList = [];

   /**
    * Initialize the list of software versions
    *
    * @var array
    */
   var $softVersionList = [];

   /**
    * Initilize the list of logs to add in the database
    *
    * @var array
    */
   var $log_add = [];


   /**
    * Initilize a list of installation that should not be logged
    *
    * @var array
    */
   var $installationWithoutLogs = [];

   /**
    * __contruct function where initialize many variables
    */
   function __construct() {
      $this->software                = new Software();
      $this->softwareVersion         = new SoftwareVersion();
      $this->computerSoftwareVersion = new Item_SoftwareVersion();
      $this->softcatrule             = new RuleSoftwareCategoryCollection();
      $this->computer                = new Computer();
   }


   /**
    * Update computer data
    *
    * @global object $DB
    * @global array $CFG_GLPI
    * @param array $a_computerinventory all data from the agent
    * @param integer $computers_id id of the computer
    * @param boolean $no_history set true if not want history
    * @param integer $setdynamic
    */
   function updateComputer($a_computerinventory, $computers_id, $no_history, $setdynamic = 0) {
      global $DB, $CFG_GLPI;

      $computer                     = new Computer();
      $pfInventoryComputerComputer  = new PluginFusioninventoryInventoryComputerComputer();
      $item_DeviceProcessor         = new Item_DeviceProcessor();
      $deviceProcessor              = new DeviceProcessor();
      $item_DeviceMemory            = new Item_DeviceMemory();
      $deviceMemory                 = new DeviceMemory();
      $item_DeviceBattery           = new Item_DeviceBattery();
      $deviceBattery                = new DeviceBattery();
      $computerVirtualmachine       = new ComputerVirtualMachine();
      $itemDisk                     = new Item_Disk();
      $item_DeviceControl           = new Item_DeviceControl();
      $item_DeviceHardDrive         = new Item_DeviceHardDrive();
      $item_DeviceDrive             = new Item_DeviceDrive();
      $item_DeviceGraphicCard       = new Item_DeviceGraphicCard();
      $item_DeviceNetworkCard       = new Item_DeviceNetworkCard();
      $item_DeviceSoundCard         = new Item_DeviceSoundCard();
      $item_DeviceBios              = new Item_DeviceFirmware();
      $pfInventoryComputerAntivirus = new ComputerAntivirus();
      $pfConfig                     = new PluginFusioninventoryConfig();
      $pfComputerLicenseInfo        = new PluginFusioninventoryComputerLicenseInfo();
      $computer_Item                = new Computer_Item();
      $monitor                      = new Monitor();
      $printer                      = new Printer();
      $peripheral                   = new Peripheral();
      $pfComputerRemotemgmt         = new PluginFusioninventoryComputerRemoteManagement();

      $computer->getFromDB($computers_id);

      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_computers', $computers_id);

      // Manage operating system
      if (isset($a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id'])) {
         $ios = new Item_OperatingSystem();
         $pfos = $a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id'];
         $ios->getFromDBByCrit([
         'itemtype'                          => 'Computer',
         'items_id'                          => $computers_id
         ]);

         $input_os = [
            'itemtype'                          => 'Computer',
            'items_id'                          => $computer->getID(),
            'operatingsystemarchitectures_id'   => $pfos['operatingsystemarchitectures_id'],
            'operatingsystemkernelversions_id'  => $pfos['operatingsystemkernelversions_id'],
            'operatingsystems_id'               => $pfos['operatingsystems_id'],
            'operatingsystemversions_id'        => $pfos['operatingsystemversions_id'],
            'operatingsystemservicepacks_id'    => $pfos['operatingsystemservicepacks_id'],
            'operatingsystemeditions_id'        => $pfos['operatingsystemeditions_id'],
            'licenseid'                         => $pfos['licenseid'],
            'license_number'                    => $pfos['license_number'],
            'is_dynamic'                        => 1,
            'entities_id'                       => $computer->fields['entities_id']
         ];

         if (!$ios->isNewItem()) {
            //OS exists, check for updates
            $same = true;
            foreach ($input_os as $key => $value) {
               if ($ios->fields[$key] != $value) {
                  $same = false;
                  break;
               }
            }
            if ($same === false) {
               $ios->update(['id' => $ios->getID()] + $input_os);
            }
         } else {
            $input_os['_no_history'] = $no_history;
            $ios->add($input_os);
         }
      }

      if ($pfConfig->getValue("component_simcard") != 0) {
         //Import simcards
         $this->importSimcards('Computer', $a_computerinventory, $computers_id, $no_history);
      }

      // * Computer
      $db_computer = $computer->fields;
      // manage auto inventory number
      if ($computer->fields['otherserial'] == ''
         && (!isset($a_computerinventory['Computer']['otherserial'])
            || $a_computerinventory['Computer']['otherserial'] == '')) {

         $a_computerinventory['Computer']['otherserial'] = PluginFusioninventoryToolbox::setInventoryNumber(
            'Computer', '', $computer->fields['entities_id']);
      }

      $a_ret = PluginFusioninventoryToolbox::checkLock($a_computerinventory['Computer'],
                                                         $db_computer, $a_lockable);
      $a_computerinventory['Computer'] = $a_ret[0];

      $input = $a_computerinventory['Computer'];

      $input['id'] = $computers_id;
      $history = true;
      if ($no_history) {
         $history = false;
      }
      $input['_no_history'] = $no_history;
      if (!in_array('states_id', $a_lockable)) {
         $input = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('computer', $input);
      }
      $computer->update($input, !$no_history);

      $this->computer = $computer;

      // * Computer fusion (ext)
      $db_computer = [];
      if ($no_history === false) {
         $iterator = $DB->request([
            'FROM'   => 'glpi_plugin_fusioninventory_inventorycomputercomputers',
            'WHERE'  => ['computers_id' => $computers_id],
            'START'  => 0,
            'LIMIT'  => 1
         ]);
         while ($data = $iterator->next()) {
            foreach ($data as $key=>$value) {
               $data[$key] = Toolbox::addslashes_deep($value);
            }
            $db_computer = $data;
         }
      }

      if (count($db_computer) == '0') { // Add
         $a_computerinventory['fusioninventorycomputer']['computers_id'] = $computers_id;
         $pfInventoryComputerComputer->add($a_computerinventory['fusioninventorycomputer'],
                                        [], false);
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
         $pfInventoryComputerComputer->update($input, !$no_history);
      }

      // Put all link item dynamic (in case of update computer not yet inventoried with fusion)
      if ($setdynamic == 1) {
         $this->setDynamicLinkItems($computers_id);
      }

      // * BIOS
      $db_bios = [];
      if ($no_history === false) {
         $iterator = $DB->request([
            'SELECT'    => [
               'glpi_items_devicefirmwares.id',
               'serial',
               'designation',
               'version'
            ],
            'FROM'      => 'glpi_items_devicefirmwares',
            'LEFT JOIN' => [
               'glpi_devicefirmwares' => [
                  'FKEY' => [
                     'glpi_items_devicefirmwares'  => 'devicefirmwares_id',
                     'glpi_devicefirmwares'        => 'id'
                  ]
               ]
            ],
            'WHERE'     => [
               'items_id'     => $computers_id,
               'itemtype'     => 'Computer',
               'is_dynamic'   => 1
            ]
         ]);
         while ($data = $iterator->next()) {
            $idtmp = $data['id'];
            unset($data['id']);
            $data1 = Toolbox::addslashes_deep($data);
            $data2 = array_map('strtolower', $data1);
            $db_bios[$idtmp] = $data2;
         }
      }

      if (count($db_bios) == 0) {
         if (isset($a_computerinventory['bios'])
               && !empty($a_computerinventory['bios'])) {
            $this->addBios($a_computerinventory['bios'], $computers_id, $no_history);
         }
      } else {
         if (isset($a_computerinventory['bios'])
               && !empty($a_computerinventory['bios'])) {
            $arrayslower = array_map('strtolower', $a_computerinventory['bios']);
            foreach ($db_bios as $keydb => $arraydb) {
               if (isset($arrayslower['version']) && $arrayslower['version'] == $arraydb['version']) {
                  unset($a_computerinventory['bios']);
                  unset($db_bios[$keydb]);
                  break;
               }
            }
         }

         if (count($db_bios) != 0) {
            // Delete BIOS in DB
            foreach ($db_bios as $idtmp => $data) {
               $item_DeviceBios->delete(['id'=>$idtmp], 1);
            }
         }

         if (isset($a_computerinventory['bios'])) {
            $this->addBios($a_computerinventory['bios'], $computers_id, $no_history);
         }
      }

      // * Processors
      if ($pfConfig->getValue("component_processor") != 0) {
         $db_processors = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_deviceprocessors.id',
                  'designation',
                  'frequency',
                  'frequence',
                  'frequency_default',
                  'serial',
                  'manufacturers_id',
                  'glpi_items_deviceprocessors.nbcores',
                  'glpi_items_deviceprocessors.nbthreads'
               ],
               'FROM'      => 'glpi_items_deviceprocessors',
               'LEFT JOIN' => [
                  'glpi_deviceprocessors' => [
                     'FKEY' => [
                        'glpi_deviceprocessors'       => 'id',
                        'glpi_items_deviceprocessors' => 'deviceprocessors_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'  => $computers_id,
                  'itemtype'  => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
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

            if (count($a_computerinventory['processor']) || count($db_processors)) {
               if (count($db_processors) != 0) {
                  // Delete processor in DB
                  foreach ($db_processors as $idtmp => $data) {
                     $item_DeviceProcessor->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['processor']) != 0) {
                  foreach ($a_computerinventory['processor'] as $a_processor) {
                     $this->addProcessor($a_processor, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * Memories
      if ($pfConfig->getValue("component_memory") != 0) {
         $db_memories = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_devicememories.id',
                  'designation',
                  'size',
                  'frequence',
                  'serial',
                  'devicememorytypes_id',
                  'glpi_items_devicememories.busID',
               ],
               'FROM'      => 'glpi_items_devicememories',
               'LEFT JOIN' => [
                  'glpi_devicememories' => [
                     'FKEY' => [
                        'glpi_devicememories'         => 'id',
                        'glpi_items_devicememories'   => 'devicememories_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'  => $computers_id,
                  'itemtype'  => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);

            while ($data = $iterator->next()) {
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
               $frequence = (int) $arrays['frequence'];
               unset($arrays['frequence']);
               foreach ($db_memories as $keydb => $arraydb) {
                  $frequencedb = (int) $arraydb['frequence'];
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

            if (count($a_computerinventory['memory']) || count($db_memories)) {
               if (count($db_memories) != 0) {
                  // Delete memory in DB
                  foreach ($db_memories as $idtmp => $data) {
                     $item_DeviceMemory->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['memory']) != 0) {
                  foreach ($a_computerinventory['memory'] as $a_memory) {
                     $this->addMemory($a_memory, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * Hard drive
      if ($pfConfig->getValue("component_harddrive") != 0) {
         $db_harddrives = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT' => [
                  'id',
                  'serial',
                  'capacity'
               ],
               'FROM'   => 'glpi_items_deviceharddrives',
               'WHERE'  => [
                  'items_id'     => $computers_id,
                  'itemtype'     => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
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

               // if disk has no serial, don't add and unset it
               if (!isset($arrayslower['serial'])) {
                  unset($a_computerinventory['harddrive'][$key]);
                  break;
               }

               foreach ($db_harddrives as $keydb => $arraydb) {
                  if ($arrayslower['serial'] == $arraydb['serial']) {
                     if ($arrayslower['capacity'] > 0
                         && $arraydb['capacity'] != $arrayslower['capacity']) {
                        $input = [
                           'id'       => $keydb,
                           'capacity' => $arrayslower['capacity']
                        ];
                        $item_DeviceHardDrive->update($input);
                     }
                     unset($a_computerinventory['harddrive'][$key]);
                     unset($db_harddrives[$keydb]);
                     break;
                  }
               }
            }

            if (count($a_computerinventory['harddrive']) || count($db_harddrives)) {
               if (count($db_harddrives) != 0) {
                  // Delete hard drive in DB
                  foreach ($db_harddrives as $idtmp => $data) {
                     $item_DeviceHardDrive->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['harddrive']) != 0) {
                  foreach ($a_computerinventory['harddrive'] as $a_harddrive) {
                     $this->addHardDisk($a_harddrive, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * drive
      if ($pfConfig->getValue("component_drive") != 0) {
         $db_drives = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_devicedrives.id',
                  'serial',
                  'glpi_devicedrives.designation'
               ],
               'FROM'      => 'glpi_items_devicedrives',
               'LEFT JOIN' => [
                  'glpi_devicedrives' => [
                     'FKEY' => [
                        'glpi_devicedrives'        => 'id',
                        'glpi_items_devicedrives'  => 'devicedrives_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'     => $computers_id,
                  'itemtype'     => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data1 = Toolbox::addslashes_deep($data);
               $data2 = array_map('strtolower', $data1);
               $db_drives[$idtmp] = $data2;
            }
         }

         if (count($db_drives) == 0) {
            foreach ($a_computerinventory['drive'] as $a_drive) {
               $this->addDrive($a_drive, $computers_id, $no_history);
            }
         } else {
            foreach ($a_computerinventory['drive'] as $key => $arrays) {
               $arrayslower = array_map('strtolower', $arrays);
               if ($arrayslower['serial'] == '') {
                  foreach ($db_drives as $keydb => $arraydb) {
                     if ($arrayslower['designation'] == $arraydb['designation']) {
                        unset($a_computerinventory['drive'][$key]);
                        unset($db_drives[$keydb]);
                        break;
                     }
                  }
               } else {
                  foreach ($db_drives as $keydb => $arraydb) {
                     if ($arrayslower['serial'] == $arraydb['serial']) {
                        unset($a_computerinventory['drive'][$key]);
                        unset($db_drives[$keydb]);
                        break;
                     }
                  }
               }
            }

            if (count($a_computerinventory['drive']) || count($db_drives)) {
               if (count($db_drives) != 0) {
                  // Delete drive in DB
                  foreach ($db_drives as $idtmp => $data) {
                     $item_DeviceDrive->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['drive']) != 0) {
                  foreach ($a_computerinventory['drive'] as $a_drive) {
                     $this->addDrive($a_drive, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * Graphiccard
      if ($pfConfig->getValue("component_graphiccard") != 0) {
         $db_graphiccards = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_devicegraphiccards.id',
                  'designation',
                  'memory'
               ],
               'FROM'      => 'glpi_items_devicegraphiccards',
               'LEFT JOIN' => [
                  'glpi_devicegraphiccards' => [
                     'FKEY' => [
                        'glpi_devicegraphiccards'        => 'id',
                        'glpi_items_devicegraphiccards'  => 'devicegraphiccards_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'     => $computers_id,
                  'itemtype'     => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
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

            if (count($a_computerinventory['graphiccard']) || count($db_graphiccards)) {
               if (count($db_graphiccards) != 0) {
                  // Delete graphiccard in DB
                  foreach ($db_graphiccards as $idtmp => $data) {
                     $item_DeviceGraphicCard->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['graphiccard']) != 0) {
                  foreach ($a_computerinventory['graphiccard'] as $a_graphiccard) {
                     $this->addGraphicCard($a_graphiccard, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * networkcard
      if ($pfConfig->getValue("component_networkcard") != 0) {
         $db_networkcards = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_devicenetworkcards.id',
                  'designation',
                  'mac',
                  'manufacturers_id'
               ],
               'FROM'      => 'glpi_items_devicenetworkcards',
               'LEFT JOIN' => [
                  'glpi_devicenetworkcards' => [
                     'FKEY' => [
                        'glpi_devicenetworkcards'        => 'id',
                        'glpi_items_devicenetworkcards'  => 'devicenetworkcards_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'     => $computers_id,
                  'itemtype'     => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
               $idtmp = $data['id'];
               unset($data['id']);
               if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $data['designation'])) {
                  $data['designation'] = Toolbox::addslashes_deep($data['designation']);
               }
               $data['designation'] = trim(strtolower($data['designation']));
               $db_networkcards[$idtmp] = $data;
            }
         }

         if (count($db_networkcards) == 0) {
            foreach ($a_computerinventory['networkcard'] as $a_networkcard) {
               $this->addNetworkCard($a_networkcard, $computers_id, $no_history);
            }
         } else {
            // Check all fields from source: 'designation', 'mac'
            foreach ($a_computerinventory['networkcard'] as $key => $arrays) {
               $arrays['designation'] = strtolower($arrays['designation']);
               foreach ($db_networkcards as $keydb => $arraydb) {
                  if ($arrays == $arraydb) {
                     unset($a_computerinventory['networkcard'][$key]);
                     unset($db_networkcards[$keydb]);
                     break;
                  }
               }
            }

            if (count($a_computerinventory['networkcard']) || count($db_networkcards)) {
               if (count($db_networkcards) != 0) {
                  // Delete networkcard in DB
                  foreach ($db_networkcards as $idtmp => $data) {
                     $item_DeviceNetworkCard->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['networkcard']) != 0) {
                  foreach ($a_computerinventory['networkcard'] as $a_networkcard) {
                     $this->addNetworkCard($a_networkcard, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * Sound
      if ($pfConfig->getValue("component_soundcard") != 0) {
         $db_soundcards = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_devicesoundcards.id',
                  'designation',
                  'comment',
                  'manufacturers_id'
               ],
               'FROM'      => 'glpi_items_devicesoundcards',
               'LEFT JOIN' => [
                  'glpi_devicesoundcards' => [
                     'FKEY' => [
                        'glpi_devicesoundcards'       => 'id',
                        'glpi_items_devicesoundcards' => 'devicesoundcards_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'     => $computers_id,
                  'itemtype'     => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
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

            if (count($a_computerinventory['soundcard']) || count($db_soundcards)) {
               if (count($db_soundcards) != 0) {
                  // Delete soundcard in DB
                  foreach ($db_soundcards as $idtmp => $data) {
                     $item_DeviceSoundCard->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['soundcard']) != 0) {
                  foreach ($a_computerinventory['soundcard'] as $a_soundcard) {
                     $this->addSoundCard($a_soundcard, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * Controllers
      if ($pfConfig->getValue("component_control") != 0) {
         $db_controls = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_devicecontrols.id',
                  'interfacetypes_id',
                  'manufacturers_id',
                  'designation'
               ],
               'FROM'      => 'glpi_items_devicecontrols',
               'LEFT JOIN' => [
                  'glpi_devicecontrols' => [
                     'FKEY' => [
                        'glpi_devicecontrols'         => 'id',
                        'glpi_items_devicecontrols'   => 'devicecontrols_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'     => $computers_id,
                  'itemtype'     => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
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

            if (count($a_computerinventory['controller']) || count($db_controls)) {
               if (count($db_controls) != 0) {
                  // Delete controller in DB
                  foreach ($db_controls as $idtmp => $data) {
                     $item_DeviceControl->delete(['id'=>$idtmp], 1);
                  }
               }
               if (count($a_computerinventory['controller']) != 0) {
                  foreach ($a_computerinventory['controller'] as $a_control) {
                     $this->addControl($a_control, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      // * Software
      if ($pfConfig->getValue("import_software") != 0) {
         $this->importSoftware('Computer', $a_computerinventory, $computer, $no_history);
      }

      // * Virtualmachines
      if ($pfConfig->getValue("import_vm") == 1) {
         $db_computervirtualmachine = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT' => ['id', 'name', 'uuid', 'virtualmachinesystems_id'],
               'FROM'   => 'glpi_computervirtualmachines',
               'WHERE'  => [
                  'computers_id' => $computers_id,
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data1 = Toolbox::addslashes_deep($data);
               $db_computervirtualmachine[$idtmp] = $data1;
            }
         }
         $simplecomputervirtualmachine = [];
         if (isset($a_computerinventory['virtualmachine'])) {
            foreach ($a_computerinventory['virtualmachine'] as $key=>$a_computervirtualmachine) {
               $a_field = ['name', 'uuid', 'virtualmachinesystems_id'];
               foreach ($a_field as $field) {
                  if (isset($a_computervirtualmachine[$field])) {
                     $simplecomputervirtualmachine[$key][$field] = $a_computervirtualmachine[$field];
                  }
               }
            }
         }
         foreach ($simplecomputervirtualmachine as $key => $arrays) {
            foreach ($db_computervirtualmachine as $keydb => $arraydb) {
               if ($arrays == $arraydb) {
                  $input = [];
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
                  $computerVirtualmachine->update($input, !$no_history);
                  unset($simplecomputervirtualmachine[$key]);
                  unset($a_computerinventory['virtualmachine'][$key]);
                  unset($db_computervirtualmachine[$keydb]);
                  break;
               }
            }
         }
         if (count($a_computerinventory['virtualmachine']) || count($db_computervirtualmachine)) {
            if (count($db_computervirtualmachine) != 0) {
               // Delete virtualmachine in DB
               foreach ($db_computervirtualmachine as $idtmp => $data) {
                  $computerVirtualmachine->delete(['id'=>$idtmp], 1);
               }
            }
            if (count($a_computerinventory['virtualmachine']) != 0) {
               foreach ($a_computerinventory['virtualmachine'] as $a_virtualmachine) {
                  $a_virtualmachine['computers_id'] = $computers_id;
                  $computerVirtualmachine->add($a_virtualmachine, [], !$no_history);
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
         if (isset($a_computerinventory['virtualmachine_creation'])
         && is_array($a_computerinventory['virtualmachine_creation'])) {
            foreach ($a_computerinventory['virtualmachine_creation'] as $a_vm) {
               // Define location of physical computer (host)
               $a_vm['locations_id'] = $computer->fields['locations_id'];

               if (isset($a_vm['uuid'])
               && $a_vm['uuid'] != '') {
                  $iterator = $DB->request([
                     'FROM'   => 'glpi_computers',
                     'WHERE'  => [
                        'RAW' => [
                           'LOWER(uuid)'  => ComputerVirtualMachine::getUUIDRestrictCriteria($a_vm['uuid'])
                        ]
                     ],
                     'LIMIT'  => 1
                  ]);
                  $computers_vm_id = 0;
                  while ($data = $iterator->next()) {
                     $computers_vm_id = $data['id'];
                  }
                  if ($computers_vm_id == 0) {
                     // Add computer
                     $a_vm['entities_id'] = $computer->fields['entities_id'];
                     $computers_vm_id = $computervm->add($a_vm, [], !$no_history);
                     // Manage networks
                     $this->manageNetworkPort($a_vm['networkport'], $computers_vm_id, false);
                  } else {
                     if ($pfAgent->getAgentWithComputerid($computers_vm_id) === false) {
                        // Update computer
                        $a_vm['id'] = $computers_vm_id;
                        $computervm->update($a_vm, !$no_history);
                        // Manage networks
                        $this->manageNetworkPort($a_vm['networkport'], $computers_vm_id, false);
                     }
                  }
               }
            }
         }
      }

      // * Item_Disk
      if ($pfConfig->getValue("import_volume") != 0) {
         $db_itemdisk = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT' => ['id', 'name', 'device', 'mountpoint'],
               'FROM'   => 'glpi_items_disks',
               'WHERE'  => [
                  'items_id' => $computers_id,
                  'itemtype' => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);
            while ($data = $iterator->next()) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data1 = Toolbox::addslashes_deep($data);
               $data2 = array_map('strtolower', $data1);
               $db_itemdisk[$idtmp] = $data2;
            }
         }
         $simpleitemdisk = [];
         foreach ($a_computerinventory['computerdisk'] as $key=>$a_itemdisk) {
            $a_field = ['name', 'device', 'mountpoint'];
            foreach ($a_field as $field) {
               if (isset($a_itemdisk[$field])) {
                  $simpleitemdisk[$key][$field] = $a_itemdisk[$field];
               }
            }
         }
         foreach ($simpleitemdisk as $key => $arrays) {
            $arrayslower = array_map('strtolower', $arrays);
            foreach ($db_itemdisk as $keydb => $arraydb) {
               if ($arrayslower == $arraydb) {
                  $input = [];
                  $input['id'] = $keydb;
                  if (isset($a_computerinventory['computerdisk'][$key]['filesystems_id'])) {
                     $input['filesystems_id'] =
                              $a_computerinventory['computerdisk'][$key]['filesystems_id'];
                  }
                  $input['totalsize'] = $a_computerinventory['computerdisk'][$key]['totalsize'];
                  $input['freesize'] = $a_computerinventory['computerdisk'][$key]['freesize'];
                  $disk = $a_computerinventory['computerdisk'][$key];

                  // Safecheck until GLPI X
                  if (defined('Item_Disk::ENCRYPTION_STATUS_YES') && isset($a_itemdisk['encryption_status'])) {
                     // Encryption status
                     if ($disk['encryption_status'] == "Yes") {
                        $input['encryption_status'] = Item_Disk::ENCRYPTION_STATUS_YES;
                     } else if ($disk['encryption_status'] == "Partially") {
                        $input['encryption_status'] = Item_Disk::ENCRYPTION_STATUS_PARTIALLY;
                     } else {
                        $input['encryption_status'] = Item_Disk::ENCRYPTION_STATUS_NO;
                     }

                     // Encryption details
                     $input['encryption_tool'] = $disk['encryption_tool'] ??  null;
                     $input['encryption_algorithm'] = $disk['encryption_algorithm'] ?? null;
                     $input['encryption_type'] = $disk['encrypt_type'] ?? null;
                  }

                  $input['_no_history'] = true;
                  $itemDisk->update($input, false);
                  unset($simpleitemdisk[$key]);
                  unset($a_computerinventory['computerdisk'][$key]);
                  unset($db_itemdisk[$keydb]);
                  break;
               }
            }
         }

         if (count($a_computerinventory['computerdisk']) || count($db_itemdisk)) {
            if (count($db_itemdisk) != 0) {
               // Delete Item_Disk in DB
               foreach ($db_itemdisk as $idtmp => $data) {
                  $itemDisk->delete(['id'=>$idtmp], 1);
               }
            }
            if (count($a_computerinventory['computerdisk']) != 0) {
               foreach ($a_computerinventory['computerdisk'] as $a_itemdisk) {
                  $a_itemdisk['items_id']  = $computers_id;
                  $a_itemdisk['itemtype']  = 'Computer';
                  $a_itemdisk['is_dynamic']    = 1;

                  // Safecheck until GLPI X
                  if (defined('Item_Disk::ENCRYPTION_STATUS_YES') && isset($a_itemdisk['encryption_status'])) {
                     //Encryption status
                     if ($a_itemdisk['encryption_status'] == "Yes") {
                        $a_itemdisk['encryption_status'] = Item_Disk::ENCRYPTION_STATUS_YES;
                     } else if ($a_itemdisk['encryption_status'] == "Partially") {
                        $a_itemdisk['encryption_status'] = Item_Disk::ENCRYPTION_STATUS_PARTIALLY;
                     } else {
                        $a_itemdisk['encryption_status'] = Item_Disk::ENCRYPTION_STATUS_NO;
                     }
                  }
                  $a_itemdisk['_no_history']   = $no_history;
                  $itemDisk->add($a_itemdisk, [], !$no_history);
               }
            }
         }
      }

      // * Networkports
      if ($pfConfig->getValue("component_networkcard") != 0) {
         // Get port from unmanaged device if exist
         $this->manageNetworkPort($a_computerinventory['networkport'], $computers_id, $no_history);
      }

      // * Antivirus
      if ($pfConfig->getValue("import_antivirus") != 0) {
         $db_antivirus = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT' => ['id', 'name', 'antivirus_version'],
               'FROM'   => $pfInventoryComputerAntivirus->getTable(),
               'WHERE'  => ['computers_id' => $computers_id]
            ]);
            while ($data = $iterator->next()) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data1 = Toolbox::addslashes_deep($data);
               $data2 = array_map('strtolower', $data1);
               $db_antivirus[$idtmp] = $data2;
            }
         }
         $simpleantivirus = [];
         foreach ($a_computerinventory['antivirus'] as $key=>$a_antivirus) {
            $a_field = ['name', 'antivirus_version'];
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
                  $input = [];
                  $input = $a_computerinventory['antivirus'][$key];
                  $input['id'] = $keydb;
                  $input['is_dynamic'] = 1;
                  $pfInventoryComputerAntivirus->update($input, !$no_history);
                  unset($simpleantivirus[$key]);
                  unset($a_computerinventory['antivirus'][$key]);
                  unset($db_antivirus[$keydb]);
                  break;
               }
            }
         }
         if (count($a_computerinventory['antivirus']) || count($db_antivirus)) {
            if (count($db_antivirus) != 0) {
               foreach ($db_antivirus as $idtmp => $data) {
                  $pfInventoryComputerAntivirus->delete(['id'=>$idtmp], 1);
               }
            }
            if (count($a_computerinventory['antivirus']) != 0) {
               foreach ($a_computerinventory['antivirus'] as $a_antivirus) {
                  $a_antivirus['computers_id'] = $computers_id;
                  $a_antivirus['is_dynamic'] = 1;
                  $pfInventoryComputerAntivirus->add($a_antivirus, [], !$no_history);
               }
            }
         }
      }

      // * Licenseinfo
         $db_licenseinfo = [];
      if ($no_history === false) {
         $iterator = $DB->request([
            'SELECT' => ['id', 'name', 'fullname', 'serial'],
            'FROM'   => 'glpi_plugin_fusioninventory_computerlicenseinfos',
            'WHERE'  => ['computers_id' => $computers_id]
         ]);
         while ($data = $iterator->next()) {
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
      if (count($a_computerinventory['licenseinfo']) || count($db_licenseinfo)) {
         if (count($db_licenseinfo) != 0) {
            foreach ($db_licenseinfo as $idtmp => $data) {
               $pfComputerLicenseInfo->delete(['id'=>$idtmp], 1);
            }
         }
         if (count($a_computerinventory['licenseinfo']) != 0) {
            foreach ($a_computerinventory['licenseinfo'] as $a_licenseinfo) {
               $a_licenseinfo['computers_id'] = $computers_id;
               $pfComputerLicenseInfo->add($a_licenseinfo, [], !$no_history);
            }
         }
      }

      // * Remote_mgmt
         $db_remotemgmt = [];
      if ($no_history === false) {
         $iterator = $DB->request([
            'SELECT' => ['id', 'type', 'number'],
            'FROM'   => 'glpi_plugin_fusioninventory_computerremotemanagements',
            'WHERE'  => ['computers_id' => $computers_id]
         ]);
         while ($data = $iterator->next()) {
            $idtmp = $data['id'];
            unset($data['id']);
            $data1 = Toolbox::addslashes_deep($data);
            $data2 = array_map('strtolower', $data1);
            $db_remotemgmt[$idtmp] = $data2;
         }
      }
      foreach ($a_computerinventory['remote_mgmt'] as $key => $arrays) {
         $arrayslower = array_map('strtolower', $arrays);
         foreach ($db_remotemgmt as $keydb => $arraydb) {
            if ($arrayslower == $arraydb) {
               unset($a_computerinventory['remote_mgmt'][$key]);
               unset($db_remotemgmt[$keydb]);
               break;
            }
         }
      }
      if (count($a_computerinventory['remote_mgmt']) || count($db_remotemgmt)) {
         if (count($db_remotemgmt) != 0) {
            foreach ($db_remotemgmt as $idtmp => $data) {
               $pfComputerRemotemgmt->delete(['id'=>$idtmp], 1);
            }
         }
         if (count($a_computerinventory['remote_mgmt']) != 0) {
            foreach ($a_computerinventory['remote_mgmt'] as $a_remotemgmt) {
               $a_remotemgmt['computers_id'] = $computers_id;
               $pfComputerRemotemgmt->add($a_remotemgmt, [], !$no_history);
            }
         }
      }

      // * Batteries
      if ($pfConfig->getValue("component_battery") != 0) {
         $db_batteries = [];
         if ($no_history === false) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_items_devicebatteries.id',
                  'serial',
                  'voltage',
                  'capacity'
               ],
               'FROM'      => 'glpi_items_devicebatteries',
               'LEFT JOIN' => [
                  'glpi_devicebatteries' => [
                     'FKEY' => [
                        'glpi_devicebatteries'        => 'id',
                        'glpi_items_devicebatteries'  => 'devicebatteries_id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'items_id'     => $computers_id,
                  'itemtype'     => 'Computer',
                  'is_dynamic'   => 1
               ]
            ]);

            while ($data = $iterator->next()) {
               $idtmp = $data['id'];
               unset($data['id']);
               $data = Toolbox::addslashes_deep($data);
               $data = array_map('strtolower', $data);
               $db_batteries[$idtmp] = $data;
            }
         }

         if (count($db_batteries) == 0) {
            foreach ($a_computerinventory['batteries'] as $a_battery) {
               $this->addBattery($a_battery, $computers_id, $no_history);
            }
         } else {
            // Check all fields from source: 'designation', 'serial', 'size',
            // 'devicebatterytypes_id', 'frequence'
            foreach ($a_computerinventory['batteries'] as $key => $arrays) {
               $arrayslower = array_map('strtolower', $arrays);
               foreach ($db_batteries as $keydb => $arraydb) {
                  if (isset($arrayslower['serial'])
                     && isset($arraydb['serial'])
                     && $arrayslower['serial'] == $arraydb['serial']
                  ) {
                     $update = false;
                     if ($arraydb['capacity'] == 0
                              && $arrayslower['capacity'] > 0) {
                        $input = [
                           'id'       => $keydb,
                           'capacity' => $arrayslower['capacity']
                        ];
                        $update = true;
                     }

                     if ($arraydb['voltage'] == 0
                              && $arrayslower['voltage'] > 0) {
                        $input = [
                           'id'        => $keydb,
                           'voltage'   => $arrayslower['voltage']
                        ];
                        $update = true;
                     }

                     if ($update === true) {
                        $item_DeviceMemory->update($input);
                     }

                     unset($a_computerinventory['batteries'][$key]);
                     unset($db_batteries[$keydb]);
                     break;
                  }
               }

               //delete remaining batteries in database
               if (count($db_batteries) > 0) {
                  // Delete battery in DB
                  foreach ($db_batteries as $idtmp => $data) {
                     $item_DeviceBattery->delete(['id' => $idtmp], 1);
                  }
               }

               //add new batteries in database
               if (count($a_computerinventory['batteries']) != 0) {
                  foreach ($a_computerinventory['batteries'] as $a_battery) {
                     $this->addBattery($a_battery, $computers_id, $no_history);
                  }
               }
            }
         }
      }

      $entities_id = $_SESSION["plugin_fusioninventory_entity"];
      // * Monitors
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      $a_monitors = [];
      foreach ($a_computerinventory['monitor'] as $key => $arrays) {
         $input = [];
         $input['itemtype'] = "Monitor";
         $input['name']     = $arrays['name'];
         $input['serial']   = isset($arrays['serial'])
                               ? $arrays['serial']
                               : "";
         $data = $rule->processAllRules($input, [], ['class'=>$this, 'return' => true]);

         if (isset($data['found_equipment'])) {
            if ($data['found_equipment'][0] == 0) {
               // add monitor
               $arrays['entities_id'] = $entities_id;
               $arrays['otherserial'] = PluginFusioninventoryToolbox::setInventoryNumber(
                  'Monitor', '', $entities_id);
               $a_monitors[] = $monitor->add($arrays);
            } else {
               $a_monitors[] = $data['found_equipment'][0];
            }
            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
               $inputrulelog = [];
               $inputrulelog['date'] = date('Y-m-d H:i:s');
               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
                  $inputrulelog['plugin_fusioninventory_agents_id'] =
                                 $_SESSION['plugin_fusioninventory_agents_id'];
               }
               $inputrulelog['items_id'] = end($a_monitors);
               $inputrulelog['itemtype'] = "Monitor";
               $inputrulelog['method'] = 'inventory';
               $pfRulematchedlog->add($inputrulelog, [], false);
               $pfRulematchedlog->cleanOlddata(end($a_monitors), "Monitor");
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }
         }
      }

      $db_monitors = [];
      $iterator = $DB->request([
         'SELECT'    => [
            'glpi_monitors.id',
            'glpi_computers_items.id AS link_id'
         ],
         'FROM'      => 'glpi_computers_items',
         'LEFT JOIN' => [
            'glpi_monitors' => [
               'FKEY' => [
                  'glpi_monitors'         => 'id',
                  'glpi_computers_items'  => 'items_id'
               ]
            ]
         ],
         'WHERE'     => [
            'itemtype'                          => 'Monitor',
            'computers_id'                      => $computers_id,
            'entities_id'                       => $entities_id,
            'glpi_computers_items.is_dynamic'   => 1,
            'glpi_monitors.is_global'           => 0
         ]
      ]);
      while ($data = $iterator->next()) {
         $idtmp = $data['link_id'];
         unset($data['link_id']);
         $db_monitors[$idtmp] = $data['id'];
      }
      if (count($db_monitors) == 0) {
         foreach ($a_monitors as $monitors_id) {
            $input = [
               'computers_id' => $computers_id,
               'itemtype'     => 'Monitor',
               'items_id'     => $monitors_id,
               'is_dynamic'   => 1,
               '_no_history'  => $no_history
            ];
            $this->computerItemAdd($input, $no_history);
         }
      } else {
         // Check all fields from source:
         foreach ($a_monitors as $key => $monitors_id) {
            foreach ($db_monitors as $keydb => $monits_id) {
               if ($monitors_id == $monits_id) {
                  unset($a_monitors[$key]);
                  unset($db_monitors[$keydb]);
                  break;
               }
            }
         }

         if (count($a_monitors) || count($db_monitors)) {
            if (count($db_monitors) != 0) {
               // Delete monitors links in DB
               foreach ($db_monitors as $idtmp => $monits_id) {
                  $computer_Item->delete(['id'=>$idtmp], 1);
               }
            }
            if (count($a_monitors) != 0) {
               foreach ($a_monitors as $key => $monitors_id) {
                  $input = [];
                  $input['computers_id']   = $computers_id;
                  $input['itemtype']       = 'Monitor';
                  $input['items_id']       = $monitors_id;
                  $input['is_dynamic']     = 1;
                  $input['_no_history']    = $no_history;
                  $this->computerItemAdd($input, $no_history);
               }
            }
         }
      }

      // * Printers
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      $a_printers = [];
      foreach ($a_computerinventory['printer'] as $key => $arrays) {
         $input = [];
         $input['itemtype'] = "Printer";
         $input['name']     = $arrays['name'];
         $input['serial']   = isset($arrays['serial'])
                               ? $arrays['serial']
                               : "";
         $data = $rule->processAllRules($input, [], ['class'=>$this, 'return' => true]);
         if (isset($data['found_equipment'])) {
            if ($data['found_equipment'][0] == 0) {
               // add printer
               $arrays['entities_id'] = $entities_id;
               $arrays['otherserial'] = PluginFusioninventoryToolbox::setInventoryNumber(
                  'Printer', '', $entities_id);
               $a_printers[] = $printer->add($arrays);
            } else {
               $a_printers[] = $data['found_equipment'][0];
            }
            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
               $inputrulelog = [];
               $inputrulelog['date'] = date('Y-m-d H:i:s');
               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
                  $inputrulelog['plugin_fusioninventory_agents_id'] =
                                 $_SESSION['plugin_fusioninventory_agents_id'];
               }
               $inputrulelog['items_id'] = end($a_printers);
               $inputrulelog['itemtype'] = "Printer";
               $inputrulelog['method'] = 'inventory';
               $pfRulematchedlog->add($inputrulelog, [], false);
               $pfRulematchedlog->cleanOlddata(end($a_printers), "Printer");
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }

         }
      }
      $db_printers = [];
      $iterator = $DB->request([
         'SELECT'    => [
            'glpi_printers.id',
            'glpi_computers_items.id AS link_id'
         ],
         'FROM'      => 'glpi_computers_items',
         'LEFT JOIN' => [
            'glpi_printers' => [
               'FKEY' => [
                  'glpi_printers'         => 'id',
                  'glpi_computers_items'  => 'items_id'
               ]
            ]
         ],
         'WHERE'     => [
            'itemtype'                          => 'Printer',
            'computers_id'                      => $computers_id,
            'entities_id'                       => $entities_id,
            'glpi_computers_items.is_dynamic'   => 1,
            'glpi_printers.is_global'           => 0
         ]
      ]);

      while ($data = $iterator->next()) {
         $idtmp = $data['link_id'];
         unset($data['link_id']);
         $db_printers[$idtmp] = $data['id'];
      }
      if (count($db_printers) == 0) {
         foreach ($a_printers as $printers_id) {
            $input['entities_id'] = $entities_id;
            $input['computers_id']   = $computers_id;
            $input['itemtype']       = 'Printer';
            $input['items_id']       = $printers_id;
            $input['is_dynamic']     = 1;
            $input['_no_history']    = $no_history;
            $this->computerItemAdd($input, $no_history);
         }
      } else {
         // Check all fields from source:
         foreach ($a_printers as $key => $printers_id) {
            foreach ($db_printers as $keydb => $prints_id) {
               if ($printers_id == $prints_id) {
                  unset($a_printers[$key]);
                  unset($db_printers[$keydb]);
                  break;
               }
            }
         }
         if (count($a_printers) || count($db_printers)) {
            if (count($db_printers) != 0) {
               // Delete printers links in DB
               foreach ($db_printers as $idtmp => $data) {
                  $computer_Item->delete(['id'=>$idtmp], 1);
               }
            }
            if (count($a_printers) != 0) {
               foreach ($a_printers as $printers_id) {
                  $input['entities_id'] = $entities_id;
                  $input['computers_id']   = $computers_id;
                  $input['itemtype']       = 'Printer';
                  $input['items_id']       = $printers_id;
                  $input['is_dynamic']     = 1;
                  $input['_no_history']    = $no_history;
                  $this->computerItemAdd($input, $no_history);
               }
            }
         }
      }

      // * Peripheral
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      $a_peripherals = [];
      foreach ($a_computerinventory['peripheral'] as $key => $arrays) {
         $input = [];
         $input['itemtype'] = "Peripheral";
         $input['name']     = $arrays['name'];
         $input['serial']   = isset($arrays['serial'])
                               ? $arrays['serial']
                               : "";
         $data = $rule->processAllRules($input, [], ['class'=>$this, 'return' => true]);
         if (isset($data['found_equipment'])) {
            if ($data['found_equipment'][0] == 0) {
               // add peripheral
               $arrays['entities_id'] = $entities_id;
               $arrays['otherserial'] = PluginFusioninventoryToolbox::setInventoryNumber(
                  'Peripheral', '', $entities_id);
               $a_peripherals[] = $peripheral->add(\Toolbox::addslashes_deep($arrays));
            } else {
               $a_peripherals[] = $data['found_equipment'][0];
            }
            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
               $inputrulelog = [];
               $inputrulelog['date'] = date('Y-m-d H:i:s');
               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
                  $inputrulelog['plugin_fusioninventory_agents_id'] =
                                 $_SESSION['plugin_fusioninventory_agents_id'];
               }
               $inputrulelog['items_id'] = end($a_peripherals);
               $inputrulelog['itemtype'] = "Peripheral";
               $inputrulelog['method'] = 'inventory';
               $pfRulematchedlog->add($inputrulelog, [], false);
               $pfRulematchedlog->cleanOlddata(end($a_peripherals), "Peripheral");
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }
         }
      }
      $db_peripherals = [];
      $iterator = $DB->request([
         'SELECT'    => [
            'glpi_peripherals.id',
            'glpi_computers_items.id AS link_id'
         ],
         'FROM'      => 'glpi_computers_items',
         'LEFT JOIN' => [
            'glpi_peripherals' => [
               'FKEY' => [
                  'glpi_peripherals'      => 'id',
                  'glpi_computers_items'  => 'items_id'
               ]
            ]
         ],
         'WHERE'     => [
            'itemtype'                          => 'Peripheral',
            'computers_id'                      => $computers_id,
            'entities_id'                       => $entities_id,
            'glpi_computers_items.is_dynamic'   => 1,
            'glpi_peripherals.is_global'           => 0
         ]
      ]);

      while ($data = $iterator->next()) {
         $idtmp = $data['link_id'];
         unset($data['link_id']);
         $db_peripherals[$idtmp] = $data['id'];
      }

      if (count($db_peripherals) == 0) {
         foreach ($a_peripherals as $peripherals_id) {
            $input = [];
            $input['computers_id']   = $computers_id;
            $input['itemtype']       = 'Peripheral';
            $input['items_id']       = $peripherals_id;
            $input['is_dynamic']     = 1;
            $input['_no_history']    = $no_history;
            $this->computerItemAdd($input, $no_history);
         }
      } else {
         // Check all fields from source:
         foreach ($a_peripherals as $key => $peripherals_id) {
            foreach ($db_peripherals as $keydb => $periphs_id) {
               if ($peripherals_id == $periphs_id) {
                  unset($a_peripherals[$key]);
                  unset($db_peripherals[$keydb]);
                  break;
               }
            }
         }

         if (count($a_peripherals) || count($db_peripherals)) {
            if (count($db_peripherals) != 0) {
               // Delete peripherals links in DB
               foreach ($db_peripherals as $idtmp => $data) {
                  $computer_Item->delete(['id'=>$idtmp], 1);
               }
            }
            if (count($a_peripherals) != 0) {
               foreach ($a_peripherals as $peripherals_id) {
                  $input = [];
                  $input['computers_id']   = $computers_id;
                  $input['itemtype']       = 'Peripheral';
                  $input['items_id']       = $peripherals_id;
                  $input['is_dynamic']     = 1;
                  $input['_no_history']    = $no_history;
                  $this->computerItemAdd($input, $no_history);
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
      //            while ($data = $DB->fetchAssoc($result)) {
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

      Plugin::doHook("fusioninventory_inventory",
                     ['inventory_data' => $a_computerinventory,
                      'computers_id'   => $computers_id,
                      'no_history'     => $no_history
                     ]);

      $this->addLog();
   }


   /**
    * Manage network ports
    *
    * @global object $DB
    * @param array $inventory_networkports
    * @param integer $computers_id
    * @param boolean $no_history
    */
   function manageNetworkPort($inventory_networkports, $computers_id, $no_history) {
      global $DB;

      $networkPort = new NetworkPort();
      $networkName = new NetworkName();
      $iPAddress   = new IPAddress();
      $iPNetwork   = new IPNetwork();
      $item_DeviceNetworkCard = new Item_DeviceNetworkCard();

      foreach ($inventory_networkports as $a_networkport) {
         if ($a_networkport['mac'] != '') {
            $a_networkports = $networkPort->find(
                  ['mac'      => $a_networkport['mac'],
                   'itemtype' => 'PluginFusioninventoryUnmanaged'],
                  [], 1);
            if (count($a_networkports) > 0) {
               $input = current($a_networkports);
               $unmanageds_id = $input['items_id'];
               $input['logical_number'] = $a_networkport['logical_number'];
               $input['itemtype'] = 'Computer';
               $input['items_id'] = $computers_id;
               $input['is_dynamic'] = 1;
               $input['name'] = $a_networkport['name'];
               $networkPort->update($input, !$no_history);
               $pfUnmanaged = new PluginFusioninventoryUnmanaged();
               $pfUnmanaged->delete(['id'=>$unmanageds_id], 1);
            }
         }
      }
      // end get port from unknown device

      $db_networkport = [];
      $iterator = $DB->request([
         'SELECT' => ['id', 'name', 'mac', 'instantiation_type', 'logical_number'],
         'FROM'   => 'glpi_networkports',
         'WHERE'  => [
            'items_id'     => $computers_id,
            'itemtype'     => 'Computer',
            'is_dynamic'   => 1
         ]
      ]);
      while ($data = $iterator->next()) {
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
      $simplenetworkport = [];
      foreach ($inventory_networkports as $key=>$a_networkport) {
         // Add ipnetwork if not exist
         if ($a_networkport['gateway'] != ''
                 && $a_networkport['netmask'] != ''
                 && $a_networkport['subnet']  != '') {
            if (countElementsInTable('glpi_ipnetworks',
                  [
                     'address'     => $a_networkport['subnet'],
                     'netmask'     => $a_networkport['netmask'],
                     'gateway'     => $a_networkport['gateway'],
                     'entities_id' => $_SESSION["plugin_fusioninventory_entity"],
                  ]) == 0) {

               $input_ipanetwork = [
                   'name'    => $a_networkport['subnet'].'/'.
                                $a_networkport['netmask'].' - '.
                                $a_networkport['gateway'],
                   'network' => $a_networkport['subnet'].' / '.
                                $a_networkport['netmask'],
                   'gateway' => $a_networkport['gateway'],
                   'entities_id' => $_SESSION["plugin_fusioninventory_entity"]
               ];
               $iPNetwork->add($input_ipanetwork, [], !$no_history);
            }
         }

         // End add ipnetwork
         $a_field = ['name', 'mac', 'instantiation_type'];
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
                  $input = [];
                  $input['id'] = $keydb;
                  $input['logical_number'] = $inventory_networkports[$key]['logical_number'];
                  $networkPort->update($input, !$no_history);
               }

               // Add / update instantiation_type
               if (isset($inventory_networkports[$key]['instantiation_type'])) {
                  $instantiation_type = $inventory_networkports[$key]['instantiation_type'];
                  if (in_array($instantiation_type, ['NetworkPortEthernet',
                                                          'NetworkPortFiberchannel'])) {

                     $instance = new $instantiation_type;
                     $portsinstance = $instance->find(['networkports_id' => $keydb], [], 1);
                     if (count($portsinstance) == 1) {
                        $portinstance = current($portsinstance);
                        $input = $portinstance;
                     } else {
                        $input = [
                           'networkports_id' => $keydb
                        ];
                     }

                     if (isset($inventory_networkports[$key]['speed'])) {
                        $input['speed'] = $inventory_networkports[$key]['speed'];
                        $input['speed_other_value'] = $inventory_networkports[$key]['speed'];
                     }
                     if (isset($inventory_networkports[$key]['wwn'])) {
                        $input['wwn'] = $inventory_networkports[$key]['wwn'];
                     }
                     if (isset($inventory_networkports[$key]['mac'])) {
                        $networkcards = $item_DeviceNetworkCard->find(
                                ['mac'      => $inventory_networkports[$key]['mac'],
                                 'itemtype' => 'Computer',
                                 'items_id' => $computers_id],
                                [], 1);
                        if (count($networkcards) == 1) {
                           $networkcard = current($networkcards);
                           $input['items_devicenetworkcards_id'] = $networkcard['id'];
                        }
                     }
                     $input['_no_history'] = $no_history;
                     if (isset($input['id'])) {
                        $instance->update($input);
                     } else {
                        $instance->add($input);
                     }
                  }
               }

               // Get networkname
               $a_networknames_find = current($networkName->find(
                     ['items_id' => $keydb,
                      'itemtype' => 'NetworkPort'],
                     [], 1));
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
                  $a_networknames_id = $networkName->add($a_networkport, [], !$no_history);
                  $a_networknames_find['id'] = $a_networknames_id;
               }

               // Same networkport, verify ipaddresses
               $db_addresses = [];
               $iterator = $DB->request([
                  'SELECT' => ['id', 'name'],
                  'FROM'   => 'glpi_ipaddresses',
                  'WHERE'  => [
                     'items_id'  => $a_networknames_find['id'],
                     'itemtype'  => 'NetworkName'
                  ]
               ]);
               while ($data = $iterator->next()) {
                  $db_addresses[$data['id']] = $data['name'];
               }
               $a_computerinventory_ipaddress =
                           $inventory_networkports[$key]['ipaddress'];
               $nb_ip = count($a_computerinventory_ipaddress);
               foreach ($a_computerinventory_ipaddress as $key2 => $arrays2) {
                  foreach ($db_addresses as $keydb2 => $arraydb2) {
                     if ($arrays2 == $arraydb2) {
                        unset($a_computerinventory_ipaddress[$key2]);
                        unset($db_addresses[$keydb2]);
                        break;
                     }
                  }
               }
               if (count($a_computerinventory_ipaddress) || count($db_addresses)) {
                  if (count($db_addresses) != 0 AND $nb_ip > 0) {
                     // Delete ip address in DB
                     foreach (array_keys($db_addresses) as $idtmp) {
                        $iPAddress->delete(['id'=>$idtmp], 1);
                     }
                  }
                  if (count($a_computerinventory_ipaddress) != 0) {
                     foreach ($a_computerinventory_ipaddress as $ip) {
                        $input = [];
                        $input['items_id']   = $a_networknames_find['id'];
                        $input['itemtype']   = 'NetworkName';
                        $input['name']       = $ip;
                        $input['is_dynamic'] = 1;
                        $iPAddress->add($input, [], !$no_history);
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
         $coding_std = true;
      } else {
         if (count($db_networkport) != 0) {
            // Delete networkport in DB
            foreach ($db_networkport as $idtmp => $data) {
               $networkPort->delete(['id'=>$idtmp], 1);
            }
         }
         if (count($inventory_networkports) != 0) {
            foreach ($inventory_networkports as $a_networkport) {
               $a_networkport['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
               $a_networkport['items_id'] = $computers_id;
               $a_networkport['itemtype'] = "Computer";
               $a_networkport['is_dynamic'] = 1;
               $a_networkport['_no_history'] = $no_history;
               $a_networkport['items_id'] = $networkPort->add($a_networkport, [], !$no_history);
               unset($a_networkport['_no_history']);
               $a_networkport['is_recursive'] = 0;
               $a_networkport['itemtype'] = 'NetworkPort';
               unset($a_networkport['name']);
               $a_networkport['_no_history'] = $no_history;
               $a_networknames_id = $networkName->add($a_networkport, [], !$no_history);
               foreach ($a_networkport['ipaddress'] as $ip) {
                  $input = [];
                  $input['items_id']   = $a_networknames_id;
                  $input['itemtype']   = 'NetworkName';
                  $input['name']       = $ip;
                  $input['is_dynamic'] = 1;
                  $input['_no_history'] = $no_history;
                  $iPAddress->add($input, [], !$no_history);
               }
               if (isset($a_networkport['instantiation_type'])) {
                  $instantiation_type = $a_networkport['instantiation_type'];
                  if (in_array($instantiation_type, ['NetworkPortEthernet',
                                                          'NetworkPortFiberchannel'])) {
                     $instance = new $instantiation_type;
                     $input = [
                        'networkports_id' => $a_networkport['items_id']
                     ];
                     if (isset($a_networkport['speed'])) {
                        $input['speed'] = $a_networkport['speed'];
                        $input['speed_other_value'] = $a_networkport['speed'];
                     }
                     if (isset($a_networkport['wwn'])) {
                        $input['wwn'] = $a_networkport['wwn'];
                     }
                     if (isset($a_networkport['mac'])) {
                        $networkcards = $item_DeviceNetworkCard->find(
                                ['mac'      => $a_networkport['mac'],
                                 'itemtype' => 'Computer',
                                 'items_id' => $computers_id],
                                [], 1);
                        if (count($networkcards) == 1) {
                           $networkcard = current($networkcards);
                           $input['items_devicenetworkcards_id'] = $networkcard['id'];
                        }
                     }
                     $input['_no_history'] = $no_history;
                     $instance->add($input);
                  }
               }
            }
         }
      }
   }


   /**
    * Add a new bios component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
    */
   function addBios($data, $computers_id, $no_history) {
      $item_DeviceBios  = new Item_DeviceFirmware();
      $deviceBios       = new DeviceFirmware();

      $fwTypes = new DeviceFirmwareType();
      $fwTypes->getFromDBByCrit([
         'name' => 'BIOS'
      ]);
      $type_id = $fwTypes->getID();
      $data['devicefirmwaretypes_id'] = $type_id;

      $bios_id = $deviceBios->import($data);
      $data['devicefirmwares_id']   = $bios_id;
      $data['itemtype']             = 'Computer';
      $data['items_id']             = $computers_id;
      $data['is_dynamic']           = 1;
      $data['_no_history']          = $no_history;
      $item_DeviceBios->add($data, [], !$no_history);
   }


   /**
    * Add a new processor component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
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
      $item_DeviceProcessor->add($data, [], !$no_history);
   }


   /**
    * Add a new memory component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
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
      $item_DeviceMemory->add($data, [], !$no_history);
   }


   /**
    * Add a new hard disk component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
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
      $item_DeviceHardDrive->add($data, [], !$no_history);
   }


   /**
    * Add a new drive component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
    */
   function addDrive($data, $computers_id, $no_history) {
      $item_DeviceDrive         = new Item_DeviceDrive();
      $deviceDrive              = new DeviceDrive();

      $drives_id = $deviceDrive->import($data);
      $data['devicedrives_id']      = $drives_id;
      $data['itemtype']             = 'Computer';
      $data['items_id']             = $computers_id;
      $data['is_dynamic']           = 1;
      $data['_no_history']          = $no_history;
      $item_DeviceDrive->add($data, [], !$no_history);
   }


   /**
    * Add a new graphic card component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
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
      $item_DeviceGraphicCard->add($data, [], !$no_history);
   }


   /**
    * Add a new network card component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
    */
   function addNetworkCard($data, $computers_id, $no_history) {
      $item_DeviceNetworkCard       = new Item_DeviceNetworkCard();
      $deviceNetworkCard            = new DeviceNetworkCard();

      $networkcards_id = $deviceNetworkCard->add($data);
      $data['devicenetworkcards_id']   = $networkcards_id;
      $data['itemtype']                = 'Computer';
      $data['items_id']                = $computers_id;
      $data['is_dynamic']              = 1;
      $data['_no_history']             = $no_history;
      $item_DeviceNetworkCard->add($data, [], !$no_history);
   }


   /**
    * Add a new sound card component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
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
      $item_DeviceSoundCard->add($data, [], !$no_history);
   }


   /**
    * Add a new controller component
    *
    * @param array $data
    * @param integer $computers_id
    * @param boolean $no_history
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
      $item_DeviceControl->add($data, [], !$no_history);
   }


   /**
    * Add a new battery component
    *
    * @param array   $data
    * @param integer $computers_id
    * @param boolean $no_history
    */
   function addBattery($data, $computers_id, $no_history) {
      $item_DeviceBattery  = new Item_DeviceBattery();
      $deviceBattery       = new DeviceBattery();

      if (!isset($data['voltage']) || $data['voltage'] == '') {
         //a numeric value is expected here
         $data['voltage'] = 0;
      }

      if (empty($data['designation'])) {
         //Placebo designation; sometimes missing from agent
         $data['designation'] = __('Internal battery', 'fusioninventory');
      }

      $batteries_id = $deviceBattery->import($data);
      $data['devicebatteries_id'] = $batteries_id;
      $data['itemtype']           = 'Computer';
      $data['items_id']           = $computers_id;
      $data['is_dynamic']         = 1;
      $data['_no_history']        = $no_history;
      $item_DeviceBattery->add($data, [], !$no_history);
   }


   /**
    * Load softwares from database that are matching softwares coming from the
    * currently processed inventory
    *
    * @global object $DB
    * @param integer $entities_id entitity id
    * @param array $a_soft list of software from the agent inventory
    * @param integer $lastid last id search to not search from beginning
    * @return integer last software id
    */
   function loadSoftwares($entities_id, $a_soft, $lastid = 0) {
      global $DB;

      $whereid = '';
      if ($lastid > 0) {
         $whereid .= ' AND `id` > "'.$lastid.'"';
      }
      $a_softSearch = [];
      $nbSoft = 0;
      if (count($this->softList) == 0) {
         foreach ($a_soft as $a_software) {
            $a_softSearch[] = "'".$a_software['name']."$$$$".$a_software['manufacturers_id']."'";
            $nbSoft++;
         }
      } else {
         foreach ($a_soft as $a_software) {
            if (!isset($this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']])) {
               $a_softSearch[] = "'".$a_software['name']."$$$$".$a_software['manufacturers_id']."'";
               $nbSoft++;
            }
         }
      }
      $whereid .= " AND CONCAT_WS('$$$$', `name`, `manufacturers_id`) IN (".implode(",", $a_softSearch).")";

      $sql     = "SELECT max( id ) AS max FROM `glpi_softwares`";
      $result  = $DB->query($sql);
      $data    = $DB->fetchAssoc($result);
      $lastid  = $data['max'];
      $whereid.= " AND `id` <= '".$lastid."'";
      if ($nbSoft == 0) {
         return $lastid;
      }

      $sql = "SELECT `id`, `name`, `manufacturers_id`
              FROM `glpi_softwares`
              WHERE `entities_id`='".$entities_id."'".$whereid;
      foreach ($DB->request($sql) as $data) {
         $this->softList[Toolbox::addslashes_deep($data['name'])."$$$$".$data['manufacturers_id']] = $data['id'];
      }
      return $lastid;
   }


   /**
    * Load software versions from DB are in the incomming inventory
    *
    * @global object $DB
    * @param integer $entities_id entitity id
    * @param array $a_softVersion list of software versions from the agent inventory
    * @param integer $lastid last id search to not search from beginning
    * @return integer last software version id
    */
   function loadSoftwareVersions($entities_id, $a_softVersion, $lastid = 0) {
      global $DB;

      $whereid = '';
      if ($lastid > 0) {
         $whereid .= ' AND `id` > "'.$lastid.'"';
      }
      $arr = [];
      $a_versions = [];
      foreach ($a_softVersion as $a_software) {
         $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
         if (!isset($this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id."$$$$".$a_software['operatingsystems_id']])) {
            $a_versions[$a_software['version']][] = $softwares_id;
         }
      }

      $nbVersions = 0;
      foreach ($a_versions as $name=>$a_softwares_id) {
         foreach ($a_softwares_id as $softwares_id) {
            $arr[] = "'".$name."$$$$".$softwares_id."$$$$".$a_software['operatingsystems_id']."'";
         }
         $nbVersions++;
      }
      $whereid .= " AND CONCAT_WS('$$$$', `name`, `softwares_id`, `operatingsystems_id`) IN ( ";
      $whereid .= implode(',', $arr);
      $whereid .= " ) ";

      $sql = "SELECT max( id ) AS max FROM `glpi_softwareversions`";
      $result = $DB->query($sql);
      $data = $DB->fetchAssoc($result);
      $lastid = $data['max'];
      $whereid .= " AND `id` <= '".$lastid."'";

      if ($nbVersions == 0) {
         return $lastid;
      }

      $sql = "SELECT `id`, `name`, `softwares_id`, `operatingsystems_id` FROM `glpi_softwareversions`
      WHERE `entities_id`='".$entities_id."'".$whereid;
      $result = $DB->query($sql);
      while ($data = $DB->fetchAssoc($result)) {
         $this->softVersionList[strtolower($data['name'])."$$$$".$data['softwares_id']."$$$$".$data['operatingsystems_id']] = $data['id'];
      }

      return $lastid;
   }


   /**
    * Add a new software
    *
    * @param array $a_software
    * @param array $options
    */
   function addSoftware($a_software, $options) {
      $a_softwares_id = $this->software->add($a_software, $options, false);
      $this->addPrepareLog($a_softwares_id, 'Software');

      $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']] = $a_softwares_id;
   }


   /**
    * Add a software version
    *
    * @param array $a_software
    * @param integer $softwares_id
    * @param boolean $no_history
    */
   function addSoftwareVersion($a_software, $softwares_id, $no_history) {
      $options = [];
      $options['disable_unicity_check'] = true;

      $a_software['name']         = $a_software['version'];
      $a_software['softwares_id'] = $softwares_id;
      $a_software['_no_history']  = true;
      $softwareversions_id = $this->softwareVersion->add($a_software, $options, false);
      $this->addPrepareLog($softwareversions_id, 'SoftwareVersion');
      $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id."$$$$".$a_software['operatingsystems_id']] = $softwareversions_id;
   }


   /**
    * Link software versions with the computer
    *
    * @global object $DB
    * @param array $a_input
    */
   function addSoftwareVersionsComputer($a_input) {
      global $DB;

      $insert_query = $DB->buildInsert(
         'glpi_items_softwareversions', [
            'itemtype'              => 'Computer',
            'items_id'              => new \QueryParam(),
            'softwareversions_id'   => new \QueryParam(),
            'is_dynamic'            => new \QueryParam(),
            'entities_id'           => new \QueryParam(),
            'date_install'          => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($insert_query);

      foreach ($a_input as $input) {
         $stmt->bind_param(
            'sssss',
            $input['items_id'],
            $input['softwareversions_id'],
            $input['is_dynamic'],
            $input['entities_id'],
            $input['date_install']
         );
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }


   /**
    * Link software version with the computer
    *
    * @param array $a_software
    * @param integer $computers_id
    * @param boolean $no_history
    * @param array $options
    */
   function addSoftwareVersionComputer($a_software, $computers_id, $no_history, $options) {

      $options['disable_unicity_check'] = true;

      $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
      $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id."$$$$".$a_software['operatingsystems_id']];

      $this->softwareVersion->getFromDB($softwareversions_id);
      $a_software['itemtype']             = 'Computer';
      $a_software['items_id']             = $computers_id;
      $a_software['softwareversions_id']  = $softwareversions_id;
      $a_software['is_dynamic']           = 1;
      $a_software['is_template_item'] = false;
      $a_software['is_deleted_item']  = false;
      $a_software['_no_history']          = true;
      $a_software['entities_id']          = $computers_id['entities_id'];

      //Check if historical has been disabled for this software only
      $comp_key = strtolower($a_software['name']).
                   PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.strtolower($a_software['version']).
                   PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['manufacturers_id'].
                   PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['entities_id'].
                   PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['operatingsystems_id'];
      if (isset($a_software['no_history']) && $a_software['no_history']) {
         $no_history_for_this_software = true;
      } else {
         $no_history_for_this_software = false;
      }

      if ($this->computerSoftwareVersion->add($a_software, $options, false)) {
         if (!$no_history && !$no_history_for_this_software) {
            $changes[0] = '0';
            $changes[1] = "";
            $changes[2] = addslashes($this->computerSoftwareVersion->getHistoryNameForItem1($this->softwareVersion, 'add'));
            $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                         Log::HISTORY_INSTALL_SOFTWARE);

            $changes[0] = '0';
            $changes[1] = "";
            $changes[2] = addslashes($this->computerSoftwareVersion->getHistoryNameForItem2($this->computer, 'add'));
            $this->addPrepareLog($softwareversions_id, 'SoftwareVersion', 'Computer', $changes,
                         Log::HISTORY_INSTALL_SOFTWARE);
         }
      }
   }


   /**
    * Arraydiff function to have real diff between 2 arrays
    *
    * @param array $arrayFrom
    * @param array $arrayAgainst
    * @return array
    */
   function arrayDiffEmulation($arrayFrom, $arrayAgainst) {
      $arrayAgainsttmp = [];
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


   /**
    * Prepare add history in database
    *
    * @param integer $items_id
    * @param string $itemtype
    * @param string $itemtype_link
    * @param array $changes
    * @param integer $linked_action
    */
   function addPrepareLog($items_id, $itemtype, $itemtype_link = '', $changes = ['0', '', ''], $linked_action = Log::HISTORY_CREATE_ITEM) {
      $this->log_add[] = [$items_id, $itemtype, $itemtype_link, $_SESSION["glpi_currenttime"], $changes, $linked_action];
   }


   /**
    * Insert logs are in queue
    *
    * @global object $DB
    */
   function addLog() {
      global $DB;

      if (count($this->log_add) > 0) {
         $qparam = new \QueryParam();
         $stmt = $DB->prepare(
            $DB->buildInsert(
               'glpi_logs', [
                  'items_id'           => $qparam,
                  'itemtype'           => $qparam,
                  'itemtype_link'      => $qparam,
                  'date_mod'           => $qparam,
                  'linked_action'      => $qparam,
                  'id_search_option'   => $qparam,
                  'old_value'          => $qparam,
                  'new_value'          => $qparam,
                  'user_name'          => $qparam
               ]
            )
         );
         $username = addslashes($_SESSION["glpiname"]);

         foreach ($this->log_add as $data) {
            $changes = $data[4];
            unset($data[4]);
            $data = array_values($data);
            $id_search_option = $changes[0];
            $old_value = $changes[1];
            $new_value = $changes[2];

            $stmt->bind_param(
               'sssssssss',
               $data[0],
               $data[1],
               $data[2],
               $data[3],
               $data[4],
               $id_search_option,
               $old_value,
               $new_value,
               $username
            );
            $stmt->execute();
         }

         $this->log_add = [];
      }
   }


   /**
    * Define items link to computer in dynamic mode
    *
    * @global object $DB
    * @param integer $computers_id
    */
   function setDynamicLinkItems($computers_id) {
      global $DB;

      $computer = new Computer();
      $input = ['id' => $computers_id];
      $input = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('computer', $input);
      $computer->update($input);

      $a_tables = [
         'glpi_computers_items',
         'glpi_computervirtualmachines'
      ];
      foreach ($a_tables as $table) {
         $DB->update(
            $table, [
               'is_dynamic' => 1
            ], [
               'computers_id' => $computers_id
            ]
         );
      }

      $DB->update(
         'glpi_items_softwareversions', [
            'is_dynamic' => 1
         ], [
            'itemtype'  => 'Computer',
            'items_id'  => $computers_id
         ]
      );

      $a_tables = ["glpi_networkports", "glpi_items_devicecases", "glpi_items_devicecontrols",
                   "glpi_items_devicedrives", "glpi_items_devicegraphiccards",
                   "glpi_items_deviceharddrives", "glpi_items_devicememories",
                   "glpi_items_devicemotherboards", "glpi_items_devicenetworkcards",
                   "glpi_items_devicepcis", "glpi_items_devicepowersupplies",
                   "glpi_items_deviceprocessors", "glpi_items_devicesoundcards",
                   'glpi_items_disks'
      ];

      foreach ($a_tables as $table) {
         $DB->update(
            $table, [
               'is_dynamic'   => 1,
            ], [
               'itemtype'  => 'Computer',
               'items_id'  => $computers_id
            ]
         );
      }
   }

   /**
    * Import software
    * @since 9.2+2.0
    *
    * @param string $itemtype the itemtype to be inventoried
    * @param array   $a_inventory Inventory data
    * @param integer asset id
    *
    * @return void
    */
   function importSoftware($itemtype, $a_inventory, $computer, $no_history) {
      global $DB;

      //By default entity  = root
      $entities_id  = 0;
      $computers_id = $computer->getID();

      //Try to guess the entity of the software
      if (count($a_inventory['software']) > 0) {
         //Get the first software of the list
         $a_softfirst = current($a_inventory['software']);
         //Get the entity of the first software : this info has been processed
         //in formatconvert, so it's either the computer's entity or
         //the entity as defined in the entity's configuration
         if (isset($a_softfirst['entities_id'])) {
            $entities_id = $a_softfirst['entities_id'];
         }
      }
      $db_software = [];

      //If we must take care of historical : it means we're not :
      //- at computer first inventory
      //- during the first inventory after an OS upgrade/change
      if ($no_history === false) {
         $query = "SELECT `glpi_items_softwareversions`.`id` as sid,
                    `glpi_softwares`.`name`,
                    `glpi_softwareversions`.`name` AS version,
                    `glpi_softwares`.`manufacturers_id`,
                    `glpi_softwareversions`.`entities_id`,
                    `glpi_softwareversions`.`operatingsystems_id`,
                    `glpi_items_softwareversions`.`is_template_item`,
                    `glpi_items_softwareversions`.`is_deleted_item`
             FROM `glpi_items_softwareversions`
             LEFT JOIN `glpi_softwareversions`
                  ON (`glpi_items_softwareversions`.`softwareversions_id`
                        = `glpi_softwareversions`.`id`)
             LEFT JOIN `glpi_softwares`
                  ON (`glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`)
             WHERE `glpi_items_softwareversions`.`items_id` = '$computers_id'
               AND glpi_items_softwareversions.itemtype = 'Computer' AND `glpi_items_softwareversions`.`is_dynamic`='1'";
         foreach ($DB->request($query) as $data) {
            $idtmp = $data['sid'];
            unset($data['sid']);
            //Escape software name if needed
            if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $data['name'])) {
               $data['name'] = Toolbox::addslashes_deep($data['name']);
            }
            //Escape software version if needed
            if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $data['version'])) {
               $data['version'] = Toolbox::addslashes_deep($data['version']);
            }
            $comp_key = strtolower($data['name']).
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.strtolower($data['version']).
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$data['manufacturers_id'].
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$data['entities_id'].
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$data['operatingsystems_id'];
            $db_software[$comp_key] = $idtmp;
         }
      }

      $lastSoftwareid  = 0;
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

      $dbLock = new PluginFusioninventoryDBLock();

      if (count($db_software) == 0) { // there are no software associated with computer
         $nb_unicity = count(FieldUnicity::getUnicityFieldsConfig("Software", $entities_id));
         $options    = [];
         //There's no unicity rules, do not enable this feature
         if ($nb_unicity == 0) {
            $options['disable_unicity_check'] = true;
         }

         $lastSoftwareid = $this->loadSoftwares($entities_id,
                                                $a_inventory['software'],
                                                $lastSoftwareid);

         //-----------------------------------
         //Step 1 : import softwares
         //-----------------------------------
         //Put a lock during software import for this computer
         $dbLock->setLock('softwares');
         $this->loadSoftwares($entities_id,
                              $a_inventory['software'],
                              $lastSoftwareid);

         //Browse softwares: add new software in database
         foreach ($a_inventory['software'] as $a_software) {
            if (!isset($this->softList[$a_software['name']."$$$$".
                     $a_software['manufacturers_id']])) {
               $this->addSoftware($a_software, $options);
            }
         }
         $dbLock->releaseLock('softwares');

         //-----------------------------------
         //Step 2 : import software versions
         //-----------------------------------
         $lastSoftwareVid = $this->loadSoftwareVersions($entities_id,
                                        $a_inventory['software'],
                                        $lastSoftwareVid);
         $dbLock->setLock('softwareversions');
         $this->loadSoftwareVersions($entities_id,
                                     $a_inventory['software'],
                                     $lastSoftwareVid);
         foreach ($a_inventory['software'] as $a_software) {
            $softwares_id = $this->softList[$a_software['name']
               .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['manufacturers_id']];
            if (!isset($this->softVersionList[strtolower($a_software['version'])
            .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$softwares_id
            .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['operatingsystems_id']])) {
               $this->addSoftwareVersion($a_software, $softwares_id, $no_history);
            }
         }
         $dbLock->releaseLock('softwareversions');

         $a_toinsert = [];
         foreach ($a_inventory['software'] as $a_software) {
            $softwares_id = $this->softList[$a_software['name']
               .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['manufacturers_id']];
            $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])
               .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$softwares_id
               .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['operatingsystems_id']];
            $a_tmp = [
               'itemtype'            => 'Computer',
               'items_id'            => $computers_id,
               'softwareversions_id' => $softwareversions_id,
               'is_dynamic'          => 1,
               'entities_id'         => $computer->fields['entities_id'],
               'date_install'        => null
            ];
            //By default date_install is null: if an install date is provided,
            //we set it
            if (isset($a_software['date_install'])) {
               $a_tmp['date_install'] = $a_software['date_install'];
            }
            $a_toinsert[] = $a_tmp;
         }
         if (count($a_toinsert) > 0) {
            $this->addSoftwareVersionsComputer($a_toinsert);

            //Check if historical has been disabled for this software only
            $comp_key = strtolower($a_software['name']).
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.strtolower($a_software['version']).
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['manufacturers_id'].
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['entities_id'].
                         PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['operatingsystems_id'];
            if (isset($a_software['no_history']) && $a_software['no_history']) {
               $no_history_for_this_software = true;
            } else {
               $no_history_for_this_software = false;
            }

            if (!$no_history && !$no_history_for_this_software) {
               foreach ($a_inventory['software'] as $a_software) {
                  $softwares_id = $this->softList[$a_software['name']
                     .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['manufacturers_id']];
                  $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])
                     .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$softwares_id
                     .PluginFusioninventoryFormatconvert::FI_SOFTWARE_SEPARATOR.$a_software['operatingsystems_id']];

                  $changes[0] = '0';
                  $changes[1] = "";
                  $changes[2] = $a_software['name']." - ".
                          sprintf(__('%1$s (%2$s)'), $a_software['version'], $softwareversions_id);
                  $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                               Log::HISTORY_INSTALL_SOFTWARE);

                  $changes[0] = '0';
                  $changes[1] = "";
                  $changes[2] = sprintf(__('%1$s (%2$s)'), $computer->getName(), $computers_id);
                  $this->addPrepareLog($softwareversions_id, 'SoftwareVersion', 'Computer', $changes,
                               Log::HISTORY_INSTALL_SOFTWARE);
               }
            }
         }
      } else {
         //It's not the first inventory, or not an OS change/upgrade

         //Do software migration first if needed
         $a_inventory = $this->migratePlatformForVersion($a_inventory, $db_software);

         //If software exists in DB, do not process it
         foreach ($a_inventory['software'] as $key => $arrayslower) {
            //Software installation already exists for this computer ?
            if (isset($db_software[$key])) {
               //It exists: remove the software from the key
               unset($a_inventory['software'][$key]);
               unset($db_software[$key]);
            }
         }

         if (count($a_inventory['software']) > 0
            || count($db_software) > 0) {
            if (count($db_software) > 0) {
               // Delete softwares in DB
               foreach ($db_software as $idtmp) {

                  if (isset($this->installationWithoutLogs[$idtmp])) {
                     $no_history_for_this_software = true;
                  } else {
                     $no_history_for_this_software = false;
                  }
                  $this->computerSoftwareVersion->getFromDB($idtmp);
                  $this->softwareVersion->getFromDB($this->computerSoftwareVersion->fields['softwareversions_id']);

                  if (!$no_history && !$no_history_for_this_software) {
                     $changes[0] = '0';
                     $changes[1] = addslashes($this->computerSoftwareVersion->getHistoryNameForItem1($this->softwareVersion, 'delete'));
                     $changes[2] = "";
                     $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                                  Log::HISTORY_UNINSTALL_SOFTWARE);

                     $changes[0] = '0';
                     $changes[1] = sprintf(__('%1$s (%2$s)'), $computer->getName(), $computers_id);
                     $changes[2] = "";
                     $this->addPrepareLog($idtmp, 'SoftwareVersion', 'Computer', $changes,
                                  Log::HISTORY_UNINSTALL_SOFTWARE);
                  }
               }
               $DB->delete(
                  'glpi_items_softwareversions', [
                  'id' => $db_software
                  ]
               );
            }
            if (count($a_inventory['software']) > 0) {
               $nb_unicity = count(FieldUnicity::getUnicityFieldsConfig("Software",
                                                                        $entities_id));
               $options = [];
               if ($nb_unicity == 0) {
                  $options['disable_unicity_check'] = true;
               }
               $lastSoftwareid = $this->loadSoftwares($entities_id, $a_inventory['software'], $lastSoftwareid);

               $dbLock->setLock('softwares');
               $this->loadSoftwares($entities_id, $a_inventory['software'], $lastSoftwareid);
               foreach ($a_inventory['software'] as $a_software) {
                  if (!isset($this->softList[$a_software['name']."$$$$".
                           $a_software['manufacturers_id']])) {
                     $this->addSoftware($a_software,
                                        $options);
                  }
               }
               $dbLock->releaseLock('softwares');

               $lastSoftwareVid = $this->loadSoftwareVersions($entities_id,
                                              $a_inventory['software'],
                                              $lastSoftwareVid);
               $dbLock->setLock('softwareversions');
               $this->loadSoftwareVersions($entities_id,
                                           $a_inventory['software'],
                                           $lastSoftwareVid);
               foreach ($a_inventory['software'] as $a_software) {
                  $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                  if (!isset($this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id."$$$$".$a_software['operatingsystems_id']])) {
                     $this->addSoftwareVersion($a_software, $softwares_id, $no_history);
                  }
               }
               $dbLock->releaseLock('softwareversions');

               $a_toinsert = [];
               foreach ($a_inventory['software'] as $key => $a_software) {
                  //Check if historical has been disabled for this software only
                  if (isset($a_software['no_history']) && $a_software['no_history']) {
                     $no_history_for_this_software = true;
                  } else {
                     $no_history_for_this_software = false;
                  }
                  $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                  $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id."$$$$".$a_software['operatingsystems_id']];
                  $a_tmp = [
                     'itemtype'            => 'Computer',
                     'items_id'            => $computers_id,
                     'softwareversions_id' => $softwareversions_id,
                     'is_dynamic'          => 1,
                     'entities_id'         => $computer->fields['entities_id'],
                     'date_install'        => 'NULL'
                  ];
                  if (isset($a_software['date_install'])) {
                     $a_tmp['date_install'] = $a_software['date_install'];
                  }
                  $a_toinsert[] = $a_tmp;
               }
               $this->addSoftwareVersionsComputer($a_toinsert);

               if (!$no_history && !$no_history_for_this_software) {
                  foreach ($a_inventory['software'] as $a_software) {
                     $softwares_id = $this->softList[$a_software['name']."$$$$".$a_software['manufacturers_id']];
                     $softwareversions_id = $this->softVersionList[strtolower($a_software['version'])."$$$$".$softwares_id."$$$$".$a_software['operatingsystems_id']];

                     $changes[0] = '0';
                     $changes[1] = "";
                     $changes[2] = $a_software['name']." - ".
                           sprintf(__('%1$s (%2$s)'), $a_software['version'], $softwareversions_id);
                     $this->addPrepareLog($computers_id, 'Computer', 'SoftwareVersion', $changes,
                                  Log::HISTORY_INSTALL_SOFTWARE);

                     $changes[0] = '0';
                     $changes[1] = "";
                     $changes[2] = sprintf(__('%1$s (%2$s)'), $computer->getName(), $computers_id);
                     $this->addPrepareLog($softwareversions_id, 'SoftwareVersion', 'Computer', $changes,
                                  Log::HISTORY_INSTALL_SOFTWARE);
                  }
               }
            }
         }
      }
   }

   /**
   * Migration software versions without OS
   * Before 0.90, no OS was added to an installation...
   *
   * @since 9.2+.20
   *
   * @param array $a_inventory the incoming inventory as an array
   * @param array $db_inventory the software inventory by reading GLPI db
   * @return array the incoming inventory modified if needed
   */
   function migratePlatformForVersion($a_inventory, $db_inventory) {
      //Browse each software in the inventory sent by an agent
      foreach ($a_inventory['software'] as $key => $software) {
         //Check if the installation exists without platform (OS)
         //if it is the case, then add the new and old installation
         //to the list of installation/uninstallation that must not be logged
         if (isset($software['comp_key_noos']) && isset($db_inventory[$software['comp_key_noos']])) {
            //This array is used during version uninstallation
            $this->installationWithoutLogs[] = $software['comp_key_noos'];
            //This boolean is used for software version installation
            $software['no_history']          = true;
            //Software the modified array in the incoming inventory
            $a_inventory['software'][$key]   = $software;
         }
      }
      return $a_inventory;
   }

   // For monitors, printers... import
   function rulepassed($items_id, $itemtype, $ports_id = 0) {
      return true;
   }

   /**
    * Manage attach a device (monitor, printer....) to computer
    */
   function computerItemAdd($input, $no_history) {
      $computer_Item = new Computer_Item();

      // Check if the device is yet connected to another computer

      $computer_Item->getFromDBByCrit([
         'itemtype' => $input['itemtype'],
         'items_id' => $input['items_id']
      ]);
      if (isset($computer_Item->fields['id'])) {
         $computer_Item->delete(['id' => $computer_Item->fields['id']], true);
      }
      $computer_Item->add($input, [], !$no_history);
   }
}
