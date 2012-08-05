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

      $a_itemtype['PluginFusioninventoryIPRange'] = $LANG['plugin_fusioninventory']['iprange'][2];

      return $a_itemtype;
   }



   static function task_definitionselection_PluginFusioninventoryIPRange_netdiscovery($title) {

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("PluginFusioninventoryIPRange", $options);
      return $rand;
   }

   

   // *** SNMPINVENTORY ***
   static function task_definitiontype_snmpinventory($a_itemtype) {
      global $LANG;

      $a_itemtype['PluginFusioninventoryIPRange'] = $LANG['plugin_fusioninventory']['iprange'][2];
      $a_itemtype['NetworkEquipment'] = NetworkEquipment::getTypeName();
      $a_itemtype['Printer'] = Printer::getTypeName();

      return $a_itemtype;
   }



   static function task_definitionselection_PluginFusioninventoryIPRange_snmpinventory($title) {
      $rand = PluginFusinvsnmpStaticmisc::task_definitionselection_PluginFusioninventoryIPRange_netdiscovery($title);
      return $rand;
   }



   static function task_definitionselection_NetworkEquipment_snmpinventory($title) {

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("NetworkEquipment", $options);
      return $rand;
   }



   static function task_definitionselection_Printer_snmpinventory($title) {

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
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $pfAgentmodule->getAgentsCanDo('NETDISCOVERY');
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
      $a_itemtype[] = 'PluginFusioninventoryIPRange';

      return $a_itemtype;
   }



   # Selection type for actions
   static function task_selection_type_snmpinventory($itemtype) {
      $selection_type = '';
      switch ($itemtype) {

         case 'PluginFusioninventoryIPRange':
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
      $selection_type = '';
      switch ($itemtype) {

         case 'PluginFusioninventoryIPRange':
            $selection_type = 'iprange';
            break;

         // $LANG['plugin_fusioninventory']['agents'][32]

      }

      return $selection_type;
   }
   
   

   static function displayMenu() {
      global $LANG,$CFG_GLPI;

      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusinvsnmp']['model_info'][4];
         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_models.png";
         $a_menu[0]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/model.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity", "r")) {
         $a_menu[1]['name'] = $LANG['plugin_fusinvsnmp']['model_info'][3];
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_authentification.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/configsecurity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[5]['name'] = $LANG['plugin_fusinvsnmp']['menu'][9];
         $a_menu[5]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_discovery_status.png";
         $a_menu[5]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/statediscovery.php";

         $a_menu[6]['name'] = $LANG['plugin_fusinvsnmp']['menu'][10];
         $a_menu[6]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_inventory_status.png";
         $a_menu[6]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/stateinventory.php";
      }
      
      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r")) {
         $a_menu[7]['name'] = "SNMP models creation";
         $a_menu[7]['pic']  = "";
         $a_menu[7]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/constructmodel.php";
      }
      
      return $a_menu;
   }


   
   static function profiles() {
      global $LANG;

      $a_profil = array();
      $a_profil[] = array('profil'  => 'configuration',
                          'name'    => $LANG['plugin_fusinvsnmp']['profile'][2]);
      $a_profil[] = array('profil'  => 'configsecurity',
                          'name'    => $LANG['plugin_fusinvsnmp']['model_info'][3]);
      //$a_profil[] = array('profil'  => 'iprange',
      //                    'name'    => $LANG['plugin_fusinvsnmp']['profile'][4]);
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