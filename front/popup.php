<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

/*
 * Manage the rule popup.
 */
include ("../../../inc/includes.php");

Session::checkLoginUser();

if (isset($_GET["popup"])) {
   $_SESSION["glpipopup"]["name"] = $_GET["popup"];
}

if (isset($_SESSION["glpipopup"]["name"])) {
   switch ($_SESSION["glpipopup"]["name"]) {
      case "test_rule" :
         Html::popHeader(__('Test'), $_SERVER['PHP_SELF']);
         include "../../../front/rule.test.php";
         break;

      case "test_all_rules" :
         Html::popHeader(__('Test rules engine'), $_SERVER['PHP_SELF']);
         include "../../../front/rulesengine.test.php";
         break;

      case "show_cache" :
         Html::popHeader(__('Cache informations', 'fusioninventory'), $_SERVER['PHP_SELF']);
         include "../../../front/rule.cache.php";
         break;

      case "pluginfusioninventory_networkport_display_options" :
         Html::popHeader(__('Network ports display options', 'fusioninventory'), $_SERVER['PHP_SELF']);
         include "networkport.display.php";
         break;

   }
   echo "<div class='center'><br><a href='javascript:window.close()'>".__('Back')."</a>";
   echo "</div>";
   Html::popFooter();
}

