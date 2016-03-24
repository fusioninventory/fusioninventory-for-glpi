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
   @author    Vincent Mazzoni
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

class PluginFusioninventoryModule extends CommonDBTM {

   /**
    * Get all installed modules
    *
    * @param $p_inactive=FALSE Show inactive modules
    *
    * @return array of fields
    **/
   static function getAll($p_inactive=FALSE) {
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
    * @param $p_name Module name
    * @return Plugin id or FALSE if module is not active or not a fusioninventory module
    **/
   static function getModuleId($p_name) {
      $index = FALSE;
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
    * @param $p_id Module id
    * @return name or FALSE if module is not active or not a fusioninventory module
    **/
   static function getModuleName($p_id) {
      if (isset($_SESSION['glpi_plugins'][$p_id])) {
         if ((substr($_SESSION['glpi_plugins'][$p_id], 0, 6) == 'fusinv')
              OR ($_SESSION['glpi_plugins'][$p_id] == 'fusioninventory')) {
            return $_SESSION['glpi_plugins'][$p_id];
         } else {
            return FALSE;
         }
      } else {
         return FALSE;
      }
   }
}

?>
