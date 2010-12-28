<?php

/*
 * @version $Id: connection.function.php 6975 2008-06-13 15:43:18Z remi $
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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



function plugin_fusioninventory_giveItem($type,$id,$data,$num) {
   global $CFG_GLPI, $LANG;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

   switch ($table.'.'.$field) {

      case "glpi_plugin_fusioninventory_tasks.id" :
         // Get progression bar
         return "";
         break;

      case "glpi_plugin_fusioninventory_taskjobs.status":
         $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
         return $PluginFusioninventoryTaskjobstatus->stateTaskjob($data['id'], '200', 'htmlvar', 'simple');
         break;

   }
   return "";
}

// Define Dropdown tables to be manage in GLPI :
function plugin_fusioninventory_getDropdown() {
   return array ();
}

/* Cron */
function cron_plugin_fusioninventory() {
//   TODO :Disable for the moment (may be check if functions is good or not
//   $ptud = new PluginFusioninventoryUnknownDevice;
//   $ptud->CleanOrphelinsConnections();
//   $ptud->FusionUnknownKnownDevice();
//   TODO : regarder les 2 lignes juste en dessous !!!!!
//   #Clean server script processes history
//   $pfisnmph = new PluginFusioninventoryNetworkPortLog;
//   $pfisnmph->cronCleanHistory();



   return 1;
}



function plugin_fusioninventory_install() {
   global $DB, $LANG, $CFG_GLPI;

   $version = "2.3.0";
   include (GLPI_ROOT . "/plugins/fusioninventory/install/update.php");
   $version_detected = pluginFusioninventoryGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      pluginFusioninventoryUpdate($version_detected);
   } else {
      include (GLPI_ROOT . "/plugins/fusioninventory/install/install.php");
      pluginFusioninventoryInstall($version);
   }

   return true;
}

// Uninstall process for plugin : need to return true if succeeded
function plugin_fusioninventory_uninstall() {
   if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   }
   return PluginFusioninventorySetup::uninstall();
}

// Define headings added by the plugin //
function plugin_get_headings_fusioninventory($item,$withtemplate) {
   global $LANG;

   switch (get_class($item)) {
      case 'Computer' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[2] = $LANG['plugin_fusioninventory']["title"][5];
            }
            $array[3] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["task"][18];
            return $array;
         }
         break;

      case 'Monitor' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[1] = $LANG['plugin_fusioninventory']["title"][5];
            }
            return $array;
         }
         break;

      case 'NetworkEquipment' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[1] = $LANG['plugin_fusioninventory']["title"][5];
            }
            $array[2] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["task"][18];
            return $array;
         }
         break;

      case 'Printer' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[1] = $LANG['plugin_fusioninventory']["title"][5];
            }
            $array[2] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["task"][18];
            return $array;
         }
         break;

      case 'Profile' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[1] = $LANG['plugin_fusioninventory']["title"][0];
            }
            return $array;
         }
         break;

      case 'PluginFusioninventoryUnknownDevice' :
         $array = array ();
         if ($_GET['id'] > 0) {
            $array[1] = $LANG['plugin_fusioninventory']["xml"][0];
         }
         return $array;
         break;

   }
   return false;
}

// Define headings actions added by the plugin
//function plugin_headings_actions_fusioninventory($type) {
function plugin_headings_actions_fusioninventory($item) {
//   switch ($type) {
   switch (get_class($item)) {
      case 'Computer' :
         $array = array ();
         $array[2] = "plugin_headings_fusioninventory_locks";
         $array[3] = "plugin_headings_fusioninventory_tasks";
         return $array;
         break;

      case 'Monitor' :
         return array (
            1 => "plugin_headings_fusioninventory_locks"
         );
         break;

      case 'Printer' :
         return array (
            1 => "plugin_headings_fusioninventory_locks",
            2 => "plugin_headings_fusioninventory_tasks"
         );
         break;

      case 'NetworkEquipment' :
         return array (
            1 => "plugin_headings_fusioninventory_locks",
            2 => "plugin_headings_fusioninventory_tasks"
         );
         break;

      case 'Profile' :
         return array(
            1 => "plugin_headings_fusioninventory"
            );
         break;

      case 'PluginFusioninventoryUnknownDevice' :
         $array = array ();
         $array[1] = "plugin_headings_fusioninventory_xml";
         return $array;
         break;

   }
   return false;
}


