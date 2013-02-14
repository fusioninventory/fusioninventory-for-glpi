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

class PluginFusioninventoryDeployPackage extends CommonDBTM {

   static function getTypeName($nb=0) {

      return __('Packages', 'fusioninventory');

   }

   static function canCreate() {
      return TRUE;
   }

   static function canView() {
      return TRUE;
   }



   function defineTabs($options=array()) {

      $ong = array();
      if ($this->fields['id'] > 0){
         $this->addStandardTab('PluginFusioninventoryDeployInstall', $ong, $options);
         $this->addStandardTab('PluginFusioninventoryDeployUninstall', $ong, $options);
      }
      $ong['no_all_tab'] = TRUE;
      return $ong;
   }



   function getSearchOptions() {

      $tab = array();
      $tab['common']           = __('Characteristics');

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['linkfield']     = 'name';
      $tab[1]['name']          = __('Name');
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_link'] = $this->getType();

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'id';
      $tab[2]['linkfield'] = '';
      $tab[2]['name']      = __('ID');

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['linkfield'] = 'comment';
      $tab[16]['name']      = __('Comments');
      $tab[16]['datatype']  = 'text';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = __('Last update');
      $tab[19]['datatype']  = 'datetime';

      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['linkfield'] = 'entities_id';
      $tab[80]['name']      = __('Entity');

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['linkfield'] = 'is_recursive';
      $tab[86]['name']      = __('Child entities');
      $tab[86]['datatype']  = 'bool';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = __('Last update');
      $tab[19]['datatype']  = 'datetime';

      return $tab;
   }



   function post_addItem() {
      //check whether orders have not already been created
      if (!isset($_SESSION['tmp_clone_package'])) {
         //Create installation & uninstallation order
         PluginFusioninventoryDeployOrder::createOrders($this->fields['id']);
      }
   }



   function cleanDBonPurge() {
      global $DB;
      
      $query = "DELETE FROM `glpi_plugin_fusioninventory_deployorders`
                WHERE `plugin_fusioninventory_deploypackages_id`=".$this->fields['id'];
      $DB->query($query);
   }



   function title() {
      global $CFG_GLPI;

      $buttons = array();
      $title = __('Packages', 'fusioninventory');


      if ($this->canCreate()) {
         $buttons["deploypackage.form.php?new=1"] = __('Add a package', 'fusioninventory');

         $title = "";
      }

      Html::displayTitle($CFG_GLPI['root_doc'].
                           "/plugins/fusioninventory/pics/menu_mini_package.png", 
                         $title, $title, $buttons);
   }



   function showMenu($options=array()) {

      $this->displaylist = FALSE;

      $this->fields['id'] = -1;
      $this->showList();
   }



   function showList() {
      self::title();
      Search::show('PluginFusioninventoryDeployPackage');
   }



   function showForm($ID, $options=array()) {
      global $CFG_GLPI;


      if ($ID > 0) {
         $this->check($ID, 'r');
      } else {
         $this->check(-1, 'w');
         $this->getEmpty();
      }

      $options['colspan'] = 2;
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td>".__('Comments')."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<textarea cols='40' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      if (!PluginFusioninventoryDeployPackage::canEdit($ID)) {
         PluginFusioninventoryDeployPackage::showEditDeniedMessage($ID,
               __('One or more active tasks (#task#) use this package. Edition denied.',
                  'fusioninventory'));

      }


      //drag and drop lib
      echo "<script type='text/javascript' src='".$CFG_GLPI["root_doc"].
         "/plugins/fusioninventory/lib/REDIPS_drag/redips-drag-source.js'></script>";
     

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return TRUE;
   }

   
   
