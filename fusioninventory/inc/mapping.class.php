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
   @author    Vincent Mazzoni
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
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

class PluginFusioninventoryMapping extends CommonDBTM {

   /**
    * Get mapping
    *
    *@param $p_itemtype Mapping itemtype
    *@param $p_name Mapping name
    *@return mapping fields or false
    **/
   function get($p_itemtype, $p_name) {
      $data = $this->find("`itemtype`='".$p_itemtype."' AND `name`='".$p_name."'", "", 1);
      $mapping = current($data);
      if (isset($mapping['id'])) {
         return $mapping;
      }
      return false;
   }
   
   
   
   /**
    *
    * @param $parm
    */
   function set($parm) {
      global $DB;
      
      $data = current(getAllDatasFromTable("glpi_plugin_fusioninventory_mappings", 
                                   "`itemtype`='".$parm['itemtype']."' AND `name`='".$parm['name']."'"));
      if (empty($data)) {
         // Insert
         $query = '';
         if (isset($parm['shortlocale'])) {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
                        (`itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
                     VALUES ('".$parm['itemtype']."','".$parm['name']."','".$parm['table']."',
                             '".$parm['tablefield']."','".$parm['locale']."','".$parm['shortlocale']."')";
         } else {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
                        (`itemtype`, `name`, `table`, `tablefield`, `locale`)
                     VALUES ('".$parm['itemtype']."','".$parm['name']."','".$parm['table']."',
                             '".$parm['tablefield']."','".$parm['locale']."')";
         }
         $DB->query($query);
      } elseif ($data['table'] != $parm['table']
                OR $data['tablefield'] != $parm['tablefield']
                OR $data['locale'] != $parm['locale']) {
         $data['table'] = $parm['table'];
         $data['tablefield'] = $parm['tablefield'];
         $data['locale'] = $parm['locale'];
         if (isset($parm['shortlocale'])) {
            $data['shortlocale'] = $parm['shortlocale'];
         }
         $this->update($data);
      }
   }
}

?>