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
 * This file is used to manage the network equipment port not have
 * connection since xx days.
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

//Options for GLPI 0.71 and newer : need slave db to access the report
$USEDBREPLICATE=1;
$DBCONNECTION_REQUIRED=0;

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'), filter_input(INPUT_SERVER, "PHP_SELF"), "utils", "report");

Session::checkRight('plugin_fusioninventory_reportnetworkequipment', READ);

$reset_search = filter_input(INPUT_GET, "reset_search");
if ($reset_search != '') {
   resetSearch();
}

$options=['options'=>['default'=>0]];
$start = filter_input(INPUT_GET, "start", FILTER_VALIDATE_INT, $options);
$_GET["start"] = $start;

$_GET=getValues($_GET, $_POST);
displaySearchForm();

if (isset($_POST["dropdown_calendar"]) && isset($_POST["dropdown_sup_inf"])) {

   $date_search = '';
   if ($_POST['dropdown_sup_inf'] == 'sup') {
      $date_search .= "> '".$_POST['dropdown_calendar']."'";
   } else if ($_POST['dropdown_sup_inf'] == 'inf') {
      $date_search .= "< '".$_POST['dropdown_calendar']."'";
   } else if ($_POST['dropdown_sup_inf'] == 'equal') {
      $date_search .= " LIKE '".$_POST['dropdown_calendar']."%'";
   }
   $networkport = new NetworkPort();
   $networkequipment = new NetworkEquipment();

   $query = "SELECT `glpi_networkports`.`id`, a.date_mod, `glpi_networkports`.`items_id` FROM `glpi_networkports`"
           . " LEFT JOIN `glpi_plugin_fusioninventory_networkportconnectionlogs` a"
           . " ON a.id= (SELECT MAX(fn.id) a_id
               FROM glpi_plugin_fusioninventory_networkportconnectionlogs fn
               WHERE (fn.networkports_id_source = glpi_networkports.id
                      OR fn.networkports_id_destination = glpi_networkports.id))"
           . " WHERE a.id IS NOT NULL AND `glpi_networkports`.`itemtype`='NetworkEquipment'"
           . " AND a.date_mod".$date_search
           . " ORDER BY `glpi_networkports`.`items_id`";
   $result = $DB->query($query);
   echo "<table width='950' class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo 'Port name';
      echo "</th>";
      echo "<th>";
      echo 'Switch';
      echo "</th>";
      echo "<th>";
      echo 'Last connection';
      echo "</th>";
      echo "</tr>";

   while ($data = $DB->fetchArray($result)) {
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      $networkport->getFromDB($data['id']);
      echo $networkport->getLink();
      echo "</td>";
      echo "<td>";
      $networkequipment->getFromDB($data['items_id']);
      echo $networkequipment->getLink();
      echo "</td>";
      echo "<td>";
      echo Html::convDate($data['date_mod']);
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
}
Html::footer();


/**
 * Display special search form
 *
 * @global array $_SERVER
 * @global array $_GET
 * @global array $CFG_GLPI
 */
