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



function plugin_fusinvsnmp_getAddSearchOptions($itemtype) {
   global $LANG;

   $sopt = array();
   if ($itemtype == 'PluginFusioninventoryUnknownDevice') {

      $sopt[100]['table']         = 'glpi_plugin_fusinvsnmp_unknowndevices';
      $sopt[100]['field']         = 'sysdescr';
      $sopt[100]['linkfield']     = 'sysdescr';
      $sopt[100]['name']          = $LANG['plugin_fusinvsnmp']['snmp'][4];
      $sopt[100]['datatype']      = 'text';

      $sopt[101]['table']='glpi_plugin_fusinvsnmp_models';
      $sopt[101]['field']='name';
      $sopt[101]['linkfield']='id';
      $sopt[101]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['model_info'][4];
      $sopt[101]['datatype'] = 'itemlink';
      $sopt[101]['itemlink_type'] = 'PluginFusinvsnmpModel';

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      if ($PluginFusioninventoryConfig->getValue($plugins_id, "storagesnmpauth") == "file") {
         $sopt[102]['table'] = 'glpi_plugin_fusinvsnmp_printers';
         $sopt[102]['field'] = 'plugin_fusinvsnmp_configsecurities_id';
         $sopt[102]['linkfield'] = 'id';
         $sopt[102]['name'] = $LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['functionalities'][43];
      } else {
         $sopt[102]['table']='glpi_plugin_fusinvsnmp_configsecurities';
         $sopt[102]['field']='name';
         $sopt[102]['linkfield']='id';
         $sopt[102]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['functionalities'][43];
         $sopt[102]['datatype'] = 'itemlink';
         $sopt[102]['itemlink_type'] = 'PluginFusinvsnmpConfigSecurity';
      }

   }
   
   if ($itemtype == 'Computer') {
      $sopt[5192]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5192]['field']='id';
      $sopt[5192]['linkfield']='id';
      $sopt[5192]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][52];
      $sopt[5192]['forcegroupby']='1';

      $sopt[5193]['table']='glpi_plugin_fusinvsnmp_networkports';
      $sopt[5193]['field']='id';
      $sopt[5193]['linkfield']='id';
      $sopt[5193]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][46];
      $sopt[5193]['forcegroupby']='1';
   }

   if ($itemtype == 'Printer') {
      // Switch
      $sopt[5192]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5192]['field']='id';
      $sopt[5192]['linkfield']='id';
      $sopt[5192]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][52];
      $sopt[5192]['forcegroupby']='1';

      // Port of switch
      $sopt[5193]['table']='glpi_plugin_fusinvsnmp_networkports';
      $sopt[5193]['field']='id';
      $sopt[5193]['linkfield']='id';
      $sopt[5193]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG["reports"][46];
      $sopt[5193]['forcegroupby']='1';

      $sopt[5190]['table']='glpi_plugin_fusinvsnmp_models';
      $sopt[5190]['field']='name';
      $sopt[5190]['linkfield']='id';
      $sopt[5190]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['model_info'][4];
      $sopt[5190]['datatype'] = 'itemlink';
      $sopt[5190]['itemlink_type'] = 'PluginFusinvsnmpModel';

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      if ($PluginFusioninventoryConfig->getValue($plugins_id, "storagesnmpauth") == "file") {
         $sopt[5191]['table'] = 'glpi_plugin_fusinvsnmp_printers';
         $sopt[5191]['field'] = 'plugin_fusinvsnmp_configsecurities_id';
         $sopt[5191]['linkfield'] = 'id';
         $sopt[5191]['name'] = $LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['functionalities'][43];
      } else {
         $sopt[5191]['table']='glpi_plugin_fusinvsnmp_configsecurities';
         $sopt[5191]['field']='name';
         $sopt[5191]['linkfield']='id';
         $sopt[5191]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['functionalities'][43];
         $sopt[5191]['datatype'] = 'itemlink';
         $sopt[5191]['itemlink_type'] = 'PluginFusinvsnmpConfigSecurity';
      }

      $sopt[5194]['table']='glpi_plugin_fusinvsnmp_printers';
      $sopt[5194]['field']='last_fusioninventory_update';
      $sopt[5194]['linkfield']='last_fusioninventory_update';
      $sopt[5194]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['snmp'][53];
      $sopt[5194]['datatype'] = 'datetime';

      $sopt[5196]['table']         = 'glpi_plugin_fusinvsnmp_printers';
      $sopt[5196]['field']         = 'sysdescr';
      $sopt[5196]['linkfield']     = 'sysdescr';
      $sopt[5196]['name']          = $LANG['plugin_fusinvsnmp']['snmp'][4];
      $sopt[5196]['datatype']      = 'text';
   }

   if ($itemtype == 'NetworkEquipment') {
      $sopt[5190]['table']='glpi_plugin_fusinvsnmp_models';
      $sopt[5190]['field']='name';
      $sopt[5190]['linkfield']='id';
      $sopt[5190]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['model_info'][4];
      $sopt[5190]['datatype'] = 'itemlink';
      $sopt[5190]['itemlink_type'] = 'PluginFusinvsnmpModel';

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      if ($PluginFusioninventoryConfig->getValue($plugins_id, "storagesnmpauth") == "file") {
         $sopt[5191]['table'] = 'glpi_plugin_fusinvsnmp_networkequipments';
         $sopt[5191]['field'] = 'plugin_fusinvsnmp_configsecurities_id';
         $sopt[5191]['linkfield'] = 'id';
         $sopt[5191]['name'] = $LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['functionalities'][43];
      } else {
         $sopt[5191]['table']='glpi_plugin_fusinvsnmp_configsecurities';
         $sopt[5191]['field']='name';
         $sopt[5191]['linkfield']='id';
         $sopt[5191]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['functionalities'][43];
         $sopt[5191]['datatype'] = 'itemlink';
         $sopt[5191]['itemlink_type'] = 'PluginFusinvsnmpConfigSecurity';
      }

      $sopt[5194]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5194]['field']='last_fusioninventory_update';
      $sopt[5194]['linkfield']='last_fusioninventory_update';
      $sopt[5194]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['snmp'][53];
      $sopt[5194]['datatype'] = 'datetime';

      $sopt[5195]['table']='glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5195]['field']='cpu';
      $sopt[5195]['linkfield']='cpu';
      $sopt[5195]['name']=$LANG['plugin_fusioninventory']['title'][1]." - ".$LANG['plugin_fusinvsnmp']['snmp'][13];
      $sopt[5195]['datatype'] = 'number';

      $sopt[5196]['table']         = 'glpi_plugin_fusinvsnmp_networkequipments';
      $sopt[5196]['field']         = 'sysdescr';
      $sopt[5196]['linkfield']     = 'sysdescr';
      $sopt[5196]['name']          = $LANG['plugin_fusinvsnmp']['snmp'][4];
      $sopt[5196]['datatype']      = 'text';
   }

   return $sopt;
}



