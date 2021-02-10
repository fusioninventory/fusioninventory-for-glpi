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
 * This file is used to manage the search engine. Same than GLPI search
 * engine but with little modifications.
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
 * Manage the search engine. Same than GLPI search engine but with little
 * modifications.
 */
class PluginFusioninventorySearch extends CommonDBTM {

   /**
    * Define the form URL
    *
    * @var string
    */
   public $formurl = 'monitoring/front/componentscatalog_rule.form.php';

   /**
    * Define the custom id field name
    *
    * @var string
    */
   public $customIdVar = 'plugin_monitoring_componentscalalog_id';

   /**
    * Set the display the delete button to yes
    *
    * @var boolean
    */
   public $displaydeletebuton = true;

   /*
    * ************************************************************************ *
    * ************** Functions derived from Search of GLPI core ************** *
    * ************************************************************************ *
    */


   /**
    * Specific constructDatas to manage the specific FusionInventoryComputer
    * and replace with Computer for the Glpi built SQL queries.
    *
    * @return boolean
    */
   static function constructDatas(array &$data, $onlycount = false) {
      // Hack the built SQL query to use Computer rather than FusionInventoryComputer
      $data['sql']['search'] = str_replace("`mainitemtype` = 'PluginFusioninventoryComputer'",
         "`mainitemtype` = 'Computer'", $data['sql']['search']);
      $data['sql']['search'] = str_replace("`itemtype` = 'PluginFusioninventoryComputer'",
         "`itemtype` = 'Computer'", $data['sql']['search']);

      Search::constructData($data, $onlycount);
   }


   /**
    * Clone of Search::showList but only to have SQL query
    *
    * @global array $CFG_GLPI
    * @param string $itemtype
    * @param array $params
    * @param integer $items_id_check
    * @return boolean
    */
   function constructSQL($itemtype, $params, $items_id_check = 0) {
      global $CFG_GLPI;

      /**
       * the method Search::addMetaLeftJoin() on Software items uses
       * getEntitiesRestrictRequest() which needs a list of active entities
       * and ancestors at all costs.
       *
       * Since dynamic groups are not entity aware and Search::addMetaLeftJoin()
       * does a recursive entities JOIN, the query is fixed with the root entity.
       **/
      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $entities = getSonsOf("glpi_entities", 0);
         $_SESSION['glpiactiveentities_string'] = "'".implode("', '", $entities)."'";
      }
      if (!isset($_SESSION['glpiparententities'])) {
         $_SESSION['glpiparententities'] = '';
      }

      // Instanciate an object to access method
      $item = null;

      if ($itemtype!='States' && class_exists($itemtype)) {
         $item = new $itemtype();
      }

      // Default values of parameters
      $p = [];
      $p['link']        = [];//
      $p['field']       = [];//
      $p['contains']    = [];//
      $p['searchtype']  = [];//
      $p['sort']        = '1'; //
      $p['order']       = 'ASC';//
      $p['start']       = 0;//
      $p['is_deleted']  = 0;
      $p['export_all']  = 0;
      $p['link2']       = '';//
      $p['contains2']   = '';//
      $p['field2']      = '';//
      $p['itemtype2']   = '';
      $p['searchtype2'] = '';

      foreach ($params as $key => $val) {
         $p[$key] = $val;
      }

      if ($p['export_all']) {
         $p['start'] = 0;
      }

      // Manage defautll seachtype value : for SavedSearch compatibility
      if (count($p['contains'])) {
         foreach ($p['contains'] as $key => $val) {
            if (!isset($p['searchtype'][$key])) {
               $p['searchtype'][$key] = 'contains';
            }
         }
      }
      if (is_array($p['contains2']) && count($p['contains2'])) {
         foreach ($p['contains2'] as $key => $val) {
            if (!isset($p['searchtype2'][$key])) {
               $p['searchtype2'][$key] = 'contains';
            }
         }
      }

      //      $target = Toolbox::getItemTypeSearchURL($itemtype);

