<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

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

      case "glpi_plugin_fusioninventory_agents.version":
         $array = importArrayFromDB($data['ITEM_'.$num]);
         $input = "";
         foreach ($array as $name=>$version){
            $input .= "<strong>".$name."</strong> : ".$version."<br/>";
         }
         $input .= "*";
         $input = str_replace("<br/>*", "", $input);
         return $input;
         break;
        
   }
   
   if ($table == "glpi_plugin_fusioninventory_agentmodules") {
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $PluginFusioninventoryAgentmodule->find();
      foreach ($a_modules as $data2) {
         if ($table.".".$field == "glpi_plugin_fusioninventory_agentmodules.".$data2['modulename']) {
            if (strstr($data["ITEM_".$num."_0"], '"'.$data['id'].'"')) {
               if ($data['ITEM_'.$num] == '0') {
                  return Dropdown::getYesNo('1');
               } else {
                  return Dropdown::getYesNo('0');
               }

            }
            return Dropdown::getYesNo($data['ITEM_'.$num]);
         }
      }
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
               $array[2] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['title'][5];
            }
            $array[3] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['task'][18];
            return $array;
         }
         break;

      case 'Monitor' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['title'][5];
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
               $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['title'][5];
            }
            $array[2] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['task'][18];
            return $array;
         }
         break;

      case 'Printer' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['title'][5];
            }
            $array[2] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['task'][18];
            return $array;
         }
         break;

      case 'Profile' :
         if ($withtemplate) { // new object / template case
            return array();
         } else { // Non template case / editing an existing object
            $array = array ();
            if(PluginFusioninventoryModule::getModuleId("fusioninventory")) {
               $array[1] = $LANG['plugin_fusioninventory']['title'][0];
            }
            return $array;
         }
         break;

      case 'PluginFusioninventoryUnknownDevice' :
         $array = array ();
         if ($_GET['id'] > 0) {
            $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['xml'][0];
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
      echo "<th>".$LANG['plugin_fusioninventory']['xml'][0];
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
      case "NetworkEquipment":
         return array (
            "plugin_fusioninventory_manage_locks" => $LANG['plugin_fusioninventory']['functionalities'][75]
         );
         break;

      case "Printer":
         return array (
            "plugin_fusioninventory_manage_locks" => $LANG['plugin_fusioninventory']['functionalities'][75]
         );
         break;

      case 'PluginFusioninventoryAgent';
         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_modules = $PluginFusioninventoryAgentmodule->find();
         $array = array();
         foreach ($a_modules as $data) {
            $array["plugin_fusioninventory_agentmodule".$data["modulename"]] = $LANG['plugin_fusioninventory']['task'][26]." - ".$data['modulename'];
         }
         return $array;
         break;

   }
   return array ();
}

//function plugin_fusioninventory_MassiveActionsFieldsDisplay($options=array()) {
//   global $LANG;
//
//   $table = $options['options']['table'];
//   $field = $options['options']['field'];
//   $linkfield = $options['options']['linkfield'];
//
//   switch ($table) {
//
//		case 'glpi_plugin_fusioninventory_agentmodules':
//         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
//         $a_modules = $PluginFusioninventoryAgentmodule->find();
//         foreach ($a_modules as $data) {
//            if ($table.".".$field == "glpi_plugin_fusioninventory_agentmodules.".$data['modulename']) {
//               Dropdown::showYesNo($field);
//               return true;
//            }
//         }
//			break;
//
//    }
//   return false;
//}



function plugin_fusioninventory_MassiveActionsDisplay($options=array()) {
   global $LANG, $CFG_GLPI, $DB;

   switch ($options['itemtype']) {
      case "NetworkEquipment":
         switch ($options['action']) {
            case "plugin_fusioninventory_manage_locks" :
               $pfil = new PluginFusioninventoryLock;
               $pfil->showForm($_SERVER["PHP_SELF"], "NetworkEquipment");
               break;
         }
         break;

      case "Printer":
         switch ($options['action']) {
            case "plugin_fusioninventory_manage_locks" :
               $pfil = new PluginFusioninventoryLock();
               $pfil->showForm($_SERVER["PHP_SELF"], "Printer");
               break;
         }
         break;

      case 'PluginFusioninventoryAgent':
         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_modules = $PluginFusioninventoryAgentmodule->find();
         foreach ($a_modules as $data) {
            if ($options['action'] == "plugin_fusioninventory_agentmodule".$data['modulename']) {
               Dropdown::showYesNo($options['action']);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
            }
         }

         break;
   }
   return "";
}