   static function displayOrderTypeForm($order_type, $packages_id) {
      global $CFG_GLPI;

      $subtypes = array(
         'check'  => __("Audits", 'fusioninventory'),
         'file'  => __("Files", 'fusioninventory'),
         'action' => __("Actions", 'fusioninventory')
      );
      $rand = mt_rand();

      $o_order = new PluginFusioninventoryDeployOrder;
      $found = $o_order->find("plugin_fusioninventory_deploypackages_id = $packages_id 
                               AND type = $order_type");
      $order = array_shift($found);
      $datas = json_decode($order['json'], TRUE);
      $orders_id = $order['id'];

      
      echo "<table class='tab_cadre_fixe' id='package'>";

      $multipart = "";
      foreach ($subtypes as $subtype => $label) {

         echo "<tr>";
         echo "<th id='th_title_{$subtype}_$rand'>";
         echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/$subtype.png' />";
         echo "&nbsp;".__($label, 'fusioninventory');
         self::plusButton($subtype."s_block$rand");
         echo "</th>";
         echo "</tr>";


         if ($subtype == "file") {
            $multipart = "enctype='multipart/form-data'";
         }
         echo "<tr>";
         echo "<td style='vertical-align:top'>";
         echo "<form name='add$subtype' method='post' ".$multipart.
            " action='deploypackage.form.php'>";
         echo "<input type='hidden' name='orders_id' value='$orders_id' />";
         echo "<input type='hidden' name='itemtype' value='PluginFusioninventoryDeploy".
            ucfirst($subtype)."' />";
         $classname = "PluginFusioninventoryDeploy".ucfirst($subtype);
         $classname::displayForm($orders_id, $datas, $rand);
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";

      //init drag and drop on subtype table
      echo "<script type='text/javascript'>
         var rand = $rand;
         if (orders == null) var orders = {};
         orders[$rand] = $orders_id;
         </script>";
       echo "<script type='text/javascript' src='".$CFG_GLPI["root_doc"].
         "/plugins/fusioninventory/lib/REDIPS_drag/drag_table_rows.js'></script>";


      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE) {
         // === debug ===
         echo "<span class='red'><b>DEBUG</b></span>";
         echo "<form action='".$CFG_GLPI["root_doc"].
         "/plugins/fusioninventory/front/deploypackage.form.php' method='POST'>";
         echo "<textarea cols='132' rows='25' style='border:0' name='json'>";
         echo json_encode($datas, JSON_PRETTY_PRINT);
         echo "</textarea>";
         echo "<input type='hidden' name='id' value='$orders_id' />";
         echo "<input type='submit' name='update_json' value=\"".
            _sx('button','Save')."\" class='submit'>";
         Html::closeForm();
         // === debug ===
      }
   }

   
   
   static function alter_json($action_type, $params) {
      //route to sub class
      if (in_array($params['itemtype'], array(
         'PluginFusioninventoryDeployCheck',
         'PluginFusioninventoryDeployFile',
         'PluginFusioninventoryDeployAction'
      ))) {
         switch ($action_type) {
            case "add_item" : 
               $params['itemtype']::add_item($params);
               break;
            case "save_item" : 
               $params['itemtype']::save_item($params);
               break;
            case "remove_item" : 
               $params['itemtype']::remove_item($params);
               break;
            case "move_item" : 
               $params['itemtype']::move_item($params);
               break;
         }
      } else {
         Html::displayErrorAndDie ("package subtype not found");
      }
   }

   
   
   static function plusButton($dom_id, $clone = FALSE) {
      global $CFG_GLPI;

      echo "&nbsp;<img id='plus_$dom_id' onClick='return plusbutton$dom_id()'
                 title='".__('Add')."' alt='".__('Add')."'
                 class='pointer' src='".$CFG_GLPI["root_doc"]."/pics/add_dropdown.png'>";
      //This should lie in a libjs file instead hardcoded
      echo "<script type='text/javascript>";
      echo "function plusbutton$dom_id() {";
        
      if ($clone !== FALSE) {
         echo "
         var root=document.getElementById('$dom_id');
         if (root.style.display == 'block') {
            var clone=root.getElementsByTagName('$clone')[0].cloneNode(true);
            root.appendChild(clone);
            clone.style.display = 'block';
         }
         ";
      }
      echo "
          Ext.get('".$dom_id."').setDisplayed('block');
      }</script>";
   }

   
   
   function getAllDatas() {
      global $DB;

      $sql = " SELECT id, name
               FROM `".$this->getTable()."`
               ORDER BY name";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      $i = 0;
      while($row = $DB->fetch_assoc($res)) {
         $json['packages'][$i]['package_id'] = $row['id'];
         $json['packages'][$i]['package_name'] = $row['name'];

         $i++;
      }
      $json['results'] = $nb;

      return json_encode($json);
   }



