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
   die("Sorry. You can't access this file directly");
}

class PluginFusinvsnmpStaticmisc {

   static function task_methods() {

      $a_tasks = array();

      $a_tasks[] = array('module'         => 'fusioninventory',
                         'method'         => 'networkdiscovery',
                         'name'           => __('Network discovery'));


      $a_tasks[] = array('module'         => 'fusioninventory',
                         'method'         => 'networkinventory',
                         'name'           => __('Network inventory (SNMP)'));


      return $a_tasks;
   }



   // *** NETDISCOVERY ***
   static function task_definitiontype_netdiscovery($a_itemtype) {

      $a_itemtype['PluginFusioninventoryIPRange'] = __('IP Ranges');


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

      $a_itemtype['PluginFusioninventoryIPRange'] = __('IP Ranges');

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

      $array = array();
      $array["-.1"] = __('Auto managenement dynamic of agents');

      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $pfAgentmodule->getAgentsCanDo('NETDISCOVERY');
      foreach ($array1 as $id => $data) {
         $array["PluginFusioninventoryAgent-".$id] = __('Auto managenement dynamic of agents')." - ".$data['name'];
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

         // __('Auto managenement dynamic of agents')


      }

      return $selection_type;
   }



   static function displayMenu() {
      global $CFG_GLPI;

      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r")) {
         $a_menu[0]['name'] = __('SNMP models');

         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_models.png";
         $a_menu[0]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/model.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity", "r")) {
         $a_menu[1]['name'] = __('SNMP authentication');

         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_authentification.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/configsecurity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[5]['name'] = __('Discovery status');

         $a_menu[5]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_discovery_status.png";
         $a_menu[5]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/statediscovery.php";

         $a_menu[6]['name'] = __('Network inventory status');

         $a_menu[6]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/menu_inventory_status.png";
         $a_menu[6]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/stateinventory.php";
      }

      return $a_menu;
   }



   static function profiles() {

      $a_profil = array();
      $a_profil[] = array('profil'  => 'configuration',
                          'name'    => __('Configuration'));

      $a_profil[] = array('profil'  => 'configsecurity',
                          'name'    => __('SNMP authentication'));

      //$a_profil[] = array('profil'  => 'iprange',
      //                    'name'    => __('IP Range'));

      $a_profil[] = array('profil'  => 'networkequipment',
                          'name'    => __('Network equipment SNMP'));

      $a_profil[] = array('profil'  => 'printer',
                          'name'    => __('Printer SNMP'));

      $a_profil[] = array('profil'  => 'model',
                          'name'    => __('SNMP model'));

      $a_profil[] = array('profil'  => 'reportprinter',
                          'name'    => __('Printers report'));

      $a_profil[] = array('profil'  => 'reportnetworkequipment',
                          'name'    => __('Network report'));

      return $a_profil;
   }
}
?>