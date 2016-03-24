<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @author
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");
Session::checkLoginUser();

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

//Session::checkRight('create_ticket', "1");

// Security
if (!TableExists($_POST['table'])) {
   exit();
}


$where = "WHERE 1";



if (strlen($_POST['searchText'])>0 && $_POST['searchText']!=$CFG_GLPI["ajax_wildcard"]) {
   $search = Search::makeTextSearch($_POST['searchText']);

   $where .= " AND (`name` ".$search."
                    OR `id` = '".$_POST['searchText']."'";
   $where .= ")";
}


$NBMAX = $CFG_GLPI["dropdown_max"];
$LIMIT = "LIMIT 0, $NBMAX";

if ($_POST['searchText']==$CFG_GLPI["ajax_wildcard"]) {
   $LIMIT = "";
}

$query = "SELECT *
          FROM `".$_POST['table']."`
          $where
          ORDER BY `name`
          $LIMIT";
$result = $DB->query($query);

echo "<select name='".$_POST['myname']."' id='".$_POST['myname']."' size='1'>";

if ($_POST['searchText']!=$CFG_GLPI["ajax_wildcard"] && $DB->numrows($result)==$NBMAX) {
   echo "<option value='0'>--".__('Limited view')."--</option>";
}

echo "<option value='0'>".Dropdown::EMPTY_VALUE."</option>";

if ($DB->numrows($result)) {
   while ($data = $DB->fetch_array($result)) {
      $output = $data['name'];

      if (empty($output) || $_SESSION['glpiis_ids_visible']) {
         $output .= " (".$data['id'].")";
      }
      $selected = "";
      if ($data['id'] == $_POST['value']) {
         $selected = "selected='selected'";
      }

      echo "<option value='".$data['id']."' $selected title=\"".Html::cleanInputText($output)."\">".
            Toolbox::substr($output, 0, $_SESSION["glpidropdown_chars_limit"])."</option>";
   }
}

echo "</select>";

?>
