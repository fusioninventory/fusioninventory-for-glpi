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

function plugin_fusinvdeploy_getSearchOption() {


}



function plugin_fusinvdeploy_giveItem($type,$id,$data,$num) {
	return "";
}



// Define Dropdown tables to be manage in GLPI :
function plugin_fusinvdeploy_getDropdown() {
   return array ();
}



/* Cron */
function cron_plugin_fusinvdeploy() {

   return 1;
}



function plugin_fusinvdeploy_install() {
	global $DB, $LANG, $CFG_GLPI;

   include (GLPI_ROOT . "/plugins/fusinvdeploy/install/install.php");
   pluginFusinvdeployInstall();

   return true;
}



// Uninstall process for plugin : need to return true if succeeded
function plugin_fusinvdeploy_uninstall() {
   include (GLPI_ROOT . "/plugins/fusinvdeploy/install/install.php");
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
   include (GLPI_ROOT . "/plugins/fusinvdeploy/install/update.php");
   $version_detected = pluginFusinvdeployGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      return 1;
   } else {
      return 0;
   }
}



// Define headings added by the plugin //
function plugin_get_headings_fusinvdeploy($item,$withtemplate) {



}



// Define headings actions added by the plugin
function plugin_headings_actions_fusinvdeploy($type) {


}



function plugin_headings_fusinvdeploy($type,$id,$withtemplate=0) {
	global $CFG_GLPI;

}



function plugin_fusinvdeploy_MassiveActions($type) {
	global $LANG;

}



function plugin_fusinvdeploy_MassiveActionsDisplay($type, $action) {
	global $LANG, $CFG_GLPI, $DB;


}



function plugin_fusinvdeploy_MassiveActionsProcess($data) {
	global $LANG;

}



// Massive Action functions
function plugin_fusinvdeploy_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield) {
	global $LINK_ID_TABLE,$LANG;


}



function plugin_fusinvdeploy_addSelect($type,$id,$num) {
	return "";
}



function plugin_fusinvdeploy_forceGroupBy($type) {
    return false;
}



function plugin_fusinvdeploy_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables) {
	return "";
}



function plugin_fusinvdeploy_addOrderBy($type,$id,$order,$key=0) {
	return "";
}



function plugin_fusinvdeploy_addWhere($link,$nott,$type,$id,$val) {
	return "";
}



function plugin_pre_item_purge_fusinvdeploy($parm) {
	global $DB;


}



function plugin_pre_item_delete_fusinvdeploy($parm) {
	return $parm;
}



/**
 * Hook after updates
 *
 * @param $parm
 * @return nothing
 *
**/
function plugin_item_update_fusinvdeploy($parm) {



}



function plugin_item_add_fusinvdeploy($parm) {
}

?>