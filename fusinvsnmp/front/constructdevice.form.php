<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$PluginFusinvsnmpConstructDevice = new PluginFusinvsnmpConstructDevice();

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","constructdevice");
checkLoginUser();

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_GET['vlan_update'])) {
   $query_update = "UPDATE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
         SET vlan=0
      WHERE plugin_fusinvsnmp_constructdevices_id=".$_GET['id']."
         AND plugin_fusinvsnmp_miboids_id=".$_GET['vlan_update'];
   $DB->query($query_update);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["add"])) {
   $query = "SELECT * FROM glpi_plugin_fusinvsnmp_constructdevices
      WHERE sysdescr='".$_POST['sysdescr']."' ";
   $result = $DB->query($query);
   if ($DB->numrows($result) == '0') {
      $PluginFusinvsnmpConstructDevice->add($_POST);
   } else {
      $_SESSION["MESSAGE_AFTER_REDIRECT"] = "Déjà existant";
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['addWalk'])) {
   $i = 1;
   $md5 = md5(rand(1, 1000000));
   while ($i == '1') {
      $md5 = md5(rand(1, 1000000));
      $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_constructdevicewalks`
         WHERE log='".$md5."' ";
      $result = $DB->query($query);
      if ($DB->numrows($result) == "0") {
         $i = 0;
      }   
   }

   $query_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_constructdevicewalks` (
`id`,
`plugin_fusinvsnmp_constructdevices_id`,
`log`
)
VALUES (
NULL, '".$_POST['id']."', '".$md5."'
)";
   $DB->query($query_ins);
   move_uploaded_file($_FILES['walk']['tmp_name'], GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$md5);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['mib'])) { // Check MIBS
   foreach($_POST['oidsselected'] as $oid) {
      $a_mapping = explode('||', $_POST['links_oid_fields_'.$oid]);

      $mapping = new PluginFusioninventoryMapping;
      $mappings = $mapping->get($a_mapping[0], $a_mapping[1]);
      $mappings_id = $mappings->fields['id'];
      $query_ins = "INSERT INTO glpi_plugin_fusinvsnmp_constructdevice_miboids
         (`plugin_fusinvsnmp_miboids_id`, `plugin_fusinvsnmp_constructdevices_id`,
            `plugin_fusioninventory_mappings_id`,
            `oid_port_counter`, `oid_port_dyn`, `vlan`)
         VALUES
         ('".$oid."', '".$_POST['id']."', '".$mappings_id."',
            '".$_POST['oid_port_counter_'.$oid]."',
            '".$_POST['oid_port_dyn_'.$oid]."',
              '".$_POST['vlan_'.$oid]."' )";
      $DB->query($query_ins);     
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
   $PluginFusinvsnmpConstructDevice->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
   $PluginFusinvsnmpConstructDevice->delete($_POST);
   glpi_header("construct_device.php");
}

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}

$PluginFusinvsnmpConstructDevice->showForm($id);
$PluginFusinvsnmpConstructDevice->manageWalks($_SERVER["PHP_SELF"], $id);

commonFooter();

?>