//function plugin_headings_fusioninventory_locks($type, $id) {
function plugin_headings_fusioninventory_locks($item) {
   $type = get_Class($item);
   $id = $item->getField('id');
   $fusioninventory_locks = new PluginFusioninventoryLock();
   $fusioninventory_locks->showForm(getItemTypeFormURL('PluginFusioninventoryLock').'?id='.$id,
                                                       $type, $id);
}

function plugin_headings_fusioninventory_tasks($item, $itemtype='', $items_id=0) {
   if ($itemtype == '') {
      $itemtype = get_Class($item);
      $items_id = $item->getField('id');
   }
   if ($itemtype == 'Computer') {
      // Possibility to remote agent
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $PluginFusioninventoryAgent->forceRemoteAgent();
   }
   $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
   $PluginFusioninventoryTaskjob->manageTasksByObject($itemtype, $items_id);

}




function plugin_headings_fusioninventory($item, $withtemplate=0) {
	global $CFG_GLPI;

	switch (get_class($item)) {
		case 'Profile' :

			$PluginFusioninventoryProfile = new PluginFusioninventoryProfile();
//			if (!$prof->GetfromDB($id)) {
//				PluginFusioninventoryDb::createaccess($id);
//         }
			$PluginFusioninventoryProfile->showProfileForm($item->getField('id'), GLPI_ROOT."/plugins/fusioninventory/front/profile.php");
		break;
	}
}



function plugin_headings_fusioninventory_xml($item) {
   global $LANG;

   $id = $_POST['id'];

   $folder = substr($id,0,-1);
   if (empty($folder)) {
      $folder = '0';
   }
   if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/u".$folder."/u".$id)) {
      $xml = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/u".$folder."/u".$id);
      $xml = str_replace("<", "&lt;", $xml);
      $xml = str_replace(">", "&gt;", $xml);
      $xml = str_replace("\n", "<br/>", $xml);
      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
      echo "<tr>";
      echo "<th>".$LANG['plugin_fusioninventory']["xml"][0];
      echo " (".$LANG['common'][26]."&nbsp;: " . convDateTime(date("Y-m-d H:i:s", filemtime(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/u".$folder."/u".$id))).")";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td width='130'>";
      echo "<pre width='130'>".$xml."</pre>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }
}



function plugin_fusioninventory_MassiveActions($type) {
   global $LANG;
   switch ($type) {
      case NETWORKING_TYPE :
         return array (
            "plugin_fusioninventory_manage_locks" => $LANG['plugin_fusioninventory']["functionalities"][75]
         );
         break;

      case PRINTER_TYPE :
         return array (
            "plugin_fusioninventory_manage_locks" => $LANG['plugin_fusioninventory']["functionalities"][75]
         );
         break;
   }
   return array ();
}

function plugin_fusioninventory_MassiveActionsDisplay($type, $action='') {
   global $LANG, $CFG_GLPI, $DB;
   switch ($type) {
      case NETWORKING_TYPE :
         switch ($action) {
            case "plugin_fusioninventory_manage_locks" :
               $pfil = new PluginFusioninventoryLock;
               $pfil->showForm($_SERVER["PHP_SELF"], NETWORKING_TYPE, '');
               break;
         }
         break;

      case PRINTER_TYPE :
         switch ($action) {
            case "plugin_fusioninventory_manage_locks" :
               $pfil = new PluginFusioninventoryLock;
               $pfil->showForm($_SERVER["PHP_SELF"], NETWORKING_TYPE, '');
               break;
         }
         break;
   }
   return "";
}

function plugin_fusioninventory_MassiveActionsProcess($data) {
   global $LANG;
   switch ($data['action']) {
      case "plugin_fusioninventory_manage_locks" :
         if (($data['itemtype'] == NETWORKING_TYPE) OR ($data['itemtype'] == PRINTER_TYPE)) {
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  if (isset($data["lockfield_fusioninventory"])&&count($data["lockfield_fusioninventory"])){
                     $tab=PluginFusioninventoryLock::exportChecksToArray($data["lockfield_fusioninventory"]);
                        PluginFusioninventoryLock::setLockArray($data['type'], $key, $tab);
                  } else {
                     PluginFusioninventoryLock::setLockArray($data['type'], $key, array());
                  }
               }
            }
         }
         break;
   }
}

