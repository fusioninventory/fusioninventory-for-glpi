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

include_once (GLPI_ROOT . "/plugins/webservices/inc/methodcommon.class.php");
include_once (GLPI_ROOT . "/plugins/webservices/inc/methodinventaire.class.php");

class PluginFusinvdeploySearch extends CommonDBTM {

   static function methodListObjects($params, $protocol) {
      global $DB, $CFG_GLPI;

      if (!isset ($_SESSION['glpiID'])) {
         return self::Error($protocol, WEBSERVICES_ERROR_NOTAUTHENTICATED);
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
         return self::Error($protocol, WEBSERVICES_ERROR_BADPARAMETER. ": ".$params['itemtype']);
      }

      //Fields to return to the client when search search is performed
      $params['return_fields'][$params['itemtype']] = array('id','name', 'interface', 'is_default',
                                                            'locations_id', 'otherserial', 'serial');

      $output = array();
      $item = new $params['itemtype'];
      $table = getTableForItemType($params['itemtype']);

      //Restrict request
      $where = " WHERE 1 ";
      if ($item->maybeDeleted()) {
         $where .= " AND `$table`.`is_deleted` = '0'";
      }
      if ($item->maybeTemplate()) {
         $where .= " AND `$table`.`is_template` = '0'";
      }
      $where .= getEntitiesRestrictRequest(" AND ", $table) .
      $left_join = "";
      if ($item->getField('entities_id') != NOT_AVAILABLE) {
         $left_join = " LEFT JOIN `glpi_entities` ON (`$table`.`entities_id` = `glpi_entities`.`id`) ";
      }

      $query = "SELECT $table.* FROM `$table`
                   $left_join".
                   PluginWebservicesMethodInventaire::listInventoryObjectsRequestParameters($params,$item, $table, $where);
      $query.= " ORDER BY `id`
                LIMIT $start,$limit";

      foreach ($DB->request($query) as $data) {
         $tmp = array();
         $toformat = array('table' => $table, 'data'  => $data,
                           'searchOptions' => Search::getOptions($params['itemtype']),
                           'options' => $params);
         PluginWebservicesMethodCommon::formatDataForOutput($toformat, $tmp);
         $output[] = $tmp;
      }
      return $output;
   }


}
