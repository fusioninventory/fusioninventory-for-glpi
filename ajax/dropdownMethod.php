<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (strpos($_SERVER['PHP_SELF'],"dropdownMethod.php")) {
   define('GLPI_ROOT','../../..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   header_nocache();
}

checkCentralAccess();
$value = 0;
if (isset($_POST['value'])) {
   $value = $_POST['value'];
} else {
   echo "<script type='text/javascript'>
   document.getElementById('selection').value = '';
   document.getElementById('selectionDisplay').length = 0;
   </script>";
}
$PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
$PluginFusioninventoryTaskjob->dropdownSelectionType("selection_type", $_POST['method_id'], $value);

?>