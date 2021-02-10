<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the history of network port connections.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the history of network port connections.
 */
class PluginFusioninventoryNetworkPortConnectionLog extends CommonDBTM {


   /**
    * Display form
    *
    * @global object $DB
    * @global array $CFG_GLPI
    * @param array $input
    */
   function showForm($input = []) {
      global $DB, $CFG_GLPI;

      $fi_path = Plugin::getWebDir('fusioninventory');

      $NetworkPort = new NetworkPort();

      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1'>";

      echo "<th>";
      echo __('PID', 'fusioninventory');

      echo " <a href='".$fi_path."/front/agentprocess.form.php'>(".__('All').")</a>";
      echo "</th>";

      echo "<th>";
      echo _n('Date', 'Dates', 1);

      echo "</th>";

      echo "<th>";
      echo _n('Item', 'Items', 1);

      echo "</th>";

      echo "<th>";
      echo __('Status');

      echo "</th>";

      echo "<th>";
      echo _n('Item', 'Items', 1);

      echo "</th>";

      echo "</tr>";

      $condition = '';
      if (!isset($input['plugin_fusioninventory_agentprocesses_id'])) {
         $condition = '';
      } else {
         $condition = "WHERE `plugin_fusioninventory_agentprocesses_id`='".
                           $input['plugin_fusioninventory_agentprocesses_id']."'";
         if (isset($input['created'])) {
            $condition .= " AND `creation`='".$input['created']."' ";
         }
      }
      $query = "SELECT * FROM `".$this->getTable()."`
         ".$condition."
         ORDER BY `date`DESC, `plugin_fusioninventory_agentprocesses_id` DESC";
      if (!isset($input['process_number'])) {
         $query .= " LIMIT 0, 500";
      }

      $result = $DB->query($query);
      if ($result) {
         while ($data=$DB->fetchArray($result)) {
            echo "<tr class='tab_bg_1 center'>";

            echo "<td>";
            echo "<a href='".$fi_path."/front/agentprocess.form.php?h_process_number=".
                    $data['plugin_fusioninventory_agentprocesses_id']."'>".
            $data['plugin_fusioninventory_agentprocesses_id']."</a>";
            echo "</td>";

            echo "<td>";
            echo Html::convDateTime($data['date']);
            echo "</td>";

            echo "<td>";
            $NetworkPort->getFromDB($data['networkports_id_source']);
            $item = new $NetworkPort->fields["itemtype"];
            $item->getFromDB($NetworkPort->fields["items_id"]);
            $link1 = $item->getLink(1);

            $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networkport.form.php?id=".
                        $NetworkPort->fields["id"] . "\">";
            if (rtrim($NetworkPort->fields["name"]) != "") {
               $link .= $NetworkPort->fields["name"];
            } else {
               $link .= __('Without name');
            }
            $link .= "</a>";
            echo $link." ".__('on', 'fusioninventory')." ".$link1;
            echo "</td>";

            echo "<td>";
            if ($data['creation'] == '1') {
               echo "<img src='".$fi_path."/pics/connection_ok.png'/>";
            } else {
               echo "<img src='".$fi_path."/pics/connection_notok.png'/>";
            }
            echo "</td>";

            echo "<td>";
            $NetworkPort->getFromDB($data['networkports_id_destination']);
            $item = new $NetworkPort->fields["itemtype"];
            $item->getFromDB($NetworkPort->fields["items_id"]);
            $link1 = $item->getLink(1);
            $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networkport.form.php?id=".
                        $NetworkPort->fields["id"] . "\">";
            if (rtrim($NetworkPort->fields["name"]) != "") {
               $link .= $NetworkPort->fields["name"];
            } else {
               $link .= __('Without name');
            }
            $link .= "</a>";
            echo $link." ".__('on', 'fusioninventory')." ".$link1;
            echo "</td>";

            echo "</tr>";
         }
      }
      echo "</table>";
   }
}
