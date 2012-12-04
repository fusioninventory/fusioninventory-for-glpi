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


class PluginFusioninventoryMenu {

   /**
   * Display the menu of FusionInventory
   *
   *@param type value "big" or "mini"
   *
   *@return nothing
   **/
   static function displayMenu($type = "big") {
      global $CFG_GLPI;

      if (PLUGIN_FUSIONINVENTORY_OFFICIAL_RELEASE != 1) {
         echo "<center>";
         echo "<a href='http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/wiki/Beta_test'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/beta.png'/></a>";
//         echo "&nbsp;<a href='https://www.transifex.net/projects/p/FusionInventory/resources/'>";
//         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/Translate.png'/></a>";
         echo "<H1>Version '".PLUGIN_FUSIONINVENTORY_REALVERSION."'</H1></center><br/>\n";
      }

      $config = new PluginFusioninventoryConfig();
      $plugin_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      if (strlen($config->getValue($plugin_id, 'agent_base_url', ''))<10) {
         echo "<div class='msgboxmonit msgboxmonit-red'>";
         print "<center><a href=\"config.form.php\">";
         print __('The server needs to kown the URL the agents use to access the server. Please '.
                 'configure it in the General Configuration page.', 'fusioninventory');
         print "</a></center>";
         echo "</div>";
//         exit;
      }


      $width_status = 0;

      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agent", "r")) {
         $a_menu[0]['name'] = __('Agents management', 'fusioninventory');

         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[0]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryAgent');
      }

      if(PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[1]['name'] = __('Task management', 'fusioninventory')." (".__s('Summary').")";
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/tasksummary.php";
      }

      if(PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[2]['name'] = __('Task management', 'fusioninventory')." (".__s('Normal').")";
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[2]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryTask');
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[3]['name'] = __('Running jobs', 'fusioninventory');

         $a_menu[3]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_runningjob.png";
         $a_menu[3]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryTaskJob');
      }

      if (Session::haveRight("rule_ocs","r")) {
         $a_menu[4]['name'] = __('Equipment import and link rules', 'fusioninventory');

         $a_menu[4]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";

         $a_menu[4]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryInventoryRuleImport');
      }