      $limitsearchopt = Search::getCleanedOptions($itemtype);

      if (isset($CFG_GLPI['union_search_type'][$itemtype])) {
         $itemtable = $CFG_GLPI['union_search_type'][$itemtype];
      } else {
         $itemtable = getTableForItemType($itemtype);
      }

      $LIST_LIMIT = $_SESSION['glpilist_limit'];

      // Set display type for export if define
      $output_type = Search::HTML_OUTPUT;
      if (isset($_GET['display_type'])) {
         $output_type = $_GET['display_type'];
         // Limit to 10 element
         if ($_GET['display_type'] == GLOBAL_SEARCH) {
            $LIST_LIMIT = GLOBAL_SEARCH_DISPLAY_COUNT;
         }
      }
      // hack for States
      if (isset($CFG_GLPI['union_search_type'][$itemtype])) {
         $entity_restrict = true;
      } else {
         $entity_restrict = $item->isEntityAssign();
      }

      //      $metanames = array();

      // Get the items to display
      //      $toview = Search::addDefaultToView($itemtype);

      //      // Add items to display depending of personal prefs
      //      $displaypref = DisplayPreference::getForTypeUser($itemtype, Session::getLoginUserID());
      //      if (count($displaypref)) {
      //         foreach ($displaypref as $val) {
      //            array_push($toview,$val);
      //         }
      //      }
      /* =========== Add for plugin Monitoring ============ */
         $toview = [];
         array_push($toview, 1);

      // Add searched items
      if (count($p['field'])>0) {
         foreach ($p['field'] as $key => $val) {
            if (!in_array($val, $toview) && $val!='all' && $val!='view') {
               array_push($toview, $val);
            }
         }
      }

      // Add order item
      if (!in_array($p['sort'], $toview)) {
         array_push($toview, $p['sort']);
      }

      //      // Special case for Ticket : put ID in front
      //      if ($itemtype=='Ticket') {
      //         array_unshift($toview, 2);
      //      }

      // Clean toview array
      $toview=array_unique($toview);
      foreach ($toview as $key => $val) {
         if (!isset($limitsearchopt[$val])) {
            unset($toview[$key]);
         }
      }

      //      $toview_count = count($toview);

      // Construct the request

      //// 1 - SELECT
      // request currentuser for SQL supervision, not displayed
      $SELECT = "SELECT ".Search::addDefaultSelect($itemtype);

      // Add select for all toview item
      foreach ($toview as $key => $val) {
         $SELECT .= Search::addSelect($itemtype, $val, $key, 0);
      }

      //// 2 - FROM AND LEFT JOIN
      // Set reference table
      $FROM = " FROM `$itemtable`";

      // Init already linked tables array in order not to link a table several times
      $already_link_tables = [];
      // Put reference table
      array_push($already_link_tables, $itemtable);

      // Add default join
      $COMMONLEFTJOIN = Search::addDefaultJoin($itemtype, $itemtable, $already_link_tables);
      $FROM .= $COMMONLEFTJOIN;

      $searchopt = [];
      $searchopt[$itemtype] = &Search::getOptions($itemtype);
      // Add all table for toview items
      foreach ($toview as $key => $val) {
         $FROM .= Search::addLeftJoin($itemtype, $itemtable, $already_link_tables,
                                    $searchopt[$itemtype][$val]["table"],
                                    $searchopt[$itemtype][$val]["linkfield"], 0, 0,
                                    $searchopt[$itemtype][$val]["joinparams"]);
      }

      // Search all case :
      if (in_array("all", $p['field'])) {
         foreach ($searchopt[$itemtype] as $key => $val) {
            // Do not search on Group Name
            if (is_array($val)) {
               $FROM .= Search::addLeftJoin($itemtype, $itemtable, $already_link_tables,
                                          $searchopt[$itemtype][$key]["table"],
                                          $searchopt[$itemtype][$key]["linkfield"], 0, 0,
                                          $searchopt[$itemtype][$key]["joinparams"]);
            }
         }
      }

