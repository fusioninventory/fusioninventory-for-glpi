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
 * This file is called by ajax function and display dropdown action
 * selection.
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

if (strpos(filter_input(INPUT_SERVER, "PHP_SELF"), "dropdownactionselection.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}
if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}
Session::checkCentralAccess();

echo "<script type='text/javascript'>
var select = document.getElementById('actionlist');
var obj = document.getElementById('".filter_input(INPUT_POST, "actionselectadd")."');
var actiontype = document.getElementById('".filter_input(INPUT_POST, "actiontypeid")."');

var list = document.getElementById('actionselection').innerHTML;

var pattern1 = new RegExp(actiontype.options[actiontype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value + ',');
var pattern2 = new RegExp(actiontype.options[actiontype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value + '$');
if ((select.value.match(pattern1)) || (select.value.match(pattern2))) {

} else {
   document.getElementById('actionselection').innerHTML = list + '<br>' + actiontype.options[actiontype.selectedIndex].text + ' -> ' + obj.options[obj.selectedIndex].text +
   ' <img src=\"".$CFG_GLPI['root_doc']."/pics/delete.png\" onclick=\'delaction(\"' + actiontype.options[actiontype.selectedIndex].text + '->' + obj.options[obj.selectedIndex].text + '->' + actiontype.options[actiontype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value + '\")\'>';
   if (actiontype.value !== '0') {
      document.getElementById('actionlist').value = document.getElementById('actionlist').value + ',' + actiontype.options[actiontype.selectedIndex].value + '->' + obj.options[obj.selectedIndex].value;
   } else {
      document.getElementById('actionlist').value = document.getElementById('actionlist').value + ',' + obj.options[obj.selectedIndex].value;
   }
}

 </script>";

