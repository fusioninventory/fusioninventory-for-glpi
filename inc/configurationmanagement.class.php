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

class PluginFusioninventoryConfigurationManagement extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_agent';
   private $referential = array();
   private $managetype = '';
   public $a_trees = array();

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Configuration management', 'fusioninventory');
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Agent', 'fusioninventory');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = __('Name');
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'conform';
      $tab[2]['massiveaction']    = false;
      $tab[2]['name']      = __('conform', 'fusioninventory');
      $tab[2]['datatype']  = 'bool';

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'sha_referential';
      $tab[3]['massiveaction']    = false;
      $tab[3]['name']      = __('SHA of referential', 'fusioninventory');
      $tab[3]['datatype']  = 'string';

      $tab[4]['table']             = $this->getTable();
      $tab[4]['field']             = 'items_id';
      $tab[4]['name']              = __('Associated element');
      $tab[4]['datatype']          = 'specific';
      $tab[4]['nosearch']          = true;
      $tab[4]['nosort']            = true;
      $tab[4]['massiveaction']     = false;
      $tab[4]['additionalfields']  = array('itemtype');


      $tab[5]['table']            = $this->getTable();
      $tab[5]['field']            = 'itemtype';
      $tab[5]['name']             = __('Type');
      $tab[5]['massiveaction']    = false;
      $tab[5]['datatype']         = 'itemtypename';


      return $tab;
   }



   /**
    * @see CommonGLPI::defineTabs()
   **/
   function defineTabs($options=array()) {

      $ong = array();
   //   $this->addDefaultFormTab($ong);
      $this->addStandardTab("PluginFusioninventoryConfigurationManagement", $ong, $options);
      return $ong;
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $a_tabs = array();
      if ($item->getType() == __CLASS__) {
         $a_tabs[1] = 'Generate the referentiel';
         if ($item->fields['sha_referential'] != '') {
            $a_tabs[2] = 'Diff';
         }
      } else if ($item->getType() == 'Computer') {
         $a_tabs[1] = 'Configuration Management';
      }
      return $a_tabs;
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType() == __CLASS__) {
         if ($tabnum == 1) {
            $item->generateReferential($item->getID());
         } else if ($tabnum == 2) {
            $a_currentinv = $item->generateCurrentInventory($item->getID());
            $item->displayDiff($item->getID(), $a_currentinv);
         }
      } else if ($item->getType() == 'Computer') {
         $pfConfigurationManagement = new PluginFusioninventoryConfigurationManagement();
         $a_find = $pfConfigurationManagement->find("`itemtype`='Computer'
                           AND `items_id`='".$item->getID()."'", '', 1);
         if (count($a_find) == 0) {
            // form to add this computer into config management
            $pfConfigurationManagement->showForm($item);
         } else {
            $data = current($a_find);
            if ($data['sha_referential'] == '') {
               $pfConfigurationManagement->showLinkToDefineRef($data['id']);
            } else {
               // See diff
               $a_currentinv = $pfConfigurationManagement->generateCurrentInventory($data['id']);
               $pfConfigurationManagement->displayDiff($data['id'], $a_currentinv);
            }
         }

      }
      return TRUE;
   }




   /** Display item with tabs
    *
    * @since version 0.85
    *
    * @param $options   array
   **/
   function display($options=array()) {

      if (isset($options['id'])
          && !$this->isNewID($options['id'])) {
         $this->getFromDB($options['id']);
      }

      $this->showNavigationHeader($options);
      $this->showTabsContent($options);
   }



   function showForm($item) {

      $this->getEmpty();
      $this->showFormHeader();

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "To add configuratiom management of this device, click on add button...";
      echo Html::hidden('itemtype', array('value' => $item->getType()));
      echo Html::hidden('items_id', array('value' => $item->getID()));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons();

      return true;
   }



   function showLinkToDefineRef($id) {

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "<br/><a href='".$this->getFormURL()."?id=".$id."' class='vsubmit'>Define your referential</a><br/><br/>";
      echo "</th>";
      echo "</tr>";
      echo "</table>";
   }



   function generateReferential($items_id) {
      global $CFG_GLPI;

      $pfconfmanage_model = new PluginFusioninventoryConfigurationManagement_Model();

      $list_fields = $pfconfmanage_model->getListFields();

      $this->getFromDB($items_id);
      $this->referential = importArrayFromDB($this->fields['serialized_referential']);

      // Use model

      // Add form to add for example a software to the referential

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo '<th colspan="2"></th>';
      echo '<th>Valeur de référence</th>';
      echo '<th>Config</th>';
      echo '<th>Action</th>';
      echo '</tr>';

      echo "<tr class='tab_bg_3'>";
      echo '<td colspan="3" align="center">';
      echo "<i><strong>All</strong></i>";
      echo '</td>';
      echo '<td>';
      if (isset($this->referential['/_managetype_'])) {
         $elements = array(
             '_managed_'    => __('In referentiel + alert', 'fusioninventory'),
             '_ignored_'    => __('In referentiel - alert', 'fusioninventory'),
             '_notmanaged_' => __('Not in referentiel', 'fusioninventory'),
         );
         echo $elements['_'.$this->referential['/_managetype_'].'_'];
      }
      echo '</td>';
      echo '<td>';
      $rand = mt_rand();
      $js_vals = array(
          'managed'    => 1,
          'ignored'    => 1,
          'notmanaged' => 1
      );
      echo "<div id='".$rand."'>";
      echo "<a href='javascript:viewAction(".$rand.", \"/\","
              .$js_vals['managed'].",".$js_vals['ignored'].", "
              .$js_vals['notmanaged'].");'>".__('View actions', 'fusioninventory')."</a>";
      echo "</div>";
      echo '</td>';
      echo "</tr>";
      if (isset($this->referential['/_managetype_'])) {
         $this->managetype = $this->referential['/_managetype_'];
      }

      $this->displayGenerateLine(1, $list_fields, 1, '', $this->fields['items_id'],$this->fields['itemtype']);
      echo "</table>";

      echo "<script>
         function viewAction(id, tree, managed, ignored, notmanaged) {
            $('#' + id).load('".$CFG_GLPI['root_doc']."/plugins/fusioninventory/ajax/configurationamanagement_action.php'
               ,{tree:tree,items_id:".$items_id.",managed:managed,ignored:ignored,notmanaged:notmanaged}
            )
         };
      </script>";
   }



   function displayGenerateLine($rank, $a_fields, $new, $tree, $items_id=0, $itemtype='', $a_DBvalues=array()) {
      global $CFG_GLPI;

      foreach ($a_fields as $key=>$data) {
         if ($key != '_internal_name_'
                 && $key != '_itemtype_') {
            if (is_array($data)) {
               if ($itemtype == '') {
                  $a_DBData = $this->getData_fromDB($key, $items_id, $data['_itemtype_'], $tree.$key."/".$items_id."/");
               } else {
                  $a_DBData = $this->getData_fromDB($key, $items_id, $itemtype, $tree."/".$items_id."/".$key);
               }
               foreach ($a_DBData as $k=>$a_val) {
                  if ($rank == 1) {
                     echo "<tr class='tab_bg_3'>";
                     echo '<td colspan="5">&nbsp;';
                     echo "</td>";
                     echo "</tr>";
                  }

                  echo "<tr class='tab_bg_3'>";
                  echo '<td colspan="3">';
                  $indent = 0;
                  if ($rank != 1) {
                     $indent = 4;
                  }
                  echo str_repeat("&nbsp;", $indent);
                  echo "<strong>ₒ</strong> ";
                  echo "<i>".$data['_internal_name_']."</i>";
                  echo '</td>';
                  $managed_checked = '';
                  $ignored_checked = '';

                  $tree_temp = $tree."/".$key."/".$a_val['id']."/_managetype_";
                  if ($new) {
                     $managed_checked = 'checked';
                  } else if (isset($this->model_tree[$tree_temp])) {
                     if ($this->model_tree[$tree_temp] == 'managed') {
                        $managed_checked = 'checked';
                     } else {
                        $ignored_checked = 'checked';
                     }
                  }
                  $elements = array(
                      '_managed_'    => __('In referentiel + alert', 'fusioninventory'),
                      '_ignored_'    => __('In referentiel - alert', 'fusioninventory'),
                      '_notmanaged_' => __('Not in referentiel', 'fusioninventory'),
                  );
                  echo '<td>';
                  if (isset($this->referential[$tree_temp])) {
                     echo str_repeat("&nbsp;", $indent);
                     echo "<strong>ₒ</strong> ";
                     echo $elements['_'.$this->referential[$tree_temp].'_'];
                     unset($elements['_'.$this->referential[$tree_temp].'_']);
                  } else if (isset($this->referential[$tree.'/_managetype_'])) {
                     echo str_repeat("&nbsp;", $indent);
                     echo "<strong>ₒ</strong> ";
                     echo "<font color='#b0b0b0'>";
                     echo __('Inheritance of the parent entity');
                     echo "</font>";
                     $this->managetype = $this->referential[$tree.'/_managetype_'];
                     unset($elements['_'.$this->referential[$tree.'/_managetype_'].'_']);
                  } else if ($this->managetype != '') {
                     echo str_repeat("&nbsp;", $indent);
                     echo "<strong>ₒ</strong> ";
                     echo "<font color='#b0b0b0'>";
                     echo __('Inheritance of the parent entity');
                     echo "</font>";
                  }
                  echo '</td>';
                  echo '<td>';
                  $rand = mt_rand();
                  $js_vals = array(
                      'managed'    => 0,
                      'ignored'    => 0,
                      'notmanaged' => 0
                  );
                  foreach ($elements as $elkey => $elvalue) {
                     $js_vals[trim($elkey, '_')] = 1;
                  }
                  echo "<div id='".$rand."'>";
                  echo "<a href='javascript:viewAction(".$rand.", \"".$tree."/".$key."/".$a_val['id']."\","
                          .$js_vals['managed'].",".$js_vals['ignored'].", "
                          .$js_vals['notmanaged'].");'>".__('View actions', 'fusioninventory')."</a>";
                  echo "</div>";
                  echo '</td>';
                  echo "</tr>";
                  $this->displayGenerateLine(($rank+1), $data, $new, $tree."/".$key."/".$a_val['id'], $a_val['id'], $data['_itemtype_'], $a_val);
               }
            } else {
               $indent = 4;
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
               echo '<td colspan="2">';
               if ($rank == 3) {
                  $indent = 9;
               }
               echo str_repeat("&nbsp;", $indent);
               echo "<strong>ₒ</strong> ";
               echo $data;
               echo '</td>';
               echo '<td>';
               if (isset($a_DBvalues[$key])) {
                  echo $a_DBvalues[$key];
               }
               echo '</td>';
               $tree_temp = $tree."/".$key;
               $value = '';
               if (isset($a_DBvalues[$key])) {
                  $value = $a_DBvalues[$key];
               }
               $elements = array(
                   '_managed_'    => __('In referentiel + alert', 'fusioninventory'),
                   '_ignored_'    => __('In referentiel - alert', 'fusioninventory'),
                   '_notmanaged_' => __('Not in referentiel', 'fusioninventory'),
               );
               echo '<td>';
               if (isset($this->referential[$tree_temp.'/_managetype_'])) {
                  echo str_repeat("&nbsp;", $indent);
                  echo "<strong>ₒ</strong> ";
                  echo $elements['_'.$this->referential[$tree_temp.'/_managetype_'].'_'];
                  unset($elements['_'.$this->referential[$tree_temp.'/_managetype_'].'_']);
               } else if (isset($this->referential[$tree.'/_managetype_'])) {
                  echo str_repeat("&nbsp;", $indent);
                  echo "<strong>ₒ</strong> ";
                  echo "<font color='#b0b0b0'>";
                  echo __('Inheritance of the parent entity');
                  echo "</font>";
                  unset($elements['_'.$this->referential[$tree.'/_managetype_'].'_']);
               } else if ($this->managetype != '') {
                     echo str_repeat("&nbsp;", $indent);
                     echo "<strong>ₒ</strong> ";
                     echo "<font color='#b0b0b0'>";
                     echo __('Inheritance of the parent entity');
                     echo "</font>";
                  }
               echo '</td>';
               echo '<td>';

               $rand = mt_rand();
               $js_vals = array(
                   'managed'    => 0,
                   'ignored'    => 0,
                   'notmanaged' => 0
               );
               foreach ($elements as $elkey => $elvalue) {
                  $js_vals[trim($elkey, '_')] = 1;
               }
               echo "<div id='".$rand."'>";
               echo "<a href='javascript:viewAction(".$rand.", \"".$tree_temp."\","
                       .$js_vals['managed'].",".$js_vals['ignored'].", "
                       .$js_vals['notmanaged'].");'>".__('View actions', 'fusioninventory')."</a>";
               echo "</div>";
               echo '</td>';
               echo "</tr>";
            }
         }
      }
   }



   function generateTree($rank, $a_fields, $new, $tree, $items_id=0, $itemtype='', $a_DBvalues=array()) {

      foreach ($a_fields as $key=>$data) {
         if ($key != '_internal_name_'
                 && $key != '_itemtype_') {
            if (is_array($data)) {
               if ($itemtype == '') {
                  $a_DBData = $this->getData_fromDB($key, $items_id, $data['_itemtype_'], $tree.$key."/".$items_id."/");
               } else {
                  $a_DBData = $this->getData_fromDB($key, $items_id, $itemtype, $tree."/".$items_id."/".$key);
               }
               foreach ($a_DBData as $k=>$a_val) {
                  $this->a_trees[$tree."/".$key."/".$a_val['id']] = '';
                  $this->generateTree(($rank+1), $data, $new, $tree."/".$key."/".$a_val['id'], $a_val['id'], $data['_itemtype_'], $a_val);
               }
            } else {
               $value = '';
               if (isset($a_DBvalues[$key])) {
                  $value = $a_DBvalues[$key];
               }
               $this->a_trees[$tree."/".$key] = $value;
            }
         }
      }
   }



   function getData_fromDB($name, $items_id, $itemtype, $tree) {
      $a_DBdata = array();
      $item = new $itemtype();
      if ($item->getFromDB($items_id)) {

         switch ($name) {
            case 'manufacturers_id':
               $manufacturer = new Manufacturer();
               if ($manufacturer->getFromDB($item->fields[$name])) {
                  $a_DBdata[] = $manufacturer->fields;
               } else {
                  $manufacturer->getEmpty();
                  $manufacturer->fields['id'] = $item->fields[$name];
                  $a_DBdata[] = $manufacturer->fields;
               }
               break;

            case 'Computer':
               $a_DBdata[] = $item->fields;
               break;

            case 'users_id_tech':
            case 'users_id':
               $user = new User();
               if ($user->getFromDB($item->fields[$name])) {
                  $a_DBdata[] = $user->fields;
               } else {
                  $user->getEmpty();
                  $user->fields['id'] = $item->fields[$name];
                  $a_DBdata[] = $user->fields;
               }
               break;

            case 'processor':
               $deviceProcessor = new DeviceProcessor();
               $item_DeviceProcessor = new Item_DeviceProcessor();
               $a_procs = $item_DeviceProcessor->find("`itemtype`='".$itemtype."'
                  AND `items_id`='".$items_id."'");
               foreach ($a_procs as $a_proc) {
                  $deviceProcessor->getFromDB($a_proc['deviceprocessors_id']);
                  $a_add = $deviceProcessor->fields;
                  $a_add['frequency'] = $a_proc['frequency'];
                  $a_add['serial'] = $a_proc['serial'];
                  $a_add['id'] = $a_proc['id'];
                  $a_DBdata[] = $a_add;
               }
               break;

            case 'software':
               $software = new Software();
               $softwareVersion = new SoftwareVersion();
               $computer_SoftwareVersion = new Computer_SoftwareVersion();

               $a_compversions = $computer_SoftwareVersion->find("`computers_id`='".$items_id."'");
               foreach ($a_compversions as $a_compversion) {
                  $softwareVersion->getFromDB($a_compversion['softwareversions_id']);
                  $software->getFromDB($softwareVersion->fields['softwares_id']);
                  $a_add = $software->fields;
                  $a_add['version'] = $softwareVersion->fields['name'];
                  $a_DBdata[] = $a_add;
               }
               break;

         }
      }
      return $a_DBdata;
   }



   function generateCurrentInventory($id, $a_ref=array(), $a_currentinv=array(),
                                     $a_fields=array(), $tree='', $items_id=0, $itemtype='', $a_DBvalues=array()) {
      if (count($a_ref) == 0) {
         $this->getFromDB($id);
         $a_ref = importArrayFromDB($this->fields['serialized_referential']);
         $pfconfmanage_model = new PluginFusioninventoryConfigurationManagement_Model();
         $a_fields = $pfconfmanage_model->getListFields();
         $itemtype = $this->fields['itemtype'];
         $items_id = $this->fields['items_id'];
      }

      foreach ($a_fields as $key=>$data) {
         if ($key != '_internal_name_'
                 && $key != '_itemtype_') {
            if (is_array($data)) {
               if ($itemtype == '') {
                  $a_DBData = $this->getData_fromDB($key, $items_id, $data['_itemtype_'], $tree.$key."/".$items_id."/");
               } else {
                  $a_DBData = $this->getData_fromDB($key, $items_id, $itemtype, $tree."/".$items_id."/".$key);
               }
               foreach ($a_DBData as $k=>$a_val) {
                  #$tree_temp = $tree."/".$key."/".$a_val['id']."/_managetype_";
                  $tree_temp = $tree."/".$key."/".$a_val['id'];
                  if (isset($a_ref[$tree_temp])) {
                     $a_currentinv[$tree_temp] = $a_ref[$tree_temp];
                  } else {
                     $a_currentinv[$tree_temp] = 'managed';
                  }
                  if ($a_currentinv[$tree_temp] != 'ignored') {
                     $a_currentinv = $this->generateCurrentInventory($id, $a_ref, $a_currentinv, $data, $tree."/".$key."/".$a_val['id'], $a_val['id'], $data['_itemtype_'], $a_val);
                  }
               }
            } else {
               $tree_temp = $tree."/".$key;
               $value = '';
               if (isset($a_DBvalues[$key])) {
                  $value = $a_DBvalues[$key];
               }
               if (isset($a_ref[$tree_temp])) {
                  if ($a_ref[$tree_temp] == '_ignored_') {
                     $a_currentinv[$tree_temp] = '_ignored_';
                  } else if ($a_ref[$tree_temp] == '_notmanaged_') {
                     // not use it
                  } else {
                     $a_currentinv[$tree_temp] = $value;
                  }
               } else {
                  $a_currentinv[$tree_temp] = $value;
               }
            }
         }
      }
      return $a_currentinv;
   }



   function displayDiff($items_id, $a_currentinv) {

      $pfconfmanage_model = new PluginFusioninventoryConfigurationManagement_Model();

      $list_fields = $pfconfmanage_model->getListFields();

      $this->getFromDB($items_id);
      $a_ref = importArrayFromDB($this->fields['serialized_referential']);

      $a_missingInCurrentinv = array_diff_key($a_ref, $a_currentinv);
      $a_missingInRef = array_diff_key($a_currentinv, $a_ref);

      $a_update = array();
      foreach ($a_ref as $key=>$value) {
         if (isset($a_currentinv[$key])
                 && $a_currentinv[$key] != $value) {
            $a_update[$key] = $value;
         }
      }


      $a_onlyinref = array();
      $a_notinref = array();

      // Get sections in ref but not in current
      $a_miss_curr = array();
      foreach ($a_missingInCurrentinv as $key=>$value) {
         $split = explode('/', $key);
         unset($split[(count($split) - 1)]);
         $a_miss_curr[(implode('/', $split))] = "";
      }
      $current_itemtype = '';
      $current_id = '';
      $base_tree = '';
      foreach ($a_miss_curr as $key=>$value) {
         // Get all elements of the section
         $split = explode('/', $key);
         $list_fields_temp = $list_fields;
         for ($i=1; $i < count($split); $i += 2) {
            $list_fields_temp = $list_fields_temp[$split[$i]];
         }
         if ($key != '') {
            if (count($split) <= 3) {
               $current_itemtype = $split[count($split) - 2];
               $current_id = $split[count($split) - 1];
               $base_tree = $key;
            }
            foreach ($list_fields_temp as $keyref=>$valueref) {
               if ($keyref != '_internal_name_'
                    && $keyref != '_itemtype_') {
                  if (!is_array($valueref)) {
                     if (isset($a_missingInCurrentinv[$key.'/'.$keyref])) {
                        $a_onlyinref[$current_itemtype][$current_id][str_replace($base_tree.'/', '', $key.'/'.$keyref)]
                                = $a_ref[$key.'/'.$keyref];
                     }
                  }
               }
            }
         }
      }
//echo "<pre align='left'>";print_r($a_onlyinref);echo "</pre>";
      // Get sections not in ref but in current

      $a_miss_ref = array();
      foreach ($a_missingInRef as $key=>$value) {
         $split = explode('/', $key);
         unset($split[(count($split) - 1)]);
         $a_miss_ref[(implode('/', $split))] = "";
      }

      $current_itemtype = '';
      $current_id = '';
      $base_tree = '';
      foreach ($a_miss_ref as $key=>$value) {
         // Get all elements of the section
         $split = explode('/', $key);
         $list_fields_temp = $list_fields;
         for ($i=1; $i < count($split); $i += 2) {
            $list_fields_temp = $list_fields_temp[$split[$i]];
         }
         if (count($split) <= 3) {
            $current_itemtype = $split[count($split) - 2];
            $current_id = $split[count($split) - 1];
            $base_tree = $key;
         }
         foreach ($list_fields_temp as $keyref=>$valueref) {
            if ($keyref != '_internal_name_'
                 && $keyref != '_itemtype_') {
               if (!is_array($valueref)) {
                  if (isset($a_missingInRef[$key.'/'.$keyref])) {
                        $a_notinref[$current_itemtype][$current_id][str_replace($base_tree.'/', '', $key.'/'.$keyref)]
                                = $a_currentinv[$key.'/'.$keyref];
                  }
               }
            }
         }
      }

      // Check between onlyinref and notinref
      $a_keys_Update_ref = array();
      foreach ($a_onlyinref as $itemtype=>$data) {
         foreach ($data as $keyref=>$dataref) {
            if (isset($a_onlyinref[$itemtype])) {
               foreach ($a_notinref[$itemtype] as $keynotref=>$datanotref) {
                  if ($dataref === $datanotref) {
                     $a_keys_Update_ref[$itemtype.'/'.$keyref] = $keynotref;
                     unset($a_notinref[$itemtype][$keynotref]);
                     unset($a_onlyinref[$itemtype][$keyref]);
                     break;
                  }
               }
            }
         }
      }

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo "What";
      echo "</th>";
      echo "<th>";
      echo "Référentiel";
      echo "</th>";
      echo "<th>";
      echo "valeur trouvée";
      echo "</th>";
      echo "<th>";
      echo "Action";
      echo "</th>";
      echo "</tr>";

      $a_update_sections = array();
      foreach ($a_update as $key=>$value) {
         $split = explode('/', $key);
         unset($split[(count($split) - 1)]);
         $a_update_sections[(implode('/', $split))] = "";
      }

      foreach ($a_update_sections as $key=>$value) {
         // Get all elements of the section
         $split = explode('/', $key);
         $list_fields_temp = $list_fields;
         for ($i=1; $i < count($split); $i += 2) {
            $list_fields_temp = $list_fields_temp[$split[$i]];
         }

         if (count($split) <= 3) {
            echo "<tr class='tab_bg_3'>";
            echo "<td colspan='5'>&nbsp;</td>";
            echo "</tr>";
         }
         echo "<tr class='tab_bg_3'>";
         echo "<td></td>";
         $indent = 0;
         if (count($split) <= 3) {
            echo "<td><strong>ₒ ";
            echo $list_fields_temp['_internal_name_'];
            echo "</strong>";
         } else {
            echo "<td>&nbsp;&nbsp;&nbsp;<strong>ₒ</strong> ";
            echo $list_fields_temp['_internal_name_'];
         }
         if (count($split) > 3) {
            $indent = 4;
         }
         echo "</td>";
         echo "<td colspan='3'>";
         echo "</td>";
         echo "</tr>";

         foreach ($list_fields_temp as $keyref=>$valueref) {
            if ($keyref != '_internal_name_'
                 && $keyref != '_itemtype_') {
               if (!is_array($valueref)) {
                  if (isset($a_update[$key.'/'.$keyref])) {
                     echo "<tr class='tab_bg_3'>";
                     echo "<td></td>";
                     echo "<td>&nbsp;&nbsp;&nbsp;";
                     echo str_repeat("&nbsp;", $indent);
                     echo "<strong>ₒ</strong> ";
                     echo $valueref;
                     echo "</td>";
                     echo "<td style='background-color:#ccffcc'>";
                     echo $a_ref[$key.'/'.$keyref];
                     echo "</td>";
                     echo "<td style='background-color:#ffcccc'>";
                     echo $a_currentinv[$key.'/'.$keyref];
                     echo "</td>";
                     echo "<td>";
                     echo "<input type='submit' class='submit' value='Update the referential' />";
                     echo "</td>";
                     echo "</tr>";
                  } else {
                     echo "<tr class='tab_bg_3'>";
                     echo "<td></td>";
                     echo "<td>&nbsp;&nbsp;&nbsp;";
                     echo str_repeat("&nbsp;", $indent);
                     echo "<strong>ₒ</strong> ";
                     echo $valueref;
                     echo "</td>";
                     echo "<td colspan='2' class='tab_bg_3'>";
                     echo $a_ref[$key.'/'.$keyref];
                     echo "</td>";
                     echo "<td>";
                     echo "</td>";
                     echo "</tr>";
                  }
               }
            }
         }
      }
      foreach ($a_onlyinref as $itemtype=>$data) {
         foreach ($data as $id=>$a_var) {
            echo "<tr class='tab_bg_3'>";
            echo "<td colspan='5'>&nbsp;</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_3'>";
            echo "<td></td>";
            echo "<td><strong>ₒ ";
            echo $itemtype;
            echo "</strong>";
            echo "</td>";
            echo "<td colspan='3'>";
            echo "</td>";
            echo "</tr>";

            foreach ($a_var as $key=>$value) {
               echo "<tr class='tab_bg_3'>";
               echo "<td></td>";
               echo "<td>&nbsp;&nbsp;&nbsp;";
               echo str_repeat("&nbsp;", 4);
               echo "<strong>ₒ</strong> ";
               echo $key;
               echo "</td>";
               echo "<td style='background-color:#ccffcc'>";
               echo $value;
               echo "</td>";
               echo "<td style='background-color:#ffcccc'>";
               echo "</td>";
               echo "<td>";
               echo "</td>";
               echo "</tr>";
            }
         }
      }
      foreach ($a_notinref as $itemtype=>$data) {
         foreach ($data as $id=>$a_var) {
            echo "<tr class='tab_bg_3'>";
            echo "<td colspan='5'>&nbsp;</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_3'>";
            echo "<td></td>";
            echo "<td><strong>ₒ ";
            echo $itemtype;
            echo "</strong>";
            echo "</td>";
            echo "<td colspan='3'>";
            echo "</td>";
            echo "</tr>";

            foreach ($a_var as $key=>$value) {
               echo "<tr class='tab_bg_3'>";
               echo "<td></td>";
               echo "<td>&nbsp;&nbsp;&nbsp;";
               echo str_repeat("&nbsp;", 4);
               echo "<strong>ₒ</strong> ";
               echo $key;
               echo "</td>";
               echo "<td>";
               echo "</td>";
               echo "<td style='background-color:#ffcccc'>";
               echo $value;
               echo "</td>";
               echo "<td>";
               echo "</td>";
               echo "</tr>";
            }
         }
      }

      echo "</table>";

   }



   static function cronCheckdevices() {

      $pfConfigurationManagement = new PluginFusioninventoryConfigurationManagement();
      $a_list = $pfConfigurationManagement->find("`sha_referential` IS NOT NULL");
      foreach ($a_list as $id=>$data) {

         $a_currinv = $pfConfigurationManagement->generateCurrentInventory($id);
         $sha = sha1(exportArrayToDB($a_currinv));

         if ($sha == $data['sha_referential']) {
            $input = array();
            $input['id'] = $id;
            $input['sha_last'] = $sha;
            $input['sentnotification'] = 0;
            $input['conform'] = 1;
            $input['date'] = date('Y-m-d');
            $pfConfigurationManagement->update($input);
         } else if ($sha == $data['sha_last']) {
            // nothing to do, managed last time, only update date
            $input = array();
            $input['id'] = $id;
            $input['date'] = date('Y-m-d');
            $pfConfigurationManagement->update($input);
         } else {
            $input = array();
            $input['id'] = $id;
            $input['sha_last'] = $sha;
            $input['sentnotification'] = 0;
            $input['conform'] = 0;
            $input['date'] = date('Y-m-d');
            $pfConfigurationManagement->update($input);
         }
      }
      // Send emails
   }



   function addToReferential() {

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "Add software :";

      echo "</td>";
      echo "</td>";
      echo "</table>";
   }



   /**
    * This function display synthetic data like:
    *  ~ Number of computers have referential / not conform / not have referential
    *  ~ Number of printers have referential / not conform / not have referential
    *  ~ Number of network equipments have referential / not conform / not have referential
    *  ~
    */
   function dashboard() {
      global $CFG_GLPI;

      echo "<center><h2>Configuration management summary</h2></center>";
      echo "<table class='tab_cadre'>";

      $a_itemtypes = array(
          'Computer',
          'Printer',
          'NetworkEquipment'
      );

      echo "<tr class='tab_bg_1'>";
      foreach ($a_itemtypes as $itemtype) {
         echo "<th>";
         echo $itemtype;
         echo "</th>";
      }
      echo "</tr>";

      echo "<tr>";
      foreach ($a_itemtypes as $itemtype) {
         echo "<td>";
         $this->drawPie(mt_rand(10,80), mt_rand(0,2), 8);
         echo "</td>";
      }
      echo "</tr>";

      echo "</table>";

   }



   function drawPie($havereferential, $notconform, $total) {
      global $CFG_GLPI;

      $colorcircle = 'ececec';
      if ($notconform > 0) {
         $colorcircle = 'ff8f8f';
      }

      echo '<script src="'.$CFG_GLPI["root_doc"].'/plugins/fusioninventory/lib/d3-3.4.3/d3.min.js"></script>';

      $rand = mt_rand();
      echo "<div id='pie".$rand."'></div>";
      echo '<script>

var width = 350,
    height = 150,
    radius = Math.min(width, height) / 2;

var arc = d3.svg.arc()
    .outerRadius(radius - 10)
    .innerRadius(radius - 35);

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) { return d.percentage; });

