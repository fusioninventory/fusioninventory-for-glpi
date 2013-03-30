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
   @co-author Alexandre Delaunay
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

class PluginFusioninventoryDeployStaticmisc {

   const DEPLOYMETHOD_INSTALL   = 'deployinstall';
   const DEPLOYMETHOD_UNINSTALL = 'deployuninstall';

   static function task_methods() {

      return array(array('module'         => 'fusinvdeploy',
                         'method'         => self::DEPLOYMETHOD_INSTALL,
                         'name'           => __('Package deployment', 'fusioninventory'),
                         'task'           => "DEPLOY",
                         'use_rest'       => TRUE),
                   array('module'         => 'fusinvdeploy',
                         'method'         => self::DEPLOYMETHOD_UNINSTALL,
                         'name'           => __('Package uninstall', 'fusioninventory'),
                         'task'           => "DEPLOY",
                         'use_rest'       => TRUE)
                         );
   }

   
   
   static function getItemtypeActions() {
      return array('PluginFusioninventoryDeployPackage');
   }
   /*
   # Actions with itemtype autorized
   static function task_action_deploy_install() {
      return self::getItemtypeActions();
   }

   # Actions with itemtype autorized
   static function task_action_deploy_uninstall() {
      return self::getItemtypeActions();
   }*/

   
   
   static function getDefinitionType() {
      return array(0 => Dropdown::EMPTY_VALUE,
                   'PluginFusioninventoryDeployPackage' => __('Package', 'fusioninventory'));
   }

   
   
   static function getActionType() {
      return array(0 => Dropdown::EMPTY_VALUE,
                   'PluginFusioninventoryDeployGroup'=> __('Group of computers', 'fusioninventory'),
                   'Computer' => __('Computers'),
                   'Group' => __('Group')
                  );
   }

   
   
   static function task_definitiontype_deployinstall($a_itemtype) {
      return self::getDefinitionType();
   }

   
   
   static function task_definitiontype_deployuninstall($a_itemtype) {
      return self::getDefinitionType();
   }

   
   
   static function task_actiontype_deployinstall($a_itemtype) {
      return self::getActionType();
   }

   
   
   static function task_actiontype_deployuninstall($a_itemtype) {
      return self::getActionType();
   }

   
   
   static function getDeploySelections() {

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployPackage", $options);
   }

   
   
  /* static function getDeployActions() {

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = Session::haveAccessToEntity($_SESSION['glpiactive_entity'], 1);
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Computer", $options);
   }*/

   
   
   static function getDeployActions() {

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);

   }
   
   

   static function task_definitionselection_PluginFusioninventoryDeployPackage_deployinstall() {
      return self::getDeploySelections();
   }
   
   

   static function task_definitionselection_PluginFusioninventoryDeployPackage_deployuninstall() {
      return self::getDeploySelections();
   }
   
   

   static function task_definitionselection_PluginFusioninventoryDeployGroup_deployinstall() {
      return self::getDeployActions();
   }
   
   

   static function task_definitionselection_PluginFusioninventoryDeployGroup_deployuninstall() {
      return self::getDeployActions();
   }

   
   
   static function task_actionselection_Computer_deployinstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   = '`id` IN (SELECT `items_id` FROM '.
                                    '`glpi_plugin_fusioninventory_agents`)';
      return Dropdown::show("Computer", $options);
   }
   
   
   
   static function task_actionselection_Computer_deployuninstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   = '`id` IN (SELECT `items_id` '.
                                   'FROM `glpi_plugin_fusioninventory_agents`)';
      return Dropdown::show("Computer", $options);
   }
   
   

   static function task_actionselection_Group_deployinstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Group", $options);
   }
   
   

   static function task_actionselection_PluginFusioninventoryDeployGroup_deployinstall() {
      return self::getDeployActions();
   }
   
   

   static function task_actionselection_PluginFusioninventoryDeployGroup_deployuninstall() {
      return self::getDeployActions();
   }

   

   static function profiles() {

      return array(array('profil'  => 'packages',
                         'name'    => __('Manage packages', 'fusioninventory')),

                   array('profil'  => 'status',
                         'name'    => __('Deployment status', 'fusioninventory')));

   }

   
   
   static function task_deploy_getParameters() {
      return array ('periodicity' => 3600, 'delayStartup' => 3600, 'task' => 'Deploy',
                    'remote' => PluginFusioninventoryAgentmodule::getUrlForModule('Deploy'));
   }
}

?>
