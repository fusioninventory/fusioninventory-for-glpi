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


class PluginFusioninventoryMenu extends CommonGLPI {

   /**
    * Name of the type
    *
    * @param $nb  integer  number of item in the type (default 0)
   **/
   static function getTypeName($nb=0) {
      return 'FusionInventory';
   }

   static function canView() {
      $can_display = false;
      $profile     = new PluginFusioninventoryProfile();

      foreach ($profile->getAllRights() as $right) {
         if (Session::haveRight($right['field'], READ)) {
            $can_display = true;
            break;
         }
      }
      return $can_display;
   }

   static function canCreate() {
      return false;
   }

   static function getMenuName() {
      return self::getTypeName();
   }

   static function getAdditionalMenuOptions() {

      $elements = array(
          'iprange'                    => 'PluginFusioninventoryIPRange',
          'config'                     => 'PluginFusioninventoryConfig',
          'task'                       => 'PluginFusioninventoryTask',
          'timeslot'                   => 'PluginFusioninventoryTimeslot',
          'unknowndevice'              => 'PluginFusioninventoryUnknownDevice',
          'inventoryruleimport'        => 'PluginFusioninventoryInventoryRuleImport',
          'inventoryruleentity'        => 'PluginFusioninventoryInventoryRuleEntity',
          'inventoryrulelocation'      => 'PluginFusioninventoryInventoryRuleLocation',
          'collectrule'                => 'PluginFusioninventoryCollectRule',
          'inventorycomputerblacklist' => 'PluginFusioninventoryInventoryComputerBlacklist',
          'configsecurity'             => 'PluginFusioninventoryConfigSecurity',
          'credential'                 => 'PluginFusioninventoryCredential',
          'credentialip'               => 'PluginFusioninventoryCredentialIp',
          'collect'                    => 'PluginFusioninventoryCollect',
          'deploypackage'              => 'PluginFusioninventoryDeployPackage',
          'deploymirror'               => 'PluginFusioninventoryDeployMirror',
          'deploytask'                 => 'PluginFusioninventoryDeployTask',
          'deploygroup'                => 'PluginFusioninventoryDeployGroup',
          'ignoredimportdevice'        => 'PluginFusioninventoryIgnoredimportdevice'
      );
      $options = array();
      foreach ($elements as $type => $itemtype) {
         $options[$type] = array(
              'title' => $itemtype::getTypeName(),
              'page'  => $itemtype::getSearchURL(false));
         $options[$type]['links']['search'] = Toolbox::getItemTypeSearchURL($itemtype, false);
         if ($itemtype::canCreate()) {
            $options[$type]['links']['add'] = Toolbox::getItemTypeFormURL($itemtype, false);
         }
      }
      $options['agent'] = array(
           'title' => PluginFusioninventoryAgent::getTypeName(),
           'page'  => PluginFusioninventoryAgent::getSearchURL(false),
           'links' => array(
               'search' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryAgent', false)
           ));
      return $options;
   }



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

