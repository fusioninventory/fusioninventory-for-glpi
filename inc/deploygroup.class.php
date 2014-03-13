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

class PluginFusioninventoryDeployGroup extends CommonDBTM {

   const STATIC_GROUP  = 'STATIC';
   const DYNAMIC_GROUP = 'DYNAMIC';

   static $rightname = "plugin_fusioninventory_configuration";

   protected $static_group_types = array('Computer');

   public $dohistory = TRUE;
   

   static function getTypeName($nb=0) {

      if ($nb>1) {
         return __('Task');
      }
      return __('Groups of computers', 'fusioninventory');
   }

   static function canCreate() {
      return true;
   }

   public function __construct() {
      $this->grouptypes = array(
            self::STATIC_GROUP  => __('Static group', 'fusioninventory'),
            self::DYNAMIC_GROUP => __('Dynamic group', 'fusioninventory')
         );
   }

   /*
   function defineTabs($options=array()) {
      $ong = array();
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }*/


   function showMenu($options=array())  {

      $this->displaylist = false;

      $this->fields['id'] = -1;
      $this->showList();
   }



   function title() {
      global $CFG_GLPI;

      $buttons = array();
      $title = __('Groups of computers', 'fusioninventory');

      if ($this->canCreate()) {
         $buttons["group.form.php?new=1"] = __('Add group', 'fusioninventory');
         $title = "";
      }

      Html::displayTitle($CFG_GLPI['root_doc']."/plugins/fusinvdeploy/pics/menu_group.png",
                         $title, $title, $buttons);
   }



