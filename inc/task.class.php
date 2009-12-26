<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginTrackerTask extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_task";
      $this->type = PLUGIN_TRACKER_TASK;
	}


   function Counter($agent_id, $action) {
      global $DB;

      $count = 0;
      $query = "SELECT COUNT(*) as count FROM `glpi_plugin_tracker_task`
         WHERE `agent_id`='".$agent_id."'
            AND `action`='".$action."' ";

      if ($result = $DB->query($query)) {
         $res = $DB->fetch_assoc($result);
         $count = $res["count"];
      }
      return $count;
   }


   function ListTask($agent_id, $action) {
      global $DB;

      $tasks = array();
      $query = "SELECT glpi_plugin_tracker_task.id, param, ifaddr, single,
            glpi_plugin_tracker_task.on_device, glpi_plugin_tracker_task.device_type
            FROM `glpi_plugin_tracker_task`
         INNER JOIN glpi_networking_ports on (glpi_plugin_tracker_task.on_device=glpi_networking_ports.on_device
                                             AND glpi_plugin_tracker_task.device_type=glpi_networking_ports.device_type)
         WHERE `agent_id`='".$agent_id."'
            AND `action`='".$action."' ";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($data=$DB->fetch_array($result)) {
               $tasks[$data["id"]] = $data;
               $type='';
               switch ($tasks[$data["id"]]["device_type"]) {
                  case "networking":
                     $tasks[$data["id"]]["device_type"]='NETWORKING';
                     break;
                  case "printer":
                     $tasks[$data["id"]]["device_type"]='PRINTER';
                     break;
               }
            }
         }
      }
      return $tasks;
   }
}

?>