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

class PluginFusioninventoryProfile extends Profile {



      /*
       * Old profile names:
       *
       *    agent
       *    remotecontrol
       *    configuration
       *    wol
       *    unknowndevice
       *    task
       *    iprange
       *    credential
       *    credentialip
       *    existantrule
       *    importxml
       *    blacklist
       *    ESX
       *    configsecurity
       *    networkequipment
       *    printer
       *    model
       *    reportprinter
       *    reportnetworkequipment
       *    packages
       *    status
       */


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->getID() > 0
              && $item->fields['interface'] == 'central') {
         return self::createTabEntry('FusionInventory');
      }
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      if ($item->getID() > 0) {
         $pfProfile = new self();
         $pfProfile->showForm($item->getID());
      }
      return TRUE;
   }



    /**
    * Show profile form
    *
    * @param $items_id integer id of the profile
    * @param $target value url of target
    *
    * @return nothing
    **/
   function showForm($profiles_id=0, $openform=TRUE, $closeform=TRUE) {

      echo "<div class='firstbloc'>";
      if (($canedit = Session::haveRightsOr(self::$rightname, array(CREATE, UPDATE, PURGE)))
          && $openform) {
         $profile = new Profile();
         echo "<form method='post' action='".$profile->getFormURL()."'>";
      }

      $profile = new Profile();
      $profile->getFromDB($profiles_id);

      $rights = $this->getRightsGeneral();
      $profile->displayRightsChoiceMatrix($rights, array('canedit'       => $canedit,
                                                      'default_class' => 'tab_bg_2',
                                                      'title'         => __('General', 'fusioninventory')));

      $rights = $this->getRightsRules();
      $profile->displayRightsChoiceMatrix($rights, array('canedit'       => $canedit,
                                                      'default_class' => 'tab_bg_2',
                                                      'title'         => _n('Rule', 'Rules', 2)));

      $rights = $this->getRightsInventory();
      $profile->displayRightsChoiceMatrix($rights, array('canedit'       => $canedit,
                                                      'default_class' => 'tab_bg_2',
                                                      'title'         => __('Inventory', 'fusioninventory')));

      $rights = $this->getRightsDeploy();
      $profile->displayRightsChoiceMatrix($rights, array('canedit'       => $canedit,
                                                      'default_class' => 'tab_bg_2',
                                                      'title'         => __('Software deployment', 'fusioninventory')));
      if ($canedit
          && $closeform) {
         echo "<div class='center'>";
         echo "<input type='hidden' name='id' value='".$profiles_id."'>";
         echo "<input type='submit' name='update' value=\""._sx('button', 'Save')."\" class='submit'>";
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";

      $this->showLegend();
   }



   /**
    * Init profiles
    *
    **/
   static function initProfile() {
      $pfProfile = new self();
      $profile = new Profile();

      $a_rights = $pfProfile->getAllRights();
      foreach ($a_rights as $data) {
         if (countElementsInTable("glpi_profilerights", "`name` = '".$data['field']."'") == 0) {
            ProfileRight::addProfileRights(array($data['field']));
            $_SESSION['glpiactiveprofile'][$data['field']] = 0;
         }
      }
      // Add all rights to current profile of the user
      if (
         isset($_SESSION['glpiactiveprofile'])
         and isset($_SESSION['glpiactiveprofile']['id'])
      ) {
         $dataprofile = array();
         $dataprofile['id'] = $_SESSION['glpiactiveprofile']['id'];
         $profile->getFromDB($_SESSION['glpiactiveprofile']['id']);
         foreach ($a_rights as $info) {
            if (is_array($info) && ((!empty($info['itemtype'])) || (!empty($info['rights'])))
                && (!empty($info['label'])) && (!empty($info['field']))) {

               if (isset($info['rights'])) {
                  $rights = $info['rights'];
               } else {
                  $rights = $profile->getRightsFor($info['itemtype']);
               }

               foreach ($rights as $right => $label) {
                  $dataprofile['_'.$info['field']][$right] = 1;
               }
            }
         }
         $profile->update($dataprofile);
      }
   }



   static function uninstallProfile() {
      $pfProfile = new self();
      $a_rights = $pfProfile->getAllRights();
      foreach ($a_rights as $data) {
         ProfileRight::deleteProfileRights(array($data['field']));
      }
   }



   function getAllRights() {
      $a_rights = array();
      $a_rights = array_merge($a_rights, $this->getRightsGeneral());
      $a_rights = array_merge($a_rights, $this->getRightsInventory());
      $a_rights = array_merge($a_rights, $this->getRightsRules());
      $a_rights = array_merge($a_rights, $this->getRightsDeploy());
      return $a_rights;
   }



   function getRightsRules() {
      $rights = array(
          array('itemtype'  => 'PluginFusioninventoryInventoryRuleImport',
                'label'     => __('Rules for import and link computers'),
                'field'     => 'plugin_fusioninventory_ruleimport'
          ),
          array('itemtype'  => 'PluginFusioninventoryInventoryRuleEntity',
                'label'     => __('Entity rules', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_ruleentity'
          ),
          array('itemtype'  => 'PluginFusioninventoryInventoryRuleImport',
                'label'     => __('Rules for import and link computers'),
                'field'     => 'plugin_fusioninventory_rulelocation'
          ),
          array('itemtype'  => 'PluginFusioninventoryInventoryComputerBlacklist',
                'label'     => __('Fields blacklist', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_blacklist'
          )
      );
      return $rights;
   }



   function getRightsDeploy() {
      $rights = array(
          array('itemtype'  => 'PluginFusioninventoryDeployPackage',
                'label'     => __('Manage packages'),
                'field'     => 'plugin_fusioninventory_package'),
          array('itemtype'  => 'PluginFusioninventoryDeployMirror',
                'label'     => __('Mirror servers', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_deploymirror'),
          /**
           * The DeployState class is not used anymore. It's just commented for references.
           * TODO: replace DeployState with the monitoring facility.
           */
          //array('itemtype'  => 'PluginFusioninventoryDeployState',
          //      'label'     => __('Deployment status'),
          //      'field'     => 'plugin_fusioninventory_status')
      );
      return $rights;
   }



   function getRightsInventory() {
      $rights = array(
          array('itemtype'  => 'PluginFusioninventoryIprange',
                'label'     => __('IP range configuration', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_iprange'),
          array('itemtype'  => 'PluginFusioninventoryCredential',
                'label'     => __('Authentication for remote devices (VMware)', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_credential'),
          array('itemtype'  => 'PluginFusioninventoryCredentialip',
                'label'     => __('Remote devices to inventory (VMware)', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_credentialip'),
          array('rights'    => CommonDBTM::getRights(),
                'label'     => __('VMware host', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_esx'),
          array('itemtype'  => 'PluginFusioninventoryConfigSecurity',
                'label'     => __('SNMP authentication', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_configsecurity'),
          array('itemtype'  => 'PluginFusioninventoryNetworkEquipment',
                'label'     => __('Network equipment SNMP', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_networkequipment'),
          array('itemtype'  => 'PluginFusioninventoryPrinter',
                'label'     => __('Printer SNMP', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_printer'),
          array('itemtype'  => 'PluginFusioninventoryUnknowndevice',
                'label'     => __('Unknown devices', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_unknowndevice'),
          array('itemtype'  => 'PluginFusioninventoryInventoryComputerImportXML',
                'label'     => __('computer XML manual import', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_importxml'),
          array('rights'    => CommonDBTM::getRights(),
                'label'     => __('Printers report', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_reportprinter'),
          array('rights'    => CommonDBTM::getRights(),
                'label'     => __('Network report'),
                'field'     => 'plugin_fusioninventory_reportnetworkequipment'),
          array('itemtype'  => 'PluginFusioninventoryLock',
                'label'     => __('Lock', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_lock')
      );
      return $rights;
   }



   function getRightsGeneral() {
      $rights = array(
          array('rights'    => CommonDBTM::getRights(),
                'label'     => __('Menu', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_menu'),
          array('itemtype'  => 'PluginFusioninventoryAgent',
                'label'     => __('Agents', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_agent'),
          array('rights'    => CommonDBTM::getRights(),
                'label'     => __('Agent remote control', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_remotecontrol'),
          array('itemtype'  => 'PluginFusioninventoryConfig',
                'label'     => __('Configuration', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_configuration'),
          array('itemtype'  => 'PluginFusioninventoryTask',
                'label'     => _n('Task', 'Tasks', 2, 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_task'),
          array('rights'    => CommonDBTM::getRights(),
                'label'     => __('Wake On LAN', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_wol')
      );
      return $rights;
   }
}

?>
