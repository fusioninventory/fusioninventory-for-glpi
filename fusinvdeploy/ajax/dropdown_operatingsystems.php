<?php
/*
 * @version $Id: dropdownFindNum.php 14684 2011-06-11 06:32:40Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file: List of device for tracking.
// ----------------------------------------------------------------------

define('GLPI_ROOT','../../..');
include (GLPI_ROOT . "/inc/includes.php");
checkLoginUser();

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

checkRight("create_ticket", "1");

// Security
if (!TableExists($_POST['table'])) {
   exit();
}


$where = "WHERE 1";



if (strlen($_POST['searchText'])>0 && $_POST['searchText']!=$CFG_GLPI["ajax_wildcard"]) {
   $search = makeTextSearch($_POST['searchText']);

   $where .= " AND (`name` ".$search."
                    OR `id` = '".$_POST['searchText']."'";
   $where .= ")";
}


$NBMAX = $CFG_GLPI["dropdown_max"];
$LIMIT = "LIMIT 0,$NBMAX";

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
   echo "<option value='0'>--".$LANG['common'][11]."--</option>";
}

echo "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option>";

if ($DB->numrows($result)) {
   while ($data = $DB->fetch_array($result)) {
      $output = $data['name'];

      if (empty($output) || $_SESSION['glpiis_ids_visible']) {
         $output .= " (".$data['id'].")";
      }
      $selected = "";
      if ($data['id'] == $_POST['value']) $selected = "selected='selected'";
      echo "<option value='".$data['id']."' $selected title=\"".cleanInputText($output)."\">".
            utf8_substr($output, 0, $_SESSION["glpidropdown_chars_limit"])."</option>";
   }
}

echo "</select>";

?>
