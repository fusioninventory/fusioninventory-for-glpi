<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the agent modules.
 */
class PluginFusioninventoryModule extends CommonDBTM {


   /**
    * Get all installed modules
    *
    * @param boolean $p_inactive Show inactive modules
    * @return array
    */
   static function getAll($p_inactive = false) {
      $plugin = new Plugin();
      if ($p_inactive) {
         return $plugin->find("(`state` = '1' OR `state` = '4') AND `directory` LIKE 'fusinv%'");
      } else {
         return $plugin->find("`state`='1' AND `directory` LIKE 'fusinv%'");
      }
   }


   /**
    * Get module id or fusioninventory plugin id
    *
    * @param string $p_name the module name
    * @return integer|false plugin id or FALSE if module is not active or not a
    *                       fusioninventory module
    */
   static function getModuleId($p_name) {
      $index = false;
      if (!isset($_SESSION['glpi_plugins'])) {
         return $index;
      }
      if ($p_name == 'fusioninventory') {
         $index = array_search($p_name, $_SESSION['glpi_plugins']);
         if (!$index) {
            $plugin = new Plugin();
            $data = $plugin->find("directory='".$p_name."'");
            if (count($data)) {
               $fields = current($data);
               $index = $fields['id'];
            }
         }
      }
      return $index;
   }


   /**
    * Get module name
    *
    * @param integer $p_id the module id
    * @return string|false false if module is not active or not a fusioninventory module
    */
   static function getModuleName($p_id) {
      if (isset($_SESSION['glpi_plugins'][$p_id])) {
         if ((substr($_SESSION['glpi_plugins'][$p_id], 0, 6) == 'fusinv')
              OR ($_SESSION['glpi_plugins'][$p_id] == 'fusioninventory')) {
            return $_SESSION['glpi_plugins'][$p_id];
         } else {
            return false;
         }
      } else {
         return false;
      }
   }


}

