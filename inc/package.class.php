<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployPackage extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][8];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      if ($this->fields['id'] > 0){
         //$ong[1]  = $LANG['plugin_fusinvdeploy']['package'][5];
         $ong[2]  = $LANG['plugin_fusinvdeploy']['package'][14];
         $ong[3]  = $LANG['plugin_fusinvdeploy']['package'][15];
         $ong['no_all_tab'] = true;

      }
      return $ong;
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common']           = $LANG['common'][32];;

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['linkfield']     = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_link'] = $this->getType();

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'id';
      $tab[2]['linkfield'] = '';
      $tab[2]['name']      = $LANG['common'][2];

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['linkfield'] = 'comment';
      $tab[16]['name']      = $LANG['common'][25];
      $tab[16]['datatype']  = 'text';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = $LANG['common'][26];
      $tab[19]['datatype']  = 'datetime';

      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['linkfield'] = 'entities_id';
      $tab[80]['name']      = $LANG['entity'][0];

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['linkfield'] = 'is_recursive';
      $tab[86]['name']      = $LANG['entity'][9];
      $tab[86]['datatype']  = 'bool';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = $LANG['common'][26];
      $tab[19]['datatype']  = 'datetime';

      return $tab;
   }

   function post_addItem() {
      //Create installation & uninstallation order
      PluginFusinvdeployOrder::createOrders($this->fields['id']);
   }

   function cleanDBonPurge() {
      PluginFusinvdeployOrder::cleanForPackage($this->fields['id']);
   }

   function showForm($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $options['colspan'] = 2;
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<textarea cols='40' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();
      </script>";

      //load extjs plugins library
      echo "<script type='text/javascript'>";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/FileUploadField.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/Spinner.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/SpinnerField.js";
      echo "</script>";

      return true;
   }

   function getAllDatas()  {
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
      global $DB;

      if (count(getAllDatasFromTable('glpi_plugin_fusinvdeploy_taskjobs',
               "definition LIKE '%\"PluginFusinvdeployPackage\":\"".$id."%'")) > 0) return false;
      return true;
   }

   function pre_deleteItem() {
      global $LANG, $CFG_GLPI;

      //if task use this package, delete denied
      if (!self::canEdit($this->getField('id'))) {
         $task = new PluginFusinvdeployTask;
         $tasks_url = "";
         $taskjobs = getAllDatasFromTable('glpi_plugin_fusinvdeploy_taskjobs',
                  "definition LIKE '%\"PluginFusinvdeployPackage\":\"".$this->getField('id')."%'");
         foreach($taskjobs as $job) {
            $task->getFromDB($job['plugin_fusinvdeploy_tasks_id']);
            $tasks_url .= "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/task.form.php?id="
                  .$job['plugin_fusinvdeploy_tasks_id']."'>".$task->fields['name']."</a>, ";
         }
         $tasks_url = substr($tasks_url, 0, -2);


         addMessageAfterRedirect(str_replace('#task#',
               $tasks_url, $LANG['plugin_fusinvdeploy']['package'][23]));
         glpi_header(GLPI_ROOT."/plugins/fusinvdeploy/front/package.form.php?id="
               .$this->getField('id'));
         return false;
      }

      return true;
   }

   public static function showEditDeniedMessage($id, $message) {
      global $CFG_GLPI, $CFG_GLPI;

      $task = new PluginFusinvdeployTask;
      $tasks_url = "";
      $taskjobs = getAllDatasFromTable('glpi_plugin_fusinvdeploy_taskjobs',
               "definition LIKE '%\"PluginFusinvdeployPackage\":\"".$id."%'");
      foreach($taskjobs as $job) {
         $task->getFromDB($job['plugin_fusinvdeploy_tasks_id']);
         $tasks_url .= "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/task.form.php?id="
               .$job['plugin_fusinvdeploy_tasks_id']."'>".$task->fields['name']."</a>, ";
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
