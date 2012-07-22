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

Html::header(_('FusionInventory'),$_SERVER["PHP_SELF"],"plugins","fusioninventory","fusinvinventory-importxmlfile");

PluginFusioninventoryProfile::checkRight("fusioninventory", "importxmlcomputer","w");

PluginFusioninventoryMenu::displayMenu("mini");

$pfInventoryComputerImportXML = new PluginFusioninventoryInventoryComputerImportXML();

if (isset($_FILES['importfile']['tmp_name'])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "importxmlcomputer","w");

   if ($_FILES['importfile']['tmp_name'] != '') {
      $_SESSION["plugin_fusioninventory_disablelocks"] = 1;
      if ($pfInventoryComputerImportXML->importXMLFile($_FILES['importfile']['tmp_name'])) {
         $_SESSION["MESSAGE_AFTER_REDIRECT"] = _('1');

      } else {
         $_SESSION["MESSAGE_AFTER_REDIRECT"] = _('XML file not valid!');

      }
      unset($_SESSION["plugin_fusioninventory_disablelocks"]);
   } else {
      $_SESSION["MESSAGE_AFTER_REDIRECT"] = _('No file to import!');

   }
	Html::back();
}

$pfImportXML->showForm();

Html::footer();

?>