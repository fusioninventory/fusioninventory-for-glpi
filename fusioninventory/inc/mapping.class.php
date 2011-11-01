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

class PluginFusioninventoryMapping extends CommonDBTM {

   /**
    * Get mapping
    *
    *@param $p_itemtype Mapping itemtype
    *@param $p_name Mapping name
    *@return mapping fields or false
    **/
   function get($p_itemtype, $p_name) {
      $data = $this->find("`itemtype`='".$p_itemtype."' AND `name`='".$p_name."'");
      $mapping = current($data);
      if (isset($mapping['id'])) {
         return $mapping;
      }
      return false;
   }
   
   
   
   function set($p_itemtype, $p_name, $p_table, $p_tablefield, $p_locale, $p_shortlocale) {
      global $DB;
      
      $data = current(getAllDatasFromTable("glpi_plugin_fusioninventory_mappings", 
                                   "`itemtype`='NetworkEquipment' AND `name`='location'"));
      if (empty($data)) {
         // Insert
         $query = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
                     (`itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
                  VALUES ('".$p_itemtype."','".$p_name."','".$p_table."',
                          '".$p_tablefield."','".$p_locale."','".$p_shortlocale."')";
         $DB->query($query);
      } elseif ($data['table'] != $p_table
                OR $data['tablefield'] != $p_tablefield
                OR $data['locale'] != $p_locale) {
         $data['table'] = $p_table;
         $data['tablefield'] = $p_tablefield;
         $data['locale'] = $p_locale;
         $data['shortlocale'] = $p_shortlocale;
         
         $this->update($data);
      }
   }
}

?>