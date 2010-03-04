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

$NEEDED_ITEMS = array (
	"setup",
	"rulesengine",
	"fusioninventory",
	"search"
);

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$ptcd = new PluginFusionInventoryConstructDevice;

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","constructdevice");


plugin_fusioninventory_mini_menu();

if (isset ($_POST["add"])) {
   $query = "SELECT * FROM glpi_plugin_fusioninventory_construct_device
      WHERE sysdescr='".$_POST['sysdescr']."' ";
   $result = $DB->query($query);
	if ($DB->numrows($result) == '0') {
      $ptcd->add($_POST);
   } else {
      $_SESSION["MESSAGE_AFTER_REDIRECT"] = "Déjà existant";
   }
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['addWalk'])) {
   $i = 1;
   while ($i == '1') {
      $md5 = md5(rand(1, 1000000));
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_construct_walks`
         WHERE log='".$md5."' ";
      $result = $DB->query($query);
      if ($DB->numrows($result) == "0") {
         $i = 0;
      }   
   }

   $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_construct_walks` (
`ID` ,
`construct_device_id` ,
`log`
)
VALUES (
NULL , '".$_POST['ID']."', '".$md5."'
)";
   $id_ins = $DB->query($query_ins);
   move_uploaded_file($_FILES['walk']['tmp_name'], GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$md5);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['mib'])) { // Check MIBS
   foreach($_POST['oidsselected'] as $oid) {
      $a_mapping = explode('||', $_POST['links_oid_fields_'.$oid]);

      $query_ins = "INSERT INTO glpi_plugin_fusioninventory_construct_mibs
         (`mib_oid_id`, `construct_device_id`, `mapping_name`,
            `oid_port_counter`, `oid_port_dyn`, `mapping_type`, `vlan`)
         VALUES
         ('".$oid."', '".$_POST['ID']."', '".$a_mapping[1]."',
            '".$_POST['oid_port_counter_'.$oid]."',
            '".$_POST['oid_port_dyn_'.$oid]."', '".$a_mapping[0]."',
              '".$_POST['vlan_'.$oid]."' )";
      $DB->query($query_ins);     
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
	$ptcd->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	$ptcd->delete($_POST);
	glpi_header("plugin_fusioninventory.construct_device.php");
}

$ID = "";
if (isset($_GET["ID"])) {
	$ID = $_GET["ID"];
}

$ptcd->showForm($_SERVER["PHP_SELF"], $ID);
$ptcd->manageWalks($_SERVER["PHP_SELF"], $ID);

commonFooter();

?>