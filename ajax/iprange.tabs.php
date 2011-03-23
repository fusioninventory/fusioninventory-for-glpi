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
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if(!isset($_POST["id"])) {
   exit();
}

if(!isset($_POST["sort"])) $_POST["sort"] = "";
if(!isset($_POST["order"])) $_POST["order"] = "";
if(!isset($_POST["withtemplate"])) $_POST["withtemplate"] = "";

switch($_POST['glpi_tab']) {
   case -1:
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange();
      $PluginFusinvsnmpIPRange->permanentTask($_POST["id"], "NETDISCOVERY", $_POST['allowcreate']);
      $PluginFusinvsnmpIPRange->permanentTask($_POST["id"], "SNMPQUERY", $_POST['allowcreate']);
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjob->manageTasksByObject("PluginFusinvsnmpIPRange", $_POST['id']);
      break;

   case 1:
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange();
      $PluginFusinvsnmpIPRange->permanentTask($_POST["id"], "NETDISCOVERY", $_POST['allowcreate']);
      break;

   case 2:
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange();
      $PluginFusinvsnmpIPRange->permanentTask($_POST["id"], "SNMPQUERY", $_POST['allowcreate']);
      break;

   case 3:
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjob->manageTasksByObject("PluginFusinvsnmpIPRange", $_POST['id']);
      break;

   default:

      break;
}

ajaxFooter();

?>