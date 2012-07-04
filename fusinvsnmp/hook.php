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

function plugin_fusinvsnmp_getDatabaseRelations() {
   $plugin = new Plugin();
   if ($plugin->isActivated("fusinvsnmp")) {
      return array (
         "glpi_plugin_fusinvsnmp_models" => array (
            "glpi_plugin_fusinvsnmp_unknowndevices" => "plugin_fusinvsnmp_models_id"
         ));
   } else {
      return array ();
   }
}



function plugin_fusinvsnmp_getAddSearchOptions($itemtype) {
   global $LANG;

   $sopt = array();
   if ($itemtype == 'PluginFusioninventoryUnknownDevice') {

      $sopt[100]['table']     = 'glpi_plugin_fusinvsnmp_unknowndevices';
      $sopt[100]['field']     = 'sysdescr';
      $sopt[100]['linkfield'] = '';
      $sopt[100]['name']      = $LANG['plugin_fusinvsnmp']['snmp'][4];
      $sopt[100]['datatype']  = 'text';

      $sopt[101]['table']='glpi_plugin_fusinvsnmp_models';
      $sopt[101]['field']='name';
      $sopt[101]['linkfield']='';
      $sopt[101]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
         $LANG['plugin_fusinvsnmp']['model_info'][4];
      //$sopt[101]['datatype'] = 'itemlink';
      //$sopt[101]['itemlink_type'] = 'PluginFusinvsnmpModel';

      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      if ($pfConfig->getValue($plugins_id, "storagesnmpauth") == "file") {
         $sopt[102]['table'] = 'glpi_plugin_fusinvsnmp_printers';
         $sopt[102]['field'] = 'plugin_fusinvsnmp_configsecurities_id';
         $sopt[102]['linkfield'] = '';
         $sopt[102]['name'] = $LANG['plugin_fusioninventory']['title'][1]." - ".
            $LANG['plugin_fusinvsnmp']['model_info'][3];
      } else {
         $sopt[102]['table']='glpi_plugin_fusinvsnmp_configsecurities';
         $sopt[102]['field']='name';
         $sopt[102]['linkfield']='';
         $sopt[102]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
            $LANG['plugin_fusinvsnmp']['model_info'][3];
//         $sopt[102]['datatype'] = 'itemlink';
//         $sopt[102]['itemlink_type'] = 'PluginFusinvsnmpConfigSecurity';
      }

   }
   
   if ($itemtype == 'Computer') {
      // Switch
      $sopt[5192]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5192]['field']='name';
      $sopt[5192]['linkfield']='';
      $sopt[5192]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][52];
      $sopt[5192]['itemlink_type'] = 'NetworkEquipment';

      // Port of switch
      $sopt[5193]['table']='glpi_plugin_fusinvsnmp_networkports';
      $sopt[5193]['field']='id';
      $sopt[5193]['linkfield']='';
      $sopt[5193]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][46];
      $sopt[5193]['forcegroupby']='1';
   }

   if ($itemtype == 'Printer') {
      // Switch
      $sopt[5192]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5192]['field']='name';
      $sopt[5192]['linkfield']='';
      $sopt[5192]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][52];
      $sopt[5192]['itemlink_type'] = 'NetworkEquipment';

      // Port of switch
      $sopt[5193]['table']='glpi_plugin_fusinvsnmp_networkports';
      $sopt[5193]['field']='id';
      $sopt[5193]['linkfield']='';
      $sopt[5193]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][46];
      $sopt[5193]['forcegroupby']='1';

      $sopt[5190]['table']='glpi_plugin_fusinvsnmp_models';
      $sopt[5190]['field']='name';
      $sopt[5190]['linkfield']='plugin_fusinvsnmp_models_id';
      $sopt[5190]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
         $LANG['plugin_fusinvsnmp']['model_info'][4];
      $sopt[5190]['massiveaction'] = false;


      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      if ($pfConfig->getValue($plugins_id, "storagesnmpauth") == "file") {
         $sopt[5191]['table'] = 'glpi_plugin_fusinvsnmp_printers';
         $sopt[5191]['field'] = 'plugin_fusinvsnmp_configsecurities_id';
         $sopt[5191]['linkfield'] = 'id';
         $sopt[5191]['name'] = $LANG['plugin_fusioninventory']['title'][1]." - ".
            $LANG['plugin_fusinvsnmp']['model_info'][3];
         $sopt[5191]['massiveaction'] = false;
      } else {
         $sopt[5191]['table']='glpi_plugin_fusinvsnmp_configsecurities';
         $sopt[5191]['field']='name';
         $sopt[5191]['linkfield']='id';
         $sopt[5191]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
            $LANG['plugin_fusinvsnmp']['model_info'][3];
         $sopt[5191]['datatype'] = 'itemlink';
         $sopt[5191]['itemlink_type'] = 'PluginFusinvsnmpConfigSecurity';
         $sopt[5191]['massiveaction'] = false;
      }

      $sopt[5194]['table']='glpi_plugin_fusinvsnmp_printers';
      $sopt[5194]['field']='last_fusioninventory_update';
      $sopt[5194]['linkfield']='';
      $sopt[5194]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
         $LANG['plugin_fusinvsnmp']['snmp'][53];
      $sopt[5194]['datatype'] = 'datetime';

      $sopt[5196]['table']         = 'glpi_plugin_fusinvsnmp_printers';
      $sopt[5196]['field']         = 'sysdescr';
      $sopt[5196]['linkfield']     = '';
      $sopt[5196]['name']          = $LANG['plugin_fusinvsnmp']['snmp'][4];
      $sopt[5196]['datatype']      = 'text';
   }

   if ($itemtype == 'NetworkEquipment') {
      $sopt[5190]['table']='glpi_plugin_fusinvsnmp_models';
      $sopt[5190]['field']='name';
      $sopt[5190]['linkfield']='plugin_fusinvsnmp_models_id';
      $sopt[5190]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
         $LANG['plugin_fusinvsnmp']['model_info'][4];
      $sopt[5190]['massiveaction'] = false;

      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      if ($pfConfig->getValue($plugins_id, "storagesnmpauth") == "file") {
         $sopt[5191]['table'] = 'glpi_plugin_fusinvsnmp_networkequipments';
         $sopt[5191]['field'] = 'plugin_fusinvsnmp_configsecurities_id';
         $sopt[5191]['linkfield'] = '';
         $sopt[5191]['name'] = $LANG['plugin_fusioninventory']['title'][1]." - ".
            $LANG['plugin_fusinvsnmp']['model_info'][3];
         $sopt[5191]['massiveaction'] = false;
      } else {
         $sopt[5191]['table']='glpi_plugin_fusinvsnmp_configsecurities';
         $sopt[5191]['field']='name';
         $sopt[5191]['linkfield']='plugin_fusinvsnmp_configsecurities_id';
         $sopt[5191]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
            $LANG['plugin_fusinvsnmp']['model_info'][3];
         $sopt[5191]['massiveaction'] = false;
      }

      $sopt[5194]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5194]['field']='last_fusioninventory_update';
      $sopt[5194]['linkfield']='';
      $sopt[5194]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
         $LANG['plugin_fusinvsnmp']['snmp'][53];
      $sopt[5194]['datatype'] = 'datetime';

      $sopt[5195]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5195]['field']='cpu';
      $sopt[5195]['linkfield']='';
      $sopt[5195]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".
         $LANG['plugin_fusinvsnmp']['snmp'][13];
      $sopt[5195]['datatype'] = 'number';

      $sopt[5196]['table']         = 'glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5196]['field']         = 'sysdescr';
      $sopt[5196]['linkfield']     = '';
      $sopt[5196]['name']          = $LANG['plugin_fusinvsnmp']['snmp'][4];
      $sopt[5196]['datatype']      = 'text';
   }
   if ($itemtype == 'PluginFusioninventoryAgent') {
      $sopt[5197]['table']         = 'glpi_plugin_fusinvsnmp_agentconfigs';
      $sopt[5197]['field']         = 'threads_netdiscovery';
      $sopt[5197]['linkfield']     = '';
      $sopt[5197]['name']          = $LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][4]).")";
      $sopt[5197]['itemlink_type'] = 'PluginFusinvsnmpAgentconfig';

      $sopt[5198]['table']         = 'glpi_plugin_fusinvsnmp_agentconfigs';
      $sopt[5198]['field']         = 'threads_snmpquery';
      $sopt[5198]['linkfield']     = '';
      $sopt[5198]['name']          = $LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][3]).")";
      $sopt[5198]['itemlink_type'] = 'PluginFusinvsnmpAgentconfig';

   }

   return $sopt;
}



function plugin_fusinvsnmp_giveItem($type,$id,$data,$num) {
   global $CFG_GLPI,$DB,$INFOFORM_PAGES,$LANG;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];
