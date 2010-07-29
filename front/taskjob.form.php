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

$pft = new PluginFusioninventoryTaskjob;

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","tasks");

//PluginFusioninventoryProfile::checkRight("Fusioninventory", "agents","r");

PluginFusioninventoryMenu::displayMenu("mini");

if (isset ($_POST["add"])) {
//   PluginFusioninventoryProfile::checkRight("fusioninventory", "Tasks", "w");

   if (isset($_POST['method_id'])) {
      $_POST['method'] = $_POST['method_id'];
   }
   $_POST['plugins_id'] = $_POST['method-'.$_POST['method']];

   if (!empty($_POST['selection'])) {
      $a_selection = explode(',', $_POST['selection']);
      foreach ($a_selection as $num=>$data) {
         $dataDB = explode('-', $data);
         if (!empty($dataDB[0])) {
            $a_selectionDB[][$dataDB[0]] = $dataDB[1];
         }
      }
      $_POST['selection'] = exportArrayToDB($a_selectionDB);
   }
   $pft->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["delete"])) {
//   PluginFusioninventoryProfile::checkRight("fusioninventory", "Tasks", "w");

   $pft->delete($_POST);
   glpi_header($CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/task.php");
} else if (isset($_POST["update"])) {
//   PluginFusioninventoryProfile::checkRight("fusioninventory", "Tasks", "w");

   if (!empty($_POST['selection'])) {
      $a_selection = explode(',', $_POST['selection']);
      foreach ($a_selection as $num=>$data) {
         $dataDB = explode('-', $data);
         if (!empty($dataDB[0])) {
            $a_selectionDB[][$dataDB[0]] = $dataDB[1];
         }
      }
      $_POST['selection'] = exportArrayToDB($a_selectionDB);
   }
   $pft->update($_POST);

   glpi_header($_SERVER['HTTP_REFERER']);
}

commonFooter();

?>