var svg'.$rand.' = d3.select("#pie'.$rand.'").append("svg")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

';

echo "var jsonstr".$rand." = '".json_encode(array(array('percentage' => $havereferential, 'color' => '#6aff75'),
                                         array('percentage' => (100 - $havereferential), 'color' => '#ececec')))."';
var jsonstrconf".$rand." = '".json_encode(array('notconform' => $notconform, 'total' => $total, 'color' => '#'.$colorcircle))."';
";
echo '
var data'.$rand.' = JSON.parse(jsonstr'.$rand.');
var dataconf'.$rand.' = JSON.parse(jsonstrconf'.$rand.');

  data'.$rand.'.forEach(function(d) {
    d.percentage = +d.percentage;
  });

  var g'.$rand.' = svg'.$rand.'.selectAll(".arc")
      .data(pie(data'.$rand.'))
    .enter();

    g'.$rand.'.append("circle")
       .attr("r", 40)
       .style("stroke", "#bdbdbd")
       .style("fill", function(d) { return dataconf'.$rand.'.color; });

  g'.$rand.'.append("path")
      .attr("d", arc)
      .style("stroke", "#bdbdbd")
      .style("fill", function(d) { return d.data.color; })
      .attr("class", "arc");

  g'.$rand.'.append("text")
      .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
      .attr("dy", ".35em")
      .style("text-anchor", "middle")
      .text(function(d) { return d.data.percentage + "%"; });

    g'.$rand.'.append("text")
      .attr("dy", ".35em")
      .style("text-anchor", "middle")
      .text(function(d) { return dataconf'.$rand.'.notconform + " / " + dataconf'.$rand.'.total; });


</script>';

   }
}

?>