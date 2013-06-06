<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

if (strpos($_SERVER['PHP_SELF'], "dropdownsnmpmodelconstruct_progressbar.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

Session::checkCentralAccess();

echo "<table class='tab_cadre'>
         <tr class='tab_bg_3'>
            <th>".__('Printer cartridges', 'fusioninventory')."</th>
         </tr>";
foreach ($_POST as $cartridge_name => $percentage) {
   echo "<tr class='tab_bg_3'>
            <td>";
      if ($percentage > 100) {
         echo sprintf(__('Problem, have percentage > 100 (%s) for %s'),
                         "<strong>".ceil($percentage)."%</strong>", 
                         "<strong>".$cartridge_name."</strong>");
         echo __('');
      } else {
         PluginFusioninventoryDisplay::bar(ceil($percentage), $cartridge_name, '', 300, 10);
      }
   echo " </td>
         </tr>";
}
echo "</table>";
?>