function plugin_fusinvsnmp_giveItem($type,$id,$data,$num) {
	global $CFG_GLPI, $DB, $INFOFORM_PAGES, $LANG, $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

	switch ($type) {
		// * Computer List (front/computer.php)
		case 'Computer':

			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusinvsnmp_networkequipments.id" :
					$out = '';
               $NetworkEquipment = new NetworkEquipment();
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  if (is_numeric($vartmp)) {
                     $NetworkEquipment->getFromDB($vartmp);

                     $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/front/networkequipment.form.php?id=".$vartmp."\">";
                     $out .=  $NetworkEquipment->getName(1);
                     if ($_SESSION["glpiis_ids_visible"]) $out .= " (".$vartmp.")";
                     $out .=  "</a><br/>";
                  }
               }
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusinvsnmp_networkports.id" :
					$out = '';
					if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new NetworkPort();
                  foreach ($list as $numtmp=>$vartmp) {
                     if (is_numeric($vartmp)) {
                        $np->getFromDB($vartmp);
                        $out .= "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$vartmp."'>".$np->getName(1)."</a><br/>";
                     }
                  }
					}
					return "<center>".$out."</center>";
					break;
			}
			break;

		// * Networking List (front/networking.php)
		case 'NetworkEquipment':
			switch ($table.'.'.$field) {


			}
			break;

		// * Printer List (front/printer.php)
		case 'Printer':
			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusinvsnmp_networkequipments.id" :
					$out = '';
               $NetworkEquipment = new NetworkEquipment();
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  if (is_numeric($vartmp)) {
                     $NetworkEquipment->getFromDB($vartmp);

                     $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/front/networkequipment.form.php?id=".$vartmp."\">";
                     $out .=  $NetworkEquipment->getName(1);
                     if ($_SESSION["glpiis_ids_visible"]) $out .= " (".$vartmp.")";
                     $out .=  "</a><br/>";
                  }
               }
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusinvsnmp_networkports.id" :
					$out = '';
					if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new NetworkPort();
                  foreach ($list as $numtmp=>$vartmp) {
                     if (is_numeric($vartmp)) {
                        $np->getFromDB($vartmp);
                        $out .= "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$vartmp."'>".$np->getName(1)."</a><br/>";
                     }
                  }
					}
					return "<center>".$out."</center>";
					break;

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
					$out = "<div align='center'><form></form><form method='get' action='" . GLPI_ROOT . "/plugins/fusinvsnmp/front/models.export.php' target='_blank'>
						<input type='hidden' name='model' value='" . $data["id"] . "' />
						<input name='export' src='" . GLPI_ROOT . "/pics/right.png' title='Exporter' value='Exporter' type='image'>
						</form></div>";
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
                  $NetworkPort->getDeviceData($vartmp,'PluginFusioninventoryUnknownDevice');

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES['PluginFusioninventoryUnknownDevice']."?id=".$vartmp."\">";
                  $out .=  $NetworkPort->device_name;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
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
                     $out .= "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$vartmp."'>".$np->fields["name"]."</a><br/>";
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
					$out = "<a href='".GLPI_ROOT."/front/networking.form.php?id=".$data2["id"]."'>".$data2["name"]."</a>";
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
					$out = "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$data["ITEM_$num"]."'>".$name."</a>";
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
		case 'PluginFusinvsnmpIPRange' :
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
					$Array_device = PluginFusinvsnmpNetworkPort::getUniqueObjectfieldsByportID($data["ITEM_$num"]);
					$item = new $Array_device["itemtype"];
					$item->getFromDB($Array_device["items_id"]);
					$out = "<div align='center'>" . $item->getLink(1);

					$query = "SELECT *
                         FROM `glpi_networkports`
                         WHERE `id`='" . $data["ITEM_$num"] . "';";
					$result = $DB->query($query);

					if ($DB->numrows($result) != "0") {
						$out .= "<br/><a href='".GLPI_ROOT."/front/networkport.form.php?id=".$data["ITEM_$num"]."'>".$DB->result($result, 0, "name")."</a>";
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
               if (isset($_SESSION['glpi_plugin_fusioninventory_history_start']))
                  unset($_SESSION['glpi_plugin_fusioninventory_history_start']);
               if (isset($_SESSION['glpi_plugin_fusioninventory_history_end']))
                  unset($_SESSION['glpi_plugin_fusioninventory_history_end']);
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
//	// Table => Name
//	global $LANG;
//	if (isset ($_SESSION["glpi_plugin_fusinvsnmp_installed"]) && $_SESSION["glpi_plugin_fusinvsnmp_installed"] == 1) {
//		return array (
//			"glpi_plugin_fusinvsnmp_snmpversions" => "SNMP version",
//			"glpi_plugin_fusinvsnmp_miboids" => "OID MIB",
//			"glpi_plugin_fusinvsnmp_mibobjects" => "Objet MIB",
//			"glpi_plugin_fusinvsnmp_miblabels" => "Label MIB"
//		);
//   } else {
//		return array ();
//   }
//}

/* Cron */
function cron_plugin_fusinvsnmp() {
   // TODO :Disable for the moment (may be check if functions is good or not
//	$ptud = new PluginFusioninventoryUnknownDevice;
//   $ptud->CleanOrphelinsConnections();
//	$ptud->FusionUnknownKnownDevice();
//   #Clean server script processes history
   $pfisnmph = new PluginFusinvsnmpNetworkPortLog;
   $pfisnmph->cronCleanHistory();
   return 1;
}



function plugin_fusinvsnmp_install() {
	global $DB, $LANG, $CFG_GLPI;

   $version = "2.3.0-1";
   include_once (GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
   $version_detected = pluginFusinvsnmpGetCurrentVersion($version);
   echo $version_detected;
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      pluginFusinvsnmpUpdate($version_detected);
   } else {
      include_once (GLPI_ROOT . "/plugins/fusinvsnmp/install/install.php");
      pluginFusinvsnmpInstall($version);
   }


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
   $version = "2.3.0";
   include (GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
   $version_detected = pluginFusinvsnmpGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
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
//				if ((PluginFusinvsnmpAuth::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
				$array = array ();
            //return array(
            if (($config->is_active('fusioninventory', 'remotehttpagent')) AND(PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","w"))) {
               $array[1] = $LANG['plugin_fusinvsnmp']['title'][0];
            }
				//}

            return $array;
//				}
			}
			break;

		case MONITOR_TYPE :
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
					$array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusinvsnmp']['title'][6];
				}
            if ($_GET['id'] > 0) {
               $array[2] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['xml'][0];
            }
            return $array;
			}
			break;

		case 'Printer' :
			// template case
			if ($withtemplate) {
				return array();
			// Non template case
         } else {
            $array = array ();
				if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "printer", "r")) {
					$array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusinvsnmp']['title'][6];
				}
            if ($_GET['id'] > 0) {
               $array[2] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['xml'][0];
            }
            return $array;
			}
			break;

       case 'PluginFusioninventoryAgent' :
          $array = array ();
          $array[1] = $LANG['plugin_fusinvsnmp']['agents'][24];
          return $array;
          break;

       case 'PluginFusioninventoryUnknownDevice':
          $array = array ();
          $array[1] = $LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusinvsnmp']['title'][6];
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

	$plugin_fusinvsnmp_printer = new PluginFusinvsnmpPrinter;
	$plugin_fusinvsnmp_printer->showForm($_POST['id'],
               array('target'=>GLPI_ROOT.'/plugins/fusinvsnmp/front/printer_info.form.php'));
	echo '<div id="overDivYFix" STYLE="visibility:hidden">fusinvsnmp_1</div>';

   $PluginFusinvsnmpPrinterCartridge = new PluginFusinvsnmpPrinterCartridge('glpi_plugin_fusinvsnmp_printercartridges');
   $PluginFusinvsnmpPrinterCartridge->showForm($_POST['id'],
               array('target'=>GLPI_ROOT.'/plugins/fusinvsnmp/front/printer_info.form.php'));

   $PluginFusinvsnmpPrinterLog = new PluginFusinvsnmpPrinterLog;
   $PluginFusinvsnmpPrinterLog->showGraph($_POST['id'],
               array('target'=>GLPI_ROOT . '/plugins/fusinvsnmp/front/printer_info.form.php'));

}

