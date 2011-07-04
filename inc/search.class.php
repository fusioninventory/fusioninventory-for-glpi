<?php
/*
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

class PluginFusinvdeploySearch extends CommonDBTM {

   static function methodListObjects($params, $protocol) {
      global $DB, $CFG_GLPI;

      if (isset ($params['help'])) {
         return array('start'        => 'integer,optional',
                      'limit'        => 'integer,optional',
                      'name'         => 'string,optional',
                      'serial'       => 'string,optional',
                      'otherserial'  => 'string,optional',
                      'locations_id' => 'integer,optional',
                      'room'         => 'string (Location only)',
                      'building'     => 'string (Location only)',
                      'itemtype'     => 'string or array, optional',
                      'show_label'   => 'bool, optional (0 default)',
                      'help'         => 'bool,optional');
      }

      if (!isset ($_SESSION['glpiID'])) {
         return false;
      }

      $resp = array();

      $start = 0;
      $limit = $CFG_GLPI["list_limit_max"];
      if (isset ($params['limit']) && is_numeric($params['limit'])) {
         $limit = $params['limit'];
      }
      if (isset ($params['start']) && is_numeric($params['start'])) {
         $start = $params['start'];
      }
      foreach (array('show_label','show_name') as $key) {
          $params[$key] = (isset($params[$key])?$params[$key]:false);
      }

      if (!class_exists($params['itemtype'])) {
         return false;
      }

      //Fields to return to the client when search search is performed
      $params['return_fields'][$params['itemtype']] = array('id','name', 'interface', 'is_default',
                                                            'locations_id', 'otherserial', 'serial');

      $output = array();
      $item = new $params['itemtype'];
      $table = getTableForItemType($params['itemtype']);

      //Restrict request
      $where = " 1 ";
      if ($item->maybeDeleted()) {
         $where .= " AND `$table`.`is_deleted` = '0'";
      }
      if ($item->maybeTemplate()) {
         $where .= " AND `$table`.`is_template` = '0'";
      }
      $left_join = "";
      if ($item->getField('entities_id') != NOT_AVAILABLE) {
         $left_join = " LEFT JOIN `glpi_entities` ON (`$table`.`entities_id` = `glpi_entities`.`id`) ";
      }

      $query = "SELECT $table.* FROM `$table`
                   $left_join
                   WHERE $where" .
                         getEntitiesRestrictRequest(" AND ", $table) .
                         self::listInventoryObjectsRequestParameters($params,$item, $table);
      $query.= " ORDER BY `id`
                LIMIT $start,$limit";

      foreach ($DB->request($query) as $data) {
         $tmp = array();
         $toformat = array('table' => $table, 'data'  => $data,
                           'searchOptions' => Search::getOptions($params['itemtype']),
                           'options' => $params);
         self::formatDataForOutput($toformat, $tmp);
         $output[] = $tmp;
      }
      return $output;
   }


   //-----------------------------------------------//
   //--------- Itemtype independant methods -------//
   //---------------------------------------------//

   /**
    * Contruct parameters restriction for listInventoryObjects sql request
    * @param the input parameters
    */
   static function listInventoryObjectsRequestParameters($params, CommonDBTM $item, $table) {

      $where        = "";
      $already_used = array();

      foreach ($params as $key => $value) {
         //Key representing the FK associated with the _name value
         $key_transformed = preg_replace("/_name/", "s_id", $key);
         $fk_table = getTableNameForForeignKeyField($key);
         $option   = $item->getSearchOptionByField('field', $key_transformed);

         if (!empty($option)) {
            if (!in_array($key, $already_used)
               && (isset ($params[$key])
                  && $item->getField($option['linkfield']) != NOT_AVAILABLE)) {

               if (getTableNameForForeignKeyField($key)) {
                  $where .= " AND `$table`.`$key`='" . $params[$key] . "'";

               } else {
                  //
                  if (($key != $key_transformed) || ($table != $option['table'])) {
                     $where .= " AND `".$option['table']."`.`".$option['field'];
                     $where .= "` LIKE '%" . $params[$key] . "%'";

                  } else {
                     $where .= " AND `$table`.`$key` LIKE '%" . $params[$key] . "%'";
                  }
               }
               $already_used[] = $key;

            }
         }
      }

      return $where;
   }

   /**
    * Contruct parameters restriction for listInventoryObjects sql request
    * @param the input parameters
    */
   static function listInventoryObjectsRequestLeftJoins($params, CommonDBTM $item, $table) {

      $join           = "";
      $already_joined = array();

      foreach ($params as $key => $value) {

         //Key representing the FK associated with the _name value
         $key_transformed = preg_replace("/_name/", "s_id", $key);
         $option = $item->getSearchOptionByField('field', $key_transformed);

         if (!empty($option)
            && !isset($option['common'])
               && $table != $option['table']
                  && !in_array($option['table'], $already_joined)) {
            $join.= " \nINNER JOIN `".$option['table'].
                     "` ON (`$table`.`".$option['linkfield']."` = `".$option['table']."`.`id`) ";
            $already_joined[] = $option['table'];
         }

      }
      return $join;
   }


   /**
    * Return data formatted
    * @param params the needed parameters
    * @param output array which contains the data to be sent to the client
    * @return nothing
    */
   static function formatDataForOutput($params = array(), &$output) {
      global $LANG;

      $blacklisted_fields = array('items_id');

      $p['searchOptions'] = array();
      $p['data']          = array();
      $p['options']       = array();
      $p['subtype']       = false;

      foreach ($params as $key => $value) {
         $p[$key] = $value;
      }

      $p['table']          = getTableForItemType($p['options']['itemtype']);
      $p['show_label']     = $p['options']['show_label'];
      $p['show_name']      = $p['options']['show_name'];
      $p['return_fields']  = $p['options']['return_fields'];

      $p['searchOptions'][999]['table']       = $p['table'];
      $p['searchOptions'][999]['field']       = 'id';
      $p['searchOptions'][999]['linkfield']   = 'id';
      $p['searchOptions'][999]['name']        = $LANG['login'][6];

      $tmp = array();
      foreach($p['searchOptions'] as $id => $option) {
         if (isset($option['table'])) {
            if (!isset($option['linkfield']) || empty($option['linkfield'])) {
               if ($p['table'] == $option['table']) {
                  $linkfield = $option['name'];
               } else {
                  $linkfield = getForeignKeyFieldForTable($p['table']);
               }
            } else {
               $linkfield = $option['linkfield'];
            }

            if (isset($p['data'][$linkfield])
                  && $p['data'][$linkfield] != ''
                     && (empty($p['return_fields'][$p['options']['itemtype']])
                        || (!empty($p['return_fields'][$p['options']['itemtype']])
                           && in_array($linkfield,$p['return_fields'][$p['options']['itemtype']])))) {

               $tmp[$linkfield] = $p['data'][$linkfield];
               if ($p['show_label']) {
                  $tmp[$linkfield."_label"] = $option['name'];
               }
               if ($p['show_name']) {
                   //If field is an FK and is not blacklisted !
                   if (self::isForeignKey($linkfield)
                         && !in_array($linkfield,$blacklisted_fields)
                            && (!isset($option['datatype'])
                               || isset($option['datatype']) && $option['datatype'] != 'itemlink')) {
                      $option_name = str_replace("_id","_name",$linkfield);
                      $result = Dropdown::getDropdownName($option['table'],
                                                          $p['data'][$linkfield]);
                      if ($result != '&nbsp;') {
                         $tmp[$option_name] = $result;
                      }
                   } else {
                      //Should exists if we could get results directly from the search engine...
                      if (isset($option['datatype'])) {
                         $option_name = $linkfield."_name";
                         switch ($option['datatype']) {
                            case 'date':
                               $tmp[$linkfield] = convDateTime($p['data'][$linkfield]);
                               break;
                            case 'bool':
                               $tmp[$option_name] = Dropdown::getYesNo($p['data'][$linkfield]);
                               break;
                            case 'itemlink':
                                  if (isset($option['itemlink_type'])) {
                                     $obj = new $option['itemlink_type']();
                                  } else {
                                     $obj = new $option['itemlink_link']();
                                  }
                                  $obj->getFromDB($p['data'][$linkfield]);
                                  $tmp[$linkfield] = $p['data'][$linkfield];
                                  $tmp[$option_name] = $obj->getField($option['field']);
                               break;
                            case 'itemtype':
                               if (class_exists($p['data'][$linkfield])) {
                                  $obj = new $p['data'][$linkfield];
                                  $tmp[$option_name] = $obj->getTypeName();
                               }
                               break;
                         }
                      }
                   }
               }
            }
         }
      }
      if (!empty($tmp)) {
         $output = $tmp;
      }
   }

   static public function isForeignKey($field) {
      if (preg_match("/s_id/",$field)) {
         return true;
      } else {
         return false;
      }
   }
}