      if (Session::haveRight("rule_ocs","r")) {
         $a_menu[9]['name'] = __('Ignored import devices', 'fusioninventory');

         $a_menu[9]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[9]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryIgnoredimportdevice');
         $a_menu[4]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryInventoryRuleImport');
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "unknowndevice", "r")) {
         $a_menu[5]['name'] = __('Unknown device', 'fusioninventory');

         $a_menu[5]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_unknown_device.png";
         $a_menu[5]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryUnknownDevice');
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "iprange", "r")) {
         $a_menu[6]['name'] = __('IP range configuration', 'fusioninventory');

         $a_menu[6]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rangeip.png";
         $a_menu[6]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryIPRange');
      }

      if (PluginFusioninventoryCredential::hasAlLeastOneType()
            && PluginFusioninventoryProfile::haveRight("fusioninventory", "credential", "r")) {
         $a_menu[7]['name'] = __('Authentication for remote devices (VMware)', 'fusioninventory');

         $a_menu[7]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_authentification.png";
         $a_menu[7]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryCredential');

      }

      if (PluginFusioninventoryCredential::hasAlLeastOneType()
            && PluginFusioninventoryProfile::haveRight("fusioninventory", "credentialip", "r")) {
         $a_menu[8]['name'] = __('Remote devices to inventory (VMware)', 'fusioninventory');

         $a_menu[8]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_credentialips.png";
         $a_menu[8]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryCredentialip');

      }

      if (Session::haveRight("config","w")) {
         $a_menu[10]['name'] = __('General configuration', 'fusioninventory');
         $a_menu[10]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[10]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/config.form.php";

      }

      echo "<div align='center' style='z-index: 1;position:absolute;width: 100%; margin: 0 auto;'>";
      echo "<table width='100%'>";

      echo "<tr>";
      echo "<td align='center'>";

      echo "<table>";
      echo "<tr>";
      echo "<td>";
      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu("fusioninventory", $a_menu, $type,
                                                             $width_status);
      }


      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "importxml", "r")) {
         $a_menu[0]['name'] = __('Import agent XML file', 'fusioninventory');

         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_importxml.png";
         $a_menu[0]['link'] = $CFG_GLPI['root_doc']."/plugins/fusinvinventory/front/importxml.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "existantrule", "r")) {
         $a_menu[1]['name'] = __('Entity rules', 'fusioninventory');

         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/inventoryruleentity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "blacklist", "r")) {
         $a_menu[2]['name'] = __('BlackList');

         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_blacklist.png";
         $a_menu[2]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/inventorycomputerblacklist.php";
      }

      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu("Inventory", $a_menu, $type,
                                                             $width_status);
      }

      
      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "model", "r")) {
         $a_menu[0]['name'] = __('SNMP models', 'fusioninventory');

         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_models.png";
         $a_menu[0]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/snmpmodel.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configsecurity", "r")) {
         $a_menu[1]['name'] = __('SNMP authentication', 'fusioninventory');

         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_authentification.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/configsecurity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[5]['name'] = __('Discovery status', 'fusioninventory');

         $a_menu[5]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_discovery_status.png";
         $a_menu[5]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/statediscovery.php";

         $a_menu[6]['name'] = __('Network inventory status', 'fusioninventory');

         $a_menu[6]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_inventory_status.png";
         $a_menu[6]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/stateinventory.php";
      }

      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu("NetworkTools", $a_menu, $type,
                                                             $width_status);
      }
      
      
      
      
      // Get menu from plugins fusinv...
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $datas) {
         $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($datas['directory']);
         if (is_callable(array($class, "displayMenu"))) {
            $a_menu = call_user_func(array($class, "displayMenu"));
            if (!empty($a_menu)) {
               $width_status = PluginFusioninventoryMenu::htmlMenu($datas['directory'], $a_menu,
                                                                   $type, $width_status);
            }
         }
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</div><br/><br/><br/>";
   }



   /**
    * htmlMenu
    *
    *@param $plugin_name value Plugin directory (name)
    *@param $a_menu array menu of each module
    *@param $type value "big" or "mini"
    *@param $width_status integer width of space before and after menu position
    *
    *@return $width_status integer total width used by menu
    **/
   static function htmlMenu($plugin_name, $a_menu = array(), $type = "big", $width_status='300') {
      global $CFG_GLPI;

      $width_max = 950;

      $width = 0;
      $width="230";

      if (($width + $width_status) > $width_max) {
         $width_status = 0;
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "<table>";
         echo "<tr>";
         echo "<td valign='top'>";
      } else {
         echo "</td>";
         echo "<td valign='top'>";
      }
      $width_status = ($width + $width_status);

      echo "<table class='tab_cadre'
         onMouseOver='document.getElementById(\"menu".$plugin_name."\").style.display=\"block\"'
         onMouseOut='document.getElementById(\"menu".$plugin_name."\").style.display=\"none\"'>";

      echo "<tr>";
      $menu_name = __('FusionInventory', 'fusioninventory');
      echo "<th colspan='".count($a_menu)."' nowrap width='".$width."'>
         <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />
         &nbsp;".str_replace("FusionInventory ","",$menu_name)."&nbsp;
         <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />
      </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' id='menu".$plugin_name."' style='display:none'>";
      echo "<td>";

      echo "<table>";
      foreach ($a_menu as $menu_id) {
         echo "<tr>";
         $menu_id['pic'] = str_replace("/menu_","/menu_mini_",$menu_id['pic']);
         echo "<th>
               <img src='".$menu_id['pic']."' width='16' height='16'/></th>";
         echo "<th colspan='".(count($a_menu) - 1)."' width='190'>
                  <a href='".$menu_id['link']."'>".$menu_id['name']."</a></th>";
         echo "</tr>";
      }
      echo "</table>";

      echo "</td>";
      echo "</tr>";
      echo "</table>";

      return $width_status;
   }
}

?>
