<?php
/*
 * @version $Id$
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

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class 
 **/
class PluginFusinvinventoryImportXML extends CommonDBTM  {

   function __construct() {
      //$this->table = "glpi_plugin_fusinvinventory_agents";
      //$this->type = 'PluginFusioninventoryAgent';
   }


   function showForm() {
      global $DB,$CFG_GLPI,$LANG;

      $target = GLPI_ROOT.'/plugins/fusinvinventory/front/importxml.php';
		echo "<form action='".$target."' method='post' enctype='multipart/form-data'>";

		echo "<br>";
		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th colspan='2'>";
		echo $LANG['plugin_fusinvinventory']['importxml'][0]." :</th></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "</td>";
		echo "<td align='center'>";
		echo "<input type='file' name='importfile' value=''/>";
      echo "&nbsp;<input type='submit' value='".$LANG["buttons"][37]."' class='submit'/>";
		echo "</td>";
		echo "</tr>";

		echo "</table>";

		echo "</form>";
   }


   function importXMLFile($file) {
      $PluginFusinvinventoryInventory = new PluginFusinvinventoryInventory();
      $p_xml = file_get_contents($file);
      $PluginFusinvinventoryInventory->sendCriteria("", "", $p_xml);
   }


   function importXMLContent($p_xml) {
      $PluginFusinvinventoryInventory = new PluginFusinvinventoryInventory();
      $PluginFusinvinventoryInventory->sendCriteria("", "", $p_xml);
   }

}

?>