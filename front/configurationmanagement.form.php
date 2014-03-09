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
$pfconfmanage_model = new PluginFusioninventoryConfigurationManagement_Model();

if (isset($_POST["add"])) {
   $pfConfigurationmanagement->add($_POST);
   Html::back();
} else if (isset($_POST["update"])) {
   $pfConfigurationmanagement->update($_POST);
   Html::back();
} else if (isset($_REQUEST["purge"])) {
   $pfConfigurationmanagement->delete($_POST);
   $pfConfigurationmanagement->redirectToList();
} else if (isset($_POST['update_managed'])) {
   $treeKey = $_POST['tree'];
   unset($_POST['update_managed']);
   unset($_POST['tree']);
   unset($_POST['_glpi_csrf_token']);

   $pfConfigurationmanagement->getFromDB($_POST['id']);
   $list_fields = $pfconfmanage_model->getListFields();
   $pfConfigurationmanagement->generateTree(
           1,
           $list_fields,
           1,
           '',
           $pfConfigurationmanagement->fields['items_id'],
           $pfConfigurationmanagement->fields['itemtype']);

   $serialized_referential = importArrayFromDB($pfConfigurationmanagement->fields['serialized_referential']);
   if (!is_array($serialized_referential)) {
      $serialized_referential = array();
   }
   if ($treeKey == "/") {
      $serialized_referential['/_managetype_'] = 'managed';
   }
   foreach ($pfConfigurationmanagement->a_trees as $key=>$value) {
      if ($treeKey == "/") {
         $serialized_referential[$key] = $value;
         if ($key != $treeKey
                 && isset($serialized_referential[$key.'/_managetype_'])) {
            unset($serialized_referential[$key.'/_managetype_']);
         }
      } else {
         if ($key == $treeKey) {
            $serialized_referential[$key.'/_managetype_'] = 'managed';
         }
         if (substr($key, 0, strlen($treeKey)) == $treeKey) {
            if ($key != $treeKey
                    && isset($serialized_referential[$key.'/_managetype_'])) {
               unset($serialized_referential[$key.'/_managetype_']);
            }
            $serialized_referential[$key] = $value;
         }
      }
   }
//   foreach ($_POST as $key => $value) {
//      if ($value != 'notmanaged'
//              && $value != 'id'
//              && $key != 'id'
//              && $key != '_glpi_csrf_token') {
//         $serialized_model[$key] = $value;
//      }
//   }
   $_POST['serialized_referential'] = exportArrayToDB($serialized_referential);

   // Prepare the sha for the array cleaned
   $cleanarray = $pfConfigurationmanagement->cleanArray($serialized_referential);

   $_POST['sha_referential'] = sha1(exportArrayToDB($cleanarray));
   $pfConfigurationmanagement->update($_POST);
   Html::back();
}


$pfConfigurationmanagement->display(array('id'           => $_GET["id"],
                                           'withtemplate' => ""));
Html::footer();

?>
