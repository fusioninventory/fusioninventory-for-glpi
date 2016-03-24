<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'),
             $_SERVER["PHP_SELF"],
             "plugins",
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
      $zip = zip_open($_FILES['importfile']['tmp_name']);

      if (!$zip) {
         error_log("Zip failure");
         $_SESSION["MESSAGE_AFTER_REDIRECT"] = __("Can't read zip file!", 'fusioninventory');
      } else {
         error_log("Zip ok");
         while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry, "r")) {
               $xml= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
               error_log("toto");
               error_log($xml);
               if (!empty($xml)) {
                  $_SESSION['glpi_fusionionventory_nolock'] = TRUE;
                  $pfCommunication->handleOCSCommunication('', $xml);
                  unset($_SESSION['glpi_fusionionventory_nolock']);
               }
               zip_entry_close($zip_entry);
            }
         }
         zip_close($zip);
      }
   } else if (preg_match('/\.(ocs|xml)/i', $_FILES['importfile']['name'])) {

      $xml = file_get_contents($_FILES['importfile']['tmp_name']);
      $_SESSION['glpi_fusionionventory_nolock'] = TRUE;
      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');
      unset($_SESSION['glpi_fusionionventory_nolock']);
   } else {
      $_SESSION["MESSAGE_AFTER_REDIRECT"] = __('No file to import!', 'fusioninventory');
   }
   Html::back();
}

$pfInventoryComputerImportXML = new PluginFusioninventoryInventoryComputerImportXML();
$pfInventoryComputerImportXML->showForm();

Html::footer();

?>
