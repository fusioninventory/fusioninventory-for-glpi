<?php
/*
 * @version $Id: updateLicenses.php 8299 2009-05-16 12:23:38Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

$pfit = new PluginFusionInventoryTask;

if (isset($_POST["action"])) {
   if ($_POST["action"] == "INVENTORY") {
      $pfit->showAgentInventory($_POST["on_device"], $_POST["device_type"]);
   } else if ($_POST["action"] == "NETDISCOVERY") {
      $pfit->showAgentNetDiscovery($_POST["on_device"], $_POST["device_type"]);
   } else if ($_POST["action"] == "SNMPQUERY") {
      $pfit->showAgentSNMPQuery($_POST["on_device"], $_POST["device_type"]);
   } else if ($_POST["action"] == "WAKEONLAN") {
      $pfit->showAgentWol($_POST["on_device"], $_POST["device_type"]);
   }
   echo "<input type='hidden' name='action' value='".$_POST["action"]."'/>";
}

?>