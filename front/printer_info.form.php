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
 * This file is used to manage the printer information form.
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

include ("../../../inc/includes.php");

Session::checkRight('plugin_fusioninventory_printer', READ);

if ((isset($_POST['update'])) && (isset($_POST['id']))) {
      Session::checkRight('plugin_fusioninventory_printer', UPDATE);

   $plugin_fusioninventory_printer = new PluginFusioninventoryPrinter();

   $_POST['printers_id'] = $_POST['id'];
   unset($_POST['id']);

   $query = "SELECT *
             FROM `glpi_plugin_fusioninventory_printers`
             WHERE `printers_id`='".$_POST['printers_id']."' ";
   $result = $DB->query($query);

   if ($DB->numrows($result) == "0") {
      $DB->insert(
         'glpi_plugin_fusioninventory_printers', [
            'printers_id' => $_POST['printers_id']
         ]
      );
      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_printers`
                WHERE `printers_id`='".$_POST['printers_id']."' ";
      $result = $DB->query($query);
   }

   $data = $DB->fetchAssoc($result);
   $_POST['id'] = $data['id'];

   $plugin_fusioninventory_printer->update($_POST);

} else if ((isset($_POST["update_cartridge"])) && (isset($_POST['id']))) {
   Session::checkRight('plugin_fusioninventory_printer', UPDATE);
   $cartridge = new PluginFusioninventoryPrinterCartridge();
   if ($cartridge->getFromDB($_POST['id'])) {
      $cartridge->update($_POST);
   }
}

$arg = "";
for ($i=1; $i <= 5; $i++) {
   $value = '';
   switch ($i) {
      case 1:
         $value = "datetotalpages";
         break;

      case 2:
         $value = "dateblackpages";
         break;

      case 3:
         $value = "datecolorpages";
         break;

      case 4:
         $value = "daterectoversopages";
         break;

      case 5:
         $value = "datescannedpages";
         break;

   }
   if (isset($_POST[$value])) {
      $_SESSION[$value] = $_POST[$value];
   }
}

if (isset($_POST['graph_plugin_fusioninventory_printer_period'])) {
   $fields = ['graph_begin', 'graph_end', 'graph_timeUnit', 'graph_type'];
   foreach ($fields as $field) {
      if (isset($_POST[$field])) {
         $_SESSION['glpi_plugin_fusioninventory_'.$field] = $_POST[$field];
      } else {
         unset($_SESSION['glpi_plugin_fusioninventory_'.$field]);
      }
   }
}

$field = 'graph_printerCompAdd';
if (isset($_POST['graph_plugin_fusioninventory_printer_add'])) {
   if (isset($_POST[$field])) {
      $_SESSION['glpi_plugin_fusioninventory_'.$field] = $_POST[$field];
   }
} else {
   unset($_SESSION['glpi_plugin_fusioninventory_'.$field]);
}

$field = 'graph_printerCompRemove';
if (isset($_POST['graph_plugin_fusioninventory_printer_remove'])) {
   if (isset($_POST[$field])) {
      $_SESSION['glpi_plugin_fusioninventory_'.$field] = $_POST[$field];
   }
} else {
   unset($_SESSION['glpi_plugin_fusioninventory_'.$field]);
}

Html::back();