      //// 3 - WHERE

      // default string
      $COMMONWHERE = Search::addDefaultWhere($itemtype);
      $first = empty($COMMONWHERE);

      // Add deleted if item have it
      if ($item && $item->maybeDeleted()) {
         $LINK = " AND ";
         if ($first) {
            $LINK  = " ";
            $first = false;
         }
         $COMMONWHERE .= $LINK."`$itemtable`.`is_deleted` = '".$p['is_deleted']."' ";
      }

      // Remove template items
      if ($item && $item->maybeTemplate()) {
         $LINK = " AND ";
         if ($first) {
            $LINK  = " ";
            $first = false;
         }
         $COMMONWHERE .= $LINK."`$itemtable`.`is_template` = '0' ";
      }

      // Add Restrict to current entities
      if ($entity_restrict) {
         $LINK = " AND ";
         if ($first) {
            $LINK  = " ";
            $first = false;
         }

         if ($itemtype == 'Entity') {
            $COMMONWHERE .= getEntitiesRestrictRequest($LINK, $itemtable, 'id', '', true);

         } else if (isset($CFG_GLPI["union_search_type"][$itemtype])) {
            // Will be replace below in Union/Recursivity Hack
            $COMMONWHERE .= $LINK." ENTITYRESTRICT ";
         } else {
            $COMMONWHERE .= getEntitiesRestrictRequest($LINK, $itemtable, '', '',
                                                       $item->maybeRecursive());
         }
      }
      $WHERE  = "";
      $HAVING = "";

      // Add search conditions
      // If there is search items
      if ($_SESSION["glpisearchcount"][$itemtype]>0 && count($p['contains'])>0) {
         for ($key=0; $key<$_SESSION["glpisearchcount"][$itemtype]; $key++) {
            // if real search (strlen >0) and not all and view search
            if (isset($p['contains'][$key]) && strlen($p['contains'][$key])>0) {
               // common search
               if ($p['field'][$key]!="all" && $p['field'][$key]!="view") {
                  $LINK    = " ";
                  $NOT     = 0;
                  $tmplink = "";
                  if (is_array($p['link']) && isset($p['link'][$key])) {
                     if (strstr($p['link'][$key], "NOT")) {
                        $tmplink = " ".str_replace(" NOT", "", $p['link'][$key]);
                        $NOT     = 1;
                     } else {
                        $tmplink = " ".$p['link'][$key];
                     }
                  } else {
                     $tmplink = " AND ";
                  }

                  if (isset($searchopt[$itemtype][$p['field'][$key]]["usehaving"])) {
                     // Manage Link if not first item
                     if (!empty($HAVING)) {
                        $LINK = $tmplink;
                     }
                     // Find key
                     $item_num = array_search($p['field'][$key], $toview);
                     $HAVING .= Search::addHaving($LINK, $NOT, $itemtype, $p['field'][$key],
                                                $p['searchtype'][$key], $p['contains'][$key], 0,
                                                $item_num);
                  } else {
                     // Manage Link if not first item
                     if (!empty($WHERE)) {
                        $LINK = $tmplink;
                     }
                     $WHERE .= Search::addWhere($LINK, $NOT, $itemtype, $p['field'][$key],
                                              $p['searchtype'][$key], $p['contains'][$key]);
                  }

                  // view and all search
               } else {
                  $LINK       = " OR ";
                  $NOT        = 0;
                  $globallink = " AND ";

                  if (is_array($p['link']) && isset($p['link'][$key])) {
                     switch ($p['link'][$key]) {
                        case "AND" :
                           $LINK       = " OR ";
                           $globallink = " AND ";
                           break;

                        case "AND NOT" :
                           $LINK       = " AND ";
                           $NOT        = 1;
                           $globallink = " AND ";
                           break;

                        case "OR" :
                           $LINK       = " OR ";
                           $globallink = " OR ";
                           break;

                        case "OR NOT" :
                           $LINK       = " AND ";
                           $NOT        = 1;
                           $globallink = " OR ";
                           break;
                     }

                  } else {
                     $tmplink ="  AND ";
                  }

                  // Manage Link if not first item
                  if (!empty($WHERE)) {
                     $WHERE .= $globallink;
                  }
                  $WHERE .= " ( ";
                  $first2 = true;

                  $items = [];

                  if ($p['field'][$key]=="all") {
                     $items = $searchopt[$itemtype];

                  } else { // toview case : populate toview
                     foreach ($toview as $key2 => $val2) {
                        $items[$val2] = $searchopt[$itemtype][$val2];
                     }
                  }

                  foreach ($items as $key2 => $val2) {
                     if (is_array($val2)) {
                        // Add Where clause if not to be done in HAVING CLAUSE
                        if (!isset($val2["usehaving"])) {
                           $tmplink = $LINK;
                           if ($first2) {
                              $tmplink = " ";
                              $first2  = false;
                           }
                           $WHERE .= Search::addWhere($tmplink, $NOT, $itemtype, $key2,
                                                    $p['searchtype'][$key], $p['contains'][$key]);
                        }
                     }
                  }
                  $WHERE .= " ) ";
               }
            }
         }
      }