function plugin_headings_fusinvsnmp_printerHistory($type, $id) {
	$print_history = new PluginFusinvsnmpPrinterLog;
	$print_history->showForm($_GET["id"],
               array('target'=>GLPI_ROOT.'/plugins/fusinvsnmp/front/printer_history.form.php'));
}

function plugin_headings_fusinvsnmp_networkingInfo($type, $id) {
	$snmp = new PluginFusinvsnmpNetworkEquipment;
	$snmp->showForm($_POST['id'],
           array('target'=>GLPI_ROOT.'/plugins/fusinvsnmp/front/switch_info.form.php'));
}


function plugin_headings_fusinvsnmp_agents($type,$id) {
   $PluginFusinvsnmpAgentconfig = new PluginFusinvsnmpAgentconfig;
   $PluginFusinvsnmpAgentconfig->showForm($_POST['id']);
}


function plugin_headings_fusinvsnmp_unknowndevices($type,$id) {
   $PluginFusinvsnmpUnknownDevice = new PluginFusinvsnmpUnknownDevice();
   $PluginFusinvsnmpUnknownDevice->showForm($_POST['id']);
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
      echo "<th>".$LANG['plugin_fusioninventory']['title'][1]." ".$LANG['plugin_fusioninventory']['xml'][0];
      echo " (".$LANG['common'][26]."&nbsp;: " . convDateTime(date("Y-m-d H:i:s", filemtime(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$type."/".$folder."/".$id))).")";
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
	}
	return array ();
}

