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

class PluginFusinvsnmpModeldevice extends CommonDBTM {

   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r");
   }


   function updateDevicesForModel($models_id, $a_devices) {
      $a_devicesDBtmp = $this->find("`plugin_fusinvsnmp_models_id`='".$models_id."'");
      $a_devicesDB = array();
      foreach ($a_devicesDBtmp as $data) {
         $a_devicesDB[$data['sysdescr']] = $data['id'];
      }
      foreach ($a_devices as $sysdescr) {
         if (!isset($a_devicesDB[$sysdescr])) {
            $input = array();
            $input['plugin_fusinvsnmp_models_id'] = $models_id;
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

      $a_devices = $this->find("`plugin_fusinvsnmp_models_id`='".$models_id."'");
      foreach ($a_devices as $data) {
         echo "<tr class='tab_bg_3'>";
         echo "<td>";
         echo $data['sysdescr'];
         echo "</td>";
         echo "</tr>";
      }
      
      echo "</table>";      
   }
   
   
   
   function generateDico() {
      
   }
}

?>