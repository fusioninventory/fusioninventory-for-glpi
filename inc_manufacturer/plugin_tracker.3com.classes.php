<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginTrackerManufacturer3com extends CommonDBTM {

   function MultiplePorts() {
      // For 3Com IntelliJack NJ225
      $Array_multiplemac_ifIndex["1"] = 1;
      return $Array_multiplemac_ifIndex;
   }


   function tmpConnections($oidvalues,$oidsModel,$ifIndex,$TMP_ID,$ID_Device,$type) {
      $logs = new PluginTrackerLogs;
      $tmpc = new PluginTrackerTmpConnections;
      $walks = new PluginTrackerWalk;


      $BridgePortifIndex = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dBasePortIfIndex'],1);
      foreach($BridgePortifIndex as $num=>$BridgePortNumber) {
         $logs->write("tracker_fullsync","*********** TMP ".$BridgePortNumber,$type."][".$ID_Device,1);
         $ifIndexFound = $oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".$BridgePortNumber][""];
         if ($ifIndexFound == $ifIndex) {
            // Search in dot1dTpFdbPort the dynamicdata associate to this BridgePortNumber
            $ArrayBridgePortNumber = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dTpFdbPort'],1);
            foreach($ArrayBridgePortNumber as $num=>$dynamicdata) {
               $BridgePortifIndexFound = $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][""];
               if ($BridgePortifIndexFound == $BridgePortNumber) {
                  $MacAddress = str_replace("0x","",$oidvalues[$oidsModel[0][1]['dot1dTpFdbAddress'].".".$dynamicdata][""]);
                  $MacAddress_tmp = str_split($MacAddress, 2);
                  $MacAddress = $MacAddress_tmp[0];
                  for($i = 1 ; $i < count($MacAddress_tmp) ; $i++) {
                     $MacAddress .= ":".$MacAddress_tmp[$i];
                  }
                  $logs->write("tracker_fullsync","Add TMPConnection = ".$MacAddress."(PortID ".$TMP_ID.")",$type."][".$ID_Device,1);
                  $tmpc->AddConnections($TMP_ID, $MacAddress);
               }
            }
         }
      }

   }


}


?>