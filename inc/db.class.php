<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryDb extends CommonDBTM {
   static function createfirstaccess($id) {
      global $DB;

      $plugin_fusioninventory_Profile=new PluginFusioninventoryProfile;
      if (!$plugin_fusioninventory_Profile->GetfromDB($id)) {
         $Profile=new Profile;
         $Profile->GetfromDB($id);
         $name=$Profile->fields["name"];

         $query = "INSERT INTO `glpi_plugin_fusioninventory_profiles` (
                   `id`, `name`, `interface`, `is_default`, `snmp_networking`, `snmp_printers`,
                   `snmp_models`, `snmp_authentication`, `iprange`, `agents`, `remotecontrol`,
                   `agentprocesses`, `unknowndevices`, `reports`, `deviceinventory`, `netdiscovery`,
                   `snmp_query`, `wol`, `configuration` )
                   VALUES ('$id', '$name','fusioninventory','0','w','w',
                     'w','w','w','w','w',
                     'r','w','r','w','w',
                     'w','w','w');";
         $DB->query($query);
      }
   }

   static function createaccess($id) {
      global $DB;

      $Profile=new Profile;
      $Profile->GetfromDB($id);
      $name=$Profile->fields["name"];

      $query = "INSERT INTO `glpi_plugin_fusioninventory_profiles` (
                   `id`, `name` , `interface`, `is_default`, `snmp_networking`, `snmp_printers`,
                   `snmp_models`, `snmp_authentication`, `iprange`, `agents`, `remotecontrol`,
                   `agentprocesses`, `unknowndevices`, `reports`, `deviceinventory`, `netdiscovery`,
                   `snmp_query`, `wol`, `configuration` )
                VALUES ('$id', '$name','fusioninventory','0',NULL,NULL,
                   NULL,NULL,NULL,NULL,NULL,
                   NULL,NULL,NULL,NULL,NULL,
                   NULL,NULL,NULL);";
      $DB->query($query);
   }

   static function updateaccess($id) {
      global $DB;

      $Profile=new Profile;
      $Profile->GetfromDB($id);
      $name=$Profile->fields["name"];

      $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
                  SET `interface`='fusioninventory', `snmp_networking`='w',
                      `snmp_printers`='w', `snmp_models`='w',
                      `snmp_authentication`='w', `iprange`='w',
                      `agents`='w', `remotecontrol`='w',
                      `agentprocesses`='r', `unknowndevices`='w',
                      `reports`='r', `deviceinventory`='w',
                      `netdiscovery`='w', `snmp_query`='w',
                      `wol`='w', `configuration`='w'
                  WHERE `name`='".$name."'";
      $DB->query($query);

   }

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

      $ptp = new PluginFusioninventoryNetworkPort;
      $pti = new PluginFusioninventoryNetworkEquipmentIp;
      $ptn = new PluginFusioninventoryNetworkEquipment;
      $ptpr = new PluginFusioninventoryPrinter;
      $ptpc = new PluginFusioninventoryPrinter_Cartridge;
      $ptph = new PluginFusioninventoryPrinterLog;

      // * Clean glpi_plugin_fusioninventory_networkports
      $query_select = "SELECT `glpi_plugin_fusioninventory_networkports`.`id`
                       FROM `glpi_plugin_fusioninventory_networkports`
                             LEFT JOIN `glpi_networkports`
                                       ON `glpi_networkports`.`id` = `networkports_id`
                             LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `items_id`
                       WHERE `glpi_networkequipments`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptp->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusioninventory_networkequipmentips
      $query_select = "SELECT `glpi_plugin_fusioninventory_networkequipmentips`.`id`
                       FROM `glpi_plugin_fusioninventory_networkequipmentips`
                             LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                       WHERE `glpi_networkequipments`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $pti->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusioninventory_networkequipments
      $query_select = "SELECT `glpi_plugin_fusioninventory_networkequipments`.`id`
                       FROM `glpi_plugin_fusioninventory_networkequipments`
                             LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                       WHERE `glpi_networkequipments`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptn->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusioninventory_printers
      $query_select = "SELECT `glpi_plugin_fusioninventory_printers`.`id`
                       FROM `glpi_plugin_fusioninventory_printers`
                             LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                       WHERE `glpi_printers`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptpr->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusioninventory_printercartridges
      $query_select = "SELECT `glpi_plugin_fusioninventory_printercartridges`.`id`
                       FROM `glpi_plugin_fusioninventory_printercartridges`
                             LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                       WHERE `glpi_printers`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptpc->deleteFromDB($data["id"],1);
      }

      // * Clean glpi_plugin_fusioninventory_printerlogs
      $query_select = "SELECT `glpi_plugin_fusioninventory_printerlogs`.`id`
                       FROM `glpi_plugin_fusioninventory_printerlogs`
                             LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                       WHERE `glpi_printers`.`id` IS NULL";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $ptph->deleteFromDB($data["id"],1);
      }
   }

   static function lock_wire_check() {
      while (1) {
         $file_lock = GLPI_PLUGIN_DOC_DIR."/fusioninventory/wire.lock";
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
      $file_lock = GLPI_PLUGIN_DOC_DIR."/fusioninventory/wire.lock";
      $fp =  fopen($file_lock,"r+");
      fputs($fp,0);
      fclose($fp);
   }
}

?>