//echo "giveItem : ".$table.'.'.$field."<br/>";
   switch ($type) {

      case 'Computer':
         if ($table.'.'.$field == 'glpi_plugin_fusinvsnmp_networkports.id') {
            if (strstr($data["ITEM_$num"], "$")) {
               $split=explode("$$$$",$data["ITEM_$num"]);
               $ports = array();

               foreach ($split as $portconcat) {
                  $split2 = explode("....", $portconcat);
                  if (isset($split2[1])) {
                     $ports[] = $split2[1];
                  }
               }
               $out = implode("<br/>", $ports);
               return $out;
            }
         }
         break;


      // * Networking List (front/networking.php)
      case 'NetworkEquipment':
         switch ($table.'.'.$field) {


         }
         break;

      case 'Printer':
         if ($table.'.'.$field == 'glpi_plugin_fusinvsnmp_networkequipments.name') {
            if (strstr($data["ITEM_$num"], "$")) {
               $split=explode("$$$$",$data["ITEM_$num"]);
               $out = implode("<br/>", $split);
               return $out;
            }
         }
         break;

      // * Model List (plugins/fusinvsnmp/front/snmpmodel.php)
      case 'PluginFusinvsnmpModel' :
         switch ($table.'.'.$field) {

            // ** Name of type of model (network, printer...)
            case "glpi_plugin_fusinvsnmp_models.itemtype" :
               $out = '<center> ';
               switch ($data["ITEM_$num"]) {
                  case COMPUTER_TYPE:
                     $out .= $LANG["Menu"][0];
                     break;

                  case NETWORKING_TYPE:
                     $out .= $LANG["Menu"][1];
                     break;

                  case PRINTER_TYPE:
                     $out .= $LANG["Menu"][2];
                     break;

                  case PERIPHERAL_TYPE:
                     $out .= $LANG["Menu"][16];
                     break;

                  case PHONE_TYPE:
                     $out .= $LANG["Menu"][34];
                     break;
               }
               $out .= '</center>';
               return $out;
               break;

            // ** Display pic / link for exporting model
            case "glpi_plugin_fusinvsnmp_models.id" :
               $out = "<div align='center'><form>";
               $out .= Html::closeForm(false);
               $out .= "<form method='get' action='"; 
               $out .= $CFG_GLPI['root_doc'] . "/plugins/fusinvsnmp/front/models.export.php' target='_blank'>
                  <input type='hidden' name='model' value='" . $data["id"] . "' />
                  <input name='export' src='" . $CFG_GLPI['root_doc'];
               $out.= "/pics/right.png' title='Exporter' value='Exporter' type='image'>";
               $out .= Html::closeForm(false);
               $out .= "</div>";
               return "<center>".$out."</center>";
               break;

         }
         break;


      // * Authentification List (plugins/fusinvsnmp/front/configsecurity.php)
      case 'PluginFusinvsnmpConfigSecurity' :
         switch ($table.'.'.$field) {

            // ** Hidden auth passphrase (SNMP v3)
            case "glpi_plugin_fusinvsnmp_configsecurities.auth_passphrase" :
               $out = "";
               if (empty($data["ITEM_$num"])) {
                  
               } else {
                  $out = "********";
               }
               return $out;
               break;

            // ** Hidden priv passphrase (SNMP v3)
            case "glpi_plugin_fusinvsnmp_configsecurities.priv_passphrase" :
               $out = "";
               if (empty($data["ITEM_$num"])) {
                  
               } else {
                  $out = "********";
               }
               return $out;
               break;
         }
         break;

      // * Unknown mac addresses connectd on switch - report (plugins/fusinvsnmp/report/unknown_mac.php)
      case 'PluginFusioninventoryUnknownDevice' :
         switch ($table.'.'.$field) {

            // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.id" :
               $out = '';
               $NetworkPort = new NetworkPort;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $NetworkPort->getDeviceData($vartmp, 'PluginFusioninventoryUnknownDevice');

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/";
                  $out .= $INFOFORM_PAGES['PluginFusioninventoryUnknownDevice']."?id=".$vartmp."\">";
                  $out .=  $NetworkPort->device_name;
                  if ($CFG_GLPI["view_ID"]) {
                     $out .= " (".$vartmp.")";
                  }
                  $out .=  "</a><br/>";
               }
               return "<center>".$out."</center>";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               $out = '';
               if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new NetworkPort;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".$CFG_GLPI['root_doc']."/front/networkport.form.php?id=".$vartmp."'>";
                     $out .= $np->fields["name"]."</a><br/>";
                  }
               }
               return "<center>".$out."</center>";
               break;

            case "glpi_plugin_fusinvsnmp_unknowndevices.type" :
               $out = '<center> ';
               switch ($data["ITEM_$num"]) {
                  case COMPUTER_TYPE:
                     $out .= $LANG["Menu"][0];
                     break;

                  case NETWORKING_TYPE:
                     $out .= $LANG["Menu"][1];
                     break;

                  case PRINTER_TYPE:
                     $out .= $LANG["Menu"][2];
                     break;

                  case PERIPHERAL_TYPE:
                     $out .= $LANG["Menu"][16];
                     break;

                  case PHONE_TYPE:
                     $out .= $LANG["Menu"][34];
                     break;
               }
               $out .= '</center>';
               return $out;
               break;


         }
         break;

      // * Ports date connection - report (plugins/fusinvsnmp/report/ports_date_connections.php)
      case 'PluginFusinvsnmpNetworkport' :
         switch ($table.'.'.$field) {

            // ** Name and link of networking device (switch)
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               $query = "SELECT `glpi_networkequipments`.`name` AS `name`, `glpi_networkequipments`.`id` AS `id`
                         FROM `glpi_networkequipments`
                              LEFT JOIN `glpi_networkports`
                                        ON `items_id` = `glpi_networkequipments`.`id`
                              LEFT JOIN `glpi_plugin_fusinvsnmp_networkports`
                                        ON `glpi_networkports`.`id`=`networkports_id`
                         WHERE `glpi_plugin_fusinvsnmp_networkports`.`id`='".$data["ITEM_$num"]."'
                         LIMIT 0,1;";
               $result = $DB->query($query);
               $data2 = $DB->fetch_assoc($result);
               $out = "<a href='".$CFG_GLPI['root_doc']."/front/networking.form.php?id=".$data2["id"]."'>";
               $out.= $data2["name"]."</a>";
            return "<center>".$out."</center>";
            break;

            // ** Name and link of port of networking device (port of switch)
            case "glpi_plugin_fusinvsnmp_networkports.networkports_id" :
               $NetworkPort=new NetworkPort;
               $NetworkPort->getFromDB($data["ITEM_$num"]);
               $name = "";
               if (isset($NetworkPort->fields["name"])) {
                  $name = $NetworkPort->fields["name"];
               }
               $out = "<a href='".$CFG_GLPI['root_doc']."/front/networkport.form.php?id=".$data["ITEM_$num"];
               $out.= "'>".$name."</a>";
               return "<center>".$out."</center>";
               break;

            // ** Location of switch
            case "glpi_locations.id" :
               $out = Dropdown::getDropdownName("glpi_locations",$data["ITEM_$num"]);
               return "<center>".$out."</center>";
               break;

         }
         break;

      // * range IP list (plugins/fusinvsnmp/front/iprange.php)
      case 'PluginFusioninventoryIPRange' :
         switch ($table.'.'.$field) {


            // ** Display entity name
            case "glpi_entities.name" :
               if ($data["ITEM_$num"] == '') {
                  $out = Dropdown::getDropdownName("glpi_entities",$data["ITEM_$num"]);
                  return "<center>".$out."</center>";
               }
               break;

         }
         break;

      // * Detail of ports history (plugins/fusinvsnmp/report/switch_ports.history.php)
      case 'PluginFusinvsnmpNetworkPortLog' :
         switch ($table.'.'.$field) {

            // ** Display switch and Port
            case "glpi_networkports.id" :
               $Array_device = 
                  PluginFusinvsnmpNetworkPort::getUniqueObjectfieldsByportID($data["ITEM_$num"]);
               $item = new $Array_device["itemtype"];
               $item->getFromDB($Array_device["items_id"]);
               $out = "<div align='center'>" . $item->getLink(1);

               $query = "SELECT *
                         FROM `glpi_networkports`
                         WHERE `id`='" . $data["ITEM_$num"] . "';";
               $result = $DB->query($query);

               if ($DB->numrows($result) != "0") {
                  $out .= "<br/><a href='".$CFG_GLPI['root_doc']."/front/networkport.form.php?id=";
                  $out .= $data["ITEM_$num"]."'>".$DB->result($result, 0, "name")."</a>";
               }
               $out .= "</td>";
               return $out;
               break;

            // ** Display GLPI field of device
            case "glpi_plugin_fusinvsnmp_networkportlogs.field" :
//               $out = $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$data["ITEM_$num"]]['name'];
               $out = '';
               $map = new PluginFusioninventoryMapping;
               $mapfields = $map->get('NetworkEquipment', $data["ITEM_$num"]);
               if ($mapfields != false) {
                  $out = $LANG['plugin_fusinvsnmp']['mapping'][$mapfields["locale"]];
               }
               return $out;
               break;

            // ** Display Old Value (before changement of value)
            case "glpi_plugin_fusinvsnmp_networkportlogs.old_value" :
               // TODO ADD LINK TO DEVICE
               if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"]))) {
                  return "<center><b>".$data["ITEM_$num"]."</b></center>";
               }
               break;

            // ** Display New Value (new value modified)
            case "glpi_plugin_fusinvsnmp_networkportlogs.new_value" :
               if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"]))) {
                  return "<center><b>".$data["ITEM_$num"]."</b></center>";
               }
               break;

         }
         break;

      case "PluginFusinvsnmpPrinterLog":
         switch ($table.'.'.$field) {

            case 'glpi_printers.name':

               // Search pages in printer history to limit SQL queries
               if (isset($_SESSION['glpi_plugin_fusioninventory_history_start'])) {
                  unset($_SESSION['glpi_plugin_fusioninventory_history_start']);
               }
               if (isset($_SESSION['glpi_plugin_fusioninventory_history_end'])) {
                  unset($_SESSION['glpi_plugin_fusioninventory_history_end']);
               }
               if ((isset($_SESSION['glpi_plugin_fusioninventory_date_start']))
                       AND (isset($_SESSION['glpi_plugin_fusioninventory_date_end']))) {

                  $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_printerlogs`
                     WHERE `printers_id`='".$data['ITEM_0_2']."'
                        AND `date`>= '".$_SESSION['glpi_plugin_fusioninventory_date_start']."'
                        AND `date`<= '".$_SESSION['glpi_plugin_fusioninventory_date_end']." 23:59:59'
                     ORDER BY date asc
                     LIMIT 1";
                  $result=$DB->query($query);
                  while ($data2=$DB->fetch_array($result)) {
                     $_SESSION['glpi_plugin_fusioninventory_history_start'] = $data2;
                  }
                  $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_printerlogs`
                     WHERE `printers_id`='".$data['ITEM_0_2']."'
                        AND `date`>= '".$_SESSION['glpi_plugin_fusioninventory_date_start']."'
                        AND `date`<= '".$_SESSION['glpi_plugin_fusioninventory_date_end']." 23:59:59'
                     ORDER BY date desc
                     LIMIT 1";
                  $result=$DB->query($query);
                  while ($data2=$DB->fetch_array($result)) {
                     $_SESSION['glpi_plugin_fusioninventory_history_end'] = $data2;
                  }
               }
               return "";
               break;

            }

         switch($table) {

            case 'glpi_plugin_fusinvsnmp_printerlogs':
               if ((isset($_SESSION['glpi_plugin_fusioninventory_history_start'][$field]))
                      AND (isset($_SESSION['glpi_plugin_fusioninventory_history_end'][$field]))) {
                  $counter_start = $_SESSION['glpi_plugin_fusioninventory_history_start'][$field];
                  $counter_end = $_SESSION['glpi_plugin_fusioninventory_history_end'][$field];
                  if ($_SESSION['glpi_plugin_fusioninventory_date_start'] == "1970-01-01") {
                     $counter_start = 0;
                  }
                  $number = $counter_end - $counter_start;
                  if (($number == '0')) {
                      return '-';
                  } else {
                     return $number;
                  }

               } else {
                  return '-';
               }
               break;

            }
         break;


   }

   return "";
}

