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
// Original Author of file: Alexandre DELAUNAY
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployTask extends CommonDBTM {

   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_fusinvdeploy']['task'][6];
      }
      return $LANG['plugin_fusinvdeploy']['task'][1];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function defineTabs($options=array()) {
      global $LANG;

      $ong = array();

      if ($this->fields['id'] > 0) {
         $ong[4] = $LANG['plugin_fusinvdeploy']['task'][7];
      } elseif ($this->fields['id'] == -1) {
         $ong[2] = $LANG['plugin_fusinvdeploy']['task'][1];
         $ong[3] = $LANG['plugin_fusinvdeploy']['task'][2];
         $ong['no_all_tab']=true;
      } else { // New item
         $ong[1] = $LANG['title'][26];
      }

      return $ong;
   }

   function showMenu($options=array())  {

      $this->displaylist = false;

      $this->fields['id'] = -1;
      $this->showTabs($options);
      $this->addDivForTabs();
   }

   function showList() {
      echo "<table class='tab_cadre_navigation'><tr><td>";

      self::title();
      Search::show('PluginFusinvdeployTask');

      echo "</td></tr></table>";
   }

   function title() {
      global $LANG, $CFG_GLPI;

      $buttons = array();
      $title = $LANG['plugin_fusinvdeploy']['task'][1];

      if ($this->canCreate()) {
         $buttons["task.form.php?new=1"] = $LANG['plugin_fusinvdeploy']['task'][3];
         $title = "";
      }

      displayTitle($CFG_GLPI["root_doc"] . "/plugins/fusinvdeploy/pics/task.png", $title, $title, $buttons);
   }

   function showForm($ID, $options = array()) {
      global $LANG;

      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
      }

      $options['colspan'] = 1;

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<textarea cols='40' rows='6' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      //load extjs plugins library
      echo "<script type='text/javascript'>";
      //require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/XmlTreeLoader.js";
      echo "</script>";

      return true;
   }

   function showActions($id) {
      global $LANG, $CFG_GLPI;

       echo "<table class='deploy_extjs'>
         <thead>
            <tr>
               <th colspan='2'>
                  ".$LANG['plugin_fusinvdeploy']['task'][5]."
                  <a href=\"javascript:showHideDiv('Task','taskimg',
                     '".$CFG_GLPI["root_doc"]."/pics/deplier_down.png',
                     '".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">
                  <img alt='' name='taskimg'
                     src='".$CFG_GLPI["root_doc"]."/pics/deplier_up.png'>
               </th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td id='TaskJob'>
               </td>
            </tr>
         </tbody>
      </table>";

      // Include JS
      require GLPI_ROOT."/plugins/fusinvdeploy/js/task_job.front.php";
   }

}
