<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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
         echo "<a href='http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/".
                 "wiki/Beta_test'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/beta.png'/></a>";
         echo "&nbsp;<a href='https://www.transifex.com/projects/p/FusionInventory/resource/".
                 "plugin-fusioninventory-084/'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/Translate.png'/>".
                 "</a>";
         echo "<H1>Version '".PLUGIN_FUSIONINVENTORY_REALVERSION."'</H1></center><br/>\n";
      }

      $pfEntity = new PluginFusioninventoryEntity();
      if (strlen($pfEntity->getValue('agent_base_url', 0))<10) {
         echo "<div class='msgboxmonit msgboxmonit-red'>";
         print "<center><a href=\"".$CFG_GLPI['root_doc']."/front/entity.form.php?id=0&forcetab=PluginFusioninventoryEntity$0\">";
         print __('The server needs to kown the URL the agents use to access the server. Please '.
                 'configure it in the General Configuration page.', 'fusioninventory');
         print "</a></center>";
         echo "</div>";
         exit;
      }

      $width_status = 0;

      echo "<div align='center' style='z-index: 1;position:absolute;width: 100%; margin: 0 auto;'>";
      echo "<table width='100%'>";

      echo "<tr>";
      echo "<td align='center'>";

      echo "<table>";
      echo "<tr>";
      echo "<td>";

      /*
       * General
       */
      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("agent", "r")) {
         $a_menu[0]['name'] = __('Agents management', 'fusioninventory');
         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[0]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryAgent');
      }

      if (PluginFusioninventoryProfile::haveRight("unknowndevice", "r")) {
         $a_menu[1]['name'] = __('Unknown device', 'fusioninventory');
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_unknown_device.png";
         $a_menu[1]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryUnknownDevice');
      }

      if (PluginFusioninventoryProfile::haveRight("task", "r")) {
         $a_menu[2]['name'] = __('Groups of computers', 'fusioninventory');
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_group.png";
         $a_menu[2]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/deploygroup.php";
      }

      if (Session::haveRight("config", "w")) {
         $a_menu[3]['name'] = __('General configuration', 'fusioninventory');
         $a_menu[3]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[3]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/config.form.php";
      }

      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu(__('General', 'fusioninventory'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      /*
       * Tasks
       */
      $a_menu = array();
      if(PluginFusioninventoryProfile::haveRight("task", "r")) {
         $a_menu[1]['name'] = __('Task management', 'fusioninventory')." (".__s('Summary').")";
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/tasksummary.php";
      }

      if(PluginFusioninventoryProfile::haveRight("task", "r")) {
         $a_menu[2]['name'] = __('Task management', 'fusioninventory')." (".__s('Normal').")";
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[2]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryTask');
      }

      if (PluginFusioninventoryProfile::haveRight("task", "r")) {
         $a_menu[3]['name'] = __('Running jobs', 'fusioninventory');
         $a_menu[3]['pic']  = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_runningjob.png";
         $a_menu[3]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryTaskJob');
      }

      if (PluginFusioninventoryProfile::haveRight("importxml", "r")) {
         $a_menu[0]['name'] = __('Import agent XML file', 'fusioninventory');
         $a_menu[0]['pic']  = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_importxml.png";
         $a_menu[0]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventorycomputerimportxml.php";
      }

      if (PluginFusioninventoryProfile::haveRight("collect", "r")) {
         $a_menu[11]['name'] = __('Additional computer information', 'fusioninventory');
         $a_menu[11]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[11]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryCollect');
      }

      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu(__('Tasks', 'fusioninventory'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      /*
       * Rules
       */
      $a_menu = array();

      if (Session::haveRight("rule_import", "r")) {
         $a_menu[1]['name'] = __('Equipment import and link rules', 'fusioninventory');
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[1]['link'] = Toolbox::getItemTypeSearchURL(
                    'PluginFusioninventoryInventoryRuleImport'
                 );
      }

      if (Session::haveRight("rule_import", "r")) {
         $a_menu[2]['name'] = __('Ignored import devices', 'fusioninventory');
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[2]['link'] = Toolbox::getItemTypeSearchURL(
                    'PluginFusioninventoryIgnoredimportdevice'
                 );
      }

      if (PluginFusioninventoryProfile::haveRight("existantrule", "r")) {
         $a_menu[3]['name'] = __('Computer entity rules', 'fusioninventory');
         $a_menu[3]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[3]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventoryruleentity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("existantrule", "r")) {
         $a_menu[4]['name'] = __('Computer location rules', 'fusioninventory');
         $a_menu[4]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[4]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventoryrulelocation.php";
      }

      if (PluginFusioninventoryProfile::haveRight("existantrule", "r")) {
         $a_menu[5]['name'] = __('Additional computer information rules', 'fusioninventory');
         $a_menu[5]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[5]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/collectrule.php";
      }


      if (PluginFusioninventoryProfile::haveRight("blacklist", "r")) {
         $a_menu[6]['name'] = _n('Blacklist', 'Blacklists', 1);
         $a_menu[6]['pic']  = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_blacklist.png";
         $a_menu[6]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventorycomputerblacklist.php";
      }

      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu(__('Rules', 'fusioninventory'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      /*
       * Network
       */
      $a_menu = array();

      if (PluginFusioninventoryProfile::haveRight("iprange", "r")) {
         $a_menu[] = array(
            'name' => __('IP Ranges', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_rangeip.png",
            'link' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryIPRange')
         );
      }

      if (PluginFusioninventoryProfile::haveRight("credentialip", "r")) {
         $a_menu[] = array(
            'name' => __('Remote devices to inventory (VMware)', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_credentialips.png",
            'link' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryCredentialip')
         );
      }

      if (PluginFusioninventoryProfile::haveRight("configsecurity", "r")) {
         $a_menu[] = array(
            'name' => __('SNMP authentication', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_authentification.png",
            'link' => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/configsecurity.php"
         );
      }

      if (PluginFusioninventoryProfile::haveRight("credential", "r")) {
         $a_menu[] = array(
            'name' => __('Authentication for remote devices (VMware)', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_authentification.png",
            'link' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryCredential')
         );
      }

      if (PluginFusioninventoryProfile::haveRight("task", "r")) {
         $a_menu[] = array(
            'name' => __('Discovery status', 'fusioninventory'),
            'pic'  =>   $CFG_GLPI['root_doc'].
                           "/plugins/fusioninventory/pics/menu_discovery_status.png",
            'link' =>   $CFG_GLPI['root_doc'].
                           "/plugins/fusioninventory/front/statediscovery.php"
         );

         $a_menu[] = array(
               'name' => __('Network inventory status', 'fusioninventory'),
               'pic' =>    $CFG_GLPI['root_doc'].
                              "/plugins/fusioninventory/pics/menu_inventory_status.png",
               'link' =>   $CFG_GLPI['root_doc'].
                              "/plugins/fusioninventory/front/stateinventory.php",
         );
      }

      if (PluginFusioninventoryProfile::haveRight("model", "r")) {
         $a_menu[] = array(
            'name' => __('SNMP models', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_models.png",
            'link' => $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/snmpmodel.php"
         );
      }

      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu(__('Networking', 'fusioninventory'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      /*
       * Deploy
       */
      $a_menu = array();

      if (PluginFusioninventoryProfile::haveRight("packages", "r")) {
         $a_menu[] =array(
            'name' => __('Package management', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_package.png",
            'link' => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/deploypackage.php"
         );
      }

      if(PluginFusioninventoryProfile::haveRight("task", "r")) {
         $a_menu[1]['name'] = __('Mirror servers', 'fusioninventory');
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_files.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/deploymirror.php";
      }
      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu(__('Deploy', 'fusioninventory'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
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
    *@param $menu_name value of the menu
    *@param $a_menu array menu of each module
    *@param $type value "big" or "mini"
    *@param $width_status integer width of space before and after menu position
    *
    *@return $width_status integer total width used by menu
    **/
   static function htmlMenu($menu_name, $a_menu = array(), $type = "big", $width_status='300') {
      global $CFG_GLPI;

      $width_max = 950;

      $width = 180;

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
         onMouseOver='document.getElementById(\"menu".$menu_name."\").style.display=\"block\"'
         onMouseOut='document.getElementById(\"menu".$menu_name."\").style.display=\"none\"'>";

      echo "<tr>";
      echo "<th colspan='".count($a_menu)."' nowrap width='".$width."'>
         <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />
         &nbsp;".str_replace("FusionInventory ", "", $menu_name)."&nbsp;
         <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />
      </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' id='menu".$menu_name."' style='display:none'>";
      echo "<td>";

      echo "<table>";
      foreach ($a_menu as $menu_id) {
         echo "<tr>";
         $menu_id['pic'] = str_replace("/menu_", "/menu_mini_", $menu_id['pic']);
         echo "<th>";
         if (!empty($menu_id['pic'])) {
            echo "<img src='".$menu_id['pic']."' width='16' height='16'/>";
         }
         echo "</th>";
         echo "<th colspan='".(count($a_menu) - 1)."' width='".($width - 40)."'>
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
