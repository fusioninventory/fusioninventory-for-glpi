<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org//
   ----------------------------------------------------------------------

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
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpStaticmisc {
   static function task_methods() {
      global $LANG;

      $a_tasks = array();

      $a_tasks[] = array('module'         => 'fusinvsnmp',
                         'method'         => 'netdiscovery',
                         'name'           => $LANG['plugin_fusinvsnmp']['config'][4]);

      $a_tasks[] = array('module'         => 'fusinvsnmp',
                         'method'         => 'snmpinventory',
                         'name'           => $LANG['plugin_fusinvsnmp']['config'][3]);

      return $a_tasks;
   }


   // *** NETDISCOVERY ***

   static function task_definitiontype_netdiscovery($a_itemtype) {
      global $LANG;

      $a_itemtype['PluginFusinvsnmpIPRange'] = $LANG['plugin_fusinvsnmp']['iprange'][2];

      return $a_itemtype;
   }


   static function task_definitionselection_PluginFusinvsnmpIPRange_netdiscovery($title) {
      global $LANG;

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("PluginFusinvsnmpIPRange", $options);
      return $rand;
   }

   

   // *** SNMPINVENTORY ***

   static function task_definitiontype_snmpinventory($a_itemtype) {
      global $LANG;

      $a_itemtype['PluginFusinvsnmpIPRange'] = $LANG['plugin_fusinvsnmp']['iprange'][2];
      $a_itemtype['NetworkEquipment'] = NetworkEquipment::getTypeName();
      $a_itemtype['Printer'] = Printer::getTypeName();

      return $a_itemtype;
   }


   static function task_definitionselection_PluginFusinvsnmpIPRange_snmpinventory($title) {
      $rand = PluginFusinvsnmpStaticmisc::task_definitionselection_PluginFusinvsnmpIPRange_netdiscovery($title);
      return $rand;
   }


   static function task_definitionselection_NetworkEquipment_snmpinventory($title) {
      global $LANG;

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("NetworkEquipment", $options);
      return $rand;
   }


   static function task_definitionselection_Printer_snmpinventory($title) {
      global $LANG;

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("Printer", $options);
      return $rand;
   }







   // ===============


   static function task_netdiscovery_agents() {
      global $LANG;

      $array = array();
      $array["-.1"] = $LANG['plugin_fusioninventory']['agents'][32];
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $PluginFusioninventoryAgentmodule->getAgentsCanDo('NETDISCOVERY');
      foreach ($array1 as $id => $data) {
         $array["PluginFusioninventoryAgent-".$id] = $LANG['plugin_fusioninventory']['agents'][32]." - ".$data['name'];
      }
      return $array;
   }

   # Actions with itemtype autorized
   static function task_action_snmpinventory() {
      $a_itemtype = array();
      $a_itemtype[] = PRINTER_TYPE;
      $a_itemtype[] = NETWORKING_TYPE;
      $a_itemtype[] = 'PluginFusinvsnmpIPRange';

      return $a_itemtype;
   }




   # Selection type for actions
   static function task_selection_type_snmpinventory($itemtype) {
      switch ($itemtype) {

         case 'PluginFusinvsnmpIPRange':
            $selection_type = 'iprange';
            break;

         case PRINTER_TYPE;
         case NETWORKING_TYPE;
            $selection_type = 'devices';
            break;

      }

      return $selection_type;
   }





   static function task_selection_type_netdiscovery($itemtype) {
      echo $itemtype;
      switch ($itemtype) {

         case 'PluginFusinvsnmpIPRange':
            $selection_type = 'iprange';
            break;

         // $LANG['plugin_fusioninventory']['agents'][32]

      }

      return $selection_type;
   }

   static function displayMenu() {
      global $LANG;

      $a_menu = array();
      //if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusinvsnmp']['model_info'][4];
         $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_models.png";
         $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/model.php";
      //}

      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity", "r")) {
         $a_menu[1]['name'] = $LANG['plugin_fusinvsnmp']['model_info'][3];
         $a_menu[1]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_authentification.png";
         $a_menu[1]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/configsecurity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "iprange", "r")) {
         $a_menu[2]['name'] = $LANG['plugin_fusinvsnmp']['menu'][2];
         $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_rangeip.png";
         $a_menu[2]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/iprange.php";
      }

      $a_menu[5]['name'] = $LANG['plugin_fusinvsnmp']['menu'][9];
      $a_menu[5]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_discovery_status.png";
      $a_menu[5]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/statediscovery.php";

      $a_menu[6]['name'] = $LANG['plugin_fusinvsnmp']['menu'][10];
      $a_menu[6]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_inventory_status.png";
      $a_menu[6]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/stateinventory.php";

      return $a_menu;
   }


   static function profiles() {
      global $LANG;

      $a_profil = array();
      $a_profil[] = array('profil'  => 'configuration',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][2]);
      $a_profil[] = array('profil'  => 'configsecurity',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][3]);
      $a_profil[] = array('profil'  => 'iprange',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][4]);
      $a_profil[] = array('profil'  => 'networkequipment',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][5]);
      $a_profil[] = array('profil'  => 'printer',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][6]);
      $a_profil[] = array('profil'  => 'model',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][7]);
      $a_profil[] = array('profil'  => 'reportprinter',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][8]);
      $a_profil[] = array('profil'  => 'reportnetworkequipment',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][9]);
      return $a_profil;
   }
   
}
?>