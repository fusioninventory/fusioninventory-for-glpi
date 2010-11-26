<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file: 
// ----------------------------------------------------------------------


if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class
 **/
class PluginFusinvinventoryImport_Networkport extends CommonDBTM {

   function AddUpdateItem($type, $items_id, $dataSection, $itemtype='Computer') {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $Computer_Item = new Computer_Item();
            
//      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
//              "import_printer") == '0') {
//         return;
//      }

      $NetworkPort = new NetworkPort();

      $a_NetworkPort = array();

      if ($type == 'update') {
         $a_NetworkPort = $NetworkPort->getFromDB($Computer_Item->fields['items_id']);
      } else {
         $a_NetworkPort['items_id']=$items_id;
      }

      $a_NetworkPort['itemtype'] = $itemtype;
      if (isset($dataSection["DESCRIPTION"])) {
         $a_NetworkPort['name'] = addslashes($dataSection["DESCRIPTION"]);
      }
      if (isset($dataSection["IPADDRESS"])) {
         $a_NetworkPort['ip'] = $dataSection["IPADDRESS"];
      }
      if (isset($dataSection["MACADDR"])) {
         $a_NetworkPort['mac'] = $dataSection["MACADDR"];
      }
      if (isset($dataSection["TYPE"])) {
         $a_NetworkPort["networkinterfaces_id"]
                     = Dropdown::importExternal('NetworkInterface', $dataSection["TYPE"]);
      }
      if (isset($dataSection["IPMASK"]))
         $a_NetworkPort['netmask'] = $dataSection["IPMASK"];
      if (isset($dataSection["IPGATEWAY"]))
         $a_NetworkPort['gateway'] = $dataSection["IPGATEWAY"];
      if (isset($dataSection["IPSUBNET"]))
         $a_NetworkPort['subnet'] = $dataSection["IPSUBNET"];

      $a_NetworkPort['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];

      if ($type == 'update') {
         $devID = $NetworkPort->update($a_NetworkPort);
      } else {
         $a_NetworkPort['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         $devID = $NetworkPort->add($a_NetworkPort);
      }

      return $devID;
   }


   function deleteItem($items_id) {
      $NetworkPort = new NetworkPort();
      $NetworkPort->getFromDB($items_id);
      if ($NetworkPort->fields['items_id'] == $idmachine) {
         $NetworkPort->delete(array("id" => $items_id));
      }
   }

}

?>