<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the search in groups (static and dynamic).
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the search in groups (static and dynamic).
 */
class PluginFusioninventoryComputer extends Computer {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = "plugin_fusioninventory_group";

   function rawSearchOptions() {
      $computer = new Computer();
      $options  = $computer->rawSearchOptions();

      $plugin = new Plugin();
      if ($plugin->isInstalled('fields')) {
         if ($plugin->isActivated('fields')) {
            include_once(GLPI_ROOT . "/plugins/fields/hook.php");
            $options['fields_plugin'] = [
               'id'   => 'fields_plugin',
               'name' => __('Plugin fields')
            ];
            // $fieldsoptions = plugin_fields_getAddSearchOptions('Computer');
            // Hack because cron not have profile
            $fieldsoptions = $this->pluginFieldsGetAddSearchOptions('Computer');
            foreach ($fieldsoptions as $id=>$data) {
               $data['id'] = $id;
               $options[$id] = $data;
            }
         }
      }

      return $options;
   }

   /**
    * Get the massive actions for this object
    *
    * @param object|null $checkitem
    * @return array list of actions
    */
   function getSpecificMassiveActions($checkitem = null) {

      $actions = [];
      if (strstr(filter_input(INPUT_SERVER, "PHP_SELF"), '/report.dynamic.php')) {
         // In case we export list (CSV, PDF...) we do not have massive actions.
         return $actions;
      }

      if (isset($_GET['id'])) {
         $id = $_GET['id'];
      } else {
         $id = $_POST['id'];
      }
      $group = new PluginFusioninventoryDeployGroup();
      $group->getFromDB($id);

      //There's no massive action associated with a dynamic group !
      if ($group->isDynamicGroup() || !$group->canEdit($group->getID())) {
         return [];
      }

      if (!isset($_POST['custom_action'])) {
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'add']
               = _x('button', 'Add');
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'deleteitem']
               = _x('button', 'Delete permanently');
      } else {
         if ($_POST['custom_action'] == 'add_to_group') {
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'add']
               = _x('button', 'Add');
         } else if ($_POST['custom_action'] == 'delete_from_group') {
            $actions['PluginFusioninventoryComputer'.MassiveAction::CLASS_ACTION_SEPARATOR.'deleteitem']
               = _x('button', 'Delete permanently');
         }
      }
      return $actions;
   }


   /**
    * Define the standard massive actions to hide for this class
    *
    * @return array list of massive actions to hide
    */
   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      $forbidden[] = 'add';
      $forbidden[] = 'delete';
      return $forbidden;
   }


   /**
    * Execution code for massive action
    *
    * @param object $ma MassiveAction instance
    * @param object $item item on which execute the code
    * @param array $ids list of ID on which execute the code
    */
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      $group_item = new PluginFusioninventoryDeployGroup_Staticdata();
      switch ($ma->getAction()) {

         case 'add' :
            foreach ($ids as $key) {
               if ($item->can($key, UPDATE)) {
                  if (!countElementsInTable($group_item->getTable(),
                     [
                        'plugin_fusioninventory_deploygroups_id' => $_POST['id'],
                        'itemtype'                               => 'Computer',
                        'items_id'                               => $key,
                     ])) {
                     $group_item->add([
                        'plugin_fusioninventory_deploygroups_id'
                           => $_POST['id'],
                        'itemtype' => 'Computer',
                        'items_id' => $key]);
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
               if ($group_item->deleteByCriteria(['items_id' => $key,
                                                       'itemtype' => 'Computer',
                                                       'plugin_fusioninventory_deploygroups_id'
                                                          => $_POST['id']])) {
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
               } else {
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
               }
            }

      }
   }


   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      if ($ma->getAction() == 'add') {
         echo "<br><br>".Html::submit(_x('button', 'Add'),
                                      ['name' => 'massiveaction']);
         return true;
      }
      return parent::showMassiveActionsSubForm($ma);
   }

   /**
    * Hack of PluginFieldsContainer::getAddSearchOptions
    * I remove part of SQL restrict on profile
    * when cron run, the offical function return nothing because not have profile
    */
   function pluginFieldsGetAddSearchOptions($itemtype, $containers_id = false) {
      global $DB;

      $opt = [];

      $i = 76665;

      $query = "SELECT DISTINCT fields.id, fields.name, fields.label, fields.type, fields.is_readonly,
            containers.name as container_name, containers.label as container_label,
            containers.itemtypes, containers.id as container_id, fields.id as field_id
         FROM glpi_plugin_fields_containers containers
         INNER JOIN glpi_plugin_fields_fields fields
            ON containers.id = fields.plugin_fields_containers_id
            AND containers.is_active = 1
         WHERE containers.itemtypes LIKE '%$itemtype%'
            AND fields.type != 'header'
            ORDER BY fields.id ASC";
      $res = $DB->query($query);
      while ($data = $DB->fetch_assoc($res)) {

         if ($containers_id !== false) {
            // Filter by container (don't filter by SQL for have $i value with few containers for a itemtype)
            if ($data['container_id'] != $containers_id) {
               $i++;
               continue;
            }
         }

         $tablename = "glpi_plugin_fields_".strtolower($itemtype.
                        getPlural(preg_replace('/s$/', '', $data['container_name'])));

         //get translations
         $container = [
            'itemtype' => PluginFieldsContainer::getType(),
            'id'       => $data['container_id'],
            'label'    => $data['container_label']
         ];
         $data['container_label'] = PluginFieldsLabelTranslation::getLabelFor($container);

         $field = [
            'itemtype' => PluginFieldsField::getType(),
            'id'       => $data['field_id'],
            'label'    => $data['label']
         ];
         $data['label'] = PluginFieldsLabelTranslation::getLabelFor($field);

         $opt[$i]['table']         = $tablename;
         $opt[$i]['field']         = $data['name'];
         $opt[$i]['name']          = $data['container_label']." - ".$data['label'];
         $opt[$i]['linkfield']     = $data['name'];
         $opt[$i]['joinparams']['jointype'] = "itemtype_item";
         $opt[$i]['pfields_type']  = $data['type'];
         if ($data['is_readonly']) {
             $opt[$i]['massiveaction'] = false;
         }

         if ($data['type'] === "dropdown") {
            $opt[$i]['table']      = 'glpi_plugin_fields_'.$data['name'].'dropdowns';
            $opt[$i]['field']      = 'completename';
            $opt[$i]['linkfield']  = "plugin_fields_".$data['name']."dropdowns_id";

            $opt[$i]['forcegroupby'] = true;

            $opt[$i]['joinparams']['jointype'] = "";
            $opt[$i]['joinparams']['beforejoin']['table'] = $tablename;
            $opt[$i]['joinparams']['beforejoin']['joinparams']['jointype'] = "itemtype_item";
         }

         if ($data['type'] === "dropdownuser") {
            $opt[$i]['table']      = 'glpi_users';
            $opt[$i]['field']      = 'name';
            $opt[$i]['linkfield']  = $data['name'];
            $opt[$i]['right'] = 'all';

            $opt[$i]['forcegroupby'] = true;

            $opt[$i]['joinparams']['jointype'] = "";
            $opt[$i]['joinparams']['beforejoin']['table'] = $tablename;
            $opt[$i]['joinparams']['beforejoin']['joinparams']['jointype'] = "itemtype_item";
         }

         switch ($data['type']) {
            case 'dropdown':
            case 'dropdownuser':
               $opt[$i]['datatype'] = "dropdown";
               break;
            case 'yesno':
               $opt[$i]['datatype'] = "bool";
               break;
            case 'textarea':
               $opt[$i]['datatype'] = "text";
               break;
            case 'number':
               $opt[$i]['datatype'] = "number";
               break;
            case 'date':
            case 'datetime':
               $opt[$i]['datatype'] = $data['type'];
               break;
            default:
               $opt[$i]['datatype'] = "string";
         }

         $i++;
      }

      return $opt;
   }

}