function displaySearchForm() {
   global $_SERVER, $_GET, $CFG_GLPI;

   echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'>";
   echo "<table class='tab_cadre' cellpadding='5'>";
   echo "<tr class='tab_bg_1' align='center'>";
   echo "<td>";
   echo __('Initial contract period')." :";

   $values=[];
   $values["sup"]=">";
   $values["inf"]="<";
   $values["equal"]="=";

   if (isset($_GET["contains"][1])) {
      if (strstr($_GET["contains"][1], "lt;")) {
         $_GET["dropdown_sup_inf"] = "inf";
         $_GET["dropdown_calendar"] = str_replace("lt;", "", $_GET["contains"][1]);
         $_GET["dropdown_calendar"] = str_replace("&", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("\\", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("'", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace(" 00:00:00", "", $_GET["dropdown_calendar"]);
         $_GET["contains"][1] = "<".$_GET["dropdown_calendar"];
      }
      if (strstr($_GET["contains"][1], "gt;")) {
         $_GET["dropdown_sup_inf"] = "sup";
         $_GET["dropdown_calendar"] = str_replace("gt;", "", $_GET["contains"][1]);
         $_GET["dropdown_calendar"] = str_replace("&", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("\\", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("'", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace(" 00:00:00", "", $_GET["dropdown_calendar"]);
         $_GET["contains"][1] = ">".$_GET["dropdown_calendar"];
      }
      if (strstr($_GET["contains"][1], "LIKE")) {
         $_GET["dropdown_sup_inf"] = "equal";
         $_GET["dropdown_calendar"] = str_replace("=", "", $_GET["contains"][1]);
         $_GET["dropdown_calendar"] = str_replace("&", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("\\", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("'", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("%", "", $_GET["dropdown_calendar"]);
         $_GET["dropdown_calendar"] = str_replace("LIKE ", "", $_GET["dropdown_calendar"]);
         $_GET["contains"][1] = "LIKE '".$_GET["dropdown_calendar"]."%'";
      }
   }
   Dropdown::showFromArray("dropdown_sup_inf", $values,
                           ['value'=>(isset($_GET["dropdown_sup_inf"])?$_GET["dropdown_sup_inf"]:"sup")]);
   echo "</td>
      <td width='120'>";
   Html::showDateField("dropdown_calendar",
                       ['value' => (isset($_GET["dropdown_calendar"])
                                     ?$_GET["dropdown_calendar"]:0)]);
   echo "</td>";

   echo "<td>".__('Location')."</td>";
   echo "<td>";
   Dropdown::show("Location",
                  ['name' => "location",
                        'value' => (isset($_GET["location"])?$_GET["location"]:"")]);
   echo "</td>";

   // Display Reset search
   echo "<td>";
   echo "<a href='".Plugin::getWebDir('fusioninventory')."/report/ports_date_connections.php?reset_search=reset_search' ><img title=\"".__('Blank')."\" alt=\"".__('Blank')."\" src='".$CFG_GLPI["root_doc"]."/pics/reset.png' class='calendrier'></a>";
   echo "</td>";

   echo "<td>";
   //Add parameters to uri to be saved as SavedSearch
   $_SERVER["REQUEST_URI"] = buildSavedSearchUrl($_SERVER["REQUEST_URI"], $_GET);
   SavedSearch::showSaveButton(SavedSearch::SEARCH, 'PluginFusioninventoryNetworkport2');
   echo "</td>";

   echo "<td>";
   echo "<input type='submit' value='" . __('Validate') . "' class='submit' />";
   echo "</td>";

   echo "</tr>";
   echo "</table>";
   Html::closeForm();

}


/**
 * Get array in GET for search
 *
 * @param array $get
 * @return string
 */
function getContainsArray($get) {
   if (isset($get["dropdown_sup_inf"])) {
      switch ($get["dropdown_sup_inf"]) {

         case "sup":
            return ">'".$get["dropdown_calendar"]." 00:00:00'";

         case "equal":
            return "LIKE '".$get["dropdown_calendar"]."%'";

         case "inf":
            return "<'".$get["dropdown_calendar"]." 00:00:00'";

      }
   }
}


/**
 * Generate the URL SavedSearch
 *
 * @param string $url
 * @param array $get
 * @return string
 */
function buildSavedSearchUrl($url, $get) {
    return $url."?field[0]=3&contains[0]=".getContainsArray($get);
}


/**
 * Get values
 *
 * @param array $get
 * @param array $post
 * @return array
 */
function getValues($get, $post) {
   $get=array_merge($get, $post);
   if (isset($get["field"])) {
      foreach ($get["field"] as $index => $value) {
         $get["contains"][$index] = stripslashes($get["contains"][$index]);
         $get["contains"][$index] = htmlspecialchars_decode($get["contains"][$index]);
         switch ($value) {
            case 14:
               if (strpos( $get["contains"][$index], "=")==1) {
                  $get["dropdown_sup_inf"]="equal";
               } else {
                  if (strpos( $get["contains"][$index], "<")==1) {
                     $get["dropdown_sup_inf"]="inf";
                  } else {
                     $get["dropdown_sup_inf"]="sup";
                  }
               }
               break;
         }
         $get["dropdown_calendar"] = substr($get["contains"][$index], 1);
      }
   }
   return $get;
}


/**
 * Reset the search engine
 */
function resetSearch() {
   $_GET["start"]=0;
   $_GET["order"]="ASC";
   $_GET["is_deleted"]=0;
   $_GET["distinct"]="N";
   $_GET["link"]=[];
   $_GET["field"]=[0=>"view"];
   $_GET["contains"]=[0=>""];
   $_GET["link2"]=[];
   $_GET["field2"]=[0=>"view"];
   $_GET["contains2"]=[0=>""];
   $_GET["type2"]="";
   $_GET["sort"]=1;

   $_GET["dropdown_sup_inf"]="sup";
   $_GET["dropdown_calendar"]=date("Y-m-d H:i");
}

