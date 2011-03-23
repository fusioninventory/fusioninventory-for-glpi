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

class PluginFusinvsnmpDiscovery extends CommonDBTM {


   /**
    * Add discovered device to discovered table in MySQL
    *
    *@param $Array : datas
    *  - date
    *  - ip
    *  - name
    *  - description
    *  - serial
    *  - type
    *  - agent_id
    *  - entity
    *  - plugin_fusinvsnmp_models_id
    *  - storagesnmpauth
    *
    *@return Nothing (displays)
    **/
   function addDevice($Array) {
      global $DB;

      // Detect if device exist
      $query_sel = "SELECT *
                    FROM `glpi_plugin_fusioninventory_discovery`
                    WHERE `ip`='".$Array['ip']."'
                          AND `name`='".PluginFusinvsnmpSNMP::hex_to_string($Array['name'])."'
                          AND `descr`='".$Array['description']."'
                          AND `serialnumber`='".$Array['serial']."'
                          AND `entities_id`='".$Array['entity']."';";
		$result_sel = $DB->query($query_sel);
		if ($DB->numrows($result_sel) == "0") {
         $insert = 1;
         if (!empty($Array['serial'])) {
            // Detect is a device is same but this another IP (like switch)
            $query_sel = "SELECT *
                          FROM `glpi_plugin_fusioninventory_discovery`
                          WHERE `name`='".PluginFusinvsnmpSNMP::hex_to_string($Array['name'])."'
                                AND `descr`='".$Array['description']."'
                                AND `serialnumber`='".$Array['serial']."';";
            $result_sel = $DB->query($query_sel);
            if ($DB->numrows($result_sel) > 0) {
               $insert = 0;
            }
         }
         if ($insert == "1") {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_discovery`
                                  (`date`, `ip`, `name`, `descr`, `serialnumber`, `type`,
                                   `plugin_fusioninventory_agents_id`, `entities_id`, `plugin_fusinvsnmp_models_id`,
                                   `plugin_fusinvsnmp_configsecurities_id`)
                      VALUES('".$Array['date']."',
                             '".$Array['ip']."',
                             '".PluginFusinvsnmpSNMP::hex_to_string($Array['name'])."',
                             '".$Array['description']."',
                             '".$Array['serial']."',
                             '".$Array['type']."',
                             '".$Array['agent_id']."',
                             '".$Array['entity']."',
                             '".$Array['plugin_fusinvsnmp_models_id']."',
                             '".$Array['storagesnmpauth']."');";
            $DB->query($query);
         }
		}      
   }



   static function criteria($p_criteria, $type=0) {
      global $DB;

      $ptc = new PluginFusioninventoryConfig;

      $a_criteria = array();

      $CountCriteria1 = 0;
      $CountCriteria2 = 0;
      $arrayc = array();
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
                     $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
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
               $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
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
                  $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
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
                     $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
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
               $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
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
                  $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
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


   
   static function find_device($a_criteria, $p_type=0) {
      global $DB,$CFG_GLPI;

      $ci = new commonitem;

      $a_types = array(COMPUTER_TYPE, NETWORKING_TYPE, PRINTER_TYPE, PERIPHERAL_TYPE,
                  PHONE_TYPE, 'PluginFusioninventoryUnknownDevice');
      if ($p_type != '0') {
         $a_types = array($p_type);
      }
      $condition = "";
      $select = "";
      $condition_unknown = "";
      $select_unknown = "";
      foreach ($a_criteria as $criteria=>$value) {
         switch ($criteria) {

            case 'ip':
               $condition .= "AND `ip`='".$value."' ";
               $select .= ", ip";
               $condition_unknown .= "AND `glpi_networkports`.`ip`='".$value."' ";
               $select_unknown .= ", `glpi_networkports`.`ip`";
               break;

            case 'macaddr':
               $condition .= "AND `mac`='".$value."' ";
               $select .= ", mac";
               $condition_unknown .= "AND `glpi_networkports`.`mac`='".$value."' ";
               $select_unknown .= ", `glpi_networkports`.`mac`";
               break;

            case 'name':
               $condition .= "AND `name`='".$value."' ";
               $select .= ", name";
               $condition_unknown .= "AND `name`='".$value."' ";
               $select_unknown .= ", name";
               break;

            case 'serial':
               $condition .= "AND `serial`='".$value."' ";
               $select .= ", serial";
               $condition_unknown .= "AND `serial`='".$value."' ";
               $select_unknown .= ", serial";
               break;
         }
      }

      foreach ($a_types as $type) {
         $ci->setType($type,true);
         $query = "";
         if ($type == 'PluginFusioninventoryUnknownDevice') {
            $query = "SELECT ".$ci->obj->getTable().".id ".$select_unknown." FROM ".$ci->obj->getTable();
         } else {
            $query = "SELECT ".$ci->obj->getTable().".id ".$select." FROM ".$ci->obj->getTable();
         }
         if ($ci->obj->getTable() != "glpi_networkequipments") {
            $query .= " LEFT JOIN glpi_networkports on items_id=".$ci->obj->getTable().".id AND itemtype=".$type;
         }
         if ($type == 'PluginFusioninventoryUnknownDevice') {
            $query .= " WHERE is_deleted=0 ".$condition_unknown;
         } else {
            $query .= " WHERE is_deleted=0 ".$condition;
         }
         $result = $DB->query($query);
         if($DB->numrows($result) > 0) {
            $data = $DB->fetch_assoc($result);
            if ($p_type == '0') {
               return $data['id'].'||'.$type;
            } else {
               return $data['id'];
            }
         }
      }

      // Search in 'PluginFusioninventoryUnknownDevice' when ip in not empty (so when it's a switch)
      $ci->setType('PluginFusioninventoryUnknownDevice',true);
      $query = "SELECT ".$ci->obj->getTable().".id ".$select." FROM ".$ci->obj->getTable();
      $query .= " WHERE is_deleted=0 ".$condition;
      $result = $DB->query($query);
      if($DB->numrows($result) > 0) {
         $data = $DB->fetch_assoc($result);
         if ($p_type == '0') {
            return $data['id'].'||'.$type;
         } else {
            return $data['id'];
         }
      }
      return false;
   }
}

?>