<?php
/*
 * @version $Id: popup.php 143 2010-10-05 13:34:02Z walid $
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
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

checkLoginUser();

if (isset($_GET["popup"])) {
   $_SESSION["glpipopup"]["name"] = $_GET["popup"];
}

if (isset($_SESSION["glpipopup"]["name"])) {
   switch ($_SESSION["glpipopup"]["name"]) {
      case "test_rule" :
         popHeader($LANG['buttons'][50],$_SERVER['PHP_SELF']);
         include "../../../front/rule.test.php";
         break;

      case "test_all_rules" :
         popHeader($LANG['rulesengine'][84],$_SERVER['PHP_SELF']);
         include "../../../front/rulesengine.test.php";
         break;

      case "show_cache" :
         popHeader($LANG['rulesengine'][100],$_SERVER['PHP_SELF']);
         include "../../../front/rule.cache.php";
         break;
   }
   echo "<div class='center'><br><a href='javascript:window.close()'>".$LANG['buttons'][13]."</a>";
   echo "</div>";
   popFooter();
}

?>