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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

if (isset ($_POST["startagent"])) {
   $pta = new PluginTrackerAgents;
   $ptt = new PluginTrackerTask;
   $ptais = new PluginTrackerAgentsInventoryState;

   if ($ptt->addTask(0, 0, 'INVENTORY', $_POST['agentID'])) {
      $ptais->changeStatus($_POST['ID'], 1);
      if ($pta->RemoteStartAgent($_POST['agentID'], $_POST['ip'])) {
         $ptais->changeStatus($_POST['ID'], 2);
      }
   }
	glpi_header($_SERVER['HTTP_REFERER']);
}

$ptais = new PluginTrackerAgentsInventoryState;
$ptais->computerState($_SERVER["PHP_SELF"], $_GET["ID"]);

?>