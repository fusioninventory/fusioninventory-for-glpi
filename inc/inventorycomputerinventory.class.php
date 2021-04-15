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
 * This file is used to manage the import of computer inventory.
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
 * Manage the import of computer inventory.
 */
class PluginFusioninventoryInventoryComputerInventory {

   /**
    * Initialize the array of inventory
    *
    * @var array
    */
   private $arrayinventory = [];

   /**
    * initialize the device_id of the agent
    *
    * @var string
    */
   private $device_id = '';


   /**
    * import data
    *
    * @global object $DB
    * @global array $CFG_GLPI
    * @param string $p_DEVICEID
    * @param array $a_CONTENT
    * @param array $arrayinventory
    * @return string errors
    */
   function import($p_DEVICEID, $a_CONTENT, $arrayinventory) {
      global $DB, $CFG_GLPI;

      $errors = '';
      $_SESSION["plugin_fusioninventory_entity"] = -1;

      // Prevent 2 computers with same name (Case of have the computer inventory 2 times in same time
      // and so we don't want it create 2 computers instead one)
      $name = '';
      if (isset($arrayinventory['CONTENT']['HARDWARE']['NAME'])) {
         $name = strtolower($arrayinventory['CONTENT']['HARDWARE']['NAME']);
      }

      $dbLock = new PluginFusioninventoryDBLock();
      //Clean all locks lasting more than 10 minutes
      $dbLock->releaseAllLocks();

      if (!$dbLock->setLock('inventorynames', $name, true)) {
         exit();
      }
      //$CFG_GLPI["use_log_in_files"] = TRUE;
      $this->sendCriteria($p_DEVICEID, $arrayinventory);
      $DB->delete(
         'glpi_plugin_fusioninventory_dblockinventorynames', [
            'value' => $name
         ]
      );

      return $errors;
   }


