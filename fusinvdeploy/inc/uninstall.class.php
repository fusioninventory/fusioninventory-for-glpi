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
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployUninstall extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][15];
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      switch(get_class($item)) {
         case 'PluginFusinvdeployPackage': return $LANG['plugin_fusinvdeploy']['package'][15];
      }
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      switch(get_class($item)) {
         case 'PluginFusinvdeployPackage':
            $obj = new self;
            $obj->showForm($_POST['id']);
            break;
      }
   }

   static function showForm($id){
      global $CFG_GLPI, $LANG;

      $disabled = "false";
      if (!PluginFusinvdeployPackage::canEdit($id)) {
         $disabled = "true";
         PluginFusinvdeployPackage::showEditDeniedMessage($id,
               $LANG['plugin_fusinvdeploy']['package'][24]);
      }

      if(isset($_POST["glpi_tab"])) {
         switch($_POST["glpi_tab"]){
            case -1 :
               $render = "alluninstall";
               break;
            default:
               $render = "uninstall";
               break;
         }
      }

      echo "<table class='deploy_extjs'>
            <tbody>
               <tr>
                  <td id='".$render."Check'>
                  </td>
               </tr>
               <tr><td><br /></td></tr>
               <tr>
                  <td id='".$render."File'></td></td>
               </tr>
               <tr><td><br /></td></tr>
               <tr>
                  <td id='".$render."Action'></td>
               </tr>
            </tbody>
         </table>";

      // Include JS
      require GLPI_ROOT."/plugins/fusinvdeploy/js/package_check.front.php";
      require GLPI_ROOT."/plugins/fusinvdeploy/js/package_file.front.php";
      require GLPI_ROOT."/plugins/fusinvdeploy/js/package_action.front.php";
   }

}

?>