      //// 4 - ORDER
      $ORDER = " ORDER BY `id` ";
      foreach ($toview as $key => $val) {
         if ($p['sort']==$val) {
            $ORDER = Search::addOrderBy($itemtype, $p['sort'], $p['order'], $key);
         }
      }

      //// 5 - META SEARCH
      // Preprocessing
      if ($_SESSION["glpisearchcount2"][$itemtype]>0 && is_array($p['itemtype2'])) {

         // a - SELECT
         for ($i=0; $i<$_SESSION["glpisearchcount2"][$itemtype]; $i++) {
            if (isset($p['itemtype2'][$i])
                && !empty($p['itemtype2'][$i])
                && isset($p['contains2'][$i])
                && strlen($p['contains2'][$i])>0) {

               $SELECT .= Search::addSelect($p['itemtype2'][$i], $p['field2'][$i], $i, 1,
                                          $p['itemtype2'][$i]);
            }
         }

         // b - ADD LEFT JOIN
         // Already link meta table in order not to linked a table several times
         $already_link_tables2 = [];
         // Link reference tables
         for ($i=0; $i<$_SESSION["glpisearchcount2"][$itemtype]; $i++) {
            if (isset($p['itemtype2'][$i])
                && !empty($p['itemtype2'][$i])
                && isset($p['contains2'][$i])
                && strlen($p['contains2'][$i])>0) {

               if (!in_array(getTableForItemType($p['itemtype2'][$i]), $already_link_tables2)) {
                  $FROM .= Search::addMetaLeftJoin($itemtype, $p['itemtype2'][$i],
                                                 $already_link_tables2,
                                                 (($p['contains2'][$i]=="NULL")
                                                  || (strstr($p['link2'][$i], "NOT"))));
               }
            }
         }
         // Link items tables
         for ($i=0; $i<$_SESSION["glpisearchcount2"][$itemtype]; $i++) {
            if (isset($p['itemtype2'][$i])
                && !empty($p['itemtype2'][$i])
                && isset($p['contains2'][$i])
                && strlen($p['contains2'][$i])>0) {

               if (!isset($searchopt[$p['itemtype2'][$i]])) {
                  $searchopt[$p['itemtype2'][$i]] = &Search::getOptions($p['itemtype2'][$i]);
               }
               if (!in_array($searchopt[$p['itemtype2'][$i]][$p['field2'][$i]]["table"]."_".
                                 $p['itemtype2'][$i],
                             $already_link_tables2)) {

                  $FROM .= Search::addLeftJoin($p['itemtype2'][$i],
                                 getTableForItemType($p['itemtype2'][$i]),
                                 $already_link_tables2,
                                 $searchopt[$p['itemtype2'][$i]][$p['field2'][$i]]["table"],
                                 $searchopt[$p['itemtype2'][$i]][$p['field2'][$i]]["linkfield"],
                                 1, $p['itemtype2'][$i],
                                 $searchopt[$p['itemtype2'][$i]][$p['field2'][$i]]["joinparams"]);
               }
            }
         }
      }

