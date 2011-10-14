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

class PluginFusinvsnmpNetworkEquipmentIP extends PluginFusinvsnmpCommonDBTM {

   function __construct() {
      parent::__construct("glpi_plugin_fusinvsnmp_networkequipmentips");
   }



   /**
    * Add a new ip with the instance values
    *
    *@param $p_id Networking id
    *@return nothing
    **/
   function addDB($p_id) {
      if (count($this->ptcdUpdates)) {
         $this->ptcdUpdates['networkequipments_id']=$p_id;
         $this->add($this->ptcdUpdates);
      }
   }



   // Get all IP of the switch
   function getIP($items_id) {
      $a_ips = $this->find("`networkequipments_id`='".$items_id."'");
      $array = array();
      foreach ($a_ips as $ip) {
         $array[] = $ip['ip'];
      }
      return $array;
   }
}

?>