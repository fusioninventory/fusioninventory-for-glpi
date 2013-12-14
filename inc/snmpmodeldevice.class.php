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
   @since     2012

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventorySnmpmodeldevice extends CommonDBTM {

   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight("model", "w");
   }


   static function canView() {
      return PluginFusioninventoryProfile::haveRight("model", "r");
   }


   function updateDevicesForModel($models_id, $a_devices) {
      $a_devicesDBtmp = $this->find("`plugin_fusioninventory_snmpmodels_id`='".$models_id."'");
      $a_devicesDB = array();
      foreach ($a_devicesDBtmp as $data) {
         $a_devicesDB[$data['sysdescr']] = $data['id'];
      }
      foreach ($a_devices as $sysdescr) {
         if (!isset($a_devicesDB[$sysdescr])) {
            $input = array();
            $input['plugin_fusioninventory_snmpmodels_id'] = $models_id;
            $input['sysdescr'] = $sysdescr;
            $this->add($input);
         } else {
            unset($a_devicesDB[$sysdescr]);
         }
      }
      foreach ($a_devicesDB as $id) {
         $input = array();
         $input['id'] = $id;
         $this->delete($input);
      }
   }



   function showDevices($models_id) {

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "Devices";
      echo "</th>";
      echo "</tr>";

      $a_devices = $this->find("`plugin_fusioninventory_snmpmodels_id`='".$models_id."'");
      foreach ($a_devices as $data) {
         echo "<tr class='tab_bg_3'>";
         echo "<td>";
         echo $data['sysdescr'];
         echo "</td>";
         echo "</tr>";
      }

      echo "</table>";
   }

   
   
   function cleanDevices() {
      global $DB;
      
      $query = "SELECT `glpi_plugin_fusioninventory_snmpmodeldevices`.`id`
                   FROM `glpi_plugin_fusioninventory_snmpmodeldevices`
                LEFT JOIN `glpi_plugin_fusioninventory_snmpmodels`
                   ON `plugin_fusioninventory_snmpmodels_id`=".
                        "`glpi_plugin_fusioninventory_snmpmodels`.`id`
                WHERE `glpi_plugin_fusioninventory_snmpmodels`.`id` IS NULL";

      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $this->delete($data);
      }
   }


   function generateDico() {

   }
}

?>
