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

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");

Html::header($LANG['plugin_fusioninventory']['title'][0],
             $_SERVER["PHP_SELF"],
             "plugins",
             "fusioninventory",
             "constructdevice");
Session::checkLoginUser();

PluginFusioninventoryMenu::displayMenu("mini");

$pfConstructmodel = new PluginFusinvsnmpConstructmodel();

if (isset($_FILES['snmpwalkfile'])) {
   if ($pfConstructmodel->connect()) {
      if ($pfConstructmodel->showAuth()) {
   //   if (isset($_POST['sysdescr'])) {
   //      $jsonret = $pfConstructmodel->setLock($_POST['sysdescr'], 
   //                                            $_POST['itemtype']);
   //   } else {

         $jsonret = $pfConstructmodel->setLock($_SESSION['plugin_fusioninventory_snmptool_device']->sysdescr, 
                                               $_SESSION['plugin_fusioninventory_snmptool_itemtype']);

   //   }
         $i = 1;
         $md5 = '';
         while ($i == '1') {
            $md5 = md5(rand(1, 1000000));
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_construct_walks`
               WHERE log='".$md5."' ";
            $result = $DB->query($query);
            if ($DB->numrows($result) == "0") {
               $i = 0;
            }   
         }

         $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_construct_walks` 
            (`id`, `construct_device_id`, `log`)
            VALUES (NULL , '".$jsonret->device->id."', '".$md5."')";
         $id_ins = $DB->query($query_ins);
         move_uploaded_file($_FILES['snmpwalkfile']['tmp_name'], GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/walks/".$md5);

         $_SESSION['plugin_fusioninventory_snmpwalks_id'] = $jsonret->device->id;
         Html::redirect($CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/constructmodeleditoid.form.php?id=".$jsonret->device->id);
      }
      $pfConstructmodel->closeConnection();
   }
}

if ($pfConstructmodel->connect()) {
   if ($pfConstructmodel->showAuth()) {
      $pfConstructmodel->showDeviceForm();   
   }
}

Html::footer();

?>