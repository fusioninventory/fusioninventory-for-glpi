<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @author    Walid Nouh
   @co-author
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");
Session::checkCentralAccess();

header("Content-Type: text/json; charset=UTF-8");
Html::header_nocache();

if (isset($_REQUEST['id'])) {
   $agent = new PluginFusioninventoryAgent;
   $agent->getFromDB((int) $_REQUEST['id']);

   if (isset($_REQUEST['action'])) {

      switch ($_REQUEST['action']) {
         case "get_status":
            $agentStatus = $agent->getStatus();
            $agentStatus['waiting'] = false;

            switch ($agentStatus['message']) {

               case 'executing scheduled tasks':
               case 'running':
                  $agentStatus['message'] = __('Running');
                  break;

               case 'noanswer':
                  $agentStatus['message'] = "<i class='fa fa-exclamation-triangle'></i>".
                                            __('cannot contact the agent', 'fusioninventory');
                  break;

               case 'waiting':
                  $agentStatus['waiting'] = true;
                  $agentStatus['message'] = sprintf(
                     __('Available on %1$s', 'fusioninventory'),
                     '<a target="_blank" href="'. $agentStatus['url_ok'] . '">' . $agentStatus['url_ok'] . '</a>'
                  );
                  break;

               default:
                  if (strstr($agentStatus['message'], 'running')) {
                     $agentStatus['message'] = $agentStatus['message'];
                  } else {
                     $agentStatus['message'] = "SELinux problem, do 'setsebool -P httpd_can_network_connect on'";
                  }
                  break;


            }
            echo json_encode($agentStatus);
            break;

         case "start_agent";
            if ($agent->wakeUp()) {
               Session::addMessageAfterRedirect(__('The agent is running', 'fusioninventory'));
               $response = [
                  'status' => 'ok'
               ];

            } else {
               Session::addMessageAfterRedirect(__('Impossible to communicate with agent!', 'fusioninventory'));
               $response = [
                  'status' => 'ko'
               ];
            }

            echo json_encode($response);
            break;

      }
   }
}
