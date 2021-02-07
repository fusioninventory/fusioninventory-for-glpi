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
 * This file is used to manage the computer import inventry XML form.
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

Html::header(__('FusionInventory', 'fusioninventory'),
             $_SERVER["PHP_SELF"],
             "admin",
             "pluginfusioninventorymenu",
             "inventorycomputerimportxml");

Session::checkRight('plugin_fusioninventory_importxml', CREATE);

PluginFusioninventoryMenu::displayMenu("mini");

$pfCommunication = new PluginFusioninventoryCommunication();

if (isset($_FILES['importfile']) && $_FILES['importfile']['tmp_name'] != '') {

   error_log($_FILES['importfile']['name']);
   ini_set("memory_limit", "-1");
   ini_set("max_execution_time", "0");

   if (preg_match('/\.zip/i', $_FILES['importfile']['name'])) {
      $zip = new ZipArchive;
      $zip->open($_FILES['importfile']['tmp_name']);

      if (!$zip) {
         error_log("Zip failure");
         Session::addMessageAfterRedirect(
            __("Can't read zip file!", 'fusioninventory'),
            ERROR
         );
      } else {
         for ($n = 0; $n < $zip->numFiles; $n++) {
            $filename = $zip->getNameIndex($n);
            $xml = $zip->getFromName($zip->getNameIndex($n));
            if (!empty($xml)) {
               $_SESSION['glpi_fusionionventory_nolock'] = true;
               $pfCommunication->handleOCSCommunication('', $xml);
               unset($_SESSION['glpi_fusionionventory_nolock']);
            }
         }
         $zip->close();
      }
   } else if (preg_match('/\.(ocs|xml)/i', $_FILES['importfile']['name'])) {

      $xml = file_get_contents($_FILES['importfile']['tmp_name']);
      $_SESSION['glpi_fusionionventory_nolock'] = true;
      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');
      unset($_SESSION['glpi_fusionionventory_nolock']);
   } else {
      Session::addMessageAfterRedirect(
         __('No file to import!', 'fusioninventory'),
         ERROR
      );
   }
   Html::back();
}

$pfInventoryComputerImportXML = new PluginFusioninventoryInventoryComputerImportXML();
$pfInventoryComputerImportXML->showForm();

Html::footer();

