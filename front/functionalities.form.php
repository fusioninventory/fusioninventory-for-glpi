<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");
//include (GLPI_ROOT."/plugins/fusioninventory/locales/fr_FR.php");
//include (GLPI_ROOT."/plugins/fusioninventory/inc/config.class.php");
//include (GLPI_ROOT."/plugins/fusioninventory/setup.php");

checkRight("config","w");

commonHeader($LANG['plugin_fusioninventory']["functionalities"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","summary");

if (isset($_POST['update'])) {

   if (empty($_POST['cleaning_days'])) {
      $_POST['cleaning_days'] = 0;
   }

   $_POST['id']=1;
   switch ($_POST['tabs']) {
      
      case 'config' :
         $config1 = new PluginFusioninventoryConfig;
         break;

//      case 'history' :
//         $pficlf = new PluginFusioninventoryConfigLogField();
//         foreach ($_POST as $key=>$val) {
//            if (is_int($key)) {
//               $input = array();
//               $input['id']   = $key;
//               $input['days'] = $val;
//               $pficlf->update($input);
//            }
//         }
//         break;

   }
   if (isset($config1)) {
      $config1->update($_POST);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
//} else if ((isset($_POST['Clean_history']))) {
//   $pfisnmph = new PluginFusioninventoryNetworkPortLog;
//   $pfisnmph->cronCleanHistory();
//   glpi_header($_SERVER['HTTP_REFERER']);
} else if ((isset($_POST['plugin_fusioninventory_lockable_add']))
           OR (isset($_POST['plugin_fusioninventory_lockable_delete']))) {
   PluginFusioninventoryLockable::setLockableForm($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
}

$config = new PluginFusioninventoryConfig;

$config->showForm('');
//$config->showTabs('1', '',$_SESSION['glpi_tabs']);
//echo "<div id='tabcontent'></div>";
//echo "<script type='text/javascript'>loadDefaultTab();</script>";
commonFooter();

?>
