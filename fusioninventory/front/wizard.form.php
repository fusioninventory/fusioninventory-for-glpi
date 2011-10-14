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
   Original Author of file: David Durieux
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

require_once GLPI_ROOT."/inc/includes.php";

checkLoginUser();
// ** Used for rules in the wizard

if (!isset($_GET['wizz'])) {
   $a_split = explode("?", $_SERVER['HTTP_REFERER']);
   $a_vars = explode("&", $a_split[1]);
   foreach($a_vars as $vars) {
      $endsplit = explode("=", $vars);
      $_GET[$endsplit[0]] = $endsplit[1];
   }
   $url = $_SERVER['PHP_SELF']."?";
   $i = 0;
   foreach($_GET as $key=>$value) {
      if ($i > 0) {
         $url .= "&";
      }
      $url .= $key."=".$value;
      $i++;
   }
   glpi_header($url);
} else {
   include (GLPI_ROOT . "/plugins/fusioninventory/front/wizard.php");
}

?>