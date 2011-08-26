<?php

/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

function plugin_fusinvdeploy_install() {
   include_once (GLPI_ROOT . "/plugins/fusinvdeploy/install/install.php");
   pluginFusinvdeployInstall();

   return true;
}



// Uninstall process for plugin : need to return true if succeeded
function plugin_fusinvdeploy_uninstall() {
   include_once (GLPI_ROOT . "/plugins/fusinvdeploy/install/install.php");
   pluginFusinvdeployUninstall();
}



/**
* Check if Fusinvdeploy need to be updated
*
* @param
*
* @return 0 (no need update) OR 1 (need update)
**/
function plugin_fusinvdeploy_needUpdate() {
   $version = "2.3.0";
   include_once (GLPI_ROOT . "/plugins/fusinvdeploy/install/update.php");
   $version_detected = pluginFusinvdeployGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      return 1;
   } else {
      return 0;
   }
}



function plugin_fusinvdeploy_MassiveActions($type) {
   global $LANG;

   switch ($type) {
      case 'PluginFusinvdeployPackage' :
         return array("plugin_fusinvdeploy_duplicatePackage" => $LANG['buttons'][54]);
         break;
   }
   return array();
}

function plugin_fusinvdeploy_MassiveActionsDisplay($options=array()) {
   global $LANG;

   switch ($options['itemtype']) {
      case 'PluginFusinvdeployPackage' :
         switch ($options['action']) {
            case "plugin_fusinvdeploy_duplicatePackage" :
               echo $LANG['plugin_fusinvdeploy']['package'][25].":&nbsp;<input type='text' name='newname' value=''>";
               echo "&nbsp;<input type='submit' name='massiveaction' class='submit' value='".
                     $LANG["buttons"][2]."'>&nbsp;";
            break;
         }
         break;
   }
   return "";
}

function plugin_fusinvdeploy_MassiveActionsProcess($data) {
   global $LANG;

   switch ($data['action']) {
      case 'plugin_fusinvdeploy_duplicatePackage' :
         if ($data['itemtype'] == 'PluginFusinvdeployPackage') {
            $package = new PluginFusinvdeployPackage;
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  if ($package->getFromDB($key)) {
                     $package->package_clone($data['newname']);
                  }
               }
            }

         }
         break;
   }
}


?>