   /**
    * Send Computer to inventoryruleimport
    *
    * @param string $p_DEVICEID
    * @param array $arrayinventory
    */
   function sendCriteria($p_DEVICEID, $arrayinventory) {

      if (isset($_SESSION['plugin_fusioninventory_entityrestrict'])) {
         unset($_SESSION['plugin_fusioninventory_entityrestrict']);
      }

      $this->device_id = $p_DEVICEID;
      // * Hacks

         // Hack to put OS in software
      if (isset($arrayinventory['CONTENT']['HARDWARE']['OSNAME'])) {
         $inputos = [];
         if (isset($arrayinventory['CONTENT']['HARDWARE']['OSCOMMENTS'])) {
            $inputos['COMMENTS'] = $arrayinventory['CONTENT']['HARDWARE']['OSCOMMENTS'];
         }
         $inputos['NAME']     = $arrayinventory['CONTENT']['HARDWARE']['OSNAME'];
         if (isset($arrayinventory['CONTENT']['HARDWARE']['OSVERSION'])) {
            $inputos['VERSION']  = $arrayinventory['CONTENT']['HARDWARE']['OSVERSION'];
         }
         if (isset($arrayinventory['CONTENT']['SOFTWARES']['VERSION'])) {
            $temparray = $arrayinventory['CONTENT']['SOFTWARES'];
            $arrayinventory['CONTENT']['SOFTWARES'] = [];
            $arrayinventory['CONTENT']['SOFTWARES'][] = $temparray;
         }
         $arrayinventory['CONTENT']['SOFTWARES'][] = $inputos;
      }

         // Hack for USB Printer serial
      if (isset($arrayinventory['CONTENT']['PRINTERS'])) {
         foreach ($arrayinventory['CONTENT']['PRINTERS'] as $key=>$printer) {
            if ((isset($printer['SERIAL']))
                    AND (preg_match('/\/$/', $printer['SERIAL']))) {
               $arrayinventory['CONTENT']['PRINTERS'][$key]['SERIAL'] =
                     preg_replace('/\/$/', '', $printer['SERIAL']);
            }
         }
      }

         // Hack to remove Memories with Flash types see ticket
         // http://forge.fusioninventory.org/issues/1337
      if (isset($arrayinventory['CONTENT']['MEMORIES'])) {
         foreach ($arrayinventory['CONTENT']['MEMORIES'] as $key=>$memory) {
            if ((isset($memory['TYPE']))
                    AND (preg_match('/Flash/', $memory['TYPE']))) {

               unset($arrayinventory['CONTENT']['MEMORIES'][$key]);
            }
         }
      }
      // End hack
      $a_computerinventory = PluginFusioninventoryFormatconvert::computerInventoryTransformation(
                                             $arrayinventory['CONTENT']);

      // Get tag is defined and put it in fusioninventory_agent table
      $tagAgent = "";
      if (isset($a_computerinventory['ACCOUNTINFO'])) {
         if (isset($a_computerinventory['ACCOUNTINFO']['KEYNAME'])
              && $a_computerinventory['ACCOUNTINFO']['KEYNAME'] == 'TAG') {
            if (isset($a_computerinventory['ACCOUNTINFO']['KEYVALUE'])
                    && $a_computerinventory['ACCOUNTINFO']['KEYVALUE'] != '') {
               $tagAgent = $a_computerinventory['ACCOUNTINFO']['KEYVALUE'];
            }
         }
      }
      $pfAgent = new PluginFusioninventoryAgent();
      $input = [];
      $input['id'] = $_SESSION['plugin_fusioninventory_agents_id'];
      $input['tag'] = $tagAgent;
      $pfAgent->update($input);

      $pfBlacklist = new PluginFusioninventoryInventoryComputerBlacklist();
      $a_computerinventory = $pfBlacklist->cleanBlacklist($a_computerinventory);

      if (isset($a_computerinventory['monitor'])) {
         foreach ($a_computerinventory['monitor'] as $num=>$a_monit) {
            $a_computerinventory['monitor'][$num] = $pfBlacklist->cleanBlacklist($a_monit);
         }
      }
      $this->fillArrayInventory($a_computerinventory);

      $input = [];

      // Global criterias

      if ((isset($a_computerinventory['Computer']['serial']))
                 AND (!empty($a_computerinventory['Computer']['serial']))) {
         $input['serial'] = $a_computerinventory['Computer']['serial'];
      }
      if ((isset($a_computerinventory['Computer']['otherserial']))
                 AND (!empty($a_computerinventory['Computer']['otherserial']))) {
         $input['otherserial'] = $a_computerinventory['Computer']['otherserial'];
      }
      if ((isset($a_computerinventory['Computer']['uuid']))
                 AND (!empty($a_computerinventory['Computer']['uuid']))) {
         $input['uuid'] = $a_computerinventory['Computer']['uuid'];
      }
      if (isset($this->device_id) && !empty($this->device_id)) {
         $input['device_id'] = $this->device_id;
      }

      foreach ($a_computerinventory['networkport'] as $network) {
         if (((isset($network['virtualdev']))
              && ($network['virtualdev'] != 1))
              OR (!isset($network['virtualdev']))) {
            if ((isset($network['mac'])) AND (!empty($network['mac']))) {
               $input['mac'][] = $network['mac'];
            }
            foreach ($network['ipaddress'] as $ip) {
               if ($ip != '127.0.0.1' && $ip != '::1') {
                  $input['ip'][] = $ip;
               }
            }
            if ((isset($network['subnet'])) AND (!empty($network['subnet']))) {
               $input['subnet'][] = $network['subnet'];
            }
         }
      }
         // Case of virtualmachines
      if (!isset($input['mac'])
                 && !isset($input['ip'])) {
         foreach ($a_computerinventory['networkport'] as $network) {
            if ((isset($network['mac'])) AND (!empty($network['mac']))) {
               $input['mac'][] = $network['mac'];
            }
            foreach ($network['ipaddress'] as $ip) {
               if ($ip != '127.0.0.1' && $ip != '::1') {
                  $input['ip'][] = $ip;
               }
            }
            if ((isset($network['subnet'])) AND (!empty($network['subnet']))) {
               $input['subnet'][] = $network['subnet'];
            }
         }
      }

      if ((isset($a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id']['license_number']))
               AND (!empty($a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id']['license_number']))) {
         $input['mskey'] = $a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id']['license_number'];
      }
      if ((isset($a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id']['operatingsystems_id']))
               AND (!empty($a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id']['operatingsystems_id']))) {
         $input['osname'] = $a_computerinventory['fusioninventorycomputer']['items_operatingsystems_id']['operatingsystems_id'];
      }
      if ((isset($a_computerinventory['fusioninventorycomputer']['oscomment']))
               AND (!empty($a_computerinventory['fusioninventorycomputer']['oscomment']))) {
         $input['oscomment'] = $a_computerinventory['fusioninventorycomputer']['oscomment'];
      }
      if ((isset($a_computerinventory['Computer']['computermodels_id']))
                 AND (!empty($a_computerinventory['Computer']['computermodels_id']))) {
         $input['model'] = $a_computerinventory['Computer']['computermodels_id'];
      }
      if ((isset($a_computerinventory['Computer']['domains_id']))
                 AND (!empty($a_computerinventory['Computer']['domains_id']))) {
         $input['domains_id'] = $a_computerinventory['Computer']['domains_id'];
      }

         $input['tag'] = $tagAgent;

      if ((isset($a_computerinventory['Computer']['name']))
                 AND ($a_computerinventory['Computer']['name'] != '')) {
         $input['name'] = $a_computerinventory['Computer']['name'];
      } else {
         $input['name'] = '';
      }
      $input['itemtype'] = "Computer";

      // If transfer is disable, get entity and search only on this entity
      // (see http://forge.fusioninventory.org/issues/1503)

      // * entity rules
      $inputent = $input;
      if ((isset($a_computerinventory['Computer']['domains_id']))
                    AND (!empty($a_computerinventory['Computer']['domains_id']))) {
         $inputent['domain'] = $a_computerinventory['Computer']['domains_id'];
      }
      if (isset($inputent['serial'])) {
         $inputent['serialnumber'] = $inputent['serial'];
      }
      $ruleEntity = new PluginFusioninventoryInventoryRuleEntityCollection();

      // * Reload rules (required for unit tests)
      $ruleEntity->getCollectionPart();

      $dataEntity = $ruleEntity->processAllRules($inputent, []);
      if (isset($dataEntity['_ignore_import'])) {
         return;
      }

      if (isset($dataEntity['entities_id'])
                    && $dataEntity['entities_id'] >= 0) {
         $_SESSION["plugin_fusioninventory_entity"] = $dataEntity['entities_id'];
         $input['entities_id'] = $dataEntity['entities_id'];

      } else if (isset($dataEntity['entities_id'])
                    && $dataEntity['entities_id'] == -1) {
         $input['entities_id'] = 0;
         $_SESSION["plugin_fusioninventory_entity"] = -1;
      } else {
         $input['entities_id'] = 0;
         $_SESSION["plugin_fusioninventory_entity"] = 0;
      }

      if (isset($dataEntity['locations_id'])) {
         $_SESSION['plugin_fusioninventory_locations_id'] = $dataEntity['locations_id'];
      }
         // End entity rules
      $_SESSION['plugin_fusioninventory_classrulepassed'] =
                     "PluginFusioninventoryInventoryComputerInventory";

      //Add the location if needed (play rule locations engine)
      $output = [];
      $inputloc = $input;
      if ((isset($a_computerinventory['Computer']['domains_id']))
          AND (!empty($a_computerinventory['Computer']['domains_id']))) {
          $inputloc['domain'] = $a_computerinventory['Computer']['domains_id'];
      }
      $output = PluginFusioninventoryToolbox::addLocation($inputloc, $output);
      if (isset($output['locations_id'])) {
         $_SESSION['plugin_fusioninventory_locations_id'] =
               $output['locations_id'];
      }

      $rule = new PluginFusioninventoryInventoryRuleImportCollection();

      // * Reload rules (required for unit tests)
      $rule->getCollectionPart();

      $data = $rule->processAllRules($input, [], ['class'=>$this]);
      PluginFusioninventoryToolbox::logIfExtradebug("pluginFusioninventory-rules",
                                                   $data);

      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         $this->rulepassed(0, "Computer");
      } else if (!isset($data['found_equipment'])) {
         $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
         $inputdb = [];
         $inputdb['name'] = $input['name'];
         $inputdb['date'] = date("Y-m-d H:i:s");
         $inputdb['itemtype'] = "Computer";

         if ((isset($a_computerinventory['Computer']['domains_id']))
                    AND (!empty($a_computerinventory['Computer']['domains_id']))) {
               $inputdb['domain'] = $a_computerinventory['Computer']['domains_id'];
         }
         if (isset($a_computerinventory['Computer']['serial'])) {
            $inputdb['serial'] = $a_computerinventory['Computer']['serial'];
         }
         if (isset($a_computerinventory['Computer']['uuid'])) {
            $inputdb['uuid'] = $a_computerinventory['Computer']['uuid'];
         }
         if (isset($input['ip'])) {
            $inputdb['ip'] = $input['ip'];
         }
         if (isset($input['mac'])) {
            $inputdb['mac'] = $input['mac'];
         }

         $inputdb['entities_id'] = $input['entities_id'];

         if (isset($input['ip'])) {
            $inputdb['ip'] = exportArrayToDB($input['ip']);
         }
         if (isset($input['mac'])) {
            $inputdb['mac'] = exportArrayToDB($input['mac']);
         }
         $inputdb['rules_id'] = $data['_ruleid'];
         $inputdb['method'] = 'inventory';
         $inputdb['plugin_fusioninventory_agents_id'] = $_SESSION['plugin_fusioninventory_agents_id'];

         // if existing ignored device, update it
         if ($found = $pfIgnoredimportdevice->find(
               ['plugin_fusioninventory_agents_id' => $inputdb['plugin_fusioninventory_agents_id']],
               ['date DESC'], 1)) {
            $agent         = array_pop($found);
            $inputdb['id'] = $agent['id'];
            $pfIgnoredimportdevice->update($inputdb);
         } else {
            $pfIgnoredimportdevice->add($inputdb);
         }
      }
   }


   /**
    * After rule engine passed, update task (log) and create item if required
    *
    * @global object $DB
    * @global string $PLUGIN_FUSIONINVENTORY_XML
    * @global boolean $PF_ESXINVENTORY
    * @global array $CFG_GLPI
    * @param integer $items_id id of the item (0 = not exist in database)
    * @param string $itemtype
    */
   function rulepassed($items_id, $itemtype, $ports_id = 0) {
      global $DB, $PLUGIN_FUSIONINVENTORY_XML, $PF_ESXINVENTORY, $CFG_GLPI;

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_computerinventory = $pfFormatconvert->replaceids($this->arrayinventory,
              $itemtype, $items_id);
      $entities_id = $_SESSION["plugin_fusioninventory_entity"];

      if ($itemtype == 'Computer') {
         $pfInventoryComputerLib = new PluginFusioninventoryInventoryComputerLib();
         $pfAgent                = new PluginFusioninventoryAgent();

         $computer   = new Computer();
         if ($items_id == '0') {
            if ($entities_id == -1) {
               $entities_id = 0;
               $_SESSION["plugin_fusioninventory_entity"] = 0;
            }
            $_SESSION['glpiactiveentities']        = [$entities_id];
            $_SESSION['glpiactiveentities_string'] = $entities_id;
            $_SESSION['glpiactive_entity']         = $entities_id;
         } else {
            $computer->getFromDB($items_id);
            $a_computerinventory['Computer']['states_id'] = $computer->fields['states_id'];
            $input = [];
            $input = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('computer', $input);
            if (isset($input['states_id'])) {
                $a_computerinventory['Computer']['states_id'] = $input['states_id'];
            }

            if ($entities_id == -1) {
               $entities_id = $computer->fields['entities_id'];
               $_SESSION["plugin_fusioninventory_entity"] = $computer->fields['entities_id'];
            }

            $_SESSION['glpiactiveentities']        = [$entities_id];
            $_SESSION['glpiactiveentities_string'] = $entities_id;
            $_SESSION['glpiactive_entity']         = $entities_id;

            if ($computer->fields['entities_id'] != $entities_id) {
               $pfEntity = new PluginFusioninventoryEntity();
               $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
               $moveentity = false;
               if ($pfEntity->getValue('transfers_id_auto', $computer->fields['entities_id']) > 0) {
                  if (!$pfInventoryComputerComputer->getLock($items_id)) {
                     $moveentity = true;
                  }
               }
               if ($moveentity) {
                  $pfEntity = new PluginFusioninventoryEntity();
                  $transfer = new Transfer();
                  $transfer->getFromDB($pfEntity->getValue('transfers_id_auto', $entities_id));
                  $item_to_transfer = ["Computer" => [$items_id=>$items_id]];
                  $transfer->moveItems($item_to_transfer, $entities_id, $transfer->fields);
               } else {
                  $_SESSION["plugin_fusioninventory_entity"] = $computer->fields['entities_id'];
                  $_SESSION['glpiactiveentities']        = [$computer->fields['entities_id']];
                  $_SESSION['glpiactiveentities_string'] = $computer->fields['entities_id'];
                  $_SESSION['glpiactive_entity']         = $computer->fields['entities_id'];
                  $entities_id = $computer->fields['entities_id'];
               }
            }
         }
         if ($items_id > 0) {
            $a_computerinventory = $pfFormatconvert->extraCollectInfo(
                                                   $a_computerinventory,
                                                   $items_id);
         }
         $a_computerinventory = $pfFormatconvert->computerSoftwareTransformation(
                                                $a_computerinventory,
                                                $entities_id);

         $no_history = false;
         // * New
         $setdynamic = 1;
         if ($items_id == '0') {
            $input = [];
            $input['entities_id'] = $entities_id;
            $input = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('computer', $input);
            if (isset($input['states_id'])) {
                $a_computerinventory['Computer']['states_id'] = $input['states_id'];
            } else {
                $a_computerinventory['Computer']['states_id'] = 0;
            }
            $items_id = $computer->add($input);
            $no_history = true;
            $setdynamic = 0;
            $_SESSION['glpi_fusionionventory_nolock'] = true;

            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
               $inputrulelog = [];
               $inputrulelog['date'] = date('Y-m-d H:i:s');
               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
                  $inputrulelog['plugin_fusioninventory_agents_id'] =
                                 $_SESSION['plugin_fusioninventory_agents_id'];
               }
               $inputrulelog['items_id'] = $items_id;
               $inputrulelog['itemtype'] = $itemtype;
               $inputrulelog['method'] = 'inventory';
               $pfRulematchedlog->add($inputrulelog, [], false);
               $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }

         }
         if (isset($_SESSION['plugin_fusioninventory_locations_id'])) {
               $a_computerinventory['Computer']['locations_id'] =
                                 $_SESSION['plugin_fusioninventory_locations_id'];
               unset($_SESSION['plugin_fusioninventory_locations_id']);
         }

         $serialized = gzcompress(serialize($a_computerinventory));
         $a_computerinventory['fusioninventorycomputer']['serialized_inventory'] =
                  Toolbox::addslashes_deep($serialized);

         if (!$PF_ESXINVENTORY) {
            $pfAgent->setAgentWithComputerid($items_id, $this->device_id, $entities_id);
         }

         $query = $DB->buildInsert(
            'glpi_plugin_fusioninventory_dblockinventories', [
               'value' => $items_id
            ]
         );
         $CFG_GLPI["use_log_in_files"] = false;
         if (!$DB->query($query)) {
            $communication = new PluginFusioninventoryCommunication();
            $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
         <REPLY>
         <ERROR>ERROR: SAME COMPUTER IS CURRENTLY UPDATED</ERROR>
         </REPLY>");
            $communication->sendMessage($_SESSION['plugin_fusioninventory_compressmode']);
            exit;
         }
         $CFG_GLPI["use_log_in_files"] = true;

         // * For benchs
         //$start = microtime(TRUE);

         PluginFusioninventoryInventoryComputerStat::increment();

         $pfInventoryComputerLib->updateComputer(
                 $a_computerinventory,
                 $items_id,
                 $no_history,
                 $setdynamic);

         $DB->delete(
            'glpi_plugin_fusioninventory_dblockinventories', [
               'value' => $items_id
            ]
         );
         if (isset($_SESSION['glpi_fusionionventory_nolock'])) {
            unset($_SESSION['glpi_fusionionventory_nolock']);
         }

         $plugin = new Plugin();
         if ($plugin->isActivated('monitoring')) {
            Plugin::doOneHook("monitoring", "ReplayRulesForItem", ['Computer', $items_id]);
         }
         // * For benchs
         //Toolbox::logInFile("exetime", (microtime(TRUE) - $start)." (".$items_id.")\n".
         //  memory_get_usage()."\n".
         //  memory_get_usage(TRUE)."\n".
         //  memory_get_peak_usage()."\n".
         //  memory_get_peak_usage()."\n");

         // Write XML file
         if (!empty($PLUGIN_FUSIONINVENTORY_XML)) {
            PluginFusioninventoryToolbox::writeXML(
                    $items_id,
                    $PLUGIN_FUSIONINVENTORY_XML->asXML(),
                    'computer');
         }
      } else if ($itemtype == 'PluginFusioninventoryUnmanaged') {

         $a_computerinventory = $pfFormatconvert->computerSoftwareTransformation(
                                                $a_computerinventory,
                                                $entities_id);

         $class = new $itemtype();
         if ($items_id == "0") {
            if ($entities_id == -1) {
               $_SESSION["plugin_fusioninventory_entity"] = 0;
            }
            $input = [];
            $input['date_mod'] = date("Y-m-d H:i:s");
            $items_id = $class->add($input);
            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
               $inputrulelog = [];
               $inputrulelog['date'] = date('Y-m-d H:i:s');
               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
                  $inputrulelog['plugin_fusioninventory_agents_id'] =
                                 $_SESSION['plugin_fusioninventory_agents_id'];
               }
               $inputrulelog['items_id'] = $items_id;
               $inputrulelog['itemtype'] = $itemtype;
               $inputrulelog['method'] = 'inventory';
               $pfRulematchedlog->add($inputrulelog);
               $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }
         }
         $class->getFromDB($items_id);
         $_SESSION["plugin_fusioninventory_entity"] = $class->fields['entities_id'];
         $input = [];
         $input['id'] = $class->fields['id'];

         // Write XML file
         if (!empty($PLUGIN_FUSIONINVENTORY_XML)) {
            PluginFusioninventoryToolbox::writeXML(
                    $items_id,
                    $PLUGIN_FUSIONINVENTORY_XML->asXML(),
                    'PluginFusioninventoryUnmanaged');
         }

         if (isset($a_computerinventory['Computer']['name'])) {
            $input['name'] = $a_computerinventory['Computer']['name'];
         }
         $input['item_type'] = "Computer";
         if (isset($a_computerinventory['Computer']['domains_id'])) {
            $input['domain'] = $a_computerinventory['Computer']['domains_id'];
         }
         if (isset($a_computerinventory['Computer']['serial'])) {
            $input['serial'] = $a_computerinventory['Computer']['serial'];
         }
         $class->update($input);
      }
   }


   /**
    * Return method name of this class/plugin
    *
    * @return string
    */
   static function getMethod() {
      return 'inventory';
   }


   /**
    * Fill internal variable with the inventory array
    *
    * @param array $data
    */
   function fillArrayInventory($data) {
      $this->arrayinventory = $data;
   }
}
