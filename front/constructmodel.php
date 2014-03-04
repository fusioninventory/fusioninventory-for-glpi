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
   @since     2012

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

// DEV
//$_SESSION['plugin_fusioninventory_snmpwalks_id'] = 214;

// END DEV

Html::header(__('FusionInventory', 'fusioninventory'),
             $_SERVER["PHP_SELF"],
             "plugins",
             "fusioninventory",
             "constructdevice");
Session::checkLoginUser();

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_POST['updatesort'])) {
   $_SESSION['glpi_plugin_fusioninventory_constructmodelsort'] = $_POST['sort'];
   Html::back();
}

if (!function_exists('curl_init')) {
   echo '<table  class="tab_cadre_fixe">';
   echo '<tr class="tab_bg_1">';
   echo "<th>";
   echo "<br/>!! cURL extension (PHP) is required... !!<br/><br/>";
   echo "</th>";
   echo "</tr>";
   echo "</table>";
   exit;
}


$pfConstructmodel = new PluginFusioninventoryConstructmodel();
if ($pfConstructmodel->connect()) {
   if ($pfConstructmodel->showAuth()) {
      if (isset($_GET['reset'])) {
         // Unlock the device
         if (isset($_SESSION['plugin_fusioninventory_snmpwalks_id'])) {
            $pfConstructmodel->setUnLock();
         }
         unset($_SESSION['plugin_fusioninventory_snmpwalks_id']);
         if (isset($_SESSION['plugin_fusioninventory_sysdescr'])) {
            unset($_SESSION['plugin_fusioninventory_sysdescr']);
         }
         if (isset($_SESSION['plugin_fusioninventory_itemtype'])) {
            unset($_SESSION['plugin_fusioninventory_itemtype']);
         }
         Html::redirect($CFG_GLPI['root_doc']."/plugins/fusioninventory/front/constructmodel.php");

      } else if (isset($_FILES['snmpwalkfile'])) {
         if (isset($_POST['sysdescr'])) {
            $jsonret = $pfConstructmodel->setLock($_POST['sysdescr'],
                                                  $_POST['itemtype']);
         } else {
            $jsonret = $pfConstructmodel->setLock($_SESSION['plugin_fusioninventory_sysdescr'],
                                                  $_SESSION['plugin_fusioninventory_itemtype']);
         }
         $i = 1;
         $md5 = '';
         while ($i == '1') {
            $md5 = md5(rand(1, 1000000));
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
               WHERE log='".$md5."' ";
            $result = $DB->query($query);
            if ($DB->numrows($result) == "0") {
               $i = 0;
            }
         }

         $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
            (`id`, `plugin_fusioninventory_snmpmodelconstructdevices_id`, `log`)
            VALUES (NULL , '".$jsonret->device->id."', '".$md5."')";
         $id_ins = $DB->query($query_ins);
         move_uploaded_file($_FILES['snmpwalkfile']['tmp_name'],
                            GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$md5);

         $_SESSION['plugin_fusioninventory_snmpwalks_id'] = $jsonret->device->id;
         Html::redirect($CFG_GLPI['root_doc'].
                 "/plugins/fusioninventory/front/constructmodel.php?editoid=".$jsonret->device->id);
      } else if (isset($_POST['deletesnmpwalkfile'])) {
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
                   WHERE `plugin_fusioninventory_snmpmodelconstructdevices_id`='".$_POST['devices_id']."'";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            unlink(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$data['log']);
            $query_delete = "DELETE FROM glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks
               WHERE id='".$data['id']."'";
            $DB->query($query_delete);
         }
         Html::back();
      } else if (isset($_GET['action'])
              AND $_GET['action'] == 'displaydevice'
              AND isset($_SESSION['plugin_fusioninventory_sysdescr'])
              AND $_SESSION['plugin_fusioninventory_sysdescr'] != ''
              AND $_SESSION['plugin_fusioninventory_itemtype'] != '0') {
         $pfConstructmodel->sendGetsysdescr($_SESSION['plugin_fusioninventory_sysdescr'],
                                            $_SESSION['plugin_fusioninventory_itemtype']);

//      } else if ((isset($_GET['editoid'])
//              OR isset($_GET['id']))
//              AND isset($_SESSION['plugin_fusioninventory_snmpwalks_id'])
//              AND $_SESSION['plugin_fusioninventory_snmpwalks_id'] > 0) {
//         $pfConstructDevice = new PluginFusioninventorySNMPConstructDevice();
//    $dataret = $pfConstructmodel->sendGetDevice($_SESSION['plugin_fusioninventory_snmpwalks_id']);
//         $pfConstructDevice->showForm($_SESSION['plugin_fusioninventory_snmpwalks_id'], $dataret);
      } else if (isset($_GET['editoid'])) {
         $pfConstructDevice = new PluginFusioninventorySnmpmodelConstructDevice();
         $dataret = $pfConstructmodel->sendGetDevice($_GET['editoid']);
         $pfConstructDevice->showForm($_GET['editoid'], $dataret);

      } else if (isset($_POST['sendsnmpwalk'])
              AND $_POST['sysdescr'] != '') {
         $message = array();
         $message = $pfConstructmodel->detectWrongSysdescr($_POST['sysdescr']);
         if (!empty($message)){
            $pfConstructmodel->showFormDefineSysdescr($message);
         } else if ($_POST['itemtype'] == '0') {
            $pfConstructmodel->showFormDefineSysdescr();
         } else {
            $pfConstructmodel->sendGetsysdescr($_POST['sysdescr'], $_POST['itemtype']);
         }
      } else if (isset($_GET['devices_id'])) {
         $pfConstructmodel->sendGetsysdescr('', '', $_GET['devices_id']);
      } else if (isset($_GET['action'])) {
         if ($_GET['action'] == "checksysdescr") {
            $pfConstructmodel->showFormDefineSysdescr();
         } else if ($_GET['action'] == "seemodels") {
            if (isset($_POST['models'])) {
               $pfConstructmodel->importModels();
               Html::back();
            } else {
               $pfConstructmodel->showAllModels();
            }
         }
      } else {
         $pfConstructmodel->menu();
      }
   }
   $pfConstructmodel->closeConnection();
}

Html::footer();

?>