function plugin_fusinvsnmp_MassiveActionsDisplay($options=array()) {

	global $LANG, $CFG_GLPI, $DB;
	switch ($options['itemtype']) {
		case 'NetworkEquipment':
		case 'Printer':
			switch ($options['action']) {

            case "plugin_fusinvsnmp_get_model" :
               if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusinvsnmp_assign_model" :
               if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_fusinvsnmp_models`
                                   WHERE `itemtype`!='".$options['itemtype']."'";
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
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusinvsnmp_assign_auth" :
               if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity","w")) {
                  PluginFusinvsnmpSNMP::auth_dropdown();
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

			}
			break;

	}
	return "";
}

function plugin_fusinvsnmp_MassiveActionsProcess($data) {
	global $LANG;

	switch ($data['action']) {

      case "plugin_fusinvsnmp_get_model" :
         if ($data['itemtype'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
                  $PluginFusinvsnmpModel = new PluginFusinvsnmpModel;
                  $PluginFusinvsnmpModel->getrightmodel($key, NETWORKING_TYPE);
					}
				}
         } else if($data['itemtype'] == PRINTER_TYPE) {
            foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
                  $PluginFusinvsnmpModel = new PluginFusinvsnmpModel;
                  $PluginFusinvsnmpModel->getrightmodel($key, PRINTER_TYPE);
					}
				}
         }
         break;

		case "plugin_fusinvsnmp_assign_model" :
			if ($data['itemtype'] == 'NetworkEquipment') {
				foreach ($data['item'] as $items_id => $val) {
					if ($val == 1) {
                  $PluginFusinvsnmpNetworkEquipment = new PluginFusinvsnmpNetworkEquipment();

                  $PluginFusinvsnmpNetworkEquipment->load($items_id);
                  $PluginFusinvsnmpNetworkEquipment->setValue('plugin_fusinvsnmp_models_id', $data['snmp_model']);
                  $PluginFusinvsnmpNetworkEquipment->updateDB();
					}
				}
			} else if($data['itemtype'] == 'Printer') {
				foreach ($data['item'] as $items_id => $val) {
					if ($val == 1) {
                  $PluginFusinvsnmpPrinter = new PluginFusinvsnmpPrinter();

                  $PluginFusinvsnmpPrinter->load($items_id);
                  $PluginFusinvsnmpPrinter->setValue('plugin_fusinvsnmp_models_id', $data['snmp_model']);
                  $PluginFusinvsnmpPrinter->updateDB();
					}
				}
			}
			break;
      
		case "plugin_fusinvsnmp_assign_auth" :
			if ($data['itemtype'] == 'NetworkEquipment') {
				foreach ($data['item'] as $items_id => $val) {
					if ($val == 1) {
                  $PluginFusinvsnmpNetworkEquipment = new PluginFusinvsnmpNetworkEquipment();

                  $PluginFusinvsnmpNetworkEquipment->load($items_id);
                  $PluginFusinvsnmpNetworkEquipment->setValue('plugin_fusinvsnmp_configsecurities_id', $data['plugin_fusinvsnmp_configsecurities_id']);
                  $PluginFusinvsnmpNetworkEquipment->updateDB();
               }
				}
			} else if($data['itemtype'] == 'Printer') {
				foreach ($data['item'] as $items_id => $val) {
					if ($val == 1) {
                  $PluginFusinvsnmpPrinter = new PluginFusinvsnmpPrinter();

                  $PluginFusinvsnmpPrinter->load($items_id);
                  $PluginFusinvsnmpPrinter->setValue('plugin_fusinvsnmp_configsecurities_id', $data['plugin_fusinvsnmp_configsecurities_id']);
                  $PluginFusinvsnmpPrinter->updateDB();
                }
				}
			}
			break;

	}
}

// How to display specific update fields ?
// Massive Action functions
function plugin_fusinvsnmp_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield) {
	global $LANG;
	// Table fields
	//echo $table.".".$field."<br/>";
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
			$plugin_fusinvsnmp_snmp = new PluginFusinvsnmpConfigSecurity;
			echo $plugin_fusinvsnmp_snmp->selectbox();
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



function plugin_fusinvsnmp_addSelect($type,$id,$num) {
	global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

	switch ($type) {
		// * Computer List (front/computer.php)
		case 'Computer':

			switch ($table.".".$field) {

			// ** FusionInventory - switch
				case "glpi_plugin_fusinvsnmp_networkequipments.id" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.items_id SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusinvsnmp_networkports.id" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
					break;
			}
			break;
		// * PRINTER List (front/printer.php)
      case 'Printer':
         switch ($table.".".$field) {

			// ** FusionInventory - switch
				case "glpi_plugin_fusinvsnmp_networkequipments.id" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.items_id SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusinvsnmp_networkports.id" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
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

      case 'PluginFusinvsnmpIPRange' :
         switch ($table.".".$SEARCH_OPTION[$type][$id]["linkfield"]) {

            case "glpi_plugin_fusinvsnmp_agents.plugin_fusinvsnmp_agents_id_query" :
               return "GROUP_CONCAT( DISTINCT CONCAT(gpta.name,'$$' ,gpta.id) SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

         }
         break;

      case "PluginFusinvsnmpPrinterLog":
         if ($table.".".$field == "glpi_users.name") {
            return " `glpi_users`.`name` AS ITEM_$num, `glpi_users`.`realname` AS ITEM_".$num."_2, `glpi_users`.`id` AS ITEM_".$num."_3, `glpi_users`.`firstname` AS ITEM_".$num."_4,";
         }
         break;

	}
	return "";
}


function plugin_fusinvsnmp_forceGroupBy($type) {
 return true;
   switch ($type) {
      case COMPUTER_TYPE :
         // ** FusionInventory - switch
         return "GROUP BY glpi_computers.id";
         break;

      case PRINTER_TYPE :
         // ** FusionInventory - switch
         return "GROUP BY glpi_printers.id";
         break;

//      case "PluginFusinvsnmpPrinterLog":
//         return "GROUP BY ITEM_0";
//         break;
    }
    return false;
}


// Search modification for plugin FusionInventory

function plugin_fusinvsnmp_addLeftJoin($itemtype,$ref_table,$new_table,$linkfield,&$already_link_tables) {

//	echo "Left Join : ".$new_table.".".$linkfield."<br/>";
	switch ($itemtype) {
		// * Computer List (front/computer.php)
		case 'Computer':

			switch ($new_table.".".$linkfield) {
				// ** FusionInventory - switch
				case "glpi_plugin_fusinvsnmp_networkequipments.id" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_1 OR glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = glpi_networkports.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";

               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_10 ON (FUSIONINVENTORY_10.items_id = glpi_computers.id AND FUSIONINVENTORY_10.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_1 OR FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = FUSIONINVENTORY_10.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";
               }
               break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusinvsnmp_networkports.id" :
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
         if ((in_array('glpi_plugin_fusinvsnmp_networkequipments.cpu', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_networkequipments.last_fusioninventory_update', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_models.id', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_configsecurities.id', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_networkequipments.sysdescr', $already_link_tables_tmp))) {

            $leftjoin_fusinvsnmp_networkequipments = 0;
         }

			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusinvsnmp_networkequipments.last_fusioninventory_update" :
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                  return " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON (glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id) ";
					}
               return " ";
               break;

            // ** FusionInventory - cpu
				case "glpi_plugin_fusinvsnmp_networkequipments.cpu" :
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                     return " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON (glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id) ";
               }
               return " ";
               break;


				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusinvsnmp_models.id" :
               $return = "";
               if ($leftjoin_fusinvsnmp_networkequipments == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_networkequipments ON (glpi_networkequipments.id = glpi_plugin_fusinvsnmp_networkequipments.networkequipments_id) ";
               }
					return $return." LEFT JOIN glpi_plugin_fusinvsnmp_models ON (glpi_plugin_fusinvsnmp_networkequipments.plugin_fusinvsnmp_models_id = glpi_plugin_fusinvsnmp_models.id) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusinvsnmp_configsecurities.id":
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
//		// * Printer List (front/printer.php)
		case 'Printer':
         $already_link_tables_tmp = $already_link_tables;
         array_pop($already_link_tables_tmp);

         $leftjoin_fusinvsnmp_printers = 1;
         if ((in_array('glpi_plugin_fusinvsnmp_printers.last_fusioninventory_update', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_models.id', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_configsecurities.id', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_printers.sysdescr', $already_link_tables_tmp))) {

            $leftjoin_fusinvsnmp_printers = 0;
         }

			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusinvsnmp_printers.last_fusioninventory_update" :
               if ($leftjoin_fusinvsnmp_printers == "1") {
                  return " LEFT JOIN glpi_plugin_fusinvsnmp_printers ON (glpi_printers.id = glpi_plugin_fusinvsnmp_printers.printers_id) ";
					}
               return " ";
               break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusinvsnmp_models.id" :
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

            case "glpi_plugin_fusinvsnmp_printers.sysdescr":
               $return = " ";
               if ($leftjoin_fusinvsnmp_printers == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_printers ON glpi_printers.id = glpi_plugin_fusinvsnmp_printers.printers_id ";
               }
               return $return;
					break;


//			switch ($new_table.".".$linkfield) {
				// ** FusionInventory - switch
				case "glpi_plugin_fusinvsnmp_networkequipments.id" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_1 OR glpi_networkports.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = glpi_networkports.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";

               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_10 ON (FUSIONINVENTORY_10.items_id = glpi_printers.id AND FUSIONINVENTORY_10.itemtype='Printer') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_1 OR FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.networkports_id_1 = FUSIONINVENTORY_10.id THEN FUSIONINVENTORY_11.networkports_id_2 ELSE FUSIONINVENTORY_11.networkports_id_1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";
               }
               break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusinvsnmp_networkports.id" :
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
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.items_id = glpi_computers.id AND FUSIONINVENTORY_20.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.networkports_id_1 OR FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.networkports_id_2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.networkports_id_1 = FUSIONINVENTORY_20.id THEN FUSIONINVENTORY_21.networkports_id_2 ELSE FUSIONINVENTORY_21.networkports_id_1 END ";

               }
					break;

			}
			break;

		case 'PluginFusioninventoryUnknownDevice' :
         $leftjoin = "";
         $already_link_tables_tmp = $already_link_tables;
         array_pop($already_link_tables_tmp);
         
         $leftjoin_fusinvsnmp_unknowndevices = 1;
         if ((in_array('glpi_plugin_fusinvsnmp_models.id', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_configsecurities.id', $already_link_tables_tmp))
            OR (in_array('glpi_plugin_fusinvsnmp_unknowndevices.sysdescr', $already_link_tables_tmp))) {

            $leftjoin_fusinvsnmp_unknowndevices = 0;
         }

         switch ($new_table.".".$linkfield) {

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusinvsnmp_models.id" :
               $return = "";
               if ($leftjoin_fusinvsnmp_unknowndevices == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_unknowndevices ON (glpi_plugin_fusioninventory_unknowndevices.id = glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusioninventory_unknowndevices_id) ";
               }
					return $return." LEFT JOIN glpi_plugin_fusinvsnmp_models ON (glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusinvsnmp_models_id = glpi_plugin_fusinvsnmp_models.id) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusinvsnmp_configsecurities.id":
               $return = "";
               if ($leftjoin_fusinvsnmp_unknowndevices == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_unknowndevices ON (glpi_plugin_fusioninventory_unknowndevices.id = glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusioninventory_unknowndevices_id) ";
               }
               return $return." LEFT JOIN glpi_plugin_fusinvsnmp_configsecurities ON glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusinvsnmp_configsecurities_id = glpi_plugin_fusinvsnmp_configsecurities.id ";
					break;

				case "glpi_plugin_fusinvsnmp_unknowndevices.sysdescr" :
               $return = " ";
               if ($leftjoin_fusinvsnmp_unknowndevices == "1") {
                  $return = " LEFT JOIN glpi_plugin_fusinvsnmp_unknowndevices ON (glpi_plugin_fusioninventory_unknowndevices.id = glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusioninventory_unknowndevices_id) ";
               }
					return $return;
					break;

			}
         return $leftjoin;
			break;
//
//
//		// * Ports date connection - report (plugins/fusinvsnmp/report/ports_date_connections.php)
//		case 'PluginFusinvsnmpNetworkport' :
//			switch ($new_table.".".$linkfield) {
//
//				// ** Location of switch
//				case "glpi_locations.networkports_id" :
//					return " LEFT JOIN glpi_networkports ON (glpi_plugin_fusinvsnmp_networkports.networkports_id = glpi_networkports.id) ".
//						" LEFT JOIN glpi_networkequipments ON glpi_networkports.items_id = glpi_networkequipments.id".
//						" LEFT JOIN glpi_locations ON glpi_locations.id = glpi_networkequipments.location";
//					break;
//
//			}
//			break;
//
//		// * range IP list (plugins/fusinvsnmp/front/iprange.php)
//		case 'PluginFusinvsnmpIPRange' :
//			switch ($new_table.".".$linkfield) {
//
//				// ** Agent name associed to IP range and link to agent form
//				case "glpi_plugin_fusinvsnmp_agents.plugin_fusinvsnmp_agents_id_discovery" :
//					return " LEFT JOIN glpi_plugin_fusinvsnmp_agents ON (glpi_plugin_fusinvsnmp_agents.id = glpi_plugin_fusinvsnmp_ipranges.plugin_fusinvsnmp_agents_id_discovery) ";
//					break;
//
//            case "glpi_plugin_fusinvsnmp_agents.plugin_fusinvsnmp_agents_id_query" :
//               return " LEFT JOIN glpi_plugin_fusinvsnmp_agents AS gpta ON (glpi_plugin_fusinvsnmp_ipranges.plugin_fusinvsnmp_agents_id_query = gpta.id) ";
//               break;
//            
//
//			}
//			break;
//
//      // * ports updates list (report/switch_ports.history.php)
//		case 'PluginFusinvsnmpNetworkPortLog' :
//         return " LEFT JOIN `glpi_networkports` ON ( `glpi_networkports`.`id` = `glpi_plugin_fusinvsnmp_networkportlogs`.`networkports_id` ) ";
//			break;

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

	}
	return "";
}



function plugin_fusinvsnmp_addOrderBy($type,$id,$order,$key=0) {
	global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

//	echo "ORDER BY :".$type." ".$table.".".$field;

	switch ($type) {
		// * Computer List (front/computer.php)
		case 'Computer':
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
					return " ORDER BY FUSIONINVENTORY_12.items_id $order ";
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
		case 'PluginFusinvsnmpIPRange' :
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
         return " GROUP BY ITEM_0_2
            ORDER BY ITEM_".$key." $order ";
         break;

	}
	return "";
}



function plugin_fusinvsnmp_addWhere($link,$nott,$type,$id,$val) {
	global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

//	echo "add where : ".$table.".".$field."<br/>";
	$SEARCH=makeTextSearch($val,$nott);

	switch ($type) {
		// * Computer List (front/computer.php)
		case 'Computer':
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
		case 'PluginFusinvsnmpIPRange' :
			switch ($table.".".$field) {

				// ** Name of range IP and link to form
				case "glpi_plugin_fusinvsnmp_ipranges.name" :
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
//                  include (GLPI_ROOT . "/plugins/fusinvsnmp/inc_constants/snmp.mapping.constant.php");
//						$val = $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$val]['field'];
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
	global $DB;

	if (isset($parm["_item_type_"])) {
		switch ($parm["_item_type_"]) {
			case NETWORKING_TYPE :
				// Delete all ports
				$query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkequipments`
                             WHERE `networkequipments_id`='".$parm["id"]."';";
				$DB->query($query_delete);

				$query_select = "SELECT `glpi_plugin_fusinvsnmp_networkports`.`id`
                             FROM `glpi_plugin_fusinvsnmp_networkports`
                                  LEFT JOIN `glpi_networkports`
                                            ON `glpi_networkports`.`id` = `networkports_id`
                             WHERE `items_id`='".$parm["id"]."'
                                   AND `itemtype`='".NETWORKING_TYPE."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkports`
                                WHERE `id`='".$data["id"]."';";
					$DB->query($query_delete);
				}

				$query_select = "SELECT `glpi_plugin_fusinvsnmp_networkequipmentips`.`id`
                             FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                                  LEFT JOIN `glpi_networkequipments`
                                            ON `glpi_networkequipments`.`id` = `networkequipments_id`
                             WHERE `networkequipments_id`='".$parm["id"]."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                                WHERE `id`='".$data["id"]."';";
					$DB->query($query_delete);
				}
            break;

			case PRINTER_TYPE :
				$query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_printers`
                             WHERE `printers_id`='".$parm["id"]."';";
				$DB->query($query_delete);
				$query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_printercartridges`
                             WHERE `printers_id`='".$parm["id"]."';";
				$DB->query($query_delete);
				$query_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_printerlogs`
                             WHERE `printers_id`='".$parm["id"]."';";
				$DB->query($query_delete);
            break;

         case 'PluginFusioninventoryUnknownDevice' :
            // Delete ports and connections if exists
            $np=new NetworkPort;
            $nn = new NetworkPort_NetworkPort();
            $query = "SELECT `id`
                      FROM `glpi_networkports`
                      WHERE `items_id` = '".$parm["id"]."'
                            AND `itemtype` = 'PluginFusioninventoryUnknownDevice';";
            $result = $DB->query($query);
            while ($data = $DB->fetch_array($result)) {
               if ($nn->getFromDBForNetworkPort($data['id'])) {
                  $nn->delete($data);
               }
               $np->delete(array("id"=>$data["id"]));
            }
            break;

         case COMPUTER_TYPE :
            // Delete link between computer and agent fusion
            $query = "UPDATE `glpi_plugin_fusinvsnmp_agents`
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
	global $DB;

	if (isset($parm["type"])) {
		switch ($parm["type"]) {

         case NETWORKING_PORT_TYPE :
            // Verify when add networking port on object (not unknown device) if port
            // of an unknown device exist.
            if ($parm["input"]["itemtype"] != 'PluginFusioninventoryUnknownDevice') {
               // Search in DB
               $np = new NetworkPort;
               $nw = new NetworkPort_NetworkPort;
               $pfiud = new PluginFusioninventoryUnknownDevice;
               $a_ports = $np->find("`mac`='".$parm["input"]["mac"]."' AND `itemtype`='PluginFusioninventoryUnknownDevice' ");
               if (count($a_ports) == "1") {
                  $nn = new NetworkPort_NetworkPort();
                  foreach ($a_ports as $port_infos) {
                     // Get wire
                     $opposite_ID = $nw->getOppositeContact($port_infos['id']);
                     if (isset($opposite_ID)) {
                        // Modify wire
                        if ($nn->getFromDBForNetworkPort($port_infos['id'])) {
                            $nn->delete($port_infos);
                        }
                        $nn->add(array('networkports_id_1'=> $parm['id'],
                                       'networkports_id_2' => $opposite_ID));
                     }
                     // Delete port
                     $np->deleteFromDB($port_infos['id']);
                     // Delete unknown device (if it has no port)
                     if (count($np->find("`items_id`='".$port_infos['items_id']."' AND `itemtype`='PluginFusioninventoryUnknownDevice' ")) == "0") {
                        $pfiud->deleteFromDB($port_infos['items_id']);
                     }
                  }
               }
            }
            break;

      }
   }
}

?>
