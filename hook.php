<?php

/*
 * @version $Id: connection.function.php 6975 2008-06-13 15:43:18Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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




//function plugin_fusioninventory_getAddSearchOptions() {
//	global $LANG;
//	$sopt = array ();
//
//	$config = new PluginFusioninventoryConfig;
//
//	// Part header
//	$sopt['PluginFusioninventoryError']['common'] = $LANG['plugin_fusioninventory']["errors"][0];
//
//	$sopt['PluginFusioninventoryError'][1]['table'] = 'glpi_plugin_fusioninventory_errors';
//	$sopt['PluginFusioninventoryError'][1]['field'] = 'ip';
//	$sopt['PluginFusioninventoryError'][1]['linkfield'] = 'ip';
//	$sopt['PluginFusioninventoryError'][1]['name'] = $LANG['plugin_fusioninventory']["errors"][1];
//
//	$sopt['PluginFusioninventoryError'][30]['table'] = 'glpi_plugin_fusioninventory_errors';
//	$sopt['PluginFusioninventoryError'][30]['field'] = 'id';
//	$sopt['PluginFusioninventoryError'][30]['linkfield'] = '';
//	$sopt['PluginFusioninventoryError'][30]['name'] = $LANG["common"][2];
//
//	$sopt['PluginFusioninventoryError'][3]['table'] = 'glpi_plugin_fusioninventory_errors';
//	$sopt['PluginFusioninventoryError'][3]['field'] = 'itemtype';
//	$sopt['PluginFusioninventoryError'][3]['linkfield'] = 'itemtype';
//	$sopt['PluginFusioninventoryError'][3]['name'] = $LANG["common"][1];
//
//	$sopt['PluginFusioninventoryError'][4]['table'] = 'glpi_plugin_fusioninventory_errors';
//	$sopt['PluginFusioninventoryError'][4]['field'] = 'device_id';
//	$sopt['PluginFusioninventoryError'][4]['linkfield'] = 'device_id';
//	$sopt['PluginFusioninventoryError'][4]['name'] = $LANG["common"][16];
//
//	$sopt['PluginFusioninventoryError'][6]['table'] = 'glpi_plugin_fusioninventory_errors';
//	$sopt['PluginFusioninventoryError'][6]['field'] = 'description';
//	$sopt['PluginFusioninventoryError'][6]['linkfield'] = 'description';
//	$sopt['PluginFusioninventoryError'][6]['name'] = $LANG['plugin_fusioninventory']["errors"][2];
//  $sopt['PluginFusioninventoryError'][6]['datatype']='text';
//
//	$sopt['PluginFusioninventoryError'][7]['table'] = 'glpi_plugin_fusioninventory_errors';
//	$sopt['PluginFusioninventoryError'][7]['field'] = 'first_pb_date';
//	$sopt['PluginFusioninventoryError'][7]['linkfield'] = 'first_pb_date';
//	$sopt['PluginFusioninventoryError'][7]['name'] = $LANG['plugin_fusioninventory']["errors"][3];
//  $sopt['PluginFusioninventoryError'][7]['datatype']='datetime';
//
//	$sopt['PluginFusioninventoryError'][8]['table'] = 'glpi_plugin_fusioninventory_errors';
//	$sopt['PluginFusioninventoryError'][8]['field'] = 'last_pb_date';
//	$sopt['PluginFusioninventoryError'][8]['linkfield'] = 'last_pb_date';
//	$sopt['PluginFusioninventoryError'][8]['name'] = $LANG['plugin_fusioninventory']["errors"][4];
//  $sopt['PluginFusioninventoryError'][8]['datatype']='datetime';
//
//	$sopt['PluginFusioninventoryError'][80]['table'] = 'glpi_entities';
//	$sopt['PluginFusioninventoryError'][80]['field'] = 'completename';
//	$sopt['PluginFusioninventoryError'][80]['linkfield'] = 'entities_id';
//	$sopt['PluginFusioninventoryError'][80]['name'] = $LANG["entity"][0];
//
//   $sopt['PluginFusioninventoryAgent']['common'] = $LANG['plugin_fusioninventory']["profile"][26];
//
//	$sopt['PluginFusioninventoryAgent'][1]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][1]['field'] = 'name';
//	$sopt['PluginFusioninventoryAgent'][1]['linkfield'] = 'name';
//	$sopt['PluginFusioninventoryAgent'][1]['name'] = $LANG["common"][16];
//   $sopt['PluginFusioninventoryAgent'][1]['datatype']='itemlink';
//
//	$sopt['PluginFusioninventoryAgent'][30]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][30]['field'] = 'id';
//	$sopt['PluginFusioninventoryAgent'][30]['linkfield'] = '';
//	$sopt['PluginFusioninventoryAgent'][30]['name'] = $LANG["common"][2];
//
//	$sopt['PluginFusioninventoryAgent'][4]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][4]['field'] = 'threads_discovery';
//	$sopt['PluginFusioninventoryAgent'][4]['linkfield'] = 'threads_discovery';
//	$sopt['PluginFusioninventoryAgent'][4]['name'] = $LANG['plugin_fusioninventory']["agents"][3];
//
//	$sopt['PluginFusioninventoryAgent'][6]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][6]['field'] = 'threads_query';
//	$sopt['PluginFusioninventoryAgent'][6]['linkfield'] = 'threads_query';
//	$sopt['PluginFusioninventoryAgent'][6]['name'] = $LANG['plugin_fusioninventory']["agents"][2];
//
//	$sopt['PluginFusioninventoryAgent'][8]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][8]['field'] = 'last_agent_update';
//	$sopt['PluginFusioninventoryAgent'][8]['linkfield'] = '';
//	$sopt['PluginFusioninventoryAgent'][8]['name'] = $LANG['plugin_fusioninventory']["agents"][4];
//   $sopt['PluginFusioninventoryAgent'][8]['datatype']='datetime';
//
//	$sopt['PluginFusioninventoryAgent'][9]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][9]['field'] = 'fusioninventory_agent_version';
//	$sopt['PluginFusioninventoryAgent'][9]['linkfield'] = '';
//	$sopt['PluginFusioninventoryAgent'][9]['name'] = $LANG['plugin_fusioninventory']["agents"][5];
//
//	$sopt['PluginFusioninventoryAgent'][10]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][10]['field'] = 'lock';
//	$sopt['PluginFusioninventoryAgent'][10]['linkfield'] = 'lock';
//	$sopt['PluginFusioninventoryAgent'][10]['name'] = $LANG['plugin_fusioninventory']["agents"][6];
//   $sopt['PluginFusioninventoryAgent'][10]['datatype']='bool';
//
// 	$sopt['PluginFusioninventoryAgent'][11]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][11]['field'] = 'module_inventory';
//	$sopt['PluginFusioninventoryAgent'][11]['linkfield'] = 'module_inventory';
//	$sopt['PluginFusioninventoryAgent'][11]['name'] = $LANG['plugin_fusioninventory']['config'][3];
//   $sopt['PluginFusioninventoryAgent'][11]['datatype']='bool';
//
// 	$sopt['PluginFusioninventoryAgent'][12]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][12]['field'] = 'module_netdiscovery';
//	$sopt['PluginFusioninventoryAgent'][12]['linkfield'] = 'module_netdiscovery';
//	$sopt['PluginFusioninventoryAgent'][12]['name'] = $LANG['plugin_fusioninventory']['config'][4];
//   $sopt['PluginFusioninventoryAgent'][12]['datatype']='bool';
//
//   $sopt['PluginFusioninventoryAgent'][13]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][13]['field'] = 'module_snmpquery';
//	$sopt['PluginFusioninventoryAgent'][13]['linkfield'] = 'module_snmpquery';
//	$sopt['PluginFusioninventoryAgent'][13]['name'] = $LANG['plugin_fusioninventory']['config'][7];
//   $sopt['PluginFusioninventoryAgent'][13]['datatype']='bool';
//
//   $sopt['PluginFusioninventoryAgent'][14]['table'] = 'glpi_plugin_fusioninventory_agents';
//	$sopt['PluginFusioninventoryAgent'][14]['field'] = 'module_wakeonlan';
//	$sopt['PluginFusioninventoryAgent'][14]['linkfield'] = 'module_wakeonlan';
//	$sopt['PluginFusioninventoryAgent'][14]['name'] = $LANG['plugin_fusioninventory']['config'][6];
//   $sopt['PluginFusioninventoryAgent'][14]['datatype']='bool';
//
//
//
//
//
//	return $sopt;
//}


function plugin_fusioninventory_giveItem($type,$id,$data,$num) {
	return "";
}

// Define Dropdown tables to be manage in GLPI :
function plugin_fusioninventory_getDropdown() {
   return array ();
}

/* Cron */
function cron_plugin_fusioninventory() {
   // TODO :Disable for the moment (may be check if functions is good or not
//	$ptud = new PluginFusioninventoryUnknownDevice;
//   $ptud->CleanOrphelinsConnections();
//	$ptud->FusionUnknownKnownDevice();
//	TODO : regarder les 2 lignes juste en dessous !!!!!
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
   return PluginFusioninventorySetup::uninstall();
}

