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

if (strpos($_SERVER['PHP_SELF'],"dropdowndefinitionselection.php")) {
   define('GLPI_ROOT','../../..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   header_nocache();
}
if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}
checkCentralAccess();

//
//echo "<script type='text/javascript'>
//var deftype = document.getElementById('".$_POST['defselectadd']."');
//alert(deftype.options[deftype.selectedIndex].text);
// </script>";



echo "<script type='text/javascript'>
var select = document.getElementById('definitionlist');
var obj = document.getElementById('".$_POST['defselectadd']."');
var deftype = document.getElementById('".$_POST['deftypeid']."');

var list = document.getElementById('definitionselection').innerHTML;

var pattern1 = new RegExp(deftype.options[deftype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value + ',');
var pattern2 = new RegExp(deftype.options[deftype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value + '$');
if ((select.value.match(pattern1)) || (select.value.match(pattern2))) {

} else {
   document.getElementById('definitionselection').innerHTML = list + '<br>' + deftype.options[deftype.selectedIndex].text + ' -> ' + obj.options[obj.selectedIndex].text +
   ' <img src=\"".GLPI_ROOT."/pics/delete2.png\" onclick=\'deldef(\"' + deftype.options[deftype.selectedIndex].text + '->' + obj.options[obj.selectedIndex].text + '->' + deftype.options[deftype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value + '\")\'>';
   if (deftype.value !== '0') {
      document.getElementById('definitionlist').value = document.getElementById('definitionlist').value + ',' + deftype.options[deftype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value;
   } else {
      document.getElementById('definitionlist').value = document.getElementById('definitionlist').value + ',' + obj.options[obj.selectedIndex].value;
   }
}

 </script>";

?>