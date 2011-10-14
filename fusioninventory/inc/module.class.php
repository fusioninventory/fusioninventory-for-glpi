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

class PluginFusioninventoryModule extends CommonDBTM {

   /**
    * Get all installed modules
    *
    *@param $p_inactive=false Show inactive modules
    * 
    *@return array of fields
    **/
   static function getAll($p_inactive=false) {
      $plugin = new Plugin();
      if ($p_inactive) {
         return $plugin->find("`state` IN ('1', '4') AND `directory` LIKE 'fusinv%'");
      } else {
         return $plugin->find("`state`='1' AND `directory` LIKE 'fusinv%'");
      }
   }


   
   /**
    * Get module id or fusioninventory plugin id
    *
    *@param $p_name Module name
    *@return Plugin id or false if module is not active or not a fusioninventory module
    **/
   static function getModuleId($p_name) {
      $index = false;
      if ((substr($p_name, 0, 6) == 'fusinv') OR ($p_name == 'fusioninventory')) {
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
    *@param $p_id Module id
    *@return name or false if module is not active or not a fusioninventory module
    **/
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

?>