// Define headings added by the plugin //
function plugin_get_headings_fusioninventory($item,$withtemplate) {
	global $LANG;
	$pfc = new PluginFusioninventoryConfig;

	$type = get_Class($item);
//   switch ($type) {
   switch (get_class($item)) {
		case 'Computer' :
			if ($withtemplate) { // new object / template case
				return array();
         } else { // Non template case / editing an existing object
				$array = array ();
            $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
            if (($pfc->is_active($plugins_id, 'remotehttpagent')) AND
                    (PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","w"))) {
               $array[1] = $LANG['plugin_fusioninventory']["title"][0];
            }
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[2] = $LANG['plugin_fusioninventory']["title"][5];
            }
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
	}
	return false;	
}

// Define headings actions added by the plugin	 
//function plugin_headings_actions_fusioninventory($type) {
function plugin_headings_actions_fusioninventory($item) {
//	switch ($type) {
	switch (get_class($item)) {
		case 'Computer' :
			$array = array ();
         $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
         $pfc = new PluginFusioninventoryConfig;
         if (($pfc->is_active($plugins_id, 'remotehttpagent')) AND
                 (PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","w"))) {
             $array[1] = "plugin_headings_fusioninventory_computerInfo";
         }
         $array[2] = "plugin_headings_fusioninventory_locks";
			return $array;
			break;

		case 'Monitor' :
         return array (
            1 => "plugin_headings_fusioninventory_locks"
         );
         break;

      case 'Printer' :
         return array (
            1 => "plugin_headings_fusioninventory_locks"
         );
			break;

		case 'NetworkEquipment' :
         return array (
            1 => "plugin_headings_fusioninventory_locks"
         );
			break;

		case 'Profile' :
			return array(
				1 => "plugin_headings_fusioninventory",
				);
			break;

	}
	return false;
}


function plugin_headings_fusioninventory_computerInfo($type, $id) {
   $pfit = new PluginFusioninventoryTask;
   $pfit->RemoteStateAgent(GLPI_ROOT . '/plugins/fusioninventory/front/agents.state.php', $id, $type, array('INVENTORY' => 1, 'NETDISCOVERY' => 1, 'SNMPQUERY' => 1, 'WAKEONLAN' => 1));
}

//function plugin_headings_fusioninventory_locks($type, $id) {
function plugin_headings_fusioninventory_locks($item) {
   $type = get_Class($item);
   $id = $item->getField('id');
	$fusioninventory_locks = new PluginFusioninventoryLock();
   $fusioninventory_locks->showForm(getItemTypeFormURL('PluginFusioninventoryLock').'?id='.$id,
                                                       $type, $id);
}

//function plugin_headings_fusioninventory($type,$id,$withtemplate=0) {
//function plugin_headings_fusioninventory($item,$withtemplate=0) {
//	global $CFG_GLPI;
//
//   if (!$withtemplate) {
//      echo "<div class='center'>";
//      switch (get_class($item)) {
////      switch ($type) {
//         case 'Profile' :
//            $prof=new PluginFusioninventoryProfile;
//            if (!$prof->GetfromDB($id)) {
//               PluginFusioninventoryProfile::createaccess($id);
//            }
//            $prof->showForm($id,
//                 array('target'=>$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/profile.php"));
//            break;
//
//         case 'Computer' :
//            $prof=new PluginFusioninventoryProfile;
//            if (!$prof->GetfromDB($id)) {
//               PluginFusioninventoryProfile::createaccess($id);
//            }
//            $prof->showForm($id,
//                 array('target'=>$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/profile.php"));
//            break;
//      }
//   }
//}


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

function plugin_fusioninventory_MassiveActionsDisplay($type, $action) {

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

function plugin_pre_item_purge_fusioninventory($parm) {
	global $DB;

	if (isset($parm["_item_type_"])) {
		switch ($parm["_item_type_"]) {
         case COMPUTER_TYPE :
            // Delete link between computer and agent fusion
            $query = "UPDATE `glpi_plugin_fusioninventory_agents`
                        SET `items_id` = '0'
                           AND `itemtype` = '0'
                        WHERE `items_id` = '".$parm["id"]."'
                           AND `itemtype` = '1' ";
            $DB->query($query);
            break;

		}
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

?>