   static function canEdit($id) {

      $taskjobs_a = getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobs',
               "definition LIKE '%\"PluginFusioninventoryDeployPackage\":\"".$id."%'");

      foreach ($taskjobs_a as $job) {
         $task = new PluginFusioninventoryTask;
         $task->getFromDB($job['plugin_fusioninventory_tasks_id']);
         if ($task->getField('is_active') == 1) {
            return FALSE;
         }
      }
      return TRUE;
   }



   function pre_deleteItem() {
      global  $CFG_GLPI;

      //if task use this package, delete denied
      if (!self::canEdit($this->getField('id'))) {
         $task = new PluginFusioninventoryDeployTask;
         $tasks_url = "";
         $taskjobs = getAllDatasFromTable('glpi_plugin_fusioninventory_deploytaskjobs',
                  "definition LIKE '%\"PluginFusioninventoryDeployPackage\":\"".
                  $this->getField('id')."%'");
         foreach($taskjobs as $job) {
            $task->getFromDB($job['plugin_fusioninventory_deploytasks_id']);
            $tasks_url .= "<a href='".$CFG_GLPI["root_doc"].
                           "/plugins/fusioninventory/front/task.form.php?id=".
                           $job['plugin_fusioninventory_deploytasks_id']."'>".
                           $task->fields['name']."</a>, ";
         }
         $tasks_url = substr($tasks_url, 0, -2);


         Session::addMessageAfterRedirect(str_replace('#task#', $tasks_url, 
                     __('One or more active tasks (#task#) use this package. Deletion denied.', 
                        'fusioninventory')));

         Html::redirect($CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/task.form.php?id="
               .$this->getField('id'));
         return FALSE;
      }

      return TRUE;
   }

   
   
   public function package_clone($new_name = '') {

      if ($this->getField('id') < 0) {
         return FALSE;
      }

      $_SESSION['tmp_clone_package'] = TRUE;

      //duplicate package
      $package_oldId = $this->getField('id');
      if ($new_name == "") {
         $new_name = $this->getField('name');
      }
      $params = $this->fields;
      unset($params['id']);
      $params['name'] = $new_name;
      $new_package = new PluginFusioninventoryDeployPackage;
      $package_newId = $new_package->add($params);

      //duplicate orders
      $order_obj = new PluginFusioninventoryDeployOrder;
      $orders = $order_obj->find("plugin_fusioninventory_deploypackages_id = '".$package_oldId."'");

      foreach($orders as $order_oldId => $order) {
         //create new order for this new package
         $order_param = array(
            'type' => $order['type'],
            'create_date' => date("Y-m-d H:i:s"),
            'plugin_fusioninventory_deploypackages_id' => $package_newId
         );
         $order_newId = $order_obj->add($order_param);
         unset($order_param);


         //duplicate checks
         $check_obj = new PluginFusioninventoryDeployCheck;
         $checks = $check_obj->find("plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($checks as $check) {
            //create new check for this new order
            unset($check['id']);
            $check['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $check_obj->add($check);
         }

         //duplicate files
         $file_obj = new PluginFusioninventoryDeployFile;
         $files = $file_obj->find("plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($files as $file) {
            //create new file for this new order
            unset($file['id']);
            $file['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $file_newId = $file_obj->add($file);

            //duplicate fileparts
            $filepart_obj = new PluginFusioninventoryDeployFilepart;
            $fileparts = $filepart_obj->find(
               "plugin_fusioninventory_deployfiles_id = '".$order_oldId."'");
            foreach ($fileparts as $filepart) {
               //create new filepart for this new file
               unset($filepart['id']);
               $filepart['plugin_fusioninventory_deployorders_id'] = $order_newId;
               $filepart['plugin_fusioninventory_deployfiles_id'] = $file_newId;
               $filepart_obj->add($filepart);
            }
         }

         //duplicate actions
         $action_obj = new PluginFusioninventoryDeployAction;
         $actions = $action_obj->find(
            "plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($actions as $action) {
            //duplicate actions subitem
            $action_subitem_obj = new $action['itemtype'];
            $action_subitem_oldId = $action['items_id'];
            $action_subitem_obj->getFromDB($action_subitem_oldId);
            $params_subitem = $action_subitem_obj->fields;
            unset($params_subitem['id']);
            $action_subitem_newId = $action_subitem_obj->add($params_subitem);

            //special case for command, we need to duplicate commandstatus and commandenvvariables
            if ($action['itemtype'] == 'PluginFusioninventoryDeployAction_Command') {
               $command_oldId = $action_subitem_oldId;
               $command_newId = $action_subitem_newId;

               //duplicate commandstatus
               $commandstatus_obj = new PluginFusioninventoryDeployAction_Commandstatus;
               $commandstatus = $commandstatus_obj->find(
                  "plugin_fusioninventory_deploycommands_id = '".$command_oldId."'");
               foreach ($commandstatus as $commandstate) {
                  //create new commandstatus for this command
                  unset($commandstate['id']);
                  $commandstate['plugin_fusioninventory_deploycommands_id'] = $command_newId;
                  $commandstatus_obj->add($commandstate);
               }

               //duplicate commandenvvariables
               $commandenvvariables_obj = new PluginFusioninventoryDeployAction_Commandenvvariable;
               $commandenvvariables = $commandenvvariables_obj->find(
                  "plugin_fusioninventory_deploycommands_id = '".$command_oldId."'");
               foreach ($commandenvvariables as $commandenvvariable) {
                  //create new commandenvvariable for this command
                  unset($commandenvvariable['id']);
                  $commandenvvariable['plugin_fusioninventory_deploycommands_id'] = $command_newId;
                  $commandenvvariables_obj->add($commandenvvariable);
               }
            }

            //create new action for this new order
            unset($action['id']);
            $action['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $action['items_id'] = $action_subitem_newId;
            $action_obj->add($action);
         }
      }

      if (($name=$new_package->getName()) == NOT_AVAILABLE) {
         $new_package->fields['name'] = $new_package->getTypeName()." : ".__('ID')

                                 ." ".$new_package->fields['id'];
      }
      $display = (isset($this->input['_no_message_link'])?$new_package->getNameID()
                                                         :$new_package->getLink());

      // Do not display quotes
      Session::addMessageAfterRedirect(__('Item successfully added', 'fusioninventory')."&nbsp;: ".
                                       stripslashes($display));

      unset($_SESSION['tmp_clone_package']);

      //exit;

   }



   static function showEditDeniedMessage($id, $message) {
      global $CFG_GLPI, $CFG_GLPI;

      $task = new PluginFusioninventoryDeployTask;
      $tasks_url = "";

      $taskjobs = getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobs',
               "definition LIKE '%\"PluginFusioninventoryDeployPackage\":\"".$id."%'");

      # A task can have more than one taskjobs is an Install and Uninstall function are associated
      # to the same tasks
      $jobs_seen = array();
      foreach($taskjobs as $job) {
         if (isset($jobs_seen[$job['plugin_fusioninventory_tasks_id']])) {
            continue;
         }
         $task->getFromDB($job['plugin_fusioninventory_tasks_id']);
         $tasks_url .= "<a href='".$CFG_GLPI["root_doc"].
                     "/plugins/fusioninventory/front/task.form.php?id="
                     .$job['plugin_fusioninventory_tasks_id']."'>".
                     $job['name']."</a>, ";
         $jobs_seen[$job['plugin_fusioninventory_tasks_id']]=1;
      }
      $tasks_url = substr($tasks_url, 0, -2);

      //show edition denied message
      echo "<div class='box' style='margin-bottom:20px;'>";
      echo "<div class='box-tleft'><div class='box-tright'><div class='box-tcenter'>";
      echo "</div></div></div>";
      echo "<div class='box-mleft'><div class='box-mright'><div class='box-mcenter'>";
      echo str_replace('#task#', $tasks_url, $message);
      echo "</div></div></div>";
      echo "<div class='box-bleft'><div class='box-bright'><div class='box-bcenter'>";
      echo "</div></div></div>";
      echo "</div>";

   }
}

?>
