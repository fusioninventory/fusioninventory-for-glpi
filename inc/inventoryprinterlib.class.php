<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryPrinterLib extends CommonDBTM {


   /**
    * Function to update Printer
    *
    * @param array $a_inventory data fron agent inventory
    * @param id $items_id id of the printer
    *
    * @return nothing
    */
   function updatePrinter($a_inventory, $items_id) {
      global $DB;

      $printer = new Printer();
      $pfPrinter = new PluginFusioninventoryPrinter();

      $printer->getFromDB($items_id);

      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = $printer->fields['entities_id'];
      }
      if (!isset($_SESSION['glpiactiveentities'])) {
         $_SESSION['glpiactiveentities'] = array($printer->fields['entities_id']);
      }
      if (!isset($_SESSION['glpiactive_entity'])) {
         $_SESSION['glpiactive_entity'] = $printer->fields['entities_id'];
      }

      // * Printer
      $db_printer =  $printer->fields;

      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_printers', $items_id);

      $a_ret = PluginFusioninventoryToolbox::checkLock($a_inventory['Printer'],
                                                       $db_printer, $a_lockable);
      $a_inventory['Printer'] = $a_ret[0];

      $input = $a_inventory['Printer'];

      $input['id'] = $items_id;
      $printer->update($input);

      // * Printer fusion (ext)
         $db_printer = array();
         $query = "SELECT *
            FROM `".  getTableForItemType("PluginFusioninventoryPrinter")."`
            WHERE `printers_id` = '$items_id'";
         $result = $DB->query($query);
         while ($data = $DB->fetch_assoc($result)) {
            foreach($data as $key=>$value) {
               $db_printer[$key] = Toolbox::addslashes_deep($value);
            }
         }
         if (count($db_printer) == '0') { // Add
            $a_inventory['PluginFusioninventoryPrinter']['printers_id'] =
               $items_id;
            $pfPrinter->add($a_inventory['PluginFusioninventoryPrinter']);
         } else { // Update
            $idtmp = $db_printer['id'];
            unset($db_printer['id']);
            unset($db_printer['printers_id']);
            unset($db_printer['plugin_fusioninventory_configsecurities_id']);

            $a_ret = PluginFusioninventoryToolbox::checkLock(
                        $a_inventory['PluginFusioninventoryPrinter'],
                        $db_printer);
            $a_inventory['PluginFusioninventoryPrinter'] = $a_ret[0];
            $input = $a_inventory['PluginFusioninventoryPrinter'];
            $input['id'] = $idtmp;
            $pfPrinter->update($input);
         }

      // * Ports
         $this->importPorts($a_inventory, $items_id);

      // Page counters
         $this->importPageCounters($a_inventory['pagecounters'], $items_id);

      // Cartridges
         $this->importCartridges($a_inventory['cartridge'], $items_id);

   }



   function importPorts($a_inventory, $items_id) {

      $networkPort = new NetworkPort();
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();

      $networkports_id = 0;
      foreach ($a_inventory['networkport'] as $a_port) {
         $a_ports_DB = current($networkPort->find(
                    "`itemtype`='Printer'
                       AND `items_id`='".$items_id."'
                       AND `instantiation_type`='NetworkPortEthernet'
                       AND `logical_number` = '".$a_port['logical_number']."'", '', 1));
         if (!isset($a_ports_DB['id'])) {
            // Add port
            $a_port['instantiation_type'] = 'NetworkPortEthernet';
            $a_port['items_id'] = $items_id;
            $a_port['itemtype'] = 'Printer';
            $networkports_id = $networkPort->add($a_port);
            unset($a_port['id']);
            $a_pfnetworkport_DB = current($pfNetworkPort->find(
                    "`networkports_id`='".$networkports_id."'", '', 1));
            $a_port['id'] = $a_pfnetworkport_DB['id'];
            $pfNetworkPort->update($a_port);
         } else {
            // Update port
            $networkports_id = $a_ports_DB['id'];
            $a_port['id'] = $a_ports_DB['id'];
            $networkPort->update($a_port);
            unset($a_port['id']);

            // Check if pfnetworkport exist.
            $a_pfnetworkport_DB = current($pfNetworkPort->find(
                    "`networkports_id`='".$networkports_id."'", '', 1));
            $a_port['networkports_id'] = $networkports_id;
            if (isset($a_pfnetworkport_DB['id'])) {
               $a_port['id'] = $a_pfnetworkport_DB['id'];
               $pfNetworkPort->update($a_port);
            } else {
               $a_port['networkports_id'] = $networkports_id;
               $pfNetworkPort->add($a_port);
            }
         }
      }
   }



   /**
    * Import page counters
    *
    * @return string
    */
   function importPageCounters($a_pagecounters, $items_id) {

      $pfPrinterLog = new PluginFusioninventoryPrinterLog();
      //See if have an entry today
      $a_entires = $pfPrinterLog->find("`printers_id`='".$items_id."'
         AND LEFT(`date`, 10)='".date("Y-m-d")."'", "", 1);
      if (count($a_entires) > 0) {
         return;
      }

      $a_pagecounters['printers_id'] = $items_id;
      $a_pagecounters['date'] = date("Y-m-d H:i:s");

      $pfPrinterLog->add($a_pagecounters);
   }



   /**
    * Import cartridges
    *
    **/
   function importCartridges($a_cartridges, $items_id) {

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();

      $a_db = $pfPrinterCartridge->find("`printers_id`='".$items_id."'");
      $a_dbcartridges = array();
      foreach ($a_db as $data) {
         $a_dbcartridges[$data['plugin_fusioninventory_mappings_id']] = $data;
      }

      foreach ($a_cartridges as $mappings_id=>$value) {
         if (isset($a_dbcartridges[$mappings_id])) {
            $a_dbcartridges[$mappings_id]['state'] = $value;
            $pfPrinterCartridge->update($a_dbcartridges[$mappings_id]);
         } else {
            $input = array();
            $input['printers_id'] = $items_id;
            $input['plugin_fusioninventory_mappings_id'] = $mappings_id;
            $input['state'] = $value;
            $pfPrinterCartridge->add($input);
         }
      }
   }

}

?>