   function showForm($ID, $options = array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);
    
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td rowspan='2'>".__('Comments')."&nbsp;:</td>";
      echo "<td rowspan='2' align='center'>";
      echo "<textarea cols='40' rows='6' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Type')."&nbsp;:</td>";
      echo "<td align='center'>";
      self::dropdownGroupType('type', $this->fields['type']);
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);

      //Display computers in the group only if it's static
      if ($this->fields['type'] == self::STATIC_GROUP) {
         PluginFusioninventoryDeployGroup_Staticdata::showResultsForGroup($this);
      }
      
      $params           = Search::manageParams('PluginFusioninventoryComputer', $_POST);
      $params['target'] = Toolbox::getItemTypeFormURL('PluginFusioninventoryDeployGroup')."?id=".$this->getID();
      
      //TODO exclude computers that already belong to the static group
      //$params['criteria'][] = array('field' => 2, 'value' => 9, 'searchtype' => 'notequals');
      
      //if (!isset($params['metacriteria']) && empty($params['metacriteria'])) {
         $params['metacriteria'] = array();
      //}

      self::showCriteria($this, true, $params);
      if (isset($_POST['preview'])) {
         Search::showList('PluginFusioninventoryComputer', $params);
      } else {
         //If not preview requested : clear search parameters in session
         if (isset($_SESSION['groupSearchResults'])) {
         unset($_SESSION['groupSearchResults']);
         }
         $_SESSION['plugin_fusioninventory_group_search_id'] = $ID;
      }
      return TRUE;
   }

   /**
    * Print pager for group list
    *
    * @param $title displayed above
    * @param $start from witch item we start
    * @param $numrows total items
    *
    * @return nothing (print a pager)
    **/
   static function printGroupPager($title, $start, $numrows) {
      global $CFG_GLPI;

      $list_limit = 50;
      // Forward is the next step forward
      $forward = $start+$list_limit;

      // This is the end, my friend
      $end = $numrows-$list_limit;

      // Human readable count starts here
      $current_start = $start+1;

      // And the human is viewing from start to end
      $current_end = $current_start+$list_limit-1;
      if ($current_end>$numrows) {
         $current_end = $numrows;
      }
      // Empty case
      if ($current_end==0) {
         $current_start = 0;
      }
      // Backward browsing
      if ($current_start-$list_limit<=0) {
         $back = 0;
      } else {
         $back = $start-$list_limit;
      }

      // Print it
      echo "<table class='tab_cadre_pager'>";
      if ($title) {
         echo "<tr><th colspan='6'>$title</th></tr>";
      }
      echo "<tr>\n";

      // Back and fast backward button
      if (!$start==0) {
         echo "<th class='left'><a href='javascript:reloadTab(\"start=0\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/first.png' alt=\"".__('Start').

                "\" title=\"".__('Start')."\"></a></th>";
         echo "<th class='left'><a href='javascript:reloadTab(\"start=$back\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/left.png' alt=\"".__('Previous').

                "\" title=\"".__('Previous')."\"></th>";
      }

      echo "<td width='50%' class='tab_bg_2'>";
      Html::printPagerForm();
      echo "</td>";

      // Print the "where am I?"
      echo "<td width='50%' class='tab_bg_2 b'>";
      echo __('from')."&nbsp;".$current_start."&nbsp;".__('to')."&nbsp;".
           $current_end."&nbsp;".__('of')."&nbsp;".$numrows."&nbsp;";
      echo "</td>\n";

      // Forward and fast forward button
      if ($forward<$numrows) {
         echo "<th class='right'><a href='javascript:reloadTab(\"start=$forward\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/right.png' alt=\"".__('Next').

                "\" title=\"".__('Next')."\"></a></th>";
         echo "<th class='right'><a href='javascript:reloadTab(\"start=$end\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/last.png' alt=\"".__('End').

                "\" title=\"".__('End')."\"></th>";
      }

      // End pager
      echo "</tr></table>";
   }

   static function showSearchResults($params) {
      global $DB;

      if (isset($params['type'])) {
         $type  = $params['type'];
      } else {
         exit;
      }
//      $options = array(
//         'type'                  => $type,
//         'itemtype'              => $params['itemtype'],
//            'locations_id'          => $params['locations_id'],
//            'serial'                => $params['serial'],
//            'operatingsystems_id'   => $params['operatingsystems_id'],
//            'operatingsystem_name'  => $params['operatingsystem_name'],
//            'otherserial'           => $params['otherserial'],
//            'name'                  => $params['name'],
//         'limit'                 => 99999999
//      );

      $query = "SELECT `glpi_computers`.`id` FROM `glpi_computers`";
      $where = 0;
      // * Serial
      if (isset($params['serial'])
              && !empty($params['serial'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`serial` LIKE '%".$params['serial']."%'";
         $where++;
      }
      // * Otherserial
      if (isset($params['otherserial'])
              && !empty($params['otherserial'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`otherserial` LIKE '%".$params['otherserial']."%'";
         $where++;
      }
      // * name
      if (isset($params['name'])
              && !empty($params['name'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`name` LIKE '%".$params['name']."%'";
         $where++;
      }
      // * locations_id
      if (isset($params['locations_id'])
              && !empty($params['locations_id'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`locations_id`='".$params['locations_id']."'";
         $where++;
      }
      // * operatingsystems_id
      if (isset($params['operatingsystems_id'])
              && !empty($params['operatingsystems_id'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`operatingsystems_id`='".$params['operatingsystems_id']."'";
         $where++;
      }
      // * operatingsystem_name
      if (isset($params['operatingsystem_name'])
              && !empty($params['operatingsystem_name'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`operatingsystem_name`='".$params['operatingsystem_name']."'";
         $where++;
      }
      $result = $DB->query($query);

//      if ($options['operatingsystems_id'] != 0) unset($options['operatingsystem_name']);
//      if ($options['operatingsystems_id'] == 0) unset($options['operatingsystems_id']);
//      if ($options['locations_id'] == 0) unset($options['locations_id']);
//      $nb_items = count(PluginWebservicesMethodInventaire::methodListInventoryObjects(
//         $options, ''));
//
//      $options['limit'] = 50;
//      $options['start'] = $params['start'];
      $nb_items = $DB->numrows($result);
//      $datas = PluginWebservicesMethodInventaire::methodListInventoryObjects($options, '');
      $datas = array();
      while ($data=$DB->fetch_array($result)) {
         $datas[$data['id']] = $data;

      }

      echo "<div class='center'><br />";
      $nb_col = 5;

      echo "<table class='tab_cadrehov' style='width:950px'>";
      echo "<thead><tr>";
      if ($type == 'static') {
         echo "<th></th>";
      }
      echo "<th colspan='".($nb_col*2)."'>".__('Computers')."</th>";
      echo "</tr></thead>";

      $stripe = TRUE;
      $computer = new Computer();
      $i=1;
      echo "<tr class='tab_bg_".(((int)$stripe)+1)."'>";
      foreach ($datas as $row) {
         $computer->getFromDB($row["id"]);
         if ($type == 'static') {
            echo "<td width='1%'>";
            echo "<input type='checkbox' name='item[".$row["id"]."]' value='".$row["id"]."'>";
            echo "</td>";
         }
         echo "<td>";
         echo $computer->getLink(TRUE);
         echo "</td>";

         if (($i % $nb_col) == 0) {
            $stripe =! $stripe;
            echo "</tr><tr class='tab_bg_".(((int)$stripe)+1)."'>";
         }
         $i++;
      }
      echo "</tr>";
      echo "</table>";

      if ($type == 'static') {
         Html::openArrowMassives("group_search");
         echo "<input type='submit' class='submit' value="
            .__('Add')." name='additem' />";
         Html::closeArrowMassives(array());
      } else {
         echo "<br />";
      }

      self::printGroupPager('', $params['start'], $nb_items);

      echo "</div>";
   }

   static function getAllDatas($root = 'groups')  {
      global $DB;

      $sql = " SELECT id, name
               FROM glpi_plugin_fusioninventory_deploygroups
               ORDER BY name";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
       $i = 0;
      while($row = $DB->fetch_assoc($res)) {
         $json[$root][$i]['id'] = $row['id'];
         $json[$root][$i]['name'] = $row['name'];

         $i++;
      }
      $json['results'] = $nb;

      return json_encode($json);
   }

   function getSearchOptions() {

      $tab = array();

      $tab['common'] = self::getTypeName();


      $tab[1]['table']          = $this->getTable();
      $tab[1]['field']          = 'name';
      $tab[1]['linkfield']      = '';
      $tab[1]['name']           = __('Name');
      $tab[1]['datatype']       = 'itemlink';
      $tab[1]['massiveaction']   = false;

      $tab[2]['table']           = $this->getTable();
      $tab[2]['field']           = 'type';
      $tab[2]['name']            = __('Type');
      $tab[2]['datatype']        = 'specific';
      $tab[2]['massiveaction']   = false;
      $tab[2]['searchtype']      = 'equals';

      return $tab;
   }

   static function getSpecificValueToDisplay($field, $values, array $options=array()) {
      $group = new self();
      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'type' :
            return $group->grouptypes[$values[$field]];
      }
      return '';
   }

   /**
   * Display dropdown to select dynamic of static group
   */
   static function dropdownGroupType($name = 'type', $value = 'STATIC') {
      $group = new self();
      return Dropdown::showFromArray($name, $group->grouptypes, array('value'=>$value));
   }

   static function getSpecificValueToSelect($field, $name='', $values='', array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }

      $options['display'] = false;
      switch ($field) {
         case 'type':
            return self::dropdownGroupType($name, $values[$field]);
         default:
            break;
      }
      return parent::getSpecificValueToSelect($field, $name, $values, $options);
   }

   /**
    * Displays tab content
    * This function adapted from Search::showGenericSearch with controls removed
    * @param  bool $formcontrol : display form buttons
    * @return nothing, displays a seach form
    */
   static function showCriteria(PluginFusioninventoryDeployGroup $group, $formcontrol = true, $criteria) {
      global $CFG_GLPI, $DB;


      $itemtype = "PluginFusioninventoryComputer";
      unset($_SESSION['glpisearch'][$itemtype]);
      $p = array();
      
      if ($group->fields['type'] == self::DYNAMIC_GROUP) {
         $query = "SELECT `fields_array` 
                   FROM `glpi_plugin_fusioninventory_deploygroups_dynamicdatas` 
                   WHERE `plugin_fusioninventory_deploygroups_groups_id`='".$group->getID()."'";
         $result = $DB->query($query);
         if ($DB->numrows($result) > 0) {
            $fields_array = $DB->result($result, 0, 'fields_array');
            $p['criteria'] = json_decode($fields_array, true);
         }
      }
      
      // load saved criterias
      //$p['criteria'] = $this->getCriteria();
      //$p['metacriteria'] = $this->getMetaCriteria();

      //if (isset($_SESSION['glpisearch'][$itemtype])) {
      //   $p['criteria'] = $_SESSION['glpisearch'][$itemtype];
      //}
      //manage sessions
      
      $p = Search::manageParams($itemtype, $p);

      if ($formcontrol) {
         //show generic search form (duplicated from Search class)
         echo "<form name='group_search_form' method='post'>";
         echo "<input type='hidden' name='id' value='".$group->getID()."'>";  

         // add tow hidden fields to permit delete of (meta)criteria
         echo "<input type='hidden' name='criteria' value=''>";     
         echo "<input type='hidden' name='metacriteria' value=''>"; 
      } 

      echo "<div class='tabs_criteria'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th>"._n('Criterion', 'Criteria', 2)."</th></tr>";
      echo "<tr><td>";

      echo "<div id='searchcriteria'>";
      $nb_criteria = count($p['criteria']);
      if ($nb_criteria == 0) $nb_criteria++;
      $nbsearchcountvar = 'nbcriteria'.strtolower($itemtype).mt_rand();
      $nbmetasearchcountvar = 'nbmetacriteria'.strtolower($itemtype).mt_rand();
      $searchcriteriatableid = 'criteriatable'.strtolower($itemtype).mt_rand();
      // init criteria count
      $js = "var $nbsearchcountvar=".$nb_criteria.";";
      $js .= "var $nbmetasearchcountvar=".count($p['metacriteria']).";";
      echo Html::scriptBlock($js);

      echo "<table class='tab_cadre_fixe' >";
      echo "<tr class='tab_bg_1'>";
      echo "<td>";

      echo "<table class='tab_format' id='$searchcriteriatableid'>";

      // Displays normal search parameters
      for ($i=0 ; $i<$nb_criteria ; $i++) {
         $_POST['itemtype'] = $itemtype;
         $_POST['num'] = $i ;
         include(GLPI_ROOT.'/ajax/searchrow.php');
      }

      $metanames = array();
      $linked =  Search::getMetaItemtypeAvailable($itemtype);
      
      if (is_array($linked) && (count($linked) > 0)) {
         for ($i=0 ; $i<count($p['metacriteria']) ; $i++) {

            $_POST['itemtype'] = $itemtype;
            $_POST['num'] = $i ;
            include(GLPI_ROOT.'/ajax/searchmetarow.php');
         }
      }
      echo "</table>\n";
      echo "</td>"; 
      echo "</tr>";
      echo "</table>\n";

      // For dropdown
      echo "<input type='hidden' name='itemtype' value='$itemtype'>";

      if ($formcontrol) {
         // add new button to search form (to store and preview)
         echo "<div class='center'>";
         //echo "<input type='submit' value=\" "._sx('button', 'Save').
         //     " \" class='submit' name='update'>&nbsp;";
        
        echo "<input type='submit' value=\" ".__('Preview')." \" class='submit' name='preview'>";
         echo "</div>";
      }

      echo "</td></tr></table>";
      echo "</div>";

      //restore search session variables
      //$_SESSION['glpisearch'] = $glpisearch_session;

      // Reset to start when submit new search
      echo "<input type='hidden' name='start' value='0'>";

      Html::closeForm();

      //clean with javascript search control
      $clean_script = "jQuery( document ).ready(function( $ ) {
         $('#parent_criteria img').remove();
         $('.tabs_criteria img[name=img_deleted').remove();
      });";
      echo Html::scriptBlock($clean_script);
   }
   
}
?>
