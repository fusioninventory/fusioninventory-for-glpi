<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2015 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2015 FusionInventory team
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

/// Class ComputerModel
class PluginFusioninventoryComputerArch extends CommonDropdown {

   static function getTypeName($nb=0) {
      return __('Architecture', 'fusioninventory');
   }

   function importExternal($value, $entities_id=-1, $external_params=array(), $comment="",
                           $add=true) {

      $value = trim($value);
      if (strlen($value) == 0) {
         return 0;
      }

      $ruleinput      = array("name" => stripslashes($value));
      $rulecollection = getItemForItemtype("PluginFusioninventoryRuleDictionnaryComputerArchCollection");

      foreach ($this->additional_fields_for_dictionnary as $field) {
         if (isset($external_params[$field])) {
            $ruleinput[$field] = $external_params[$field];
         } else {
            $ruleinput[$field] = '';
         }
      }

      $input["name"]        = $value;
      $input["comment"]     = $comment;

      if ($rulecollection) {
         $res_rule = $rulecollection->processAllRules(Toolbox::stripslashes_deep($ruleinput), array(), array());
         if (isset($res_rule["name"])) {
            $input["name"] = $res_rule["name"];
         }
      }
      return ($add ? $this->import($input) : $this->findID($input));
   }
}

?>