// Define Dropdown tables to be manage in GLPI :
//function plugin_fusinvsnmp_getDropdown() {
// // Table => Name
// global $LANG;
// if (isset ($_SESSION["glpi_plugin_fusinvsnmp_installed"]) && $_SESSION["glpi_plugin_fusinvsnmp_installed"] == 1) {
//    return array (
//       "glpi_plugin_fusinvsnmp_snmpversions" => "SNMP version",
//       "glpi_plugin_fusinvsnmp_miboids" => "OID MIB",
//       "glpi_plugin_fusinvsnmp_mibobjects" => "Objet MIB",
//       "glpi_plugin_fusinvsnmp_miblabels" => "Label MIB"
//    );
//   } else {
//    return array ();
//   }
//}

/* Cron */
function cron_plugin_fusinvsnmp() {
   // TODO :Disable for the moment (may be check if functions is good or not
// $ptud = new PluginFusioninventoryUnknownDevice;
//   $ptud->CleanOrphelinsConnections();
// $ptud->FusionUnknownKnownDevice();
//   #Clean server script processes history
//   $pfisnmph = new PluginFusinvsnmpNetworkPortLog;
//   $pfisnmph->cronCleanHistory();
   return 1;
}



function plugin_fusinvsnmp_install() {

   include_once (GLPI_ROOT . "/plugins/fusinvsnmp/install/install.php");
   pluginFusinvsnmpInstall(PLUGIN_FUSINVSNMP_VERSION);

   return true;
}

// Uninstall process for plugin : need to return true if succeeded
function plugin_fusinvsnmp_uninstall() {
   include (GLPI_ROOT . "/plugins/fusinvsnmp/install/install.php");
   pluginFusinvsnmpUninstall();
}

/**
* Check if FusionInventory need to be updated
*
* @param
*
* @return 0 (no need update) OR 1 (need update)
**/
function plugin_fusinvsnmp_needUpdate() {
   include (GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
   $version_detected = pluginFusinvsnmpGetCurrentVersion(PLUGIN_FUSIONINVENTORY_VERSION);
   if ((isset($version_detected)) 
      AND ($version_detected != PLUGIN_FUSIONINVENTORY_VERSION)
      AND $version_detected!='0') {

      return 1;
   } else {
      return 0;
   }
}



// Define headings added by the plugin //
//function plugin_get_headings_fusinvsnmp($type,$id,$withtemplate) {
function plugin_get_headings_fusinvsnmp($item,$withtemplate) {
   global $LANG;
   $config = new PluginFusioninventoryConfig;

   $type = get_Class($item);
   switch ($type) {
      
      case 'Computer':
         if ($withtemplate) { //?
            return array();
         // Non template case
         } else {
//          if ((PluginFusinvsnmpAuth::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
            $array = array ();
            //return array(
            if (($config->is_active('fusioninventory', 'remotehttpagent')) 
                  AND (PluginFusioninventoryProfile::haveRight("fusioninventory", 
                                                               "remotecontrol", "w"))) {
               $array[1] = $LANG['plugin_fusinvsnmp']['title'][0];
            }
            //}

            return $array;
//          }
         }
         break;

      case 'Monitor':
         if ($withtemplate) { //?
            return array();
         // Non template case
         } else {
            return array();
         }
         break;

      case 'NetworkEquipment' :
         if ($withtemplate) {
            return array();
         // Non template case
         } else {
            $array = array ();
            if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "networkequipment", "r")) {
               $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".
                  $LANG['plugin_fusinvsnmp']['title'][6];
            }
            if ($_GET['id'] > 0) {
               $array[2] = $LANG['plugin_fusioninventory']['title'][1]." ".
                  $LANG['plugin_fusioninventory']['xml'][0];
            }
            return $array;
         }
         break;

      case 'Printer':
         // template case
         if ($withtemplate) {
            return array();
         // Non template case
         } else {
            $array = array ();
            if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "printer", "r")) {
               $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".
                  $LANG['plugin_fusinvsnmp']['title'][6];
            }
            if ($_GET['id'] > 0) {
               $array[2] = $LANG['plugin_fusioninventory']['title'][1]." ".
                  $LANG['plugin_fusioninventory']['xml'][0];
            }
            return $array;
         }
         break;

       case 'PluginFusioninventoryAgent':
          $array = array ();
          $array[1] = $LANG['plugin_fusinvsnmp']['agents'][24];
          return $array;
          break;

       case 'PluginFusioninventoryUnknownDevice':
          $array = array ();
          $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".
            $LANG['plugin_fusinvsnmp']['title'][6];
          return $array;
          break;

   }
   return false;  
}



// Define headings actions added by the plugin   
function plugin_headings_actions_fusinvsnmp($item) {

   switch (get_class($item)) {

      case 'Printer' :
         $array = array ();
         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "printer", "r")) {
            $array[1] = "plugin_headings_fusinvsnmp_printerInfo";
         }
         $array[2] = "plugin_headings_fusinvsnmp_xml";
         return $array;
         break;

      case 'NetworkEquipment' :
         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "networkequipment", "r")) {
            $array[1] = "plugin_headings_fusinvsnmp_networkingInfo";
         }
         $array[2] = "plugin_headings_fusinvsnmp_xml";
         return $array;
         break;

      case 'PluginFusioninventoryAgent' :
         $array = array ();
         $array[1] = "plugin_headings_fusinvsnmp_agents";
         // $array[1] = "plugin_headings_fusinvsnmp_agents";  => $array[1] = array('taclasse', 'taméthode');
         $array[2] = "plugin_headings_fusinvsnmp_xml";
         return $array;
         break;

      case 'PluginFusioninventoryUnknownDevice':
         $array = array ();
         $array[1] = "plugin_headings_fusinvsnmp_unknowndevices";
         return $array;
         break;

   }
   return false;
}



function plugin_headings_fusinvsnmp_printerInfo($type, $id) {
   global $CFG_GLPI;

   $plugin_fusinvsnmp_printer = new PluginFusinvsnmpPrinter;
   $plugin_fusinvsnmp_printer->showForm($_POST['id'],
               array('target'=>$CFG_GLPI['root_doc'].'/plugins/fusinvsnmp/front/printer_info.form.php'));
   echo '<div id="overDivYFix" STYLE="visibility:hidden">fusinvsnmp_1</div>';

   $pfPrinterCartridge = new PluginFusinvsnmpPrinterCartridge();
   $pfPrinterCartridge->showForm($_POST['id'],
               array('target'=>$CFG_GLPI['root_doc'].'/plugins/fusinvsnmp/front/printer_info.form.php'));

   $pfPrinterLog = new PluginFusinvsnmpPrinterLog();
   $pfPrinterLog->showGraph($_POST['id'],
               array('target'=>$CFG_GLPI['root_doc'] . '/plugins/fusinvsnmp/front/printer_info.form.php'));

}

function plugin_headings_fusinvsnmp_printerHistory($type, $id) {
   global $CFG_GLPI;
   
   $print_history = new PluginFusinvsnmpPrinterLog();
   $print_history->showForm($_GET["id"],
               array('target'=>$CFG_GLPI['root_doc'].'/plugins/fusinvsnmp/front/printer_history.form.php'));
}

