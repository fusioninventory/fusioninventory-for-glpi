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

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

require_once GLPI_ROOT."/inc/includes.php";

Session::checkLoginUser();
// ** Used for rules in the wizard

if (!isset($_GET['wizz'])) {
   if (isset($_POST)) {
      if (isset($_POST['endtask'])) {
         $action = current($_POST['endtask']);
         switch ($action) {
            
            case 'finishdelete':
               if (isset($_SESSION['plugin_fusioninventory_wizard']['ipranges_id'])) {
                  $nb = countElementsInTable("glpi_plugin_fusioninventory_taskjobs", 
                          "`definition` LIKE '%\"PluginFusioninventoryIPRange\":\"".$_SESSION['plugin_fusioninventory_wizard']['ipranges_id']."\"%'");
                  if ($nb == 1) {
                     // Delete iprange
                     $pfIPRange = new PluginFusioninventoryIPRange();
                     $pfIPRange->delete(array('id' => $_SESSION['plugin_fusioninventory_wizard']['ipranges_id']));
                  }
                  $pfTask = new PluginFusioninventoryTask();
                  $pfTask->delete(array('id'=>$_SESSION['plugin_fusioninventory_wizard']['tasks_id']));
               }
              $url = $_SERVER['PHP_SELF']."?wizz=w_start";
              $url = str_replace("wizard.form.php", "wizard.php", $url);
              Html::redirect($url);
              break;
           
           case 'finish':
              $url = $_SERVER['PHP_SELF']."?wizz=w_start";
              $url = str_replace("wizard.form.php", "wizard.php", $url);
              Html::redirect($url);
              break;
           
           case 'runagain':

              break;
            
         }         
      }      
      if (isset($_POST['iprange'])
              AND count($_POST['iprange'] > 0)) {
         $ipranges_id = current($_POST['iprange']);
         if ($ipranges_id == '-1') {
            $pfIPRange = new PluginFusioninventoryIPRange();
            if ($pfIPRange->checkip($_POST)) {
               $_POST['ip_start']  = $_POST['ip_start0'].".".$_POST['ip_start1'].".";
               $_POST['ip_start'] .= $_POST['ip_start2'].".".$_POST['ip_start3'];
               $_POST['ip_end']    = $_POST['ip_end0'].".".$_POST['ip_end1'].".";
               $_POST['ip_end']   .= $_POST['ip_end2'].".".$_POST['ip_end3'];
               $ipranges_id = $pfIPRange->add($_POST);
            } else {
               $ipranges_id = 0;
            }
         }
         if (!(isset($_SESSION['plugin_fusioninventory_wizard'])
                 AND isset($_SESSION['plugin_fusioninventory_wizard']['ipranges_id'])
                 AND $_SESSION['plugin_fusioninventory_wizard']['ipranges_id'] == $ipranges_id)) {
            if (isset($_SESSION['plugin_fusioninventory_wizard']['tasks_id'])) {
               unset($_SESSION['plugin_fusioninventory_wizard']['tasks_id']);
            }
         }
         if (isset($_SESSION["plugin_fusioninventory_forcerun"])) {
            unset($_SESSION["plugin_fusioninventory_forcerun"]);
         }
         $_SESSION['plugin_fusioninventory_wizard']['ipranges_id'] = $ipranges_id;
      }
      $url = $_SERVER['PHP_SELF']."?wizz=".$_POST['nexturl'];
      $url = str_replace("wizard.form.php", "wizard.php", $url);
      Html::redirect($url);
   } else {
      $a_split = explode("?", $_SERVER['HTTP_REFERER']);
      $a_vars = explode("&", $a_split[1]);
      foreach($a_vars as $vars) {
         $endsplit = explode("=", $vars);
         $_GET[$endsplit[0]] = $endsplit[1];
      }
      $url = $_SERVER['PHP_SELF']."?";
      $i = 0;
      foreach($_GET as $key=>$value) {
         if ($i > 0) {
            $url .= "&";
         }
         $url .= $key."=".$value;
         $i++;
      }
      Html::redirect($url);
   }
} else {
   include (GLPI_ROOT . "/plugins/fusioninventory/front/wizard.php");
}

?>