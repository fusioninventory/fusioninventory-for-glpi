<?php

/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

function plugin_fusinvinventory_getSearchOption() {

   
}



function plugin_fusinvinventory_giveItem($type,$id,$data,$num) {
	return "";
}



// Define Dropdown tables to be manage in GLPI :
function plugin_fusinvinventory_getDropdown() {
   return array ();
}



/* Cron */
function cron_plugin_fusinvinventory() {

   return 1;
}



function plugin_fusinvinventory_install() {
	global $DB, $LANG, $CFG_GLPI;

   include (GLPI_ROOT . "/plugins/fusinvinventory/install/install.php");
   pluginFusinvinventoryInstall("2.3.0");

   return true;
}



// Uninstall process for plugin : need to return true if succeeded
function plugin_fusinvinventory_uninstall() {
   return PluginFusinvinventorySetup::uninstall();
}



/**
* Check if Fusinvinventory need to be updated
*
* @param
*
* @return 0 (no need update) OR 1 (need update)
**/
function plugin_fusinvinventory_needUpdate() {
   $version = "2.3.0";
   include (GLPI_ROOT . "/plugins/fusinvinventory/install/update.php");
   $version_detected = pluginFusinvinventoryGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      return 1;
   } else {
      return 0;
   }
}



// Define headings added by the plugin //
function plugin_get_headings_fusinvinventory($item,$withtemplate) {



}



// Define headings actions added by the plugin
function plugin_headings_actions_fusinvinventory($type) {


}



function plugin_headings_fusinvinventory($type,$id,$withtemplate=0) {
	global $CFG_GLPI;

}



function plugin_fusinvinventory_MassiveActions($type) {
	global $LANG;

}



function plugin_fusinvinventory_MassiveActionsDisplay($type, $action) {
	global $LANG, $CFG_GLPI, $DB;


}



function plugin_fusinvinventory_MassiveActionsProcess($data) {
	global $LANG;

}



// Massive Action functions
function plugin_fusinvinventory_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield) {
	global $LINK_ID_TABLE,$LANG;


}



function plugin_fusinvinventory_addSelect($type,$id,$num) {
	return "";
}



function plugin_fusinvinventory_forceGroupBy($type) {
    return false;
}



function plugin_fusinvinventory_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables) {
	return "";
}



function plugin_fusinvinventory_addOrderBy($type,$id,$order,$key=0) {
	return "";
}



function plugin_fusinvinventory_addWhere($link,$nott,$type,$id,$val) {
	return "";
}



function plugin_pre_item_purge_fusinvinventory($parm) {
	global $DB;


}



function plugin_pre_item_delete_fusinvinventory($parm) {
	return $parm;
}



/**
 * Hook after updates
 *
 * @param $parm
 * @return nothing
 *
**/
function plugin_item_update_fusinvinventory($parm) {



}



function plugin_item_add_fusinvinventory($parm) {
}

?>