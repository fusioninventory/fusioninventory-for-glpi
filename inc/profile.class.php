<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2014 FusionInventory team
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

      static $rightname = "config";

      /*
       * Old profile names:
       *
       *    agent
       *    remotecontrol
       *    configuration
       *    wol
       *    unmanaged
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

   static function getOldRightsMappings() {
      $types = array ('agent'                  => 'plugin_fusioninventory_agent',
                      'remotecontrol'          => 'plugin_fusioninventory_remotecontrol',
                      'configuration'          => 'plugin_fusioninventory_configuration',
                      'wol'                    => 'plugin_fusioninventory_wol',
                      'unmanaged'              => 'plugin_fusioninventory_unmanaged',
                      'task'                   => 'plugin_fusioninventory_task',
                      'credential'             => 'plugin_fusioninventory_credential',
                      'credentialip'           => 'plugin_fusioninventory_credentialip',
                      'existantrule'           => array('plugin_fusioninventory_ruleimport',
                                                         'plugin_fusioninventory_ruleentity',
                                                         'plugin_fusioninventory_rulelocation'),
                      'importxml'              => 'plugin_fusioninventory_importxml',
                      'blacklist'              => 'plugin_fusioninventory_blacklist',
                      'ESX'                    => 'plugin_fusioninventory_esx',
                      'configsecurity'         => 'plugin_fusioninventory_configsecurity',
                      'networkequipment'       => 'plugin_fusioninventory_networkequipment',
                      'printer'                => 'plugin_fusioninventory_printer',
                      'reportprinter'          => 'plugin_fusioninventory_reportprinter',
                      'reportnetworkequipment' => 'plugin_fusioninventory_reportnetworkequipment',
                      'packages'               => 'plugin_fusioninventory_package',
                      'status'                 => 'plugin_fusioninventory_status',
                      'collect'                => array('plugin_fusioninventory_collect',
                                                        'plugin_fusioninventory_rulecollect'));

      return $types;
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->fields['interface'] == 'central') {
         return self::createTabEntry('FusionInventory');
      }
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      $pfProfile = new self();
      $pfProfile->showForm($item->getID());
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
         echo Html::hidden('id', array('value' => $profiles_id));
         echo Html::submit(_sx('button', 'Save'), array('name' => 'update'));
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";

      $this->showLegend();
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
          ),
          array('itemtype'  => 'PluginFusioninventoryCollectRule',
                'label'     => __('Additional computer information rules', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_rulecollect'
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
          array('itemtype'  => 'PluginFusioninventoryCredential',
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
          array('itemtype'  => 'PluginFusioninventoryUnmanaged',
                'label'     => __('Unmanaged devices', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_unmanaged'),
          array('itemtype'  => 'PluginFusioninventoryInventoryComputerImportXML',
                'label'     => __('computer XML manual import', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_importxml'),
          array('rights'    => array(READ => __('Read')),
                'label'     => __('Printers report', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_reportprinter'),
          array('rights'    => array(READ => __('Read')),
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
          array('rights'    => array(READ => __('Read')),
                'label'     => __('Menu', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_menu'),
          array('itemtype'  => 'PluginFusioninventoryAgent',
                'label'     => __('Agents', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_agent'),
          array('rights'    => array(READ => __('Read')),
                'label'     => __('Agent remote control', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_remotecontrol'),
          array('rights'    => array(READ => __('Read'), UPDATE => __('Update')),
                'itemtype'  => 'PluginFusioninventoryConfig',
                'label'     => __('Configuration', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_configuration'),
          array('itemtype'  => 'PluginFusioninventoryTask',
                'label'     => _n('Task', 'Tasks', 2, 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_task'),
          array('rights'    => array(READ => __('Read')),
                'label'     => __('Wake On LAN', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_wol'),
          array('itemtype'  => 'PluginFusioninventoryDeployGroup',
                'label'     => __('Groups of computers', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_group'),
          array('itemtype'  => 'PluginFusioninventoryCollect',
                'label'     => __('Additional computer information', 'fusioninventory'),
                'field'     => 'plugin_fusioninventory_collect')
      );

      return $rights;
   }

   static function addDefaultProfileInfos($profiles_id, $rights) {
      $profileRight = new ProfileRight();
      foreach ($rights as $right => $value) {
         if (!countElementsInTable('glpi_profilerights',
                                   "`profiles_id`='$profiles_id' AND `name`='$right'")) {
            $myright['profiles_id'] = $profiles_id;
            $myright['name']        = $right;
            $myright['rights']      = $value;
            $profileRight->add($myright);

            //Add right to the current session
            $_SESSION['glpiactiveprofile'][$right] = $value;
         }
      }
   }

   /**
    * @param $ID  integer
    */
   static function createFirstAccess($profiles_id) {
      include_once(GLPI_ROOT."/plugins/fusioninventory/inc/profile.class.php");
      $profile = new self();
      foreach ($profile->getAllRights() as $right) {
         self::addDefaultProfileInfos($profiles_id,
                                      array($right['field'] => ALLSTANDARDRIGHT));
      }
   }

   static function removeRightsFromSession() {
      $profile = new self();
      foreach ($profile->getAllRights() as $right) {
         if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
            unset($_SESSION['glpiactiveprofile'][$right['field']]);
         }
      }
      ProfileRight::deleteProfileRights(array($right['field']));

      if (isset($_SESSION['glpimenu']['plugins']['types']['PluginFusioninventoryMenu'])) {
         unset ($_SESSION['glpimenu']['plugins']['types']['PluginFusioninventoryMenu']);
      }
      if (isset($_SESSION['glpimenu']['plugins']['content']['pluginfusioninventorymenu'])) {
         unset ($_SESSION['glpimenu']['plugins']['content']['pluginfusioninventorymenu']);
      }
      if (isset($_SESSION['glpimenu']['assets']['types']['PluginFusioninventoryUnmanaged'])) {
         unset ($_SESSION['glpimenu']['plugins']['types']['PluginFusioninventoryUnmanaged']);
      }
      if (isset($_SESSION['glpimenu']['assets']['content']['pluginfusioninventoryunmanaged'])) {
         unset ($_SESSION['glpimenu']['assets']['content']['pluginfusioninventoryunmanaged']);
      }
   }

   static function migrateProfiles() {
      global $DB;
      //Get all rights from the old table
      $profiles = getAllDatasFromTable(getTableForItemType(__CLASS__));

      //Load mapping of old rights to their new equivalent
      $oldrights = self::getOldRightsMappings();

      //For each old profile : translate old right the new one
      foreach ($profiles as $id => $profile) {
         switch ($profile['right']) {
            case 'r' :
               $value = READ;
               break;
            case 'w':
               $value = ALLSTANDARDRIGHT;
               break;
            case 0:
            default:
               $value = 0;
               break;
         }
         //Write in glpi_profilerights the new fusioninventory right
         if (isset($oldrights[$profile['type']])) {
            //There's one new right corresponding to the old one
            if (!is_array($oldrights[$profile['type']])) {
               self::addDefaultProfileInfos($profile['profiles_id'],
                                            array($oldrights[$profile['type']] => $value));
            } else {
               //One old right has been splitted into serveral new ones
               foreach ($oldrights[$profile['type']] as $newtype) {
                  self::addDefaultProfileInfos($profile['profiles_id'],
                                               array($newtype => $value));
               }
            }
         }
      }
   }

   /**
   * Init profiles during installation :
   * - add rights in profile table for the current user's profile
   * - current profile has all rights on the plugin
   */
   static function initProfile() {
      $pfProfile = new self();
      $profile   = new Profile();
      $a_rights  = $pfProfile->getAllRights();

      foreach ($a_rights as $data) {
         if (countElementsInTable("glpi_profilerights", "`name` = '".$data['field']."'") == 0) {
            ProfileRight::addProfileRights(array($data['field']));
            $_SESSION['glpiactiveprofile'][$data['field']] = 0;
         }
      }

      // Add all rights to current profile of the user
      if (isset($_SESSION['glpiactiveprofile'])) {
         $dataprofile       = array();
         $dataprofile['id'] = $_SESSION['glpiactiveprofile']['id'];
         $profile->getFromDB($_SESSION['glpiactiveprofile']['id']);
         foreach ($a_rights as $info) {
            if (is_array($info)
                && ((!empty($info['itemtype'])) || (!empty($info['rights'])))
                  && (!empty($info['label'])) && (!empty($info['field']))) {

               if (isset($info['rights'])) {
                  $rights = $info['rights'];
               } else {
                  $rights = $profile->getRightsFor($info['itemtype']);
               }
               foreach ($rights as $right => $label) {
                  $dataprofile['_'.$info['field']][$right] = 1;
                  $_SESSION['glpiactiveprofile'][$data['field']] = $right;
               }
            }
         }
         $profile->update($dataprofile);
      }
   }
}

?>
