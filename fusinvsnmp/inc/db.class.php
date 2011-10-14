<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Vincent MAZZONI
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFuvinvsnmpDb extends CommonDBTM {

   static function getDeviceFieldFromId($type, $id, $field, $return) {
      global $DB;
      switch($type) {
         case COMPUTER_TYPE:
            $table = "`glpi_computers`";
            break;

         case NETWORKING_TYPE:
            $table = "`glpi_networkequipments`";
            break;

         case PRINTER_TYPE:
            $table = "`glpi_printers`";
            break;

         case USER_TYPE:
            $table = "`glpi_users`";
            break;

         default:
            return $return;
            break;
      }

      $query = "SELECT ".$field.
               "FROM ".$table." ".
               "WHERE `id` = '".$id."';";
      if ($result = $DB->query($query)) {
         if (($fields=$DB->fetch_row($result)) && ($fields['0'] != NULL)) {
            return $fields['0'];
         }
      }
      return $return;
   }


   
   static function clean_db() {
      global $DB;

      $ptp = new PluginFusinvsnmpNetworkPort;
      $pti = new PluginFusinvsnmpNetworkEquipmentIp;
      $ptn = new PluginFusinvsnmpNetworkEquipment;
      $ptpr = new PluginFusinvsnmpPrinter;
      $ptpc = new PluginFusinvsnmpPrinter_Cartridge;
      $ptph = new PluginFusinvsnmpPrinterLog;

      // * Clean glpi_plugin_fusinvsnmp_networkports
      $query_select = "SELECT `glpi_plugin_fusinvsnmp_networkports`.`id`
                       FROM `glpi_plugin_fusinvsnmp_networkports`
                             LEFT JOIN `glpi_networkports`
                                       ON `glpi_networkports`.`id` = `networkports_id`
                             LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `items_id`
                       WHERE `glpi_networkequipments`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptp->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusinvsnmp_networkequipmentips
      $query_select = "SELECT `glpi_plugin_fusinvsnmp_networkequipmentips`.`id`
                       FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                             LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                       WHERE `glpi_networkequipments`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $pti->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusinvsnmp_networkequipments
      $query_select = "SELECT `glpi_plugin_fusinvsnmp_networkequipments`.`id`
                       FROM `glpi_plugin_fusinvsnmp_networkequipments`
                             LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                       WHERE `glpi_networkequipments`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptn->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusinvsnmp_printers
      $query_select = "SELECT `glpi_plugin_fusinvsnmp_printers`.`id`
                       FROM `glpi_plugin_fusinvsnmp_printers`
                             LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                       WHERE `glpi_printers`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptpr->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusinvsnmp_printercartridges
      $query_select = "SELECT `glpi_plugin_fusinvsnmp_printercartridges`.`id`
                       FROM `glpi_plugin_fusinvsnmp_printercartridges`
                             LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                       WHERE `glpi_printers`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptpc->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusinvsnmp_printerlogs
      $query_select = "SELECT `glpi_plugin_fusinvsnmp_printerlogs`.`id`
                       FROM `glpi_plugin_fusinvsnmp_printerlogs`
                             LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                       WHERE `glpi_printers`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptph->deleteFromDB($data["id"],1);
      }
   }


   
   static function lock_wire_check() {
      while (1) {
         $file_lock = GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/wire.lock";
         $fp =  fopen($file_lock,"r+");
         $lock = 1;
         fseek($fp,0);
         $lock = fgets($fp,255);
         if ($lock == 0) {
            fseek($fp,0);
            fputs($fp,1);
            fclose($fp);
            return;
         }
         fclose($fp);
         usleep(250000);
      }
   }


   
   static function lock_wire_unlock() {
      $file_lock = GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/wire.lock";
      $fp =  fopen($file_lock,"r+");
      fputs($fp,0);
      fclose($fp);
   }
}

?>