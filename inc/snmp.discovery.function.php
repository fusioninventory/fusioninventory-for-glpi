<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}
	

/**
 * Description
 *
 * @param
 * @param
 *
 * @return
 *
**/
function plugin_fusioninventory_discovery_update_devices($array, $target) {
	global $DB;

	foreach ($array as $key=>$value) {
		if (strstr($key, "model_infos")) {
			$explode = explode ("-", $key);
			$query = "UPDATE `glpi_plugin_fusioninventory_discovery`
                   SET `FK_model_infos`='".$value."',`type`='".$array['type-'.$explode[1]]."'
                   WHERE `ID`='".$explode[1]."';";
			$DB->query($query);
		}
	}
}



/**
 * Function to import discovered device
 *
 * @param $discovery_ID ID of the device to import
 *
 * @return nothing
 *
**/
function plugin_fusioninventory_discovery_import($discovery_ID,$Import=0, $NoImport=0) {
	global $DB,$CFG_GLPI,$LANG;

   $Netport = new Netport;
	$ptud = new PluginFusionInventoryUnknownDevice;
	
	$ptud->getFromDB($discovery_ID);
   $query = "SELECT `ID`
             FROM `glpi_networking_ports`
             WHERE `on_device` = '".$discovery_ID."'
                   AND `device_type` = '".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."';";
	if ($result = $DB->query($query)) {
      $data = $DB->fetch_assoc($result);
      $Netport->getFromDB($data["ID"]);
   }

	switch ($ptud->fields['type']) {
		case PRINTER_TYPE :
			$Printer = new Printer;
			$tp = new PluginFusionInventoryPrinters;

			$data["FK_entities"] = $ptud->fields["FK_entities"];
			$data["name"] = $ptud->fields["name"];
         $data["location"] = $ptud->fields["location"];
			$data["serial"] = $ptud->fields["serial"];
         $data["otherserial"] = $ptud->fields["otherserial"];
         $data["contact"] = $ptud->fields["contact"];
         $data["domain"] = $ptud->fields["domain"];
			$data["comments"] = $ptud->fields["comments"];
			$ID_Device = $Printer->add($data);

         $data_Port = $Netport->fields;
         $data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $ptud->fields['type'];
			$Netport->update($data_Port);

			$data_fusioninventory["FK_printers"] = $ID_Device;
			$data_fusioninventory["FK_model_infos"] = $ptud->fields["FK_model_infos"];
			$data_fusioninventory["FK_snmp_connection"] = $ptud->fields["FK_snmp_connection"];
			$tp->add($data_fusioninventory);			

         $ptud->deleteFromDB($discovery_ID,1);
			$Import++;
			break;

		case NETWORKING_TYPE :
			$Netdevice = new Netdevice;
			$fusioninventory_networking = new PluginFusionInventoryNetworking;

			$data["FK_entities"] = $ptud->fields["FK_entities"];
			$data["name"] = $ptud->fields["name"];
         $data["location"] = $ptud->fields["location"];
			$data["serial"] = $ptud->fields["serial"];
         $data["otherserial"] = $ptud->fields["otherserial"];
         $data["contact"] = $ptud->fields["contact"];
         $data["domain"] = $ptud->fields["domain"];
			$data["comments"] = $ptud->fields["comments"];
			$data["ifaddr"] = $Netport->fields["ifaddr"];
         $data["ifmac"] = $Netport->fields["ifmac"];
			$ID_Device = $Netdevice->add($data);
         
         removeConnector($Netport->fields["ID"]);
         $Netdevice->deleteFromDB($Netport->fields["ID"]);

			$data_fusioninventory["FK_networking"] = $ID_Device;
			$data_fusioninventory["FK_model_infos"] = $ptud->fields["FK_model_infos"];
			$data_fusioninventory["FK_snmp_connection"] = $ptud->fields["FK_snmp_connection"];
			$fusioninventory_networking->add($data_fusioninventory);

			$ptud->deleteFromDB($discovery_ID,1);
			$Import++;
			break;

		case PERIPHERAL_TYPE :
			$Peripheral = new Peripheral;

			$data["FK_entities"] = $ptud->fields["FK_entities"];
			$data["name"] = $ptud->fields["name"];
         $data["location"] = $ptud->fields["location"];
			$data["serial"] = $ptud->fields["serial"];
         $data["otherserial"] = $ptud->fields["otherserial"];
         $data["contact"] = $ptud->fields["contact"];
			$data["comments"] = $ptud->fields["comments"];
			$ID_Device = $Peripheral->add($data);

         $data_Port = $Netport->fields;
         $data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $ptud->fields['type'];
			$Netport->update($data_Port);
			
			$ptud->deleteFromDB($discovery_ID,1);
			$Import++;
			break;

		case COMPUTER_TYPE :
			$Computer = new Computer;

			$data["FK_entities"] = $ptud->fields["FK_entities"];
			$data["name"] = $ptud->fields["name"];
         $data["location"] = $ptud->fields["location"];
			$data["serial"] = $ptud->fields["serial"];
         $data["otherserial"] = $ptud->fields["otherserial"];
         $data["contact"] = $ptud->fields["contact"];
         $data["domain"] = $ptud->fields["domain"];
			$data["comments"] = $ptud->fields["comments"];
			$ID_Device = $Computer->add($data);

         $data_Port = $Netport->fields;
         $data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $ptud->fields['type'];
			$Netport->update($data_Port);

			$ptud->deleteFromDB($discovery_ID,1);
			$Import++;
			break;

		case PHONE_TYPE :
			$Phone = new Phone;

			$data["FK_entities"] = $ptud->fields["FK_entities"];
			$data["name"] = $ptud->fields["name"];
         $data["location"] = $ptud->fields["location"];
			$data["serial"] = $ptud->fields["serial"];
         $data["otherserial"] = $ptud->fields["otherserial"];
         $data["contact"] = $ptud->fields["contact"];
			$data["comments"] = $ptud->fields["comments"];
			$ID_Device = $Phone->add($data);

         $data_Port = $Netport->fields;
         $data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $ptud->fields['type'];
			$Netport->update($data_Port);

			$ptud->deleteFromDB($discovery_ID,1);
			$Import++;
			break;

      default:
         // GENERIC OBJECT : Search types in generic object
         $typeimported = 0;
         $plugin = new Plugin;
         if ($plugin->isActivated('genericobject')) {
            if (TableExists("glpi_plugin_genericobject_types")) {
               $query = "SELECT * FROM `glpi_plugin_genericobject_types`
                  WHERE `status`='1' ";
               if ($result=$DB->query($query)) {
                  while ($data=$DB->fetch_array($result)) {
                     if ($ptud->fields['type'] == $data['device_type']) {
                        $Netdevice = new Netdevice;
                        $pgo = new PluginGenericObject;
                        $pgo->setType($data['device_type']);

                        $data["FK_entities"] = $ptud->fields["FK_entities"];
                        $data["name"] = $ptud->fields["name"];
                        $data["location"] = $ptud->fields["location"];
                        $data["serial"] = $ptud->fields["serial"];
                        $data["otherserial"] = $ptud->fields["otherserial"];
                        $data["contact"] = $ptud->fields["contact"];
                        $data["domain"] = $ptud->fields["domain"];
                        $data["comments"] = $ptud->fields["comments"];
                        $ID_Device = $pgo->add($data);

                        if ($pgo->canUseNetworkPorts()) {
                           $data_Port = $Netport->fields;
                           $data_Port['on_device'] = $ID_Device;
                           $data_Port['device_type'] = $ptud->fields['type'];
                           $Netport->update($data_Port);
                        } else {
                           $Netport->deleteFromDB($Netport->fields['ID']);
                        }

                        $ptud->deleteFromDB($discovery_ID,1);
                        $Import++;
                        $typeimported++;
                     }
                  }
               }
            }
         }
         // END GENERIC OBJECT

         if ($typeimported == "0") {
            $NoImport++;
         }
	}
   return array($Import, $NoImport);
}



