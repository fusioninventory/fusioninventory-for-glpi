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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$NEEDED_ITEMS=array("setup");
if(!defined('GLPI_ROOT')){
	define('GLPI_ROOT', '../../..'); 
}
include (GLPI_ROOT . "/inc/includes.php");

checkRight("profile","w");

//useplugin('tracker');

//$plugin = new Plugin();
//if ($plugin->isInstalled("tracker") && $plugin->isActivated("tracker")) {

if (plugin_tracker_needUpdate() == 1)
{
	commonHeader($LANGTRACKER["setup"][4], $_SERVER["PHP_SELF"],"plugins","tracker");
	echo "<div align='center'>";
	echo "<table class='tab_cadre' cellpadding='5'>";
	echo "<tr><th>".$LANGTRACKER["setup"][3];
	echo "</th></tr>";
	echo "<tr class='tab_bg_1'><td>";
	echo "<a href='plugin_tracker.install.php'>".$LANGTRACKER["setup"][5]."</a></td></tr>";
	echo "</table></div>";
}
else
{
	if(!isset($_SESSION["glpi_plugin_tracker_installed"]) || $_SESSION["glpi_plugin_tracker_installed"]!=1) {

		commonHeader($LANGTRACKER["setup"][4], $_SERVER["PHP_SELF"],"plugins","tracker");
		plugin_tracker_phpextensions();
		if ($_SESSION["glpiactive_entity"]==0){

			if(!TableExists("glpi_plugin_tracker_rangeip")) {

				/* Install */
				echo "<div align='center'>";
				echo "<table class='tab_cadre' cellpadding='5'>";
				echo "<tr><th>".$LANGTRACKER["setup"][3];
				echo "</th></tr>";
				echo "<tr class='tab_bg_1'><td>";
				echo "<a href='plugin_tracker.install.php'>".$LANGTRACKER["setup"][4]."</a></td></tr>";
				echo "</table></div>";
	/*		}elseif (TableExists("glpi_plugin_tracker_config") && !FieldExists("glpi_plugin_tracker_config","logs")) {
				echo "<div align='center'>";
				echo "<table class='tab_cadre' cellpadding='5'>";
				echo "<tr><th>".$LANGTRACKER["setup"][3];
				echo "</th></tr>";
				echo "<tr class='tab_bg_1'><td>";
				echo "<a href='plugin_tracker.install.php'>".$LANGTRACKER["setup"][5]."</a></td></tr>";
				echo "</table></div>";*/
			}

		}else{
			echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
			echo "<b>".$LANGTRACKER["setup"][2]."</b></div>";
		}
	}
	else {
		commonHeader($LANGTRACKER["title"][0],$_SERVER["PHP_SELF"],"plugins","tracker");
		plugin_tracker_phpextensions();
		echo "<div align='center'>";
		echo "<table class='tab_cadre' cellpadding='5'>";
		echo "<tr><th>".$LANGTRACKER["setup"][3];
		echo "</th></tr>";

		/* Profiles */
		if (haveRight("config","w") && haveRight("profile","w")){
		echo "<tr class='tab_bg_1'><td align='center'>";
		echo "<a href=\"./plugin_tracker.profile.php\">".$LANGTRACKER["profile"][0]."</a>";
		echo "</td></tr>";
		}

		/* Fonctionalities */
		echo "<tr class='tab_bg_1'><td align='center'>";
		echo "<a href=\"./plugin_tracker.functionalities.form.php\">".$LANGTRACKER["functionalities"][0]."</a>";
		echo "</td></tr>";

		/* Instructions / FAQ */
		echo "<tr class='tab_bg_1'><td align='center'>";
		echo "<a href='http://glpi-project.org/wiki/doku.php?id=".substr($_SESSION["glpilanguage"],0,2).":plugins:tracker_use' target='_blank'>".$LANGTRACKER["setup"][11]."&nbsp;</a>";
		echo "/&nbsp;<a href='http://glpi-project.org/wiki/doku.php?id=".substr($_SESSION["glpilanguage"],0,2).":plugins:tracker_faq' target='_blank'>".$LANGTRACKER["setup"][12]." </a>";
		echo "</td></tr>";

		/* Models */
/*		echo "<tr class='tab_bg_1'><td align='center'>";
		echo "<a href='http://glpi-project.org/wiki/doku.php?id=wiki:".substr($_SESSION["glpilanguage"],0,2).":plugins:tracker:models' target='_blank'>".$LANGTRACKER["profile"][19]."&nbsp;</a>";
		echo "</td></tr>";
*/
		/* Uninstall */
		if ($_SESSION["glpiactive_entity"]==0){
			echo "<tr class='tab_bg_1'><td align='center'>";
			echo "<a href='plugin_tracker.uninstall.php'>".$LANGTRACKER["setup"][6]."</a>";
			echo " <img src='".$CFG_GLPI["root_doc"]."/pics/aide.png' alt=\"\" onmouseout=\"setdisplay(getElementById('comments'),'none')\" onmouseover=\"setdisplay(getElementById('comments'),'block')\">";
			echo "<span class='over_link' id='comments'>".$LANGTRACKER["setup"][8]."</span>";
			echo "</td></tr>";
		}

		echo "</table>";

		echo "</div>";
	}
}
/*}else{
	commonHeader($LANG["common"][12],$_SERVER['PHP_SELF'],"config","plugins");
	echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
	echo "<b>Please activate the plugin</b></div>";
}*/

commonFooter();


?>