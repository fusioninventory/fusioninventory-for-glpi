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
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryConfigurationManagement_Model extends CommonDBTM {

   private $model_tree = array();
   private $DB_data = array();
   private $reference = array();
   static $rightname = 'plugin_fusioninventory_agent';


   function showForm($ID, $options=array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$this->fields['name']."' />";
      echo "</td>";
      echo "<td>".__('Type')."</td>";
      echo "<td>";
      $elements = array(
          'Computer' => __('Computer')
      );
      Dropdown::showFromArray('itemtype',
                              $elements,
                              array('value' => $this->fields['itemtype']));
      echo "</td>";
      echo "</tr>\n";

      $this->showFormButtons($options);

      if ($ID > 0) {
         $this->manageModel();
      }

      return TRUE;
   }



   function manageModel($items_id_1=-1, $items_id_2=-1) {
      global $CFG_GLPI;

      if (is_null($this->fields['serialized_model'])) {
         $new = TRUE;
      } else {
         $new = FALSE;
         $this->model_tree = importArrayFromDB($this->fields['serialized_model']);
      }

      $a_fields = $this->getListFields();

      echo "<form method='post' name='' id=''  action=\"".$CFG_GLPI['root_doc'] .
         "/plugins/fusioninventory/front/configurationmanagement_model.form.php\">";

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo '<th colspan="2"></th>';
      if ($items_id_1 != -1) {
         echo '<th>Valeur de référence</th>';
      }
      if ($items_id_2 != -1) {
         echo '<th>Valeur trouvée</th>';
      }
      echo '<th>Géré</th>';
      echo '<th>Ignoré</th>';
      echo '<th>Pas géré</th>';
      echo '</tr>';

      $this->manageModelLine(1, $a_fields, $new);

      echo "<tr>";
      echo "<th colspan='5'>";
      echo Html::hidden('id', array('value' => $this->getID()));
      echo "<input name='update_serialized' value=\"".__('Save').
         "\" class='submit' type='submit'>";
      echo "</th>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
   }



   function manageModelLine($rank, $a_fields, $new, $tree='') {
      foreach ($a_fields as $key=>$data) {
         if ($key != '_internal_name_'
                 && $key != '_itemtype_') {
            if (is_array($data)) {
               $celltype = 'td';
               if ($rank == 1) {
                  $celltype = 'th';
               }
               echo "<tr class='tab_bg_3'>";
               echo '<'.$celltype.' colspan="2" class="center"><strong>';
               echo $data['_internal_name_'];
               echo '</strong></'.$celltype.'>';
               $managed_checked = '';
               $ignored_checked = '';

               $tree_temp = $tree."/".$key."/_managetype_";
               if ($new) {
                  $managed_checked = 'checked';
               } else if (isset($this->model_tree[$tree_temp])) {
                  if ($this->model_tree[$tree_temp] == 'managed') {
                     $managed_checked = 'checked';
                  } else {
                     $ignored_checked = 'checked';
                  }
               }
               echo '<'.$celltype.' class="center">';
               echo "<input type='radio' name='".$tree_temp."' value='managed' ".$managed_checked." />";
               echo '</'.$celltype.'>';
               echo '<'.$celltype.' class="center">';
               echo "<input type='radio' name='".$tree_temp."' value='ignored' ".$ignored_checked." />";
               echo '</'.$celltype.'>';
               echo '<'.$celltype.' class="center"></'.$celltype.'>';
               echo "</tr>";
               $this->manageModelLine(($rank+1), $data, $new, $tree."/".$key);
            } else {
               $managed_checked = '';
               $ignored_checked = '';
               $notmanaged_checked = '';

               if ($new) {
                  $managed_checked = 'checked';
               } else if (isset($this->model_tree[$tree."/".$key])) {
                  if ($this->model_tree[$tree."/".$key] == 'managed') {
                     $managed_checked = 'checked';
                  } else if ($this->model_tree[$tree."/".$key] == 'ignored') {
                     $ignored_checked = 'checked';
                  } else {
                     $notmanaged_checked = 'checked';
                  }
               }

               echo "<tr class='tab_bg_3'>";
               for ($i=2; $i < $rank; $i++) {
                  echo '<td></td>';
               }
               echo '<td colspan="'.(2-($rank-2)).'">';
               echo $data;
               echo '</td>';
               echo '<td class="center">';
               $tree_temp = $tree."/".$key;
               echo "<input type='radio' name='".$tree_temp."' value='managed' ".$managed_checked." />";
               echo '</td>';
               echo '<td class="center">';
               echo "<input type='radio' name='".$tree_temp."' value='ignored' ".$ignored_checked." />";
               echo '</td>';
               echo '<td class="center">';
               echo "<input type='radio' name='".$tree_temp."' value='notmanaged' ".$notmanaged_checked." />";
               echo '</td>';
               echo "</tr>";
            }
         }
      }
   }



   function getListFields() {

      $a_fields = array();
      $a_fields['Computer'] = array(
          '_internal_name_' => 'Computer info',
          '_itemtype_'     => 'Computer',
          'entities_id'    => __('Entity'),
          'name'           => __('Name'),
          'serial'         => __('Serial number'),
          'otherserial'    => __('Inventory number'),
          'contact'        => __('Alternate username'),
          'contact_num'    => __('Alternate username number'),
          'uuid'           => __('UUID'),
          'comment'        => __('Comments'),
          'users_id_tech'  => array(
              '_internal_name_' => __('Technician in charge of the hardware'),
              '_itemtype_' => 'User',
              'id'         => __('ID'),
              'name'       => __('Name'),
              'realname'   => __('Surname'),
              'firstname'  => __('First name')
          ),
          'groups_id_tech' => array(
              '_internal_name_' => __('Group in charge of the hardware'),
              '_itemtype_' => 'Group',
              'id'        => __('ID'),
              'name'      => __('Name'),
          ),
          'operatingsystems_id' => array(
              '_internal_name_' => __('Operating system'),
              '_itemtype_' => 'OperatingSystem',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'operatingsystemservicepacks_id' => array(
              '_internal_name_' => __('Service pack'),
              '_itemtype_' => 'OperatingSystemServicePack',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'operatingsystemversions_id' => array(
              '_internal_name_' => __('Version of the operating system'),
              '_itemtype_' => 'OperatingSystemVersion',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'os_license_number' => __('Serial of the operating system'),
          'os_licenseid' => __('Product ID of the operating system'),
          'autoupdatesystems_id' => array(
              '_internal_name_' => __('Update Source'),
              '_itemtype_' => 'AutoUpdateSystem',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'locations_id' => array(
              '_internal_name_' => __('Location'),
              '_itemtype_' => 'Location',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'domains_id' => array(
              '_internal_name_' => __('Domain'),
              '_itemtype_' => 'Domain',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'networks_id' => array(
              '_internal_name_' => __('Network'),
              '_itemtype_' => 'Network',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'computermodels_id' => array(
              '_internal_name_' => __('Model'),
              '_itemtype_' => 'ComputerModel',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'computertypes_id' => array(
              '_internal_name_' => __('Type'),
              '_itemtype_' => 'ComputerType',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'manufacturers_id' => array(
              '_internal_name_' => __('Manufacturer'),
              '_itemtype_' => 'Manufacturer',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'users_id' => array(
              '_internal_name_' => __('User'),
              '_itemtype_' => 'User',
              'id'   => __('ID'),
              'name' => __('Name'),
              'realname'  => __('Surname'),
              'firstname' => __('First name')
          ),
          'groups_id' => array(
              '_internal_name_' => __('Group'),
              '_itemtype_' => 'Group',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'states_id' => array(
              '_internal_name_' => __('Status'),
              '_itemtype_' => 'State',
              'id'   => __('ID'),
              'name' => __('Name')
          )
      );
      $a_fields['processor'] = array(
          '_internal_name_' => __('Processor'),
          '_itemtype_' => 'DeviceProcessor',
          'id'    => __('ID'),
          'designation'  => __('Name'),
          'frequence' => __('Frequency'),
          'frequency' => __('Frequency'),
          'serial' => __('Serial number'),
          'manufacturers_id' => array(
              '_internal_name_' => __('Manufacturer'),
              '_itemtype_' => 'Manufacturer',
              'id'   => __('ID'),
              'name' => __('Name')
          )
      );
      $a_fields['memory'] = array(
          '_internal_name_' => __('Memory'),
          '_itemtype_' => 'DeviceMemory',
          'id'    => __('ID'),
          'designation'  => __('Name'),
          'frequence' => '',
          'size' => '',
          'serial' => '',
          'manufacturers_id' => array(
              '_internal_name_' => __('Manufacturer'),
              '_itemtype_' => 'Manufacturer',
              'id'   => __('ID'),
              'name' => __('Name')
          ),
          'devicememorytypes_id' => array(
              '_internal_name_' => '',
              '_itemtype_' => 'DeviceMemoryType',
              'id'   => __('ID'),
              'name' => __('Name')
          )
      );
      $a_fields['software'] = array(
          '_internal_name_' => __('Software'),
          '_itemtype_' => 'Software',
          'id'    => __('ID'),
          'name'  => __('Name'),
          'version' => __('Version'),
          'manufacturers_id' => array(
              '_internal_name_' => __('Manufacturer'),
              '_itemtype_' => 'Manufacturer',
              'id'   => __('ID'),
              'name' => __('Name')
          )
      );
      return $a_fields;
   }
}

?>