function plugin_headings_fusinvsnmp_networkingInfo($type, $id) {
   global $CFG_GLPI;
   
   $snmp = new PluginFusinvsnmpNetworkEquipment;
   $snmp->showForm($_POST['id'],
           array('target'=>$CFG_GLPI['root_doc'].'/plugins/fusinvsnmp/front/switch_info.form.php'));
}


function plugin_headings_fusinvsnmp_agents($type,$id) {
   $pfAgentconfig = new PluginFusinvsnmpAgentconfig;
   $pfAgentconfig->showForm($_POST['id']);
}


function plugin_headings_fusinvsnmp_unknowndevices($type,$id) {
   $pfUnknownDevice = new PluginFusinvsnmpUnknownDevice();
   $pfUnknownDevice->showForm($_POST['id']);
}


function plugin_headings_fusinvsnmp_xml($item) {
   global $LANG;

   $type = get_Class($item);
   $id = $item->getField('id');

   $folder = substr($id,0,-1);
   if (empty($folder)) {
      $folder = '0';
   }
   if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$type."/".$folder."/".$id)) {
      $xml = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$type."/".$folder."/".$id);
      $xml = str_replace("<", "&lt;", $xml);
      $xml = str_replace(">", "&gt;", $xml);
      $xml = str_replace("\n", "<br/>", $xml);
      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
      echo "<tr>";
      echo "<th>".$LANG['plugin_fusioninventory']['title'][1]." ".
         $LANG['plugin_fusioninventory']['xml'][0];
      echo " (".$LANG['plugin_fusinvsnmp']['snmp'][53]."&nbsp;: " . 
         Html::convDateTime(date("Y-m-d H:i:s", 
                      filemtime(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$type."/".$folder."/".$id))).")";
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



function plugin_fusinvsnmp_MassiveActions($type) {
   global $LANG;

   switch ($type) {
      case 'NetworkEquipment':
         return array (
            "plugin_fusinvsnmp_get_model" => $LANG['plugin_fusinvsnmp']['model_info'][14],
            "plugin_fusinvsnmp_assign_model" => $LANG['plugin_fusinvsnmp']['massiveaction'][1],
            "plugin_fusinvsnmp_assign_auth" => $LANG['plugin_fusinvsnmp']['massiveaction'][2]
         );
         break;

      case 'Printer':
         return array (
            "plugin_fusinvsnmp_get_model" => $LANG['plugin_fusinvsnmp']['model_info'][14],
            "plugin_fusinvsnmp_assign_model" => $LANG['plugin_fusinvsnmp']['massiveaction'][1],
            "plugin_fusinvsnmp_assign_auth" => $LANG['plugin_fusinvsnmp']['massiveaction'][2]
         );
         break;

      case 'PluginFusioninventoryUnknownDevice':
         return array (
            "plugin_fusinvsnmp_assign_model" => $LANG['plugin_fusinvsnmp']['massiveaction'][1],
            "plugin_fusinvsnmp_assign_auth" => $LANG['plugin_fusinvsnmp']['massiveaction'][2]
         );
         break;

      case 'PluginFusioninventoryAgent':
         return array(
            "plugin_fusinvsnmp_set_discovery_threads" => $LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][4]).")",
            "plugin_fusinvsnmp_set_snmpinventory_threads" => $LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][3]).")"
         );
         break;

   }
   return array ();
}

