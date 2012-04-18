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


class PluginFusioninventoryWizard {

   /**
    * Display breadcrumb
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   function filAriane($ariane) {
      global $LANG,$CFG_GLPI;

      $a_list = array();
      if (method_exists("PluginFusioninventoryWizard", $ariane)) {
         $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();
         $a_list = $pluginFusioninventoryWizard->$ariane();
      } else {
         return;
      }

      if (count($a_list) == '0') {
         return;
      }
      echo "<table class='tab_cadre' width='250'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "<strong>".$LANG['plugin_fusioninventory']['wizard'][11]."</strong>";
      echo "</th>";
      echo "</tr>";
      foreach ($a_list as $name=>$link) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         if ($link == $_GET['wizz']) {
            echo "<img src='".$CFG_GLPI['root_doc']."/pics/right.png'/>";
         } else {
            echo "<img src='".$CFG_GLPI['root_doc']."/pics/right_off.png'/>";
         }
         $getariane = "&ariane=".$ariane;
         if ($link == "w_start") {
            $getariane = "";
         }
         echo " <a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/wizard.php?wizz=".
            $link.$getariane."'>".$name."</a>";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }



   /**
    * Display big button to select what path of wizard
    *
    * @param $a_buttons array with data of each button (name, pic, wizard name, breadcrumb)
    * @param $filariane value current breadcrumb name
    *
    * @return Nothing (display)
    **/
   static function displayButtons($a_buttons, $filariane) {
      global $CFG_GLPI;

      $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();

      echo "<style type='text/css'>
      .bgout {
         background-image: url(".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/wizard_button.png);
      }
      .bgover {
         background-image: url(".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/wizard_button_active.png);
      }
      </style>";
      echo "<center><table width='950'>";
      echo "<tr>";
      echo "<td rowspan='2' align='center'>";
         echo "<table cellspacing='10'>";
         echo "<tr>";
         foreach ($a_buttons as $array) {
            $getariane = '';
            if (isset($array[3]) AND $array[3] != '') {
               $getariane = '&ariane='.$array[3];
            }
            echo "<td class='bgout'
               onmouseover='this.className=\"bgover\"' onmouseout='this.className=\"bgout\"'
               width='240' height='155' align='center'>
               <a href='".$CFG_GLPI['root_doc']
               ."/plugins/fusioninventory/front/wizard.php?wizz=".$array[1].$getariane."'>";
            echo "<strong>".$array[0]."</strong><br/><br/>";
            if ($array[2] != '') {
               echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/".$array[2]."'/>";
            }
            echo "</a></td>";
         }
         echo "</tr>";
         echo "</table>";
      echo "</td>";
      echo "<td height='8'></td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td valign='top'>";
      $pluginFusioninventoryWizard->filAriane($filariane);
      echo "</td>";
      echo "</tr>";

      echo "</table></center>";
   }



   /**
    * Display form of wizzard (showform + breadcrumb + button "next")
    *
    * @param $filariane value current breadcrumb name
    * @param $classname value name of the class (itemtype)
    * @param $options array
    *
    * @return Nothing (display)
    **/
   static function displayShowForm($filariane, $classname, $options = array()) {
      global $LANG,$CFG_GLPI;

      $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();

      echo "<style type='text/css'>
      .bgout {
         background-image: url(".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/wizard_button.png);
      }
      .bgover {
         background-image: url(".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/wizard_button_active.png);
      }
      </style>";
      echo "<center><table width='950'>";
      echo "<tr>";
      echo "<td colspan='2' valign='top' width='950'>";
      
      if (isset($_GET['wizz']) AND (strstr($_GET['wizz'], "rules"))) {
         if (isset($_GET['id'])) {
            include (GLPI_ROOT."/plugins/fusioninventory/front/wizzrule.common.form.php");
         } else {
            self::addButton();
            $rulecollection = new $classname();
            include (GLPI_ROOT."/plugins/fusioninventory/front/wizzrule.common.php");
         }
      } else if (isset($_GET['id'])) {
         $class = new $classname;
         if ($_GET['wizz'] == 'w_tasks') {
            initNavigateListItems($classname);
            $class->showQuickForm($_GET['id'], $options['arg1']);
         } else {
            if (!isset($_GET['id'])) {
               $_GET['id'] = '';
            } else if ($_GET['id'] == '0') {
               $_GET['id'] = '';
            }
            $class->showForm($_GET['id']);
         }

      } else if (!empty($options)) {
         if (!isset($options['noadditem'])) {
            self::addButton();
         }
         call_user_func(array($classname, $options['f']), $options['arg1']);

      } else {
         self::addButton();
         Search::manageGetValues($classname);
         Search::showList($classname, $_GET);
      }

      echo "</td>";
      echo "<td valign='top' style='background-color: #e1cc7b;'>";
      $pluginFusioninventoryWizard->filAriane($filariane);
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td width='475' align='left' style='background-color: #e1cc7b;' height='30'>";
      echo "&nbsp;<input class='submit' type='submit' name='previous' value='".$LANG['buttons'][12]."'
               onclick='window.location.href=\"".$CFG_GLPI['root_doc'].
         "/plugins/fusioninventory/front/wizard.php?wizz=".PluginFusioninventoryWizard::getPreviousStep($filariane)."\"'/>";
      echo "</td>";
      echo "<td align='right' style='background-color: #e1cc7b;' height='30'>";
      if (isset($options['finish'])) {
         echo "<input class='submit' type='submit' name='next' value='".$LANG['plugin_fusioninventory']['buttons'][0]."'
               onclick='window.location.href=\"".$CFG_GLPI['root_doc']."/plugins/fusioninventory/\"'/>";

      } else {
         echo "<input class='submit' type='submit' name='next' value='".$LANG['buttons'][11]."'
               onclick='window.location.href=\"".$CFG_GLPI['root_doc'].
         "/plugins/fusioninventory/front/wizard.php?wizz=".PluginFusioninventoryWizard::getNextStep($filariane)."\"'/>";
      }
      echo "</form>&nbsp;&nbsp;";
      echo "</td>";
      echo "<td style='background-color: #e1cc7b;'></td>";
      echo "</tr>";

      echo "</table></center>";
   }



   /**
    * Get next page name wizard with help of breadcrumb
    *
    * @param $ariane value current breadcrumb name
    *
    * @return nothing or wiz name + breadcrumb value for url
    **/
   static function getNextStep($ariane) {
      if (method_exists("PluginFusioninventoryWizard", $ariane)) {
         $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();
         $a_list = $pluginFusioninventoryWizard->$ariane();

         $find = 0;
         foreach ($a_list as $link) {
            if ($link == $_GET['wizz']) {
               $find = 1;
            } else {
               if ($find == '1') {
                  return $link."&ariane=".$ariane;
               }
            }
         }
      }
      return;
   }


   
   /**
    * Get previous page name wizard with help of breadcrumb
    *
    * @param $ariane value current breadcrumb name
    *
    * @return nothing or wiz name + breadcrumb value for url
    **/
   static function getPreviousStep($ariane) {
      if (method_exists("PluginFusioninventoryWizard", $ariane)) {
         $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();
         $a_list = $pluginFusioninventoryWizard->$ariane();

         $find = 0;
         $p_link = '';
         foreach ($a_list as $link) {
            if ($link == $_GET['wizz']) {
               $find = 1;
            }
            if ($find == '1') {
               return $p_link."&ariane=".$ariane;
            }
            $p_link = $link;
         }
      }
      return;
   }

   
   
  // **************************************************************//
  // ********************* Define fil ariane **********************//
  // **************************************************************//
   
   /**
    * Set breadcrumb / steps for configure computer inventory
    *
    * @return array with data of breadcrumb
    **/
   function filInventoryComputer() {
      global $LANG;

      return array(
      $LANG['plugin_fusioninventory']['wizard'][0]   => "w_start",
      $LANG['plugin_fusioninventory']['wizard'][1]   => "w_inventorychoice",
      $LANG['plugin_fusioninventory']['wizard'][2]   => "w_importcomputeroptions",
      $LANG['plugin_fusioninventory']['rules'][2]    => "w_importrules",
      $LANG['plugin_fusioninventory']['wizard'][3]   => "w_entityrules",
      $LANG['plugin_fusioninventory']['wizard'][4]   => "w_agentconfig");
   }



   /**
    * Set breadcrumb / steps for make an inventory of ESX Servers
    *
    * @return array with data of breadcrumb
    **/
   function filInventoryESX() {
      global $LANG;

      return array(
      $LANG['plugin_fusioninventory']['wizard'][0]   => "w_start",
      $LANG['plugin_fusioninventory']['wizard'][1]   => "w_inventorychoice",
      $LANG['plugin_fusioninventory']['wizard'][5]   => "w_credential",
      $LANG['plugin_fusioninventory']['wizard'][6]   => "w_remotedevices",
      $LANG['plugin_fusioninventory']['rules'][2]    => "w_importrules",
      $LANG['plugin_fusioninventory']['task'][1]     => "w_tasks",
      $LANG['plugin_fusioninventory']['wizard'][7]   => "w_tasksforcerun",
      $LANG['plugin_fusioninventory']['wizard'][8]   => "w_taskslog");
   }



   /**
    * Set breadcrumb / steps for make a SNMP inventory (switch / printers)
    *
    * @return array with data of breadcrumb
    **/
   function filInventorySNMP() {
      global $LANG;

      return array(
      $LANG['plugin_fusioninventory']['wizard'][0]   => "w_start",
      $LANG['plugin_fusioninventory']['wizard'][1]   => "w_inventorychoice",
      $LANG['plugin_fusioninventory']['iprange'][2]  => "w_iprange",
      $LANG['plugin_fusioninventory']['functionalities'][16]   => "w_authsnmp",
      $LANG['plugin_fusioninventory']['rules'][2]    => "w_importrules",
      $LANG['plugin_fusioninventory']['task'][1]     => "w_tasks",
      $LANG['plugin_fusioninventory']['wizard'][7]   => "w_tasksforcerun",
      $LANG['plugin_fusioninventory']['wizard'][8]   => "w_taskslog");
   }



   /**
    * Set first breadcrumb / steps for make a network discovery
    *
    * @return array with data of breadcrumb
    **/
   function filNetDiscovery() {
      global $LANG;

      $array = array($LANG['plugin_fusioninventory']['wizard'][0]   => "w_start");
      return array_merge($array, $this->fil_Part_NetDiscovery());
   }




   /**
    * Set breadcrumb / steps for choice between SNMP inventory and network discovery
    *
    * @return array with data of breadcrumb
    **/
   function filInventorySNMP_Netdiscovery() {
      global $LANG;

      $array = array(
      $LANG['plugin_fusioninventory']['wizard'][0]   => "w_start",
      $LANG['plugin_fusioninventory']['wizard'][1]   => "w_snmpdeviceschoice",
      $LANG['plugin_fusioninventory']['wizard'][10]  => "");
      return array_merge($array, $this->fil_Part_NetDiscovery());
  }



   /**
    * Set big part of breadcrumb / steps for make a network discovery
    *
    * @return array with data of breadcrumb
    **/
   function fil_Part_NetDiscovery() {
      global $LANG;

      return array(
      $LANG['plugin_fusioninventory']['iprange'][2]  => "w_iprange",
      $LANG['plugin_fusioninventory']['functionalities'][16]   => "w_authsnmp",
      $LANG['plugin_fusioninventory']['rules'][2]    => "w_importrules",
      $LANG['plugin_fusioninventory']['task'][1]     => "w_tasks",
      $LANG['plugin_fusioninventory']['wizard'][7]   => "w_tasksforcerun",
      $LANG['plugin_fusioninventory']['wizard'][8]   => "w_taskslog");
   }

   
   
  // **************************************************************//
  // ********************* All wizard display **********************//
  // **************************************************************//
   
   /**
    * First panel of wizard with choice
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_start($ariane='') {
      global $LANG;

      $plugin = new Plugin();
      
      $a_buttons = array();
      if ($plugin->isInstalled('fusinvsnmp')
         && $plugin->isActivated('fusinvsnmp')) {
         
         $a_buttons[] = array($LANG['plugin_fusioninventory']['wizard'][12],
                               'w_iprange',
                               'networkscan.png',
                               'filNetDiscovery');         
      }

      $a_buttons[] = array($LANG['plugin_fusioninventory']['wizard'][13],
                                'w_inventorychoice',
                                'general_inventory.png',
                                '');

      echo "<center>".$LANG['plugin_fusioninventory']['wizard'][14]."</center><br/>";

      PluginFusioninventoryWizard::displayButtons($a_buttons, $ariane);
   }



   /**
    * Panel of wizard for inventory choice
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_inventorychoice($ariane='') {
      global $LANG;

      $plugin = new Plugin();
      
      $a_buttons = array();
      if ($plugin->isInstalled('fusinvinventory')
         && $plugin->isActivated('fusinvinventory')) {
         
         $a_buttons[] = array($LANG['plugin_fusioninventory']['wizard'][15],
                               'w_importcomputeroptions',
                               '',
                               'filInventoryComputer');
      }
      
      $a_buttons[] = array($LANG['plugin_fusioninventory']['wizard'][16],
                            'w_credential',
                            '',
                            'filInventoryESX');
     
      if ($plugin->isInstalled('fusinvsnmp')
         && $plugin->isActivated('fusinvsnmp')) {
         
         $a_buttons[] = array($LANG['plugin_fusioninventory']['wizard'][17],
                                'w_iprange',
                                'general_inventory.png',
                                'filInventorySNMP');
      }

      PluginFusioninventoryWizard::displayButtons($a_buttons, $ariane);
   }
   


   /**
    * Manage SNMP authentication
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_authsnmp($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusinvsnmpConfigSecurity");
   }

   
   
   /**
    * Manage ip ranges
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_iprange($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusioninventoryIprange");
   }
   


   /**
    * Manage devices import rules
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_importrules($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusioninventoryRuleImportEquipmentCollection");
   }
   
   
   
   /**
    * Manage entity rules for computers
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_entityrules($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusinvinventoryRuleEntityCollection");
   }

   

   /**
    * Manage credential for ESX servers
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_credential($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusioninventoryCredential");
   }



   /**
    * Manage ESX servers informations
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_remotedevices($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusioninventoryCredentialIp");
   }



   /**
    * Manage taskjobs
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_tasks($ariane='') {
      unset($_SESSION["plugin_fusioninventory_forcerun"]);
      if (!isset($_GET['sort'])) {
         $_GET['sort'] = 6;
         $_GET['order'] = 'DESC';
      }
      $_GET['target']="task.php";

      $func = self::getMethod($ariane);

      PluginFusioninventoryWizard::displayShowForm($ariane,
               "PluginFusioninventoryTaskjob",
               array("f"=>'quickList',
                     "arg1"=>$func));
   }



   /**
    * Manage force running taskjobs
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_tasksforcerun($ariane='') {
      if (isset($_SESSION["plugin_fusioninventory_forcerun"])) {
         glpi_header($_SERVER["PHP_SELF"]."?wizz=".PluginFusioninventoryWizard::getNextStep($ariane));
         exit;
      }

      if (!isset($_GET['sort'])) {
         $_GET['sort'] = 6;
         $_GET['order'] = 'DESC';
      }
      $_GET['target']="task.php";

      $func = self::getMethod($ariane);

      PluginFusioninventoryWizard::displayShowForm($ariane,
               "PluginFusioninventoryTaskjob",
               array("f"=>'listToForcerun',
                     "arg1"=>$func,
                     "noadditem"=>1));
      
   }



   /**
    * Manage taskjobs logs/history
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_taskslog($ariane='') {
      if (!isset($_GET['sort'])) {
         $_GET['sort'] = 6;
         $_GET['order'] = 'DESC';
      }
      $_GET['target']="task.php";

      PluginFusioninventoryWizard::displayShowForm($ariane,
               "PluginFusioninventoryTaskjob",
               array("f"=>'quickListLogs',
                     "arg1"=>'',
                     "noadditem"=>1,
                     "finish"=>1));

   }

   
   
   /**
    * Computer options
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_importcomputeroptions($ariane='') {
      global $CFG_GLPI;
      
      PluginFusioninventoryWizard::displayShowForm($ariane, 
              "PluginFusinvinventoryConfig",
              array('f'=>'showForm',
                    'arg1'=>array('target'=> $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/configuration.form.php"
              ),
              'noadditem'=>1));
      
   }

   
   

   /**
    * Manage configuration of agents
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return Nothing (display)
    **/
   static function w_agentconfig($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, 
               "PluginFusioninventoryAgent",
               array("f"=>'showConfig',
                     "arg1"=>'',
                     "noadditem"=>1,
                     "finish"=>1));
   }

   

   /**
    * Get task method for current breadcrumb
    *
    * @param $ariane value name of current breadcrumb
    *
    * @return method name
    **/
   static function getMethod($ariane) {
      $method = '';
      switch ($ariane) {

         case 'filNetDiscovery':
            $method = 'netdiscovery';
            break;

         case 'filInventorySNMP':
            $method = 'snmpinventory';
            break;

         case 'filInventoryESX':
            $method = 'ESX';
            break;

      }
      return $method;
   }
   
   
   
   static function addButton() {
      global $LANG;
      
      echo "<table class='tab_cadre'>";
      echo "<tr>";
      echo "<th>";
      echo "<a href='".$_SERVER["REQUEST_URI"]."&id=0'>".ucfirst($LANG['log'][98])."</a>";
      echo "</th>";
      echo "</tr>";
      echo "</table>";
   }
}

?>