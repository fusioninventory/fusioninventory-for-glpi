<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'),
             $_SERVER["PHP_SELF"],
             "plugins",
             "fusioninventory",
             "configurationmanagement");

//Session::checkRight('plugin_fusioninventory_blacklist', READ);

PluginFusioninventoryMenu::displayMenu("mini");

$pfConfigurationmanagement = new PluginFusioninventoryConfigurationmanagement();

if (isset($_POST["add"])) {
   $pfConfigurationmanagement->add($_POST);
   Html::back();
} else if (isset($_POST["update"])) {
   $pfConfigurationmanagement->update($_POST);
   Html::back();
} else if (isset($_REQUEST["purge"])) {
   $pfConfigurationmanagement->delete($_POST);
   $pfConfigurationmanagement->redirectToList();
} else if (isset($_POST['update_serialized'])) {
   unset($_POST['update_serialized']);
   unset($_POST['_glpi_csrf_token']);
   $serialized_model = array();
   foreach ($_POST as $key => $value) {
      if ($value != 'notmanaged'
              && $value != 'id'
              && $key != 'id'
              && $key != '_glpi_csrf_token') {
         $serialized_model[$key] = $value;
      }
   }
   $_POST['serialized_referential'] = exportArrayToDB($serialized_model);
   $_POST['sha_referential'] = sha1($_POST['serialized_referential']);
   $pfConfigurationmanagement->update($_POST);
   Html::back();
}


$pfConfigurationmanagement->display(array('id'           => $_GET["id"],
                                           'withtemplate' => ""));
Html::footer();

?>
