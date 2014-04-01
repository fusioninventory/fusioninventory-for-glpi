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


   static $rightname = 'plugin_fusioninventory_menu';



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
   
   static function getMenuName() {
      return self::getTypeName();
   }

   static function getMenuContent() {
      global $CFG_GLPI;
      $menu          = array();
      if (self::canView()) {
      
         // * Fil ariane
         $menu= array();
         $menu['title'] = __('Menu', 'fusioninventory');
         $menu['page']  = '/plugins/fusioninventory/front/wizard.php';

         $menu['options']['tasks']['title'] = __('Task management', 'fusioninventory');
         $menu['options']['tasks']['page']  = '/plugins/fusioninventory/front/task.php';

         $menu['options']['taskjob']['title'] = __('Running jobs', 'fusioninventory');
         $menu['options']['taskjob']['page']  = '/plugins/fusioninventory/front/taskjob.php';

         $menu['options']['agents']['title'] = __('Agents management', 'fusioninventory');
         $menu['options']['agents']['page']  = '/plugins/fusioninventory/front/agent.php';

         $menu['options']['configuration']['title'] = __('General setup');
         $menu['options']['configuration']['page']  = '/plugins/fusioninventory/front/config.form.php';

         $menu['options']['unknown']['title'] = __('Unknown device', 'fusioninventory');
         $menu['options']['unknown']['page']  = '/plugins/fusioninventory/front/unknowndevice.php';

         $menu['options']['inventoryruleimport']['title'] = __('Equipment import and link rules', 'fusioninventory');
         $menu['options']['inventoryruleimport']['page']  = '/plugins/fusioninventory/front/inventoryruleimport.php';

         $menu['options']['wizard-start']['title'] = __('Wizard', 'fusioninventory');
         $menu['options']['wizard-start']['page']  = '/plugins/fusioninventory/front/wizard.php';

         $menu['options']['iprange']['title'] = __('IP range configuration', 'fusioninventory');
         $menu['options']['iprange']['page']  = '/plugins/fusioninventory/front/iprange.php';

         $menu['options']['packages']['title'] = __('Packages', 'fusioninventory');
         $menu['options']['packages']['page']  = '/plugins/fusioninventory/front/deploypackage.php';

         $menu['options']['group']['title'] = __('Groups of computers', 'fusioninventory');
         $menu['options']['group']['page']  = '/plugins/fusioninventory/front/deploygroup.php';

         $menu['options']['ignoredimportrules']['title'] = __('Equipment ignored on import', 'fusioninventory');
         $menu['options']['ignoredimportrules']['page']  = '/plugins/fusioninventory/front/ignoredimportdevice.php';

         $menu['options']['blacklist']['title'] = __('BlackList');
         $menu['options']['blacklist']['page']  = '/plugins/fusioninventory/front/inventorycomputerblacklist.php';

         $menu['options']['ruleentity']['title'] = __('Entity rules', 'fusioninventory');
         $menu['options']['ruleentity']['page']  = '/plugins/fusioninventory/front/inventoryruleentity.php';

         $menu['options']['rulelocation']['title'] = __('Location rules', 'fusioninventory');
         $menu['options']['rulelocation']['page']  = '/plugins/fusioninventory/front/inventoryrulelocation.php';

         $menu['options']['importxmlfile']['title'] = __('Import agent XML file', 'fusioninventory');
         $menu['options']['importxmlfile']['page']  = '/plugins/fusioninventory/front/inventorycomputerimportxml.php';

         $menu['options']['models']['title'] = __('SNMP models');
         $menu['options']['models']['page']  = '/plugins/fusioninventory/front/snmpmodel.php';

         $menu['options']['configsecurity']['title'] = __('SNMP authentication');
         $menu['options']['configsecurity']['page']  = '/plugins/fusioninventory/front/configsecurity.php';

         $menu['options']['statediscovery']['title'] = __('Discovery status', 'fusioninventory');
         $menu['options']['statediscovery']['page']  = '/plugins/fusioninventory/front/statediscovery.php';

         $menu['options']['stateinventory']['title'] = __('Inventory status', 'fusioninventory');
         $menu['options']['stateinventory']['page']  = '/plugins/fusioninventory/front/stateinventory.php';

         $menu['options']['mirror']['title'] = __('Mirror servers', 'fusioninventory');
         $menu['options']['mirror']['page']  = '/plugins/fusioninventory/front/deploymirror.php';
      
         $menu['title']           = self::getTypeName();
         $menu['page']            = self::getSearchURL(false);
         $menu['links']['search'] = self::getSearchURL(false);
      }
      return $menu;
   }
/*
   static function canView() {
      if ((static::$rightname) && (Session::haveRight(static::$rightname, READ))) {
         return TRUE;
      }
      return FALSE;
   }
*/


   static function getAdditionalMenuOptions() {

      $elements = array(
          'iprange'                    => 'PluginFusioninventoryIPRange',
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
          'deploygroup'                => 'PluginFusioninventoryDeployGroup'
      );
      $options = array();
      foreach ($elements as $type => $itemtype) {
         $options[$type] = array(
              'title' => $itemtype::getTypeName(),
              'page'  => $itemtype::getSearchURL(false),
              'links' => array(
                  'search' => Toolbox::getItemTypeSearchURL($type),
                  'add'    => Toolbox::getItemTypeFormURL($type)
              ));
      }
      $options['agent'] = array(
           'title' => PluginFusioninventoryAgent::getTypeName(),
           'page'  => PluginFusioninventoryAgent::getSearchURL(false),
           'links' => array(
               'search' => Toolbox::getItemTypeSearchURL('PluginFusioninventoryAgent')
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
      
      if (Session::haveRight('config', UPDATE)) {
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

      if (Session::haveRight('rule_import', READ)) {
         $a_menu[1]['name'] = __('Equipment import and link rules', 'fusioninventory');
         $a_menu[1]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[1]['link'] = Toolbox::getItemTypeSearchURL(
                    'PluginFusioninventoryInventoryRuleImport'
                 );
      }

      if (Session::haveRight('rule_import', READ)) {
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

         $a_menu[4]['name'] = __('Computer location rules', 'fusioninventory');
         $a_menu[4]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[4]['link'] = $CFG_GLPI['root_doc'].
                                 "/plugins/fusioninventory/front/inventoryrulelocation.php";
      }

      if (Session::haveRight("plugin_fusioninventory_existantrule", READ)) {
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
}

?>