// How to display specific update fields ?
// Massive Action functions
function plugin_fusioninventory_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield) {
   global $LANG;
   // Table fields
   //echo $table.".".$field."<br/>";
   switch ($table.".".$field) {
      case 'glpi_plugin_fusioninventory_agents.id' :
         Dropdown::show("PluginFusioninventoryAgent",
                        array('name' => $linkfield,
                              'comment' => false));
         return true;
         break;

      case 'glpi_plugin_fusioninventory_agents.nb_process_query' :
         Dropdown::showInteger("nb_process_query", $linkfield,1,200);
         return true;
         break;

      case 'glpi_plugin_fusioninventory_agents.nb_process_discovery' :
         Dropdown::showInteger("nb_process_discovery", $linkfield,1,400);
         return true;
         break;

      case 'glpi_plugin_fusioninventory_agents.logs' :
         $ArrayValues = array();
         $ArrayValues[]= $LANG["choice"][0];
         $ArrayValues[]= $LANG["choice"][1];
         $ArrayValues[]= $LANG["setup"][137];
         Dropdown::showFromArray('logs', $ArrayValues,
                                 array('value'=>$linkfield));
         return true;
         break;

      case 'glpi_plugin_fusioninventory_agents.core_discovery' :
         Dropdown::showInteger("core_discovery", $linkfield,1,32);
         return true;
         break;

      case 'glpi_plugin_fusioninventory_agents.core_query' :
         Dropdown::showInteger("core_query", $linkfield,1,32);
         return true;
         break;

      case 'glpi_plugin_fusioninventory_agents.threads_discovery' :
         Dropdown::showInteger("threads_discovery", $linkfield,1,400);
         return true;
         break;

      case 'glpi_plugin_fusioninventory_agents.threads_query' :
         Dropdown::showInteger("threads_query", $linkfield,1,400);
         return true;
         break;

      case 'glpi_entities.name' :
         if (isMultiEntitiesMode()) {
            Dropdown::show("Entities",
                           array('name' => "entities_id",
                           'value' => $_SESSION["glpiactive_entity"]));
         }
         return true;
         break;
   }
   return false;
}



function plugin_fusioninventory_addSelect($type,$id,$num) {
   return "";
}


function plugin_fusioninventory_forceGroupBy($type) {
    return false;
}


function plugin_fusioninventory_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables) {
   return "";
}


function plugin_fusioninventory_addOrderBy($type,$id,$order,$key=0) {
   return "";
}


function plugin_fusioninventory_addWhere($link,$nott,$type,$id,$val) {
   return "";
}

function plugin_pre_item_update_fusioninventory($parm) {
   if ($parm->fields['directory'] == 'fusioninventory') {
      $plugin = new Plugin();

      $a_plugins = PluginFusioninventoryModule::getAll();
      foreach($a_plugins as $datas) {
         $plugin->unactivate($datas['id']);
      }
   }
}

function plugin_pre_item_purge_fusioninventory($parm) {
   global $DB;
   
   switch (get_class($parm)) {

      case 'Computer':
         // Delete link between computer and agent fusion
         $query = "UPDATE `glpi_plugin_fusioninventory_agents`
                     SET `items_id` = '0'
                        AND `itemtype` = '0'
                     WHERE `items_id` = '".$parm["id"]."'
                        AND `itemtype` = '1' ";
         $DB->query($query);
         break;

   }
   return $parm;
}



function plugin_pre_item_delete_fusioninventory($parm) {
   return $parm;
}



/**
 * Hook after updates
 *
 * @param $parm
 * @return nothing
 *
**/
function plugin_item_update_fusioninventory($parm) {
   if (isset($_SESSION["glpiID"]) AND $_SESSION["glpiID"]!='') { // manual task
      $plugin = new Plugin;
      if ($plugin->isActivated('fusioninventory')) {
         // lock fields which have been updated
         $type=$parm['type'];
         $id=$parm['id'];
         $fieldsToLock=$parm['updates'];
         $lockables = PluginFusioninventoryLockable::getLockableFields('', $type);
         $fieldsToLock = array_intersect($fieldsToLock, $lockables); // do not lock unlockable fields
         PluginFusioninventoryLock::addLocks($type, $id, $fieldsToLock);
      }
   }
}


function plugin_item_add_fusioninventory($parm) {
}


function plugin_item_purge_fusioninventory($parm) {
   global $DB;

   switch (get_class($parm)) {

      case 'NetworkPort_NetworkPort':
         // If remove connection of a hub port (unknown device), we must delete this port too
         $NetworkPort = new NetworkPort();
         $NetworkPort->getFromDB($parm->getField('networkports_id_1'));
         if ($NetworkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $NetworkPort->delete($NetworkPort->fields);
         }
         $NetworkPort->getFromDB($parm->getField('networkports_id_2'));
         if ($NetworkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $NetworkPort->delete($NetworkPort->fields);
         }

         break;

   }
   return $parm;
}



?>