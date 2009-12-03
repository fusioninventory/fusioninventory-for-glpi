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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginTrackerDiscovery extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_discovery";
		$this->type = PLUGIN_TRACKER_SNMP_DISCOVERY;
	}


   /**
    * Add discovered device to discovered table in MySQL
    *
    *@param $Array : datas
    *  - date
    *  - ip
    *  - name
    *  - description
    *  - serial
    *  - type
    *  - agent_id
    *  - entity
    *  - FK_model
    *  - authSNMP
    *
    *@return Nothing (displays)
    **/
   function addDevice($Array) {
      global $DB;

      // Detect if device exist
      $query_sel = "SELECT *
                    FROM `glpi_plugin_tracker_discovery`
                    WHERE `ifaddr`='".$Array['ip']."'
                          AND `name`='".plugin_tracker_hex_to_string($Array['name'])."'
                          AND `descr`='".$Array['description']."'
                          AND `serialnumber`='".$Array['serial']."'
                          AND `FK_entities`='".$Array['entity']."';";
		$result_sel = $DB->query($query_sel);
		if ($DB->numrows($result_sel) == "0") {
         $insert = 1;
         if (!empty($Array['serial'])) {
            // Detect is a device is same but this another IP (like switch)
            $query_sel = "SELECT *
                          FROM `glpi_plugin_tracker_discovery`
                          WHERE `name`='".plugin_tracker_hex_to_string($Array['name'])."'
                                AND `descr`='".$Array['description']."'
                                AND `serialnumber`='".$Array['serial']."';";
            $result_sel = $DB->query($query_sel);
            if ($DB->numrows($result_sel) > 0) {
               $insert = 0;
            }
         }
         if ($insert == "1") {
            $query = "INSERT INTO `glpi_plugin_tracker_discovery`
                                  (`date`, `ifaddr`, `name`, `descr`, `serialnumber`, `type`,
                                   `FK_agents`, `FK_entities`, `FK_model_infos`,
                                   `FK_snmp_connection`)
                      VALUES('".$Array['date']."',
                             '".$Array['ip']."',
                             '".plugin_tracker_hex_to_string($Array['name'])."',
                             '".$Array['description']."',
                             '".$Array['serial']."',
                             '".$Array['type']."',
                             '".$Array['agent_id']."',
                             '".$Array['entity']."',
                             '".$Array['FK_model']."',
                             '".$Array['authSNMP']."');";
            $DB->query($query);
         }
		}      
   }
}