      //// 6 - Add item ID
      // Add ID to the select
      if (!empty($itemtable)) {
         $SELECT .= "`$itemtable`.`id` AS id ";
      }

      //// 7 - Manage GROUP BY
      $GROUPBY = "";
      // Meta Search / Search All / Count tickets
      if ($_SESSION["glpisearchcount2"][$itemtype]>0
          || !empty($HAVING)
          || in_array('all', $p['field'])) {

         $GROUPBY = " GROUP BY `$itemtable`.`id`";
      }

      if (empty($GROUPBY)) {
         foreach ($toview as $key2 => $val2) {
            if (!empty($GROUPBY)) {
               break;
            }
            if (isset($searchopt[$itemtype][$val2]["forcegroupby"])) {
               $GROUPBY = " GROUP BY `$itemtable`.`id`";
            }
         }
      }

      // Specific search for others item linked  (META search)
      if (is_array($p['itemtype2'])) {
         for ($key=0; $key<$_SESSION["glpisearchcount2"][$itemtype]; $key++) {
            if (isset($p['itemtype2'][$key])
                && !empty($p['itemtype2'][$key])
                && isset($p['contains2'][$key])
                && strlen($p['contains2'][$key])>0) {

               $LINK = "";

               // For AND NOT statement need to take into account all the group by items
               if (strstr($p['link2'][$key], "AND NOT")
                   || isset($searchopt[$p['itemtype2'][$key]][$p['field2'][$key]]["usehaving"])) {

                  $NOT = 0;
                  if (strstr($p['link2'][$key], "NOT")) {
                     $tmplink = " ".str_replace(" NOT", "", $p['link2'][$key]);
                     $NOT     = 1;
                  } else {
                     $tmplink = " ".$p['link2'][$key];
                  }
                  if (!empty($HAVING)) {
                     $LINK = $tmplink;
                  }
                  $HAVING .= Search::addHaving($LINK, $NOT, $p['itemtype2'][$key],
                                             $p['field2'][$key], $p['searchtype2'][$key],
                                             $p['contains2'][$key], 1, $key);
               } else { // Meta Where Search
                  $LINK = " ";
                  $NOT  = 0;
                  // Manage Link if not first item
                  if (is_array($p['link2'])
                      && isset($p['link2'][$key])
                      && strstr($p['link2'][$key], "NOT")) {

                     $tmplink = " ".str_replace(" NOT", "", $p['link2'][$key]);
                     $NOT     = 1;

                  } else if (is_array($p['link2']) && isset($p['link2'][$key])) {
                     $tmplink = " ".$p['link2'][$key];

                  } else {
                     $tmplink = " AND ";
                  }

                  if (!empty($WHERE)) {
                     $LINK = $tmplink;
                  }
                  $WHERE .= Search::addWhere($LINK, $NOT, $p['itemtype2'][$key], $p['field2'][$key],
                                           $p['searchtype2'][$key], $p['contains2'][$key], 1);
               }
            }
         }
      }

      // Use a ReadOnly connection if available and configured to be used
      $DBread = DBConnection::getReadConnection();

      // If no research limit research to display item and compute number of
      // item using simple request
      $nosearch = true;
      for ($i=0; $i<$_SESSION["glpisearchcount"][$itemtype]; $i++) {
         if (isset($p['contains'][$i]) && strlen($p['contains'][$i])>0) {
            $nosearch = false;
         }
      }

      if ($_SESSION["glpisearchcount2"][$itemtype]>0) {
         $nosearch = false;
      }

