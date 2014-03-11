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
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

class PluginFusioninventoryDeployGroup_Dynamicdata extends CommonDBTM{


   static function canCreate() {
      return TRUE;
   }

   static function canView() {
      return TRUE;
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->fields['type'] == 'DYNAMIC') {
         return __('Dynamic group', 'fusioninventory');
      }
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      self::showForGroup($item);
   }

   /**
    * Display dynamic search form
    *
    * @global type $DB
    * @global type $CFG_GLPI
    *
    * @return boolean
    */

   static function showForGroup(PluginFusioninventoryDeployGroup $group) {
      global $DB, $CFG_GLPI;

      $p = array();
      
      // load saved criterias
      //$p['criteria'] = $this->getCriteria();
      //$p['metacriteria'] = $this->getMetaCriteria();

      //manage sessions
      $glpisearch_session = $_SESSION['glpisearch'];
      unset($_SESSION['glpisearch']);

      $params = Search::manageParams('Computer', $_GET);
      Search::showGenericSearch('Computer', $params);

      /*
      $pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();

      $ID = $group->getID();

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_deploygroups_dynamicdatas`
         WHERE `groups_id`='".$ID."'
         LIMIT 1";
      $result=$DB->query($query);
      $plugin_fusioninventory_deploygroup_dynamicdatas_id = 0;

      if ($DB->numrows($result) == 1) {
         $data = $DB->fetch_assoc($result);
         $plugin_fusioninventory_deploygroup_dynamicdatas_id = $data['id'];
      } else {
         $input = array();
         $input['groups_id'] = $ID;
         $input['fields_array'] = exportArrayToDB(array());
         $plugin_fusioninventory_deploygroup_dynamicdatas_id =
            $pfDeployGroup_Dynamicdata->add($input);
      }

      $pfDeployGroup_Dynamicdata->getFromDB($plugin_fusioninventory_deploygroup_dynamicdatas_id);
      if (isset($_SESSION['plugin_fusioninventory_dynamicgroup']) &&
         $_SESSION['plugin_fusioninventory_dynamicgroup']['plugin_fusioninventory_deploygroup_dynamicdatas_id']
              == $plugin_fusioninventory_deploygroup_dynamicdatas_id
      ) {
         $_GET = $_SESSION['plugin_fusioninventory_dynamicgroup'];
         $array_delete = array('add_search_count', 'delete_search_count',
                               'add_search_count2', 'delete_search_count2');
         foreach ($array_delete as $value_delete) {
            if (isset($_SESSION['plugin_fusioninventory_dynamicgroup'][$value_delete])) {
               unset($_SESSION['plugin_fusioninventory_dynamicgroup'][$value_delete]);
            }
         }
      } else {
         $_GET = importArrayFromDB($pfDeployGroup_Dynamicdata->fields['fields_array']);
         unset($_SESSION["glpisearchcount"]['Computer']);
         unset($_SESSION["glpisearchcount2"]['Computer']);
         unset($_SESSION["glpisearch"]);
         unset($_SESSION['plugin_fusioninventory_dynamicgroup']);
         if (isset($_GET['field'])) {
            $_GET["glpisearchcount"] = count($_GET['field']);
         }
         if (isset($_GET['field2'])) {
            $_GET["glpisearchcount2"] = count($_GET['field2']);
         }
         if (!isset($_GET["glpisearchcount"])
                 || $_GET["glpisearchcount"] == 0) {
            $_GET["glpisearchcount"] = 1;
         }
      }

      if (!isset($_GET['field'])) {
         $_GET['field'] = array('');
      }
//              && count($_GET['field'])) {
//         $_GET["glpisearchcount"] = count($_GET['field']);
//      }
//      if (!isset($_GET["glpisearchcount"])) {
//         $_GET["glpisearchcount"] = 1;
//      }
      $_GET['name'] = 'rule';
      $_GET['itemtype'] = 'Computer';
//      unset($_SESSION["glpisearchcount"]['Computer']);
//      unset($_SESSION["glpisearch"]);

      Search::manageGetValues('Computer', FALSE);
      $pfSearch = new PluginFusioninventorySearch();
      $pfSearch->formurl            = $CFG_GLPI['root_doc'].'/plugins/fusioninventory/front/deploygroup_dynamicdata.form.php';
      $pfSearch->customIdVar        = 'plugin_fusioninventory_deploygroup_dynamicdatas_id';
      $pfSearch->displaydeletebuton = FALSE;

      echo "<br/><br/>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<form method='POST' name='update_group' id='update_group' action='".$pfSearch->formurl."'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>".__("Update")."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Automatic update')."&nbsp:&nbsp";
      Dropdown::showYesNo('can_update_group', $pfDeployGroup_Dynamicdata->fields['can_update_group']);
      echo "</td><td>";
      echo "<input type='submit' class='submit' value='".__("Update")."' name='update_group'>";
      echo "<input type='hidden' name='id' value='$plugin_fusioninventory_deploygroup_dynamicdatas_id'>";
      echo "</td></tr>";
      echo "</table>";
      Html::closeForm();

      $_GET[$pfSearch->customIdVar] = $plugin_fusioninventory_deploygroup_dynamicdatas_id;
      $_GET['id'] = $plugin_fusioninventory_deploygroup_dynamicdatas_id;
      $pfSearch->showGenericSearch('Computer', $_GET);

      echo "<br/><br/>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>";
      echo __('Preview');
      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>";


      $default_entity = 0;
      if (isset($_SESSION['glpiactive_entity'])) {
         $default_entity = $_SESSION['glpiactive_entity'];
      }
      $entities_isrecursive = 0;
      if (isset($_SESSION['glpiactiveentities'])
              AND count($_SESSION['glpiactiveentities']) > 1) {
         $entities_isrecursive = 1;
      }

      Search::showList('Computer', $_GET);

      echo "</td>";
      echo "</tr>";
      echo "</table>";*/
   }

}

?>
