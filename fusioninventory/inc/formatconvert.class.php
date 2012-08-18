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

class PluginFusioninventoryFormatconvert {
   
   static function XMLtoArray($xml) {
      $datainventory = array();
      $datainventory = json_decode(json_encode((array)$xml), true);
      if (isset($datainventory['CONTENT']['ENVS'])) {
         unset($datainventory['CONTENT']['ENVS']);
      }
      if (isset($datainventory['CONTENT']['PROCESSES'])) {
         unset($datainventory['CONTENT']['PROCESSES']);
      }
      $datainventory = PluginFusioninventoryFormatconvert::cleanArray($datainventory);
      return $datainventory;
   }
   
   
   
   static function JSONtoArray($json) {
      $datainventory = array();
      $datainventory = json_decode($json, true);
      $datainventory = PluginFusioninventoryFormatconvert::cleanArray($datainventory);
      return $datainventory;
   }
   
   
   
   static function cleanArray($data) {
      foreach ($data as $key=>$value) {
         if (is_array($value)) {
            $value = PluginFusioninventoryFormatconvert::cleanArray($value);
         } else {
            $value = Toolbox::clean_cross_side_scripting_deep(Toolbox::addslashes_deep($value));
         }
         $data[$key] = $value;
      }
      return $data;
   }
}

?>