      $LIMIT   = "";
      $numrows = 0;
      //No search : count number of items using a simple count(ID) request and LIMIT search
      if ($nosearch) {
         $LIMIT = " LIMIT ".$p['start'].", ".$LIST_LIMIT;

         // Force group by for all the type -> need to count only on table ID
         if (!isset($searchopt[$itemtype][1]['forcegroupby'])) {
            $count = "count(*)";
         } else {
            $count = "count(DISTINCT `$itemtable`.`id`)";
         }
         // request currentuser for SQL supervision, not displayed
         $query_num = "SELECT $count
                       FROM `$itemtable`".
                       $COMMONLEFTJOIN;

         $first = true;

         if (!empty($COMMONWHERE)) {
            $LINK = " AND ";
            if ($first) {
               $LINK  = " WHERE ";
               $first = false;
            }
            $query_num .= $LINK.$COMMONWHERE;
         }
         // Union Search :
         if (isset($CFG_GLPI["union_search_type"][$itemtype])) {
            $tmpquery = $query_num;
            $numrows  = 0;

            foreach ($CFG_GLPI[$CFG_GLPI["union_search_type"][$itemtype]] as $ctype) {
               $ctable = getTableForItemType($ctype);
               $citem  = new $ctype();
               if ($citem->canView()) {
                  // State case
                  if ($itemtype == 'States') {
                     $query_num = str_replace($CFG_GLPI["union_search_type"][$itemtype],
                                              $ctable, $tmpquery);
                     $query_num .= " AND $ctable.`states_id` > '0' ";
                     // Add deleted if item have it
                     if ($citem && $citem->maybeDeleted()) {
                        $query_num .= " AND `$ctable`.`is_deleted` = '0' ";
                     }

                     // Remove template items
                     if ($citem && $citem->maybeTemplate()) {
                        $query_num .= " AND `$ctable`.`is_template` = '0' ";
                     }

                  } else {// Ref table case
                     $reftable = getTableForItemType($itemtype);
                     $replace  = "FROM `$reftable`
                                  INNER JOIN `$ctable`
                                       ON (`$reftable`.`items_id` =`$ctable`.`id`
                                           AND `$reftable`.`itemtype` = '$ctype')";

                     $query_num = str_replace("FROM `".$CFG_GLPI["union_search_type"][$itemtype]."`",
                                              $replace, $tmpquery);
                     $query_num = str_replace($CFG_GLPI["union_search_type"][$itemtype], $ctable,
                                              $query_num);
                  }
                  $query_num = str_replace("ENTITYRESTRICT",
                                           getEntitiesRestrictRequest('', $ctable, '', '',
                                                                      $citem->maybeRecursive()),
                                           $query_num);
                  $result_num = $DBread->query($query_num);
                  $numrows   += $DBread->result($result_num, 0, 0);
               }
            }

         } else {
            $result_num = $DBread->query($query_num);
            $numrows    = $DBread->result($result_num, 0, 0);
         }
      }

      // If export_all reset LIMIT condition
      if ($p['export_all']) {
         $LIMIT = "";
      }

      if (!empty($WHERE) || !empty($COMMONWHERE)) {
         if (!empty($COMMONWHERE)) {
            $WHERE = ' WHERE '.$COMMONWHERE.(!empty($WHERE)?' AND ( '.$WHERE.' )':'');
         } else {
            $WHERE = ' WHERE '.$WHERE.' ';
         }
         $first = false;
      }

      if (!empty($HAVING)) {
         $HAVING = ' HAVING '.$HAVING;
      }

      /* =========== Add for plugin Monitoring ============ */
      if (($items_id_check > 0)) {
         if ($itemtype == "PluginMonitoringNetworkport") {
            if ($WHERE == '') {
               $WHERE .= " WHERE `".getTableForItemType($itemtype)."`.`networkports_id`='".
                              $items_id_check."' ";
            } else {
               $WHERE .= " AND `".getTableForItemType($itemtype)."`.`networkports_id`='".
                              $items_id_check."' ";
            }
         } else {
            $WHERE .= " AND `".getTableForItemType($itemtype)."`.`id`='".$items_id_check."' ";
         }
      }

