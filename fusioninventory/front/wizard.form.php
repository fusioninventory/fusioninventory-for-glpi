<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
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