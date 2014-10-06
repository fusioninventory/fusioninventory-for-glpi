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
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryComputer extends Computer {

   static $rightname = "plugin_fusioninventory_group";

   function getSearchOptions() {
      $computer = new Computer();
      $options  = $computer->getSearchOptions();

      $options['6000']['name']          = __('Static group', 'fusioninventory');
      $options['6000']['table']         = getTableForItemType('PluginFusioninventoryDeployGroup');
      $options['6000']['massiveaction'] = FALSE;
      $options['6000']['field']         ='name';
      $options['6000']['forcegroupby']  = true;
      $options['6000']['usehaving']     = true;
      $options['6000']['datatype']      = 'dropdown';
      $options['6000']['joinparams']    = array('beforejoin'
                                         => array('table'      => 'glpi_plugin_fusioninventory_deploygroups_staticdatas',
                                                  'joinparams' => array('jointype'          => 'itemtype_item',
                                                                        'specific_itemtype' => 'Computer')));
      return $options;
   }

   function getSpecificMassiveActions($checkitem=NULL) {

      $actions = array();
      if (isset($_GET['id'])) {
         $id = $_GET['id'];
      } else {
         $id = $_POST['id'];
      }
      $group = new PluginFusioninventoryDeployGroup();
      $group->getFromDB($id);

      //There's no massive action associated with a dynamic group !
      if ($group->isDynamicGroup() || !$group->canEdit($group->getID())) {
         return array();
      }

      if (!isset($_POST['custom_action'])) {
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'add']
               = _x('button', 'Add');
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'deleteitem']
               = _x('button','Delete permanently');
      } else {
         if ($_POST['custom_action'] == 'add_to_group') {
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'add']
               = _x('button', 'Add');
         } elseif($_POST['custom_action'] == 'delete_from_group') {
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'deleteitem']
               = _x('button','Delete permanently');
         }
      }
      return $actions;
   }

   /**
    * @since version 0.84
   **/
   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      $forbidden[] = 'add';
      $forbidden[] = 'delete';
      return $forbidden;
   }


   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
   **/
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      $group_item = new PluginFusioninventoryDeployGroup_Staticdata();
      switch ($ma->getAction()) {
         case 'add' :
            $input = $ma->getInput();
            foreach ($ids as $key) {
               if ($item->can($key, UPDATE)) {
                  if (!countElementsInTable($group_item->getTable(),
                                            "`plugin_fusioninventory_deploygroups_id`='"
                                                .$_POST['id']."'
                                              AND `itemtype`='Computer'
                                              AND `items_id`='$key'")) {
                     $group_item->add(array(
                        'plugin_fusioninventory_deploygroups_id'
                           => $_POST['id'],
                        'itemtype' => 'Computer',
                        'items_id' => $key));
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
            } else {
               $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_NORIGHT);
               $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
            }
         }
         return;

         case 'deleteitem':

            foreach ($ids as $key) {
               $group_item->deleteByCriteria(array('items_id' => $key,
                                                   'itemtype' => 'Computer',
                                                   'plugin_fusioninventory_deploygroups_id'
                                                      => $_POST['id']));
         }
      }
   }

   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      global $CFG_GLPI;

      switch ($ma->getAction()) {
         case 'add' :
            echo "<br><br>".Html::submit(_x('button', 'Add'),
                                         array('name' => 'massiveaction'));
            return true;

      }
      return parent::showMassiveActionsSubForm($ma);
   }

}

?>