      // Create QUERY
      if (isset($CFG_GLPI["union_search_type"][$itemtype])) {
         $first = true;
         $QUERY = "";
         foreach ($CFG_GLPI[$CFG_GLPI["union_search_type"][$itemtype]] as $ctype) {
            $ctable = getTableForItemType($ctype);
            $citem  = new $ctype();
            if ($citem->canView()) {
               if ($first) {
                  $first = false;
               } else {
                  $QUERY .= " UNION ";
               }
               $tmpquery = "";
               // State case
               if ($itemtype == 'States') {
                  $tmpquery = $SELECT.", '$ctype' AS TYPE ".
                              $FROM.
                              $WHERE;
                  $tmpquery = str_replace($CFG_GLPI["union_search_type"][$itemtype],
                                          $ctable, $tmpquery);
                  $tmpquery .= " AND `$ctable`.`states_id` > '0' ";
                  // Add deleted if item have it
                  if ($citem && $citem->maybeDeleted()) {
                     $tmpquery .= " AND `$ctable`.`is_deleted` = '0' ";
                  }

                  // Remove template items
                  if ($citem && $citem->maybeTemplate()) {
                     $tmpquery .= " AND `$ctable`.`is_template` = '0' ";
                  }

               } else {// Ref table case
                  $reftable = getTableForItemType($itemtype);

                  $tmpquery = $SELECT.", '$ctype' AS TYPE,
                                      `$reftable`.`id` AS refID, "."
                                      `$ctable`.`entities_id` AS ENTITY ".
                              $FROM.
                              $WHERE;
                  $replace = "FROM `$reftable`"."
                              INNER JOIN `$ctable`"."
                                 ON (`$reftable`.`items_id`=`$ctable`.`id`"."
                                     AND `$reftable`.`itemtype` = '$ctype')";
                  $tmpquery = str_replace("FROM `".$CFG_GLPI["union_search_type"][$itemtype]."`",
                                          $replace, $tmpquery);
                  $tmpquery = str_replace($CFG_GLPI["union_search_type"][$itemtype], $ctable,
                                          $tmpquery);
               }
               $tmpquery = str_replace("ENTITYRESTRICT",
                                       getEntitiesRestrictRequest('', $ctable, '', '',
                                                                  $citem->maybeRecursive()),
                                       $tmpquery);

               // SOFTWARE HACK
               if ($ctype == 'Software') {
                  $tmpquery = str_replace("glpi_softwares.serial", "''", $tmpquery);
                  $tmpquery = str_replace("glpi_softwares.otherserial", "''", $tmpquery);
               }
               $QUERY .= $tmpquery;
            }
         }
         if (empty($QUERY)) {
            echo Search::showError($output_type);
            return;
         }
         $QUERY .= str_replace($CFG_GLPI["union_search_type"][$itemtype].".", "", $ORDER) . $LIMIT;
      } else {
         $QUERY = $SELECT.
                  $FROM.
                  $WHERE.
                  $GROUPBY.
                  $HAVING.
                  $ORDER.
                  $LIMIT;
      }

      $DBread->query("SET SESSION group_concat_max_len = 4096;");
      $result = $DBread->query($QUERY);
      /// Check group concat limit : if warning : increase limit
      if ($result2 = $DBread->query('SHOW WARNINGS')) {
         if ($DBread->numrows($result2) > 0) {
            $data = $DBread->fetchAssoc($result2);
            if ($data['Code'] == 1260) {
               $DBread->query("SET SESSION group_concat_max_len = 4194304;");
               $result = $DBread->query($QUERY);
            }
         }
      }

      // Get it from database and DISPLAY
      if ($result) {
         return $result;
      } else {
         return false;
      }
   }
}