function plugin_fusioninventory_discovery_criteria($p_criteria, $type=0) {
	global $DB;

   $ptc = new PluginFusionInventoryConfig;

   $a_criteria = array();

   $CountCriteria1 = 0;
   $CountCriteria2 = 0;
   if ($type == '0') {
      $arrayc = array('ip', 'name', 'serial', 'macaddr');
      $CountCriteria1 = $ptc->getValue('criteria1_ip');
      $CountCriteria2 = $ptc->getValue('criteria2_ip');
   } else {
      $arrayc = array('name', 'serial', 'macaddr');
   }
   $CountCriteria1 +=  $ptc->getValue('criteria1_name')
                     + $ptc->getValue('criteria1_serial')
                     + $ptc->getValue('criteria1_macaddr');
   
   $CountCriteria2 +=  $ptc->getValue('criteria2_name')
                     + $ptc->getValue('criteria2_serial')
                     + $ptc->getValue('criteria2_macaddr');

   foreach ($arrayc as $criteria) {
      if (!isset($p_criteria[$criteria])) {
         $p_criteria[$criteria] = '';
      }
   }

   switch ($CountCriteria1) {
      case 0:
         return false;
         break;

      case 1:
         foreach ($arrayc as $criteria) {
            if ($ptc->getValue('criteria1_'.$criteria) == "1"){
               if ($p_criteria[$criteria] == "") {
                  // Go to criteria2
               } else {
                  unset($a_criteria);
                  $a_criteria[$criteria] = $p_criteria[$criteria];
                  $r_find = plugin_fusioninventory_find_device($a_criteria, $type);
                  if ($r_find) {
                     return $r_find;
                  } else {
                     return false;
                  }
               }               
            }
         }
         break;

      default: // > 1
         $i = 0;
         unset($a_criteria);
         foreach ($arrayc as $criteria) {
            if ($ptc->getValue('criteria1_'.$criteria) == "1"){
               $a_criteria[$criteria] = $p_criteria[$criteria];
               if ($p_criteria[$criteria] != "") {
                  $i++;
               }
            }
         }
         if ($i == 0) {
            // Go to criteria2
         } else {
            $r_find = plugin_fusioninventory_find_device($a_criteria, $type);
            if ($r_find) {
               return $r_find;
            } else {
               unset($a_criteria);
               foreach ($arrayc as $criteria) {
                  if ($ptc->getValue('criteria1_'.$criteria) == "1"){
                     if ($p_criteria[$criteria] != "") {
                        $a_criteria[$criteria] = $p_criteria[$criteria];
                     }
                  }
               }
               $r_find = plugin_fusioninventory_find_device($a_criteria, $type);
               if ($r_find) {
                  return $r_find;
               }
            }
         }
         break;
   }

   switch ($CountCriteria2) {
      case 0:
         return false;
         break;

      case 1:
         foreach ($arrayc as $criteria) {
            if ($ptc->getValue('criteria2_'.$criteria) == "1"){
               if ($p_criteria[$criteria] == "") {
                  return false;
               } else {
                  unset($a_criteria);
                  $a_criteria[$criteria] = $p_criteria[$criteria];
                  $r_find = plugin_fusioninventory_find_device($a_criteria, $type);
                  if ($r_find) {
                     return $r_find;
                  } else {
                     return false;
                  }
               }
            }
         }
         break;

      default: // > 1
         $i = 0;
         unset($a_criteria);
         foreach ($arrayc as $criteria) {
            if ($ptc->getValue('criteria2_'.$criteria) == "1"){
               $a_criteria[$criteria] = $p_criteria[$criteria];
               if ($p_criteria[$criteria] != "") {
                  $i++;
               }
            }
         }
         if ($i == 0) {
            return false;
         } else {
            $r_find = plugin_fusioninventory_find_device($a_criteria, $type);
            if ($r_find) {
               return $r_find;
            } else {
               unset($a_criteria);
               foreach ($arrayc as $criteria) {
                  if ($ptc->getValue('criteria2_'.$criteria) == "1"){
                     if ($p_criteria[$criteria] != "") {
                        $a_criteria[$criteria] = $p_criteria[$criteria];
                     }
                  }
               }
               $r_find = plugin_fusioninventory_find_device($a_criteria, $type);
               if ($r_find) {
                  return $r_find;
               } else {
                  return false;
               }
            }
         }
         break;
   }
   return false;   
}


