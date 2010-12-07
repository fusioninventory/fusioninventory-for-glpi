<?php
/*
 * @version $Id: HEADER 1 2010-03-03 21:49 Tsmr $
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
// ----------------------------------------------------------------------
// Original Author of file: NOUH Walid & FONTAN Benjamin & CAILLAUD Xavier
// Purpose of file: plugin order v1.2.0 - GLPI 0.78
// ----------------------------------------------------------------------
 */

if (strpos($_SERVER['PHP_SELF'],"dropdownSelection.php")) {
   define('GLPI_ROOT','../../..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   header_nocache();
}
if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}
checkCentralAccess();

echo "<script type='text/javascript'>
var select = document.getElementById('selectionDisplay');
var obj = document.getElementsByName('selectionList').item(0);
var list = document.getElementById('selection').value;

var pattern1 = new RegExp(document.getElementsByName('itemtype').item(0).value + '-' + obj.value + ',');
var pattern2 = new RegExp(document.getElementsByName('itemtype').item(0).value + '-' + obj.value + '$');
if ((list.match(pattern1)) || (list.match(pattern2))) {

} else {
   select.options[select.options.length] = new Option(obj.options[obj.selectedIndex].text, document.getElementsByName('itemtype').item(0).value + '-' + obj.value);
   if (document.getElementsByName('itemtype').item(0).value !== '0') {
      document.getElementById('selection').value = list + ',' + document.getElementsByName('itemtype').item(0).value + '-' + obj.value;
   } else {
      document.getElementById('selection').value = list + ',' + obj.value;
   }
}

 </script>";

?>