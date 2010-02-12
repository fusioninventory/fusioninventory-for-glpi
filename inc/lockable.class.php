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
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/// Plugin FusionInventory lock class
class PluginFusionInventoryLockable extends CommonDBTM{

	/**
	 * Constructor
	**/
	function __construct () {
		$this->table="glpi_plugin_fusioninventory_lockable";
		$this->type=-1;
	}


	/**
    * Show lockables form.
    *
    *@param $p_target Target file.
    *TODO:  check rights and entity
    *
    *@return nothing (print the form)
    **/
   // si suppr du lockable --> suppr les locks
   function showForm($p_target) {
      global $LANG, $DB, $LINK_ID_TABLE;

      $tableSelect='';
      if (isset($_SESSION["glpi_plugin_fusioninventory_lockable_table"])) {
         $tableId=$_SESSION["glpi_plugin_fusioninventory_lockable_table"];
         if (isset($LINK_ID_TABLE[$tableId])) {
            $tableSelect=$LINK_ID_TABLE[$tableId];
         }
      }

      echo "<form method='post' name='setLockable_form' id='setLockable_form'
                  action='".$p_target."'>";
      echo "<table class='tab_cadre_fixe' cellpadding='2'>";

      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusioninventory']["functionalities"][70]."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][72]." :</td>";
      echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][71]." :</td>";
      echo "<td></td><td>".$LANG['plugin_fusioninventory']['functionalities'][7]."</td>";

      echo "</tr>";

      echo "<tr class='tab_bg_1'>";

      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");

      $options="";

      $query = "SHOW TABLES;";
      $elements=array(0 => '-----');
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $elements[$data[0]]=$data[0];
         }
      }
      $idSelect = 'dropdown_tableSelect'.dropdownArrayValues('tableSelect', $elements, $tableSelect);
      $elements=array();
      echo "</td><td class='right'>";

      echo "<span id='columnsSelect'>&nbsp;";
      if ($tableSelect!='') {
         plugin_fusioninventory_lockable_getColumnSelect($tableSelect);
      }
      echo "</span>\n";

      $params = array('tableSelect' => '__VALUE__');
      ajaxUpdateItemOnSelectEvent($idSelect, 'columnsSelect', GLPI_ROOT."/plugins/fusioninventory/ajax/plugin_fusioninventory.lockable.columns.php", $params);
      ajaxUpdateItemOnSelectEvent($idSelect, 'columnsLockable', GLPI_ROOT."/plugins/fusioninventory/ajax/plugin_fusioninventory.lockable.lockables.php", $params);
      echo "</td><td class='center'>";
      echo "<input type='submit'  class=\"submit\" name='plugin_fusioninventory_lockable_add' value='" . $LANG["buttons"][8] . " >>'>";
      echo "<br /><br />";
      echo "<input type='submit'  class=\"submit\" name='plugin_fusioninventory_lockable_delete' value='<< " . $LANG["buttons"][6] . "'>";
      echo "</td><td class='left'>";
      echo "<span id='columnsLockable'>&nbsp;";
      if ($tableSelect!='') {
         plugin_fusioninventory_lockable_getLockableSelect($tableSelect);
      }
      echo "</span>\n";
      echo "</table></form>";
   }
}

?>
