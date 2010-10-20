<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


function plugin_fusinvinventory_displayMenu() {
   global $LANG;

   $a_menu = array();

   $a_menu[0]['name'] = $LANG['plugin_fusinvinventory']["menu"][0];
   $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_importxml.png";
   $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/importxml.php";

   $a_menu[1]['name'] = $LANG['plugin_fusinvinventory']["menu"][1];
   $a_menu[1]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_rules.png";
   $a_menu[1]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/ruleinventory.php";


   return $a_menu;
}

?>