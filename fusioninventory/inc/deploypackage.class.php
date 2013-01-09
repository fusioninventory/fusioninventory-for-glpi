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

      return __('Packages');

   }

   static function canCreate() {
      return true;
   }

   static function canView() {
      return true;
   }



   function defineTabs($options=array()) {

      $ong = array();
      if ($this->fields['id'] > 0){
         $this->addStandardTab('PluginFusioninventoryDeployInstall', $ong, $options);
         $this->addStandardTab('PluginFusioninventoryDeployUninstall', $ong, $options);
      }
      $ong['no_all_tab'] = true;
      return $ong;
   }



   function getSearchOptions() {

      $tab = array();
      $tab['common']           = __('Characteristics');;


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
      PluginFusioninventoryDeployOrder::cleanForPackage($this->fields['id']);
   }



   function title() {

      $buttons = array();
      $title = __('Packages');


      if ($this->canCreate()) {
         $buttons["deploypackage.form.php?new=1"] = __('Add a package');

         $title = "";
      }

      Html::displayTitle(GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_package.png", 
                         $title, $title, $buttons);
   }



   function showMenu($options=array()) {

      $this->displaylist = false;

      $this->fields['id'] = -1;
      $this->showList();
   }



   function showList() {
      echo "<center>";
      echo "<table class='tab_cadre_navigation'><tr><td>";

      self::title();
      Search::show('PluginFusioninventoryDeployPackage');

      echo "</td></tr></table>";
      echo "</center>";
   }



   function showForm($ID, $options=array()) {
      global $DB, $CFG_GLPI;


      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         $this->check(-1,'w');
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

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }

   static function showOrderTypeForm($order_type, $packages_id) {
      $disabled = "false";
      if (!PluginFusioninventoryDeployPackage::canEdit($id)) {
         $disabled = "true";
         PluginFusioninventoryDeployPackage::showEditDeniedMessage($id,
               __('One or more active tasks (#task#) use this package. Edition denied.',
                  'fusioninventory'));

      }

      $o_order = new PluginFusioninventoryDeployOrder;
      $found = $o_order->find("plugin_fusioninventory_deploypackages_id = $packages_id 
                               AND type = $order_type");
      $order = array_shift($found);

      echo $order['json'];
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
         if ($task->getField('is_active') == 1) return false;
      }
      return true;
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
                     __('One or more active tasks (#task#) use this package. Deletion denied.')));

         Html::redirect(GLPI_ROOT."/plugins/fusioninventory/front/task.form.php?id="
               .$this->getField('id'));
         return false;
      }

      return true;
   }



   static function import_json($data = NULL) {

      if($data !== NULL) {

         $d_package = $data->package;
         $d_orders = array(
            PluginFusioninventoryDeployOrder::INSTALLATION_ORDER => $data->install,
            PluginFusioninventoryDeployOrder::UNINSTALLATION_ORDER => $data->uninstall
         );
         //Create Package
         $o_package = new PluginFusioninventoryDeployPackage();
         $i_package = array();
         $i_package['name'] = $d_package->name;
         $i_package['comment'] = $d_package->comment;
         $i_package['entities_id'] = $_SESSION['glpiactive_entity'];
         $i_package['is_recursive'] = $d_package->is_recursive;
         $i_package['date_mod'] = $d_package->date_mod;

         if ($o_package->add($i_package)) {

            //Create Orders(Install/Uninstall)
            $o_order = new PluginFusioninventoryDeployOrder();
            foreach( $d_orders as $order_type => $order_data) {
               //Find Orders created by Package object
               $orders = $o_order->find(
                  "`type` = " . $order_type .
                  " AND `plugin_fusioninventory_deploypackages_id` = " . $o_package->fields['id'],
                  "",
                  "1"
               );

               if ( count($orders) == 1 ) {
                  $order = current($orders);
                  $order_id = $order['id'];
               }
               //Don't go further if there is no order
               if( isset($order_id) && $o_order->getFromDB($order_id)) {

                  //Create Checks
                  foreach( $order_data->checks as $check_idx => $d_check) {
                     //logDebug("checks debug:\n" . $check_idx ."\n" . print_r($d_check,true)."\n");
                     $o_check = new PluginFusioninventoryDeployCheck();
                     $i_check = array();
                     $i_check['type'] = mysql_real_escape_string($d_check->{'type'});
                     $i_check['path'] = mysql_real_escape_string($d_check->{'path'});
                     if ( isset( $d_check->{'value'} ) )
                        $i_check['value'] = mysql_real_escape_string($d_check->{'value'});
                     else
                        $i_check['value'] = '';
                     if (  $i_check['type'] == "fileSizeGreater" ||
                           $i_check['type'] == "fileSizeLower" ||
                           $i_check['type'] == "fileSizeEquals" ) {
                        # according to the requirement, We want Bytes!
                        $i_check['value'] /= 1024 * 1024;
                     }
                     $i_check['ranking'] = $check_idx;
                     $i_check['plugin_fusioninventory_deployorders_id'] = $o_order->fields['id'];
                     //logDebug(print_r($i_check,true));
                     $o_check->add($i_check);
                  }

                  //Create Files
                  //TODO(&COMMENTS): During import, 
                  //associatedFiles should be retrieved from DB and rehashed if
                  //they don't exist. This is the Order who should have a reference to the file and
                  //not the opposite!!!!
                  foreach( $order_data->associatedFiles as $file_idx => $d_file) {
                     $o_file = new PluginFusioninventoryDeployFile();
                     //logDebug('file_idx : ' . $file_idx);
                     $i_file = array();
                     $i_file['name'] = $d_file->{'name'};
                     $i_file['uncompress'] = $d_file->{'uncompress'};
                     $i_file['is_p2p'] = $d_file->{'p2p'};
                     $i_file['p2p_retention_days'] = $d_file->{'p2p-retention-duration'}/(24*3600);
                     $i_file['mimetype'] = $d_file->{'mimetype'};
                     $i_file['create_date'] = $d_file->{'create_date'};
                     $i_file['filesize'] = $d_file->{'filesize'};
                     $i_file['sha512'] = $file_idx;
                     $i_file['shortsha512'] = substr($file_idx,0,6);

                     $i_file['plugin_fusioninventory_deployorders_id'] = $o_order->fields['id'];
                     $o_file->add($i_file);

                     //Attach Multipart
                     foreach( $d_file->multiparts as $part) {
                        $o_filepart = new PluginFusioninventoryDeployFilepart();
                        //logDebug("File Part : " . print_r($part,true) . "\n");
                        $i_filepart = array();
                        $i_filepart['sha512'] = $part;
                        $i_filepart['shortsha512'] = substr($part, 0, 6);
                        $i_filepart['plugin_fusioninventory_deployorders_id'] = $o_order->fields['id'];
                        $i_filepart['plugin_fusioninventory_deployfiles_id']  = $o_file->fields['id'];
                     }
                  }

                  //Create Actions
                  foreach( $order_data->actions as $action_idx => $action ) {
                     //logDebug("actions Debug:\n".$action_idx . "\n".print_r($action,true) . "\n");
                     //logDebug("actions properties .print_r(array_keys(get_object_vars($action)),true));
                     $o_action = new PluginFusioninventoryDeployAction();
                     $i_action = array();
                     $i_action['plugin_fusioninventory_deployorders_id'] = $o_order->fields['id'];
                     $o_action->add($i_action);

                     $d_action_props = array_keys(get_object_vars($action));

                     if ($d_action_props !== NULL && !empty($d_action_props) ) {
                        $d_action_sub = $action->{$d_action_props[0]};
                        switch($d_action_props[0]) {
                           case 'cmd':
                              $o_action_sub = new PluginFusioninventoryDeployAction_Command();
                              $i_action_sub = array();
                              $i_action_sub['exec'] = mysql_real_escape_string($d_action_sub->{'exec'});
                              $o_action_sub->add($i_action_sub);
                              if ( isset($d_action_sub->{'retChecks'}) 
                                    && !empty($d_action_sub->{'retChecks'}) ){
                                 # Create CommandStatus
                                 foreach( $d_action_sub->{'retChecks'} as $retcheck_idx => $d_retcheck ) {
                                    $o_retcheck = new PluginFusioninventoryDeployAction_Commandstatus();
                                    $i_retcheck = array();
                                    switch( $d_retcheck->{'type'} ) {
                                       case 'okCode':
                                          $i_retcheck['type'] = 'RETURNCODE_OK';
                                          break;
                                       case 'errorCode':
                                          $i_retcheck['type'] = 'RETURNCODE_KO';
                                          break;
                                       case 'okPattern':
                                          $i_retcheck['type'] = 'REGEX_OK';
                                          break;
                                       case 'errorPattern':
                                          $i_retcheck['type'] = 'REGEX_KO';
                                          break;
                                    }
                                    $i_retcheck['value'] = $d_retcheck->{'values'}[0];
                                    $i_retcheck['plugin_fusioninventory_deploycommands_id'] = $o_action->fields['id'];
                                    //logDebug("DEBUG Command Status : " . print_r($i_retcheck,true));
                                    $o_retcheck->add($i_retcheck);
                                 }
                              }
                              break;
                           case 'delete':
                              $o_action_sub = new PluginFusioninventoryDeployAction_Delete();
                              $i_action_sub = array();
                              $i_action_sub['path'] = mysql_real_escape_string($d_action_sub->{'list'}[0]);
                              $o_action_sub->add($i_action_sub);
                              break;
                           case 'move':
                              $o_action_sub = new PluginFusioninventoryDeployAction_Move();
                              $i_action_sub = array();
                              $i_action_sub['from'] = mysql_real_escape_string( $d_action_sub->{'from'} );
                              $i_action_sub['to'] = mysql_real_escape_string( $d_action_sub->{'to'} );
                              $o_action_sub->add($i_action_sub);
                              break;
                           case 'copy':
                              $o_action_sub = new PluginFusioninventoryDeployAction_Copy();
                              $i_action_sub = array();
                              $i_action_sub['from'] = mysql_real_escape_string( $d_action_sub->{'from'} );
                              $i_action_sub['to'] = mysql_real_escape_string( $d_action_sub->{'to'} );
                              $o_action_sub->add($i_action_sub);
                              break;
                           case 'mkdir':
                              $o_action_sub = new PluginFusioninventoryDeployAction_Mkdir();
                              $i_action_sub = array();
                              $i_action_sub['path'] = mysql_real_escape_string($d_action_sub->{'list'}[0]);
                              $o_action_sub->add($i_action_sub);
                              break;
                        }
                     }
                     $i_action = array();
                     $i_action['id'] = $o_action->fields['id'];
                     $i_action['itemtype'] = get_class($o_action_sub) ;
                     $i_action['items_id'] = $o_action_sub->fields['id'];
                     $i_action['ranking'] = $action_idx;
                     $i_action['plugin_fusioninventory_deployorders_id'] = $o_order->fields['id'];
                     $o_action->update($i_action);
                  }
               }
            }
         }
      }
   }



   public function package_clone($new_name = '') {

      if ($this->getField('id') < 0) return false;

      $_SESSION['tmp_clone_package'] = true;

      //duplicate package
      $package_oldId = $this->getField('id');
      if ($new_name == "") $new_name = $this->getField('name');
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
         foreach ($checks as $check_oldId => $check) {
            //create new check for this new order
            unset($check['id']);
            $check['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $check_newId = $check_obj->add($check);
         }

         //duplicate files
         $file_obj = new PluginFusioninventoryDeployFile;
         $files = $file_obj->find("plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($files as $file_oldId => $file) {
            //create new file for this new order
            unset($file['id']);
            $file['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $file_newId = $file_obj->add($file);

            //duplicate fileparts
            $filepart_obj = new PluginFusioninventoryDeployFilepart;
            $fileparts = $filepart_obj->find(
               "plugin_fusioninventory_deployfiles_id = '".$order_oldId."'");
            foreach ($fileparts as $filepart_oldId => $filepart) {
               //create new filepart for this new file
               unset($filepart['id']);
               $filepart['plugin_fusioninventory_deployorders_id'] = $order_newId;
               $filepart['plugin_fusioninventory_deployfiles_id'] = $file_newId;
               $filepart_newId = $filepart_obj->add($filepart);
            }
         }

         //duplicate actions
         $action_obj = new PluginFusioninventoryDeployAction;
         $actions = $action_obj->find(
            "plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($actions as $action_oldId => $action) {
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
               foreach ($commandstatus as $commandstatus_oldId => $commandstate) {
                  //create new commandstatus for this command
                  unset($commandstate['id']);
                  $commandstate['plugin_fusioninventory_deploycommands_id'] = $command_newId;
                  $commandstatus_newId = $commandstatus_obj->add($commandstate);
               }

               //duplicate commandenvvariables
               $commandenvvariables_obj = new PluginFusioninventoryDeployAction_Commandenvvariable;
               $commandenvvariables = $commandenvvariables_obj->find(
                  "plugin_fusioninventory_deploycommands_id = '".$command_oldId."'");
               foreach ($commandenvvariables as $commandenvvariable_oldId => $commandenvvariable) {
                  //create new commandenvvariable for this command
                  unset($commandenvvariable['id']);
                  $commandenvvariable['plugin_fusioninventory_deploycommands_id'] = $command_newId;
                  $commandenvvariable_newId = $commandenvvariables_obj->add($commandenvvariable);
               }
            }

            //create new action for this new order
            unset($action['id']);
            $action['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $action['items_id'] = $action_subitem_newId;
            $action_newId = $action_obj->add($action);
         }
      }

      if (($name=$new_package->getName()) == NOT_AVAILABLE) {
         $new_package->fields['name'] = $new_package->getTypeName()." : ".__('ID')

                                 ." ".$new_package->fields['id'];
      }
      $display = (isset($this->input['_no_message_link'])?$new_package->getNameID()
                                                         :$new_package->getLink());

      // Do not display quotes
      Session::addMessageAfterRedirect(__('Item successfully added')."&nbsp;: ".
                                       stripslashes($display));

      unset($_SESSION['tmp_clone_package']);

      //exit;

   }



   static function showEditDeniedMessage($id, $message) {
      global $CFG_GLPI, $CFG_GLPI;

      $task = new PluginFusioninventoryDeployTask;
      $tasks_url = "";

      $taskjobs = getAllDatasFromTable('glpi_plugin_fusioninventory_deploytaskjobs',
               "definition LIKE '%\"PluginFusioninventoryDeployPackage\":\"".$id."%'");

      # A task can have more than one taskjobs is an Install and Uninstall function are associated
      # to the same tasks
      $jobs_seen = array();
      foreach($taskjobs as $job) {
         if (isset($jobs_seen[$job['plugin_fusioninventory_deploytasks_id']])) {
            continue;
         }
         $task->getFromDB($job['plugin_fusioninventory_deploytasks_id']);
         $tasks_url .= "<a href='".$CFG_GLPI["root_doc"].
                     "/plugins/fusioninventory/front/task.form.php?id="
                     .$job['plugin_fusioninventory_deploytasks_id']."'>".
                     $task->fields['name']."</a>, ";
         $jobs_seen[$job['plugin_fusioninventory_deploytasks_id']]=1;
      }
      $tasks_url = substr($tasks_url, 0, -2);

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