function plugin_fusioninventory_MassiveActionsProcess($data) {
   global $LANG;

   switch ($data['action']) {
      case "plugin_fusioninventory_manage_locks" :
         if (($data['itemtype'] == "NetworkEquipment") OR ($data['itemtype'] == "Printer")) {
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

   if (strstr($data['action'], 'plugin_fusioninventory_agentmodule')) {
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $PluginFusioninventoryAgentmodule->find();
      foreach ($a_modules as $data2) {
         if ($data['action'] == "plugin_fusioninventory_agentmodule".$data2['modulename']) {
            foreach ($data['item'] as $items_id => $val) {
               if ($data["plugin_fusioninventory_agentmodule".$data2['modulename']] == $data2['is_active']) {
                  // Remove from exceptions
                  $a_exceptions = importArrayFromDB($data2['exceptions']);
                  if (in_array($items_id, $a_exceptions)) {
                     foreach ($a_exceptions as $key=>$value) {
                        if ($value == $items_id) {
                           unset($a_exceptions[$key]);
                        }
                     }
                  }
                  $data2['exceptions'] = exportArrayToDB($a_exceptions);
               } else {
                  // Add to exceptions
                  $a_exceptions = importArrayFromDB($data2['exceptions']);
                  if (!in_array($items_id, $a_exceptions)) {
                     $a_exceptions[] = (string)$items_id;
                  }
                  $data2['exceptions'] = exportArrayToDB($a_exceptions);
               }
            }
            $PluginFusioninventoryAgentmodule->update($data2);
         }
      }
   }

}

// How to display specific update fields ?
// Massive Action functions
//function plugin_fusioninventory_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield) {
//   global $LANG;
//   // Table fields
//   //echo $table.".".$field."<br/>";
//   switch ($table.".".$field) {
//      case 'glpi_plugin_fusioninventory_agents.id' :
//         Dropdown::show("PluginFusioninventoryAgent",
//                        array('name' => $linkfield,
//                              'comment' => false));
//         return true;
//         break;
//
//      case 'glpi_plugin_fusioninventory_agents.nb_process_query' :
//         Dropdown::showInteger("nb_process_query", $linkfield,1,200);
//         return true;
//         break;
//
//      case 'glpi_plugin_fusioninventory_agents.nb_process_discovery' :
//         Dropdown::showInteger("nb_process_discovery", $linkfield,1,400);
//         return true;
//         break;
//
//      case 'glpi_plugin_fusioninventory_agents.logs' :
//         $ArrayValues = array();
//         $ArrayValues[]= $LANG['choice'][0];
//         $ArrayValues[]= $LANG['choice'][1];
//         $ArrayValues[]= $LANG['setup'][137];
//         Dropdown::showFromArray('logs', $ArrayValues,
//                                 array('value'=>$linkfield));
//         return true;
//         break;
//
//      case 'glpi_plugin_fusioninventory_agents.core_discovery' :
//         Dropdown::showInteger("core_discovery", $linkfield,1,32);
//         return true;
//         break;
//
//      case 'glpi_plugin_fusioninventory_agents.core_query' :
//         Dropdown::showInteger("core_query", $linkfield,1,32);
//         return true;
//         break;
//
//      case 'glpi_plugin_fusioninventory_agents.threads_discovery' :
//         Dropdown::showInteger("threads_discovery", $linkfield,1,400);
//         return true;
//         break;
//
//      case 'glpi_plugin_fusioninventory_agents.threads_query' :
//         Dropdown::showInteger("threads_query", $linkfield,1,400);
//         return true;
//         break;
//
//      case 'glpi_entities.name' :
//         if (isMultiEntitiesMode()) {
//            Dropdown::show("Entities",
//                           array('name' => "entities_id",
//                           'value' => $_SESSION["glpiactive_entity"]));
//         }
//         return true;
//         break;
//   }
//   return false;
//}



function plugin_fusioninventory_addSelect($type,$id,$num) {
	global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

   switch ($type) {

      case 'PluginFusioninventoryAgent':

         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_modules = $PluginFusioninventoryAgentmodule->find();
         foreach ($a_modules as $data) {
            if ($table.".".$field == "glpi_plugin_fusioninventory_agentmodules.".$data['modulename']) {
               return " `FUSION_".$data['modulename']."`.`is_active` AS ITEM_$num, `FUSION_".$data['modulename']."`.`exceptions`  AS ITEM_".$num."_0,";
            }
         }
         break;

   }


   return "";
}


function plugin_fusioninventory_forceGroupBy($type) {
    return false;
}


function plugin_fusioninventory_addLeftJoin($itemtype,$ref_table,$new_table,$linkfield,&$already_link_tables) {

   switch ($itemtype) {

      case 'PluginFusioninventoryAgent':
         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_modules = $PluginFusioninventoryAgentmodule->find();
         foreach ($a_modules as $data) {
            if ($new_table.".".$linkfield == "glpi_plugin_fusioninventory_agentmodules.".$data['modulename']) {
               return " LEFT JOIN `glpi_plugin_fusioninventory_agentmodules` AS FUSION_".$data['modulename']."
                  ON FUSION_".$data['modulename'].".`modulename`='".$data['modulename']."' ";
            }
         }
         break;
      
   }


   return "";
}


function plugin_fusioninventory_addOrderBy($type,$id,$order,$key=0) {
   return "";
}


function plugin_fusioninventory_addWhere($link,$nott,$type,$id,$val) {
	global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

   switch ($type) {

      case 'PluginFusioninventoryAgent':
         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_modules = $PluginFusioninventoryAgentmodule->find();
         foreach ($a_modules as $data) {
            if ($table.".".$field == "glpi_plugin_fusioninventory_agentmodules.".$data['modulename']) {
               if (($data['exceptions'] != "[]") AND ($data['exceptions'] != "")) {
                  $a_exceptions = importArrayFromDB($data['exceptions']);
                  $current_id = current($a_exceptions);
                  $in = "(";
                  foreach($a_exceptions as $agent_id) {
                     $in .= $agent_id.", ";
                  }
                  $in .= ")";
                  $in = str_replace(", )", ")", $in);

                  if ($val != $data['is_active']) {
                     return $link." (FUSION_".$data['modulename'].".`exceptions` LIKE '%\"".$current_id."\"%' ) AND `glpi_plugin_fusioninventory_agents`.`id` IN ".$in." ";
                  } else {
                     return $link." `glpi_plugin_fusioninventory_agents`.`id` NOT IN ".$in." ";
                  }
               } else {
                  if ($val != $data['is_active']) {
                     return $link." (FUSION_".$data['modulename'].".`is_active`!='".$data['is_active']."') ";
                  } else {
                     return $link." (FUSION_".$data['modulename'].".`is_active`='".$data['is_active']."') ";
                  }
               }
            }
         }
         break;

   }

   
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
         $type=get_class($parm);
         $id=$parm->getField('id');
         $fieldsToLock=$parm->updates;
         if (!isset($_SESSION["plugin_fusioninventory_disablelocks"])) {
            PluginFusioninventoryLock::addLocks($type, $id, $fieldsToLock);
         }
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
         $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();

         $NetworkPort->getFromDB($parm->getField('networkports_id_1'));
        if ($NetworkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $PluginFusioninventoryUnknownDevice->getFromDB($NetworkPort->fields['items_id']);
            if ($PluginFusioninventoryUnknownDevice->fields['hub'] == '1') {
               $NetworkPort->delete($NetworkPort->fields);
            }
         }
         $NetworkPort->getFromDB($parm->getField('networkports_id_2'));
         if ($NetworkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $PluginFusioninventoryUnknownDevice->getFromDB($NetworkPort->fields['items_id']);
            if ($PluginFusioninventoryUnknownDevice->fields['hub'] == '1') {
               $NetworkPort->delete($NetworkPort->fields);
            }
         }

         break;

   }
   return $parm;
}



?>