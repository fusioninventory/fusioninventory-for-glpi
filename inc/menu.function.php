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


function plugin_fusinvsnmp_displayMenu() {
   global $LANG;

   $a_menu = array();
   if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "models", "r")) {
      $a_menu[0]['name'] = $LANG['plugin_fusinvsnmp']["model_info"][4];
      $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_models.png";
      $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/model.php";
   }

   if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity", "r")) {
      $a_menu[1]['name'] = $LANG['plugin_fusinvsnmp']["model_info"][3];
      $a_menu[1]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_authentification.png";
      $a_menu[1]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/configsecurity.php";
   }

   if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "iprange", "r")) {
      $a_menu[2]['name'] = $LANG['plugin_fusinvsnmp']["menu"][2];
      $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_rangeip.png";
      $a_menu[2]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/iprange.php";
   }

   if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "unknowndevices", "r")) {
      $a_menu[3]['name'] = $LANG['plugin_fusinvsnmp']["menu"][4];
      $a_menu[3]['pic']  = GLPI_ROOT."/plugins/fusinvsnmp/pics/menu_unknown_device.png";
      $a_menu[3]['link'] = GLPI_ROOT."/plugins/fusinvsnmp/front/unknowndevice.php";
   }

   return $a_menu;
}

?>