function plugin_fusioninventory_find_device($a_criteria, $p_type=0) {
	global $DB,$CFG_GLPI;

   $ci = new commonitem;

   if ($p_type != '0') {
      $a_types = array($p_type);
   } else {
      $a_types = array(COMPUTER_TYPE, NETWORKING_TYPE, PRINTER_TYPE, PERIPHERAL_TYPE,
                        PHONE_TYPE, PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN);
   }

   $condition = "";
   $select = "";
   foreach ($a_criteria as $criteria=>$value) {
      switch ($criteria) {
         
         case 'ip':
            $condition .= "AND `ifaddr`='".$value."' ";
            $select .= ", ifaddr";
            break;

         case 'macaddr':
            $condition .= "AND `ifmac`='".$value."' ";
            $select .= ", ifmac";
            break;

         case 'name':
            $condition .= "AND `name`='".$value."' ";
            $select .= ", name";
            break;

         case 'serial':
            $condition .= "AND `serial`='".$value."' ";
            $select .= ", serial";
            break;       
      }
   }

   foreach ($a_types as $type) {
      $ci->setType($type,true);
      $query = "SELECT ".$ci->obj->table.".ID ".$select." FROM ".$ci->obj->table;
      if ($ci->obj->table != "glpi_networking") {
         $query .= " LEFT JOIN glpi_networking_ports on on_device=".$ci->obj->table.".ID AND device_type=".$type;
      }
      $query .= " WHERE deleted=0 ".$condition;
      $result = $DB->query($query);
      if($DB->numrows($result) > 0) {
         $data = $DB->fetch_assoc($result);
         if ($p_type == '0') {
            return $data['ID'].'||'.$type;
         } else {
            return $data['ID'];
         }
      }
   }
   return false;
}

?>