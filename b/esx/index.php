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
   Original Author of file: Walid Nouh
   Co-authors of file:
   Purpose of file: REST communication
   ----------------------------------------------------------------------
 */

if(!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../../..');
}
include (GLPI_ROOT."/inc/includes.php");

$response = false;

//Agent communication using REST protocol
if (isset($_GET['a']) && isset($_GET['d'])) {
   switch ($_GET['a']) {
      case 'getJobs':
         //Specific to ESX

         $response = PluginFusinvinventoryESX::getJobs($_GET['d']);
         break;
      case 'setLog':
         //Generic method to update logs
         PluginFusioninventoryRestCommunication::updateLog($_GET);
         break;
   }
   
   if ($response) {
      echo json_encode($response);
      logDebug($response);
   } else {
      PluginFusioninventoryRestCommunication::sendError();
    }

}

?>