function plugin_fusinvsnmp_MassiveActionsDisplay($options=array()) {
   global $LANG,$DB;

   switch ($options['itemtype']) {
      case 'NetworkEquipment':
      case 'Printer':
      case 'PluginFusioninventoryUnknownDevice':
         switch ($options['action']) {

            case "plugin_fusinvsnmp_get_model" :
               if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" .
                     $LANG["buttons"][2] . "\" >";
               }
               break;

            case "plugin_fusinvsnmp_assign_model" :
               if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_fusinvsnmp_models`
                                   WHERE `itemtype`!='".$options['itemtype']."'";
                  if ($options['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
                     $query_models = "SELECT *
                                   FROM `glpi_plugin_fusinvsnmp_models`
                                   WHERE `itemtype`='nothing'";
                  }
                  $result_models=$DB->query($query_models);
                  $exclude_models = array();
                  while ($data_models=$DB->fetch_array($result_models)) {
                     $exclude_models[] = $data_models['id'];
                  }
                  Dropdown::show("PluginFusinvsnmpModel",
                                 array('name' => "snmp_model",
                                       'value' => "name",
                                       'comment' => false,
                                       'used' => $exclude_models));
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" .
                     $LANG["buttons"][2] . "\" >";
               }
               break;

            case "plugin_fusinvsnmp_assign_auth" :
               if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity","w")) {
                  PluginFusinvsnmpSNMP::auth_dropdown();
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . 
                     $LANG["buttons"][2] . "\" >";
               }
               break;

         }
         break;

      case 'PluginFusioninventoryAgent':
         switch ($options['action']) {

            case 'plugin_fusinvsnmp_set_discovery_threads':
               echo Dropdown::showInteger('threads_netdiscovery', '10');
               echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" .
                     $LANG["buttons"][2] . "\" >";
               break;

            case 'plugin_fusinvsnmp_set_snmpinventory_threads':
               echo Dropdown::showInteger('threads_snmpquery', '5');
               echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" .
                     $LANG["buttons"][2] . "\" >";
               break;
         }
         break;

   }
   return "";
}

function plugin_fusinvsnmp_MassiveActionsProcess($data) {

   switch ($data['action']) {

      case "plugin_fusinvsnmp_get_model" :
         if ($data['itemtype'] == NETWORKING_TYPE) {
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  $pfModel = new PluginFusinvsnmpModel;
                  $pfModel->getrightmodel($key, NETWORKING_TYPE);
               }
            }
         } else if($data['itemtype'] == PRINTER_TYPE) {
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  $pfModel = new PluginFusinvsnmpModel;
                  $pfModel->getrightmodel($key, PRINTER_TYPE);
               }
            }
         }
         break;

      case "plugin_fusinvsnmp_assign_model" :
         if ($data['itemtype'] == 'NetworkEquipment') {
            foreach ($data['item'] as $items_id => $val) {
               if ($val == 1) {
                  $pfNetworkEquipment = 
                     new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
                  $a_networkequipments = 
                     $pfNetworkEquipment->find("`networkequipments_id`='".$items_id."'");
                  $input = array();
                  if (count($a_networkequipments) > 0) {
                     $a_networkequipment = current($a_networkequipments);
                     $pfNetworkEquipment->getFromDB($a_networkequipment['id']);
                     $input['id'] = $pfNetworkEquipment->fields['id'];
                     $input['plugin_fusinvsnmp_models_id'] = $data['snmp_model'];
                     $pfNetworkEquipment->update($input);
                  } else {
                     $input['networkequipments_id'] = $items_id;
                     $input['plugin_fusinvsnmp_models_id'] = $data['snmp_model'];
                     $pfNetworkEquipment->add($input);
                  }
               }
            }
         } else if($data['itemtype'] == 'Printer') {
            foreach ($data['item'] as $items_id => $val) {
               if ($val == 1) {
                  $pfPrinter = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");
                  $a_printers = $pfPrinter->find("`printers_id`='".$items_id."'");
                  $input = array();
                  if (count($a_printers) > 0) {
                     $a_printer = current($a_printers);
                     $pfPrinter->getFromDB($a_printer['id']);
                     $input['id'] = $pfPrinter->fields['id'];
                     $input['plugin_fusinvsnmp_models_id'] = $data['snmp_model'];
                     $pfPrinter->update($input);
                  } else {
                     $input['printers_id'] = $items_id;
                     $input['plugin_fusinvsnmp_models_id'] = $data['snmp_model'];
                     $pfPrinter->add($input);
                  }
               }
            }
         } else if($data['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            foreach ($data['item'] as $items_id => $val) {
               if ($val == 1) {
                  $pfUnknownDevice = new PluginFusinvsnmpUnknownDevice();
                  $a_snmps = $pfUnknownDevice->find("`plugin_fusioninventory_unknowndevices_id`='".$items_id."'");
                  $input = array();
                  if (count($a_snmps) > 0) {
                     $input = current($a_snmps);
                     $input['plugin_fusinvsnmp_models_id'] = $data['snmp_model'];
                     $pfUnknownDevice->update($input);
                  } else {
                     $input['plugin_fusioninventory_unknowndevices_id'] = $items_id;
                     $input['plugin_fusinvsnmp_models_id'] = $data['snmp_model'];
                     $pfUnknownDevice->add($input);
                  }
               }
            }
         }
         break;
      
      case "plugin_fusinvsnmp_assign_auth" :
         if ($data['itemtype'] == 'NetworkEquipment') {
            foreach ($data['item'] as $items_id => $val) {
               if ($val == 1) {
                  $pfNetworkEquipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
                  $a_networkequipments = $pfNetworkEquipment->find("`networkequipments_id`='".$items_id."'");
                  $input = array();
                  if (count($a_networkequipments) > 0) {
                     $a_networkequipment = current($a_networkequipments);
                     $pfNetworkEquipment->getFromDB($a_networkequipment['id']);
                     $input['id'] = $pfNetworkEquipment->fields['id'];
                     $input['plugin_fusinvsnmp_configsecurities_id'] = $data['plugin_fusinvsnmp_configsecurities_id'];
                     $pfNetworkEquipment->update($input);
                  } else {
                     $input['networkequipments_id'] = $items_id;
                     $input['plugin_fusinvsnmp_configsecurities_id'] = $data['plugin_fusinvsnmp_configsecurities_id'];
                     $pfNetworkEquipment->add($input);
                  }
               }
            }
         } else if($data['itemtype'] == 'Printer') {
            foreach ($data['item'] as $items_id => $val) {
               if ($val == 1) {
                  $pfPrinter = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");
                  $a_printers = $pfPrinter->find("`printers_id`='".$items_id."'");
                  $input = array();
                  if (count($a_printers) > 0) {
                     $a_printer = current($a_printers);
                     $pfPrinter->getFromDB($a_printer['id']);
                     $input['id'] = $pfPrinter->fields['id'];
                     $input['plugin_fusinvsnmp_configsecurities_id'] = $data['plugin_fusinvsnmp_configsecurities_id'];
                     $pfPrinter->update($input);
                  } else {
                     $input['printers_id'] = $items_id;
                     $input['plugin_fusinvsnmp_configsecurities_id'] = $data['plugin_fusinvsnmp_configsecurities_id'];
                     $pfPrinter->add($input);
                  }
                }
            }
         } else if($data['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            foreach ($data['item'] as $items_id => $val) {
               if ($val == 1) {
                  $pfUnknownDevice = new PluginFusinvsnmpUnknownDevice();
                  $a_snmps = $pfUnknownDevice->find("`plugin_fusioninventory_unknowndevices_id`='".$items_id."'");
                  $input = array();
                  if (count($a_snmps) > 0) {
                     $input = current($a_snmps);
                     $input['plugin_fusinvsnmp_configsecurities_id'] = $data['plugin_fusinvsnmp_configsecurities_id'];
                     $pfUnknownDevice->update($input);
                  } else {
                     $input['plugin_fusioninventory_unknowndevices_id'] = $items_id;
                     $input['plugin_fusinvsnmp_configsecurities_id'] = $data['plugin_fusinvsnmp_configsecurities_id'];
                     $pfUnknownDevice->add($input);
                  }
                }
            }
         }
         break;

      case "plugin_fusinvsnmp_set_discovery_threads" :
         foreach ($data['item'] as $items_id => $val) {
            if ($val == 1) {
               $pfAgentconfig = new PluginFusinvsnmpAgentconfig();
               $a_agents = $pfAgentconfig->find("`plugin_fusioninventory_agents_id`='".$items_id."'");
               $input = array();
               if (count($a_agents) > 0) {
                  $input = current($a_agents);
                  $input['threads_netdiscovery'] = $data['threads_netdiscovery'];
                  $pfAgentconfig->update($input);
               } else {
                  $input['plugin_fusioninventory_agents_id'] = $items_id;
                  $input['threads_netdiscovery'] = $data['threads_netdiscovery'];
                  $pfAgentconfig->add($input);
               }
            }
         }
         break;

      case "plugin_fusinvsnmp_set_snmpinventory_threads" :
         foreach ($data['item'] as $items_id => $val) {
            if ($val == 1) {
               $pfAgentconfig = new PluginFusinvsnmpAgentconfig();
               $a_agents = $pfAgentconfig->find("`plugin_fusioninventory_agents_id`='".$items_id."'");
               $input = array();
               if (count($a_agents) > 0) {
                  $input = current($a_agents);
                  $input['threads_snmpquery'] = $data['threads_snmpquery'];
                  $pfAgentconfig->update($input);
               } else {
                  $input['plugin_fusioninventory_agents_id'] = $items_id;
                  $input['threads_snmpquery'] = $data['threads_snmpquery'];
                  $pfAgentconfig->add($input);
               }
            }
         }
         break;

   }
}

// How to display specific update fields ?
// Massive Action functions
function plugin_fusinvsnmp_MassiveActionsFieldsDisplay($options=array()) {
   global $LANG;

   $table = $options['options']['table'];
   $field = $options['options']['field'];
   $linkfield = $options['options']['linkfield'];

   // Table fields
//   echo $table.".".$field."<br/>";
   switch ($table.".".$field) {

      case 'glpi_plugin_fusinvsnmp_configsecurities.name':
         Dropdown::show("PluginFusinvsnmpConfigSecurity",
                        array('name' => $linkfield));
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_models.name':
         Dropdown::show("PluginFusinvsnmpModel",
                        array('name' => $linkfield,
                              'comment' => false));
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_unknowndevices.type' :
         $type_list = array();
         $type_list[] = COMPUTER_TYPE;
         $type_list[] = NETWORKING_TYPE;
         $type_list[] = PRINTER_TYPE;
         $type_list[] = PERIPHERAL_TYPE;
         $type_list[] = PHONE_TYPE;
         Device::dropdownTypes('type',$linkfield,$type_list);
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.id' :
         Dropdown::show("PluginFusinvsnmpAgent",
                        array('name' => $linkfield,
                              'comment' => false));
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.nb_process_query' :
         Dropdown::showInteger("nb_process_query", $linkfield,1,200);
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.nb_process_discovery' :
         Dropdown::showInteger("nb_process_discovery", $linkfield,1,400);
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.logs' :
         $ArrayValues = array();
         $ArrayValues[]= $LANG["choice"][0];
         $ArrayValues[]= $LANG["choice"][1];
         $ArrayValues[]= $LANG["setup"][137];
         Dropdown::showFromArray('logs', $ArrayValues,
                                 array('value'=>$linkfield));
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.core_discovery' :
         Dropdown::showInteger("core_discovery", $linkfield,1,32);
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.core_query' :
         Dropdown::showInteger("core_query", $linkfield,1,32);
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.threads_discovery' :
         Dropdown::showInteger("threads_discovery", $linkfield,1,400);
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_agents.threads_query' :
         Dropdown::showInteger("threads_query", $linkfield,1,400);
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_discovery.plugin_fusinvsnmp_snmpauths_id' :
         $pfConfigSecurity = new PluginFusinvsnmpConfigSecurity();
         echo $pfConfigSecurity->selectbox();
         return true;
         break;

      case 'glpi_plugin_fusinvsnmp_models.itemtype' :
         $type_list = array();
         $type_list[] = COMPUTER_TYPE;
         $type_list[] = NETWORKING_TYPE;
         $type_list[] = PRINTER_TYPE;
         $type_list[] = PERIPHERAL_TYPE;
         $type_list[] = PHONE_TYPE;
         Device::dropdownTypes('type',$linkfield,$type_list);
         return true;
         break;

      case 'glpi_entities.name' :
         if (Session::isMultiEntitiesMode()) {
            Dropdown::show("Entities",
                           array('name' => "entities_id",
                           'value' => $_SESSION["glpiactive_entity"]));
         }
         return true;
         break;
   }
   return false;
}



function plugin_fusinvsnmp_addSelect($type,$id,$num) {
   global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];
//echo "add select : ".$table.".".$field."<br/>";
   switch ($type) {
      // * Computer List (front/computer.php)
      case 'Computer':

         switch ($table.".".$field) {

         // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.name" :
               return "GROUP_CONCAT(glpi_networkequipments.name SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               return "GROUP_CONCAT( DISTINCT 
                     CONCAT_WS('....', FUSIONINVENTORY_22.items_id,FUSIONINVENTORY_22.name)
                  SEPARATOR '$$$$') AS ITEM_$num, ";
               break;
         }
         break;
      // * PRINTER List (front/printer.php)
      case 'Printer':
         switch ($table.".".$field) {

         // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.name" :
               return "GROUP_CONCAT(glpi_networkequipments.name SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               return "GROUP_CONCAT( FUSIONINVENTORY_22.name SEPARATOR '$$$$') AS ITEM_$num, ";
               break;
            
            case "glpi_plugin_fusinvsnmp_configsecurities.name" :
               return "glpi_plugin_fusinvsnmp_configsecurities.name AS ITEM_$num, ";
               break;

         }
         break;

      case 'PluginFusioninventoryUnknownDevice' :
         switch ($table.".".$field) {

            case "glpi_networkequipments.device" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.items_id SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

            case "glpi_networkports.NetworkPort" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
               break;
  
           }
         break;

      case 'PluginFusioninventoryIPRange' :
         switch ($table.".".$SEARCH_OPTION[$type][$id]["linkfield"]) {

            case "glpi_plugin_fusinvsnmp_agents.plugin_fusinvsnmp_agents_id_query" :
               return "GROUP_CONCAT( DISTINCT CONCAT(gpta.name,'$$',gpta.id) SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

         }
         break;

      case "PluginFusinvsnmpPrinterLog":
//         if ($table.".".$field == "$table.".".$field") {
//            return " `glpi_printers`.`name` AS ITEM_".$num.", DISTINCT `glpi_printers`.`id` AS ITEM_".$num."_2,";
//         }
         if ($table.".".$field == "glpi_users.name") {
            return " `glpi_users`.`name` AS ITEM_$num, `glpi_users`.`realname` AS ITEM_".$num."_2, `glpi_users`.`id` AS ITEM_".$num."_3, `glpi_users`.`firstname` AS ITEM_".$num."_4,";
         }
         break;

      case 'PluginFusinvsnmpPrinterLogReport':

         if ($table == 'glpi_plugin_fusinvsnmp_printerlogs') {
            if (strstr($field, 'pages_') OR $field == 'scanned') {
               return " (
                  (SELECT ".$field." from glpi_plugin_fusinvsnmp_printerlogs where printers_id = glpi_printers.id
                  AND date <= '".$_SESSION['glpi_plugin_fusioninventory_date_end']." 23:59:59' ORDER BY date DESC LIMIT 1) 
                  -
                  (SELECT ".$field." from glpi_plugin_fusinvsnmp_printerlogs where printers_id = glpi_printers.id
                  AND date >= '".$_SESSION['glpi_plugin_fusioninventory_date_start']." 00:00:00'  ORDER BY date  LIMIT 1)
                  )  AS ITEM_$num, ";
            }
         }
         if ($table.".".$field == "glpi_networkports.ip") {
            return " `glpi_networkports`.`ip`  AS ITEM_$num, ";
         }
         break;
   }
   return "";
}


function plugin_fusinvsnmp_forceGroupBy($type) {
   return true;
}


// Search modification for plugin FusionInventory

function plugin_fusinvsnmp_addLeftJoin($itemtype,$ref_table,$new_table,$linkfield,&$already_link_tables) {

//echo "Left Join : ".$new_table.".".$linkfield."<br/>";
   switch ($itemtype) {
      // * Computer List (front/computer.php)
      case 'Computer':

         switch ($new_table.".".$linkfield) {
            // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_networkequipments_id" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_1 OR glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = glpi_networkports.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments ON FUSIONINVENTORY_12.items_id=glpi_networkequipments.id";

               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_10 ON (FUSIONINVENTORY_10.items_id = glpi_computers.id AND FUSIONINVENTORY_10.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_1 OR FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = FUSIONINVENTORY_10.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments ON FUSIONINVENTORY_12.items_id=glpi_networkequipments.id";
               }
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.plugin_fusinvsnmp_networkports_id" :
               $table_networking_ports = 0;
               $table_fusinvsnmp_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_fusinvsnmp_networkequipments.id") {
                     $table_fusinvsnmp_networking = 1;
                  }
               }
               if ($table_fusinvsnmp_networking == "1") {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id=FUSIONINVENTORY_12.id ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON glpi_networkports.id = FUSIONINVENTORY_21.networkports_id_1 OR glpi_networkports.id = FUSIONINVENTORY_21.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.networkports_id_1 = glpi_networkports.id THEN FUSIONINVENTORY_21.networkports_id_2 ELSE FUSIONINVENTORY_21.networkports_id_1 END ";
               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.items_id = glpi_computers.id AND FUSIONINVENTORY_20.itemtype='Computer') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.networkports_id_1 OR FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.networkports_id_1 = FUSIONINVENTORY_20.id THEN FUSIONINVENTORY_21.networkports_id_2 ELSE FUSIONINVENTORY_21.networkports_id_1 END ";

               }
               break;

         }
         break;

      // * Networking List (front/networking.php)
      case 'NetworkEquipment':
         $already_link_tables_tmp = $already_link_tables;
         array_pop($already_link_tables_tmp);

         $leftjoin_fusinvsnmp_networkequipments = 1;
         if ((in_array('glpi_plugin_fusinvsnmp_networkequipments', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_models', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_configsecurities', $already_link_tables_tmp))) {

            $leftjoin_fusinvsnmp_networkequipments = 0;
         }

         switch ($new_table.".".$linkfield) {

            // ** FusionInventory - last inventory
            case "glpi_plugin_fusinvsnmp_networkequipments." :
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                  return " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON (glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id) ";
               }
               return " ";
               break;

            // ** FusionInventory - cpu
            case "glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_networkequipments_id" :
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                     return " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON (glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id) ";
               }
               return " ";
               break;


            // ** FusionInventory - SNMP models
            case "glpi_plugin_fusinvsnmp_models.plugin_fusinvsnmp_models_id" :
               $return = "";
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON (glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id) ";
               }
               return $return." LEFT JOIN glpi_plugin_fusinvsnmp_models ON (glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_models_id = glpi_plugin_fusinvsnmp_models.id) ";
               break;

            // ** FusionInventory - SNMP authentification
            case "glpi_plugin_fusinvsnmp_configsecurities.plugin_fusinvsnmp_configsecurities_id":
               $return = "";
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id ";
               }
               return $return." LEFT JOIN glpi_plugin_fusinvsnmp_configsecurities ON glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_configsecurities_id = glpi_plugin_fusinvsnmp_configsecurities.id ";
               break;

            case "glpi_plugin_fusinvsnmp_networkequipments.sysdescr":
               $return = " ";
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id ";
               }
               return $return;
               break;

         }
         break;
//
//    // * Printer List (front/printer.php)
      case 'Printer':
         $already_link_tables_tmp = $already_link_tables;
         array_pop($already_link_tables_tmp);
         $leftjoin_fusinvsnmp_printers = 1;
         if ((in_array('glpi_plugin_fusinvsnmp_printers', $already_link_tables_tmp))
              OR in_array('glpi_plugin_fusinvsnmp_models', $already_link_tables_tmp)) {

            $leftjoin_fusinvsnmp_printers = 0;
         }
         switch ($new_table.".".$linkfield) {

            // ** FusionInventory - last inventory
            case "glpi_plugin_fusinvsnmp_printers.plugin_fusinvsnmp_printers_id" :
               if ($leftjoin_fusinvsnmp_printers == "1") {
                  return " LEFT JOIN glpi_plugin_fusinvsnmp_printers ON (glpi_printers.id = glpi_plugin_fusinvsnmp_printers.printers_id) ";
               }
               return " ";
               break;

            // ** FusionInventory - SNMP models
            case "glpi_plugin_fusinvsnmp_models.plugin_fusinvsnmp_models_id" :
               $return = "";
               if ($leftjoin_fusinvsnmp_printers == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_printers ON (glpi_printers.id = glpi_plugin_fusinvsnmp_printers.printers_id) ";
               }
               return $return." LEFT JOIN glpi_plugin_fusinvsnmp_models ON (glpi_plugin_fusinvsnmp_printers.plugin_fusinvsnmp_models_id = glpi_plugin_fusinvsnmp_models.id) ";
               break;
               
            // ** FusionInventory - SNMP authentification
            case "glpi_plugin_fusinvsnmp_configsecurities.id":
               $return = "";
               if ($leftjoin_fusinvsnmp_printers == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_printers ON glpi_printers.id = glpi_plugin_fusinvsnmp_printers.printers_id ";
               }
               return $return." LEFT JOIN glpi_plugin_fusinvsnmp_configsecurities ON glpi_plugin_fusinvsnmp_printers.plugin_fusinvsnmp_configsecurities_id = glpi_plugin_fusinvsnmp_configsecurities.id ";
               break;

            case "glpi_plugin_fusinvsnmp_printers.plugin_fusinvsnmp_printers_id":
               $return = " ";
               if ($leftjoin_fusinvsnmp_printers == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_printers ON glpi_printers.id = glpi_plugin_fusinvsnmp_printers.printers_id ";
               }
               return $return;
               break;

            // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_networkequipments_id" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_1 OR glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = glpi_networkports.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments ON FUSIONINVENTORY_12.items_id=glpi_networkequipments.id";

               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_10 ON (FUSIONINVENTORY_10.items_id = glpi_printers.id AND FUSIONINVENTORY_10.itemtype='Printer') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_1 OR FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = FUSIONINVENTORY_10.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments ON FUSIONINVENTORY_12.items_id=glpi_networkequipments.id";
               }
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.plugin_fusinvsnmp_networkports_id" :
               $table_networking_ports = 0;
               $table_fusinvsnmp_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_fusinvsnmp_networkequipments.id") {
                     $table_fusinvsnmp_networking = 1;
                  }
               }
               if ($table_fusinvsnmp_networking == "1") {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id=FUSIONINVENTORY_12.id ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON glpi_networkports.id = FUSIONINVENTORY_21.networkports_id_1 OR glpi_networkports.id = FUSIONINVENTORY_21.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.networkports_id_1 = glpi_networkports.id THEN FUSIONINVENTORY_21.networkports_id_2 ELSE FUSIONINVENTORY_21.networkports_id_1 END ";
               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.items_id = glpi_printers.id AND FUSIONINVENTORY_20.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.networkports_id_1 OR FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.networkports_id_1 = FUSIONINVENTORY_20.id THEN FUSIONINVENTORY_21.networkports_id_2 ELSE FUSIONINVENTORY_21.networkports_id_1 END ";

               }
               break;

         }
         break;

		case 'PluginFusioninventoryUnknownDevice' :
         $already_link_tables_tmp = $already_link_tables;
         array_pop($already_link_tables_tmp);
         $leftjoin_fusinvsnmp_unknown = 1;
         if ((in_array('glpi_plugin_fusinvsnmp_unknowndevices', $already_link_tables_tmp))
              OR in_array('glpi_plugin_fusinvsnmp_models', $already_link_tables_tmp)
              OR in_array('glpi_plugin_fusinvsnmp_configsecurities', $already_link_tables_tmp)) {

            $leftjoin_fusinvsnmp_unknown = 0;
         }

         switch ($new_table.".".$linkfield) {

            case "glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusinvsnmp_unknowndevices_id":
               if ($leftjoin_fusinvsnmp_unknown == "1") {
                  return " LEFT JOIN glpi_plugin_fusinvsnmp_unknowndevices ON (glpi_plugin_fusioninventory_unknowndevices.id = glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusioninventory_unknowndevices_id) ";
               } else {
                  return " ";
               }
               break;
            
            // ** FusionInventory - SNMP models
            case "glpi_plugin_fusinvsnmp_models.plugin_fusinvsnmp_models_id" :
               $return = "";
               if ($leftjoin_fusinvsnmp_unknown == "1") {
                  $return .= " LEFT JOIN glpi_plugin_fusinvsnmp_unknowndevices ON (glpi_plugin_fusioninventory_unknowndevices.id = glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusioninventory_unknowndevices_id) ";
               }
               $return .= " LEFT JOIN glpi_plugin_fusinvsnmp_models ON (glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusinvsnmp_models_id = glpi_plugin_fusinvsnmp_models.id) ";
               return $return;
               break;
               
            case 'glpi_plugin_fusinvsnmp_configsecurities.plugin_fusinvsnmp_configsecurities_id':
               $return = "";
               if ($leftjoin_fusinvsnmp_unknown == "1") {
                  $return .= " LEFT JOIN glpi_plugin_fusinvsnmp_unknowndevices ON (glpi_plugin_fusioninventory_unknowndevices.id = glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusioninventory_unknowndevices_id) ";
               }               
               $return .= " LEFT JOIN `glpi_plugin_fusinvsnmp_configsecurities` ON (`glpi_plugin_fusinvsnmp_unknowndevices`.`plugin_fusinvsnmp_configsecurities_id` = `glpi_plugin_fusinvsnmp_configsecurities`.`id` ) ";
               return $return;
               break;

			}
         return;
			break;

      case "PluginFusinvsnmpPrinterLog":
         if ($new_table == "glpi_infocoms") {
            return " LEFT JOIN glpi_infocoms ON (glpi_printers.ID = glpi_infocoms.FK_device AND glpi_infocoms.device_type='".PRINTER_TYPE."')
                    LEFT JOIN glpi_dropdown_budget ON glpi_dropdown_budget.ID = glpi_infocoms.budget ";
         }

         switch ($new_table.".".$linkfield) {

            case "glpi_locations.locations_id":
            case "glpi_printertypes.printertypes_id":
            case "glpi_printermodels.printermodels_id":
            case "glpi_states.states_id":
            case "glpi_users.users_id":
            case "glpi_manufacturers.manufacturers_id":

               return " LEFT JOIN `".$new_table."` ON (`glpi_printers`.`".$linkfield."` = `".$new_table."`.`id`)  ";
               break;

            case "glpi_networkports.id":
               return " LEFT JOIN `glpi_networkports` ON (`glpi_printers`.`id` = `glpi_networkports`.`items_id` AND `glpi_networkports`.`itemtype` = 'Printer') ";
               break;
         }
         break;

      case 'PluginFusioninventoryAgent';
         if ($new_table.".".$linkfield == 'glpi_plugin_fusinvsnmp_agentconfigs.plugin_fusinvsnmp_agentconfigs_id') {
            return " LEFT JOIN `glpi_plugin_fusinvsnmp_agentconfigs` ON (`glpi_plugin_fusioninventory_agents`.`id` = `glpi_plugin_fusinvsnmp_agentconfigs`.`plugin_fusioninventory_agents_id`) ";
         }

         break;

      case 'PluginFusinvsnmpPrinterLogReport':
         
         switch ($new_table.".".$linkfield) {

            case 'glpi_locations.locations_id':
               return " LEFT JOIN `glpi_locations` ON (`glpi_printers`.`locations_id` = `glpi_locations`.`id`) ";
               break;
            
            case 'glpi_printertypes.printertypes_id':
               return " LEFT JOIN `glpi_printertypes` ON (`glpi_printers`.`printertypes_id` = `glpi_printertypes`.`id`) ";
               break;
            
            case 'glpi_states.states_id':
               return " LEFT JOIN `glpi_states` ON (`glpi_printers`.`states_id` = `glpi_states`.`id`) ";
               break;

            case 'glpi_users.users_id':
               return " LEFT JOIN `glpi_users` AS glpi_users ON (`glpi_printers`.`users_id` = `glpi_users`.`id`) ";
               break;
            
            case 'glpi_manufacturers.manufacturers_id':
               return " LEFT JOIN `glpi_manufacturers` ON (`glpi_printers`.`manufacturers_id` = `glpi_manufacturers`.`id`) ";
               break;
            
            case 'glpi_networkports.printers_id':
               return " LEFT JOIN `glpi_networkports` ON (`glpi_printers`.`id` = `glpi_networkports`.`items_id` AND `glpi_networkports`.`itemtype` = 'Printer') ";
               break;

            case 'glpi_plugin_fusinvsnmp_printerlogs.printers_id':
               return " LEFT JOIN `glpi_plugin_fusinvsnmp_printerlogs` ON (`glpi_plugin_fusinvsnmp_printerlogs`.`printers_id` = `glpi_printers`.`id`) ";
               break;
               
         }         
         break;
   }
   return "";
}



function plugin_fusinvsnmp_addOrderBy($type,$id,$order,$key=0) {

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];
// echo "ORDER BY :".$type." ".$table.".".$field;

   switch ($type) {
      // * Computer List (front/computer.php)
      case 'Computer':
         switch ($table.".".$field) {

            // ** FusionInventory - switch
            case "glpi_networkequipments.device" :
               return " ORDER BY glpi_networkequipments.name $order ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
               break;

         }
         break;

      // * Networking List (front/networking.php)
      case 'NetworkEquipment':
         switch ($table.".".$field) {

            // ** FusionInventory - last inventory
            case "glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id" :
               return " ORDER BY glpi_plugin_fusinvsnmp_networkequipments.last_fusinvsnmp_update $order ";
               break;

            // ** FusionInventory - SNMP models
            case "glpi_plugin_fusinvsnmp_models.id" :
               return " ORDER BY glpi_plugin_fusinvsnmp_models.name $order ";
               break;

         }
         break;

      // * Printer List (front/printer.php)
      case 'Printer':
         switch ($table.".".$field) {

            // ** FusionInventory - last inventory
            case "glpi_plugin_fusinvsnmp_printers.printers_id" :
               return " ORDER BY glpi_plugin_fusinvsnmp_printers.last_fusinvsnmp_update $order ";
               break;

            // ** FusionInventory - SNMP models
            case "glpi_plugin_fusinvsnmp_models.id" :
               return " ORDER BY glpi_plugin_fusinvsnmp_models.name $order ";
               break;

            // ** FusionInventory - SNMP authentification
            case "glpi_plugin_fusinvsnmp_configsecurities.id" :
               return " ORDER BY glpi_plugin_fusinvsnmp_configsecurities.name $order ";
               break;

            // ** FusionInventory - switch
            case "glpi_networkequipments.device" :
               return " ORDER BY glpi_networkequipments.name $order ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
               break;

         }
         break;

      // * Unknown mac addresses connectd on switch - report (plugins/fusinvsnmp/report/unknown_mac.php)
      case 'PluginFusioninventoryUnknownDevice' :
         switch ($table.".".$field) {

            // ** FusionInventory - switch
            case "glpi_networkequipments.device" :
               return " ORDER BY FUSIONINVENTORY_12.items_id $order ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
               break;

         }
         break;

      // * Ports date connection - report (plugins/fusinvsnmp/report/ports_date_connections.php)
      case 'PluginFusinvsnmpNetworkport' :
         switch ($table.".".$field) {

            // ** Location of switch
            case "glpi_locations.id" :
               return " ORDER BY glpi_locations.name $order ";
               break;

         }
         break;

      // * range IP list (plugins/fusinvsnmp/front/iprange.php)
      case 'PluginFusioninventoryIPRange' :
         switch ($table.".".$field) {
         
            // ** Agent name associed to IP range and link to agent form
            case "glpi_plugin_fusinvsnmp_agents.id" :
               return " ORDER BY glpi_plugin_fusinvsnmp_agents.name $order ";
               break;

         }
         break;

      // * Detail of ports history (plugins/fusinvsnmp/report/switch_ports.history.php)
      case 'PluginFusinvsnmpNetworkPortLog' :
         switch ($table.".".$field) {

            // ** Display switch and Port
            case "glpi_plugin_fusinvsnmp_networkportlogs.id" :
               return " ORDER BY glpi_plugin_fusinvsnmp_networkportlogs.id $order ";
               break;
            case "glpi_networkports.id" :
               return " ORDER BY glpi_networkequipments.name,glpi_networkports.name $order ";
               break;

            // ** Display GLPI field of device
            case "glpi_plugin_fusinvsnmp_networkportlogs.field" :
               return " ORDER BY glpi_plugin_fusinvsnmp_networkportlogs.field $order ";
               break;

            // ** Display Old Value (before changement of value)
            case "glpi_plugin_fusinvsnmp_networkportlogs.old_value" :
               return " ORDER BY glpi_plugin_fusinvsnmp_networkportlogs.old_value $order ";
               break;

            // ** Display New Value (new value modified)
            case "glpi_plugin_fusinvsnmp_networkportlogs.new_value" :
               return " ORDER BY glpi_plugin_fusinvsnmp_networkportlogs.new_value $order ";
               break;

            case "glpi_plugin_fusinvsnmp_networkportlogs.date_mod" :
            return " ORDER BY glpi_plugin_fusinvsnmp_networkportlogs.date_mod $order ";
                  break;

         }
         break;

      case "PluginFusinvsnmpPrinterLog":
//         return " GROUP BY ITEM_0_2
//            ORDER BY ITEM_".$key." $order ";
         break;

   }
   return "";
}



function plugin_fusinvsnmp_addWhere($link,$nott,$type,$id,$val) {
   global $SEARCH_OPTION;
   
   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

//echo "add where : ".$table.".".$field."<br/>";

   switch ($type) {
      // * Computer List (front/computer.php)
      case 'Computer':
         switch ($table.".".$field) {

            // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.name" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR glpi_networkequipments.id IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR glpi_networkequipments.id IS NOT NULL";
               }
               return $link." (glpi_networkequipments.id  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_22.name  LIKE '%".$val."%' $ADD ) ";
               break;

         }
         break;

      // * Networking List (front/networking.php)
      case NETWORKING_TYPE :
         switch ($table.".".$field) {

         // ** FusionInventory - last inventory
            case "glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.last_fusinvsnmp_update IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.last_fusinvsnmp_update IS NOT NULL";
               }
               return $link." ($table.last_fusinvsnmp_update  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - SNMP models
            case "glpi_plugin_fusinvsnmp_models.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NOT NULL";
               }
               return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - SNMP authentification
            case "glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_snmpauths_id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR glpi_plugin_fusinvsnmp_configsecurities.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR glpi_plugin_fusinvsnmp_configsecurities.name IS NOT NULL";
               }
               return $link." (glpi_plugin_fusinvsnmp_configsecurities.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - CPU
            case "glpi_plugin_fusinvsnmp_networkequipments.cpu":

               break;

         }
         break;

      // * Printer List (front/printer.php)
      case 'Printer':
         switch ($table.".".$field) {

            // ** FusionInventory - last inventory
            case "glpi_plugin_fusinvsnmp_printers.printers_id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.last_fusinvsnmp_update IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.last_fusinvsnmp_update IS NOT NULL";
               }
               return $link." ($table.last_fusinvsnmp_update  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - SNMP models
            case "glpi_plugin_fusinvsnmp_models.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NOT NULL";
               }
               return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - SNMP authentification
            case "glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_snmpauths_id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NOT NULL";
               }
               return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.name" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR glpi_networkequipments.id IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR glpi_networkequipments.id IS NOT NULL";
               }
               return $link." (glpi_networkequipments.id  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_22.name  LIKE '%".$val."%' $ADD ) ";
               break;

         }
         break;

      // * Unknown mac addresses connectd on switch - report (plugins/fusinvsnmp/report/unknown_mac.php)
      case 'PluginFusioninventoryUnknownDevice' :
         switch ($table.".".$field) {

            // ** FusionInventory - switch
            case "glpi_plugin_fusinvsnmp_networkequipments.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.items_id IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.items_id IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_13.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusinvsnmp_networkports.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_22.name  LIKE '%".$val."%' $ADD ) ";
               break;
         }
         break;

      // * Ports date connection - report (plugins/fusinvsnmp/report/ports_date_connections.php)
      case 'PluginFusinvsnmpNetworkport' :
         switch ($table.".".$field) {

            // ** Name and link of networking device (switch)
            case "glpi_plugin_fusinvsnmp_networkports.id" :
            break;

            // ** Name and link of port of networking device (port of switch)
            case "glpi_plugin_fusinvsnmp_networkports.networkports_id" :
               break;

            // ** Location of switch
            case "glpi_locations.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR glpi_networkequipments.location IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR glpi_networkequipments.location IS NOT NULL";
               }
               if ($val == "0") {
                  return $link." (glpi_networkequipments.location >= -1 ) ";
               }
               return $link." (glpi_networkequipments.location = '".$val."' $ADD ) ";
               break;

            case "glpi_plugin_fusinvsnmp_networkports.lastup" :
               $ADD = "";
               //$val = str_replace("&lt;",">",$val);
               //$val = str_replace("\\","",$val);
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.$field IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.$field IS NOT NULL";
               }
               return $link." ($table.$field $val $ADD ) ";
               break;
         }
         break;

      // * range IP list (plugins/fusinvsnmp/front/iprange.php)
      case 'PluginFusioninventoryIPRange' :
         switch ($table.".".$field) {

            // ** Name of range IP and link to form
            case "glpi_plugin_fusioninventory_ipranges.name" :
               break;

            // ** Agent name associed to IP range and link to agent form
            case "glpi_plugin_fusinvsnmp_agents.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NOT NULL";
               }
               return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
               break;

         }

         switch ($table.".".$SEARCH_OPTION[$type][$id]["linkfield"]) {

            case "glpi_plugin_fusinvsnmp_agents.plugin_fusinvsnmp_agents_id_query" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.name IS NOT NULL";
               }
               return $link." (gpta.name  LIKE '%".$val."%' $ADD ) ";
               break;

         }

         break;

      // * Detail of ports history (plugins/fusinvsnmp/report/switch_ports.history.php)
      case 'PluginFusinvsnmpNetworkPortLog' :
         switch ($table.".".$field) {

            // ** Display switch and Port
            case "glpi_networkports.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.id IS NULL ";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.id IS NOT NULL ";
               }
               return $link." ($table.id = '".$val."' $ADD ) ";
               break;

            // ** Display GLPI field of device
            case "glpi_plugin_fusinvsnmp_networkportlogs.field" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR $table.$field IS NULL ";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR $table.$field IS NOT NULL ";
               }
               if (!empty($val)) {
//                  include ($CFG_GLPI['root_doc'] . "/plugins/fusinvsnmp/inc_constants/snmp.mapping.constant.php");
//                $val = $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$val]['field'];
                  $map = new PluginFusioninventoryMapping;
                  $mapfields = $map->get('NetworkEquipment', $val);
                  if ($mapfields != false) {
                     $val = $mapfields['tablefields'];
                  } else {
                     $val = '';
                  }
               }
               return $link." ($table.$field = '".addslashes($val)."' $ADD ) ";
               break;

         }
   }
   return "";
}



function plugin_pre_item_purge_fusinvsnmp($parm) {
   
   switch (get_class($parm)) {
   
      case 'NetworkPort_NetworkPort':
      $networkPort = new NetworkPort();
      if ($networkPort->getFromDB($parm->fields['networkports_id_1'])) {
         if (($networkPort->fields['itemtype']) == 'NetworkEquipment') {
            PluginFusinvsnmpNetworkPortLog::addLogConnection("remove",$parm->fields['networkports_id_1']);
         } else {
            $networkPort->getFromDB($parm->fields['networkports_id_2']);
            if (($networkPort->fields['itemtype']) == 'NetworkEquipment') {
               PluginFusinvsnmpNetworkPortLog::addLogConnection("remove",$parm->fields['networkports_id_2']);
            }
         }
      }
      break;
   }
   return $parm;
}


function plugin_item_purge_fusinvsnmp($parm) {
   global $DB;
   
   switch (get_class($parm)) {

      case 'NetworkEquipment':
         // Delete all ports
         $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkequipments`
                          WHERE `networkequipments_id`='".$parm->fields["id"]."';";
         $DB->query($query_delete);

         $query_select = "SELECT `glpi_plugin_fusinvsnmp_networkports`.`id`,
                              `glpi_networkports`.`id` as nid
                          FROM `glpi_plugin_fusinvsnmp_networkports`
                               LEFT JOIN `glpi_networkports`
                                         ON `glpi_networkports`.`id` = `networkports_id`
                          WHERE `items_id`='".$parm->fields["id"]."'
                                AND `itemtype`='NetworkEquipment';";
         $result=$DB->query($query_select);
         while ($data=$DB->fetch_array($result)) {
            $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkports`
                             WHERE `id`='".$data["id"]."';";
            $DB->query($query_delete);
            $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkportlogs`
                           WHERE `networkports_id`='".$data['nid']."'";
            $DB->query($query_delete);
         }

         $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                          WHERE `networkequipments_id`='".$parm->fields["id"]."';";
         $DB->query($query_delete);

         break;

      case "Printer":
         $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_printers`
                          WHERE `printers_id`='".$parm->fields["id"]."';";
         $DB->query($query_delete);
         $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_printercartridges`
                          WHERE `printers_id`='".$parm->fields["id"]."';";
         $DB->query($query_delete);
         $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_printerlogs`
                          WHERE `printers_id`='".$parm->fields["id"]."';";
         $DB->query($query_delete);
         break;

      case 'PluginFusioninventoryUnknownDevice' :
         $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_unknowndevices`
                          WHERE `plugin_fusioninventory_unknowndevices_id`='".$parm->fields["id"]."';";
         $DB->query($query_delete);
         break;

   }
   return $parm;
}



function plugin_pre_item_delete_fusinvsnmp($parm) {
   global $DB;

   if (isset($parm["_item_type_"])) {
      switch ($parm["_item_type_"]) {

         case NETWORKING_PORT_TYPE :
               $query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkports`
                  WHERE `networkports_id`='".$parm["id"]."';";
               $DB->query($query_delete);
            break;

      }
   }
   return $parm;
}



function plugin_item_add_fusinvsnmp($parm) {
   
   switch (get_class($parm)) {

      case 'NetworkPort_NetworkPort':
      $networkPort = new NetworkPort();
      $networkPort->getFromDB($parm->fields['networkports_id_1']);
      if ($networkPort->fields['itemtype'] == 'NetworkEquipment') {
         PluginFusinvsnmpNetworkPortLog::addLogConnection("make",$parm->fields['networkports_id_1']);
      } else {
         $networkPort->getFromDB($parm->fields['networkports_id_2']);
         if ($networkPort->fields['itemtype'] == 'NetworkEquipment') {
            PluginFusinvsnmpNetworkPortLog::addLogConnection("make",$parm->fields['networkports_id_2']);
         }
      }
      break;

   }
   return $parm;
}

?>