      $config = new PluginFusioninventoryConfig();
      if (strlen($config->getValue('agent_base_url'))<10) {
         echo "<div class='msgboxmonit msgboxmonit-red'>";
         print "<center><a href=\"config.form.php\">";
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
      if (Session::haveRight('plugin_fusioninventory_agent', READ)) {
         $a_menu[0]['name'] = __('Agents management', 'fusioninventory');
         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[0]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryAgent');
      }

      if (Session::haveRight('plugin_fusioninventory_group', READ)) {
         $a_menu[2]['name'] = __('Groups of computers', 'fusioninventory');
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_group.png";
         $a_menu[2]['link'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/deploygroup.php";
      }

      if (Session::haveRight('config', UPDATE) || Session::haveRight('plugin_fusioninventory_configuration', UPDATE)) {
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
      if(Session::haveRight('plugin_fusioninventory_task', READ)) {
         $a_menu[1]['name'] = __('Task management', 'fusioninventory')." (".__s('Summary').")";
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/tasksummary.php";

         $a_menu[2]['name'] = __('Task management', 'fusioninventory')." (".__s('Normal').")";
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[2]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryTask');

         $a_menu[3]['name'] = __('Monitoring / Logs', 'fusioninventory');
         $a_menu[3]['pic']  = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_runningjob.png";
         $a_menu[3]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryTaskJob');
      }

      if (Session::haveRight('plugin_fusioninventory_importxml', READ)) {
         $a_menu[0]['name'] = __('Import agent XML file', 'fusioninventory');
         $a_menu[0]['pic']  = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_importxml.png";
         $a_menu[0]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventorycomputerimportxml.php";
      }

      if (Session::haveRight("plugin_fusioninventory_collect", READ)) {
         $a_menu[11]['name'] = __('Additional computer information', 'fusioninventory');
         $a_menu[11]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[11]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryCollect');
      }
      if(Session::haveRight('plugin_fusioninventory_task', READ)) {
         $a_menu[12]['name'] = __('Time slot', 'fusioninventory');
         $a_menu[12]['pic']  = "";
         $a_menu[12]['link'] = Toolbox::getItemTypeSearchURL('PluginFusioninventoryTimeslot');
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

      if (Session::haveRight('plugin_fusioninventory_ruleimport', READ)) {
         $a_menu[1]['name'] = __('Equipment import and link rules', 'fusioninventory');
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[1]['link'] = Toolbox::getItemTypeSearchURL(
                    'PluginFusioninventoryInventoryRuleImport'
                 );
      }

      if (Session::haveRight('plugin_fusioninventory_ruleentity', READ)) {
         $a_menu[2]['name'] = __('Ignored import devices', 'fusioninventory');
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[2]['link'] = Toolbox::getItemTypeSearchURL(
                    'PluginFusioninventoryIgnoredimportdevice'
                 );
      }

      if (Session::haveRight('plugin_fusioninventory_ruleentity', READ)) {
         $a_menu[3]['name'] = __('Computer entity rules', 'fusioninventory');
         $a_menu[3]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[3]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventoryruleentity.php";
      }

      if (Session::haveRight('plugin_fusioninventory_rulelocation', READ)) {
         $a_menu[4]['name'] = __('Computer location rules', 'fusioninventory');
         $a_menu[4]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[4]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventoryrulelocation.php";
      }

      if (Session::haveRight("plugin_fusioninventory_rulecollect", READ)) {
         $a_menu[5]['name'] = __('Additional computer information rules', 'fusioninventory');
         $a_menu[5]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[5]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/collectrule.php";
      }


      if (Session::haveRight('plugin_fusioninventory_blacklist', READ)) {
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

      if (Session::haveRight('plugin_fusioninventory_iprange', READ)) {
         $a_menu[] = array(
            'name' => __('IP Ranges', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_rangeip.png",
            'link' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryIPRange')
         );
      }

      if (Session::haveRight('plugin_fusioninventory_credentialip', READ)) {
         $a_menu[] = array(
            'name' => __('Remote devices to inventory (VMware)', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_credentialips.png",
            'link' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryCredentialip')
         );
      }

      if (Session::haveRight('plugin_fusioninventory_configsecurity', READ)) {
         $a_menu[] = array(
            'name' => __('SNMP authentication', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_authentification.png",
            'link' => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/configsecurity.php"
         );
      }

      if (Session::haveRight('plugin_fusioninventory_credential', READ)) {
         $a_menu[] = array(
            'name' => __('Authentication for remote devices (VMware)', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_authentification.png",
            'link' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryCredential')
         );
      }

      if (Session::haveRight('plugin_fusioninventory_task', READ)) {
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

      if (Session::haveRight('plugin_fusioninventory_model', READ)) {
         $a_menu[] = array(
            'name' => __('SNMP models creation', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_constructmodel.png",
            'link' => $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/constructmodel.php"
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

      if (Session::haveRight('plugin_fusioninventory_package', READ)) {
         $a_menu[] =array(
            'name' => __('Package management', 'fusioninventory'),
            'pic'  => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/pics/menu_package.png",
            'link' => $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/deploypackage.php"
         );
      }

      if (Session::haveRight('plugin_fusioninventory_deploymirror', READ)) {
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

      /*
       * Configuration management
       */
      $a_menu = array();

      if (Session::haveRight('config', UPDATE)) {
         $nb = countElementsInTable("glpi_plugin_fusioninventory_configurationmanagements",
                                    "`conform`='0'");
         $a_menu[0]['name'] = __('Not conform', 'fusioninventory')." <sup>(".$nb.")</sup>";
         $a_menu[0]['pic']  = "";
         $a_menu[0]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/configurationmanagement_notconform.php";

         $nb = countElementsInTable("glpi_plugin_fusioninventory_configurationmanagements",
                                    "`sha_referential`='' OR `sha_referential` IS NULL");
         $a_menu[1]['name'] = __('To be validated', 'fusioninventory')." <sup>(".$nb.")</sup>";
         $a_menu[1]['pic']  = "";
         $a_menu[1]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/configurationmanagement_tobevalidated.php";

         $a_menu[2]['name'] = __('Models', 'fusioninventory');
         $a_menu[2]['pic']  = "";
         $a_menu[2]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/configurationmanagement_model.php";

      }

      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu(__('Configuration management', 'fusioninventory'),
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

      $width_max = 1250;

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



   static function board() {
      global $DB;

      // Computers
      $fusionComputers = countElementsInTable('glpi_plugin_fusioninventory_inventorycomputercomputers');
      $allComputers    = countElementsInTable('glpi_computers',
                                              "`is_deleted`='0' AND `is_template`='0'");

      $dataComputer = array();
      $dataComputer[] = array(
          'key' => 'FusionInventory computers : '.$fusionComputers,
          'y'   => $fusionComputers,
          'color' => '#3dff7d'
      );
      $dataComputer[] = array(
          'key' => 'Other computers : '.($allComputers - $fusionComputers),
          'y'   => ($allComputers - $fusionComputers),
          'color' => "#dedede"
      );


      // SNMP
      $networkequipment = countElementsInTable('glpi_plugin_fusioninventory_networkequipments');
      $printer    = countElementsInTable('glpi_plugin_fusioninventory_printers');

      $dataSNMP = array();
      $dataSNMP[] = array(
          'key' => 'NetworkEquipments (SNMP) : '.$networkequipment,
          'y'   => $networkequipment,
          'color' => '#3d94ff'
      );
      $dataSNMP[] = array(
          'key' => 'Printers (SNMP) : '.$printer,
          'y'   => $printer,
          'color' => '#3dff7d'
      );


      // switches ports
      $allSwitchesPortSNMP = countElementsInTable('glpi_plugin_fusioninventory_networkports');
      $query = "SELECT `glpi_networkports`.`id` FROM `glpi_networkports`
              LEFT JOIN `glpi_plugin_fusioninventory_networkports`
                 ON `glpi_plugin_fusioninventory_networkports`.`networkports_id` = `glpi_networkports`.`id`
              LEFT JOIN glpi_networkports_networkports
                  ON (`networkports_id_1`=`glpi_networkports`.`id`
                     OR `networkports_id_2`=`glpi_networkports`.`id`)
              WHERE `glpi_plugin_fusioninventory_networkports`.`id` IS NOT NULL
                  AND `glpi_networkports_networkports`.`id` IS NOT NULL";
      $result = $DB->query($query);
      $networkPortsLinked = $DB->numrows($result);

      $dataPortL = array();
      $dataPortL[] = array(
          'key' => 'SNMP switch network ports linked : '.$networkPortsLinked,
          'y'   => $networkPortsLinked,
          'color' => '#3dff7d'
      );
      $dataPortL[] = array(
          'key' => 'SNMP switch network ports not linked : '.($allSwitchesPortSNMP - $networkPortsLinked),
          'y'   => ($allSwitchesPortSNMP - $networkPortsLinked),
          'color' => '#dedede'
      );

      // Ports connected at last SNMP inventory
      $networkPortsConnected = countElementsInTable('glpi_plugin_fusioninventory_networkports',
                                                    "`ifstatus`='1' OR `ifstatus`='up'");
      $dataPortC = array();
      $dataPortC[] = array(
          'key' => 'Ports connected : '.$networkPortsConnected,
          'y'   => $networkPortsConnected,
          'color' => '#3dff7d'
      );
      $dataPortC[] = array(
          'key' => 'Ports not connected : '.($allSwitchesPortSNMP - $networkPortsConnected),
          'y'   => ($allSwitchesPortSNMP - $networkPortsConnected),
          'color' => '#dedede'
      );

      $dataDeploy = array();
      $dataDeploy[] = array(
          'key' => 'Deployment successfull : 400',
          'y'   => 400,
          'color' => '#3dff7d'
      );
      $dataDeploy[] = array(
          'key' => 'Deployment in error : 55',
          'y'   => 55,
          'color' => '#ff3d3d'
      );
      $dataDeploy[] = array(
          'key' => 'Deployment prepared and waiting : 568',
          'y'   => 568,
          'color' => '#feffc9'
      );

      // Number of computer inventories in last hour, 6 hours, 24 hours
      $dataInventory = PluginFusioninventoryInventoryComputerStat::getLastTwelveHours();



      echo "<table align='center'>";
      echo "<tr height='280'>";
      echo "<td width='380'>";
      self::showChart('computers', $dataComputer);
      echo "</td>";
      echo "<td width='380'>";
      $title = __('Number of computer inventories of last hours', 'fusioninventory');
      self::showChartBar('nbinventory', $dataInventory, $title);
      echo "</td>";
      echo "<td width='380'>";
      self::showChart('deploy', $dataDeploy);
      echo "</td>";
      echo "</tr>";

      echo "<tr height='280'>";
      echo "<td>";
      self::showChart('snmp', $dataSNMP);
      echo "</td>";
      echo "<td>";
      self::showChart('ports', $dataPortL);
      echo "</td>";
      echo "<td>";
      self::showChart('portsconnected', $dataPortC);
      echo "</td>";
      echo "</tr>";
      echo "</table>";

   }


   static function showChart($name, $data) {

      echo '<svg style="background-color: #f3f3f3;" id="'.$name.'"></svg>';

      echo "<script>
         statHalfDonut('".$name."', '".json_encode($data)."');
</script>";
   }


   static function showChartBar($name, $data, $title='') {
      echo '<svg style="background-color: #f3f3f3;" id="'.$name.'"></svg>';

      echo "<script>
         statBar('".$name."', '".json_encode($data)."', '".$title."');
</script>";
   }
}

?>
