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
   @author    Kevin Roy
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
include (GLPI_ROOT."/inc/includes.php");
Session::checkLoginUser();

if (! empty($_FILES)) {
   if ($_FILES["file"]["error"] > 0) {
      Toolbox::logDebug("Error on import: " . $_FILES["file"]["error"] . "\n");
   } else {
      //logDebug("Upload: " . $_FILES["file"]["name"] . "\n");
      //logDebug("Type: " . $_FILES["file"]["type"] . "\n");
      //logDebug("Size: " . ($_FILES["file"]["size"] / 1024) . " Kb\n");
      //logDebug("Stored in: " . $_FILES["file"]["tmp_name"]);

      $data = json_decode(file_get_contents($_FILES["file"]["tmp_name"]));
      //logDebug("JSON DATA:\n" . print_r($data,true) . "\n");
      PluginFusinvdeployPackage::import_json($data);

   }
}
//TODO: need to verify data we are receiving

?>